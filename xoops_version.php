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

use XoopsModules\Xlanguage\Helper;

require_once __DIR__ . '/preloads/autoloader.php';

$moduleDirName      = basename(__DIR__);
$moduleDirNameUpper = mb_strtoupper($moduleDirName);

$helper = Helper::getInstance();
$helper->loadLanguage('common');

// ------------------- Informations ------------------- //
$modversion = [
    'version'             => '3.1.1',
    'module_status'       => 'Final',
    'release_date'        => '2021/08/13',
    'name'                => _MI_XLANGUAGE_NAME,
    'description'         => _MI_XLANGUAGE_DESC,
    'official'            => 1, //1 indicates official XOOPS module supported by XOOPS Dev Team, 0 means 3rd party supported
    'author'              => 'phppp(D.J.)',
    'credits'             => 'Adi Chiributa - webmaster@artistic.ro; wjue - http://www.wjue.org; GIJOE - http://www.peak.ne.jp, XOOPS Development Team',
    'author_mail'         => 'author-email',
    'author_website_url'  => 'https://xoops.org',
    'author_website_name' => 'XOOPS',
    'license'             => 'GPL 2.0 or later',
    'license_url'         => 'www.gnu.org/licenses/gpl-2.0.html/',
    'help'                => 'page=help',
    // ------------------- Folders & Files -------------------
    'release_info'        => 'Changelog',
    'release_file'        => XOOPS_URL . "/modules/$moduleDirName/docs/changelog.txt",

    'manual'              => 'link to manual file',
    'manual_file'         => XOOPS_URL . "/modules/$moduleDirName/docs/install.txt",
    // images
    'image'               => 'assets/images/logoModule.png',
    'iconsmall'           => 'assets/images/iconsmall.png',
    'iconbig'             => 'assets/images/iconbig.png',
    'dirname'             => $moduleDirName,
    //Frameworks
    //    'dirmoduleadmin'      => 'Frameworks/moduleclasses/moduleadmin',
    //    'sysicons16'          => 'Frameworks/moduleclasses/icons/16',
    //    'sysicons32'          => 'Frameworks/moduleclasses/icons/32',
    // Local path icons
    'modicons16'          => 'assets/images/icons/16',
    'modicons32'          => 'assets/images/icons/32',
    //About
    'demo_site_url'       => 'https://xoops.org',
    'demo_site_name'      => 'XOOPS Demo Site',
    'support_url'         => 'https://xoops.org/modules/newbb/viewforum.php?forum=28/',
    'support_name'        => 'Support Forum',
    'submit_bug'          => 'https://github.com/XoopsModules25x/' . $moduleDirName . '/issues',
    'module_website_url'  => 'www.xoops.org',
    'module_website_name' => 'XOOPS Project',
    // ------------------- Min Requirements -------------------
    'min_php'             => '7.3',
    'min_xoops'           => '2.5.10',
    'min_admin'           => '1.2',
    'min_db'              => ['mysql' => '5.5'],
    // ------------------- Admin Menu -------------------
    'system_menu'         => 1,
    'hasAdmin'            => 1,
    'adminindex'          => 'admin/index.php',
    'adminmenu'           => 'admin/menu.php',
    // ------------------- Main Menu -------------------
    'hasMain'             => 1,

    // ------------------- Install/Update -------------------
    //    'onInstall'           => 'include/oninstall.php',
    'onUpdate'            => 'include/onupdate.php',
    //  'onUninstall'         => 'include/onuninstall.php',
    // -------------------  PayPal ---------------------------
    'paypal'              => [
        'business'      => 'xoopsfoundation@gmail.com',
        'item_name'     => 'Donation : ' . _MI_XLANGUAGE_NAME,
        'amount'        => 0,
        'currency_code' => 'USD',
    ],
    // ------------------- Mysql -----------------------------
    'sqlfile'             => ['mysql' => 'sql/mysql.sql'],
    // ------------------- Tables ----------------------------
    'tables'              => [
        $moduleDirName . '_' . 'base',
        $moduleDirName . '_' . 'ext',
    ],
];

// ------------------- Help files ------------------- //
$modversion['helpsection'] = [
    ['name' => _MI_XLANGUAGE_OVERVIEW, 'link' => 'page=help'],
    ['name' => _MI_XLANGUAGE_DISCLAIMER, 'link' => 'page=disclaimer'],
    ['name' => _MI_XLANGUAGE_LICENSE, 'link' => 'page=license'],
    ['name' => _MI_XLANGUAGE_SUPPORT, 'link' => 'page=support'],
];

// Use smarty
//$modversion['use_smarty'] = 1;

//language selection block
$modversion['blocks'][] = [
    'file'        => 'xlanguage_blocks.php',
    'name'        => _MI_XLANGUAGE_BNAME,
    'description' => '',
    'show_func'   => 'b_xlanguage_select_show',
    'edit_func'   => 'b_xlanguage_select_edit',
    'options'     => 'images| |5',
    'template'    => 'xlanguage_block.tpl',
];

/**
 * Make Sample button visible?
 */
$modversion['config'][] = [
    'name'        => 'displaySampleButton',
    'title'       => 'CO_' . $moduleDirNameUpper . '_' . 'SHOW_SAMPLE_BUTTON',
    'description' => 'CO_' . $moduleDirNameUpper . '_' . 'SHOW_SAMPLE_BUTTON_DESC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 1,
];

/**
 * Show Developer Tools?
 */
$modversion['config'][] = [
    'name'        => 'displayDeveloperTools',
    'title'       => 'CO_' . $moduleDirNameUpper . '_' . 'SHOW_DEV_TOOLS',
    'description' => 'CO_' . $moduleDirNameUpper . '_' . 'SHOW_DEV_TOOLS_DESC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 0,
];
