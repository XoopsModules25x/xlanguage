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

$moduleHandler = xoops_getHandler('module');
$xoopsModule   = XoopsModule::getByDirname('xlanguage');
$moduleInfo    = $moduleHandler->get($xoopsModule->getVar('mid'));
$pathIcon32    = $moduleInfo->getInfo('icons32');

$adminmenu = array();

$i                      = 1;
$adminmenu[$i]['title'] = _MI_XLANGUAGE_ADMENU_HOME;
$adminmenu[$i]['link']  = 'admin/index.php';
$adminmenu[$i]['icon']  = $pathIcon32 . '/home.png';
++$i;
$adminmenu[$i]['title'] = _MI_XLANGUAGE_ADMENU0;
$adminmenu[$i]['link']  = 'admin/main.php';
$adminmenu[$i]['icon']  = $pathIcon32 . '/manage.png';
++$i;
$adminmenu[$i]['title'] = _MI_XLANGUAGE_ADMENU1;
$adminmenu[$i]['link']  = 'admin/main.php?op=add&type=base';
$adminmenu[$i]['icon']  = $pathIcon32 . '/add.png';
++$i;
$adminmenu[$i]['title'] = _MI_XLANGUAGE_ADMENU2;
$adminmenu[$i]['link']  = 'admin/main.php?op=add&type=ext';
$adminmenu[$i]['icon']  = $pathIcon32 . '/insert_table_row.png';
++$i;
$adminmenu[$i]['title'] = _MI_XLANGUAGE_ADMENU3;
$adminmenu[$i]['link']  = 'admin/about.php';
$adminmenu[$i]['icon']  = $pathIcon32 . '/about.png';
// ++$i;
// $adminmenu[$i]['title'] = _MI_XLANGUAGE_ADMENU3;
// $adminmenu[$i]['link'] = "admin/about2.php";
// $adminmenu[$i]['icon']  = $pathIcon32.'/about.png';

