/*
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
*/
function getCookie(name) {
    var dc = document.cookie;
    var prefix = name + "=";
    var begin = dc.indexOf("; " + prefix);
    if (begin == -1) {
        begin = dc.indexOf(prefix);
        if (begin != 0) return null;
    }
    else
    {
        begin += 2;
        var end = document.cookie.indexOf(";", begin);
        if (end == -1) {
        end = dc.length;
        }
    }
    // because unescape has been deprecated, replaced with decodeURI
    //return unescape(dc.substring(begin + prefix.length, end));
    return decodeURI(dc.substring(begin + prefix.length, end));
} 

var cookieGdpr = {
    init : function() {
        var cookie_grow= getCookie('DELUXEADULTCONTENTWarningCheck');
        if (!C_P_COOKIE_VALUE && !cookie_grow) {
            this.displayModal();
        }
    },
    displayModal : function() {
        if (C_P_VERSION == '1.7' && typeof($.fn.modal) !== "undefined") {
            $('#cookiesplus-basic .close').hide();
            $('#cookiesplus-basic').modal({
                backdrop: 'static',
                keyboard: false
            });
        } else if (C_P_VERSION == '1.4') {
            $.fancybox({
                content         : $('#cookiesplus-basic').html(),
                modal           : true,
                wrapCSS         : 'cookiesplus-modal',
                width           : C_P_THEME_NAME == 'prestashop_mobile' ? 'auto' : '50%',
                autoDimensions  : C_P_THEME_NAME == 'prestashop_mobile' ? true : false,
                height          : 'auto'
            });
        } else {
            $.fancybox($('#cookiesplus-basic'), {
                modal           : true,
                wrapCSS         : 'cookiesplus-modal',
                autoSize        : false,
                autoDimensions  : false,
                height          : 'auto',
                minHeight       : '0'
            });
        }
    },
    displayModalAdvanced : function(modal) {
        modal = typeof modal !== 'undefined' ? modal : true;

        if (C_P_COOKIE_VALUE == 2) {
            $('input[id="thirdparty"]').attr('checked', true);
        } else if (!C_P_COOKIE_VALUE && C_P_DEFAULT_VALUE) {
            $('input[id="thirdparty"]').attr('checked', true);
        } else {
            $('input[id="thirdparty"]').attr('checked', false);
        }

        if (C_P_VERSION == '1.7' && typeof($.fn.modal) !== "undefined") {
            if (modal) {
                $('#cookiesplus-advanced .close').hide();
            }

            $('.modal.show, .modal.in').modal('hide');

            $('#cookiesplus-advanced').modal({
                backdrop: (modal ? 'static' : true),
                keyboard: !modal
            });
        } else if (C_P_VERSION == '1.4') {
            $.fancybox({
                content         : $('#cookiesplus-advanced').html(),
                modal           : modal,
                wrapCSS         : 'cookiesplus-modal',
                width           : C_P_THEME_NAME == 'prestashop_mobile' ? 'auto' : '50%',
                autoDimensions  : C_P_THEME_NAME == 'prestashop_mobile' ? true : false,
                height          : 'auto',
                'onComplete'    : function(){
                    $('#fancybox-content input:checkbox').each(function() {
                        $(this).attr('id', $(this).data('id'));
                    })
                    if (C_P_COOKIE_VALUE == 2) {
                        $('input[id="thirdparty"]').attr('checked', true);
                    } else if (!C_P_COOKIE_VALUE && C_P_DEFAULT_VALUE) {
                        $('input[id="thirdparty"]').attr('checked', true);
                    } else {
                        $('input[id="thirdparty"]').attr('checked', false);
                    }
                }
            });
        } else {
            $.fancybox($('#cookiesplus-advanced'), {
                modal           : modal,
                wrapCSS         : 'cookiesplus-modal',
                autoSize        : false,
                autoDimensions  : false,
                height          : 'auto',
                minHeight       : '0',
                'onComplete'    : function(){
                    if (C_P_COOKIE_VALUE == 2) {
                        $('input[id="thirdparty"]').attr('checked', true);
                    } else if (!C_P_COOKIE_VALUE && C_P_DEFAULT_VALUE) {
                        $('input[id="thirdparty"]').attr('checked', true);
                    } else {
                        $('input[id="thirdparty"]').attr('checked', false);
                    }
                }
            });
        }

        return false;
    },
    saveBasic : function() {
        $('input[id="thirdparty"]').attr('checked', true);

        return true;
    },
    save : function() {
        if (!$('input[id="thirdparty"]').is(':checked')) {
            //Remove cookies
            var cookies = document.cookie.split("; ");
            for (var c = 0; c < cookies.length; c++) {
                var d = window.location.hostname.split(".");
                while (d.length > 0) {
                    var cookieBase = encodeURIComponent(cookies[c].split(";")[0].split("=")[0]) + '=; expires=Thu, 01-Jan-1970 00:00:01 GMT; domain=' + d.join('.') + ' ;path=';
                    var p = location.pathname.split('/');
                    document.cookie = cookieBase + '/';
                    while (p.length > 0) {
                        document.cookie = cookieBase + p.join('/');
                        p.pop();
                    };
                    d.shift();
                }
            }
        }

        return true;
    },
};

$(document).ready(function() {
    cookieGdpr.init();
});