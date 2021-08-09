<?php

namespace XoopsModules\Xlanguage;

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

use XoopsModules\Xlanguage;



//require(XOOPS_ROOT_PATH."/class/xoopslists.php");
//require XOOPS_ROOT_PATH.'/modules/xlanguage/include/vars.php';
//require XOOPS_ROOT_PATH.'/modules/xlanguage/class/Utility.php';

/**
 * Class Language
 */
class Language extends Xlanguage\Blanguage
{
    public $lang_base;

    /**
     * Language constructor.
     * @param bool $isBase
     */
    public function __construct($isBase = false)
    {
        parent::__construct($isBase);
        $this->table = $this->db->prefix('xlanguage_ext');
        $this->initVar('lang_base', \XOBJ_DTYPE_TXTBOX);
    }
}
