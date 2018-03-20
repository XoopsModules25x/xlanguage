<?php namespace XoopsModules\Xlanguage;

use Xmf\Request;
use XoopsModules\Xlanguage;
use XoopsModules\Xlanguage\Common;

/**
 * Class Utility
 */
class Utility
{
    use Common\VersionChecks; //checkVerXoops, checkVerPhp Traits

    use Common\ServerStats; // getServerStats Trait

    use Common\FilesManagement; // Files Management Trait

    //--------------- Custom module methods -----------------------------


    /**
     * @param $value
     * @param $out_charset
     * @param $in_charset
     * @return array|string
     */
    public static function convertEncoding($value, $out_charset, $in_charset)
    {
        if (is_array($value)) {
            foreach ($value as $key => $val) {
                $value[$key] = static::convertEncoding($val, $out_charset, $in_charset);
            }
        } else {
            $value = static::convertItem($value, $out_charset, $in_charset);
        }

        return $value;
    }

    /**
     * @param $value
     * @param $out_charset
     * @param $in_charset
     * @return string
     */
    public static function convertItem($value, $out_charset, $in_charset)
    {
        if (strtolower($in_charset) == strtolower($out_charset)) {
            return $value;
        }
        $xconvHandler = @xoops_getModuleHandler('xconv', 'xconv', true);
        if (is_object($xconvHandler) && $converted_value = @$xconvHandler->convert_encoding($value, $out_charset, $in_charset)) {
            return $converted_value;
        }
        if (XOOPS_USE_MULTIBYTES && function_exists('mb_convert_encoding')) {
            $converted_value = @mb_convert_encoding($value, $out_charset, $in_charset);
        } elseif (function_exists('iconv')) {
            $converted_value = @iconv($in_charset, $out_charset, $value);
        }
        $value = empty($converted_value) ? $value : $converted_value;

        return $value;
    }

    /**
     * @return mixed
     */
    public static function createConfig()
    {
        /** @var \XlanguageLanguageHandler $xlanguageHandler */
        $xlanguageHandler = xoops_getModuleHandler('language', 'xlanguage');

        return $xlanguageHandler->createConfig();
    }

    /**
     * @return mixed
     */
    public static function loadConfig()
    {
        /** @var \XlanguageLanguageHandler $xlanguageHandler */
        $xlanguageHandler = xoops_getModuleHandler('language', 'xlanguage');
        $config           = $xlanguageHandler->loadFileConfig();

        return $config;
    }

    /**
     * Analyzes some PHP environment variables to find the most probable language
     * that should be used
     *
     * @param  string $str
     * @param  string $envType
     * @return int|string
     * @internal param $string $ string to analyze
     * @internal param $integer $ type of the PHP environment variable which value is $str
     *
     * @global        array    the list of available translations
     * @global        string   the retained translation keyword
     * @access   private
     */
    public static function langDetect($str = '', $envType = '')
    {
        global $available_languages;
        $lang = '';

        if (!empty($available_languages)) {
            foreach ($available_languages as $key => $value) {
                // $envType =  1 for the 'HTTP_ACCEPT_LANGUAGE' environment variable,
                //             2 for the 'HTTP_USER_AGENT' one
                $expr = $value[0];
                if (false === strpos($expr, '[-_]')) {
                    $expr = str_replace('|', '([-_][[:alpha:]]{2,3})?|', $expr);
                }
                //        if (($envType == 1 && eregi('^(' . $expr . ')(;q=[0-9]\\.[0-9])?$', $str))
                //            || ($envType == 2 && eregi('(\(|\[|;[[:space:]])(' . $expr . ')(;|\]|\))', $str))) {
                if ((1 == $envType && preg_match('#^(' . $expr . ')(;q=[0-9]\\.[0-9])?$#i', $str)) || (2 == $envType && preg_match('#(\(|\[|;[[:space:]])(' . $expr . ')(;|\]|\))#i', $str))) {
                    $lang = $key;
                    //if($lang != 'en')
                    break;
                }
            }
        }

        return $lang;
    }

    /**
     * @return string
     */
    public static function detectLang()
    {
        global $available_languages, $_SERVER;

        if (!empty($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
            $HTTP_ACCEPT_LANGUAGE = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
        }

        if (!empty($_SERVER['HTTP_USER_AGENT'])) {
            $HTTP_USER_AGENT = $_SERVER['HTTP_USER_AGENT'];
        }

        $lang       = '';
        $xoops_lang = '';
        // 1. try to findout user's language by checking its HTTP_ACCEPT_LANGUAGE variable

        //    if (empty($lang) && !empty($HTTP_ACCEPT_LANGUAGE)) {
        //        $accepted    = explode(',', $HTTP_ACCEPT_LANGUAGE);
        //        $acceptedCnt = count($accepted);
        //        reset($accepted);
        //        for ($i = 0; $i < $acceptedCnt; ++$i) {
        //            $lang = static::langDetect($accepted[$i], 1);
        //            if (strncasecmp($lang, 'en', 2)) {
        //                break;
        //            }
        //        }
        //    }

        //This returns the most preferred langauage "q=1"
        $lang = static::getPreferredLanguage();

        // 2. if not found in HTTP_ACCEPT_LANGUAGE, try to find user's language by checking its HTTP_USER_AGENT variable
        if (empty($lang) && !empty($HTTP_USER_AGENT)) {
            $lang = static::langDetect($HTTP_USER_AGENT, 2);
        }
        // 3. If we catch a valid language, configure it
        if (!empty($lang)) {
            $xoops_lang = $available_languages[$lang][1];
        }

        return $xoops_lang;
    }

    /**
     * @param $output
     * @return array|mixed|string
     */
    public static function encodeCharSet($output)
    {
        global $xlanguage;
        $output = static::cleanMultiLang($output);
        // escape XML doc
        if (preg_match("/^\<\?[\s]?xml[\s]+version=([\"'])[^\>]+\\1[\s]+encoding=([\"'])[^\>]+\\2[\s]?\?\>/i", $output)) {
            return $output;
        }
        $in_charset  = $xlanguage['charset_base'];
        $out_charset = $xlanguage['charset'];

        $output = static::convertEncoding($output, $out_charset, $in_charset);
        return $output;
    }

    /**
     * @param string $text
     * @return mixed
     */
    public static function cleanMultiLang($text)
    {
        global $xoopsConfig;
        global $xlanguage_langs;
        if (!isset($xlanguage_langs)) {

            /** @var \XlanguageLanguageHandler $xlanguageHandler */
            $xlanguageHandler = xoops_getModuleHandler('language', 'xlanguage');
            $langs            = $xlanguageHandler->getAll(true);
            //        $langs = $GLOBALS['xlanguageHandler']->getAll(true); //mb
            foreach (array_keys($langs) as $_lang) {
                $xlanguage_langs[$_lang] = $langs[$_lang]->getVar('lang_code');
            }
            unset($langs);
        }
        if (empty($xlanguage_langs) || 0 == count($xlanguage_langs)) {
            return $text;
        }

        // escape brackets inside of <code>...</code>
        $patterns[] = '/(\<code>.*\<\/code>)/isU';

        // escape brackets inside of <input type="..." value="...">
        $patterns[] = '/(\<input\b(?![^\>]*\btype=([\'"]?)(submit|image|reset|button))[^\>]*\>)/isU';

        // escape brackets inside of <textarea></textarea>
        $patterns[] = '/(\<textarea\b[^>]*>[^\<]*\<\/textarea>)/isU';

        $text = preg_replace_callback($patterns, 'static::escapeBracketMultiLang', $text);

        // create the pattern between language tags
        $pqhtmltags  = explode(',', preg_quote(XLANGUAGE_TAGS_RESERVED, '/'));
        $mid_pattern = '(?:(?!(' . implode('|', $pqhtmltags) . ')).)*';

        $patterns = [];
        $replaces = [];
        /* */
        if (isset($xlanguage_langs[$xoopsConfig['language']])) {
            $lang       = $xlanguage_langs[$xoopsConfig['language']];
            $patterns[] = '/(\[([^\]]*\|)?' . preg_quote($lang) . '(\|[^\]]*)?\])(' . $mid_pattern . ')(\[\/([^\]]*\|)?' . preg_quote($lang) . '(\|[^\]]*)?\])/isU';
            $replaces[] = '$4';
        }
        /* */
        foreach (array_keys($xlanguage_langs) as $_lang) {
            if ($_lang == @$xoopsConfig['language']) {
                continue;
            }
            $name       = $xlanguage_langs[$_lang];
            $patterns[] = '/(\[([^\]]*\|)?' . preg_quote($name) . '(\|[^\]]*)?\])(' . $mid_pattern . ')(\[\/([^\]]*\|)?' . preg_quote($name) . '(\|[^\]]*)?(\]\<br[\s]?[\/]?\>|\]))/isU';
            $replaces[] = '';
        }
        if (!empty($xoopsConfig['language'])) {
            $text = preg_replace('/\[[\/]?[\|]?' . preg_quote($xoopsConfig['language']) . '[\|]?\](\<br \/\>)?/i', '', $text);
        }
        if (count($replaces) > 0) {
            $text = preg_replace($patterns, $replaces, $text);
        }

        return $text;
    }

    /**
     * @param $matches
     * @return mixed
     */
    public static function escapeBracketMultiLang($matches)
    {
        global $xlanguage_langs;
        $ret = $matches[1];
        if (!empty($xlanguage_langs)) {
            $pattern = '/(\[([\/])?(' . implode('|', array_map('preg_quote', array_values($xlanguage_langs))) . ')([\|\]]))/isU';
            $ret     = preg_replace($pattern, "&#91;\\2\\3\\4", $ret);
        }

        return $ret;
    }

    /**
     * @param  null $options
     * @return bool
     */
    public static function showSelectedLanguage($options = null)
    {
        require_once XOOPS_ROOT_PATH . '/modules/xlanguage/blocks/xlanguage_blocks.php';
        if (empty($options)) {
            $options[0] = 'images'; // display style: image, text, select
            $options[1] = ' '; // delimitor
            $options[2] = 5; // items per line
        }

        $block        = b_xlanguage_select_show($options);
        $block['tag'] = 'xlanguage';

        $content = '';
        $i       = 1;
        if (!empty($block['display'])) { //mb
            if (in_array($block['display'], ['images', 'text'])) {
                foreach ($block['languages'] as $name => $lang) {
                    $content .= '<a href="' . $block['url'] . $lang['name'] . '" title="' . $lang['desc'] . '">';
                    if ('images' === $block['display']) {
                        $content .= '<img src="' . $lang['image'] . '" alt="' . $lang['desc'] . '"';
                        if ($block['selected'] != $lang['name']) {
                            $content .= ' style="MozOpacity: .8; opacity: .8; filter:Alpha(opacity=80);"';
                        }
                        $content .= '>';
                    } else {
                        $content .= $lang['desc'];
                    }
                    $content .= '</a>';
                    if (0 == (++$i % $block['number'])) {
                        $content .= '<br>';
                    }
                }
            } else {
                $content .= '<select name="' . $block['tag'] . '"
                onChange="if (this.options[this.selectedIndex].value.length >0) { window.document.location=this.options[this.selectedIndex].value;}"
                >';
                if (!empty($block['languages'])) { //mb
                    foreach ($block['languages'] as $name => $lang) {
                        $content .= '<option value="' . $block['url'] . $lang['name'] . '"';
                        if ($block['selected'] == $lang['name']) {
                            $content .= ' selected ';
                        }
                        $content .= '>' . $lang['desc'] . '</option>';
                    }
                }
                $content .= '</select>';
            }
        }

        define('XLANGUAGE_SWITCH_CODE', $content);

        return true;
    }

    /**
     * @return int|string
     */
    public static function getPreferredLanguage()
    {
        $langs = [];
        if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
            // break up string into pieces (languages and q factors)
            preg_match_all('/([a-z]{1,8}(-[a-z]{1,8})?)\s*(;\s*q\s*=\s*(1|0\.\d+))?/i', $_SERVER['HTTP_ACCEPT_LANGUAGE'], $lang_parse);
            if (count($lang_parse[1])) {
                // create a list like "en" => 0.8
                $langs = array_combine($lang_parse[1], $lang_parse[4]);
                // set default to 1 for any without q factor
                foreach ($langs as $lang => $val) {
                    if ('' === $val) {
                        $langs[$lang] = 1;
                    }
                }
                // sort list based on value
                arsort($langs, SORT_NUMERIC);
            }
        }
        //extract most important (first)
        foreach ($langs as $lang => $val) {
            break;
        }
        //if complex language simplify it
        if (false !== strpos($lang, '-')) {
            $tmp  = explode('-', $lang);
            $lang = $tmp[0];
        }

        return $lang;
    }
}
