<?php
// $Id: vars.php 12334 2014-02-27 08:02:54Z beckmi $
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
if (!defined('XLANGUAGE_CONFIG_FILE')) {
    define('XLANGUAGE_CONFIG_FILE', XOOPS_CACHE_PATH . '/xlanguage.php');
}
if (!defined('XLANGUAGE_CONFIG_VAR')) {
    define('XLANGUAGE_CONFIG_VAR', 'cached_config');
}
if (!defined('XLANGUAGE_LANG_TAG')) {
    define('XLANGUAGE_LANG_TAG', "lang");
}
if (!defined('XLANGUAGE_TAGS_RESERVED')) {
    define('XLANGUAGE_TAGS_RESERVED', "</head>,</body>");
}

/**
 * phpMyAdmin Language Loading File
 */

/**
 * All the supported languages have to be listed in the array below.
 * 1. The key must be the "official" ISO 639 language code and, if required,
 *     the dialect code. It can also contains some informations about the
 *     charset (see the Russian case).
 * 2. The first of the values associated to the key is used in a regular
 *     expression to find some keywords corresponding to the language inside two
 *     environment variables.
 *     These values contains:
 *     - the "official" ISO language code and, if required, the dialect code
 *       also ('bu' for Bulgarian, 'fr([-_][[:alpha:]]{2})?' for all French
 *       dialects, 'zh[-_]tw' for Chinese traditional...);
 *     - the '|' character (it means 'OR');
 *     - the full language name.
 * 3. The second values associated to the key is the name of the file to load
 *     without the '.php' extension.
 * 4. The last values associated to the key is the language code as defined by
 *     the RFC1766.
 *
 * Beware that the sorting order (first values associated to keys by
 * alphabetical reverse order in the array) is important: 'zh-tw' (chinese
 * traditional) must be detected before 'zh' (chinese simplified) for
 * example.
 *
 * When there are more than one charset for a language, we put the -utf-8
 * first.
 */
$available_languages = array(
    'ar'    => array('ar([-_][[:alpha:]]{2})?|arabic', 'arabic'),
    'bg'    => array('bg|bulgarian', 'bulgarian'),
    'ca'    => array('ca|catalan', 'catalan'),
    'cs'    => array('cs|czech', 'czech'),
    'da'    => array('da|danish', 'danish'),
    'de'    => array('de([-_][[:alpha:]]{2})?|german', 'german'),
    'el'    => array('el|greek', 'greek'),
    'en'    => array('en([-_][[:alpha:]]{2})?|english', 'english'),
    'es'    => array('es([-_][[:alpha:]]{2})?|spanish', 'spanish'),
    'et'    => array('et|estonian', 'estonian'),
    'fi'    => array('fi|finnish', 'finnish'),
    'fr'    => array('fr([-_][[:alpha:]]{2})?|french', 'french'),
    'gl'    => array('gl|galician', 'galician'),
    'he'    => array('he|hebrew', 'hebrew'),
    'hr'    => array('hr|croatian', 'croatian'),
    'hu'    => array('hu|hungarian', 'hungarian'),
    'id'    => array('id|indonesian', 'indonesian'),
    'it'    => array('it|italian', 'italian'),
    'ja'    => array('ja|japanese', 'japanese'),
    'ko'    => array('ko|korean', 'koreano'),
    'ka'    => array('ka|georgian', 'georgian'),
    'lt'    => array('lt|lithuanian', 'lithuanian'),
    'lv'    => array('lv|latvian', 'latvian'),
    'nl'    => array('nl([-_][[:alpha:]]{2})?|dutch', 'dutch'),
    'no'    => array('no|norwegian', 'norwegian'),
    'pl'    => array('pl|polish', 'polish'),
    'pt-br' => array('pt[-_]br|brazilian portuguese', 'portuguesebr'),
    'pt'    => array('pt([-_][[:alpha:]]{2})?|portuguese', 'portuguese'),
    'ro'    => array('ro|romanian', 'romanian'),
    'ru'    => array('ru|russian', 'russian'),
    'sk'    => array('sk|slovak', 'slovak'),
    'sq'    => array('sq|albanian', 'albanian'),
    'sr'    => array('sr|serbian', 'serbian'),
    'sv'    => array('sv|swedish', 'swedish'),
    'th'    => array('th|thai', 'thai'),
    'tr'    => array('tr|turkish', 'turkish'),
    'uk'    => array('uk|ukrainian', 'ukrainian'),
    'zh-tw' => array('zh[-_]tw|chinese traditional', 'tchinese'),
    'zh-cn' => array('zh[-_]cn|chinese simplified', 'schinese'),
);

// mb
$GLOBALS['available_languages'] =& $available_languages;
