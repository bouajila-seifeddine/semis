{*
*
*  Please read the terms of the CLUF license attached to this module(cf "licences" folder)
*
*  @author    Pronimbo.
*  @copyright Pronimbo. all rights reserved.
*  @license   http://www.pronimbo.com/licenses/license_en.pdf http://www.pronimbo.com/licenses/license_es.pdf https://www.pronimbo.com/licenses/license_fr.pdf
*
*}

{extends file="./../../../../helpers/form/form.tpl"}
{block name="leadin"}
    <script type="text/javascript">
        $(document).ready(function () {
            var id_form = '{if isset($fields.form.form.id_form)}{$fields.form.form.id_form|escape:'html':'UTF-8'}{else}{if $table == null}configuration_form{else}{$table|escape:'html':'UTF-8'}_form{/if}{if isset($smarty.capture.table_count) && $smarty.capture.table_count}_{$smarty.capture.table_count|intval|escape:'html':'UTF-8'}{/if}{/if}';

            $('#page-header-desc-paseocenter-save-and-stay, #desc-paseocenter-save-and-stay').click(function () {
                $('#' + id_form).find('input[name=submitAddpaseocenter]').attr('name', $('#' + id_form).find('input[name=submitAddpaseocenter]').attr('name') + 'AndStay');
                $('#' + id_form).submit();
            });
            $('textarea[name=PA_SEO_HTACCESS]').attr('disabled', 'disabled');

            $('#PA_SEO_HTACCESS_ENABLE').click(function(){
                if (confirm('{l s='Are you sure to want modify .htaccess file ?' mod='paseocenterbulkedition'}'))
                {
                    $('textarea[name=PA_SEO_HTACCESS]').removeAttr('disabled');
                }
                return false;
            });
        });

    </script>
{/block}
