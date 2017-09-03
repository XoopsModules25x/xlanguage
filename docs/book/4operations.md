4.0 Operating Instructions
================================

1. install "xLanguage" as a regular module

1. select basic languages (from an available language list) and add extended languages (after selecting basic language) from module Admin page for instance, to make language switch between: English, Simplified Chinese (gb2312), Traditional Chinese (big5) and UTF-8 Chinese:

    base 1: 
    * name: english;
    * description(optional): English; 
    * charset: iso-8859-1; 
    * code: en (or another like "xen", not a true language code, just the tag for indicating English content)
    
   base 2: 
    * name: schinese; 
    * description(optional): Simplified Chinese; 
    * charset: gb2312; 
    * code: zh (or anyother like "sc", not a true language code, just the tag for indicating Chinese content)

    ***extended lang of schinese ***

    1) name: tchinese;
        * description(optional): Traditional Chinese; 
        * charset: big5 
        * code: zh-TW (the true language code of Traditional Chinese) 
        * base: schinese
extended lang of schinese 

    2: name: utf8; 
        * description(optional): Simplified Chinese UTF-8; 
        * charset: UTF-8 
        * code: zh-CN (the true language code of Simplified Chinese) 
        * base: schinese

1. make the block "language selection" visible

1. 
add multilingual content with according tags specified for each base language (in step 3) to your modules, templates or themes[Skip this step if you do not use multi-language content display but only use charset encoding]: 

    wrap content of each language with respective tag specified in step 3:
[langcode1]Content of the language1[/langcode1] [langcode2]Content of the language2[/langcode2] [langcode3]Content of the language3[/langcode3] ...

    if two or more languages have same content, you do not need add them one by one but use delimiter "|": 
[langcode1|langcode2]Content shared by language1&2[/langcode1|langcode2] [langcode3]Content of the language3[/langcode3] ...

    A real example: suppose the lang_codes specified in step 4 are: 
        English-en; 
        French-fr; 
        SimplifiedChiense-sc:
My XOOPS[sc]我的XOOPS[/sc]

    OR:
    
    [english|french]This is my content in English and French[/english|french][schinese]这是我在中国的内容[/schinese]

1. 
automatic conversion of content from one charset(extended language) to another [Actually on action needed in this step]

1. 
__if__ you would like to insert hardcoded scripts for language switch in your theme or any template besides the language selection box:

    1) modify /modules/xlanguage/api.php "$xlanguage_theme_enable = true;"
    
    2) config options "$options = array("images", " ", 
    3); // display mode, delimitor, number per line";

    3) insert <{$smarty.const.XLANGUAGE_SWITCH_CODE}> into your theme or template files anywhere you prefer it present 