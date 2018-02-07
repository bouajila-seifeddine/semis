{*
*  Please read the terms of the CLUF license attached to this module(cf "licences" folder)
*
*  @author    Pronimbo.
*  @copyright Pronimbo. all rights reserved.
*  @license   http://www.pronimbo.com/licenses/license_en.pdf http://www.pronimbo.com/licenses/license_es.pdf https://www.pronimbo.com/licenses/license_fr.pdf
*}

<a class="btn btn-default" href="#"
   onclick="if ($('.requiredFieldsParameters:visible').length == 0) $('.requiredFieldsParameters').slideDown('slow'); else $('.requiredFieldsParameters').slideUp('slow'); return false;">
    <i class="icon-plus-sign"></i> {l s='Set required fields for this section' mod='paseocenterbulkedition'}
</a>
<div class="clearfix">&nbsp;</div>
<div style="display:none" class="panel requiredFieldsParameters">
    <h3><i class="icon-asterisk"></i> {l s='Required Fields' mod='paseocenterbulkedition'}</h3>

    <form name="updateFields"
          action="{$current|escape:'htmlall':'UTF-8'}&amp;submitFields=1&amp;token={$token|escape:'htmlall':'UTF-8'}"
          method="post">
        <div class="alert alert-info">
            {l s='Select the fields you would like to be required for this section.' mod='paseocenterbulkedition'}
        </div>
        <div class="row">
            <table class="table">
                <thead>
                <tr>
                    <th class="fixed-width-xs">
                        <input type="checkbox" onclick="checkDelBoxes(this.form, 'fieldsBox[]', this.checked)"
                               class="noborder" name="checkme">
                    </th>
                    <th><span class="title_box">{l s='Field Name' mod='paseocenterbulkedition'}</span></th>
                </tr>
                </thead>
                <tbody>
                {foreach $table_fields as $field}
                    {if !in_array($field, $required_class_fields)}
                        <tr>
                            <td class="noborder">
                                <input type="checkbox" name="fieldsBox[]"
                                       value="{$field|escape:'htmlall':'UTF-8'}" {if in_array($field, $required_fields)} checked="checked"{/if} />
                            </td>
                            <td>
                                {$field|escape:'htmlall':'UTF-8'}
                            </td>
                        </tr>
                    {/if}
                {/foreach}
                </tbody>
            </table>
        </div>
        <div class="panel-footer">
            <button name="submitFields" type="submit" class="btn btn-default pull-right">
                <i class="process-icon-save "></i> <span>{l s='Save' mod='paseocenterbulkedition'}</span>
            </button>
        </div>
    </form>
</div>