{extends file="helpers/form/form.tpl"}
{block name="input"}
    {if $input.type == 'file_lang'}
        <div class="row">
            {foreach from=$languages item=language}
                {if $languages|count > 1}
                    <div class="translatable-field lang-{$language.id_lang|escape:'UTF-8'}" {if $language.id_lang != $defaultFormLanguage}style="display:none"{/if}>
                    {/if}
                    <div class="col-lg-9">
                        {*$fields_value|d*}
                        {if isset($fields_value['image']) && $fields_value['image'][$language.id_lang] != ''}
                            <img src="{$image_baseurl|escape:'UTF-8'}{$language.iso_code|escape:'UTF-8'}/{$fields_value['image'][$language.id_lang]|escape:'UTF-8'}" class="img-thumbnail" /><br><br>
                        {/if}
                        <input id="{$input.name|escape:'UTF-8'}_{$language.id_lang|escape:'UTF-8'}" type="file" name="{$input.name|escape:'UTF-8'}_{$language.id_lang|escape:'UTF-8'}" class="hide" />
                        <div class="dummyfile input-group">
                            <span class="input-group-addon"><i class="icon-file"></i></span>
                            <input id="{$input.name|escape:'UTF-8'}_{$language.id_lang|escape:'UTF-8'}-name" type="text" class="disabled" name="filename" readonly />
                            <span class="input-group-btn">
                                <button id="{$input.name|escape:'UTF-8'}_{$language.id_lang|escape:'UTF-8'}-selectbutton" type="button" name="submitAddAttachments" class="btn btn-default">
                                    <i class="icon-folder-open"></i> {l s='Choose a file' mod='ph_iconboxes'}
                                </button>
                            </span>
                        </div>
                    </div>
                    {if $languages|count > 1}
                        <div class="col-lg-2">
                            <button type="button" class="btn btn-default dropdown-toggle" tabindex="-1" data-toggle="dropdown">
                                {$language.iso_code|escape:'UTF-8'}
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu">
                                {foreach from=$languages item=lang}
                                    <li><a href="javascript:hideOtherLanguage({$lang.id_lang|escape:'UTF-8'});" tabindex="-1">{$lang.name}</a></li>
                                    {/foreach}
                            </ul>
                        </div>
                    {/if}
                    {if $languages|count > 1}
                    </div>
                {/if}
                <script>
                $(document).ready(function() {
                    $('#{$input.name|escape:'UTF-8'}_{$language.id_lang|escape:'UTF-8'}-selectbutton').click(function(e) {
                        $('#{$input.name|escape:'UTF-8'}_{$language.id_lang|escape:'UTF-8'}').trigger('click');
                    });
                    $('#{$input.name|escape:'UTF-8'}_{$language.id_lang|escape:'UTF-8'}').change(function(e) {
                        var val = $(this).val();
                        var file = val.split(/[\\/]/);
                        $('#{$input.name|escape:'UTF-8'}_{$language.id_lang|escape:'UTF-8'}-name').val(file[file.length - 1]);
                    });
                });
                </script>
            {/foreach}
        </div>
    {else}
        {$smarty.block.parent}
    {/if}
{/block}