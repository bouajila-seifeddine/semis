{include file="page_header_toolbar.tpl" toolbar_btn=$toolbar_btn toolbar_scroll=$toolbar_scroll title=$title}

{if $error_msg}
	<div class="alert alert-danger">
		{$error_msg|escape:'UTF-8'}
	</div>
{/if}

{if $update_available eq true}
<div class="alert alert-info">
	<h2 style="margin-top: -5px; margin-bottom: 15px;">{l s='An upgrade for your theme is available!' mod='prestahome'}</h2>

	{if $is_auto_update_available eq true}
	{if $invalid_purchase_code eq true && (!isset($options['purchase_code']) OR empty($options['purchase_code']))}
	<div class="alert alert-warning">
		<p>{l s='You need to provide valid purchase code in order to run auto-update.' mod='prestahome'}</p>
	</div>
	{/if}

	<div class="row">
		<div class="alert alert-warning col-md-5">
			<p><strong>{l s='Remember' mod='prestahome'}</strong></p>
			<ul style="padding-left: 15px; padding-top: 10px;">
				<li>{l s='First of all - you should always have theme (and PrestaShop) in the newest version - do not skip updates.' mod='prestahome'}</li>
				<li>{l s='All your changes in theme files (theme modules, tpl files, css/js files) can be overriden by update.' mod='prestahome'}</li>
				<li>{l s='All your custom settings from Theme Options, custom CSS, custom JS and third party modules are' mod='prestahome'} <b>{l s='completely safe' mod='prestahome'}</b>.</li>
				<li>{l s='Your server needs to have properly setup CHMOD for themes/modules/override folders. If you are not sure about this you can contact your hosting provider.' mod='prestahome'}</li>
				<li>{l s='It is highly recommended to do a full backup of your store before update.' mod='prestahome'}</li>
				<li>{l s='You can always update theme manually - more information in documentation.' mod='prestahome'}</li>
				<li>{l s='Our mechanism is safe so if there will be some errors during the process update will be stopped without losing any data.' mod='prestahome'}</li>
			</ul>
		</div>
		<div class="col-md-6 col-md-push-1">

			{if $changelog}
			<p><strong>{l s='Changelog:' mod='prestahome'}</strong></p>
			<pre>{$changelog|escape:'UTF-8'}</pre>
			{/if}

			<p><strong>{l s='I understand, and I have a full backup of my site, I want to...' mod='prestahome'}</strong></p>
			<a href="#" class="btn btn-default" data-toggle="confirmation">{l s='...update my theme.' mod='prestahome'}</a>
		</div>
	</div>
	{else}
	{l s='For this update automatic method is not available. Please visit Marketplace, download the new package and follow instructions from documentation.' mod='prestahome'}
	{/if}
</div>
<div id="confirm-update" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">{l s='Are you sure?' mod='prestahome'}</h4>
            </div>
            <div class="modal-body">
                <p class="text-warning">{l s='Remember about a backup' mod='prestahome'}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">{l s='Cancel' mod='prestahome'}</button>
                <a class="btn btn-primary" href="{$action|escape:'UTF-8'}&amp;doUpdate=true">{l s='Update' mod='prestahome'}</a>
            </div>
        </div>
    </div>
</div>
{/if}

{assign var=subsections value=[]}
<form action="{$action|escape:'UTF-8'}" method="post" enctype="multipart/form-data" id="configuration_form">
<fieldset id="prestahome_options_wrapper">
	<legend><img src="../modules/prestahome/logo.png"> {l s='Theme Options' mod='prestahome'}</legend>
		<div class="row">
			<div class="col-xs-2">
				<!-- Nav tabs -->
				<ul class="nav nav-tabs tabs-left">
					{foreach $sections as $section_id => $section_tab name='sectionsTabs'}
					<li class="{if $smarty.foreach.sectionsTabs.first}active{/if}" id="tab-{$section_id|escape:'UTF-8'}">
						<a href="#section-{$section_id|escape:'UTF-8'}" data-toggle="tab" data-tab="{$section_id|escape:'UTF-8'}">
							{if $section_tab.icon}
								<i class="fa {$section_tab.icon|escape:'UTF-8'}"></i>
							{/if}
							{$section_tab.title|escape:'UTF-8'}
						</a>
						{if isset($section_tab.fields)}
							{foreach $section_tab.fields as $key => $subsection}
								{if $subsection.type == 'sub-title-block'}
									{append var='subsections' value=['id'=>$subsection.id, 'title'=>$subsection.title]}
								{/if}
							{/foreach}
						{/if}
						{if sizeof($subsections) > 0}
							<ul style="display: none;" class="subsection" id="subsection_section_{$section_id|escape:'UTF-8'}" data-section="{$section_id|escape:'UTF-8'}">
							{foreach $subsections as $subsection}
							<li id="subsection_{$subsection.id|escape:'UTF-8'}"><a href="#{$subsection.id|escape:'UTF-8'}">{$subsection.title|escape:'UTF-8'}</a></li>
							{/foreach}
							</ul>
						{/if}
					</li>
					{assign var=subsections value=[]}
					{/foreach}
					<li id="tab-999"><a href="{$action|escape:'UTF-8'}&amp;tab=999" data-tab="999"><i class="fa fa-copy"></i> Import/Export</a></li>
				</ul>
			</div><!-- .col -->

			<div class="col-xs-10">
				<div class="tab-content">
					{foreach $sections as $section_id => $section_tab name='sectionsContent'}
					<div class="tab-pane {if $smarty.foreach.sectionsContent.first}active{/if}" id="section-{$section_id|escape:'UTF-8'}">
						<div class="panel">
							<div class="panel-heading">
								<i class="icon-cogs"></i> {$section_tab.title|escape:'UTF-8'}
							</div>

							<div class="form-wrapper">
								{foreach $section_tab.fields as $field}
									{if !in_array($field.type, $ignoredTypes) }
										<div class="form-group row">
											<div id="{$field.id|escape:'UTF-8'}">

												{if isset($field.title)}								
												<label class="control-label col-lg-3" for="{$field.id|escape:'UTF-8'}">
													{$field.title|escape:'UTF-8'}
												</label>
												{/if}

												<div class="col-lg-9">

												{if $field.type == 'switch'}
													{include file='./fields/switch.tpl'}

												{elseif $field.type == 'textLang'}
													{include file='./fields/textLang.tpl'}

												{elseif $field.type == 'text'}
													{include file='./fields/text.tpl'}	

												{elseif $field.type == 'select'}
													{include file='./fields/select.tpl'}

												{elseif $field.type == 'selectLang'}
													{include file='./fields/selectLang.tpl'}

												{elseif $field.type == 'textareaLang'}
													{include file='./fields/textareaLang.tpl'}	

												{elseif $field.type == 'textarea'}
													{include file='./fields/textarea.tpl'}
												
												{elseif $field.type == 'colorpicker-load-schemes'}
													{include file='./fields/colorpicker-load-schemes.tpl'}	

												{elseif $field.type == 'colorpicker'}
													{include file='./fields/colorpicker.tpl'}

												{elseif $field.type == 'gradient'}
													{include file='./fields/gradient.tpl'}

												{elseif $field.type == 'module'}
													{include file='./fields/module.tpl'}

												{elseif $field.type == 'custom_js'}
													{include file='./fields/custom_js.tpl'}	

												{elseif $field.type == 'uploadImage'}
													{include file='./fields/uploadImage.tpl'}		

												{elseif $field.type == 'textBlock'}
													{include file='./fields/textBlock.tpl'}	

												{elseif $field.type == 'font'}
													{include file='./fields/font.tpl'}	

												{elseif $field.type == 'font-size'}
													{include file='./fields/font-size.tpl'}		
														
												{/if}

												</div><!-- .col -->

											</div><!-- .conf -->

											<div class="col-lg-9 col-lg-offset-3">

												{if isset($field.desc)}
												<p class="help-block">
													{$field.desc|escape:'UTF-8'}
												</p>
												{/if}

											</div><!-- .col desc -->
										</div><!-- form.group -->
									{else}
										{if $field.type == 'info-box'}
											<div class="alert alert-info">
												{if isset($field.title)}
													<strong>{$field.title|escape:'UTF-8'}</strong>
												{/if}
												{if isset($field.desc)}
													{$field.desc|escape:'UTF-8'}
												{/if}
											</div>
										{elseif $field.type == 'title-block' && isset($field.title)}
											<h1 class="tab_title page-header">{$field.title|escape:'UTF-8'}</h1>
										{elseif $field.type == 'sub-title-block' && isset($field.title)}
											<h2 class="tab_subtitle page-header" id="{$field.id|escape:'UTF-8'}">{$field.title|escape:'UTF-8'}</h2>

										{elseif $field.type == 'separator'}
											<hr class="divider" />
										{else}
										{/if}
									{/if}
								{/foreach}
							</div><!-- .form-wrapper -->

							<div class="panel-footer">
								<button type="submit" class="btn btn-default pull-right" name="submitOptionsconfiguration">
									<i class="process-icon-save"></i> {l s='Save' mod='prestahome'}
								</button>
							</div><!-- .panel-footer -->
						</div><!-- .panel -->
						
					</div>
					{/foreach}
					<div class="tab-pane" id="section-backups">
						<div class="panel">
							<div class="panel-heading">
								<i class="icon-cogs"></i> Import / Export
							</div>

							<div class="alert alert-warning">
								Important! This is beta version of mechanism to import and export Theme Options, at this moment we not handle images, background images etc.
							</div>

							<div class="form-wrapper">
								<div class="form-group row">
									<label class="control-label col-lg-3">
										{l s='EXPORT - Current options:' mod='prestahome'}
									</label>
									<textarea style="height: 200px!important;" name="oldOptionsToImport">{$safeCurrentOptions|escape:'UTF-8'}</textarea>
								</div>

								<div class="form-group row">
									<label class="control-label col-lg-3">
										{l s='IMPORT - Paste options to import:' mod='prestahome'}
									</label>
									<textarea style="height: 200px!important;" name="newOptionsToImport"></textarea>
								</div>
							</div><!-- .form-wrapper -->

							<div class="panel-footer">
								<button type="submit" class="btn btn-default pull-right" name="submitImportOptions">
									<i class="process-icon-save"></i> {l s='Import' mod='prestahome'}
								</button>
							</div><!-- .panel-footer -->
						</div><!-- .panel -->
						
					</div>
				</div>
			</div><!-- .col -->  
		</div>
		<input type="hidden" name="ph_tab" value="{Tools::getValue('tab', 0)|escape:'UTF-8'}" />
</fieldset>

<script type="text/javascript">
	var module_dir = '{$smarty.const._MODULE_DIR_|addslashes}';
	var id_language = {$defaultFormLanguage|intval};
	var languages = new Array();
	var restoreConfirmationText = '{l s="Are you sure?" mod="prestahome" js=1}';
	// Multilang field setup must happen before document is ready so that calls to displayFlags() to avoid
	// precedence conflicts with other document.ready() blocks
	{foreach $languages as $k => $language}
		languages[{$k|escape:'UTF-8'}] = {
			id_lang: {$language.id_lang|intval},
			iso_code: '{$language.iso_code|escape:'UTF-8'}',
			name: '{$language.name|escape:'UTF-8'}',
			is_default: '{if $language.id_lang == $defaultFormLanguage}1{else}0{/if}'
		};
	{/foreach}
	// we need allowEmployeeFormLang var in ajax request
	allowEmployeeFormLang = true;
	displayFlags(languages, id_language, allowEmployeeFormLang);
	hideOtherLanguage({$defaultFormLanguage|intval});

	phSelectTab({Tools::getValue('tab', 0)|escape:'UTF-8'});

	$('textarea').autosize();

	$(document).ready(function() {

		{foreach $schemes_colors as $color}
		$.fn.mColorPicker.addToSwatch("{$color|escape:'UTF-8'}");
		{/foreach}

		$(".show_checkbox").click(function () {
			$(this).addClass('hidden')
			$(this).siblings('.checkbox').removeClass('hidden');
			$(this).siblings('.hide_checkbox').removeClass('hidden');
			return false;
		});
		$(".hide_checkbox").click(function () {
			$(this).addClass('hidden')
			$(this).siblings('.checkbox').addClass('hidden');
			$(this).siblings('.show_checkbox').removeClass('hidden');
			return false;
		});

		if ($(".datepicker").length > 0)
			$(".datepicker").datepicker({
				prevText: '',
				nextText: '',
				dateFormat: 'yy-mm-dd'
			});
	});
	var iso = '{$iso|addslashes}';
	var pathCSS = '{$smarty.const._THEME_CSS_DIR_|addslashes}';
	var ad = '{$ad|addslashes}';

	$(document).ready(function(){
		{block name="autoload_tinyMCE"}
			tinySetup({
				editor_selector :"autoload_rte"
			});
		{/block}
	});
</script>
</form>