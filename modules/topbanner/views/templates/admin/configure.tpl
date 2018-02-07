{*
* 2007-2017 PrestaShop
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
*  @copyright 2007-2017 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}
<!-- Module content -->
<div id="modulecontent" class="clearfix">
	<!-- Nav tabs -->
	<div class="col-lg-2" style="position:fixed">
		<div class="list-group">
			<a href="#documentation" class="list-group-item active" data-toggle="tab"><i class="icon-book"></i> {l s='Documentation' mod='topbanner'}</a>
			<a id="config_tab" href="#config" class="list-group-item" data-toggle="tab"><i class="icon-indent"></i> {l s='Configuration' mod='topbanner'}</a>
			{if ($apifaq != '')}<a href="#faq" class="faq list-group-item" data-toggle="tab"><i class="icon-question"></i>&nbsp;&nbsp;&nbsp;{l s='Help' mod='topbanner'}</a>{/if}
			<a href="#contacts" class="contacts list-group-item" data-toggle="tab"><i class="icon-envelope"></i> {l s='Contact' mod='topbanner'}</a>
		</div>
		<div class="list-group">
			<a class="list-group-item"><i class="icon-info"></i> {l s='Version' mod='topbanner'} {$module_version|escape:'htmlall':'UTF-8'}</a>
		</div>
	</div>
	<!-- Tab panes -->
	<div class="tab-content col-lg-9" style="position:relative;left:19%">

		{if isset($success) && $success}
			<div class="alert alert-success alert-dismissible" role="alert">
				{l s='Banner saved with success.' mod='topbanner'}
				<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			</div>
		{/if}
		{if isset($remove) && $remove}
			<div class="alert alert-success alert-dismissible" role="alert">
				{l s='Banner removed with success.' mod='topbanner'}
				<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			</div>
		{/if}

		{if isset($errors)}
			{foreach from=$errors item='error'}
				<div class="alert alert-danger alert-dismissible" role="alert">
					{$error|escape:'htmlall':'UTF-8'}
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				</div>
			{/foreach}
		{/if}

		<div class="tab-pane panel active" id="documentation">
			{include file="./tabs/documentation.tpl"}
		</div>

		<div class="tab-pane" id="config">
			{include file="./tabs/config.tpl"}
		</div>

        {if ($apifaq != '')}
    		<div class="tab-pane panel" id="faq">
    			{include file="./tabs/faq.tpl"}
    		</div>
        {/if}

		<div class="tab-pane panel" id="newbanner">
			{include file="./tabs/newbanner.tpl"}
		</div>

		{include file="./tabs/contact.tpl"}
	</div>
</div>

{if isset($id_banner_edit)}
<script>
	$('a[href="#newbanner"]').tab('show');
</script>
{/if}

{if (isset($success) && $success) || isset($remove) && $remove}
    <script>
        $('#config_tab').trigger('click');
    </script>
{/if}

<script>

    window.confirmDelete = "{l s='Are you sure you want to delete this banner ?' mod='topbanner'}";
    window.moduleUrl = "{$module_url|escape:'quotes':'UTF-8'}";

</script>
