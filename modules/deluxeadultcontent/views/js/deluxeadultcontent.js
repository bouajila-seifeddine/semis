/**
* 2007-2016 PrestaShop
*
* NOTICE OF LICENSE
*
* @author    Innovadeluxe SL
* @copyright 2016 Innovadeluxe SL

* @license   INNOVADELUXE
*/

function displayNotification_AC()
{
	var message =	"<div id=\"deluxe_adult_content\" style=\"background-color:"+user_options_AC.dlxColor+"; opacity:"+user_options_AC.dlxOpacity+"\">";
	message+=			"<div id=\"adultcontent\">";
	message+=				"<div id=\"center\">";
	message+=				"<img class=\"logo_aviso\" src=\"https://www.semillaslowcost.com/img/logo_slc.png\" alt=\"Logotipo SLC\">";
	message+=				"<img class=\"logo_aviso_movil\" src=\"https://www.semillaslowcost.com/img/logo_slc_movil.png\" alt=\"Logotipo SLC\">";
	message+=				"<h5>Página de acceso solo permitido para mayores de 18 años.</h5>";
		message+=					"<div id=\"buttons\">";
	message+=						"<div style=\"text-align:center\" id=\"ok\">";
	message+=							"<a href=\"#\" id=\"adultcontentOK\" onClick='JavaScript:setCookie_AC(\""+ user_options_AC.cookieName +"\",365);'>"+user_options_AC.okText+"</a>";
	message+=						"</div>";
	message+=						"<div style=\"text-align:center\" id=\"warning\">";
	message+=							"<a id=\"adultcontentnotOK\" href=\""+user_options_AC.redirectLink+"\" onClick='JavaScript:killCookies_AC();'>"+user_options_AC.notOkText+"</a>";
	message+=						"</div>";
	message+=					"</div>";
	message+=						 urldecode_AC(nl2br_AC(user_options_AC.messageContent, true));

	message+=				"</div";
	message+=			"</div";
	message+=		"</div>";
	jQuery("body").prepend(message);
}

function getCookie_AC(c_name)
{
	var i,x,y,ARRcookies=document.cookie.split(";");
	for (i=0;i<ARRcookies.length;i++)
	{
		x=ARRcookies[i].substr(0,ARRcookies[i].indexOf("="));
		y=ARRcookies[i].substr(ARRcookies[i].indexOf("=")+1);
		x=x.replace(/^\s+|\s+$/g,"");
		if (x==c_name) return unescape(y);
	}
	return null;
}

function _setCookie_AC_AC(name, value, exp_y, exp_m, exp_d, path, domain, secure)
{
	var cookie_string = name + "=" + escape ( value );

	if ( exp_y )
	{
		var expires = new Date ( exp_y, exp_m, exp_d );
		cookie_string += "; expires=" + expires.toGMTString();
	}

	if ( path ) cookie_string += "; path=" + escape ( path );
	if ( domain ) cookie_string += "; domain=" + escape ( domain );
	if ( secure ) cookie_string += "; secure";

	document.cookie = cookie_string;
}

function setCookie_AC(name, exdays)
{
	var c_expires= new Date();
	c_expires.setDate(c_expires.getDate() + exdays);
	_setCookie_AC_AC(escape(name), escape("accepted"), c_expires.getFullYear(), c_expires.getMonth(), c_expires.getDay(), escape("/"),document.domain);
	var deluxecookies = document.getElementById("adultcontent");
	if (deluxecookies) {
		deluxecookies.innerHTML = "";

		if(document.getElementById("deluxe_adult_content") != null){
					document.getElementById("deluxe_adult_content").style.display = "none";

		}
	
		//window.location.reload(false);
	}
}

function checkCookie_AC()
{
	var cookieName= user_options_AC.cookieName;
	var cookieChk=getCookie_AC(cookieName);
	if (cookieChk!=null && cookieChk!="") setCookie_AC(cookieName, 365);
	else displayNotification_AC();
}

function nl2br_AC (str, is_xhtml) {
	var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';
	return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1'+ breakTag +'$2');
}

function killCookies_AC(){
	jQuery('div#acontentwarning p#buttons').html('Clearing cookies and redirecting...');

	var cookies = document.cookie.split(";");
	for (var i = 0; i < cookies.length; i++) _setCookie_AC_AC(cookies[i].split("=")[0], -1,  1970, 1, 1, user_options_AC.cookiePath, user_options_AC.cookieDomain);

	jQuery.getJSON( user_options_AC.ajaxUrl , { path : user_options_AC.cookiePath , domain : user_options_AC.cookieDomain }, function( data ) {});
	window.location = user_options_AC.redirectLink;
}

function urldecode_AC (str) {
	return decodeURIComponent((str + '').replace(/\+/g, '%20'));
}

jQuery(window).load(function(){
	checkCookie_AC();
});
