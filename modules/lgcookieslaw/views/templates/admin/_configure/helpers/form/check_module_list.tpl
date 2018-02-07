{*
	*  Please read the terms of the CLUF license attached to this module(cf "licences" folder)
	*
	* @author    Línea Gráfica E.C.E. S.L.
	* @copyright Lineagrafica.es - Línea Gráfica E.C.E. S.L. all rights reserved.
	* @license   https://www.lineagrafica.es/licenses/license_en.pdf https://www.lineagrafica.es/licenses/license_es.pdf https://www.lineagrafica.es/licenses/license_fr.pdf
*}

<table class="table">
{foreach from=$module_list item=module name=list}
    {if (($smarty.foreach.list.index +1) mod 4) eq 0}
    <tr>
    {/if}
    <td style="width:10px;"><img src="../modules/{$module.name|escape:'html':'UTF-8'}/logo.gif" style="width:20px;"></td>
    {if ($module.name == 'lgcookieslaw')}
    <td style="width:20px;"><input type="checkbox" disabled {if $module.checked eq 1} checked="checked" {/if} name="module{$module.id_module|escape:'html':'UTF-8'}"></td>
    {else}
    <td style="width:20px;"><input type="checkbox" {if $module.checked eq 1} checked="checked" {/if} name="module{$module.id_module|escape:'html':'UTF-8'}"></td>
    {/if}
    <td style="width:100px;">{$module.name|escape:'html':'UTF-8'}</td>
    {if ($smarty.foreach.list.index +1 mod 4) eq 0}
        </tr>
        {/if}
        {/foreach}
</table>