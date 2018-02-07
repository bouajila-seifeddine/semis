{*
*
*  Please read the terms of the CLUF license attached to this module(cf "licences" folder)
*
*  @author    Pronimbo.
*  @copyright Pronimbo. all rights reserved.
*  @license   http://www.pronimbo.com/licenses/license_en.pdf http://www.pronimbo.com/licenses/license_es.pdf https://www.pronimbo.com/licenses/license_fr.pdf
*
*}
{if $show_toolbar && $ps15}
    {include file="toolbar.tpl" toolbar_btn=$toolbar_btn toolbar_scroll=$toolbar_scroll title=$title}
    <div class="leadin">{block name="leadin"}{/block}</div>
{/if}

{if isset($fields.title)}<h3>{$fields.title|escape:'html':'UTF-8'}</h3>{/if}

{if isset($fields[0].form.tabs) && $fields[0].form.tabs|count > 1}
    <script type="text/javascript">
        var helper_tabs = { };
        {foreach $fields as $key => $field}
        helper_tabs[{$key|escape:'quotes':'UTF-8'}] = {$field.form.tabs|json_encode|escape:'quotes':'UTF-8'};
        {/foreach}
        var unique_field_id = '';
    </script>
{/if}
{block name="leadin"}{/block}
{block name="defaultForm"}
    {if isset($identifier_bk) && $identifier_bk == $identifier}{capture name='identifier_count'}{counter name='identifier_count'}{/capture}{/if}
    {assign var='identifier_bk' value=$identifier scope='parent'}
    {if isset($table_bk) && $table_bk == $table}{capture name='table_count'}{counter name='table_count'}{/capture}{/if}
    {assign var='table_bk' value=$table scope='parent'}
    <form id="{if isset($fields.form.form.id_form)}{$fields.form.form.id_form|escape:'html':'UTF-8'}{else}{if $table == null}configuration_form{else}{$table|escape:'html':'UTF-8'}_form{/if}{if isset($smarty.capture.table_count) && $smarty.capture.table_count}_{$smarty.capture.table_count|intval|escape:'html':'UTF-8'}{/if}{/if}"
          class="defaultForm form-horizontal{if isset($name_controller) && $name_controller} {$name_controller|escape:'html':'UTF-8'}{/if}"{if isset($current) && $current} action="{$current|escape:'quotes':'UTF-8'}{if isset($token) && $token}&token={$token|escape:'quotes':'UTF-8'}{/if}"{/if}
          method="post" enctype="multipart/form-data"{if isset($style)} style="{$style|escape:'html':'UTF-8'}"{/if}
          novalidate>
        {if $form_id}
            <input type="hidden" name="{$identifier|escape:'html':'UTF-8'}"
                   id="{$identifier|escape:'html':'UTF-8'}{if isset($smarty.capture.identifier_count) && $smarty.capture.identifier_count}_{$smarty.capture.identifier_count|intval|escape:'html':'UTF-8'}{/if}"
                   value="{$form_id|escape:'html':'UTF-8'}"/>
        {/if}
        {if !empty($submit_action)}
            <input type="hidden" name="{$submit_action|escape:'html':'UTF-8'}" value="1"/>
        {/if}
        {foreach $fields as $f => $fieldset}
            {block name="fieldset"}
                {capture name='fieldset_name'}{counter name='fieldset_name'}{/capture}
                <div class="panel"
                     id="fieldset_{$f|escape:'html':'UTF-8'}{if isset($smarty.capture.identifier_count) && $smarty.capture.identifier_count}_{$smarty.capture.identifier_count|intval|escape:'html':'UTF-8'}{/if}{if $smarty.capture.fieldset_name > 1}_{($smarty.capture.fieldset_name - 1)|intval}{/if}">
                    {foreach $fieldset.form as $key => $field}
                        {if $key == 'legend'}
                            {block name="legend"}
                                <div class="panel-heading">
                                    {if isset($field.image) && isset($field.title)}<img src="{$field.image}"
                                                                                        alt="{$field.title|escape:'html':'UTF-8'}" />{/if}
                                    {if isset($field.icon)}<i class="{$field.icon|escape:'html':'UTF-8'}"></i>{/if}
                                    {$field.title|escape:'html':'UTF-8'}
                                </div>
                            {/block}
                        {elseif $key == 'description' && $field}
                            <div class="alert alert-info">{$field|escape:'html':'UTF-8'}</div>
                        {elseif $key == 'fieldsets' || $key == 'input'}
                            {if !isset($form_open) || $form_open == 0}
                                <div class="form-wrapper">
                                {assign form_open 1}
                            {/if}
                            {if $key == 'fieldsets' AND $field|is_array}

                                {foreach $field as $k => $fieldset_item}
                                    {block name="input_row"}
                                        <div class="form-group"
                                             data-tab-id="{$fieldset_item.tab|escape:'html':'UTF-8'}">
                                            <fieldset
                                                    name="{$fieldset_item.tab|escape:'html':'UTF-8'}_{$k|escape:'html':'UTF-8'}">
                                                {if isset($fieldset_item) && $fieldset_item.label != ''}<legend>{$fieldset_item.label|escape:'html':'UTF-8'}</legend>{/if}
                                                {foreach $fieldset.form.input as $input}
                                                    {if isset($input.fieldset) && $input.fieldset eq $k}
                                                        {include file="./input_form.tpl" inline input=$input fieldset="1"}
                                                    {/if}
                                                {/foreach}
                                            </fieldset>
                                        </div>
                                    {/block}
                                {/foreach}
                            {elseif $key == 'input'}
                                {foreach $field as $input}
                                    {if !isset($input.fieldset) || ($fieldset.form.fieldsets|is_array && !in_array($input.fieldset,$fieldset.form.fieldsets|array_keys ))}
                                        {include file="./input_form.tpl" inline input=$input}
                                    {/if}
                                {/foreach}
                                {hook h='displayAdminForm' fieldset=$f}
                                {if isset($name_controller)}
                                    {capture name=hookName assign=hookName}display{$name_controller|ucfirst|escape:'html':'UTF-8'}Form{/capture}
                                    {hook h=$hookName fieldset=$f}
                                {elseif isset($smarty.get.controller)}
                                    {capture name=hookName assign=hookName}display{$smarty.get.controller|ucfirst|escape:'html':'UTF-8'}Form{/capture}
                                    {hook h=$hookName fieldset=$f}
                                {/if}
                            {/if}

                            {if !isset($form_closed)}
                                {assign form_closed 0}
                            {elseif !$form_closed}
                                </div>
                            {/if}
                        {elseif $key == 'desc'}
                            <div class="alert alert-info col-lg-offset-3">
                                {if is_array($field)}
                                    {foreach $field as $k => $p}
                                        {if is_array($p)}
                                            <span{if isset($p.id)} id="{$p.id|escape:'html':'UTF-8'}"{/if}>{$p.text|escape:'html':'UTF-8'}</span>
                                            <br/>
                                        {else}
                                            {$p|escape:'htmlall':'UTF-8'}
                                            {if isset($field[$k+1])}<br/>{/if}
                                        {/if}
                                    {/foreach}
                                {else}
                                    {$field|escape:'htmlall':'UTF-8'}
                                {/if}
                            </div>
                        {/if}
                        {block name="other_input"}{/block}
                    {/foreach}
                    {block name="footer"}
                        {if isset($fieldset['form']['submit']) || isset($fieldset['form']['buttons'])}
                            <div class="panel-footer">
                                <div class="col-lg-10">
                                    {if isset($fieldset['form']['submit']) && !empty($fieldset['form']['submit'])}
                                        <button
                                                type="submit"
                                                value="1"
                                                id="{if isset($fieldset['form']['submit']['id'])}{$fieldset['form']['submit']['id']|escape:'html':'UTF-8'}{else}{$table|escape:'html':'UTF-8'}_form_submit_btn{/if}"
                                                name="{if isset($fieldset['form']['submit']['name'])}{$fieldset['form']['submit']['name']|escape:'html':'UTF-8'}{else}{$submit_action|escape:'html':'UTF-8'}{/if}{if isset($fieldset['form']['submit']['stay']) && $fieldset['form']['submit']['stay']}AndStay{/if}"
                                                class="{if isset($fieldset['form']['submit']['class'])}{$fieldset['form']['submit']['class']|escape:'html':'UTF-8'}{else}btn btn-default pull-right{/if} btn-orange-small"
                                                >
                                            <i class="process-icon-save"></i>
                                            {$fieldset['form']['submit']['title']|escape:'htmlall':'UTF-8'}
                                        </button>
                                    {/if}
                                    {if isset($show_cancel_button) && $show_cancel_button}
                                        <a href="{$back_url|escape:'htmlall':'UTF-8'}" class="btn btn-default"
                                           onclick="window.history.back()">
                                            <i class="process-icon-cancel"></i> {l s='Cancel' mod='paseocenterbulkedition'}
                                        </a>
                                    {/if}
                                    {if isset($fieldset['form']['reset'])}
                                        <button
                                                type="reset"
                                                id="{if isset($fieldset['form']['reset']['id'])}{$fieldset['form']['reset']['id']|escape:'html':'UTF-8'}{else}{$table|escape:'html':'UTF-8'}_form_reset_btn{/if}"
                                                class="{if isset($fieldset['form']['reset']['class'])}{$fieldset['form']['reset']['class']|escape:'html':'UTF-8'}{else}btn btn-default{/if}"
                                                >
                                            {if isset($fieldset['form']['reset']['icon'])}<i
                                                class="{$fieldset['form']['reset']['icon']|escape:'html':'UTF-8'}"></i> {/if} {$fieldset['form']['reset']['title']|escape:'html':'UTF-8'}
                                        </button>
                                    {/if}
                                    {if isset($fieldset['form']['buttons'])}
                                        {foreach from=$fieldset['form']['buttons'] item=btn key=k}
                                            {if isset($btn.href) && trim($btn.href) != ''}
                                                <a href="{$btn.href|escape:'html':'UTF-8'}"
                                                   {if isset($btn['id'])}id="{$btn['id']|escape:'html':'UTF-8'}"{/if}
                                                   class="btn btn-default{if isset($btn['class'])} {$btn['class']|escape:'html':'UTF-8'}{/if}" {if isset($btn.js) && $btn.js} onclick="{$btn.js|escape:'html':'UTF-8'}"{/if}>{if isset($btn['icon'])}
                                                        <i class="{$btn['icon']|escape:'html':'UTF-8'}"></i>
                                                    {/if}{$btn.title|escape:'html':'UTF-8'}</a>
                                            {else}
                                                <button type="{if isset($btn['type'])}{$btn['type']|escape:'html':'UTF-8'}{else}button{/if}"
                                                        {if isset($btn['id'])}id="{$btn['id']|escape:'html':'UTF-8'}"{/if}
                                                        class="btn btn-default{if isset($btn['class'])} {$btn['class']|escape:'html':'UTF-8'}{/if}"
                                                        name="{if isset($btn['name'])}{$btn['name']|escape:'html':'UTF-8'}{else}submitOptions{$table|escape:'html':'UTF-8'}{/if}"{if isset($btn.js) && $btn.js} onclick="{$btn.js|escape:'html':'UTF-8'}"{/if}>{if isset($btn['icon'])}
                                                        <i class="{$btn['icon']|escape:'html':'UTF-8'}"></i>
                                                    {/if}{$btn.title|escape:'html':'UTF-8'}</button>
                                            {/if}
                                        {/foreach}
                                    {/if}
                                </div>
                            </div>
                        {/if}
                    {/block}
                </div>
            {/block}
            {block name="other_fieldsets"}{/block}
        {/foreach}
    </form>
{/block}
{block name="after"}{/block}

{if isset($tinymce) && $tinymce}
    <script type="text/javascript">
        var iso = '{$iso|escape:'html':'UTF-8'}';
        var pathCSS = '{$smarty.const._THEME_CSS_DIR_|escape:'html':'UTF-8'}';
        var ad = '{$ad|escape:'html':'UTF-8'}';

        $(document).ready(function () {
            {block name="autoload_tinyMCE"}
            tinySetup({
                editor_selector: "autoload_rte"
            });
            {/block}
        });
    </script>
{/if}
{if $firstCall}
    <script type="text/javascript">
        var module_dir = '{$smarty.const._MODULE_DIR_|escape:'htmlall':'UTF-8'}';
        var id_language = {$defaultFormLanguage|intval};
        var languages = new Array();
        var vat_number = {if $vat_number}1{else}0{/if};
        // Multilang field setup must happen before document is ready so that calls to displayFlags() to avoid
        // precedence conflicts with other document.ready() blocks
        {foreach $languages as $k => $language}
        languages[{$k|escape:'html':'UTF-8'}] = {
            id_lang: {$language.id_lang|escape:'html':'UTF-8'},
            iso_code: '{$language.iso_code|escape:'html':'UTF-8'}',
            name: '{$language.name|escape:'html':'UTF-8'}',
            is_default: '{$language.is_default|escape:'html':'UTF-8'}'
        };
        {/foreach}
        // we need allowEmployeeFormLang var in ajax request
        allowEmployeeFormLang = {$allowEmployeeFormLang|escape:'html':'UTF-8'};
        displayFlags(languages, id_language, allowEmployeeFormLang);

        $(document).ready(function () {

            $(".show_checkbox").click(function () {
                $(this).addClass('hidden')
                $(this).siblings('.checkbox').removeClass('hidden');
                $(this).siblings('.hide_checkbox').removeClass('hidden');
                return false;
            });
            $(".hide_checkbox").click(function () {
                $(this).addClass('hidden')
                $(this).siblings('.checkbox').addClass('hidden');
                $(this).siblings('.show_checkbox').removeClass('hidden');
                return false;
            });

            {if isset($fields_value.id_state)}
            if ($('#id_country') && $('#id_state')) {
                ajaxStates({$fields_value.id_state|escape:'htmlall':'UTF-8'});
                $('#id_country').change(function () {
                    ajaxStates();
                });
            }
            {/if}

            if ($(".datepicker").length > 0)
                $(".datepicker").datepicker({
                    prevText: '',
                    nextText: '',
                    dateFormat: 'yy-mm-dd'
                });

            if ($(".datetimepicker").length > 0)
                $('.datetimepicker').datetimepicker({
                    prevText: '',
                    nextText: '',
                    dateFormat: 'yy-mm-dd',
                    // Define a custom regional settings in order to use PrestaShop translation tools
                    currentText: '{l s='Now'  mod='paseocenterbulkedition'}',
                    closeText: '{l s='Done'  mod='paseocenterbulkedition'}',
                    ampm: false,
                    amNames: ['AM', 'A'],
                    pmNames: ['PM', 'P'],
                    timeFormat: 'hh:mm:ss tt',
                    timeSuffix: '',
                    timeOnlyTitle: '{l s='Choose Time' js=1  mod='paseocenterbulkedition'}',
                    timeText: '{l s='Time' js=1  mod='paseocenterbulkedition'}',
                    hourText: '{l s='Hour' js=1 mod='paseocenterbulkedition'}',
                    minuteText: '{l s='Minute' js=1 mod='paseocenterbulkedition'}',
                });
            {if isset($use_textarea_autosize)}
            $(".textarea-autosize").autosize();
            {/if}
        });
        state_token = '{getAdminToken tab='AdminStates'}';
        {block name="script"}{/block}
    </script>
{/if}
