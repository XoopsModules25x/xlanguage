<?php
// $Id: menu.php 9674 2012-06-19 15:12:44Z beckmi $
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
$module_handler =& xoops_gethandler('module');
$xoopsModule =& XoopsModule::getByDirname('xlanguage');
$moduleInfo =& $module_handler->get($xoopsModule->getVar('mid'));
$pathIcon32 = $moduleInfo->getInfo('icons32');

$adminmenu = array();

$i = 1;
$adminmenu[$i]['title'] = _MI_XLANGUAGE_ADMENU_HOME ;
$adminmenu[$i]['link']  = 'admin/index.php' ;
$adminmenu[$i]['icon']  = $pathIcon32.'/home.png' ;
$i++;
$adminmenu[$i]['title'] = _MI_XLANGUAGE_ADMENU0 ;
$adminmenu[$i]['link']  = 'admin/main.php' ;
$adminmenu[$i]['icon']  = $pathIcon32.'/manage.png' ;
$i++;
$adminmenu[$i]['title'] = _MI_XLANGUAGE_ADMENU1 ;
$adminmenu[$i]['link']  = 'admin/main.php?op=add&type=base' ;
$adminmenu[$i]['icon']  = $pathIcon32.'/add.png' ;
$i++;
$adminmenu[$i]['title'] = _MI_XLANGUAGE_ADMENU2 ;
$adminmenu[$i]['link']  = 'admin/main.php?op=add&type=ext';
$adminmenu[$i]['icon']  = $pathIcon32.'/insert_table_row.png' ;
$i++;
$adminmenu[$i]['title'] = _MI_XLANGUAGE_ADMENU3 ;
$adminmenu[$i]['link']  = 'admin/about.php' ;
$adminmenu[$i]['icon']  = $pathIcon32.'/about.png' ;
// $i++;
// $adminmenu[$i]['title'] = _MI_XLANGUAGE_ADMENU3;
// $adminmenu[$i]['link'] = "admin/about2.php";
// $adminmenu[$i]['icon']  = $pathIcon32.'/about.png';
