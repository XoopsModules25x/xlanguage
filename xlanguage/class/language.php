<?php
// $Id: language.php 9988 2012-08-05 05:08:41Z beckmi $
//  ------------------------------------------------------------------------ //
//         Xlanguage: eXtensible Language Management For Xoops               //
//             Copyright (c) 2004 Xoops China Community                      //
//                    <http://www.xoops.org.cn/>                             //
//  ------------------------------------------------------------------------ //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  You may not change or alter any portion of this comment or credits       //
//  of supporting developers from this source code or any supporting         //
//  source code which is considered copyrighted (c) material of the          //
//  original comment or credit authors.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
//  ------------------------------------------------------------------------ //
// Author: D.J.(phppp) php_pp@hotmail.com                                    //
// URL: http://www.xoops.org.cn                                              //
// ------------------------------------------------------------------------- //
//include_once(XOOPS_ROOT_PATH."/class/xoopslists.php");
//include_once(XOOPS_ROOT_PATH.'/modules/xlanguage/include/vars.php');
//include_once(XOOPS_ROOT_PATH.'/modules/xlanguage/include/functions.php');

class Blanguage extends XoopsObject
{
	var $isBase;

    function Blanguage() {
        $this -> db = & XoopsDatabaseFactory::getDatabaseConnection();
        $this -> table = $this -> db -> prefix( "xlanguage_base" );
        $this->initVar('lang_id', XOBJ_DTYPE_INT);
        $this->initVar('weight', XOBJ_DTYPE_INT);
        $this->initVar('lang_name', XOBJ_DTYPE_TXTBOX);
        $this->initVar('lang_desc', XOBJ_DTYPE_TXTBOX);
        $this->initVar('lang_code', XOBJ_DTYPE_TXTBOX);
        $this->initVar('lang_charset', XOBJ_DTYPE_TXTBOX);
        $this->initVar('lang_image', XOBJ_DTYPE_TXTBOX);
    }

    function prepareVars()
    {
        foreach ($this->vars as $k => $v) {
				$cleanv = $this->cleanVars[$k];
                switch ($v['data_type']) {
                case XOBJ_DTYPE_TXTBOX:
                case XOBJ_DTYPE_TXTAREA:
                case XOBJ_DTYPE_SOURCE:
                case XOBJ_DTYPE_EMAIL:
                    $cleanv = ($v['changed'])?$cleanv:'';
                    if (!isset($v['not_gpc']) || !$v['not_gpc']) {
                        $cleanv =$this->db->quoteString($cleanv);
                    }
                    break;
                case XOBJ_DTYPE_INT:
                    $cleanv = ($v['changed'])?intval($cleanv):0;
	                break;
                case XOBJ_DTYPE_ARRAY:
                    $cleanv = ($v['changed'])?$cleanv:serialize(array());
                    break;
                case XOBJ_DTYPE_STIME:
                case XOBJ_DTYPE_MTIME:
                case XOBJ_DTYPE_LTIME:
                    $cleanv = ($v['changed'])?$cleanv:0;
                    break;

                default:
                    break;
                }
            $this->cleanVars[$k] =& $cleanv;
            unset($cleanv);
        }
        return true;
    }

    function setBase()
    {
	    $this->isBase = true;
    }

    function isBase()
    {
	    return $this->isBase;
    }
}


class Xlanguage extends Blanguage
{
    function Xlanguage() {
	    $this->Blanguage();
        $this->table = $this -> db -> prefix( "xlanguage_ext" );
        $this->initVar('lang_base', XOBJ_DTYPE_TXTBOX);
        $this->isBase = false;
    }
}

class XlanguageLanguageHandler extends XoopsObjectHandler
{
	var $cached_config;

	function loadConfig()
	{
	    $this->cached_config =& $this->loadFileConfig();
	}

    function &get($id, $isBase =true)
    {
	    $lang = null;
	    $id = intval($id);
	    if(!$id) {
		    return $lang;
	    }
	    $prefix = ($isBase) ? "xlanguage_base" : "xlanguage_ext";
	    if(isset($this->cached_config[$prefix][$id])) {
		    $array = $this->cached_config[$prefix][$id];
	    }else{
	        $sql = 'SELECT * FROM '.$this->db->prefix($prefix).' WHERE lang_id='.$id;
	        $array = $this->db->fetchArray($this->db->query($sql));
        }
        if(!is_array($array) || count($array)==0){
        	return $lang;
    	}
        $lang =& $this->create(false, $isBase);
        $lang->assignVars($array);
        if($isBase) $lang->isBase = true;
        return $lang;
    }

    function &getByName($name)
    {
	    $lang = null;
	    if(empty($name) || preg_match("/[^a-zA-Z0-9\_\-]/", $name)) {
		    return $lang;
	    }
	    $isBase = false;
	    if(isset($this->cached_config['xlanguage_base'][$name])) {
		    $array = $this->cached_config['xlanguage_base'][$name];
		    $isBase = true;
	    }elseif(isset($this->cached_config['xlanguage_ext'][$name])) {
		    $array = $this->cached_config['xlanguage_ext'][$name];
	    }elseif(!isset($this->cached_config)){
	        $sql = 'SELECT * FROM '.$this->db->prefix('xlanguage_base').' WHERE lang_name=\''.$name.'\'';
	        $result=$this->db->query($sql);
        	$array = $this->db->fetchArray($result);
	        if(!is_array($array) || count($array)==0){
	        	$sql = 'SELECT * FROM '.$this->db->prefix('xlanguage_ext').' WHERE lang_name=\''.$name.'\'';
		        $result=$this->db->query($sql);
	        	$array = $this->db->fetchArray($result);
		        if(!is_array($array) || count($array)==0){
		        	return $lang;
	        	}
        	}else{
		    	$isBase = true;
        	}
        }
        if(empty($array)) {
	        return $lang;
        }
        $lang =& $this->create(false, $isBase);
        $lang->assignVars($array);
        if(!isset($array['lang_base'])) $lang->isBase = true;
        return $lang;
    }

    function &getAll($isBase = true)
    {
	    $prefix = ($isBase) ? "xlanguage_base" : "xlanguage_ext";
        $ret = array();
	    if(isset($this->cached_config[$prefix])) {
		    $array = $this->cached_config[$prefix];
		    foreach($array as $lang_name=>$myrow){
	            $lang =& $this->create(false,$isBase);
	            $lang->assignVars($myrow);
	            $ret[$myrow['lang_name']] = $lang;
	           	unset($lang);
	        }
	    }elseif(!isset($this->cached_config)){
	    	$sql = 'SELECT * FROM '.$this->db->prefix($prefix);
	        $result = $this->db->query($sql);
	        while ($myrow = $this->db->fetchArray($result)) {
	            $lang =& $this->create(false,$isBase);
	            $lang->assignVars($myrow);
	            $ret[$myrow['lang_name']] = $lang;
	           	unset($lang);
	        }
        }
        return $ret;
    }

    function &getAllList()
    {
	    $baseArray = $this->getAll();
	    
	    $extArray = $this->getAll(false);
	    $ret = array();
	    if ( is_array($baseArray) && count( $baseArray ) > 0 ){
	        foreach( $baseArray as $base ) {
		        $ret[$base->getVar('lang_name')]['base'] = $base;
		        unset($base);
	        }
        }
	    if ( is_array($extArray) && count( $extArray ) > 0 ){
	        foreach( $extArray as $ext ) {
		        $ret[$ext->getVar('lang_base')]['ext'][] = $ext;
		        unset($ext);
	        }
        }
        return $ret;
    }

    function &create($isNew = true, $isBase = true)
    {
        if($isBase) {
	        $lang = new Blanguage();
	        $lang->isBase = true;
        }
        else $lang = new Xlanguage();
        if ($isNew) $lang->setNew();
        return $lang;
    }

    function insert(&$lang)
    {
        if (!$lang->isDirty())  return true;
        if (!$lang->cleanVars()) return false;
        $lang->prepareVars();
        foreach ($lang->cleanVars as $k => $v) {
            ${$k} = $v;
        }

      	if ( $lang->isNew() ) {
	        if($lang->isBase){
	        	$var_array = array("lang_id", "weight", "lang_name", "lang_desc", "lang_code", "lang_charset", "lang_image");
	    	}else{
	        	$var_array = array("lang_id", "weight", "lang_name", "lang_desc", "lang_code", "lang_charset", "lang_image", "lang_base");
	    	}
            $lang_id = $this->db->genId($lang->table."_lang_id_seq");
            foreach($var_array as $var){
	            $val_array[] = ${$var};
            }
            $sql = "INSERT INTO ".$lang->table." (".implode(",",$var_array).") VALUES (".implode(",", $val_array).")";
          	if ( !$result = $this->db->queryF($sql) ) {
                xoops_error( "Insert language error:".$sql );
                return false;
            }
            if ( $lang_id == 0 ) $lang_id = $this->db->getInsertId();
      		$lang->setVar('lang_id', $lang_id);
        }else{
	        if($lang->isBase){
	        	$var_array = array("weight", "lang_name", "lang_desc", "lang_code", "lang_charset", "lang_image");
	    	}else{
	        	$var_array = array("weight", "lang_name", "lang_desc", "lang_code", "lang_charset", "lang_image", "lang_base");
	    	}
	        $set_array = array();
	        foreach($var_array as $var){
		        $set_array[] = "$var = ".${$var};
	        }
	        $set_string = implode(',', $set_array);
			$sql = "UPDATE ".$lang->table." SET ".$set_string." WHERE lang_id = ".$lang->getVar('lang_id');
            if ( !$result = $this->db->queryF($sql) ) {
            	xoops_error( "update language error:".$sql );
                return false;
            }
        }
        $this->createConfig();
        return $lang->getVar('lang_id');
    }

    function delete(&$lang)
    {
	    if(!is_object($lang) || !$lang->getVar('lang_id')) return true;
        $sql = "DELETE FROM ".$lang->table." WHERE lang_id= ". $lang->getVar('lang_id');
        if (!$result = $this->db->query($sql)) {
            return false;
        }
        $this->createConfig();
        return true;
    }


	function getXoopsLangList()
	{
		return XoopsLists::getLangList();
	}
	

	function createConfig()
	{
		$file_config = XLANGUAGE_CONFIG_FILE;
		@unlink($file_config);
		if(!$fp = fopen($file_config, 'w')) {
			echo "<br> the config file can not be created: ".$file_config;
			return false;
		}
	
		$file_content = "<?php";
		unset($this->cached_config);
	    $baseArray = $this->getAll();
	    if ( is_array($baseArray) && count( $baseArray ) > 0 ){
			$file_content .= "\n	\$".XLANGUAGE_CONFIG_VAR."['xlanguage_base'] = array(";
	        foreach( $baseArray as $lang ) {
				$file_content .= "\n		\"".$lang->getVar('lang_name')."\"=>array(";
                $file_content .= "\n			\"lang_id\"=>".$lang->getVar('lang_id').",";
                $file_content .= "\n			\"weight\"=>".$lang->getVar('weight').",";
				$file_content .= "\n			\"lang_name\"=>\"".$lang->getVar('lang_name')."\",";
				$file_content .= "\n			\"lang_desc\"=>\"".$lang->getVar('lang_desc')."\",";
				$file_content .= "\n			\"lang_code\"=>\"".$lang->getVar('lang_code')."\",";
                $file_content .= "\n			\"lang_charset\"=>\"".$lang->getVar('lang_charset')."\",";
				$file_content .= "\n			\"lang_image\"=>\"".$lang->getVar('lang_image')."\"";
				$file_content .= "\n		),";
	        }
			$file_content .= "\n	);";
	    }
	
	    $extArray = $this->getAll(false);
	    if ( is_array($extArray) && count( $extArray ) > 0 ){
			$file_content .= "\n	\$".XLANGUAGE_CONFIG_VAR."['xlanguage_ext'] = array(";
	        foreach( $extArray as $lang ) {
				$file_content .= "\n		\"".$lang->getVar('lang_name')."\"=>array(";
                $file_content .= "\n			\"lang_id\"=>".$lang->getVar('lang_id').",";
            	$file_content .= "\n			\"weight\"=>".$lang->getVar('weight').",";
				$file_content .= "\n			\"lang_name\"=>\"".$lang->getVar('lang_name')."\",";
				$file_content .= "\n			\"lang_desc\"=>\"".$lang->getVar('lang_desc')."\",";
				$file_content .= "\n			\"lang_code\"=>\"".$lang->getVar('lang_code')."\",";
                $file_content .= "\n			\"lang_charset\"=>\"".$lang->getVar('lang_charset')."\",";
				$file_content .= "\n			\"lang_image\"=>\"".$lang->getVar('lang_image')."\",";
				$file_content .= "\n			\"lang_base\"=>\"".$lang->getVar('lang_base')."\"";
				$file_content .= "\n		),";
	        }
			$file_content .= "\n	);";
	    }
	
		$file_content .= "\n?>";
		fputs($fp,$file_content);
		fclose($fp);
		return true;
	}
	
	function &loadFileConfig()
	{
		$file_config = XLANGUAGE_CONFIG_FILE;
		if(!file_exists($file_config)) $this->createConfig();
		if(!is_readable($file_config)) {
			$config = null;
			return $config;
		}else{
			include $file_config;
			return ${XLANGUAGE_CONFIG_VAR};
		}
	}
	
}
?>