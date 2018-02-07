$(function() {
    var banners = $('.ph_iconboxes tbody');
    banners.sortable({
        opacity: 0.6,
        cursor: 'move',
        update: function() {
            var ids = '';
            $(this).find('.banner_li').each(function(){
                ids += '&ids[]='+$(this).data('id');
            });
            var order = ids + '&action=updatePosition';
            $.post(module_dir+'ph_iconboxes/ajax.php', order, function(data){
                //console.log(data);
            });
        }
    });
});

$(document).ready(function(){    

});