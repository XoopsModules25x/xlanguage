<div id="help-template" class="outer">
    <{include file=$smarty.const._MI_XLANGUAGE_HELP_HEADER}>

    <h4 class="odd">DESCRIPTION</h4> <br>

    <p class="even">
        An eXtensible Multi-language content and character encoding Management plugin
        Multilanguage management handles displaying contents of different languages, like English, French and Chinese
        Character encoding management handles contents of different encoding sets for one language, like GB2312 (Chinese
        Simplified) and BIG5 (Chinese Traditional) for Chinese. <br><br>


    <h4 class="odd">What xlanguage CAN do</h4>

    1 displaying content of specified language based on user's dynamic choice <br><br>
    2 converting content from one character encoding set to another<br><br>

    <h4 class="odd">What xlanguage can NOT do</h4>

    1 xlanguage does NOT have the ability of translating content from one language to another one. You have to input
    contents of various languages by yourself <br><br>
    2 xlanguage does NOT work without adding one line to XOOPS/include/common.php (see guide below) <br><br>
    3 xlanguage does NOT have the ability of converting content from one character encoding to another if none of
    "iconv", "mb_string" or "xconv" is available. <br><br>

    <h4 class="odd">Features</h4>

    1 auto-detection of visitor's language on his first visitor <br><br>
    2 memorizing users' language preferences<br><br>
    3 switching contents of different languages/encoding sets on-fly <br><br>
    4 supporting M-S-M mode for character encoding handler<br><br>
    Note:<br><br>
    M-S-M: Multiple encoding input, Single encoding storage, Multiple encoding output. M-S-M allows one site to fit
    various users with different language character encoding usages. For example, a site
    having xlanguage implemented porperly allows users to input content either with GB2312, with BIG5 or UTF-8 encoding
    and to store the content into DB with specified encoding, for say GB2312, and to
    display the content either with GB2312, with BIG5 or with UTF-8 encoding.
    <br><br>


    <h4 class="odd">INSTALL/UNINSTALL</h4>

    No special measures necessary, follow the standard installation process – extract the xLanguage folder into the
    ../modules directory. Install the module through Admin -> System Module -> Modules.
    <br><br>
    Detailed instructions on installing modules are available in the <a
            href="https://www.gitbook.com/book/xoops/xoops-operations-guide/" target="_blank">XOOPS Operations Manual</a>

    <h4 class="odd">OPERATING INSTRUCTIONS</h4>

    <p class="even">


        1 install "xlanguage" as a regular module<br><br>

        2 select basic languages (from an available language list) and add extended languages (upon a selected basic
        language) from module admin page<br>
        for instance, to make language switch between: English, Simplified Chinese (gb2312), Traditional Chinese (big5)
        and UTF-8 Chinese:<br>
        base 1: name: english; description(optional): English; charset: iso-8859-1; code: en (or anyother like "xen",
        not a true language code, just the tag for indicating English content)<br>
        base 2: name: schinese; description(optional): Simplified Chinese; charset: gb2312; code: zh (or anyother like
        "sc", not a true language code, just the tag for indicating Chinese content)<br>
        extended lang of schinese 1: name: tchinese; description(optional): Traditional Chinese; charset: big5 code:
        zh-TW (the true language code of Traditional Chinese) base: schinese<br>
        extended lang of schinese 2: name: utf8; description(optional): Simplified Chinese UTF-8; charset: utf-8 code:
        zh-CN (the true language code of Simplified Chinese) base: schinese<br><br>

        3 make the block "language selection" visible<br><br>

        4 add multilingual content with according tags specified for each base language (in step 3) to your modules,
        templates or themes[Skip this step if you do not use multi-language content display
        but only use charset encoding]: <br>
        wrap content of each language with respective tag specified in step 3:<br>
        [langcode1]Content of the language1[/langcode1] [langcode2]Content of the language2[/langcode2]
        [langcode3]Content of the language3[/langcode3] ...<br>
        if two or more languages have same content, you do not need add them one by one but use delimiter "|": <br>
        [langcode1|langcode2]Content shared by language1&2[/langcode1|langcode2] [langcode3]Content of the
        language3[/langcode3] ...<br>
        a true example (suppose the lang_codes specified in step 4 are: English-en; French-fr;
        SimplifiedChiense-sc):<br>
        [en]My XOOPS[/en][fr]Moi XOOPS[/fr][sc]我的XOOPS[/sc]<br>
        OR:<br>
        [english|french]This is my content in English and French[/english|french][schinese]这是我在中国的内容[/schinese]<br><br>

        5 automatic conversion of content from one charset(extended language) to another [Actually on action needed in
        this step]<br><br>

        6 __if__ you would like to insert hardcoded scripts for language switch in your theme or any template besides
        the language selection box:<br>
        1) modify /modules/xlanguage/api.php "$xlanguage_theme_enable = true;"<br>
        2) config options "$options = array("images", " ", 5); // display mode, delimitor, number per line";<br>
        3) insert &lt;{$smarty.const.XLANGUAGE_SWITCH_CODE}> into your theme or template files anywhere you prefer it
        present <br><br>


    <h4 class="odd">TUTORIAL</h4>

    <p class="even">
        There is no tutorial available at the moment.

        <!-- -----Help Content ---------- -->

</div>
