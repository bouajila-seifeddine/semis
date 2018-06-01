{*
* 2007-2018 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2018 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

{if $ps_version} {*if ps is 1.7*}
<div id="gdpr_consent">
    <span class="custom-checkbox" style="display: flex;">
        <div>
            <input id="psgdpr_consent_checkbox" name="psgdpr_consent_checkbox" type="checkbox" value="1">
            <span style="flex: 0 0 0.9375rem;"><i class="material-icons rtl-no-flip checkbox-checked psgdpr_consent_icon"></i></span>
        </div>
        <div class="psgdpr_consent_message" style="display: inline">
            {$psgdpr_consent_message nofilter}{* html data *}
        </div>
    </span>
</div>
{else} {*if ps is 1.6*}
<div id="gdpr_consent">
    <div class="form-group">
        <div class="checkbox">
            <input id="psgdpr_consent_checkbox" name="psgdpr_consent_checkbox" type="checkbox" name="" value="1">
            <div class="psgdpr_consent_message">
                <label for="psgdpr_consent_checkbox">{$psgdpr_consent_message nofilter}{* html data *}</label>
            </div>
        </div>
    </div>
</div>
{/if}
{literal}
<script type="text/javascript">
    var psgdpr_front_controller = "{/literal}{$psgdpr_front_controller|escape:'htmlall':'UTF-8'}{literal}";
    var psgdpr_id_module = "{/literal}{$psgdpr_id_module|escape:'htmlall':'UTF-8'}{literal}";
    var psgdpr_id_customer = "{/literal}{$psgdpr_id_customer|escape:'htmlall':'UTF-8'}{literal}";
    var psgdpr_customer_token = "{/literal}{$psgdpr_customer_token|escape:'htmlall':'UTF-8'}{literal}";
    var psgdpr_id_guest = "{/literal}{$psgdpr_id_guest|escape:'htmlall':'UTF-8'}{literal}";
    var psgdpr_guest_token = "{/literal}{$psgdpr_guest_token|escape:'htmlall':'UTF-8'}{literal}";

    document.addEventListener('DOMContentLoaded', function() {

        psgdpr_front_controller = psgdpr_front_controller.replace(/\amp;/g,'');

        function toggleFormActive() {
            parentForm = $('#gdpr_consent').closest('form');

            if ($('#psgdpr_consent_checkbox').prop('checked') == true) {
                parentForm.find('[type="submit"]').removeAttr('disabled');
            } else {
                parentForm.find('[type="submit"]').attr('disabled', 'disabled');
            }
        }

        // Triggered on page loading
        toggleFormActive();

        $('body').on('change', function(){
            // Triggered after the dom might change after being loaded
            toggleFormActive();

            // Listener ion the checkbox click
            $(document).on("click" ,"#psgdpr_consent_checkbox", function() {
                toggleFormActive();
            });

            $(document).on('submit', parentForm, function(event) {
                $.ajax({
                    data: 'POST',
                    //dataType: 'JSON',
                    url: psgdpr_front_controller,
                    data: {
                        ajax: true,
                        action: 'AddLog',
                        id_customer: psgdpr_id_customer,
                        customer_token: psgdpr_customer_token,
                        id_guest: psgdpr_id_guest,
                        guest_token: psgdpr_guest_token,
                        id_module: psgdpr_id_module,
                    },
                    success: function (data) {
                        // parentForm.submit();
                    },
                    error: function (err) {
                        console.log(err);
                    }
                });
            });
        });
    });
</script>
{/literal}
