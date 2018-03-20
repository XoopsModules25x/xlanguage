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
 * @copyright       XOOPS Project (https://xoops.org)
 * @license         http://www.gnu.org/licenses/gpl-2.0.html GNU Public License
 * @package         xlanguage
 * @author          trabis <lusopoemas@gmail.com>
 */

// defined('XOOPS_ROOT_PATH') || die('Restricted access');

/**
 * Xlanguage core preloads
 *
 * @copyright       XOOPS Project (https://xoops.org)
 * @license         http://www.gnu.org/licenses/gpl-2.0.html GNU Public License
 * @author          trabis <lusopoemas@gmail.com>
 */
class XlanguageCorePreload extends XoopsPreloadItem
{
    /**
     * @param $args
     */
    public static function eventCoreIncludeCommonLanguage($args)
    {
        global $xoopsConfig;
        require_once __DIR__ . '/../api.php';
    }

    // to add PSR-4 autoloader
    /**
     * @param $args
     */
    public static function eventCoreIncludeCommonEnd($args)
    {
        include __DIR__ . '/autoloader.php';
    }
}
