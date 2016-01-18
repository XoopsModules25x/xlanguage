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
 * @copyright    XOOPS Project (http://xoops.org)
 * @license      {@link http://www.gnu.org/licenses/gpl-2.0.html GNU Public License}
 * @package      xlanguage
 * @since        2.0
 * @author       D.J.(phppp) php_pp@hotmail.com
 * @version      $Id $
 **/

$modversion['name'] = _MI_XLANGUAGE_NAME;
$modversion['version'] = 3.05;
$modversion['description'] = _MI_XLANGUAGE_DESC;
$modversion['credits'] = "Adi Chiributa - webmaster@artistic.ro; wjue - http://www.wjue.org; GIJOE - http://www.peak.ne.jp";
$modversion['author'] = "phppp(D.J.)" ;
$modversion['help'] = "page=help";
$modversion['license'] = "GNU GPL";
$modversion['license_url'] = "www.gnu.org/licenses/gpl-2.0.html";
 $modversion['official'] = 0; //1 indicates supported by XOOPS Dev Team, 0 means 3rd party supported
$modversion['image'] = "xlanguage_logo.png";
$modversion['dirname'] = basename(__DIR__);
$modversion['dirmoduleadmin'] = '/Frameworks/moduleclasses/moduleadmin';
$modversion['icons16'] = '../../Frameworks/moduleclasses/icons/16';
$modversion['icons32'] = '../../Frameworks/moduleclasses/icons/32';

//about
$modversion['release_file'] = XOOPS_URL."/modules/".$modversion['dirname']."/docs/changelog.txt";
$modversion['release_date'] = "2015/09/23";
$modversion["module_website_url"] = "www.xoops.org/";
$modversion["module_website_name"] = "XOOPS";
$modversion["module_status"] = "Beta 1";
$modversion['min_php']='5.5';
$modversion['min_xoops']="2.5.7.2";
$modversion['min_admin']='1.1';
$modversion['min_db']= array('mysql'=>'5.0.7', 'mysqli'=>'5.0.7');

$modversion['system_menu'] = 1;

$modversion['sqlfile']['mysql'] = "sql/mysql.sql";
$modversion['tables'][0] = "xlanguage_base";
$modversion['tables'][1] = "xlanguage_ext";

// Admin things
$modversion['hasAdmin'] = 1;
$modversion['adminindex'] = "admin/index.php";
$modversion['adminmenu'] = "admin/menu.php";

// Use smarty
$modversion["use_smarty"] = 1;

//language selection block
$modversion['blocks'][1]['file'] = "xlanguage_blocks.php";
$modversion['blocks'][1]['name'] = _MI_XLANGUAGE_BNAME;
$modversion['blocks'][1]['description'] = '';
$modversion['blocks'][1]['show_func'] = "b_xlanguage_select_show";
$modversion['blocks'][1]['edit_func'] = "b_xlanguage_select_edit";
$modversion['blocks'][1]['options'] = "images| |5";
$modversion["blocks"][1]["template"] = "xlanguage_block.tpl";

$modversion['releasedate'] = "July 9th, 2006";
$modversion['status'] = "stable";
$modversion['xoopsversion'] = "2.0+";

$modversion['author_website'][1]['url'] = "http://xoops.org.cn";
$modversion['author_website'][1]['name'] = "The Xoops China Community";
$modversion['author_website'][2]['url'] = "http://xoopsforge.com";
$modversion['author_website'][2]['name'] = "XForge";

$modversion['author_email'] = "php_pp@hotmail.com";
$modversion['demo_site_url'] = "http://xoops.org.cn";
$modversion['demo_site_name'] = "Xoops China";
$modversion['support_site_url'] = "http://xoopsforge.com";
$modversion['support_site_name'] = "XForge";
