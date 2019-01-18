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
    .cookiesplus-modal .more-information {
        display: block;
        clear: both;
        margin: 10px 0;
    }

    .cookiesplus-modal .pull-left {
        float: left;
    }

    .cookiesplus-modal .pull-right {
        float: right;
    }

    @media (max-width: 575px) {
        .cookiesplus-modal .pull-left,
        .cookiesplus-modal .pull-right {
            float: none !important;
            min-width: 75%;
            padding-bottom: 3%;
        }
        div#cookiesplus-basic .page-heading {
              font-size: 15px;
        }

        .cookiesplus-modal .cookie_actions,
        .cookiesplus-modal .modal-footer {
            text-align: center;
        }

        .cookiesplus-modal .cookie_type_container {
            max-height: 50vh;
            overflow-y: auto;
            margin-bottom: 15px;
        }

        .cookiesplus-modal .cookie-actions > .pull-left {
            margin-top: 10px;
        }
    }
</style>

<div class="cookiesplus" style="display:none">
    {if isset($C_P_TEXT_BASIC) && $C_P_TEXT_BASIC}
        <div id="cookiesplus-basic">
            <form method="POST" name="cookies">
                <div class="page-heading">{l s='Cookie preferences' mod='cookiesplus'}</div>
                <div class="cookie_type_container">
                    <div class="cookie_type box">
                        <div>{$C_P_TEXT_BASIC nofilter}</div>
                    </div>
                </div>

                <div class="cookie_actions">
                    <div class="pull-right">
                       <input type="submit" name="save-basic" onclick="if (cookieGdpr.saveBasic()) return;" class="btn btn-primary pull-right" value="{l s='Accept and continue' mod='cookiesplus'}" />
                        {if isset($C_P_CMS_PAGE) && $C_P_CMS_PAGE}
                            <a href="{$link->getCMSLink($C_P_CMS_PAGE)|escape:'html'}" class="pull-right more-information" target="_blank">{l s='More information' mod='cookiesplus'}</a>
                        {/if}
                    </div>
                    <div class="pull-left">
                        <input type="button" onclick="cookieGdpr.displayModalAdvanced();" class="btn btn-default pull-left" value="{l s='More information' mod='cookiesplus'}" />
                    </div>
                    <div class="clear"></div>
                </div>
            </form>
        </div>
    {/if}

    <div id="cookiesplus-advanced">
        <form method="POST" name="cookies" id="cookiesplus-form">
            <div class="page-heading">{l s='Your cookie settings' mod='cookiesplus'}</div>
            <div class="cookie_type_container">
                {if isset($C_P_TEXT_REQUIRED) && $C_P_TEXT_REQUIRED}
                    <div class="cookie_type box">
                        <div>{$C_P_TEXT_REQUIRED nofilter}</div>
                        <div>
                            <strong>{l s='Accept strictly necessary cookies?' mod='cookiesplus'}</strong>
                            <div class="form-check">
                                <input type="checkbox" class="filled-in form-check-input not_uniform comparator" name="essential" id="essential" checked="checked" disabled>
                                <label class="form-check-label" for="essential">{l s='Yes' mod='cookiesplus'}</label>
                            </div>
                        </div>
                    </div>
                {/if}

                {if isset($C_P_TEXT_3RDPARTY) && $C_P_TEXT_3RDPARTY}
                    <div class="cookie_type box">
                        <div>{$C_P_TEXT_3RDPARTY nofilter}</div>
                        <div>
                            <strong>{l s='Accept third-party cookies?' mod='cookiesplus'}</strong>
                            <div class="form-check">
                                <input type="checkbox" class="filled-in form-check-input not_uniform comparator" name="thirdparty" id="thirdparty">
                                <label class="form-check-label" for="thirdparty">{l s='Yes' mod='cookiesplus'}</label>
                            </div>
                        </div>
                    </div>
                {/if}
            </div>

            <div class="cookie_actions">
                <div class="pull-right">
                    <input type="submit" name="save" onclick="if (cookieGdpr.save()) return;" class="btn btn-primary pull-right" value="{l s='Save preferences' mod='cookiesplus'}" />
                    {if isset($C_P_CMS_PAGE_ADV) && $C_P_CMS_PAGE_ADV}
                        <a href="{$link->getCMSLink($C_P_CMS_PAGE_ADV)|escape:'html'}" class="pull-right more-information" target="_blank">{l s='Privacy & Cookie Policy' mod='cookiesplus'}</a>
                    {/if}
                </div>
                <div class="clear"></div>
            </div>
        </form>
    </div>
</div>