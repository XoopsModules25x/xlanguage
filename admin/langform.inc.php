<?php
/**
 * xLanguage module (eXtensible Language Management For XOOPS)
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright    XOOPS Project (https://xoops.org)
 * @license      {@link http://www.gnu.org/licenses/gpl-2.0.html GNU Public License}
 * @package      xlanguage
 * @since        2.0
 * @author       D.J.(phppp) php_pp@hotmail.com
 **/

include XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
$sform = new \XoopsThemeForm(_AM_XLANGUAGE_EDITLANG, 'langform', xoops_getenv('PHP_SELF'), 'post', true);

if ($isBase) {
    $lang_select = new \XoopsFormSelect(_AM_XLANGUAGE_NAME, 'lang_name', $lang_name);
    $lang_select->addOptionArray($xlanguageHandler->getXoopsLangList());
    $sform->addElement($lang_select, true);
} else {
    $sform->addElement(new \XoopsFormText(_AM_XLANGUAGE_NAME, 'lang_name', 50, 255, $lang_name), true);
}

$sform->addElement(new \XoopsFormText(_AM_XLANGUAGE_DESC, 'lang_desc', 50, 255, $lang_desc));

//$sform->addElement(new \XoopsFormText(_AM_XLANGUAGE_CODE, 'lang_code', 50, 255, $lang_code), true);
$lang_code = new \XoopsFormText(_AM_XLANGUAGE_CODE, 'lang_code', 50, 255, $lang_code);
$lang_code->setDescription(_AM_XLANGUAGE_CODE_DESC);
$sform->addElement($lang_code, true);

//$sform->addElement(new \XoopsFormText(_AM_XLANGUAGE_CHARSET, 'lang_charset', 50, 255, $lang_charset), true);
$lang_charset = new \XoopsFormText(_AM_XLANGUAGE_CHARSET, 'lang_charset', 50, 255, $lang_charset);
$lang_charset->setDescription(_AM_XLANGUAGE_CHARSET_DESC);
$sform->addElement($lang_charset, true);

if (!$isBase) {
    $baseList  = $xlanguageHandler->getAll();
    $base_list = [];
    foreach ($baseList as $base => $baselang) {
        $base_list[$base] = $base;
    }

    $base_select = new \XoopsFormSelect(_AM_XLANGUAGE_BASE, 'lang_base', $lang_base);
    $base_select->addOptionArray($base_list);
    $sform->addElement($base_select, true);
}
$sform->addElement(new \XoopsFormText(_AM_XLANGUAGE_WEIGHT, 'weight', 10, 10, $weight));

$image_option_tray = new \XoopsFormElementTray(_AM_XLANGUAGE_IMAGE, '');
$image_array       = XoopsLists::getImgListAsArray(XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->dirname() . '/assets/images/');
$lang_image        = empty($lang_image) ? 'noflag.gif' : $lang_image;
$image_select      = new \XoopsFormSelect('', 'lang_image', $lang_image);
$image_select->addOptionArray($image_array);
$image_select->setExtra("onchange='showImgSelected(\"image\", \"lang_image\", \"/modules/" . $xoopsModule->dirname() . '/assets/images/", "", "' . XOOPS_URL . "\")'");
$image_tray = new \XoopsFormElementTray('', '&nbsp;');
$image_tray->addElement($image_select);
if (!empty($lang_image)) {
    $image_tray->addElement(new \XoopsFormLabel('', "<div style='padding: 8px;'><img src='" . XOOPS_URL . '/modules/' . $xoopsModule->dirname() . '/assets/images/' . $lang_image . "' name='image' id='image' alt=''></div>"));
} else {
    $image_tray->addElement(new \XoopsFormLabel('', "<div style='padding: 8px;'><img src='" . XOOPS_URL . "/images/blank.gif' name='image' id='image' alt=''></div>"));
}
$image_option_tray->addElement($image_tray);
$sform->addElement($image_option_tray);

if (isset($lang_id)) {
    $sform->addElement(new \XoopsFormHidden('lang_id', $lang_id));
}
$sform->addElement(new \XoopsFormHidden('type', $type));

$button_tray = new \XoopsFormElementTray('', '');
$button_tray->addElement(new \XoopsFormHidden('op', 'save'));
$button_tray->addElement(new \XoopsFormButton('', '', _SUBMIT, 'submit'));
$button_tray->addElement(new \XoopsFormButton('', '', _CANCEL, 'submit'));
$sform->addElement($button_tray);

$sform->display();
