{*
 * Cookies Plus
 *
 * NOTICE OF LICENSE
 *
 * This product is licensed for one customer to use on one installation (test stores and multishop included).
 * Site developer has the right to modify this module to suit their needs, but can not redistribute the module in
 * whole or in part. Any other use of this module constitues a violation of the user agreement.
 *
 * DISCLAIMER
 *
 * NO WARRANTIES OF DATA SAFETY OR MODULE SECURITY
 * ARE EXPRESSED OR IMPLIED. USE THIS MODULE IN ACCORDANCE
 * WITH YOUR MERCHANT AGREEMENT, KNOWING THAT VIOLATIONS OF
 * PCI COMPLIANCY OR A DATA BREACH CAN COST THOUSANDS OF DOLLARS
 * IN FINES AND DAMAGE A STORES REPUTATION. USE AT YOUR OWN RISK.
 *
 *  @author    idnovate.com <info@idnovate.com>
 *  @copyright 2018 idnovate.com
 *  @license   See above
*}

<style>
    #fancybox-content { overflow: auto; }
    .cookiesplus-modal .more-information {
        margin: 10px 0;
        display: block;
        clear: both;
    }

    .cookiesplus-modal .cookiesplus-form {
        text-align: left;
    }

    .cookiesplus-modal .cookiesplus-modal p {
        padding-bottom: 10px;
    }

    .cookiesplus-modal .box {
        background: #eee;
        padding: 10px;
        margin: 10px 0;
    }

    .cookiesplus-modal .title_block {
        font-size: 1.1em;
        line-height: 1.6em;
        padding-left: 0.5em;
        margin: 0.5em 0;
        text-transform: uppercase;
        font-weight: bold;
        color: #374853;
        height: 21px;
        text-align: left;
    }

    @media (max-width: 575px) {
        .cookiesplus-modal .pull-left,
        .cookiesplus-modal .pull-right {
            float: none !important;
            margin: 0 auto;
        }

        .cookiesplus-modal .cookie_actions,
        .cookiesplus-modal .modal-footer {
            text-align: center;
        }

        .cookiesplus-modal .more-information {
            display: block;
        }
    }
</style>

<div class="cookiesplus" style="display:none">
    <div id="cookiesplus-basic">
        <div class="cookiesplus-modal">
            <form method="POST" name="cookies">
                <div class="title_block">{l s='Your cookie settings' mod='cookiesplus'}</div>
                <div class="block_content">
                    <div class="cookie_type_container">
                        {if isset($C_P_TEXT_BASIC) && $C_P_TEXT_BASIC}
                            <div class="box">
                                <div>{$C_P_TEXT_BASIC nofilter}</div>
                            </div>
                        {/if}
                    </div>
                </div>

                <div class="cookie_actions">
                    <div class="pull-right">
                        <input type="submit" name="save-basic" onclick="return cookieGdpr.saveBasic();" class="button_large exclusive_large pull-right exclusive ui-btn ui-btn-up-e ui-shadow ui-btn-corner-all ui-mini ui-btn-inline ui-btn-icon-right ui-btn-inner ui-btn-corner-all" value="{l s='Yes, I accept' mod='cookiesplus'}" />
                        {if isset($C_P_CMS_PAGE) && $C_P_CMS_PAGE}
                            <a href="{$link->getCMSLink($C_P_CMS_PAGE)|escape:'html'}" class="pull-right more-information" target="_blank">{l s='Privacy & Cookie Policy' mod='cookiesplus'}</a>
                        {/if}
                    </div>
                    <div class="pull-left">
                        <input onclick="cookieGdpr.displayModalAdvanced();" class="button_large pull-left button_large ui-btn ui-btn-up-c ui-shadow ui-btn-corner-all ui-mini ui-btn-inline ui-btn-icon-left ui-btn-inner ui-btn-corner-all" value="{l s='More information' mod='cookiesplus'}" />
                    </div>
                    <div class="clear"></div>
                </div>
            </form>
        </div>
    </div>

    <div id="cookiesplus-advanced">
        <div class="cookiesplus-modal">
            <form method="POST" name="cookies" id="cookiesplus-form">
                <div class="title_block">{l s='Your cookie settings' mod='cookiesplus'}</div>
                <div class="block_content">
                    <div class="cookie_type_container">
                        {if isset($C_P_TEXT_REQUIRED) && $C_P_TEXT_REQUIRED}
                            <div class="box">
                                <div>{$C_P_TEXT_REQUIRED nofilter}</div>
                                <div>
                                    <strong>{l s='Accept strictly necessary cookies?' mod='cookiesplus'}</strong>
                                    <div class="form-check">
                                        <input type="checkbox" class="filled-in form-check-input not_uniform comparator" name="essential" data-id="essential" checked="checked" disabled>
                                        <label class="form-check-label" for="essential">{l s='Yes' mod='cookiesplus'}</label>
                                    </div>
                                </div>
                            </div>
                        {/if}

                        {if isset($C_P_TEXT_3RDPARTY) && $C_P_TEXT_3RDPARTY}
                            <div class="box">
                                <div>{$C_P_TEXT_3RDPARTY nofilter}</div>
                                <div>
                                    <strong>{l s='Accept third-party cookies?' mod='cookiesplus'}</strong>
                                    <div class="form-check">
                                        <input type="checkbox" class="filled-in form-check-input not_uniform comparator" name="thirdparty" data-id="thirdparty">
                                        <label class="form-check-label" for="thirdparty">{l s='Yes' mod='cookiesplus'}</label>
                                    </div>
                                </div>
                            </div>
                        {/if}
                    </div>

                    <div class="cookie_actions">
                        <div class="pull-right">
                            <input type="submit" name="save" onclick="return cookieGdpr.save();" class="button_large exclusive_large pull-right exclusive ui-btn ui-btn-up-e ui-shadow ui-btn-corner-all ui-mini ui-btn-inline ui-btn-icon-right ui-btn-inner ui-btn-corner-all" value="{l s='Save preferences' mod='cookiesplus'}" />
                            {if isset($C_P_CMS_PAGE_ADV) && $C_P_CMS_PAGE_ADV}
                                <a href="{$link->getCMSLink($C_P_CMS_PAGE_ADV)|escape:'html'}" class="pull-right more-information" target="_blank">{l s='Privacy & Cookie Policy' mod='cookiesplus'}</a>
                            {/if}
                        </div>
                        <div class="clear"></div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // <![CDATA[
    $(document).ready(function() {
        $('#block_various_links_footer').append("<li class='last_item' style='cursor:pointer' onclick='cookieGdpr.displayModalAdvanced(false);'>{l s='Your cookie settings' mod='cookiesplus'}</li>");
    });
    // ]]>
</script>
