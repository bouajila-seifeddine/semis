<script>
var ph_theme_version = '{$theme_version|escape:"html":"UTF-8"}';
var ph_theme_name = '{$theme_name|escape:"html":"UTF-8"}';

$(function() {ldelim}
	$('.dash_news.panel').before('<div class="dash_news panel" style="background: #fff; border-radius: 0; box-shadow: none;">' +
			'<h3 class="text-center">' +
				'<span class="icon-asterisk"></span> PrestaHome - Theme Updates' +
			'</h3>' +
			'<p class="text-center"><img src="../modules/prestahome/logo.png" alt="PrestaHome" /></p>' +
			'<div class="panel-body">' +
				'<p class="text-center"><b>An update for your theme is available!</b></p>' +
				'<p class="text-center"><a class="btn btn-default" href="{$url|escape:'UTF-8'}">' +
					'Update now' +
				'</a></p>' +
			'</div>' +
		'</div>');
{rdelim});
</script>