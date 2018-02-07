/**
 *
 *  Please read the terms of the CLUF license attached to this module(cf "licences" folder)
 *
 * @author    Pronimbo.com
 * @copyright Pronimbo.com. all rights reserved.
 * @license   http://www.pronimbo.com/licenses/license_en.pdf http://www.pronimbo.com/licenses/license_es.pdf https://www.pronimbo.com/licenses/license_fr.pdf
 *
 */

$(document).ready(function(){
    $('.table.paseocenter img[src*=disable], .table.paseocenter img[src*=enable]').closest('a').click(function()
    {
        var obj = $(this);
        var href = $(obj).attr('href');
        var args = $(obj).attr('href').split('&');
        $.ajax({
            url : href +'&ajax=1&action='+args[2],
            contentType : 'json',
            success : function(data){
                data = $.parseJSON(data);
                if(data.success == 1)
                {
                    showSuccessMessage(data.text);
                    if ($(obj).find('img').attr('src').indexOf('enabled.gif') > -1)
                    {
                        $(obj).find('img').attr('src',  $(obj).find('img').attr('src').replace('enabled.gif', 'disabled.gif'));

                    }
                    else
                    {
                        $(obj).find('img').attr('src',  $(obj).find('img').attr('src').replace('disabled.gif', 'enabled.gif'))
                    }
                }
                else
                    showErrorMessage(data.text);
            },
            error: function(data){
                if (error_func)
                    return error_func(data);
            }
        });
        return false;
    });

});