<?php
// $Id: langform.inc.php 9954 2012-07-31 10:13:35Z beckmi $
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

include XOOPS_ROOT_PATH."/class/xoopsformloader.php";
$sform = new XoopsThemeForm(_AM_XLANG_EDITLANG, "langform", xoops_getenv('PHP_SELF'));

if($isBase){
	$lang_select = new XoopsFormSelect(_AM_XLANG_NAME, 'lang_name', $lang_name);
	$lang_select->addOptionArray($xlanguage_handler->getXoopsLangList());
	$sform->addElement($lang_select, true);
}else{
	$sform->addElement(new XoopsFormText(_AM_XLANG_NAME, 'lang_name', 50, 255, $lang_name), true);
}

$sform->addElement(new XoopsFormText(_AM_XLANG_DESC, 'lang_desc', 50, 255, $lang_desc));

//$sform->addElement(new XoopsFormText(_AM_XLANG_CODE, 'lang_code', 50, 255, $lang_code), true);
$lang_code = new XoopsFormText(_AM_XLANG_CODE, 'lang_code', 50, 255, $lang_code);
$lang_code->setDescription(_AM_XLANG_CODE_DESC);
$sform->addElement($lang_code, true);


//$sform->addElement(new XoopsFormText(_AM_XLANG_CHARSET, 'lang_charset', 50, 255, $lang_charset), true);
$lang_charset = new XoopsFormText(_AM_XLANG_CHARSET, 'lang_charset', 50, 255, $lang_charset);
$lang_charset->setDescription(_AM_XLANG_CHARSET_DESC);
$sform->addElement($lang_charset, true);


if(!$isBase){
	$baseList =& $xlanguage_handler->getAll();
	$base_list = array();
	foreach($baseList as $base => $baselang){
		$base_list[$base] = $base;
	}
	
	$base_select = new XoopsFormSelect(_AM_XLANG_BASE, 'lang_base', $lang_base);
	$base_select->addOptionArray($base_list);
	$sform->addElement($base_select, true);
}
$sform->addElement(new XoopsFormText(_AM_XLANG_WEIGHT, 'weight', 10, 10, $weight));

$image_option_tray = new XoopsFormElementTray(_AM_XLANG_IMAGE, '');
$image_array =& XoopsLists::getImgListAsArray(XOOPS_ROOT_PATH . "/modules/" . $xoopsModule -> dirname() . "/images/");
$lang_image =(empty($lang_image))?'noflag.gif':$lang_image;
$image_select = new XoopsFormSelect('', 'lang_image', $lang_image);
$image_select->addOptionArray($image_array);
$image_select->setExtra("onchange='showImgSelected(\"image\", \"lang_image\", \"/modules/" . $xoopsModule -> dirname() . "/images/\", \"\", \"" . XOOPS_URL . "\")'");
$image_tray = new XoopsFormElementTray('', '&nbsp;');
$image_tray->addElement($image_select);
if (!empty($lang_image)){
    $image_tray->addElement(new XoopsFormLabel('', "<div style='padding: 8px;'><img src='" . XOOPS_URL . "/modules/" . $xoopsModule -> dirname() . "/images/" . $lang_image . "' name='image' id='image' alt='' /></div>"));
}else{
    $image_tray->addElement(new XoopsFormLabel('', "<div style='padding: 8px;'><img src='" . XOOPS_URL . "/images/blank.gif' name='image' id='image' alt='' /></div>"));
}
$image_option_tray->addElement($image_tray);
$sform->addElement($image_option_tray);

if(isset($lang_id)) $sform->addElement(new XoopsFormHidden('lang_id', $lang_id));
$sform->addElement(new XoopsFormHidden('type', $type));

$button_tray = new XoopsFormElementTray('', '');
$button_tray->addElement(new XoopsFormHidden('op', 'save'));
$button_tray->addElement(new XoopsFormButton('', '', _SUBMIT, 'submit'));
$button_tray->addElement(new XoopsFormButton('', '', _CANCEL, 'submit'));
$sform->addElement($button_tray);

$sform->display();