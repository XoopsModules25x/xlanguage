<?php
// $Id: api.php 9674 2012-06-19 15:12:44Z beckmi $
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
global $xlanguage;
include_once(XOOPS_ROOT_PATH.'/modules/xlanguage/include/vars.php');
include_once(XOOPS_ROOT_PATH.'/modules/xlanguage/include/functions.php');

//$cookie_prefix = preg_replace("/[^a-z_0-9]+/i", "_", preg_replace("/(http(s)?:\/\/)?(www.)?/i","",XOOPS_URL));
$cookie_var = XLANGUAGE_LANG_TAG;

$xlanguage["action"] = false;
if (!empty($_GET[XLANGUAGE_LANG_TAG])) {
	$cookie_path = "/";
	setcookie($cookie_var, $_GET[XLANGUAGE_LANG_TAG], time()+3600*24*30, $cookie_path, '', 0);
	$xlanguage["lang"] = $_GET[XLANGUAGE_LANG_TAG];
}elseif (!empty($_COOKIE[$cookie_var])) {
	$xlanguage["lang"] = $_COOKIE[$cookie_var];
	/* FIXME: shall we remove it? */
	/*
	if(preg_match("/[&|\?]\b".XLANGUAGE_LANG_TAG."\b=/i",$_SERVER['REQUEST_URI'])){
	}elseif (strpos($_SERVER['REQUEST_URI'], "?")) {
	    $_SERVER['REQUEST_URI'] .= "&".XLANGUAGE_LANG_TAG."=".$xlanguage["lang"];
    }else{
	    $_SERVER['REQUEST_URI'] .= "?".XLANGUAGE_LANG_TAG."=".$xlanguage["lang"];
    }
    */
}elseif($lang = xlanguage_detectLang())	{
	$xlanguage["lang"] = $lang;
}else{
	$xlanguage["lang"] = $xoopsConfig['language'];
}

$xlanguage_handler=& xoops_getmodulehandler('language', 'xlanguage');
$xlanguage_handler->loadConfig();
$lang = $xlanguage_handler->getByName($xlanguage["lang"]);
if(is_object($lang) && strcasecmp($lang->getVar('lang_name'),$xoopsConfig['language'])){
	if($lang->isBase()){
		$xoopsConfig['language'] = $lang->getVar('lang_name');
	}else{
		$lang_base = $xlanguage_handler->getByName($lang->getVar('lang_base'));
		if(is_object($lang_base)){
			$xlanguage['charset_base'] = $lang_base->getVar('lang_charset');
			$xlanguage["action"] = true;
			$xoopsConfig['language'] = $lang_base->getVar('lang_name');
			unset($lang_base);
		}
	}
	if($lang->getVar('lang_charset')) $xlanguage['charset'] = $lang->getVar('lang_charset');
	if($lang->getVar('lang_code')) $xlanguage['code'] = $lang->getVar('lang_code');
}
unset($lang);

$GLOBALS['xlanguage_handler'] =& $xlanguage_handler;

if($xlanguage["action"]){
    //if(CONV_REQUEST && (!empty($_GET)||!empty($_POST))){
    if(!empty($_POST)){
        $in_charset = $xlanguage["charset"];
        $out_charset = $xlanguage["charset_base"];

		//$CONV_REQUEST_array=array("_GET", "_POST");
		$CONV_REQUEST_array=array("_POST");
	    foreach ($CONV_REQUEST_array as $HV){
		    if(!empty(${$HV})) {
				${$HV} = xlanguage_convert_encoding(${$HV}, $out_charset, $in_charset);
		    }
			$GLOBALS["HTTP".$HV."_VARS"] = ${$HV};
		}
	}
	ob_start("xlanguage_encoding");
}else{
	ob_start("xlanguage_ml");
}


/* 
 * hardcoded scripts for language switching in theme html files
 *
 * To use it:
 * 1 set "$xlanguage_theme_enable = true;"
 * 2 config options "$options = array("images", " ", 5); // display mode, delimitor, number per line"; Options for display mode: image - flag; text - text; dropdown - dropdown selection box with text
 * 3 insert "<{$smarty.const.XLANGUAGE_SWITCH_CODE}>" into your theme html anywhere you would like to see it present
 */
$xlanguage_theme_enable = true; 
if(!empty($xlanguage_theme_enable)){
	$options = array("dropdown", " ", 5); // display mode, delimitor, number per line
	xlanguage_select_show($options);
} 
?>
