function isPrestaShop16()
{
	return (ps_version_compare(_PS_VERSION_, '1.6.0.0') === 1);
}

function ps_version_compare(a, b) {
    if (a === b) {
       return 0;
    }

    var a_components = a.split(".");
    var b_components = b.split(".");

    var len = Math.min(a_components.length, b_components.length);

    // loop while the components are equal
    for (var i = 0; i < len; i++) {
        // A bigger than B
        if (parseInt(a_components[i]) > parseInt(b_components[i])) {
            return 1;
        }

        // B bigger than A
        if (parseInt(a_components[i]) < parseInt(b_components[i])) {
            return -1;
        }
    }

    // If one's a prefix of the other, the longer one is greater.
    if (a_components.length > b_components.length) {
        return 1;
    }

    if (a_components.length < b_components.length) {
        return -1;
    }

    // Otherwise they are the same.
    return 0;
}

$(function() {

	// fieldset 2 - mega menu,
	// fieldset 3 - mega categories
	// fieldset 4 
	//$('#fieldset_2, #fieldset_3').hide();
	
	function bindTabUrl()
	{
		var links = $('#links');

		if(links.val() != '')
		{
			$('input[name^="title_"],input[name^="label_"],input[name^="url_"]').attr('disabled', true);
		}	

		links.on('change', function()
		{
			var el = $(this);

			if(el.val() != '')
			{
				$('input[name^="title_"],input[name^="label_"],input[name^="url_"]').attr('disabled', true);
			}
			else
			{
				$('input[name^="title_"],input[name^="label_"],input[name^="url_"]').attr('disabled', false);
			}
		});
	}

	function bindTabType()
	{
		var type = $('input[name=type]');
		var type_checked = $('input[name=type]:checked');

		if(type_checked.val() == '2')
		{
			if(isPrestaShop16())
			{
				$('.form-group.categories').show();
			}
			else
			{
				$('#categories-treeview').parents('.margin-form').show();
				$('#categories-treeview').parents('.margin-form').prev('label').show();
			}
			
		}
		else
		{
			if(isPrestaShop16())
			{
				$('.form-group.categories').hide();
			}
			else
			{
				$('#categories-treeview').parents('.margin-form').hide();
				$('#categories-treeview').parents('.margin-form').prev('label').hide();
			}
		}

		type.on('change', function()
		{
			bindTabType();
		});
	}

	function bindContentTextareas()
	{
		var type = $('input[name=type]:checked');

		if(type.val() == '1')
		{
			$('#fieldset_2').show();
		}

		type.on('change', function()
		{
			var el = $(this);

			if(el.val() == '1')
			{
				$('#fieldset_2').show();
			}
			else
			{
				$('#fieldset_2').hide();
			}
		});
	}

	if($('input[name=id_parent]').length === 0)
	{
		//bindTabUrl();
		bindTabType();
	}
	else
	{

		if($('input[name=type]').val() == '7')
		{
			$('#prestahome_megamenu_form').submit();
		}
	}



	$('#select_product').select2({'width' : '100%'});

	if(typeof(Tree) != 'undefined') {
		Tree.prototype.init = function(){
			var that = $(this);
			var name = this.$element.parent().find('ul.tree input').first().attr('name');
			this.$element.find("label.tree-toggler, .icon-folder-close, .icon-folder-open").unbind('click');
			this.$element.find("label.tree-toggler, .icon-folder-close, .icon-folder-open").click(
				function ()
				{
					if ($(this).parent().parent().children("ul.tree").is(":visible"))
					{
						$(this).parent().children(".icon-folder-open")
							.removeClass("icon-folder-open")
							.addClass("icon-folder-close");

						that.trigger('collapse');
						$(this).parent().parent().children("ul.tree").toggle(300);
					}
					else
					{
						$(this).parent().children(".icon-folder-close")
							.removeClass("icon-folder-close")
							.addClass("icon-folder-open");

						var load_tree = (typeof(idTree) != 'undefined'
										 && $(this).parent().closest('.tree-folder').find('ul.tree .tree-toggler').first().html() == '');
						if (load_tree)
						{
							var category = $(this).parent().children('ul.tree input').first().val();
							var inputType = $(this).parent().children('ul.tree input').first().attr('type');
							var useCheckBox = 0;
							if (inputType == 'checkbox')
							{
								useCheckBox = 1;
							}

							var thatOne = $(this);
							$.get(
								'ajax-tab.php',
								{controller:'AdminProducts',token:currentToken,action:'getCategoryTree',type:idTree,category:category,inputName:name,useCheckBox:useCheckBox},
								function(content)
								{
									thatOne.parent().closest('.tree-folder').find('ul.tree').html(content);
									$('#'+idTree).tree('collapse', thatOne.closest('.tree-folder').children("ul.tree"));
									that.trigger('expand');
									thatOne.parent().parent().children("ul.tree").toggle(300);
									$('#'+idTree).tree('init');
								}
							);
						}
						else
						{
							that.trigger('expand');
							$(this).parent().parent().children("ul.tree").toggle(300);
						}
					}
				}
			);
			this.$element.find("li").unbind('click');
			this.$element.find("li").click(
				function ()
				{
					$('.tree-selected').removeClass("tree-selected");
					$('li input:checked').parent().addClass("tree-selected");
				}
			);

			if (typeof(idTree) != 'undefined')
			{
				if ($('select#id_category_default').length)
				{
					this.$element.find(':input[type=checkbox]').unbind('click');
					this.$element.find(':input[type=checkbox]').click(function()
					{
						if ($(this).prop('checked'))
							addDefaultCategory($(this));
						else
						{
							$('select#id_category_default option[value=' + $(this).val() + ']').remove();
							if ($('select#id_category_default option').length == 0)
							{
								$('select#id_category_default').closest('.form-group').hide();
								$('#no_default_category').show();
							}
						}
					});
				}
				if (typeof(treeClickFunc) != 'undefined')
				{
					this.$element.find(":input[type=radio]").unbind('click');
					this.$element.find(":input[type=radio]").click(treeClickFunc);
				}
			}

			return $(this);
		}
	}
});

