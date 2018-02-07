<div id="ajax_confirmation" class="alert alert-success hide"></div>
{* ajaxBox allows*}
<div id="ajaxBox" style="display:none"></div>

<div class="row">
    <div class="col-lg-12">
        <div class="btn-group">
            <a href="{$aw_tab_settings|escape:'htmlall':'UTF-8'}" class="btn {if $awtab == ''}btn-success{else}btn-default{/if}">{$aw_l_settings|escape:'htmlall':'UTF-8'}</a>
            <a href="{$aw_tab_regenerate|escape:'htmlall':'UTF-8'}" class="btn {if $awtab == 'regenerate'}btn-success{else}btn-default{/if}">{$aw_l_regenerate|escape:'htmlall':'UTF-8'}</a>
        </div>
    </div>
</div>
<br>

<script type="text/javascript">
{literal}
    var AW_TRANSLATIONS = {/literal}{$aw_translations|json_encode}{literal};

    function aw_translate(key) {
        return (AW_TRANSLATIONS.hasOwnProperty(key)) ? AW_TRANSLATIONS[key] : key;
    }
{/literal}
</script>

{block "awcontent"}{/block}