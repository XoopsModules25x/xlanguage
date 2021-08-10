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

use XoopsLists;
use XoopsModules\Xlanguage;
use XoopsObjectHandler;

















/**
 * Class LanguageHandler
 */
class LanguageHandler extends XoopsObjectHandler
{
    public $cachedConfig;

    public function loadConfig()
    {
        $this->cachedConfig = $this->loadFileConfig();
    }

    /**
     * @param int  $id
     * @param bool $isBase
     *
     * @return Blanguage|Language|null
     */
    public function get($id, $isBase = true)
    {
        $array = [];
        $lang  = null;
        $id    = (int)$id;
        if (!$id) {
            return $lang;
        }
        $prefix = $isBase ? 'xlanguage_base' : 'xlanguage_ext';
        if (isset($this->cachedConfig[$prefix][$id])) {
            $array = $this->cachedConfig[$prefix][$id];
        } else {
            $sql = 'SELECT * FROM ' . $this->db->prefix($prefix) . ' WHERE lang_id=' . $id;
            /** @var \mysqli_result|false $result */
            $result = $this->db->query($sql);
            if ($result) {
                $array = $this->db->fetchArray($result);
            }
        }
        if (!\is_array($array) || 0 == \count($array)) {
            return $lang;
        }
        $lang = $this->create(false, $isBase);
        $lang->assignVars($array);
        if ($isBase) {
            $lang->isBase = true;
        }

        return $lang;
    }

    /**
     * @param string $name
     * @param bool   $isBase
     *
     * @return Blanguage|Language|null
     */
    public function getByName($name, $isBase = false)
    {
        $array = [];
        $lang  = null;
        if (empty($name) || \preg_match('/[^a-zA-Z0-9\_\-]/', $name)) {
            return $lang;
        }

        if (isset($this->cachedConfig['xlanguage_base'][$name])) {
            $array  = $this->cachedConfig['xlanguage_base'][$name];
            $isBase = true;
        } elseif (isset($this->cachedConfig['xlanguage_ext'][$name])) {
            $array = $this->cachedConfig['xlanguage_ext'][$name];
        } elseif (!isset($this->cachedConfig)) {
            $sql    = 'SELECT * FROM ' . $this->db->prefix('xlanguage_base') . ' WHERE lang_name=\'' . $name . '\'';
            $result = $this->db->query($sql);
            $array  = $this->db->fetchArray($result);
            if (!\is_array($array) || 0 == \count($array)) {
                $sql    = 'SELECT * FROM ' . $this->db->prefix('xlanguage_ext') . ' WHERE lang_name=\'' . $name . '\'';
                $result = $this->db->query($sql);
                $array  = $this->db->fetchArray($result);
                if (!\is_array($array) || 0 == \count($array)) {
                    return $lang;
                }
            } else {
                $isBase = true;
            }
        }
        if (empty($array)) {
            return $lang;
        }
        $lang = $this->create(false, $isBase);
        $lang->assignVars($array);
        if (!isset($array['lang_base'])) {
            $lang->isBase = true;
        }

        return $lang;
    }

    /**
     * @param bool $isBase
     *
     * @return array|false
     */
    public function getAll($isBase = true)
    {
        $prefix = $isBase ? 'xlanguage_base' : 'xlanguage_ext';
        $ret    = [];
        if (isset($this->cachedConfig[$prefix])) {
            $array = $this->cachedConfig[$prefix];
            foreach ($array as $lang_name => $myrow) {
                $lang = $this->create(false, $isBase);
                $lang->assignVars($myrow);
                $ret[$myrow['lang_name']] = &$lang;
                unset($lang);
            }
        } elseif (!isset($this->cachedConfig)) {
            //        } elseif (false === $this->cachedConfig) {
            $sql = 'SELECT * FROM ' . $this->db->prefix($prefix);
            /** @var \mysqli_result|false $result */
            $result = $this->db->query($sql);
            if (!$result) {
                return false;
            }
            while (false !== ($myrow = $this->db->fetchArray($result))) {
                $lang = $this->create(false, $isBase);
                $lang->assignVars($myrow);
                $ret[$myrow['lang_name']] = &$lang;
                unset($lang);
            }
        }

        return $ret;
    }

    /**
     * @return array
     */
    public function getAllList()
    {
        $baseArray = $this->getAll();

        $extArray = $this->getAll(false);
        $ret      = [];
        if ($baseArray && \is_array($baseArray)) {
            foreach ($baseArray as $base) {
                $ret[$base->getVar('lang_name')]['base'] = $base;
                unset($base);
            }
        }
        if ($extArray && \is_array($extArray)) {
            foreach ($extArray as $ext) {
                $ret[$ext->getVar('lang_base')]['ext'][] = $ext;
                unset($ext);
            }
        }

        return $ret;
    }

    /**
     * @param bool $isNew
     * @param bool $isBase
     *
     * @return Blanguage|Language
     */
    public function create($isNew = true, $isBase = true)
    {
        if ($isBase) {
            $lang = new Xlanguage\Blanguage($isBase);
        } else {
            $lang = new Xlanguage\Language();
        }
        if ($isNew) {
            $lang->setNew();
        }

        return $lang;
    }

    /**
     * @param \XoopsObject|Blanguage|Language $lang
     * @return bool|string|array
     * @internal param object $lang
     */
    public function insert($lang)
    {
        $val_array = [];
        if (!$lang->isDirty()) {
            return true;
        }
        if (!$lang->cleanVars()) {
            return false;
        }
        $lang->prepareVars();
        foreach ($lang->cleanVars as $k => $v) {
            ${$k} = $v;
        }

        if ($lang->isNew()) {
            $var_array = [
                'lang_id',
                'weight',
                'lang_name',
                'lang_desc',
                'lang_code',
                'lang_charset',
                'lang_image',
                'lang_base',
            ];
            if ($lang->isBase) {
                $var_array = [
                    'lang_id',
                    'weight',
                    'lang_name',
                    'lang_desc',
                    'lang_code',
                    'lang_charset',
                    'lang_image',
                ];
            }
            $lang_id = $this->db->genId($lang->table . '_lang_id_seq');
            foreach ($var_array as $var) {
                $val_array[] = ${$var};
            }
            $sql = 'INSERT INTO ' . $lang->table . ' (' . \implode(',', $var_array) . ') VALUES (' . \implode(',', $val_array) . ')';
            if (!$result = $this->db->queryF($sql)) {
                \xoops_error('Insert language error:' . $sql);

                return false;
            }
            if (0 == $lang_id) {
                $lang_id = $this->db->getInsertId();
            }
            $lang->setVar('lang_id', $lang_id);
        } else {
            $var_array = [
                'weight',
                'lang_name',
                'lang_desc',
                'lang_code',
                'lang_charset',
                'lang_image',
                'lang_base',
            ];
            if ($lang->isBase) {
                $var_array = ['weight', 'lang_name', 'lang_desc', 'lang_code', 'lang_charset', 'lang_image'];
            }
            $set_array = [];
            foreach ($var_array as $var) {
                $set_array[] = "$var = " . ${$var};
            }
            $set_string = \implode(',', $set_array);
            $sql        = 'UPDATE ' . $lang->table . ' SET ' . $set_string . ' WHERE lang_id = ' . $lang->getVar('lang_id');
            if (!$result = $this->db->queryF($sql)) {
                \xoops_error('update language error:' . $sql);

                return false;
            }
        }
        $this->createConfig();

        return $lang->getVar('lang_id');
    }

    /**
     * @param \XoopsObject|Blanguage|Language $lang
     * @return bool
     * @internal param object $lang
     */
    public function delete($lang)//delete(&$lang)
    {
        if (!\is_object($lang) || !$lang->getVar('lang_id')) {
            return true;
        }
        $sql = 'DELETE FROM ' . $lang->table . ' WHERE lang_id= ' . $lang->getVar('lang_id');
        if (!$result = $this->db->query($sql)) {
            return false;
        }
        $this->createConfig();

        return true;
    }

    /**
     * @return array
     */
    public function getXoopsLangList()
    {
        return XoopsLists::getLangList();
    }

    /**
     * @return bool
     */
    public function createConfig()
    {
        $file_config = \XLANGUAGE_CONFIG_FILE;
        if (\is_file($file_config)) {
            \unlink($file_config);
        }
        if (!$fp = \fopen($file_config, 'wb')) {
            echo '<br> the config file can not be created: ' . $file_config;

            return false;
        }

        $file_content = '<?php';
        unset($this->cachedConfig);
        $baseArray = $this->getAll();
        if ($baseArray && \is_array($baseArray)) {
            $file_content .= "\n    \$" . \XLANGUAGE_CONFIG_VAR . "['xlanguage_base'] = array(";
            foreach ($baseArray as $lang) {
                $file_content .= "\n        \"" . $lang->getVar('lang_name') . '"=>array(';
                $file_content .= "\n            \"lang_id\"=>" . $lang->getVar('lang_id') . ',';
                $file_content .= "\n            \"weight\"=>" . $lang->getVar('weight') . ',';
                $file_content .= "\n            \"lang_name\"=>\"" . $lang->getVar('lang_name') . '",';
                $file_content .= "\n            \"lang_desc\"=>\"" . $lang->getVar('lang_desc') . '",';
                $file_content .= "\n            \"lang_code\"=>\"" . $lang->getVar('lang_code') . '",';
                $file_content .= "\n            \"lang_charset\"=>\"" . $lang->getVar('lang_charset') . '",';
                $file_content .= "\n            \"lang_image\"=>\"" . $lang->getVar('lang_image') . '"';
                $file_content .= "\n        ),";
            }
            $file_content .= "\n    );";
        }

        $extArray = $this->getAll(false);
        if ($extArray && \is_array($extArray)) {
            $file_content .= "\n    \$" . \XLANGUAGE_CONFIG_VAR . "['xlanguage_ext'] = array(";
            foreach ($extArray as $lang) {
                $file_content .= "\n        \"" . $lang->getVar('lang_name') . '"=>array(';
                $file_content .= "\n            \"lang_id\"=>" . $lang->getVar('lang_id') . ',';
                $file_content .= "\n            \"weight\"=>" . $lang->getVar('weight') . ',';
                $file_content .= "\n            \"lang_name\"=>\"" . $lang->getVar('lang_name') . '",';
                $file_content .= "\n            \"lang_desc\"=>\"" . $lang->getVar('lang_desc') . '",';
                $file_content .= "\n            \"lang_code\"=>\"" . $lang->getVar('lang_code') . '",';
                $file_content .= "\n            \"lang_charset\"=>\"" . $lang->getVar('lang_charset') . '",';
                $file_content .= "\n            \"lang_image\"=>\"" . $lang->getVar('lang_image') . '",';
                $file_content .= "\n            \"lang_base\"=>\"" . $lang->getVar('lang_base') . '"';
                $file_content .= "\n        ),";
            }
            $file_content .= "\n    );";
        }

        $file_content .= "\n?>";
        \fwrite($fp, $file_content);
        \fclose($fp);

        return true;
    }

    /**
     * @return bool|null
     */
    public function loadFileConfig()
    {
        $file_config = \XLANGUAGE_CONFIG_FILE;
        if (!\is_file($file_config)) {
            $this->createConfig();
        }
        if (!\is_readable($file_config)) {
            $config = null;

            return $config;
        }
        require $file_config;
        return ${\XLANGUAGE_CONFIG_VAR} ?? false;
    }
}
