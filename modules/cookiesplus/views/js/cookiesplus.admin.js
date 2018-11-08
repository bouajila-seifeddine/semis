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

$(document).ready(function() {
	$('.id-hidden').closest('.margin-form').hide().prev().hide()
    showHideDebugIPs();
    $('input[name="C_P_DEBUG"]').change(function() {
        showHideDebugIPs();
    });
});

function showHideDebugIPs() {
    if ($('input[name="C_P_DEBUG"]:checked').val() == 1) {
        $('#C_P_IPS_DEBUG').closest('.form-group').slideDown();  // > PS 1.6
        $('#C_P_IPS_DEBUG').closest('.margin-form').hide().prev().hide(); //PS 1.5
    } else {
        $('#C_P_IPS_DEBUG').closest('.form-group').slideUp(); // > PS 1.6
        $('#C_P_IPS_DEBUG').closest('.margin-form').show().prev().show(); // PS 1.5
    }
}