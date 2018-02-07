{*
*
*  Please read the terms of the CLUF license attached to this module(cf "licences" folder)
*
*  @author    Pronimbo.
*  @copyright Pronimbo. all rights reserved.
*  @license   http://www.pronimbo.com/licenses/license_en.pdf http://www.pronimbo.com/licenses/license_es.pdf https://www.pronimbo.com/licenses/license_fr.pdf
*
*}

{block name="input_row"}
    <div class="form-group{if isset($input.form_group_class)} {$input.form_group_class|escape:'html':'UTF-8'}{/if}{if $input.type == 'hidden'} hide{/if}"{if isset($input.name) && $input.name == 'id_state'} id="contains_states"{if !$contains_states} style="display:none;"{/if}{/if} {if isset($tabs) && isset($input.tab)}data-tab-id="{$input.tab|escape:'html':'UTF-8'}"{/if}>
    {if $input.type == 'hidden'}
        <input type="hidden" name="{$input.name|escape:'html':'UTF-8'}"
               id="{$input.name|escape:'html':'UTF-8'}"
               value="{$fields_value[$input.name]|escape:'html':'UTF-8'}"/>
    {else}
        {block name="label"}
            {if isset($input.label)}
                <label for="{if isset($input.id)}{$input.id|escape:'html':'UTF-8'}{if isset($input.lang) AND $input.lang}_{$current_id_lang|escape:'html':'UTF-8'}{/if}{else}{$input.name|escape:'html':'UTF-8'}{if isset($input.lang) AND $input.lang}_{$current_id_lang|escape:'html':'UTF-8'}{/if}{/if}"
                       class="control-label col-lg-3 {if isset($input.required) && $input.required && $input.type != 'radio'}required{/if}">
                    {if isset($input.hint)}
                    <span class="label-tooltip" data-toggle="tooltip" data-html="true"
                          title="{if is_array($input.hint)}
                                                    {foreach $input.hint as $hint}
                                                        {if is_array($hint)}
                                                            {$hint.text|escape:'htmlall':'UTF-8'}
                                                        {else}
                                                            {$hint|escape:'htmlall':'UTF-8'}
                                                        {/if}
                                                    {/foreach}
                                                {else}
                                                    {$input.hint|escape:'htmlall':'UTF-8'}
                                                {/if}">
                                        {/if}
                        {$input.label|escape:'quotes':'UTF-8'}
                        {if isset($input.hint)}
                                        </span>
                    {/if}
                </label>
            {/if}
        {/block}

        {block name="field"}
            <div class="col-lg-{if isset($input.col)}{$input.col|intval}{else}9{/if}">
            {block name="input"}
                {if $input.type == 'text' || $input.type == 'tags'}
                    {if isset($input.lang) AND $input.lang}
                    {if $languages|count > 1}
                        <div class="form-group">
                            {/if}
                            {foreach $languages as $language}
                                {assign var='value_text' value=$fields_value[$input.name][$language.id_lang]}
                                {if $languages|count > 1}
                                    <div class="translatable-field lang-{$language.id_lang|escape:'html':'UTF-8'}" {if $language.id_lang != $defaultFormLanguage}style="display:none"{/if}>
                                    <div class="col-lg-11">
                                {/if}
                                {if $input.type == 'tags'}
                                    <script type="text/javascript">
                                        $().ready(function () {
                                            var input_id = '{if isset($input.id)}{$input.id|escape:'html':'UTF-8'}_{$language.id_lang|escape:'html':'UTF-8'}{else}{$input.name|escape:'html':'UTF-8'}_{$language.id_lang|escape:'html':'UTF-8'}{/if}';
                                            var tag_{$input.name|escape:'html':'UTF-8'} = $('#' + input_id);
                                            tag_{$input.name|escape:'html':'UTF-8'}.tagify({
                                                delimiters: [13, 44],
                                                addTagPrompt: '{l s='Add tag' mod='paseocenterbulkedition'}'
                                            });
                                            {if isset($input.autocomplete_url)}
                                            tag_{$input.name|escape:'html':'UTF-8'}.tagify('inputField').autocomplete(
                                                    {
                                                source: function (request, response) {
                                                    $.ajax({
                                                        type: "POST",
                                                        url: "{$input.autocomplete_url|escape:'html':'UTF-8'}",
                                                        data: {literal}"{'match': '" + request.term + "'}"{/literal},
                                                        dataType: "json",
                                                        contentType: "application/json",
                                                        success: function (data) {
                                                            response($.map(data, function (item) {
                                                                return {
                                                                    label: item,
                                                                    value: item
                                                                }
                                                            }));
                                                        }
                                                    });
                                                },
                                                position: { of: tag_{$input.name|escape:'html':'UTF-8'}.tagify('containerDiv')},
                                                close: function (event, ui) {
                                                    tag_{$input.name|escape:'html':'UTF-8'}.tagify('add');
                                                },
                                            });
                                            {/if}}

                                            $('#{$table|escape:'html':'UTF-8'}_form').submit(function () {
                                                $(this).find('#' + input_id).val($('#' + input_id).tagify('serialize'));
                                            });
                                        });
                                    </script>
                                {/if}
                            {if isset($input.maxchar) || isset($input.prefix) || isset($input.suffix)}
                                <div class="input-group {if isset($input.class)}{$input.class|escape:'html':'UTF-8'}{/if}">
                            {/if}
                                {if isset($input.maxchar)}
                                    <span id="{if isset($input.id)}{$input.id|escape:'html':'UTF-8'}_{$language.id_lang|escape:'html':'UTF-8'}{else}{$input.name|escape:'html':'UTF-8'}_{$language.id_lang|escape:'html':'UTF-8'}{/if}_counter"
                                          class="input-group-addon">
                                                    <span class="text-count-down">{$input.maxchar|escape:'htmlall':'UTF-8'}</span>
                                                </span>
                                {/if}
                                {if isset($input.prefix)}
                                    <span class="input-group-addon">
                                                      {$input.prefix|escape:'quotes':'UTF-8'}
                                                    </span>
                                {/if}
                                <input type="text"
                                       id="{if isset($input.id)}{$input.id|escape:'html':'UTF-8'}_{$language.id_lang|escape:'html':'UTF-8'}{else}{$input.name|escape:'html':'UTF-8'}_{$language.id_lang|escape:'html':'UTF-8'}{/if}"
                                       name="{$input.name|escape:'htmlall':'UTF-8'}_{$language.id_lang|escape:'htmlall':'UTF-8'}"
                                       class="{if $input.type == 'tags'}tagify {/if}{if isset($input.class)}{$input.class|escape:'html':'UTF-8'}{/if}"
                                       value="{if isset($input.string_format) && $input.string_format}{$value_text|string_format:$input.string_format|escape:'html':'UTF-8'}{else}{$value_text|escape:'html':'UTF-8'}{/if}"
                                {if isset($input.attr)}
                                    {if $input.attr|is_array}
                                        {foreach $input.attr as $k =>$attr}
                                            {$k|escape:'html':'UTF-8'}="{$attr|escape:'html':'UTF-8'}"
                                        {/foreach}
                                    {else}
                                        {$input.attr|escape:'html':'UTF-8'}
                                    {/if}

                                {/if}
                                {if isset($input.size)} size="{$input.size|escape:'html':'UTF-8'}"{/if}
                                {if isset($input.maxchar)} data-maxchar="{$input.maxchar|escape:'html':'UTF-8'}"{/if}
                                {if isset($input.maxlength)} maxlength="{$input.maxlength|escape:'html':'UTF-8'}"{/if}
                                {if isset($input.readonly) && $input.readonly} readonly="readonly"{/if}
                                {if isset($input.disabled) && $input.disabled} disabled="disabled"{/if}
                                {if isset($input.autocomplete) && !$input.autocomplete} autocomplete="off"{/if}
                                {if isset($input.autocomplete) && $input.autocomplete_url} autocomplete-url="{$input.autocomplete_url|escape:'html':'UTF-8'}"{/if}
                                {if isset($input.required) && $input.required} required="required" {/if}
                                {if isset($input.placeholder) && $input.placeholder} placeholder="{$input.placeholder|escape:'html':'UTF-8'}"{/if}
                                />
                                {if isset($input.suffix)}
                                    <span class="input-group-addon">
                                                      {$input.suffix|escape:'htmlall':'UTF-8'}
                                                    </span>
                                {/if}
                            {if isset($input.maxchar) || isset($input.prefix) || isset($input.suffix)}
                                </div>
                            {/if}
                                {if $languages|count > 1}
                                    </div>
                                    <div class="col-lg-1">
                                        <button type="button"
                                                class="btn btn-default dropdown-toggle lang-btn"
                                                tabindex="-1" data-toggle="dropdown" >
                                            {$language.iso_code|escape:'htmlall':'UTF-8'}
                                            {if $ps15}
                                                <span class="caret"></span>
                                            {else}
                                                <i class="icon-caret-down"></i>
                                            {/if}
                                        </button>
                                        <ul class="dropdown-menu">
                                            {foreach from=$languages item=language}
                                                <li>
                                                    <a href="javascript:hideOtherLanguage({$language.id_lang|escape:'htmlall':'UTF-8'});"
                                                       tabindex="-1">{$language.name|escape:'htmlall':'UTF-8'}</a>
                                                </li>
                                            {/foreach}
                                        </ul>
                                    </div>
                                    </div>
                                {/if}
                            {/foreach}
                            {if isset($input.maxchar)}
                                <script type="text/javascript">
                                    function countDown($source, $target) {
                                        var max = $source.attr("data-maxchar");
                                        $target.html(max - $source.val().length);

                                        $source.keyup(function () {
                                            $target.html(max - $source.val().length);
                                        });
                                    }

                                    $(document).ready(function () {
                                        {foreach from=$languages item=language}
                                        countDown($("#{if isset($input.id)}{$input.id|escape:'html':'UTF-8'}_{$language.id_lang|escape:'html':'UTF-8'}{else}{$input.name|escape:'html':'UTF-8'}_{$language.id_lang|escape:'html':'UTF-8'}{/if}"), $("#{if isset($input.id)}{$input.id|escape:'html':'UTF-8'}_{$language.id_lang|escape:'html':'UTF-8'}{else}{$input.name|escape:'html':'UTF-8'}_{$language.id_lang|escape:'html':'UTF-8'}{/if}_counter"));
                                        {/foreach}
                                    });
                                </script>
                            {/if}
                            {if $languages|count > 1}
                        </div>
                    {/if}
                    {else}
                    {if $input.type == 'tags'}
                    {literal}
                        <script type="text/javascript">

                            $().ready(function () {
                                var input_id = '{/literal}{if isset($input.id)}{$input.id|escape:'html':'UTF-8'}{else}{$input.name|escape:'html':'UTF-8'}{/if}{literal}';
                                var tag_{/literal}{$input.name|escape:'html':'UTF-8'}{literal} = $('#' + input_id);
                                tag_{/literal}{$input.name|escape:'html':'UTF-8'}{literal}.tagify({
                                    delimiters: [13, 44],
                                    addTagPrompt: '{/literal}{l s='Add tag'}{literal}',
                                });

                                {/literal}{if isset($input.autocomplete_url)}{literal}

                                $(tag_{/literal}{$input.name|escape:'html':'UTF-8'}{literal}).autocomplete({
                                    source: function (request, response) {
                                        $.ajax({
                                            type: "POST",
                                            url: "{/literal}{$input.autocomplete_url|escape:'html':'UTF-8'}{literal}",
                                            data: "{'match': '" + request.term + "'}",
                                            dataType: "json",
                                            contentType: "application/json",
                                            success: function (data) {
                                                response($.map(data, function (item) {
                                                    return {
                                                        label: item,
                                                        value: item,
                                                    }
                                                }));
                                            }
                                        });
                                    },
                                    position: {of: tag_{/literal}{$input.name|escape:'html':'UTF-8'}{literal}.tagify('containerDiv')},
                                    close: function (event, ui) {
                                        tag_{/literal}{$input.name|escape:'html':'UTF-8'}{literal}.tagify('add');
                                    },
                                });


                                {/literal}{/if}{literal}


                                $({/literal}'#{$table|escape:'html':'UTF-8'}{literal}_form').submit(function () {
                                    $(this).find('#' + input_id).val($('#' + input_id).tagify('serialize'));
                                });

                            });

                        </script>
                    {/literal}
                    {/if}
                        {assign var='value_text' value=$fields_value[$input.name]}
                    {if isset($input.maxchar) || isset($input.prefix) || isset($input.suffix)}
                        <div class="input-group {if isset($input.class)}{$input.class|escape:'html':'UTF-8'}{/if}">
                            {/if}
                            {if isset($input.maxchar)}
                                <span id="{if isset($input.id)}{$input.id|escape:'html':'UTF-8'}{else}{$input.name|escape:'html':'UTF-8'}{/if}_counter"
                                      class="input-group-addon"><span
                                            class="text-count-down">{$input.maxchar|escape:'html':'UTF-8'}</span></span>
                            {/if}
                            {if isset($input.prefix)}
                                <span class="input-group-addon">
                                          {$input.prefix|escape:'quotes':'UTF-8'}
                                        </span>
                            {/if}
                            <input type="text"
                                   name="{$input.name|escape:'htmlall':'UTF-8'}"
                                   id="{if isset($input.id)}{$input.id|escape:'html':'UTF-8'}{else}{$input.name|escape:'html':'UTF-8'}{/if}"
                                   value="{if isset($input.string_format) && $input.string_format}{$value_text|string_format:$input.string_format|escape:'html':'UTF-8'}{else}{$value_text|escape:'html':'UTF-8'}{/if}"
                                   class="{if $input.type == 'tags'}tagify {/if}{if isset($input.class)}{$input.class|escape:'html':'UTF-8'}{/if}"
                                    {if isset($input.size)} size="{$input.size|escape:'html':'UTF-8'}"{/if}
                                    {if isset($input.maxchar)} data-maxchar="{$input.maxchar|escape:'html':'UTF-8'}"{/if}
                                    {if isset($input.maxlength)} maxlength="{$input.maxlength|escape:'html':'UTF-8'}"{/if}
                                    {if isset($input.class)} class="{$input.class|escape:'html':'UTF-8'}"{/if}
                                    {if isset($input.readonly) && $input.readonly} readonly="readonly"{/if}
                                    {if isset($input.disabled) && $input.disabled} disabled="disabled"{/if}
                                    {if isset($input.autocomplete) && !$input.autocomplete} autocomplete="off"{/if}
                                    {if isset($input.autocomplete_url) && $input.autocomplete_url} autocomplete-url="{$input.autocomplete_url|escape:'html':'UTF-8'}"{/if}
                                    {if isset($input.required) && $input.required } required="required" {/if}
                                    {if isset($input.placeholder) && $input.placeholder } placeholder="{$input.placeholder|escape:'html':'UTF-8'}"{/if}
                                    />
                            {if isset($input.suffix)}
                                <span class="input-group-addon">
                                          {$input.suffix|escape:'html':'UTF-8'}
                                        </span>
                            {/if}

                            {if isset($input.maxchar) || isset($input.prefix) || isset($input.suffix)}
                        </div>
                    {/if}
                    {if isset($input.maxchar)}
                        <script type="text/javascript">
                            function countDown($source, $target) {
                                var max = $source.attr("data-maxchar");
                                $target.html(max - $source.val().length);

                                $source.keyup(function () {
                                    $target.html(max - $source.val().length);
                                });
                            }
                            $(document).ready(function () {
                                countDown($("#{if isset($input.id)}{$input.id|escape:'html':'UTF-8'}{else}{$input.name|escape:'html':'UTF-8'}{/if}"), $("#{if isset($input.id)}{$input.id|escape:'html':'UTF-8'}{else}{$input.name|escape:'html':'UTF-8'}{/if}_counter"));
                            });
                        </script>
                    {/if}
                    {/if}
                {elseif $input.type == 'ip-selector'}
                    <div class="row">
                        <div class="col-lg-8">
                            <input type="text"
                                   value="{$fields_value[$input.name]|escape:'html':'UTF-8'}"
                                   name="{$input.name|escape:'html':'UTF-8'}" size="5">
                        </div>
                        <div class="col-lg-1">
                            <button class="btn btn-default"
                                    onclick="addRemoteAddrMod('{$input.name|escape:'html':'UTF-8'}');"
                                    type="button">
                                <i class="icon-plus"></i>
                                {l s='Añadir mi IP' mod='paseocenterbulkedition'}
                            </button>
                        </div>
                    </div>
                {elseif $input.type == 'checkbox-panel'}
                    <div class="row">
                        <div class="col-xs-12">

                            {foreach $input.options.query as $value}
                                {assign var=id_checkbox value=$value[$input.options.id]}
                                <div class="checkbox col-xs-6 col-sm-4 col-md-3">
                                    <label for="{$input.name|escape:'htmlall':'UTF-8'}_{$id_checkbox|escape:'htmlall':'UTF-8'}"
                                           class="tree-item-name tree-selected">
                                        <input type="checkbox"
                                               name="{$input.name|escape:'htmlall':'UTF-8'}[]"
                                               id="{$input.name|escape:'htmlall':'UTF-8'}_{$id_checkbox|escape:'htmlall':'UTF-8'}"
                                               class="{if isset($input.class)}{$input.class|escape:'html':'UTF-8'}{/if}"
                                               {if isset($value[$input.options.id])}value="{$value[$input.options.id]|escape:'html':'UTF-8'}"{/if}
                                                {if $id_checkbox|in_array:$fields_value[$input.name]}checked="checked"{/if} />
                                        {$value[$input.options.name]|escape:'htmlall':'UTF-8'}
                                    </label>
                                </div>
                            {/foreach}
                        </div>
                    </div>
                {elseif $input.type == 'textbutton'}
                    {assign var='value_text' value=$fields_value[$input.name]}
                    <div class="row">
                        <div class="col-lg-9">
                            {if isset($input.maxchar)}
                            <div class="input-group">
                                            <span id="{if isset($input.id)}{$input.id|escape:'html':'UTF-8'}{else}{$input.name|escape:'html':'UTF-8'}{/if}_counter"
                                                  class="input-group-addon">
                                                <span class="text-count-down">{$input.maxchar|escape:'htmlall':'UTF-8'}</span>
                                            </span>
                                {/if}
                                <input type="text"
                                       name="{$input.name|escape:'htmlall':'UTF-8'}"
                                       id="{if isset($input.id)}{$input.id|escape:'html':'UTF-8'}{else}{$input.name|escape:'html':'UTF-8'}{/if}"
                                       value="{if isset($input.string_format) && $input.string_format}{$value_text|string_format:$input.string_format|escape:'html':'UTF-8'}{else}{$value_text|escape:'html':'UTF-8'}{/if}"
                                       class="{if $input.type == 'tags'}tagify {/if}{if isset($input.class)}{$input.class|escape:'html':'UTF-8'}{/if}"
                                        {if isset($input.size)} size="{$input.size|escape:'html':'UTF-8'}"{/if}
                                        {if isset($input.maxchar)} data-maxchar="{$input.maxchar|escape:'html':'UTF-8'}"{/if}
                                        {if isset($input.maxlength)} maxlength="{$input.maxlength|escape:'html':'UTF-8'}"{/if}
                                        {if isset($input.class)} class="{$input.class|escape:'html':'UTF-8'}"{/if}
                                        {if isset($input.readonly) && $input.readonly} readonly="readonly"{/if}
                                        {if isset($input.disabled) && $input.disabled} disabled="disabled"{/if}
                                        {if isset($input.autocomplete) && !$input.autocomplete} autocomplete="off"{/if}
                                        {if isset($input.placeholder) && $input.placeholder } placeholder="{$input.placeholder|escape:'html':'UTF-8'}"{/if}
                                        />
                                {if isset($input.suffix)}{$input.suffix|escape:'html':'UTF-8'}{/if}
                                {if isset($input.maxchar)}
                            </div>
                            {/if}
                        </div>
                        <div class="col-lg-2">
                            <button type="button"
                                    class="btn btn-default{if isset($input.button.attributes['class'])} {$input.button.attributes['class']|escape:'html':'UTF-8'}{/if}{if isset($input.button.class)} {$input.button.class|escape:'html':'UTF-8'}{/if}"
                            {foreach from=$input.button.attributes key=name item=value}
                                {if $name|lower != 'class'}
                                    {$name|escape:'htmlall':'UTF-8'}="{$value|escape:'htmlall':'UTF-8'}"
                                {/if}
                            {/foreach} >
                            {if isset($input.button.label)}
                                {$input.button.label|escape:'htmlall':'UTF-8'}
                            {/if}
                            </button>
                        </div>
                    </div>
                    {if isset($input.maxchar)}
                        <script type="text/javascript">
                            function countDown($source, $target) {
                                var max = $source.attr("data-maxchar");
                                $target.html(max - $source.val().length);
                                $source.keyup(function () {
                                    $target.html(max - $source.val().length);
                                });
                            }
                            $(document).ready(function () {
                                countDown($("#{if isset($input.id)}{$input.id|escape:'html':'UTF-8'}{else}{$input.name|escape:'html':'UTF-8'}{/if}"), $("#{if isset($input.id)}{$input.id|escape:'html':'UTF-8'}{else}{$input.name|escape:'html':'UTF-8'}{/if}_counter"));
                            });
                        </script>
                    {/if}
                {elseif $input.type == 'select2'}
                    <div class="col-xs-6 col-sm-4">
                        <select multiple="multiple"
                                {if isset($input.placeholder)}placeholder="{$input.placeholder|escape:'html':'UTF-8'}"{/if}
                                name="{$input.name|escape:'html':'UTF-8'}"
                                class="{if isset($input.class)}{$input.class|escape:'html':'UTF-8'}{/if} fixed-width-xl"
                                id="{if isset($input.id)}{$input.id|escape:'html':'UTF-8'}{else}{$input.name|escape:'html':'UTF-8'}{/if}"
                                data-tags="true"
                                data-placeholder="{l s='Select an option' mod='paseocenterbulkedition'}" {if isset($input.source_url)}data-ajax--url="{$input.source_url|escape:'html':'UTF-8'}"{/if} {if isset($input.source_cache) && $input.source_cache}data-ajax--cache="true" {/if}></select>
                    </div>
                    <script type="text/javascript">
                        $(document).ready(
                                function () {ldelim}
                                    $('select[name={$input.name|escape:'html':'UTF-8'}]').select2(
                                            {
                                                ldelim
                                            }
                                    tags: true,
                                            {if isset($input.source_url)}

                                            ajax
                                    :
                                    {
                                        ldelim
                                    }
                                    url: $(this).attr('data-ajax--url'),
                                            {if isset($input.source_cache)}
                                            cache
                                    :
                                    $(this).attr('data-ajax--cache'),
                                            {/if}
                                            dataType
                                    :
                                    'json',
                                            delay
                                    :
                                    250,
                                            templateResult
                                    :
                                    function (data) {ldelim}

                                        var dataFormatted = $(
                                                '<span>' + data.name + '</span>'
                                        );
                                        return dataFormatted;

                                        {rdelim
                                    }

                                    ,
                                    data: function (params) {ldelim}
                                        return {
                                            ldelim
                                        }
                                        q: params.term, // search term
                                                page
                                    :
                                        params.page
                                        {rdelim
                                    }
                                    ;
                                    {rdelim
                                },
                                processResults
                        :
                        function (data, page) {ldelim}
// parse the results into the format expected by Select2.
// since we are using custom formatting functions we do not need to
// alter the remote JSON data
                            return {
                                ldelim
                            }
                            results: data
                            {rdelim
                        }
                        ;
                        {rdelim
                        }
                        ,
                        {rdelim
                        }
                        {/if}
                        {rdelim
                        }
                        )
                        ;

                        {rdelim
                        })
                        ;
                    </script>
                {elseif $input.type == 'select'}
                    {if isset($input.options.query) && !$input.options.query && isset($input.empty_message)}
                        {$input.empty_message|escape:'htmlall':'UTF-8'}
                        {$input.required = false}
                        {$input.desc = null}
                    {else}
                        <select name="{$input.name|escape:'html':'UTF-8'}"
                                class="{if isset($input.class)}{$input.class|escape:'html':'UTF-8'}{/if} fixed-width-xl"
                                id="{if isset($input.id)}{$input.id|escape:'html':'UTF-8'}{else}{$input.name|escape:'html':'UTF-8'}{/if}"
                                {if isset($input.multiple)}multiple="multiple" {/if}
                                {if isset($input.disabled)}disabled {/if}
                                {if isset($input.size)}size="{$input.size|escape:'html':'UTF-8'}"{/if}
                                {if isset($input.onchange)}onchange="{$input.onchange|escape:'html':'UTF-8'}"{/if}>
                            {if isset($input.options.default)}
                                <option value="{$input.options.default.value|escape:'html':'UTF-8'}">{$input.options.default.label|escape:'html':'UTF-8'}</option>
                            {/if}
                            {if isset($input.options.optiongroup)}
                                {foreach $input.options.optiongroup.query AS $optiongroup}
                                    <optgroup
                                            label="{$optiongroup[$input.options.optiongroup.label]|escape:'htmlall':'UTF-8'}">
                                        {foreach $optiongroup[$input.options.options.query] as $option}
                                            <option value="{$option[$input.options.options.id]|escape:'htmlall':'UTF-8'}"
                                                    {if isset($input.multiple)}
                                                        {foreach $fields_value[$input.name] as $field_value}
                                                            {if $field_value == $option[$input.options.options.id]}selected="selected"{/if}
                                                        {/foreach}
                                                    {else}
                                                        {if $fields_value[$input.name] == $option[$input.options.options.id]}selected="selected"{/if}
                                                    {/if}
                                                    >{$option[$input.options.options.name]|escape:'htmlall':'UTF-8'}</option>
                                        {/foreach}
                                    </optgroup>
                                {/foreach}
                            {else}

                                {foreach $input.options.query AS $option}
                                    {if is_object($option)}
                                        <option value="{$option->$input.options.id|escape:'htmlall':'UTF-8'}"
                                                {if isset($input.multiple)}
                                                    {foreach $fields_value[$input.name] as $field_value}
                                                        {if $field_value == '' AND $smarty.foreach.option.index == 0}
                                                            selected="selected"
                                                        {elseif $field_value == $option->$input.options.id}
                                                            selected="selected"
                                                        {/if}
                                                    {/foreach}
                                                {else}
                                                    {if $fields_value[$input.name] == $option->$input.options.id}
                                                        selected="selected"
                                                    {/if}
                                                {/if}
                                                >{$option->$input.options.name|escape:'htmlall':'UTF-8'}</option>
                                    {elseif $option == "-"}
                                        <option value="">-</option>
                                    {else}
                                        <option value="{$option[$input.options.id]|escape:'htmlall':'UTF-8'}"
                                                {if isset($input.multiple)}
                                                    {foreach $fields_value[$input.name] as $field_value}
                                                        {if $field_value == $option[$input.options.id]}
                                                            selected="selected"
                                                        {/if}
                                                    {/foreach}
                                                {else}
                                                    {if $fields_value[$input.name] == $option[$input.options.id]}
                                                        selected="selected"
                                                    {/if}
                                                {/if}
                                                >{$option[$input.options.name]|escape:'htmlall':'UTF-8'}</option>
                                    {/if}
                                {/foreach}
                            {/if}
                        </select>
                    {/if}
                {elseif $input.type == 'radio'}
                    {foreach $input.values as $value}
                        <div class="radio {if isset($input.class)}{$input.class|escape:'html':'UTF-8'}{/if}">
                            <label>
                                <input type="radio" name="{$input.name|escape:'html':'UTF-8'}"
                                       id="{$value.id|escape:'html':'UTF-8'}"
                                       value="{$value.value|escape:'html':'UTF-8'}"
                                       {if $fields_value[$input.name] == $value.value}checked="checked"{/if}
                                        {if isset($input.disabled) && $input.disabled}disabled="disabled"{/if} />
                                {if isset($value.label)}
                                    {$value.label|escape:'html':'UTF-8'}
                                {/if}
                            </label>
                        </div>
                        {if isset($value.p) && $value.p}<p
                                class="help-block">{$value.p}</p>{/if}
                    {/foreach}
                {elseif $input.type == 'switch'}
                    <span class="switch prestashop-switch fixed-width-lg">
                                        {foreach $input.values as $value}
                                            <input
                                                    type="radio"
                                                    name="{$input.name|escape:'htmlall':'UTF-8'}"
                                                    {if $value.value == 1}
                                                        id="{$input.name|escape:'htmlall':'UTF-8'}_on"
                                                    {else}
                                                        id="{$input.name|escape:'htmlall':'UTF-8'}_off"
                                                    {/if}
                                                    value="{$value.value|escape:'htmlall':'UTF-8'}"
                                                    {if $fields_value[$input.name] == $value.value}checked="checked"{/if}
                                                    {if isset($input.disabled) && $input.disabled}disabled="disabled"{/if}
                                                    />
                                            <label
                                                    {if $value.value == 1}
                                                        for="{$input.name|escape:'htmlall':'UTF-8'}_on"
                                                    {else}
                                                        for="{$input.name|escape:'htmlall':'UTF-8'}_off"
                                                    {/if}
                                                    >
                                                {if $value.value == 1}
                                                    {l s='Yes' mod='paseocenterbulkedition'}
                                                {else}
                                                    {l s='No' mod='paseocenterbulkedition'}
                                                {/if}
                                            </label>
                                        {/foreach}
                        <a class="slide-button btn"></a>
                                    </span>
                {elseif $input.type == 'textarea'}
                    {assign var=use_textarea_autosize value=true}
                    {if isset($input.lang) AND $input.lang}
                        {foreach $languages as $language}
                            {if $languages|count > 1}
                                <div class="form-group translatable-field lang-{$language.id_lang|escape:'html':'UTF-8'}"  {if $language.id_lang != $defaultFormLanguage}style="display:none;"{/if}>

                                <div class="col-lg-11">
                            {/if}
                            <textarea id="{$input.name|escape:'html':'UTF-8'}_{$language.id_lang|escape:'html':'UTF-8'}"
                                      name="{$input.name|escape:'html':'UTF-8'}_{$language.id_lang|escape:'html':'UTF-8'}"
                                    {if isset($input.disabled) && $input.disabled} disabled {/if}
                                     class="{if isset($input.autoload_rte) && $input.autoload_rte}rte autoload_rte {if isset($input.class)}{$input.class|escape:'html':'UTF-8'}{/if}{else}{if isset($input.class)}{$input.class|escape:'html':'UTF-8'}{else}textarea-autosize{/if}{/if}">{$fields_value[$input.name][$language.id_lang]|escape:'html':'UTF-8'}</textarea>
                            {if $languages|count > 1}
                                </div>
                                <div class="col-lg-1">
                                    <button type="button"
                                            class="btn btn-default dropdown-toggle lang-btn"
                                            tabindex="-1" data-toggle="dropdown">
                                        {$language.iso_code|escape:'htmlall':'UTF-8'}
                                        <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu">
                                        {foreach from=$languages item=language}
                                            <li>
                                                <a href="javascript:hideOtherLanguage({$language.id_lang|escape:'htmlall':'UTF-8'});"
                                                   tabindex="-1">{$language.name|escape:'htmlall':'UTF-8'}</a>
                                            </li>
                                        {/foreach}
                                    </ul>
                                </div>
                                </div>
                            {/if}
                        {/foreach}

                    {else}
                        <textarea name="{$input.name|escape:'html':'UTF-8'}"
                                  id="{if isset($input.id)}{$input.id|escape:'html':'UTF-8'}{else}{$input.name|escape:'html':'UTF-8'}{/if}"
                                  {if isset($input.cols)}cols="{$input.cols|escape:'html':'UTF-8'}"{/if} {if isset($input.rows)}rows="{$input.rows|escape:'html':'UTF-8'}"{/if}
                                  class="{if isset($input.autoload_rte) && $input.autoload_rte}rte autoload_rte {if isset($input.class)}{$input.class|escape:'html':'UTF-8'}{/if}{else}textarea-autosize{/if}">{$fields_value[$input.name]|escape:'html':'UTF-8'}</textarea>
                    {/if}
                {elseif $input.type == 'checkbox'}
                    {if isset($input.expand)}
                        <a class="btn btn-default show_checkbox{if strtolower($input.expand.default) == 'hide'} hidden {/if}"
                           href="#">
                            <i class="icon-{$input.expand.show.icon|escape:'htmlall':'UTF-8'}"></i>
                            {$input.expand.show.text|escape:'htmlall':'UTF-8'}
                            {if isset($input.expand.print_total) && $input.expand.print_total > 0}
                                <span class="badge">{$input.expand.print_total|escape:'htmlall':'UTF-8'}</span>
                            {/if}
                        </a>
                        <a class="btn btn-default hide_checkbox{if strtolower($input.expand.default) == 'show'} hidden {/if}"
                           href="#">
                            <i class="icon-{$input.expand.hide.icon|escape:'htmlall':'UTF-8'}"></i>
                            {$input.expand.hide.text|escape:'htmlall':'UTF-8'}
                            {if isset($input.expand.print_total) && $input.expand.print_total > 0}
                                <span class="badge">{$input.expand.print_total|escape:'htmlall':'UTF-8'}</span>
                            {/if}
                        </a>
                    {/if}
                    {foreach $input.values.query as $value}
                        {assign var=id_checkbox value=$input.name|cat:'_'|cat:$value[$input.values.id]}
                        <div class="checkbox{if isset($input.expand) && strtolower($input.expand.default) == 'show'} hidden {/if}">
                            <label for="{$id_checkbox|escape:'htmlall':'UTF-8'}">
                                <input type="checkbox"
                                       name="{$id_checkbox|escape:'htmlall':'UTF-8'}"
                                       id="{$id_checkbox|escape:'htmlall':'UTF-8'}"
                                       class="{if isset($input.class)}{$input.class|escape:'html':'UTF-8'}{/if}"
                                       {if isset($value.val)}value="{$value.val|escape:'html':'UTF-8'}"{/if}
                                        {if isset($fields_value[$id_checkbox]) && $fields_value[$id_checkbox]}checked="checked"{/if} />
                                {$value[$input.values.name]|escape:'htmlall':'UTF-8'}
                            </label>
                        </div>
                    {/foreach}
                {elseif $input.type == 'change-password'}
                    <div class="row">
                        <div class="col-lg-12">
                            <button type="button"
                                    id="{$input.name|escape:'htmlall':'UTF-8'}-btn-change"
                                    class="btn btn-default">
                                <i class="icon-lock"></i>
                                {l s='Change password...' mod='paseocenterbulkedition'}
                            </button>
                            <div id="{$input.name|escape:'htmlall':'UTF-8'}-change-container"
                                 class="form-password-change well hide">
                                <div class="form-group">
                                    <label for="old_passwd"
                                           class="control-label col-lg-2 required">
                                        {l s='Current password'  mod='paseocenterbulkedition'}
                                    </label>

                                    <div class="col-lg-10">
                                        <div class="input-group fixed-width-lg">
                                                            <span class="input-group-addon">
                                                                <i class="icon-unlock"></i>
                                                            </span>
                                            <input type="password" id="old_passwd"
                                                   name="old_passwd" class="form-control"
                                                   value="" required="required"
                                                   autocomplete="off">
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="form-group">
                                    <label for="{$input.name|escape:'htmlall':'UTF-8'}"
                                           class="required control-label col-lg-2">
                                                        <span class="label-tooltip" data-toggle="tooltip"
                                                              data-html="true" title=""
                                                              data-original-title="Minimum of 8 characters.">
                                                            {l s='New password'  mod='paseocenterbulkedition'}
                                                        </span>
                                    </label>

                                    <div class="col-lg-9">
                                        <div class="input-group fixed-width-lg">
                                                            <span class="input-group-addon">
                                                                <i class="icon-key"></i>
                                                            </span>
                                            <input type="password"
                                                   id="{$input.name|escape:'html':'UTF-8'}"
                                                   name="{$input.name|escape:'html':'UTF-8'}"
                                                   class="{if isset($input.class)}{$input.class|escape:'html':'UTF-8'}{/if}"
                                                   value="" required="required"
                                                   autocomplete="off"/>
                                        </div>
                                        <span id="{$input.name|escape:'htmlall':'UTF-8'}-output"></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="{$input.name|escape:'htmlall':'UTF-8'}2"
                                           class="required control-label col-lg-2">
                                        {l s='Confirm password' mod='paseocenterbulkedition'}
                                    </label>

                                    <div class="col-lg-5">
                                        <div class="input-group fixed-width-lg">
                                                            <span class="input-group-addon">
                                                                <i class="icon-key"></i>
                                                            </span>
                                            <input type="password"
                                                   id="{$input.name|escape:'htmlall':'UTF-8'}2"
                                                   name="{$input.name|escape:'htmlall':'UTF-8'}2"
                                                   class="{if isset($input.class)}{$input.class|escape:'htmlall':'UTF-8'}{/if}"
                                                   value="" autocomplete="off"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-lg-10 col-lg-offset-2">
                                        <input type="text"
                                               class="form-control fixed-width-md pull-left"
                                               id="{$input.name|escape:'htmlall':'UTF-8'}-generate-field"
                                               disabled="disabled">
                                        <button type="button"
                                                id="{$input.name|escape:'html':'UTF-8'}-generate-btn"
                                                class="btn btn-default">
                                            <i class="icon-random"></i>
                                            {l s='Generate password' mod='paseocenterbulkedition'}
                                        </button>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-lg-10 col-lg-offset-2">
                                        <p class="checkbox">
                                            <label for="{$input.name|escape:'htmlall':'UTF-8'}-checkbox-mail">
                                                <input name="passwd_send_email"
                                                       id="{$input.name|escape:'htmlall':'UTF-8'}-checkbox-mail"
                                                       type="checkbox" checked="checked">
                                                {l s='Send me this new password by Email' mod='paseocenterbulkedition'}
                                            </label>
                                        </p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <button type="button"
                                                id="{$input.name|escape:'htmlall':'UTF-8'}-cancel-btn"
                                                class="btn btn-default">
                                            <i class="icon-remove"></i>
                                            {l s='Cancel' mod='paseocenterbulkedition'}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <script>
                        $(function () {
                            var $oldPwd = $('#old_passwd');
                            var $passwordField = $('#{$input.name|escape:'htmlall':'UTF-8'}');
                            var $output = $('#{$input.name|escape:'htmlall':'UTF-8'}-output');
                            var $generateBtn = $('#{$input.name|escape:'htmlall':'UTF-8'}-generate-btn');
                            var $generateField = $('#{$input.name|escape:'htmlall':'UTF-8'}-generate-field');
                            var $cancelBtn = $('#{$input.name|escape:'htmlall':'UTF-8'}-cancel-btn');

                            var feedback = [
                                {
                                    badge: 'text-danger',
                                    text: '{l s='Invalid' js=1 mod='paseocenterbulkedition'}'
                                },
                                {
                                    badge: 'text-warning',
                                    text: '{l s='Okay' js=1 mod='paseocenterbulkedition'}'
                                },
                                {
                                    badge: 'text-success',
                                    text: '{l s='Good' js=1 mod='paseocenterbulkedition'}'
                                },
                                {
                                    badge: 'text-success',
                                    text: '{l s='Fabulous' js=1 mod='paseocenterbulkedition'}'
                                }
                            ];
                            $.passy.requirements.length.min = 8;
                            $.passy.requirements.characters = 'DIGIT';
                            $passwordField.passy(function (strength, valid) {
                                $output.text(feedback[strength].text);
                                $output.removeClass('text-danger').removeClass('text-warning').removeClass('text-success');
                                $output.addClass(feedback[strength].badge);
                                if (valid) {
                                    $output.show();
                                }
                                else {
                                    $output.hide();
                                }
                            });
                            var $container = $('#{$input.name|escape:'htmlall':'UTF-8'}-change-container');
                            var $changeBtn = $('#{$input.name|escape:'htmlall':'UTF-8'}-btn-change');
                            var $confirmPwd = $('#{$input.name|escape:'htmlall':'UTF-8'}2');

                            $changeBtn.on('click', function () {
                                $container.removeClass('hide');
                                $changeBtn.addClass('hide');
                            });
                            $generateBtn.click(function () {
                                $generateField.passy('generate', 8);
                                var generatedPassword = $generateField.val();
                                $passwordField.val(generatedPassword);
                                $confirmPwd.val(generatedPassword);
                            });
                            $cancelBtn.on('click', function () {
                                $container.find("input").val("");
                                $container.addClass('hide');
                                $changeBtn.removeClass('hide');
                            });

                            $.validator.addMethod('password_same', function (value, element) {
                                return $passwordField.val() == $confirmPwd.val();
                            }, '{l s='Invalid password confirmation' js=1  mod='paseocenterbulkedition'}');

                            $('#employee_form').validate({
                                rules: {
                                    "email": {
                                        email: true
                                    },
                                    "{$input.name|escape:'htmlall':'UTF-8'}": {
                                        minlength: 8
                                    },
                                    "{$input.name|escape:'htmlall':'UTF-8'}2": {
                                        password_same: true
                                    },
                                    "old_passwd": {},
                                },
                                // override jquery validate plugin defaults for bootstrap 3
                                highlight: function (element) {
                                    $(element).closest('.form-group').addClass('has-error');
                                },
                                unhighlight: function (element) {
                                    $(element).closest('.form-group').removeClass('has-error');
                                },
                                errorElement: 'span',
                                errorClass: 'help-block',
                                errorPlacement: function (error, element) {
                                    if (element.parent('.input-group').length) {
                                        error.insertAfter(element.parent());
                                    } else {
                                        error.insertAfter(element);
                                    }
                                }
                            });
                        });
                    </script>
                {elseif $input.type == 'password'}
                    <div class="input-group fixed-width-lg">
                                        <span class="input-group-addon">
                                            <i class="icon-key"></i>
                                        </span>
                        <input type="password"
                               id="{if isset($input.id)}{$input.id|escape:'html':'UTF-8'}{else}{$input.name|escape:'html':'UTF-8'}{/if}"
                               name="{$input.name|escape:'htmlall':'UTF-8'}"
                               class="{if isset($input.class)}{$input.class|escape:'html':'UTF-8'}{/if}"
                               value=""
                               {if isset($input.autocomplete) && !$input.autocomplete}autocomplete="off"{/if}
                                {if isset($input.required) && $input.required } required="required" {/if} />
                    </div>
                {elseif $input.type == 'birthday'}
                    <div class="form-group">
                        {foreach $input.options as $key => $select}
                            <div class="col-lg-2">
                                <select name="{$key|escape:'html':'UTF-8'}"
                                        class="{if isset($input.class)}{$input.class|escape:'html':'UTF-8'}{/if}"
                                        class="fixed-width-lg">
                                    <option value="">-</option>
                                    {if $key == 'months'}
                                        This comment is useful to the translator tools /!\ do not remove them
                                        {l s='January' mod='paseocenterbulkedition'}
                                        {l s='February' mod='paseocenterbulkedition'}
                                        {l s='March' mod='paseocenterbulkedition'}
                                        {l s='April' mod='paseocenterbulkedition'}
                                        {l s='May' mod='paseocenterbulkedition'}
                                        {l s='June' mod='paseocenterbulkedition'}
                                        {l s='July' mod='paseocenterbulkedition'}
                                        {l s='August' mod='paseocenterbulkedition'}
                                        {l s='September' mod='paseocenterbulkedition'}
                                        {l s='October' mod='paseocenterbulkedition'}
                                        {l s='November' mod='paseocenterbulkedition'}
                                        {l s='December' mod='paseocenterbulkedition'}
                                        {foreach $select as $k => $v}
                                            <option value="{$k|escape:'html':'UTF-8'}"
                                                    {if $k == $fields_value[$key]}selected="selected"{/if}>{l s=$v  mod='paseocenterbulkedition'}</option>
                                        {/foreach}
                                    {else}
                                        {foreach $select as $v}
                                            <option value="{$v|escape:'html':'UTF-8'}"
                                                    {if $v == $fields_value[$key]}selected="selected"{/if}>{$v|escape:'html':'UTF-8'}</option>
                                        {/foreach}
                                    {/if}
                                </select>
                            </div>
                        {/foreach}
                    </div>
                {elseif $input.type == 'group'}
                    {assign var=groups value=$input.values}
                    {include file='helpers/form/form_group.tpl'}
                {elseif $input.type == 'shop'}
                    {*Needed this escape format to correct compile the ShopTreeShop rendered html*}
                    {$input.html|escape:'UTF-8'}
                {elseif $input.type == 'categories'}
                    {*Needed this escape format to correct compile the ShopTreeCategory rendered html*}
                    {$categories_tree|escape:'UTF-8'}
                {elseif $input.type == 'file'}
                    {*Needed this escape format to correct compile the HelperImageUploader rendered html*}
                    {if isset($input.file)}
                         {$input.file|escape:'UTF-8'}
                    {else}
                        {if isset($input.display_image) && $input.display_image|intval eq 1}
                            {if isset($fields_value.image)}
                                <div id="image">
                                    {$fields_value.image|escape:'UTF-8'}
                                    <p align="center">{l s='File size' mod='paseocenterbulkedition'} {$fields_value.size|escape:'html':'UTF-8'}kb</p>
                                    <a href="{$current|escape:'html':'UTF-8'}&{$identifier|escape:'html':'UTF-8'}={$form_id|escape:'html':'UTF-8'}&token={$token|escape:'html':'UTF-8'}&deleteImage=1">
                                        <img src="../img/admin/delete.gif" alt="{l s='Delete' mod='paseocenterbulkedition'}" /> {l s='Delete' mod='paseocenterbulkedition'}
                                    </a>
                                </div><br />
                            {/if}
                        {/if}
                        <input type="file" name="{$input.name|escape:'html':'UTF-8'}" {if isset($input.id)}id="{$input.id|escape:'html':'UTF-8'}"{/if} />
                        {if !empty($input.hint)}<span class="hint" name="help_box">{$input.hint|escape:'quotes':'UTF-8'}<span class="hint-pointer">&nbsp;</span></span>{/if}
                    {/if}
                {elseif $input.type == 'categories_select'}
                    {$input.category_tree|escape:'quotes':'UTF-8'}
                {elseif $input.type == 'asso_shop' && isset($asso_shop) && $asso_shop}
                    {*Needed this escape format to correct compile the ShopTreeShop rendered html*}
                    {$asso_shop|escape:'UTF-8'}
                {elseif $input.type == 'group-buttons'}
                    <div class="form-group">
                    {foreach from=$input.buttons name=buttons key=k item=button}
                        <div class="col-xs-6 col-md-{if isset($button.col)}{$button.col|intval}{else}3{/if} ">
                        {if isset($button.label)}
                            <div class="col-xs-12 row">
                            {if $button.type == 'color'}
                                <div class="input-group">
                                    <input type="color"
                                           data-hex="true"
                                           {if isset($button.class)}class="{$button.class}"
                                           {else}class="color mColorPickerInput"
                                            {/if}
                                           name="{$button.name|escape:'htmlall':'UTF-8'}"
                                           value="{$fields_value[$button.name]|escape:'html':'UTF-8'}"/>
                                </div>
                            {elseif $button.type == 'html'}
                                {if isset($button.html_content)}
                                    {$button.html_content|escape:'quotes':'UTF-8'}
                                {else}
                                    {$button.name|escape:'html':'UTF-8'}
                                {/if}
                            {elseif $button.type == 'radio'}
                            {foreach $button.values as $value}
                                <div class="radio {if isset($button.class)}{$button.class|escape:'html':'UTF-8'}{/if}">
                                    <label>
                                        <input type="radio" name="{$button.name|escape:'html':'UTF-8'}"
                                               id="{$value.id|escape:'html':'UTF-8'}"
                                               value="{$value.value|escape:'html':'UTF-8'}"
                                               {if $fields_value[$button.name] == $value.value}checked="checked"{/if}
                                                {if isset($button.disabled) && $button.disabled|intval eq 1}disabled="disabled"{/if} />
                                        {$value.label|escape:'html':'UTF-8'}
                                    </label>
                                </div>
                            {if isset($value.p) && $value.p}<p
                                    class="help-block">{$value.p|escape:'html':'UTF-8'}</p>{/if}
                            {/foreach}
                            {elseif $button.type == 'button'}
                                <a {if isset($button.target)}target="{$button.target|escape:'html':'UTF-8'}"{/if}  {if isset($button.disabled) && $button.disabled|intval eq 1} disabled {/if}  {if isset($button.id)}id="{$button.id|escape:'html':'UTF-8'}" {/if}
                                        class="btn btn-default {if isset($button.class)} {$button.class|escape:'html':'UTF-8'} {/if}" {if isset($button.href)}href="{$button.href|escape:'html':'UTF-8'}"{/if} >{$button.inner_label|escape:'html':'UTF-8'}</a>
                            {elseif $button.type == 'select'}
                            {if isset($button.options.query) && !$button.options.query && isset($button.empty_message)}
                                {$button.empty_message|escape:'htmlall':'UTF-8'}
                                {$button.required = false}
                                {$button.desc = null}
                            {else}
                                <div class="pull-right">
                                <select class="fixed-width-lg" name="{$button.name|escape:'html':'UTF-8'}"
                                        class="{if isset($button.class)}{$button.class|escape:'html':'UTF-8'}{/if}"
                                        id="{if isset($button.id)}{$button.id|escape:'html':'UTF-8'}{else}{$button.name|escape:'html':'UTF-8'}{/if}"
                                        {if isset($button.multiple)}multiple="multiple" {/if}
                                        {if isset($button.disabled) && $button.disabled|intval eq 1}disabled {/if}
                                        {if isset($button.size)}size="{$button.size|escape:'html':'UTF-8'}"{/if}
                                        {if isset($button.onchange)}onchange="{$button.onchange|escape:'html':'UTF-8'}"{/if}>
                                    {if isset($button.options.default)}
                                        <option value="{$button.options.default.value|escape:'html':'UTF-8'}">{$button.options.default.label|escape:'html':'UTF-8'}</option>
                                    {/if}
                                    {if isset($button.options.optiongroup)}
                                        {foreach $button.options.optiongroup.query AS $optiongroup}
                                            <optgroup
                                                    label="{$optiongroup[$button.options.optiongroup.label]|escape:'htmlall':'UTF-8'}">
                                                {if isset($optiongroup[$button.options.options.query])}
                                                    {foreach $optiongroup[$button.options.options.query] as $option}
                                                        <option value="{$option[$button.options.options.id]|escape:'htmlall':'UTF-8'}"
                                                                {if isset($button.multiple)}
                                                                    {foreach $fields_value[$button.name] as $field_value}
                                                                        {if $field_value == $option[$button.options.options.id]}selected="selected"{/if}
                                                                    {/foreach}
                                                                {else}
                                                                    {if $fields_value[$button.name] == $option[$button.options.options.id]}selected="selected"{/if}
                                                                {/if}
                                                                >{$option[$button.options.options.name]|escape:'htmlall':'UTF-8'}</option>
                                                    {/foreach}
                                                {/if}
                                            </optgroup>
                                        {/foreach}
                                    {else}
                                        {if isset($button.options.query)}
                                            {foreach $button.options.query AS $option}
                                                {if is_object($option)}
                                                    <option value="{$option->$button.options.id|escape:'htmlall':'UTF-8'}"
                                                            {if isset($button.multiple)}
                                                                {foreach $fields_value[$button.name] as $field_value}
                                                                    {if $field_value == '' AND $smarty.foreach.option.index == 0}
                                                                        selected="selected"
                                                                    {elseif $field_value == $option->$button.options.id}
                                                                        selected="selected"
                                                                    {/if}
                                                                {/foreach}
                                                            {else}
                                                                {if $fields_value[$button.name] == $option->$button.options.id}
                                                                    selected="selected"
                                                                {/if}
                                                            {/if}
                                                            >{$option->$button.options.name|escape:'htmlall':'UTF-8'}</option>
                                                {elseif $option == "-"}
                                                    <option value="">-</option>
                                                {else}
                                                    <option value="{$option[$button.options.id]|escape:'htmlall':'UTF-8'}"
                                                            {if isset($button.multiple)}
                                                                {foreach $fields_value[$button.name] as $field_value}
                                                                    {if $field_value == $option[$button.options.id]}
                                                                        selected="selected"
                                                                    {/if}
                                                                {/foreach}
                                                            {else}
                                                                {if $fields_value[$button.name] == $option[$button.options.id]}
                                                                    selected="selected"
                                                                {/if}
                                                            {/if}
                                                            >{$option[$button.options.name]|escape:'htmlall':'UTF-8'}</option>
                                                {/if}
                                            {/foreach}
                                        {/if}
                                    {/if}
                                </select>
                                </div>
                            {/if}
                            {elseif $button.type == 'radio'}
                            {if isset($input.values)}
                            {foreach $input.values as $value}
                                <div class="radio {if isset($button.class)}{$button.class|escape:'html':'UTF-8'}{/if}">
                                    <label>
                                        <input type="radio" name="{$input.name|escape:'html':'UTF-8'}"
                                               id="{$value.id|escape:'html':'UTF-8'}"
                                               value="{$value.value|escape:'html':'UTF-8'}"
                                               {if $fields_value[$button.name] == $value.value}checked="checked"{/if}
                                                {if isset($input.disabled) && $button.disabled|intval eq 1}disabled="disabled"{/if} />
                                        {$button.label|escape:'quotes':'UTF-8'}
                                    </label>
                                </div>
                            {if isset($value.p) && $value.p}<p
                                    class="help-block">{$value.p}</p>{/if}
                            {/foreach}
                            {/if}
                            {elseif $button.type == 'switch'}
                                <span class="switch prestashop-switch fixed-width-lg">
                                                        {foreach $button.values as $value}
                                                            <input
                                                                    type="radio"
                                                                    name="{$button.name|escape:'htmlall':'UTF-8'}"
                                                                    {if $value.value == 1}
                                                                        id="{$button.name|escape:'htmlall':'UTF-8'}_on"
                                                                    {else}
                                                                        id="{$button.name|escape:'htmlall':'UTF-8'}_off"
                                                                    {/if}
                                                                    value="{$value.value|escape:'htmlall':'UTF-8'}"
                                                                    {if $fields_value[$button.name] == $value.value}checked="checked"{/if}
                                                                    {if isset($button.disabled) && $button.disabled|intval eq 1}disabled="disabled"{/if}
                                                                    />
                                                            <label
                                                                    {if $value.value == 1}
                                                                        for="{$button.name|escape:'htmlall':'UTF-8'}_on"
                                                                    {else}
                                                                        for="{$button.name|escape:'htmlall':'UTF-8'}_off"
                                                                    {/if}
                                                                    >
                                                                {if $value.value == 1}
                                                                    {l s='Yes' mod='paseocenterbulkedition'}
                                                                {else}
                                                                    {l s='No' mod='paseocenterbulkedition'}
                                                                {/if}
                                                            </label>
                                                        {/foreach}
                                    <a class="slide-button btn"></a>
                                                    </span>
                            {elseif $button.type == 'text' || $button.type == 'tags'}
                            {if isset($button.lang) AND $button.lang}
                            {if $languages|count > 1}
                                <div class="form-group">
                                    {/if}
                                    {foreach $languages as $language}
                                        {assign var='value_text' value=$fields_value[$button.name][$language.id_lang]}
                                        {if $languages|count > 1}
                                            <div class="translatable-field lang-{$language.id_lang|escape:'html':'UTF-8'}" {if $language.id_lang != $defaultFormLanguage}style="display:none"{/if}>
                                            <div class="col-lg-9">
                                        {/if}
                                        {if $button.type == 'tags'}
                                            <script type="text/javascript">
                                                $(document).ready(function () {
                                                    var input_id = '{if isset($button.id)}{$button.id|escape:'html':'UTF-8'}_{$language.id_lang|escape:'html':'UTF-8'}{else}{$button.name|escape:'html':'UTF-8'}_{$language.id_lang|escape:'html':'UTF-8'}{/if}';
                                                    var tag_{$button.name|escape:'html':'UTF-8'} = $('#' + input_id);
                                                    tag_{$button.name|escape:'html':'UTF-8'}.tagify({
                                                        delimiters: [13, 44],
                                                        addTagPrompt: '{l s='Add tag' mod='paseocenterbulkedition'}'
                                                    }
                                                    );
                                                    {if isset($button.autocomplete_url)}
                                                    tag_{$button.name|escape:'html':'UTF-8'}.tagify({
                                                        source: function (request, response)
                                                        {
                                                            $.ajax({
                                                                type: "POST",
                                                                url: "{$button.autocomplete_url|escape:'html':'UTF-8'}",
                                                                data: {literal}"{'match' : '" + request.term + "'}"{/literal} ,
                                                                dataType: "json",
                                                                contentType: "application/json",
                                                                success: function (data) {
                                                                    response($.map(data, function (item) {
                                                                        return {
                                                                            label: item,
                                                                            value: item,
                                                                        }
                                                                    }
                                                                    )
                                                                    );
                                                                }
                                                            });
                                                        }
                                                    });
                                                    {/if}
                                                    $('#{$table|escape:'html':'UTF-8'}_form').submit(function ()
                                                    {
                                                        $(this).find('#' + input_id).val($('#' + input_id).tagify('serialize'));
                                                    }
                                                    );
                                                });
                                            </script>
                                        {/if}
                                    {if isset($button.maxchar) || isset($button.prefix) || isset($button.suffix)}
                                        <div class="input-group{if isset($button.class)} {$button.class|escape:'html':'UTF-8'}{/if}">
                                    {/if}
                                        {if isset($button.maxchar)}
                                            <span id="{if isset($button.id)}{$button.id|escape:'html':'UTF-8'}_{$language.id_lang|escape:'html':'UTF-8'}{else}{$button.name|escape:'html':'UTF-8'}_{$language.id_lang|escape:'html':'UTF-8'}{/if}_counter"
                                                  class="input-group-addon">
                                                    <span class="text-count-down">{$button.maxchar|escape:'html':'UTF-8'}</span>
                                                </span>
                                        {/if}
                                        {if isset($button.prefix)}
                                            <span class="input-group-addon">
                                                      {$button.prefix|escape:'html':'UTF-8'}
                                                    </span>
                                        {/if}
                                        <input type="text"
                                               id="{if isset($button.id)}{$button.id|escape:'html':'UTF-8'}_{$language.id_lang|escape:'html':'UTF-8'}{else}{$button.name|escape:'html':'UTF-8'}_{$language.id_lang|escape:'html':'UTF-8'}{/if}"
                                               name="{$button.name|escape:'html':'UTF-8'}_{$language.id_lang|escape:'html':'UTF-8'}"
                                               class="{if isset($button.class)}{$button.class|escape:'html':'UTF-8'}{/if}{if $button.type == 'tags'} tagify{/if}"
                                               value="{if isset($button.string_format) && $button.string_format}{$value_text|string_format:$button.string_format|escape:'html':'UTF-8'}{else}{$value_text|escape:'html':'UTF-8'}{/if}"
                                        {if isset($button.attr)}
                                            {if $button.attr|is_array}
                                                {foreach $button.attr as $k =>$attr}
                                                    {$k|escape:'html':'UTF-8'}="{$attr|escape:'html':'UTF-8'}"
                                                {/foreach}
                                            {else}
                                                {$button.attr|escape:'html':'UTF-8'}
                                            {/if}

                                        {/if}
                                        {if isset($button.size)} size="{$button.size|escape:'html':'UTF-8'}"{/if}
                                        {if isset($button.maxchar)} data-maxchar="{$button.maxchar|escape:'html':'UTF-8'}"{/if}
                                        {if isset($button.maxlength)} maxlength="{$button.maxlength|escape:'html':'UTF-8'}"{/if}
                                        {if isset($button.readonly) && $button.readonly} readonly="readonly"{/if}
                                        {if isset($button.disabled) && $button.disabled} disabled="disabled"{/if}
                                        {if isset($button.autocomplete) && !$button.autocomplete} autocomplete="off"{/if}
                                        {if isset($button.required) && $button.required} required="required" {/if}
                                        {if isset($button.placeholder) && $button.placeholder} placeholder="{$button.placeholder|escape:'html':'UTF-8'}"{/if}
                                        />
                                        {if isset($button.suffix)}
                                            <span class="input-group-addon">
                                                      {$button.suffix|escape:'html':'UTF-8'}
                                                    </span>
                                        {/if}
                                    {if isset($button.maxchar) || isset($button.prefix) || isset($button.suffix)}
                                        </div>
                                    {/if}
                                        {if $languages|count > 1}
                                            </div>
                                            <div class="col-lg-2">
                                                <button type="button" class="btn btn-default dropdown-toggle" tabindex="-1"
                                                        data-toggle="dropdown">
                                                    {$language.iso_code|escape:'html':'UTF-8'}
                                                    {if $ps15}
                                                        <span class="caret"></span>
                                                    {else}
                                                        <i class="icon-caret-down"></i>
                                                    {/if}
                                                </button>
                                                <ul class="dropdown-menu">
                                                    {foreach from=$languages item=language}
                                                        <li>
                                                            <a href="javascript:hideOtherLanguage({$language.id_lang|escape:'html':'UTF-8'});"
                                                               tabindex="-1">{$language.name|escape:'html':'UTF-8'}</a></li>
                                                    {/foreach}
                                                </ul>
                                            </div>
                                            </div>
                                        {/if}
                                    {/foreach}
                                    {if isset($button.maxchar)}
                                        <script type="text/javascript">
                                            function countDown($source, $target) {
                                                var max = $source.attr("data-maxchar");
                                                $target.html(max - $source.val().length);

                                                $source.keyup(function () {
                                                    $target.html(max - $source.val().length);
                                                });
                                            }

                                            $(document).ready(function () {
                                                {foreach from=$languages item=language}
                                                countDown($("#{if isset($button.id)}{$button.id|escape:'html':'UTF-8'}_{$language.id_lang|escape:'html':'UTF-8'}{else}{$button.name|escape:'html':'UTF-8'}_{$language.id_lang|escape:'html':'UTF-8'}{/if}"), $("#{if isset($button.id)}{$button.id|escape:'html':'UTF-8'}_{$language.id_lang|escape:'html':'UTF-8'}{else}{$button.name|escape:'html':'UTF-8'}_{$language.id_lang|escape:'html':'UTF-8'}{/if}_counter"));
                                                {/foreach}
                                            });
                                        </script>
                                    {/if}
                                    {if $languages|count > 1}
                                </div>
                            {/if}
                            {else}
                            {if $button.type == 'tags'}
                                <script type="text/javascript">
                                    $().ready(function ()
                                            {
                                                var input_id = '{if isset($button.id)}{$button.id|escape:'html':'UTF-8'}{else}{$button.name|escape:'html':'UTF-8'}{/if}';
                                                var tag_{$button.name|escape:'html':'UTF-8'} = $('#' + input_id);
                                                tag_{$button.name|escape:'html':'UTF-8'}.tagify(
                                                {
                                                    delimiters: [13, 44],
                                                    addTagPrompt: '{l s='Add tag' mod='paseocenterbulkedition'}'
                                                }
                                                );
                                                {if isset($button.autocomplete_url)}
                                                tag_{$button.name|escape:'html':'UTF-8'}.tagify('inputField').autocomplete(
                                                        {
                                                        source: function (request, response)
                                                        {
                                                        $.ajax(
                                                                {
                                                            type: "POST",
                                                            url: "{$button.autocomplete_url|escape:'html':'UTF-8'}",
                                                            data: "{ 'match': '" + request.term + "'}",
                                                            dataType: "json",
                                                            contentType: "application/json",
                                                            success: function (data)
                                                            {
                                                                response($.map(data, function (item)
                                                                {
                                                                    return {
                                                                        label: item,
                                                                        value: item
                                                                    }
                                                                }
                                                                )
                                                                );
                                                            }
                                                        }
                                                        );
                                                    },
                                                    position: { of: tag_{$button.name|escape:'html':'UTF-8'}.tagify('containerDiv')},
                                                    close: function (event, ui)
                                                    {
                                                        tag_{$button.name|escape:'html':'UTF-8'}.tagify('add');
                                                    }
                                                });
                                                {/if}
                                                $('#{$table|escape:'html':'UTF-8'}_form').submit(function ()
                                                        {
                                                            $(this).find('#' + input_id).val($('#' + input_id).tagify('serialize'));
                                                        }
                                                );
                                            }
                                    );
                                </script>

                            {/if}
                                {assign var='value_text' value=$fields_value[$button.name]}
                            {if isset($button.maxchar) || isset($button.prefix) || isset($button.suffix)}
                                <div class="input-group{if isset($button.class)} {$button.class|escape:'html':'UTF-8'}{/if}">
                                    {/if}
                                    {if isset($button.maxchar)}
                                        <span id="{if isset($button.id)}{$button.id|escape:'html':'UTF-8'}{else}{$button.name|escape:'html':'UTF-8'}{/if}_counter"
                                              class="input-group-addon"><span
                                                    class="text-count-down">{$button.maxchar|escape:'html':'UTF-8'}</span></span>
                                    {/if}
                                    {if isset($button.prefix)}
                                        <span class="input-group-addon">
                                          {$button.prefix|escape:'html':'UTF-8'}
                                        </span>
                                    {/if}
                                    <input type="text"
                                           name="{$button.name|escape:'html':'UTF-8'}"
                                           id="{if isset($button.id)}{$button.id|escape:'html':'UTF-8'}{else}{$button.name|escape:'html':'UTF-8'}{/if}"
                                           value="{if isset($button.string_format) && $button.string_format}{$value_text|string_format:$button.string_format|escape:'html':'UTF-8'}{else}{$value_text|escape:'html':'UTF-8'}{/if}"
                                           class="{if isset($button.class)}{$button.class|escape:'html':'UTF-8'}{/if}{if $button.type == 'tags'} tagify{/if}"
                                            {if isset($button.size)} size="{$button.size|escape:'html':'UTF-8'}"{/if}
                                            {if isset($button.maxchar)} data-maxchar="{$button.maxchar|escape:'html':'UTF-8'}"{/if}
                                            {if isset($button.maxlength)} maxlength="{$button.maxlength|escape:'html':'UTF-8'}"{/if}
                                            {if isset($button.readonly) && $button.readonly} readonly="readonly"{/if}
                                            {if isset($button.disabled) && $button.disabled} disabled="disabled"{/if}
                                            {if isset($button.autocomplete) && !$button.autocomplete} autocomplete="off"{/if}
                                            {if isset($button.required) && $button.required } required="required" {/if}
                                            {if isset($button.placeholder) && $button.placeholder } placeholder="{$button.placeholder|escape:'html':'UTF-8'}"{/if}
                                            />
                                    {if isset($button.suffix)}
                                        <span class="input-group-addon">
                                          {$button.suffix|escape:'html':'UTF-8'}
                                        </span>
                                    {/if}

                                    {if isset($button.maxchar) || isset($button.prefix) || isset($button.suffix)}
                                </div>
                            {/if}
                            {if isset($button.maxchar)}
                                <script type="text/javascript">
                                    function countDown($source, $target) {
                                        var max = $source.attr("data-maxchar");
                                        $target.html(max - $source.val().length);

                                        $source.keyup(function () {
                                            $target.html(max - $source.val().length);
                                        });
                                    }
                                    $(document).ready(function () {
                                        countDown($("#{if isset($button.id)}{$button.id|escape:'html':'UTF-8'}{else}{$button.name|escape:'html':'UTF-8'}{/if}"), $("#{if isset($button.id)}{$button.id|escape:'html':'UTF-8'}{else}{$button.name|escape:'html':'UTF-8'}{/if}_counter"));
                                    });
                                </script>
                            {/if}
                            {/if}
                            {/if}
                            </div>
                        {/if}
                            <div class="col-xs-12 row">
                                <label class="control-label">
                                                        <span {if isset($button.hint)} class="label-tooltip" title="" data-html="true" data-toggle="tooltip" data-original-title="{$button.hint|escape:'html':'UTF-8'}" {/if}>{$button.label|escape:'html':'UTF-8'}
                                                </span>{if isset($button.required) && $button.required}
                                    <sup>*</sup>
                                    {/if}</label>
                            </div>
                        </div>
                    {/foreach}
                    </div>
                {elseif $input.type == 'color'}
                    <div class="form-group">
                        <div class="col-lg-2">
                            <div class="row">
                                <div class="input-group">
                                    <input type="color"
                                           data-hex="true"
                                           {if isset($input.class)}class="{$input.class|escape:'html':'UTF-8'}"
                                           {else}class="color mColorPickerInput"{/if}
                                           name="{$input.name|escape:'htmlall':'UTF-8'}"
                                           value="{$fields_value[$input.name]|escape:'html':'UTF-8'}"/>
                                </div>
                            </div>
                        </div>
                    </div>
                {elseif $input.type == 'date'}
                    <div class="row">
                        <div class="input-group col-lg-12">
                            <input
                                    id="{if isset($input.id)}{$input.id|escape:'html':'UTF-8'}{else}{$input.name|escape:'html':'UTF-8'}{/if}"
                                    type="text"
                                    data-hex="true"
                                    {if isset($input.class)}class="{$input.class|escape:'html':'UTF-8'}"
                                    {else}class="datepicker"{/if}
                                    name="{$input.name|escape:'htmlall':'UTF-8'}"
                                    value="{$fields_value[$input.name]|escape:'html':'UTF-8'}"/>
                                            <span class="input-group-addon">
                                                <i class="icon-calendar-empty"></i>
                                            </span>
                        </div>
                    </div>
                {elseif $input.type == 'button'}
                    <button {if isset($input.id)}id="{$input.id|escape:'html':'UTF-8'}"{/if} {if isset($input.disabled) && $input.disabled} disabled="disabled" {/if}
                            class="btn btn-default{if isset($input.class)} {$input.class|escape:'html':'UTF-8'} {/if}" {if isset($input.href)}href="{$input.href|escape:'html':'UTF-8'}"{/if} >{$input.inner_label|escape:'html':'UTF-8'}</button>
                {elseif $input.type == 'datetime'}
                    <div class="row">
                        <div class="input-group col-lg-12">
                            <input
                                    id="{if isset($input.id)}{$input.id|escape:'html':'UTF-8'}{else}{$input.name|escape:'html':'UTF-8'}{/if}"
                                    type="text"
                                    data-hex="true"
                                    {if isset($input.class)}class="{$input.class}"
                                    {else}class="datetimepicker"{/if}
                                    name="{$input.name|escape:'htmlall':'UTF-8'}"
                                    value="{$fields_value[$input.name]|escape:'html':'UTF-8'}"/>
                                            <span class="input-group-addon">
                                                <i class="icon-calendar-empty"></i>
                                            </span>
                        </div>
                    </div>
                {elseif $input.type == 'list'}
                    {$input.list->generateList($input.list_result,$input.list_header)|escape:'quotes':'UTF-8'}
                {elseif $input.type == 'free'}
                    {$fields_value[$input.name]|escape:'quotes':'UTF-8'}
                {elseif $input.type == 'html'}
                    {if isset($input.html_content)}
                        {$input.html_content|escape:'quotes':'UTF-8'}
                    {else}
                        {$input.name|escape:'quotes':'UTF-8'}
                    {/if}
                {/if}
            {/block}{* end block input *}
            {block name="description"}
                {if isset($input.desc) && !empty($input.desc)}
                    <p class="help-block">
                        {if is_array($input.desc)}
                            {foreach $input.desc as $p}
                                {if is_array($p)}
                                    <span id="{$p.id|escape:'htmlall':'UTF-8'}">{$p.text|escape:'quotes':'UTF-8'}</span>
                                    <br/>
                                {else}
                                    {$p|escape:'quotes':'UTF-8'}
                                    <br/>
                                {/if}
                            {/foreach}
                        {else}
                            {$input.desc|escape:'quotes':'UTF-8'}
                        {/if}
                    </p>
                {/if}
            {/block}
            </div>
        {/block}{* end block field *}
    {/if}
    </div>
{/block}