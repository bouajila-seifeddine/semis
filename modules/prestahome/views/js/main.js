/**
 * Change color in inputs based on selected color scheme
 * @param  {string} scheme Selected scheme
 * @return {bool}
 */
function changeColorInInputs(scheme)
{
	$('.load-default-color').each(function()
	{
		$(this).find('.scheme-' + scheme).prop('selected', true);

		var parentID = $(this).data('wrapper');
		var input = $('#' + parentID).find('.color');
		input.val($(this).val()).css('background-color', $(this).val());
	});
}

function previewGoogleFont(url, font, element)
{
	if(url != '0')
	{
		$("head").append('<link href="'+url+'" rel="stylesheet" type="text/css">');
	}
	
	var previewElement = $(element);

	previewElement.css('font-family', font);
}

function scrollToElement(selector, time, verticalOffset) {
    time = typeof(time) != 'undefined' ? time : 1000;
    verticalOffset = typeof(verticalOffset) != 'undefined' ? verticalOffset : 0;
    element = $(selector);
    offset = element.offset();
    offsetTop = offset.top + verticalOffset;
    $('html, body').animate({
        scrollTop: offsetTop
    }, time);
}

$(function() {

	$('body').on('change', '.googleFontPreviewSize', function()
	{
		var fontSize = $(this).val();
		var element = $($(this).data('element'));

		element.css({'font-size':fontSize});
	});

	$('body').on('change', '.previewGoogleFont', function()
	{
		var selectValue = $(this).val();
		var url = $(this).find('option:selected').data('url');

		if(url != '')
		{
			previewGoogleFont(url, selectValue, $(this).data('element'));
		}
		else
		{
			previewGoogleFont('0', selectValue, $(this).data('element'));
		}
		
	});

	$(document).on('click', 'a.delete_uploaded_image', function(e)
	{
		e.preventDefault();
		var fieldName = $(this).data('field');
		var imgContainers = $('#upload_preview_' + fieldName + ', #preview_box_' + fieldName);
		doAdminAjax({
			"field": fieldName,
			"action":"deleteImage",
			"token" : token,
			"tab" : "AdminPrestaHomeOptions",
			"ajax" : 1 
			},
			function(data){
				data = $.parseJSON(data);
				if (data.confirmations.length != 0)
					showSuccessMessage(data.confirmations);
				else
					showErrorMessage(data.error);

				imgContainers.empty();
			}
		);
		
	});

	$(document).on('click', '#page-header-desc-configuration-save, button[name=submitOptionsconfiguration]', function(e)
	{
		tinyMCE.triggerSave();
		var btn = $(this);
		btn.hide();

		doAdminAjax({
			"fields":$('#configuration_form').serialize(),
			"action":"saveOptions",
			"token" : token,
			"tab" : "AdminPrestaHomeOptions",
			"ajax" : 1 
			},
			function(data){
				data = $.parseJSON(data);
				if (data.confirmations.length != 0)
					showSuccessMessage(data.confirmations);
				else
					showErrorMessage(data.error);

				btn.show();
			}
		);
		e.preventDefault();
	});

	$(document).on('click', '#page-header-desc-configuration-refresh-index', function(e) {

		var restoreSettings = confirm(restoreConfirmationText);
		if (restoreSettings == true)
		{
			return true;
		}
		else
		{
			e.preventDefault();
		}
	});

	// $('.increase-font-size').on('click', function(e)
	// {
	// 	e.preventDefault();
	// 	var element = $($(this).data('element'));
	// 	var fontSize =  parseInt(element.css('font-size'));
	// 	fontSize = fontSize + 1 + "px";
	// 	element.css({'font-size':fontSize});
	// });

	// $('.decrease-font-size').on('click', function(e)
	// {
	// 	e.preventDefault();
	// 	var element = $($(this).data('element'));
	// 	var fontSize =  parseInt(element.css('font-size'));
	// 	fontSize = fontSize - 1 + "px";
	// 	element.css({'font-size':fontSize});
	// });

	$(document).on('click', '#prestahome_options_wrapper .nav-tabs > li > a', function(e) {

		if($(this).data('tab') == '999')
			window.location.href = $(this).attr('href');  

		e.preventDefault();

		$('textarea').autosize();

		$('.subsection').hide();

		if($('#subsection_section_'+$(this).data('tab')).length > 0)
		{
			$('#subsection_section_'+$(this).data('tab')).show();
		}

		$('input[name=ph_tab]').val($(this).data('tab'));
	});

  	$('body').on('click', '.subsection li a', function(e){

	    e.preventDefault();

	    scrollToElement($(this).attr('href'), 1000, -100);
  	});

	$('body').on('change', '.load-default-color', function()
	{
		var parentID = $(this).data('wrapper');
		var input = $('#' + parentID).find('.color');

		input.val($(this).val()).css('background-color', $(this).val());
	});

	$('body').on('change', '.load-defaults-color', function()
	{
		changeColorInInputs($(this).val());
	});

	$(document).on('change', '.module-switch input', function(e){
		e.preventDefault();

		var method;

		if($(this).val() == 1)
		{
			method = 'enable';
		}
		else
		{
			method = 'disable';
		}
		module = $(this).data('module');
		
		doAdminAjax({
			"action":"switchModuleStatus",
			"module": module,
			"method": method,
			"token" : token,
			"tab" : "AdminPrestaHomeOptions",
			"ajax" : 1 
			}
		);
	});

  	$('#prestahome_options_wrapper select').select2();

  	$('a[data-toggle=confirmation]').on('click', function(e){
	    var $link=$(this); 
	    e.preventDefault();
	    $('#confirm-update').modal({
            keyboard: false,
            backdrop: 'static',
        })
        .one('click', '#confirm-update-submit', function() {
            $link.trigger('click');
        });
	});
});

function phSelectTab(tab)
{
	////console.log(tab);
	$('#prestahome_options_wrapper').find('.tab-pane.active,.nav-tabs li.active').removeClass('active');
	$('#tab-'+tab+',#section-'+tab).addClass('active');

	$('.subsection').hide();

	if($('#subsection_section_'+tab).length > 0)
	{
		$('#subsection_section_'+tab).show();
	}

	if(tab == 999)
		$('#section-backups').addClass('active');
}