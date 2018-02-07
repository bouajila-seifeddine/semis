;(function ($, window, document, undefined) {
    "use strict";

    (function(aw) {
    
        aw.Resizer = function () {

            function init(container, wrapper) {
                var self = this;

                this.container = container; // resizer container
                this.wrapper = wrapper; // entire panel
                
                this.wrapper.addClass('aw-panel-collapsed')

                // Older PS
                $('.aw-panel-heading-wrapper', this.wrapper).each(function () {
                    if ($(this).next('.form-wrapper').size() == 0) {
                        var items = $(this).nextAll('.form-group').remove()
                        $('<div>', {'class': 'form-wrapper'})
                            .append(items)
                            .insertAfter(this)
                    }
                })

                var header = $('.aw-panel-heading-wrapper', this.wrapper)
                var toggle = $('<span>', {'class': 'btn btn-primary aw-btn-toggle-panel', 'html': aw_translate('expand_collapse')})

                if ($('[id*=__file-images-thumbnails] img', this.wrapper).size() > 0) {
                    $('<i>', {'class': 'icon icon-picture-o'}).appendTo($('.panel-heading', header))
                }

                header.on('click', function (e) {
                        e.preventDefault()
                        
                        if (self.wrapper.hasClass('aw-panel-expanded')) {
                            self.wrapper
                                .removeClass('aw-panel-expanded')
                                .addClass('aw-panel-collapseed')
                        } else {
                            self.wrapper
                                .removeClass('aw-panel-collapseed')
                                .addClass('aw-panel-expanded')
                        }

                        self.wrapper.siblings('.panel').removeClass('aw-panel-expanded')
                    })
                    .append(toggle)

                this.data = {
                    'identifier': $('.aw-panel-heading-wrapper', this.wrapper).data('identifier'),
                    'width': parseInt($('.aw-panel-heading-wrapper', this.wrapper).data('width')),
                    'height': parseInt($('.aw-panel-heading-wrapper', this.wrapper).data('height'))
                }

                var row = $('<div>', {'class': 'row'}).appendTo(this.container)
                $('<label>', {'class': 'control-label col-lg-3'})
                    .appendTo(row)

                this.toggler = $('<div>', {'class': 'col-lg-9'})
                    .appendTo(row)

                var row = $('<div>', {'class': 'row'}).appendTo(this.container)
                this.workspace = $('<div>', {'class': 'resizer-workspace'})
                    .hide()
                    .appendTo(row)

                var area = $('<div>', {'class': 'resizer-area'})
                    .css({
                        'width': this.data.width,
                        'height': this.data.height
                    })
                    .appendTo(this.workspace)

                $('<div>', {'class': 'resizer-size', 'html': this.data.identifier+'<br>'+this.data.width+'x'+this.data.height})
                    .appendTo(area)

                $('<a>', {'href': '#', 'class': 'btn btn-primary', 'html': aw_translate('reset')})
                    .on('click', function (e) {
                        e.preventDefault()
                        $('input[name*=__coords_x], input[name*=__coords_y], input[name*=__coords_w], input[name*=__coords_h]', self.wrapper).val('')
                        var img = $('.resizer-area img', self.container)
                        self.reset_resizer(img, self)
                    })
                    .appendTo(this.workspace)

                // Padding
                var input_padding = $('input[name*=__padding]', this.wrapper)
                    .on('input', function (e) {
                        $('.resizer-padding', self.wrapper)
                            .css({'box-shadow': 'inset 0 0 0 '+parseInt($(this).val())+'px rgba(255,0,0,.05)'})
                    })

                $('<div>', {'class': 'resizer-padding'})
                    .css({'box-shadow': 'inset 0 0 0 '+parseInt(input_padding.val())+'px rgba(255,0,0,.05)'})
                    .appendTo(area)

                var img = $('[id*=__file-images-thumbnails] img', this.wrapper)
                if(img.size() > 0) {
                    this.set_image(img.attr('src'))
                }

                $('<a>', {'href': '#', 'html': aw_translate('set_size_and_location'), 'class': 'btn btn-primary'}) // TODO, translate
                    .on('click', function (e) {
                        e.preventDefault()
                        self.workspace.toggle()
                    })
                    .appendTo(this.toggler)

                $(':file', this.wrapper).on('change', function (e) {
                    if (typeof FileReader !== "undefined") {
                        if (e.target.files.length > 0) {
                            if (e.target.files[0].type.match(/^image\/.*/)) {
                                var reader = new FileReader();
                                reader.addEventListener("load", function () {
                                    $('input[name*=__coords_x], input[name*=__coords_y], input[name*=__coords_w], input[name*=__coords_h]', self.wrapper).val('')
                                    self.set_image(reader.result)
                                }, false);
                                reader.readAsDataURL(e.target.files[0]);
                            } else {
                                alert(aw_translate('not_image'))
                            }
                        }
                    }
                })
            }

            function set_image(src) {
                var self = this;

                var img = $('<img>', {'src': src})
                $('[id*=__file-images-thumbnails]', this.wrapper).html(img)

                var img = $('<img>', {'src': src})
                img.on('load', function () {
                    $(this)
                        .attr('data-originalWidth', this.width)
                        .attr('data-originalHeight', this.height)
                    self.reset_resizer(this, self)
                })

                var inputs = $('input[name*=__coords_x], input[name*=__coords_y], input[name*=__coords_w], input[name*=__coords_h]', self.wrapper)
                inputs.on('input', function (e) {
                    reset_resizer(img, self)
                })
            }

            function reset_resizer(img, _self) {
                var self = _self || this;

                var ra = $('.resizer-img-wrap', self.container)

                if (ra.size() > 0) {
                    if (ra.is('.ui-draggable')) { ra.draggable("destroy") }
                    if (ra.is('.ui-resizable')) { ra.resizable("destroy") }
                    ra.remove()
                }

                var img_wrap = $('<div>', {'class': 'resizer-img-wrap'}).append(img)
                $('.resizer-area', self.container).append(img_wrap)

                var originalWidth = parseInt($(img).attr('data-originalWidth')),
                    originalHeight = parseInt($(img).attr('data-originalHeight')),
                    scale_x = originalWidth/self.data.width,
                    scale_y = originalHeight/self.data.height,
                    scale = scale_y > scale_x ? scale_y : scale_x,
                    target_width = originalWidth,
                    target_height = originalHeight;
                
                if (originalWidth > self.data.width || originalHeight > self.data.height) {
                    target_width = Math.round(originalWidth * (1/scale)),
                    target_height = Math.round(originalHeight * (1/scale));
                }

                var input_x = $('input[name*=__coords_x]', self.wrapper),
                    input_y = $('input[name*=__coords_y]', self.wrapper),
                    input_w = $('input[name*=__coords_w]', self.wrapper),
                    input_h = $('input[name*=__coords_h]', self.wrapper);

                var x = parseInt(input_x.val()),
                    y = parseInt(input_y.val()),
                    w = parseInt(input_w.val()),
                    h = parseInt(input_h.val());

                var c = {
                    width: isNaN(w) ? target_width : w,
                    height: isNaN(h) ? target_height : h,
                    top: isNaN(y) ? Math.round((self.data.height-target_height)/2) : y,
                    left: isNaN(x) ? Math.round((self.data.width-target_width)/2) : x
                }

                $(img_wrap).css(c)

                input_x.val(isNaN(x) ? '' : c.left)
                input_y.val(isNaN(y) ? '' : c.top)
                input_w.val(c.width)
                input_h.val(c.height)
                
                $(img_wrap).resizable({
                        containment: "parent",
                        aspectRatio: true,
                        resize: function( event, ui ) {
                            $(input_x).val(Math.round(ui.position.left))
                            $(input_y).val(Math.round(ui.position.top))
                            $(input_w).val(Math.round(ui.size.width))
                            $(input_h).val(Math.round(ui.size.height))
                        }
                    })
                    .draggable({
                        containment: "parent",
                        drag: function( event, ui ) {
                            $(input_x).val(Math.round(ui.position.left))
                            $(input_y).val(Math.round(ui.position.top))
                            $(input_w).val(Math.round($(this).width()))
                            $(input_h).val(Math.round($(this).height()))
                        }
                    })
            }

            return {
                init: init,
                set_image: set_image,
                reset_resizer: reset_resizer
            }
        }

        aw.form_validation = function () {
            $('[name=submitawesomewatermark]').on('click', function (e) {
                var errors = 0;
                $('.has-error,.error').removeClass('has-error').removeClass('error')
                $('input[name*=__coords_x], input[name*=__coords_y], input[name*=__coords_w], input[name*=__coords_h], input[name*=__padding]').each(function () {
                    var val = $(this).val()
                    val = val == 'NaN' ? '' : val
                    $(this).val(val)

                    if ( ! val.match(/^\d*$/)) {
                        $(this).closest('.form-group').addClass('has-error')
                        $(this).closest('.panel').addClass('error')
                        errors++;
                    }
                })
                $('input[name*=__background],input[name=trim_color]').each(function () {
                    var val = $(this).val()
                    val = val == '#' ? '' : val
                    $(this).val(val)

                    if ( ! val.match(/^(\#?([0-9a-fA-F]{6}))?$/)) {
                        $(this).closest('.form-group').addClass('has-error')
                        $(this).closest('.panel').addClass('error')
                        errors++;
                    }
                })
                $('input[name=trim_threshold]').each(function () {
                    var val = $(this).val()
                    val = val.replace(/\,/, '.')
                    $(this).val(val)
                    if (parseFloat(val) > 1 || ! val.match(/^(\d+(\.\d+)?)?$/)) {
                        $(this).closest('.form-group').addClass('has-error')
                        $(this).closest('.panel').addClass('error')
                        errors++;
                    }
                })
                if (errors > 0) {
                    e.preventDefault() 
                    alert(aw_translate('correct_errors'))
                }
            })
        }

        aw.init = function () {
            $('[name*="__coords_enabled"]').each(function () {
                var self = this;
                var wrapper = $(this).closest('.panel')
                var form_group = $(this).closest('.form-group')

                // Add resizers
                if ( ! form_group.hasClass('resizer-mounted')) {
                    var c = $('<div>', {'class': 'form-group resizer'}).insertAfter(form_group)
                    form_group.addClass('resizer-mounted')
                    
                    var resizer = new aw.Resizer()
                    resizer.init(c, wrapper)
                }

                // Toggle watermark coordiantion and size
                $(this).on('change', function (e) {
                    if ($(this).val() == 0) {
                        $('[name*="__coords"], .resizer', wrapper)
                            .not('[name*="__coords_enabled"]')
                            .closest('.form-group').slideUp()
                    } else {
                        $('[name*="__coords"], .resizer', wrapper)
                            .not('[name*="__coords_enabled"]')
                            .closest('.form-group').slideDown()
                    }
                })

                if ($(this).is(':checked')) {
                    if ($(this).val() == 0) {
                        $('[name*="__coords"], .resizer', wrapper)
                            .not('[name*="__coords_enabled"]')
                            .closest('.form-group').slideUp()
                    } else {
                        $('<i>', {'class': 'icon icon-arrows-alt'}).appendTo($('.panel-heading', wrapper))
                        $('[name*="__coords"], .resizer', wrapper)
                            .not('[name*="__coords_enabled"]')
                            .closest('.form-group').slideDown()
                    }
                }
            })

            window.aw.form_validation()
        }

    })(window.aw || (window.aw = {}));
})(jQuery, window, document);
