/**
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
 */

$(document).on('click', '.delete-banner', function(e) {
    if (!confirm(window.confirmDelete)) {
        e.preventDefault();
    }
});

$(document).on('click', '.btn-danger', function(e) {
    e.preventDefault();
    window.location = window.moduleUrl;
});

$(document).on('click', '.list-group-item', function() {
    $('.list-group-item').removeClass('active');
    $(this).addClass('active');
});

$(document).on('change', 'input[type=radio][name=topbanner_banner_cta]', function() {
	if (parseInt($(this).val()) == 1) {
		$('.cta_group').removeClass('hidden');
	} else {
		$('.cta_group').addClass('hidden');
	}
});

$(document).on('change', 'input[type=radio][name=topbanner_banner_timer]', function() {
	$('.text_group').addClass('hidden');
	$('.text_group_carrier').addClass('hidden');
	$('.timer_group').addClass('hidden');

	if (parseInt($(this).val()) === 1) {
        $('.topbanner_banner_text').val('');
		$('.timer_group').removeClass('hidden');
	} else if (parseInt($(this).val()) === 2) {
		// TODO ?????
		$('.text_group_carrier').removeClass('hidden');
	} else {
        $('.topbanner_banner_timer_left_text').val('');
        $('.topbanner_banner_timer_right_text').val('');
		$('.text_group').removeClass('hidden');
	}
});

$(document).on('click', '.bannerTypeBtn', function() {
	$('.bannerTypeBtn').removeClass('active');

	$(this).addClass('active');
	$('#topbanner_banner_type').val(parseInt($(this).attr('data-type')));

	$('.subtype_freeshipping').addClass('hidden');
	$('.subtype_cartrule').addClass('hidden');
	$('.subtype_cartrule_sales').addClass('hidden');
	$('.timer_choice').addClass('hidden');
	$('.text_group_carrier').addClass('hidden');
	$('.text_group').addClass('hidden');

    $('.information-info').addClass('hidden');
    $('.information-freeshipping').addClass('hidden');
    $('.information-sales').addClass('hidden');

    $('.cta_choice').addClass('hidden');

	if (parseInt($(this).attr('data-type')) === 2) {
		$('.subtype_freeshipping').removeClass('hidden');
        $('.information-freeshipping').removeClass('hidden');
        $('.topbanner_banner_subtype[data-type=1]').click();
	} else if (parseInt($(this).attr('data-type')) === 3) {
        $('.information-sales').removeClass('hidden');
		$('.subtype_cartrule_sales').removeClass('hidden');
		$('.timer_choice').removeClass('hidden');
		$('.text_group').removeClass('hidden');
        $('.cta_choice').removeClass('hidden');
	} else {
		$('.text_group').removeClass('hidden');
        $('.information-info').removeClass('hidden');
        $('.cta_choice').removeClass('hidden');

        $('.timer_group').addClass('hidden');
	}
});

$(document).on('click', '.bannerSubTypeBtn', function() {
	$('.bannerSubTypeBtn').removeClass('active');
	$(this).addClass('active');
	$('#topbanner_banner_subtype').val(parseInt($(this).attr('data-type')));

    $('.text_group_carrier').addClass('hidden');
    $('.subtype_cartrule').addClass('hidden');
    $('.text_group').addClass('hidden');

    $('.cart-rule-info').addClass('hidden');

	if (parseInt($(this).attr('data-type')) === 1) {
		$('.subtype_cartrule').removeClass('hidden');
		$('.text_group').removeClass('hidden');
        $('.cart-rule-info').removeClass('hidden');
	} else if (parseInt($(this).attr('data-type')) === 2) {
		$('.text_group_carrier').removeClass('hidden');
		$('.text_group').addClass('hidden');

		$('.timer_group').addClass('hidden');
	} else {
		// TODO va dedans ?
		$('.subtype_cartrule').addClass('hidden');
	}
});

$(document).on('click', '#topbanner_preview', function() {

    var text = 'en';
    $.each($('.translatable-field .dropdown-toggle'), function() {
        if ($(this).is(':visible')) {
            text = $(this).html();
            text = text.replace('<span class="caret"></span>', '');
            text = text.trim(text);
            return false;
        }
    });

    var $form = $("#topbanner_form");
    if ($form.valid()) {
        $.ajax({
            type: 'POST',
            url: admin_module_ajax_url,
            dataType: 'html',
            data: {
                action : 'Preview',
                ajax : true,
                token: token,
                data: $('#topbanner_form').serialize(),
                iso_lang: text
            },
            success: function(data) {
                $('#topbanner_preview_wrapper').html(data);
            }
        });
    }
});

$(document).ready(function() {

	$('.dataTable').dataTable({
		 "pageLength": 25
	});

    var $form = $("#topbanner_form");

    var rules = {
        topbanner_banner_name: {
            required: true
        },
        topbanner_banner_height: {
            required: true,
            number: true
        },
        topbanner_banner_background: {
            required: true
        },
        topbanner_banner_subtype: {
            required: {
                depends: function (element) {
                    return (parseInt($('#topbanner_banner_type').val()) > 1) ? true : false;
                }
            }
        },
        topbanner_banner_timer_background: {
            required: {
                depends: function (element) {
                    return $("input[name=topbanner_banner_timer]").prop("checked");
                }
            }
        },
        topbanner_banner_timer_text_color: {
            required: {
                depends: function (element) {
                    return $("input[name=topbanner_banner_timer]").prop("checked");
                }
            }
        },
        topbanner_banner_text_size: {
            required: true,
            number: true
        },
        topbanner_banner_text_font: {
            required: true
        },
        topbanner_banner_text_color: {
            required: true,
        },
        topbanner_banner_cartrule: {
            required: {
                depends: function (element) {
                    var type = parseInt($('#topbanner_banner_type').val());
                    var subtype = parseInt($('#topbanner_banner_subtype').val());
                    if (type === 3
                        || (type === 2 && subtype === 1)
                    ) {
                        return true;
                    } else {
                        return false;
                    }
                }
            }
        },
        topbanner_banner_subtype_sales: {
            required: {
                depends: function (element) {
                    return (parseInt($('#topbanner_banner_type').val()) === 3) ? true : false;
                }
            }
        }
    };

    $.each($('.topbanner_banner_timer_left_text'), function(index) {
        rules[$(this).attr('name')] = {
            required: {
                depends: function (element) {
                    return $("input[name=topbanner_banner_timer]").prop("checked");
                }
            }
        };
    });
    $.each($('.topbanner_banner_timer_right_text'), function(index) {
        rules[$(this).attr('name')] = {
            required: {
                depends: function (element) {
                    return $("input[name=topbanner_banner_timer]").prop("checked");
                }
            }
        };
    });
    $.each($('.topbanner_banner_text_carrier_empty'), function(index) {
        rules[$(this).attr('name')] = {
            required: {
                depends: function (element) {
                    return (parseInt($('#topbanner_banner_type').val()) === 2 && parseInt($('#topbanner_banner_subtype').val()) === 2) ? true : false;
                }
            }
        };
    });
    $.each($('.topbanner_banner_text_carrier_between'), function(index) {
        rules[$(this).attr('name')] = {
            required: {
                depends: function (element) {
                    return (parseInt($('#topbanner_banner_type').val()) === 2 && parseInt($('#topbanner_banner_subtype').val()) === 2) ? true : false;
                }
            }
        };
    });
    $.each($('.topbanner_banner_text_carrier_full'), function(index) {
        rules[$(this).attr('name')] = {
            required: {
                depends: function (element) {
                    return (parseInt($('#topbanner_banner_type').val()) === 2 && parseInt($('#topbanner_banner_subtype').val()) === 2) ? true : false;
                }
            }
        };
    });
    $.each($('.topbanner_banner_text'), function(index) {
        rules[$(this).attr('name')] = {
            required: {
                depends: function (element) {
                    var type = parseInt($('#topbanner_banner_type').val());
                    var subtype = parseInt($('#topbanner_banner_subtype').val());
                    if (type === 1
                        || type === 3 && !$("input[name=topbanner_banner_timer]").prop("checked")
                        || (type === 2 && subtype === 1 && !$("input[name=topbanner_banner_timer]").prop("checked"))
                    ) {
                        return true;
                    } else {
                        return false;
                    }
                }
            }
        };
    });
    $.each($('.topbanner_banner_cta_text'), function(index) {
        rules[$(this).attr('name')] = {
            required: {
                depends: function (element) {
                    return $("input[name=topbanner_banner_cta]").prop("checked");
                }
            }
        };
    });
    $.each($('.topbanner_banner_cta_link'), function(index) {
        rules[$(this).attr('name')] = {
            required: {
                depends: function (element) {
                    return $("input[name=topbanner_banner_cta]").prop("checked");
                }
            }
        };
    });
    $.each($('.topbanner_banner_cta_text_color'), function(index) {
        rules[$(this).attr('name')] = {
            required: {
                depends: function (element) {
                    return $("input[name=topbanner_banner_cta]").prop("checked");
                }
            }
        };
    });
    $.each($('.topbanner_banner_cta_background'), function(index) {
        rules[$(this).attr('name')] = {
            required: {
                depends: function (element) {
                    return $("input[name=topbanner_banner_cta]").prop("checked");
                }
            }
        };
    });

    var validate = $form.validate({
        debug: true,
        rules: rules,
        showErrors: function(errorMap, errorList) {
            $.each(errorList, function (key, value) {
                $(value.element).addClass('error');
            });

            if (this.numberOfInvalids() > 0) {
                $('.invalid-form').removeClass('hidden');
            } else {
                $('input').removeClass('error');
            }
        }
    });

    $('#topbanner_form').submit(function(e) {
        var self = this;
        e.preventDefault();

        var go = true;
        $.each($('.multilang_input'), function(key, value) {
            go = validate.element($(value));

            if (!go) {
                return false;
            }
        });

        if ($form.valid() && go) {
            self.submit();
        } else {
           return false;
        }
   });

});
