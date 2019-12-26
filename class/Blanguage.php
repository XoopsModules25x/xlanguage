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
 * @license      {@link http://www.gnu.org/licenses/gpl-2.0.html GNU Public License}
 * @package      xlanguage
 * @since        2.0
 * @author       D.J.(phppp) php_pp@hotmail.com
 **/

use XoopsModules\Xlanguage;

//require(XOOPS_ROOT_PATH."/class/xoopslists.php");
//require(XOOPS_ROOT_PATH.'/modules/xlanguage/include/vars.php');
//require(XOOPS_ROOT_PATH.'/modules/xlanguage/class/Utility.php');

/**
 * Class Blanguage
 */
class Blanguage extends \XoopsObject
{
    public $isBase = false;
    public $db;
    public $table;

    /**
     * Blanguage constructor.
     * @param bool $isBase
     */
    public function __construct($isBase = false)
    {
        $this->isBase = $isBase;
        $this->db    = \XoopsDatabaseFactory::getDatabaseConnection();
        $this->table = $this->db->prefix('xlanguage_base');
        $this->initVar('lang_id', XOBJ_DTYPE_INT);
        $this->initVar('weight', XOBJ_DTYPE_INT);
        $this->initVar('lang_name', XOBJ_DTYPE_TXTBOX);
        $this->initVar('lang_desc', XOBJ_DTYPE_TXTBOX);
        $this->initVar('lang_code', XOBJ_DTYPE_TXTBOX);
        $this->initVar('lang_charset', XOBJ_DTYPE_TXTBOX);
        $this->initVar('lang_image', XOBJ_DTYPE_TXTBOX);
    }

    /**
     * @return bool
     */
    public function prepareVars()
    {
        foreach ($this->vars as $k => $v) {
            $cleanv = $this->cleanVars[$k];
            switch ($v['data_type']) {
                case XOBJ_DTYPE_TXTBOX:
                case XOBJ_DTYPE_TXTAREA:
                case XOBJ_DTYPE_SOURCE:
                case XOBJ_DTYPE_EMAIL:
                    $cleanv = $v['changed'] ? $cleanv : '';
                    if (!isset($v['not_gpc']) || !$v['not_gpc']) {
                        $cleanv = $this->db->quoteString($cleanv);
                    }
                    break;
                case XOBJ_DTYPE_INT:
                    $cleanv = $v['changed'] ? (int)$cleanv : 0;
                    break;
                case XOBJ_DTYPE_ARRAY:
                    $cleanv = $v['changed'] ? $cleanv : serialize([]);
                    break;
                case XOBJ_DTYPE_STIME:
                case XOBJ_DTYPE_MTIME:
                case XOBJ_DTYPE_LTIME:
                    $cleanv = $v['changed'] ? $cleanv : 0;
                    break;
                default:
                    break;
            }
            $this->cleanVars[$k] = &$cleanv;
            unset($cleanv);
        }

        return true;
    }

    public function setBase()
    {
        $this->isBase = true;
    }

    /**
     * @return mixed
     */
    public function hasBase()
    {
        return $this->isBase;
    }
}
