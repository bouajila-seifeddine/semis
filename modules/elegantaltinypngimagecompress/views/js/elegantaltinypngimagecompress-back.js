/**
 * @author    Jamoliddin Nasriddinov <jamolsoft@gmail.com>
 * @copyright (c) 2016, Jamoliddin Nasriddinov
 * @license   http://www.gnu.org/licenses/gpl-2.0.html  GNU General Public License, version 2
 */

var elegantaltinypngimagecompressAdminUrl = '';
var elegantaltinypngimagecompressCompressUrl = '';
var elegantaltinypngimagecompressId = 0;
var elegantaltinypngimagecompressTotal = 0;
var elegantaltinypngimagecompressProcessed = 0;
var elegantaltinypngimagecompressNotProcessed = 0;
var elegantaltinypngimagecompressCompressed = 0;
var elegantaltinypngimagecompressFailed = 0;
var elegantaltinypngimagecompressPaused = false;
var elegantaltinypngimagecompressAnalyzing = false;
var elegantalFormGroupClass = 'form-group';

jQuery(document).ready(function () {

    // Identify form group class
    if (jQuery('[type="submit"]').parents('.margin-form').length > 0) {
        elegantalFormGroupClass = 'margin-form';
    }

    // Back button fix on < 1.6.1
    jQuery('.panel-footer button[name="submitOptionsmodule"]').click(function () {
        if (jQuery(this).find('.process-icon-back')) {
            var url = window.location.href.replace(/&event=\w+/gi, '');
            window.location.href = url;
        }
    });

    // List page
    if (jQuery('.elegantal_readme_not_read_yet').length > 0) {
        jQuery('.elegantal_readme_modal').modal('show');
    }
    jQuery('.elegantal_readme_btn').click(function () {
        jQuery('.elegantal_readme_modal').modal('show');
    });

    // Settings Page
    if (jQuery('[name="editSettings"]').length > 0) {
        elegantalEditSettingsFormVisibility(0);
        jQuery('input, select').on('change', function () {
            elegantalEditSettingsFormVisibility(250);
        });
    }

    // Analyze Page
    if (jQuery('.elegantal_analyze_panel').length > 0) {
        elegantaltinypngimagecompressAdminUrl = jQuery('.elegantalJsDef').data('adminurl');
        elegantaltinypngimagecompressCompressUrl = jQuery('.elegantalJsDef').data('compressurl');

        // Prevent accidental page reload
        window.onbeforeunload = function () {
            if (elegantaltinypngimagecompressAnalyzing) {
                return jQuery('.elegantal_analyze_panel').data('reloadmsg');
            }
        };

        // Start analyzing with the first request
        elegantalAnalyze(1);
    }

    // Compress Page
    if (jQuery('.elegantal_compress_panel').length > 0) {
        elegantaltinypngimagecompressAdminUrl = jQuery('.elegantalJsDef').data('adminurl');
        elegantaltinypngimagecompressId = parseInt(jQuery('.elegantalJsDef').data('modelid'));
        elegantaltinypngimagecompressTotal = parseInt(jQuery('.elegantalJsDef').data('total'));
        elegantaltinypngimagecompressNotProcessed = parseInt(jQuery('.elegantalJsDef').data('notprocessed'));
        elegantaltinypngimagecompressProcessed = elegantaltinypngimagecompressTotal - elegantaltinypngimagecompressNotProcessed;
        elegantaltinypngimagecompressCompressed = parseInt(jQuery('.elegantalJsDef').data('compressed'));
        elegantaltinypngimagecompressFailed = parseInt(jQuery('.elegantalJsDef').data('failed'));

        jQuery('.elegantal_compress_btn').click(function () {
            jQuery('.elegantal_hide_on_compress').hide();
            jQuery('.elegantal_show_on_compress').show();
            elegantalCompress();
        });
        jQuery('.elegantal_pause_btn').click(function () {
            elegantaltinypngimagecompressPaused = true;
            jQuery('.elegantal_hide_on_pause').hide();
            jQuery('.elegantal_show_on_pause').show();
        });
        jQuery('.elegantal_resume_btn').click(function () {
            elegantaltinypngimagecompressPaused = false;
            jQuery('.elegantal_hide_on_resume').hide();
            jQuery('.elegantal_show_on_resume').show();
            elegantalCompress();
        });
    }
});

/**
 * Function to analyze images in portions
 * @param {int} currentRequest
 */
function elegantalAnalyze(currentRequest) {
    elegantaltinypngimagecompressAnalyzing = true;
    var panel = jQuery('.elegantal_analyze_panel');
    var progress = panel.find('.elegantal_analyze_progress_bar');
    var id = panel.data('id');

    var offset = panel.data('offset');
    var limit = panel.data('limit');
    var totalRequests = panel.data('requests');

    var fakeProgressCounter = 5;
    if (totalRequests == 1) {
        var progressInt = setInterval(function () {
            progress.css({width: fakeProgressCounter + '%'});
            progress.text(Math.round(fakeProgressCounter) + '%');
            fakeProgressCounter += Math.random();
            if (fakeProgressCounter > 99) {
                clearInterval(progressInt);
            }
        }, 300);
    }

    jQuery.ajax({
        url: elegantaltinypngimagecompressAdminUrl,
        type: 'POST',
        dataType: 'json',
        data: {
            event: 'analyze',
            id_elegantaltinypngimagecompress: id,
            offset: offset,
            limit: limit
        },
        success: function (result) {
            if (result.success) {
                var completed = (currentRequest * 100) / totalRequests;
                progress.css({width: completed + '%'});
                if (completed < 1) {
                    completed = completed.toFixed(2);
                } else {
                    completed = Math.round(completed);
                }
                progress.text(completed + '%');

                if (currentRequest < totalRequests) {
                    panel.data('offset', (offset + limit));
                    elegantalAnalyze(currentRequest + 1);
                } else {
                    fakeProgressCounter = 100;
                    elegantaltinypngimagecompressAnalyzing = false;
                    setTimeout(function () {
                        window.location.href = elegantaltinypngimagecompressCompressUrl;
                    }, 1000);
                }
            } else {
                fakeProgressCounter = 100;
                elegantaltinypngimagecompressAnalyzing = false;
                jQuery('.elegantal_analyze_error_txt').html(result.message);
                jQuery('.elegantal_analyze_error').fadeIn();
                jQuery('html, body').animate({scrollTop: 0});
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            if (errorThrown) {
                fakeProgressCounter = 100;
                elegantaltinypngimagecompressAnalyzing = false;
                jQuery('.elegantal_analyze_error_txt').text(errorThrown);
                jQuery('.elegantal_analyze_error').fadeIn();
                jQuery('html, body').animate({scrollTop: 0});
            } else {
                var completed = (currentRequest * 100) / totalRequests;
                progress.css({width: completed + '%'});
                if (completed < 1) {
                    completed = completed.toFixed(2);
                } else {
                    completed = Math.round(completed);
                }
                progress.text(completed + '%');

                if (currentRequest < totalRequests) {
                    panel.data('offset', (offset + limit));
                    elegantalAnalyze(currentRequest + 1);
                } else {
                    fakeProgressCounter = 100;
                    elegantaltinypngimagecompressAnalyzing = false;
                    setTimeout(function () {
                        window.location.href = elegantaltinypngimagecompressCompressUrl;
                    }, 1000);
                }
            }
        }
    });
}

/**
 * Function to compress images one by one
 */
function elegantalCompress() {
    var progress = jQuery('.elegantal_compress_progress_bar');

    jQuery.ajax({
        url: elegantaltinypngimagecompressAdminUrl,
        type: 'POST',
        dataType: 'json',
        data: {
            id_elegantaltinypngimagecompress: elegantaltinypngimagecompressId,
            event: 'tinify'
        },
        success: function (result) {
            if (result.message) {
                alert(result.message);
            }
            if (result.next == 1) {
                elegantaltinypngimagecompressProcessed++;
                var i = (elegantaltinypngimagecompressProcessed * 100) / elegantaltinypngimagecompressTotal;
                progress.css({width: i + '%'});
                if (i < 1) {
                    i = i.toFixed(2);
                } else {
                    i = Math.round(i);
                }
                progress.text(i + '%');

                jQuery('.elegantal_images_not_compressed').text(elegantaltinypngimagecompressTotal - elegantaltinypngimagecompressProcessed);
                if (result.success) {
                    elegantaltinypngimagecompressCompressed++;
                    jQuery('.elegantal_images_compressed').text(elegantaltinypngimagecompressCompressed);
                } else {
                    elegantaltinypngimagecompressFailed++;
                    jQuery('.elegantal_images_failed').text(elegantaltinypngimagecompressFailed);
                }
                jQuery('.elegantal_images_size_after').text(result.imagesSizeAfter);
                jQuery('.elegantal_images_size_saved').text(result.sizeSaved);

                if (!elegantaltinypngimagecompressPaused) {
                    elegantalCompress();
                }
            } else {
                if (result.redirect) {
                    window.location.href = result.redirect;
                } else {
                    jQuery('.elegantal_hide_on_complete').hide();
                    jQuery('.elegantal_show_on_complete').show();

                    if (elegantaltinypngimagecompressFailed > 0) {
                        jQuery('.elegantal_readme_modal').modal('show');
                    }
                }
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            if (!elegantaltinypngimagecompressPaused) {
                jQuery('.elegantal_pause_btn').click();
                alert('There was a problem processing your request. Please try again. ' + errorThrown);
            }
        }
    });
}

function elegantalEditSettingsFormVisibility(speed) {
    if (jQuery('[name="compress_generated_images"]:checked').val() == 1) {
        jQuery('[name="image_formats_to_compress[]"]').parents('.' + elegantalFormGroupClass).fadeIn(speed);
        if (elegantalFormGroupClass == 'margin-form') {
            jQuery('[name="image_formats_to_compress[]"]').parents('.' + elegantalFormGroupClass).prev('label').fadeIn(speed);
        }
    } else {
        jQuery('[name="image_formats_to_compress[]"]').parents('.' + elegantalFormGroupClass).hide();
        if (elegantalFormGroupClass == 'margin-form') {
            jQuery('[name="image_formats_to_compress[]"]').parents('.' + elegantalFormGroupClass).prev('label').hide();
        }
    }
}