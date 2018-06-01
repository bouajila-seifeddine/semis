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

/*
$('a[href="#fieldset_1_1"]').click(function() {
 	tinySetup({
	    editor_selector : "autoload_rte",
	    valid_children : "+body[style|script|iframe|section|link],pre[iframe|section|script|div|p|br|span|img|style|h1|h2|h3|h4|h5],*[*]",
	    forced_root_block : ''
	});
})
*/

$(document).ready(function() {
    tinySetup({
	    editor_selector :"autoload_rte",
        setup: function (ed) {
			ed.on('init', function(args) {
				var id = ed.id;
				var height = 25;
         		document.getElementById(id + '_ifr').style.height = height + 'px';
      		});
      	}
   	})
});
