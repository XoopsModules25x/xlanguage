<?php
/**
 * Extended Language
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright       XOOPS Project (http://xoops.org)
 * @license         http://www.gnu.org/licenses/gpl-2.0.html GNU Public License
 * @package         xlanguage
 * @author          trabis <lusopoemas@gmail.com>
 * @version         $Id: core.php 9674 2012-06-19 15:12:44Z beckmi $
 */

// defined('XOOPS_ROOT_PATH') || exit('XOOPS root path not defined');

/**
 * Xlanguage core preloads
 *
 * @copyright       XOOPS Project (http://xoops.org)
 * @license         http://www.gnu.org/licenses/gpl-2.0.html GNU Public License
 * @author          trabis <lusopoemas@gmail.com>
 */
class XlanguageCorePreload extends XoopsPreloadItem
{
    /**
     * @param $args
     */
    public function eventCoreIncludeCommonLanguage($args)
    {
        if (XlanguageCorePreload::isActive()) {
            global $xoopsConfig;
            include_once dirname(__DIR__) . '/api.php';
        }
    }

    /**
     * @return bool
     */
    public function isActive()
    {
        $module_handler =& xoops_getHandler('module');
        $module = $module_handler->getByDirname('xlanguage');

        return ($module && $module->getVar('isactive')) ? true : false;
    }
}
