/**
 *
 *  Please read the terms of the CLUF license attached to this module(cf "licences" folder)
 *
 * @author    Pronimbo.
 * @copyright Pronimbo. all rights reserved.
 * @license   http://www.pronimbo.com/licenses/license_en.pdf http://www.pronimbo.com/licenses/license_es.pdf https://www.pronimbo.com/licenses/license_fr.pdf
 *
 */


$(document).ready(function () {
    $('#content').addClass('bootstrap');
    if (typeof helper_tabs != 'undefined' && typeof unique_field_id != 'undefined')
    {
        $.each(helper_tabs, function(index) {
            $('#'+unique_field_id+'fieldset_'+index+' .form-wrapper').prepend('<div class="tab-content panel" />');
            $('#'+unique_field_id+'fieldset_'+index+' .form-wrapper').prepend('<ul class="nav nav-tabs" />');
            $.each(helper_tabs[index], function(key, value) {
                // Move every form-group into the correct .tab-content > .tab-pane
                $('#'+unique_field_id+'fieldset_'+index+' .tab-content').append('<div id="'+key+'" class="tab-pane" />');
                var elemts = $('#'+unique_field_id+'fieldset_'+index).find('[data-tab-id="' + key + '"]');
                $(elemts).appendTo('#'+key);
                // Add the item to the .nav-tabs
                if (elemts.length != 0)
                    $('#'+unique_field_id+'fieldset_'+index+' .nav-tabs').append('<li><a href="#'+key+'" data-toggle="tab">'+value+'</a></li>');
            });
            // Activate the first tab
            $('#'+unique_field_id+'fieldset_'+index+' .tab-content div').first().addClass('active');
            $('#'+unique_field_id+'fieldset_'+index+' .nav-tabs li').first().addClass('active');
        });
    }


    $('._blank').click(function () {
        $(this).attr('target', '_blank');
        return true;
    });

    $('.nav li a').click(function () {
        $('.nav li').removeClass('active');
        $('.tab-pane').removeClass('active');
        $($(this).attr('href')).addClass('active');
        $(this).closest('li').addClass('active');
        return false;
    });

    $('#content').on('.lang-btn', 'click',  function () {
        $(this).closest('div').addClass('open');
    });
});

function hideOtherLanguage(id) {
    $('.translatable-field').hide();
    $('.lang-' + id).show();

    var id_old_language = id_language;
    id_language = id;

    if (id_old_language != id)
        changeEmployeeLanguage();

    updateCurrentText();
}
function changeEmployeeLanguage() {
    if (typeof allowEmployeeFormLang !== 'undefined' && allowEmployeeFormLang)
        $.post("index.php", {
            action: 'formLanguage',
            tab: 'AdminEmployees',
            ajax: 1,
            token: employee_token,
            form_language_id: id_language
        });
}
function updateCurrentText() {
    $('#current_product').html($('#name_' + id_language).val());
}
