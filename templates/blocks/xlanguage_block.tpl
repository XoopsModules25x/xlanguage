<div style="text-align: center; margin-left: auto; margin-right: auto;">
    <{if $block.display eq "images" OR $block.display eq "text"}>
        <{foreach item=lang name=lang_it from=$block.languages}>
            <a href="<{$block.url}><{$lang.name}>" title="<{$lang.desc}>">
                <{if $block.display eq "images"}>
                    <img src="<{$lang.image}>" alt="<{$lang.desc}>"
                         <{if $block.selected != $lang.name}>style="-moz-opacity: .8; opacity: .8; filter:Alpha(opacity=80);"<{/if}>>
                <{else}>
                    <{$lang.desc}>
                <{/if}>
            </a>
            <{if $block.number != 0 && $smarty.foreach.lang_it.iteration % $block.number eq 0}>
                <br>
            <{/if}>
        <{/foreach}>
    <{else}>
        <select name="selectlang"
                onChange="if(this.options[this.selectedIndex].value.length >0 ){ window.document.location=this.options[this.selectedIndex].value;}"
        >
            <{foreach item=lang name=lang_it from=$block.languages}>
                <option value="<{$block.url}><{$lang.name}>"
                        <{if $block.selected eq $lang.name}>selected<{/if}>
                ><{$lang.desc}></option>
            <{/foreach}>
        </select>
    <{/if}>
</div>
