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

use Xmf\Module\Admin;
use Xmf\Module\Helper;

// defined('XOOPS_ROOT_PATH') || exit('Restricted access.');

//$path = dirname(dirname(dirname(__DIR__)));
//require_once $path . '/mainfile.php';

$moduleDirName = basename(dirname(__DIR__));

if (false !== ($moduleHelper = Helper::getHelper($moduleDirName))) {
} else {
    $moduleHelper = Helper::getHelper('system');
}
$pathIcon32    = Admin::menuIconPath('');
$pathModIcon32 = $moduleHelper->getModule()->getInfo('modicons32');

xoops_loadLanguage('modinfo', $moduleDirName);

$adminmenu[] = [
    'title' => _MI_XLANGUAGE_ADMENU_HOME,
    'link'  => 'admin/index.php',
    'icon'  => $pathIcon32 . '/home.png',
];

$adminmenu[] = [
    'title' => _MI_XLANGUAGE_ADMENU0,
    'link'  => 'admin/main.php',
    'icon'  => $pathIcon32 . '/manage.png',
];

$adminmenu[] = [
    'title' => _MI_XLANGUAGE_ADMENU1,
    'link'  => 'admin/main.php?op=add&type=base',
    'icon'  => $pathIcon32 . '/add.png',
];

$adminmenu[] = [
    'title' => _MI_XLANGUAGE_ADMENU2,
    'link'  => 'admin/main.php?op=add&type=ext',
    'icon'  => $pathIcon32 . '/insert_table_row.png',
];

$adminmenu[] = [
    'title' => _MI_XLANGUAGE_ADMENU3,
    'link'  => 'admin/about.php',
    'icon'  => $pathIcon32 . '/about.png',
];

//$adminmenu[] = [
// 'title' =>  _MI_XLANGUAGE_ADMENU3,
// 'link' =>  "admin/about2.php",
// 'icon' =>  $pathIcon32.'/about.png',
//];

