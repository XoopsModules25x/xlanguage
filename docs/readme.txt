
D.J.(phppp) http://xoops.org
===========================================================================================================

xlanguage, eXtensible Xoops Multilingual Content and Encoding Management


Applicable
---------
Any version of XOOPS and any version of any MODULE with any THEME.
NEW in 3.02 for XOOPS 2.4.0: no hacks of api.php needed anymore


Easy to use
-----------
1 All you need do is to insert ONLY ONE LINE into common.php and install "xlanguage"
2 Do NOT need to modify/hack any other XOOPS core files or any module


Powerful enough to meet your requirements
-----------------------------------------
1 Could handle as many languages of content as you want
2 Could handle different charset of a selected language
3 Could handle multilingual content anywhere on your site, in a module, a php file, an html page or a theme's hardcoded content
4 Compatible with content cache
5 Automatic detection of user browser's language preference


User guide
----------
1 install "xlanguage" as a regular module

2 select basic langauges (from an available language list) and add extended languages (upon a selected basic language) from module admin page
    for instance, to make language switch between: English, Simplified Chinese (gb2312), Traditional Chinese (big5) and UTF-8 Chinese:
    base 1:     name: english;         description(optional): English;             charset: iso-8859-1;     code: en (or anyother like "xen", not a true language code, just the tag for indicating English content)
    base 2:     name: schinese;     description(optional): Simplified Chinese;     charset: gb2312;         code: zh (or anyother like "sc", not a true language code, just the tag for indicating Chinese content)
    extended lang of schinese 1: name: tchinese;     description(optional): Traditional Chinese;         charset: big5     code: zh-TW (the true language code of Traditional Chinese)        base: schinese
    extended lang of schinese 2: name: utf8;         description(optional): Simplified Chinese UTF-8;     charset: utf-8     code: zh-CN (the true language code of Simplified Chinese)        base: schinese

3 make the block "langauge selection" visible

4 add multilingual content with according tags sepcified for each base language (in step 4) to your modules, templates or themes[Skip this step if you do not use multi-language content display but only use charset encoding]:
    wrap content of each language with respective tag specified in step 4:
    [langcode1]Content of the language1[/langcode1] [langcode2]Content of the language2[/langcode2] [langcode3]Content of the language3[/langcode3] ...
    if two or more languages have same content, you do not need add them one by one but use delimiter "|":
    [langcode1|langcode2]Content shared by language1&2[/langcode1|langcode2] [langcode3]Content of the language3[/langcode3] ...
    a true example (suppose the lang_codes specified in step 4 are: English-en; French-fr; SimplifiedChiense-sc):
    [en]My XOOPS[/en][fr]Moi XOOPS[/fr][sc]我的XOOPS[/sc]
    OR:
    [english|french]This is my content in English and French[/english|french][schinese]中文内容[/schinese]

5 automatic conversion of content from one charset(extended language) to another [Actually on action needed in this step]

6 __if__ you would like to insert hardcoded scripts for language switch in your theme or any template besides the language selection box:
    1) modify /modules/xlanguage/api.php "$xlanguage_theme_enable = true;"
    2) config options "$options = array("images", " ", 5); // display mode, delimitor, number per line";
    3) insert "<{$smarty.const.XLANGUAGE_SWITCH_CODE}>" into your theme or template files anywhere you prefer it present


xlangauge description
-------------------------
An eXtensible Multi-language content and character encoding Management plugin
Multilanguage management handles displaying contents of different languages, like English, French and Chinese
Character encoding management handles contents of different encoding sets for one language, like GB2312 (Chinese Simplified) and BIG5 (Chinese Traditional) for Chinese.


What xlanguage CAN do
---------------------
1 displaying content of specified language based on user's dynamic choice
2 converting content from one character encoding set to another


What xlanguage can NOT do
------------------------
1 xlanguage does NOT have the ability of translating content from one language to another one. You have to input contents of various languages by yourself
2 xlanguage does NOT work without adding one line to XOOPS/include/common.php (see guide below)
3 xlanguage does NOT have the ability of converting content from one character encoding to another if none of "iconv", "mb_string" or "xconv" is available.


Features
--------
1 auto-detection of visitor's language on his first visitor
2 memorizing users' langauge preferences
3 switching contents of different languges/encoding sets on-fly
4 supporting M-S-M mode for character encoding handler

Note:
M-S-M: Multiple encoding input, Single encoding storage, Multiple encoding output.
M-S-M allows one site to fit various users with different language character encoding usages. For example, a site having xlanguage implemented porperly allows users to input content either with GB2312, with BIG5 or UTF-8 encoding and to store the content into DB with specified encoding, for say GB2312, and to display the content either with GB2312, with BIG5 or with UTF-8 encoding.
