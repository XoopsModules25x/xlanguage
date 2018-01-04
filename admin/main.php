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

use XoopsModules\Xlanguage;

require_once __DIR__ . '/../../../include/cp_header.php';
require_once __DIR__ . '/admin_header.php';

require_once XOOPS_ROOT_PATH . '/modules/xlanguage/include/vars.php';
require_once XOOPS_ROOT_PATH . '/modules/xlanguage/class/Utility.php';

$op = '';
if (isset($_POST)) {
    foreach ($_POST as $k => $v) {
        ${$k} = $v;
    }
}
if (isset($_GET)) {
    foreach ($_GET as $k => $v) {
        ${$k} = $v;
    }
}

define('XLANG_CONFIG_LINK', "<a href='main.php' target='_self'>" . _AM_XLANGUAGE_CONFIG . '</a>');

/** @var \XlanguageLanguageHandler $xlanguageHandler */
$xlanguageHandler = xoops_getModuleHandler('language', 'xlanguage');
$xlanguageHandler->loadConfig();

switch ($op) {
    case 'del':
        if (!isset($_POST['ok']) || 1 != $_POST['ok']) {
            xoops_cp_header();
            $aboutAdmin = \Xmf\Module\Admin::getInstance();
            $adminObject->displayNavigation(basename(__FILE__));
            //            echo "<h4>" . XLANG_CONFIG_LINK . "</h4>";
            xoops_confirm(['op' => 'del', 'type' => $_GET['type'], 'lang_id' => (int)$_GET['lang_id'], 'ok' => 1], 'main.php', _AM_XLANGUAGE_DELETE_CFM);
        } else {
            $isBase = true;
            if (isset($type) && 'ext' === $type) {
                $isBase = false;
            }
            $lang = $xlanguageHandler->get($lang_id, $isBase);
            $xlanguageHandler->delete($lang);
            redirect_header('main.php', 2, _AM_XLANGUAGE_DELETED);
        }
        break;

    case 'save':
        $isBase = true;
        if (isset($type) && 'ext' === $type) {
            $isBase = false;
        }
        if (isset($lang_id) && $lang_id > 0) {
            $lang = $xlanguageHandler->get($lang_id, $isBase);
        } else {
            $lang = $xlanguageHandler->create(true, $isBase);
        }
        $lang_name = preg_replace("/[^a-zA-Z0-9\_\-]/", '', $lang_name);

        $lang->setVar('lang_name', $lang_name);
        $lang->setVar('lang_desc', $lang_desc);
        $lang->setVar('lang_code', $lang_code);
        $lang->setVar('lang_charset', $lang_charset);
        $lang->setVar('lang_image', $lang_image);
        if (!$isBase) {
            $lang->setVar('lang_base', $lang_base);
        }
        $lang->setVar('weight', $weight);
        $xlanguageHandler->insert($lang);
        redirect_header('main.php', 2, _AM_XLANGUAGE_SAVED);
        break;

    case 'edit':
        xoops_cp_header();
        $aboutAdmin = \Xmf\Module\Admin::getInstance();
        $adminObject->displayNavigation(basename(__FILE__));
        // echo "<h4>" . XLANG_CONFIG_LINK . "</h4>";
        // echo "<br>";
        echo '<h4>' . _AM_XLANGUAGE_EDITLANG . '</h4>';
        $isBase = true;
        if (isset($type) && 'ext' === $type) {
            $isBase = false;
        }
        if (isset($lang_id) && $lang_id > 0) {
            $lang = $xlanguageHandler->get($lang_id, $isBase);
        } elseif (isset($lang_name)) {
            $lang = $xlanguageHandler->getByName($lang_name, $isBase);
        } else {
            $lang = $xlanguageHandler->create(true, $isBase);
        }
        $lang_name    = $lang->getVar('lang_name');
        $lang_desc    = $lang->getVar('lang_desc');
        $lang_code    = $lang->getVar('lang_code');
        $lang_charset = $lang->getVar('lang_charset');
        $lang_image   = $lang->getVar('lang_image');
        $weight       = $lang->getVar('weight');
        if (!$isBase) {
            $lang_base = $lang->getVar('lang_base');
        }
        include __DIR__ . '/langform.inc.php';
        break;

    case 'add':
        xoops_cp_header();
        $aboutAdmin = \Xmf\Module\Admin::getInstance();
        //        echo "<h4>" . XLANG_CONFIG_LINK . "</h4>";
        //        echo "<br>";
        //        echo "<h4>" . _AM_XLANGUAGE_ADDLANG . "</h4>";
        if (isset($type) && 'ext' === $type) {
            $isBase = false;
            $adminObject->displayNavigation('main.php?op=add&type=ext');
        } else {
            $isBase = true;
            $adminObject->displayNavigation('main.php?op=add&type=base');
        }
        $lang_name    = '';
        $lang_desc    = '';
        $lang_code    = '';
        $lang_charset = '';
        $lang_image   = '';
        $weight       = 1;
        $lang_base    = '';
        include __DIR__ . '/langform.inc.php';
        break;

    case 'createconfig':
        Xlanguage\Utility::createConfig();
        redirect_header('main.php', 1, _AM_XLANGUAGE_CREATED);

        break;

    case 'default':
    default:
        xoops_cp_header();
        $adminObject = \Xmf\Module\Admin::getInstance();
        $adminObject->displayNavigation(basename(__FILE__));

        // if (TDMDownloads_checkModuleAdmin()) {
        // $adminObject = \Xmf\Module\Admin::getInstance();
        // $adminObject->displayNavigation('downloads.php');
        $adminObject->addItemButton(_MI_XLANGUAGE_ADMENU1, 'main.php?op=add&type=base', 'add');
        $adminObject->addItemButton(_MI_XLANGUAGE_ADMENU2, 'main.php?op=add&type=ext', 'insert_table_row');

        $adminObject->displayButton('left');
        //        }

        //        echo "<h4>" . XLANG_CONFIG_LINK . "</h4>";
        languageList();
        $configfile_status = (@is_readable(XLANGUAGE_CONFIG_FILE)) ? _AM_XLANGUAGE_CONFIGOK : _AM_XLANGUAGE_CONFIGNOTOK;
        echo "<table width='100%' border='0' cellspacing='1' class='outer'><tr><td class=\"odd\"><br>";
        //        echo " - <b><a href='index.php?op=add&amp;type=base'>" . _AM_XLANGUAGE_ADDBASE . "</a></b><br><br>\n";
        //        echo " - <b><a href='index.php?op=add&amp;type=ext'>" . _AM_XLANGUAGE_ADDEXT . "</a></b><br><br>\n";
        echo '<b>' . $configfile_status . '</b>: ' . XLANGUAGE_CONFIG_FILE . " (<a href='main.php?op=createconfig' title='" . _AM_XLANGUAGE_CREATECONFIG . "'>" . _AM_XLANGUAGE_CREATECONFIG . "</a>)<br><br>\n";
        //        echo " - <b><a href='about.php'>" . _AM_XLANGUAGE_ABOUT . "</a></b>";
        echo '</td></tr></table>';
        break;
}
xoops_cp_footer();

function languageList()
{
    global $xlanguageHandler, $xoopsModule;

    global $pathIcon16;

    $lang_list = $xlanguageHandler->getAllList();
    if (is_array($lang_list) && count($lang_list) > 0) {
        echo "<table width='100%' border='0' cellspacing='1' class='outer'><tr><td class=\"odd\">";
        echo "<div style='text-align: center;'><b><h4>" . _AM_XLANGUAGE_LANGLIST . '</h4></b><br>';
        echo "<table class='outer' width='100%' border='0' cellpadding='0' cellspacing='0' ><tr class='bg2'><th align='center'>"
             . _AM_XLANGUAGE_DESC
             . "</th><th align='center'>"
             . _AM_XLANGUAGE_NAME
             . "</th><th align='center'>"
             . _AM_XLANGUAGE_CHARSET
             . "</th><th align='center'>"
             . _AM_XLANGUAGE_CODE
             . "</th><th align='center'>"
             . _AM_XLANGUAGE_IMAGE
             . "</th><th align='center'>"
             . _AM_XLANGUAGE_WEIGHT
             . "</th><th align='center'>"
             . _AM_XLANGUAGE_BASE
             . "</th><th align='center'>"
             . _AM_XLANGUAGE_ACTION
             . "</th></tr>\n";
        $class = 'even';
        foreach (array_keys($lang_list) as $lang_name) {
            $lang     =& $lang_list[$lang_name];
            $isOrphan = true;
            if (isset($lang['base'])) {
                echo "<tr>\n";
                echo "<td class='$class' >" . $lang['base']->getVar('lang_desc') . "</td>\n";
                echo "<td class='$class' ><b>" . $lang['base']->getVar('lang_name') . "</b></td>\n";
                echo "<td class='$class' ><b>" . $lang['base']->getVar('lang_charset') . "</b></td>\n";
                echo "<td class='$class' >" . $lang['base']->getVar('lang_code') . "</td>\n";
                $lang_image = 'noflag.gif';
                if (is_readable(XOOPS_ROOT_PATH . '/modules/xlanguage/assets/images/' . $lang['base']->getVar('lang_image'))) {
                    $lang_image = $lang['base']->getVar('lang_image');
                }
                echo "<td class='$class' ><img src='" . XOOPS_URL . '/modules/xlanguage/assets/images/' . $lang_image . "' alt='" . $lang['base']->getVar('lang_desc') . "'></td>\n";
                echo "<td class='$class' >" . $lang['base']->getVar('weight') . "</td>\n";
                echo "<td class='$class' >&#216;</td>\n";
                echo "<td class='$class' ><a href='main.php?op=edit&amp;type=base&amp;lang_id="
                     . $lang['base']->getVar('lang_id')
                     . "'><img src="
                     . $pathIcon16
                     . '/edit.png title='
                     . _EDIT
                     . "></a>\n"
                     . "<a href='main.php?op=del&amp;type=base&amp;lang_id="
                     . $lang['base']->getVar('lang_id')
                     . "'><img src="
                     . $pathIcon16
                     . '/delete.png title='
                     . _DELETE
                     . "></td>\n";
                echo "</tr>\n";
                $isOrphan = false;
                $class    = ('odd' === $class) ? 'even' : 'odd';
            }
            if (!isset($lang['ext']) || count($lang['ext']) < 1) {
                continue;
            }
            foreach ($lang['ext'] as $ext) {
                echo "<tr>\n";
                echo "<td class='$class' >" . $ext->getVar('lang_desc') . "</td>\n";
                echo "<td class='$class' >" . $ext->getVar('lang_name') . "</td>\n";
                echo "<td class='$class' ><b>" . $ext->getVar('lang_charset') . "</b></td>\n";
                echo "<td class='$class' >" . $ext->getVar('lang_code') . "</td>\n";
                $lang_image = 'noflag.gif';
                if (is_readable(XOOPS_ROOT_PATH . '/modules/xlanguage/assets/images/' . $ext->getVar('lang_image'))) {
                    $lang_image = $ext->getVar('lang_image');
                }
                echo "<td class='$class' ><img src='" . XOOPS_URL . '/modules/xlanguage/assets/images/' . $lang_image . "' alt='" . $ext->getVar('lang_desc') . "'></td>\n";
                echo "<td class='$class' >" . $ext->getVar('weight') . "</td>\n";
                $lang_base = $isOrphan ? "<span style='color:red'>" . $ext->getVar('lang_base') . '</span>' : $ext->getVar('lang_base');
                echo "<td class='$class' ><b>" . $lang_base . "</b></td>\n";
                echo "<td class='$class' ><a href='main.php?op=edit&amp;type=ext&amp;lang_id="
                     . $ext->getVar('lang_id')
                     . "'><img src="
                     . $pathIcon16
                     . '/edit.png title='
                     . _EDIT
                     . "></a>\n"
                     . "<a href='main.php?op=del&amp;type=ext&amp;lang_id="
                     . $ext->getVar('lang_id')
                     . "'><img src="
                     . $pathIcon16
                     . '/delete.png title='
                     . _DELETE
                     . "></td>\n";
                echo "</tr>\n";
            }
            echo "<tr><td colspan='9' ></td></tr>\n";
            $class = ('odd' === $class) ? 'even' : 'odd';
        }

        echo "</table></div>\n";
        echo '</td></tr></table>';
        echo '<br>';
    }
}
