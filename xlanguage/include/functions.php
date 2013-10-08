<?php
// $Id: functions.php 9954 2012-07-31 10:13:35Z beckmi $
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

function xlanguage_convert_encoding($value, $out_charset, $in_charset)
{
	if (is_array($value)) {
		foreach($value as $key => $val){
		 	$value[$key] = xlanguage_convert_encoding($val, $out_charset, $in_charset);
	 	}
	}
	else {
		$value = xlanguage_convert_item($value, $out_charset, $in_charset);
	}
	return $value;
}

function xlanguage_convert_item($value, $out_charset, $in_charset)
{
	if(strtolower($in_charset) == strtolower($out_charset)) {
		return $value;
	}
	$xconv_handler = @xoops_getmodulehandler('xconv', 'xconv', true);
	if(is_object($xconv_handler) &&
		$converted_value = @$xconv_handler->convert_encoding($value, $out_charset, $in_charset)
	){
		return $converted_value;
	}
	if(XOOPS_USE_MULTIBYTES && function_exists('mb_convert_encoding')) {
		$converted_value = @mb_convert_encoding($value, $out_charset, $in_charset);
	}elseif(function_exists('iconv')) {
		$converted_value = @iconv($in_charset, $out_charset, $value);
	}
	$value = empty($converted_value) ? $value : $converted_value;

	return $value;
}

function xlanguage_createConfig()
{
	$xlang_handler=& xoops_getmodulehandler('language', 'xlanguage');
	return $xlang_handler->createConfig();
}

function &xlanguage_loadConfig()
{
	$xlang_handler=& xoops_getmodulehandler('language', 'xlanguage');
	$config =& $xlang_handler->loadFileConfig();
	return $config;
}

/**
 * Analyzes some PHP environment variables to find the most probable language
 * that should be used
 *
 * @param string $ string to analyze
 * @param integer $ type of the PHP environment variable which value is $str
 * @global array    the list of available translations
 * @global string   the retained translation keyword
 * @access private
 */
function xlanguage_lang_detect($str = '', $envType = '')
{
    global $available_languages;
	$lang='';
 
 if(!empty($available_languages))   {
    foreach ($available_languages AS $key => $value) {
        // $envType =  1 for the 'HTTP_ACCEPT_LANGUAGE' environment variable,
        //             2 for the 'HTTP_USER_AGENT' one
        $expr = $value[0];
        if (strpos($expr, '[-_]') === FALSE) {
            $expr = str_replace('|', '([-_][[:alpha:]]{2,3})?|', $expr);
        }
//        if (($envType == 1 && eregi('^(' . $expr . ')(;q=[0-9]\\.[0-9])?$', $str))
//            || ($envType == 2 && eregi('(\(|\[|;[[:space:]])(' . $expr . ')(;|\]|\))', $str))) {
        if (($envType == 1 && preg_match('#^(' . $expr . ')(;q=[0-9]\\.[0-9])?$#i', $str))
            || ($envType == 2 && preg_match('#(\(|\[|;[[:space:]])(' . $expr . ')(;|\]|\))#i', $str))) {
            $lang     = $key;
            //if($lang != 'en')
            break;
        }
    }
	}
    return $lang;
}

function xlanguage_detectLang()
{
	global $available_languages,$_SERVER;

	if (!empty($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
	    $HTTP_ACCEPT_LANGUAGE = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
	}

	if (!empty($_SERVER['HTTP_USER_AGENT'])) {
	    $HTTP_USER_AGENT = $_SERVER['HTTP_USER_AGENT'];
	}

	$lang = '';
	$xoops_lang ='';
	// 1. try to findout user's language by checking its HTTP_ACCEPT_LANGUAGE
	// variable
	if (empty($lang) && !empty($HTTP_ACCEPT_LANGUAGE)) {
	    $accepted = explode(',', $HTTP_ACCEPT_LANGUAGE);
	    $acceptedCnt = count($accepted);
	    reset($accepted);
	    for ($i = 0; $i < $acceptedCnt; $i++) {
	        $lang = xlanguage_lang_detect($accepted[$i], 1);
            if(strncasecmp($lang,'en',2)){
	            break;
            }
	    }
	}
	// 2. try to findout user's language by checking its HTTP_USER_AGENT variable
	if (empty($lang) && !empty($HTTP_USER_AGENT)) {
	    $lang = xlanguage_lang_detect($HTTP_USER_AGENT, 2);
	}
	// 3. If we catch a valid language, configure it
	if (!empty($lang)) {
	    $xoops_lang = $available_languages[$lang][1];
	}
	return $xoops_lang;
}

function xlanguage_encoding($output){
	global $xlanguage;
	$output = xlanguage_ml($output);
	// escape XML doc
	if(preg_match("/^\<\?[\s]?xml[\s]+version=([\"'])[^\>]+\\1[\s]+encoding=([\"'])[^\>]+\\2[\s]?\?\>/i", $output)){
		return $output;
	}
    $in_charset = $xlanguage["charset_base"];
    $out_charset = $xlanguage["charset"];
    
	return $output = xlanguage_convert_encoding($output, $out_charset, $in_charset);
}

function xlanguage_ml($s)
{
	global $xoopsConfig;
	global $xlanguage_langs;
	
	if(!isset($xlanguage_langs)){
		$langs =& $GLOBALS["xlanguage_handler"]->getAll(true);
		foreach( array_keys($langs) as $_lang ) {
			$xlanguage_langs[$_lang] = $langs[$_lang]->getVar("lang_code");
		}
		unset($langs);
	}
	if(empty($xlanguage_langs) || count($xlanguage_langs) ==0) return $s;
	
	// escape brackets inside of <code>...</code>
	$patterns[] = '/(\<code>.*\<\/code>)/isU';
	
	// escape brackets inside of <input type="..." value="...">
	$patterns[] = '/(\<input\b(?![^\>]*\btype=([\'"]?)(submit|image|reset|button))[^\>]*\>)/isU';
		
	// escape brackets inside of <textarea></textarea>
	$patterns[] = '/(\<textarea\b[^>]*>[^\<]*\<\/textarea>)/isU';
	
	$s = preg_replace_callback( $patterns , 'xlanguage_ml_escape_bracket' , $s ) ;
		
		
	// create the pattern between language tags
	$pqhtmltags = explode( ',' , preg_quote( XLANGUAGE_TAGS_RESERVED , '/' ) ) ;
	$mid_pattern = '(?:(?!(' . implode( '|' , $pqhtmltags ) . ')).)*' ;
	
	$patterns = array();
	$replaces = array();
	/* */
	if(isset($xlanguage_langs[$xoopsConfig['language']])) {
		$lang = $xlanguage_langs[$xoopsConfig['language']];
		$patterns[] = '/(\[([^\]]*\|)?'.preg_quote($lang).'(\|[^\]]*)?\])('.$mid_pattern.')(\[\/([^\]]*\|)?'.preg_quote($lang).'(\|[^\]]*)?\])/isU';
		$replaces[] = '$4';
	}
	/* */
	foreach( array_keys($xlanguage_langs) as $_lang ) {
		if($_lang == @$xoopsConfig['language']) continue;
		$name = $xlanguage_langs[$_lang];
		$patterns[] = '/(\[([^\]]*\|)?'.preg_quote($name).'(\|[^\]]*)?\])('.$mid_pattern.')(\[\/([^\]]*\|)?'.preg_quote($name).'(\|[^\]]*)?(\]\<br[\s]?[\/]?\>|\]))/isU';
		$replaces[] = '';
	}
	if(!empty($xoopsConfig['language'])){
		$s = preg_replace( '/\[[\/]?[\|]?'.preg_quote($xoopsConfig['language']).'[\|]?\](\<br \/\>)?/i' , '' , $s ) ;
	}
	if(count($replaces)>0){
		$s = preg_replace( $patterns , $replaces , $s ) ;
	}

	return $s ;
}

function xlanguage_ml_escape_bracket( $matches )
{
	global $xlanguage_langs;
	$ret = $matches[1];
	if(!empty($xlanguage_langs)) {
		$pattern = '/(\[([\/])?('.implode("|",array_map("preg_quote", array_values($xlanguage_langs))).')([\|\]]))/isU';
		$ret = preg_replace($pattern, "&#91;\\2\\3\\4", $ret);
	}
	return $ret;
}


function xlanguage_select_show($options = null)
{
	include_once XOOPS_ROOT_PATH."/modules/xlanguage/blocks/xlanguage_blocks.php";
	if(empty($options)){
		$options[0] = "images";		// display style: image, text, select
		$options[1] = " ";			// delimitor
		$options[2] = 5; 			// items per line
	}
	
	$block = b_xlanguage_select_show($options);
	$block["tag"] = "xlanguage";
	
	$content = "";
	$i = 1;
 if(!empty($block["display"]))   { //mb
	if(in_array($block["display"], array("images", "text"))){
		foreach($block["languages"] as $name => $lang){
			$content .= "<a href=\"".$block["url"].$lang["name"]."\" title=\"".$lang["desc"]."\">";
			if($block["display"] == "images"){
				$content .= "<img src=\"".$lang["image"]."\" alt=\"".$lang["desc"]."\"";
				if($block["selected"] != $lang["name"]){
					$content .= " style=\"MozOpacity: .8; opacity: .8; filter:Alpha(opacity=80);\"";
				}
				$content .= "/>";
			}else{
				$content .= $lang["desc"];
			}
			$content .= "</a>";
			if( ( $i++ % $block["number"] ) == 0){
				$content .= "<br />";
			}
		}
	}else{
		$content .= "<select name=\"".$block["tag"]."\"
				onChange=\"if(this.options[this.selectedIndex].value.length >0 )	{ window.document.location=this.options[this.selectedIndex].value;}\"
				>";
 if(!empty($block["languages"]))   { //mb
				foreach($block["languages"] as $name => $lang){
			$content .= "<option value=\"".$block["url"].$lang["name"]."\"";
			if($block["selected"] == $lang["name"]){
				$content .= " selected ";
			}
			$content .= "/>".$lang["desc"]."</option>";
		}
		}
		$content .= "</select>";
	}
	}

	define("XLANGUAGE_SWITCH_CODE", $content);
	return true;
}