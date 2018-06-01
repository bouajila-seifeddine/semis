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
    /*https://mdbootstrap.com/components/bootstrap-switch/*/
    .switch {
        padding: 5px 0;
    }
    .switch label {
        cursor: pointer;
        -webkit-user-select: none; /* webkit (safari, chrome) browsers */
        -moz-user-select: none; /* mozilla browsers */
        -khtml-user-select: none; /* webkit (konqueror) browsers */
        -ms-user-select: none; /* IE10+ */
    }
    .switch label input[type=checkbox] {
        opacity: 0;
        width: 0;
        height: 0
    }
    .switch label input[type=checkbox]:checked + .lever {
        background-color: #2ACC6C
    }
    .switch label input[type=checkbox]:checked + .lever:after {
        background-color: #00C851;
        left: 24px
    }
    .switch label input[type=checkbox]:checked:not(:disabled) ~ .lever:active:after {
        -webkit-box-shadow: 0 1px 3px 1px rgba(0, 0, 0, .4), 0 0 0 15px rgba(170, 102, 204, .1);
        box-shadow: 0 1px 3px 1px rgba(0, 0, 0, .4), 0 0 0 15px rgba(170, 102, 204, .1)
    }
    .switch label input[type=checkbox]:not(:disabled) ~ .lever:active:after {
        -webkit-box-shadow: 0 1px 3px 1px rgba(0, 0, 0, .4), 0 0 0 15px rgba(0, 0, 0, .08);
        box-shadow: 0 1px 3px 1px rgba(0, 0, 0, .4), 0 0 0 15px rgba(0, 0, 0, .08)
    }
    .switch label input[type=checkbox]:disabled + .lever {
        cursor: default
    }
    .switch label input[type=checkbox]:disabled + .lever:after,
    .switch label input[type=checkbox]:disabled:checked + .lever:after {
        background-color: #bdbdbd
    }
    .switch label .lever {
        content: "";
        display: inline-block;
        position: relative;
        width: 40px;
        height: 15px;
        background-color: #818181;
        -webkit-border-radius: 15px;
        border-radius: 15px;
        margin-right: 10px;
        -webkit-transition: background .3s ease;
        -o-transition: background .3s ease;
        transition: background .3s ease;
        vertical-align: middle;
        margin: 0 16px
    }
    .switch label .lever:after {
        content: "";
        position: absolute;
        display: inline-block;
        width: 21px;
        height: 21px;
        background-color: #f1f1f1;
        -webkit-border-radius: 21px;
        border-radius: 21px;
        -webkit-box-shadow: 0 1px 3px 1px rgba(0, 0, 0, .4);
        box-shadow: 0 1px 3px 1px rgba(0, 0, 0, .4);
        left: -5px;
        top: -3px;
        -webkit-transition: left .3s ease, background .3s ease, -webkit-box-shadow .1s ease;
        transition: left .3s ease, background .3s ease, -webkit-box-shadow .1s ease;
        -o-transition: left .3s ease, background .3s ease, box-shadow .1s ease;
        transition: left .3s ease, background .3s ease, box-shadow .1s ease;
        transition: left .3s ease, background .3s ease, box-shadow .1s ease, -webkit-box-shadow .1s ease
    }

    .cookiesplus {
        display: none;
    }

    .cookiesplus-modal .more-information {
        margin: 0 15px;
        line-height: 24px;
    }

    .cookiesplus-modal .pull-left {
        float: left;
    }

    .cookiesplus-modal .pull-right {
        float: right;
    }

    .cookiesplus-modal .button {
        border-color: #ccc;
        background-image: url({$img_dir|escape:'htmlall':'UTF-8'}bg_bt_2.gif);
    }

    .cookiesplus-modal p {
        padding-bottom: 10px;
    }

    .cookiesplus-modal .cookie_type {
        background: #eee;
        padding: 10px;
        margin: 10px 0
    }

    @media (max-width: 575px) {
        .cookiesplus-modal .pull-left,
        .cookiesplus-modal .pull-right {
            float: none !important;
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

<script>
    // <![CDATA[
    var C_P_COOKIE_VALUE = "{$C_P_COOKIE_VALUE|escape:'htmlall':'UTF-8'}";
    var C_P_DEFAULT_VALUE = {$C_P_DEFAULT_VALUE|intval};
    var C_P_VERSION = "{$C_P_VERSION|escape:'htmlall':'UTF-8'}";
    // ]]>
</script>

<div class="cookiesplus">
    <div id="cookiesplus-basic">
        <form method="POST" name="cookies">
            <div class="block">
                <div class="title_block">{l s='Cookie preferences' mod='cookiesplus'}</div>
                <div class="cookie_type_container">
                    {if isset($C_P_TEXT_BASIC) && $C_P_TEXT_BASIC}
                        <div class="cookie_type box">
                            <div>{$C_P_TEXT_BASIC nofilter}</div>
                        </div>
                    {/if}
                </div>

                <div class="cookie_actions">
                    <input type="submit" name="save-basic" onclick="if (cookieGdpr.saveBasic()) return;" class="button_large pull-right" value="{l s='Accept and continue' mod='cookiesplus'}" />
                    {if isset($C_P_CMS_PAGE_ADV) && $C_P_CMS_PAGE_ADV}
                        <a href="{$link->getCMSLink($C_P_CMS_PAGE_ADV)|escape:'html'}" class="pull-right more-information" target="_blank">{l s='More information' mod='cookiesplus'}</a>
                    {/if}
                    <input type="submit" onclick="cookieGdpr.displayModalAdvanced();" class="button pull-left" value="{l s='Advanced settings' mod='cookiesplus'}" />
                    <div class="clear"></div>
                </div>
            </div>
        </form>
    </div>

    <div id="cookiesplus-advanced">
        <div class="block">
            <form method="POST" name="cookies">
                <div class="title_block">{l s='Cookie preferences' mod='cookiesplus'}</div>
                <div class="block_content">
                    <div class="cookie_type_container">
                        {if isset($C_P_TEXT_REQUIRED) && $C_P_TEXT_REQUIRED}
                            <div class="cookie_type block">
                                <div>{$C_P_TEXT_REQUIRED nofilter}</div>
                                <div>
                                    <strong>{l s='Accept strictly necessary cookies?' mod='cookiesplus'}</strong>
                                    <div class="switch">
                                        <label>
                                            {l s='No' mod='cookiesplus'}
                                            <input type="checkbox" name="essential" class="not_uniform comparator">
                                            <span class="lever"></span>
                                            {l s='Yes' mod='cookiesplus'}
                                        </label>
                                    </div>
                                </div>
                            </div>
                        {/if}

                        {if isset($C_P_TEXT_3RDPARTY) && $C_P_TEXT_3RDPARTY}
                            <div class="cookie_type block">
                                <div>{$C_P_TEXT_3RDPARTY nofilter}</div>
                                <div>
                                    <strong>{l s='Accept third-party cookies?' mod='cookiesplus'}</strong>
                                    <div class="switch">
                                        <label>
                                            {l s='No' mod='cookiesplus'}
                                            <input type="checkbox" name="thirdparty" class="not_uniform comparator">
                                            <span class="lever"></span>
                                            {l s='Yes' mod='cookiesplus'}
                                        </label>
                                    </div>
                                </div>
                            </div>
                        {/if}
                    </div>

                    <div class="cookie_actions">
                        <input type="submit" name="save" onclick="if (cookieGdpr.save()) return;" class="button_large pull-right" value="{l s='Save preferences' mod='cookiesplus'}" />
                        {if isset($C_P_CMS_PAGE) && $C_P_CMS_PAGE}
                            <a href="{$link->getCMSLink($C_P_CMS_PAGE)|escape:'html'}" class="pull-right more-information" target="_blank">{l s='More information' mod='cookiesplus'}</a>
                        {/if}
                        {if $C_P_COOKIE_VALUE}
                            <input type="submit" name="remove" onclick="if (cookieGdpr.remove()) return;" class="button pull-left" value="{l s='Remove cookies from this site' mod='cookiesplus'}" />
                        {/if}
                        <div class="clear"></div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div id="cookiesplus-confirm">
        {$C_P_TEXT_REJECT nofilter}
        <div class="cookie_actions">
            <form>
                <input type="button" onclick="cookieGdpr.displayModalAdvanced();" class="button_large pull-right" value="{l s='No, I want to stay here' mod='cookiesplus'}" />
                <input type="submit" name="removeAndRedirect" class="button pull-left" value="{l s='Yes, I\'m sure' mod='cookiesplus'}" />
            </form>
        </div>
    </div>

    <div id="cookiesplus-error">
        {l s='You can not accept 3rd party cookies and don\'t accept required cookies.' mod='cookiesplus'}
        <div class="cookie_actions">
            <input type="button" onclick="cookieGdpr.displayModalAdvanced();" class="button_large pull-right" value="{l s='Ok' mod='cookiesplus'}" />
        </div>
    </div>
</div