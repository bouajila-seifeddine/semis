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

var cookieGdpr = {
    init : function() {
        if (!C_P_COOKIE_VALUE) {
            this.displayModal();
        }
    },

    setCookie : function(name, value, days) {
        var expires = "";
        if (days) {
            var date = new Date();
            date.setTime(date.getTime() + (days*24*60*60*1000));
            expires = "; expires=" + date.toUTCString();
        }
        document.cookie = name + "=" + (value || "")  + expires + "; path=/";
    },

    displayModal : function(modal) {
        modal = typeof modal !== 'undefined' ? modal : true;

        var cookie = C_P_COOKIE_VALUE;
        $('input[name="essential"]').attr('checked', true);
        if (cookie == 1) {
            $('input[name="thirdparty"]').attr('checked', false);
        } else if(cookie == 2) {
            $('input[name="thirdparty"]').attr('checked', true);
        } else if (C_P_DEFAULT_VALUE) {
            $('input[name="thirdparty"]').attr('checked', true);
        } else {
            $('input[name="thirdparty"]').attr('checked', false);
        }

        if (C_P_VERSION == '1.7' && typeof($.fn.modal) !== "undefined") {
            if (modal) {
                $('#cookiesplus-basic .close').hide();
            }


            $('#cookiesplus-basic').modal({
                backdrop: (modal ? 'static' : true),
                keyboard: !modal
            });
        } else {
            $.fancybox({
                content         : $('#cookiesplus-basic').html(),
                modal           : modal,
                wrapCSS         : 'cookiesplus-modal',
                autoSize        : false,
                autoDimensions  : false,
                width           : C_P_VERSION == '1.4' ? '70%' : 'auto',
                maxWidth        : '70%',
                height          : 'auto',
                minHeight       : '0'
            });
        }
    },
    displayModalAdvanced : function(modal) {
        modal = typeof modal !== 'undefined' ? modal : true;

        var cookie = C_P_COOKIE_VALUE;
        if (cookie == 1) {
            $('input[name="essential"]').attr('checked', true);
            $('input[name="thirdparty"]').attr('checked', false);
        } else if(cookie == 2) {
            $('input[name="essential"]').attr('checked', true);
            $('input[name="thirdparty"]').attr('checked', true);
        } else if (C_P_DEFAULT_VALUE) {
            $('input[name="essential"]').attr('checked', true);
            $('input[name="thirdparty"]').attr('checked', true);
        } else {
            $('input[name="essential"]').attr('checked', true);
            $('input[name="thirdparty"]').attr('checked', false);
        }

        if (C_P_VERSION == '1.7' && typeof($.fn.modal) !== "undefined") {
            if (modal) {
                $('#cookiesplus-advanced .close').hide();
            }

            $('.modal.show').modal('hide');

            $('#cookiesplus-advanced').modal({
                backdrop: (modal ? 'static' : true),
                keyboard: !modal
            });
        } else {
            $.fancybox({
                content         : $('#cookiesplus-advanced').html(),
                modal           : modal,
                wrapCSS         : 'cookiesplus-modal',
                autoSize        : false,
                autoDimensions  : false,
                width           : C_P_VERSION == '1.4' ? '50%' : 'auto',
                maxWidth        : '80%',
                height          : 'auto',
                minHeight       : '0',
                'onComplete'    : function(){
                    var cookie = C_P_COOKIE_VALUE;
                    if (cookie == 1) {
                        $('input[name="essential"]').attr('checked', true);
                        $('input[name="thirdparty"]').attr('checked', false);
                    } else if(cookie == 2) {
                        $('input[name="essential"]').attr('checked', true);
                        $('input[name="thirdparty"]').attr('checked', true);
                    } else if (C_P_DEFAULT_VALUE) {
                        $('input[name="essential"]').attr('checked', true);
                        $('input[name="thirdparty"]').attr('checked', true);
                    } else {
                        $('input[name="essential"]').attr('checked', true);
                        $('input[name="thirdparty"]').attr('checked', false);
                    }
                }
            });
        }
    },
    saveBasic : function() {
        $('input[name="essential"]').attr('checked', true);
        $('input[name="thirdparty"]').attr('checked', true);

        return true;
    },
    save : function() {
        if (($('.fancybox-wrap').length && !$('.fancybox-wrap input[name="essential"]').is(':checked') && !$('.fancybox-wrap input[name="thirdparty"]').is(':checked'))
            || ($('#fancybox-wrap').length && !$('#fancybox-wrap input[name="essential"]').is(':checked') && !$('#fancybox-wrap input[name="thirdparty"]').is(':checked'))
            || (C_P_VERSION == '1.7' && typeof($.fn.modal) !== "undefined" && !$('input[name="essential"]').is(':checked') && !$('input[name="thirdparty"]').is(':checked'))) {
            if (C_P_VERSION == '1.7' && typeof($.fn.modal) !== "undefined") {

                $('#cookiesplus-confirm .close').hide();

                $('.modal.show').modal('hide');

                $("#cookiesplus-confirm").modal({
                    backdrop    : 'static',
                    keyboard    : false
                });
            } else {
                $.fancybox({
                    content     : $("#cookiesplus-confirm").html(),
                    modal       : true,
                    wrapCSS     : 'cookiesplus-modal',
                    autoSize    : false,
                    width       : 'auto',
                    maxWidth    : '50%',
                    height      : 'auto',
                    minHeight   : '0',
                });
            }

            return false;
        } else if (($('.fancybox-wrap').length && !$('.fancybox-wrap input[name="essential"]').is(':checked') && $('.fancybox-wrap input[name="thirdparty"]').is(':checked'))
            || ($('#fancybox-wrap').length && !$('#fancybox-wrap input[name="essential"]').is(':checked') && $('#fancybox-wrap input[name="thirdparty"]').is(':checked'))
            || (C_P_VERSION == '1.7' && typeof($.fn.modal) !== "undefined" && !$('input[name="essential"]').is(':checked') && $('input[name="thirdparty"]').is(':checked'))) {
            if (C_P_VERSION == '1.7' && typeof($.fn.modal) !== "undefined") {
                $('#cookiesplus-error .close').hide();
                $('.modal.show').modal('hide');
                $("#cookiesplus-error").modal({
                    backdrop    : 'static',
                    keyboard    : false
                });
            } else {
                $.fancybox({
                    content     : $("#cookiesplus-error").html(),
                    modal       : true,
                    wrapCSS     : 'cookiesplus-modal',
                    autoSize    : false,
                    width       : 'auto',
                    maxWidth    : '50%',
                    height      : 'auto',
                    minHeight   : '0'
                });
            }

            return false;
        };

        return true;
    },
    remove : function() {
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
        /*
        var pathBits = location.pathname.split('/');
        var pathCurrent = ' path=';

        // do a simple pathless delete first.
        document.cookie = name + '=; expires=Thu, 01-Jan-1970 00:00:01 GMT;';

        for (var j = 0; j < pathBits.length; j++) {
            pathCurrent += ((pathCurrent.substr(-1) != '/') ? '/' : '') + pathBits[j];
            document.cookie = name + '=; expires=Thu, 01-Jan-1970 00:00:01 GMT;' + pathCurrent + ';';
        }
        */

        return true;
    }
};

$(document).ready(function() {
    cookieGdpr.init();
});