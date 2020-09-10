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
 * @license      {@link https://www.gnu.org/licenses/gpl-2.0.html GNU Public License}
 * @package      xlanguage
 * @since        2.0
 * @author       D.J.(phppp) php_pp@hotmail.com
 **/

use Xmf\Request;
use XoopsModules\Xlanguage\{
    Helper,
    LanguageHandler,
    Utility
};
/** @var Helper $helper */
/** @var Utility $utility */
/** @var LanguageHandler $languageHandler */

global $xlanguage, $xoopsConfig;
require_once XOOPS_ROOT_PATH . '/modules/xlanguage/include/vars.php';

//$cookie_prefix = preg_replace("/[^a-z_0-9]+/i", "_", preg_replace("/(http(s)?:\/\/)?(www.)?/i","",XOOPS_URL));
$cookie_var = XLANGUAGE_LANG_TAG;
$utility = new Utility();

$xlanguage['action'] = false;
$langTag             = Request::getString(XLANGUAGE_LANG_TAG, '', 'GET');
if (!empty($langTag)) {
    $cookie_path = '/';
    setcookie($cookie_var, $langTag, time() + 3600 * 24 * 30, $cookie_path, '', 0);
    $xlanguage['lang'] = $langTag;
} elseif (!empty($_COOKIE[$cookie_var])) {
    $xlanguage['lang'] = $_COOKIE[$cookie_var];

    /* FIXME: shall we remove it? */

    //    if (preg_match("/[&|\?]\b".XLANGUAGE_LANG_TAG."\b=/i",$_SERVER['REQUEST_URI'])) {
    //    } elseif (strpos($_SERVER['REQUEST_URI'], "?")) {
    //        $_SERVER['REQUEST_URI'] .= "&".XLANGUAGE_LANG_TAG."=".$xlanguage["lang"];
    //    } else {
    //        $_SERVER['REQUEST_URI'] .= "?".XLANGUAGE_LANG_TAG."=".$xlanguage["lang"];
    //    }

} elseif ($lang == $utility::detectLang()) {
    $xlanguage['lang'] = $lang;
} else {
    $xlanguage['lang'] = $xoopsConfig['language'];
}

$helper = Helper::getInstance();
$languageHandler = $helper->getHandler('Language');
$languageHandler->loadConfig();
$lang = $languageHandler->getByName($xlanguage['lang']);
if (is_object($lang) && strcasecmp($lang->getVar('lang_name'), $xoopsConfig['language'])) {
    if ($lang->hasBase()) {
        $xoopsConfig['language'] = $lang->getVar('lang_name');
    } else {
        $lang_base = $languageHandler->getByName($lang->getVar('lang_base'));
        if (is_object($lang_base)) {
            $xlanguage['charset_base'] = $lang_base->getVar('lang_charset');
            $xlanguage['action']       = true;
            $xoopsConfig['language']   = $lang_base->getVar('lang_name');
            unset($lang_base);
        }
    }
    if ($lang->getVar('lang_charset')) {
        $xlanguage['charset'] = $lang->getVar('lang_charset');
    }
    if ($lang->getVar('lang_code')) {
        $xlanguage['code'] = $lang->getVar('lang_code');
    }
}
unset($lang);

$GLOBALS['xlanguageHandler'] = $languageHandler;

if ($xlanguage['action']) {
    //if (CONV_REQUEST && (!empty($_GET)||!empty($_POST))) {
    if (!empty($_POST)) {
        $in_charset  = $xlanguage['charset'];
        $out_charset = $xlanguage['charset_base'];

        //$CONV_REQUEST_array=array("_GET", "_POST");
        $CONV_REQUEST_array = ['_POST'];
        foreach ($CONV_REQUEST_array as $HV) {
            if (!empty(${$HV})) {
                ${$HV} = $utility::convertEncoding(${$HV}, $out_charset, $in_charset);
            }
            $GLOBALS['HTTP' . $HV . '_VARS'] = ${$HV};
        }
    }
    ob_start("XoopsModules\Xlanguage\Utility::encodeCharSet");
} else {
    ob_start("XoopsModules\Xlanguage\Utility::cleanMultiLang");
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
if (!empty($xlanguage_theme_enable)) {
    $options = ['dropdown', ' ', 5]; // display mode, delimitor, number per line
    $utility::showSelectedLanguage($options);
}
