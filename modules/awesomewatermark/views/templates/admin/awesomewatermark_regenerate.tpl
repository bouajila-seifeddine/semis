{extends file="./awesomewatermark_layout.tpl"}

{block "awcontent"}
<div class="row">
	<div class="col-lg-12">
        <div class="panel">
            <div class="panel-heading">{l s='Regenerate thumbnails' mod='awesomewatermark'}</div>
            <div class="panel-content">
                <div class="row">
                    <div class="col-sm-3">
                        <a href="{$aw_link|escape:'htmlall':'UTF-8'}&ajax=true&action=GetFiles" class="btn btn-info" id="aw-fetch"><i class="icon fa fa-tasks icon-tasks" aria-hidden="true"></i> {l s='Fetch list' mod='awesomewatermark'}</a>
                    </div>
                    <div class="col-sm-6 form-inline">
                        <label for="id_image-from">{l s='Only regenerate from Image ID:' mod='awesomewatermark'}</label>
                        <input type="text" id="id_image-from" name="id_image-from" placeholder="{l s='First ID' mod='awesomewatermark'}">
                        <label for="id_image-to">{l s='to ID:' mod='awesomewatermark'}</label>
                        <input type="text" name="id_image-to" id="id_image-to" placeholder="{l s='Last ID' mod='awesomewatermark'}">

                        <a disabled="disabled" class="btn btn-info" id="aw-mark"><i class="icon fa fa-check icon-check" aria-hidden="true"></i> {l s='Mark for regeneration' mod='awesomewatermark'}</a>

                        <p style="margin-top: 1em;">{l s='Tip: Just first or last Image ID of range is enough. Empty fields means all images.' mod='awesomewatermark'}</p>
                    </div>
                    <div class="col-sm-3">
                        <div class="pull-right">
                            <a href="{$aw_link|escape:'htmlall':'UTF-8'}&ajax=true&action=RegenerateOne" disabled="disabled" class="btn btn-success" id="aw-start"><i class="icon fa fa-play icon-play" aria-hidden="true"></i> {l s='Start' mod='awesomewatermark'}</a>
                            <a disabled="disabled" class="btn btn-warning" id="aw-pause"><i class="icon fa fa-pause icon-pause" aria-hidden="true"></i> {l s='Pause' mod='awesomewatermark'}</a>
                            <a disabled="disabled" class="btn btn-danger" id="aw-retry"><i class="icon fa fa-refresh icon-refresh" aria-hidden="true"></i> {l s='Retry' mod='awesomewatermark'}</a>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="panel">
            <div class="panel-heading">{l s='Photos' mod='awesomewatermark'}</div>
            <div class="panel-content">
                <div id="aw-regenerate-list">
                </div>
            </div>
        </div>
	</div>
</div>

<script type="text/javascript">
{literal}
;(function ($, window, document, undefined) {
    "use strict";

    (function(aw) {
        var container = $('#aw-regenerate-list')

        aw.RE = {}
        aw.RE.working = true;
        aw.RE.started = false;
        aw.RE.time = (new Date()).getTime();
        aw.RE.storage = {}

        aw.RE.storage.set = function (key, value) {
            if (typeof localStorage !== 'undefined') {
                try {
                    localStorage.setItem(key, value)
                    return true
                } catch(e) {
                    return false
                }
            } else {
                return false
            }
        }

        aw.RE.storage.get = function (key) {
            if (typeof localStorage !== 'undefined') {
                try {
                    return localStorage.getItem(key)
                } catch(e) {
                    return false
                }
            } else {
                return false
            }
        }

        aw.RE.next = function () {
            if (aw.RE.working) {
                aw.RE.started = true;
                var item = $('#aw-regenerate-list table tbody tr.aw-waiting')
                var url = $('#aw-start').attr('href')
                if (item.size() > 0) {
                    item = item.eq(0)

                    item.attr('class', 'aw-current info')
                    var time = (new Date()).getTime()
                    $.getJSON(url, {'id_image': $(item).attr('data-id_image'), 'id_product': $(item).attr('data-id_product')})
                        .success(function (result) {
                            item.attr('class', 'aw-success success')
                            try {
                                var res = result || {}
                                res.response = res.response || {}
                                res.response.result = res.response.result || 'error'
                                $('.aw-result', item)
                                    .html(res.response.result)
                            } catch (e) {
                                //console.log(e)
                            }
                        })
                        .error(function (result) {
                        item.attr('class', 'aw-error danger')
                            try {
                                var res = result || {}
                                res.response = res.response || {}
                                res.response.result = res.response.result || 'error'
                                $('.aw-result', item)
                                    .html(res.response.result)
                            } catch (e) {
                                //console.log(e)
                            }
                        })
                        .always(function () {
                            $('.aw-actions', item).html(
                                $('<a>', {'href': '#', 'class': 'btn btn-default btn-sm', 'html': '<i class="icon fa fa-refresh icon-refresh"></i>'})
                                    .on('click', function (e) {
                                        e.preventDefault()
                                        var tr = $(this).closest('tr').attr('class', 'aw-waiting')
                                        $(this).parent().empty()
                                        tr.find('.aw-result').empty()
                                    })
                            )

                            var time_end = new Date()
                            $('.aw-result', item).append($('<span>', {'html': ' ('+((time_end.getTime()-time)/1000)+'s)'}))

                            var title = $('#aw-regenerate-list').closest('.panel').find('.panel-heading')
                            $('span', title).remove()
                            $('<span>', {'class': 'badge', 'html': ' ('+((time_end.getTime()-aw.RE.time)/1000)+'s)'})
                                .css({'text-transform': 'lowercase'})
                                .appendTo(title)

                            aw.RE.next()
                        })
                } else {
                    aw.RE.started = false;
                    $('#aw-start').removeAttr('disabled')
                }
            }
        }

        aw.RE.init = function () {
            var table = $('<table>', {'class': 'table table-striped table-condensed table-bordered'})
                .appendTo(container)

            $('#id_image-from').val(aw.RE.storage.get('ps-aw-id_image-from'))
            $('#id_image-to').val(aw.RE.storage.get('ps-aw-id_image-to'))
            
            var th = $('<thead>')
                .append(
                    $('<tr>')
                        .append($('<th>', {'html': aw_translate('id_image')}))
                        .append($('<th>', {'html': aw_translate('id_product')}))
                        .append($('<th>', {'html': aw_translate('result')}))
                        .append($('<th>', {'html': aw_translate('action')}))
                )
                .appendTo(table)

            var tb = $('<tbody>')
                .appendTo(table)

            $('#aw-fetch').on('click', function (e) {
                e.preventDefault()

                $.getJSON($(this).attr('href'))
                    .success(function (result) {
                        $(tb).empty()

                        result = result || []

                        for (var i = 0; i < result.length; i++) {
                            $('<tr>')
                                .addClass('aw-waiting')
                                .attr('data-id_image', result[i].id_image)
                                .attr('data-id_product', result[i].id_product)
                                .append($('<td>', {'html': result[i].id_image}))
                                .append($('<td>', {'html': result[i].id_product}))
                                .append($('<td>', {'class': 'aw-result', 'html': ''}))
                                .append($('<td>', {'class': 'aw-actions', 'html': ''}))
                                .appendTo(tb)
                        }

                        $('#aw-start').removeAttr('disabled')
                        $('#aw-retry').removeAttr('disabled')
                        $('#aw-mark').removeAttr('disabled')
                    })
            })

            $('#aw-start').on('click', function (e) {
                e.preventDefault()
                if ( ! aw.RE.started) {
                    $('#aw-start').attr('disabled', 'disabled')
                    $('#aw-pause').removeAttr('disabled')
                    aw.RE.working = true
                    aw.RE.next()
                } else {
                    alert(aw_translate('already_started'))
                }
            })

            $('#aw-pause').on('click', function (e) {
                e.preventDefault()
                $('#aw-start').removeAttr('disabled')
                $(this).attr('disabled', 'disabled')
                aw.RE.working = false
                aw.RE.started = false
            })

            $('#aw-retry').on('click', function (e) {
                e.preventDefault()
                
                //aw.RE.working = true
                
                var items = $('#aw-regenerate-list table tbody tr.aw-error')
                    .attr('class', 'aw-waiting')
                
                $('.aw-actions', items).empty()
                $('.aw-result', items).empty()

                //aw.RE.next()
            })

            $('#aw-mark').on('click', function (e) {
                e.preventDefault()
                var id_from = $('#id_image-from').val()
                var id_to = $('#id_image-to').val()

                aw.RE.storage.set('ps-aw-id_image-from', id_from)
                aw.RE.storage.set('ps-aw-id_image-to', id_to)

                var first = $('#aw-regenerate-list table tbody tr[data-id_image="'+id_from+'"]')
                if (first.size() == 0) {
                    first = $('#aw-regenerate-list table tbody tr').first()
                }

                var last = $('#aw-regenerate-list table tbody tr[data-id_image="'+id_to+'"]')
                if (last.size() == 0) {
                    last = $('#aw-regenerate-list table tbody tr').last()
                }

                $('#aw-regenerate-list table tbody tr')
                    .removeClass('aw-waiting')
                    .slice(first.index(), last.index()+1)
                    .addClass('aw-waiting')
            })
        }
    })(window.aw || (window.aw = {}));

    $(document).ready(function () {
        $.ajaxSetup({ cache: false });
        window.aw.RE.init()
    })

})(jQuery, window, document);
{/literal}
</script>
{/block}