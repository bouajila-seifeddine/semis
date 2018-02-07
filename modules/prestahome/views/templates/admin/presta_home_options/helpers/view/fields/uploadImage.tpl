<div class="form-group">
	<input type="file" id="upload_btn_{$field.id|escape:'UTF-8'}" class="file_upload" name="images[{$field.id|escape:'UTF-8'}][]" />
	<input name="fields[{$field.id|escape:'UTF-8'}]" value="{$options[$field.id]|escape:'UTF-8'}" id="{$field.id|escape:'UTF-8'}_input" class="hide" />
</div>
<div class="form-group">
	<div id="upload_progress_{$field.id|escape:'UTF-8'}"></div>
	<div id="upload_preview_{$field.id|escape:'UTF-8'}"></div>
	<div id="upload_errormsg_{$field.id|escape:'UTF-8'}" class="alert alert-warning hidden"></div>
</div>
{if $options[$field.id] != ''}
<div class="form-group" id="preview_box_{$field.id|escape:'UTF-8'}">
	<img class="img-responsive" src="{$module_dir|escape:'UTF-8'}views/img/upload/{$options[$field.id]|escape:'UTF-8'}" />

	<a class="btn btn-default delete_uploaded_image" data-field="{$field.id|escape:'UTF-8'}" href="#">{l s='Delete image' mod='prestahome'}</a>

</div>
{/if}

<script>
$(function() {
  
  	var btn = $('#upload_btn_{$field.id|escape:'UTF-8'}'),
      	wrap = $('#upload_progress_{$field.id|escape:'UTF-8'}'),
      	picBox = $('#upload_preview_{$field.id|escape:'UTF-8'}'),
      	errBox = $('#upload_errormsg_{$field.id|escape:'UTF-8'}'),
      	previewBox = $('#preview_box_{$field.id|escape:'UTF-8'}'),
      	thisInput = $('#{$field.id|escape:'UTF-8'}_input');
	
  	var uploader = new ss.SimpleUpload({
        button: btn,
        //url: currentIndex + '&token=' + token + '&ajax=1',
        name: '{$field.id|escape:'UTF-8'}',
        data: {
        	"nameOfTheFile" : "{$field.id|escape:'UTF-8'}",
        	"action":"uploadImage",
			"token" : token,
			"tab" : "AdminPrestaHomeOptions",
			"ajax" : 1 
        },
        multiple: false,
        maxUploads: 1,
        maxSize: 500,
        queue: false,
        allowedExtensions: ['jpg', 'jpeg', 'png', 'gif'],
        accept: 'image/*',
        debug: true,
        hoverClass: 'btn-hover',
        focusClass: 'active',
        disabledClass: 'disabled',
        responseType: 'json',
        onSubmit: function(filename, ext) {            
        	errBox.addClass('hidden');
        },		
		onSizeError: function() {
			errBox.html('<p>Files may not exceed 500K.</p>');
			errBox.removeClass('hidden');
		},
		onExtError: function() {
			errBox.html('Invalid file type. Please select a PNG, JPG, GIF image.');
		},
		onComplete: function(file, response) {            
			if (!response) {
				errBox.html('Unable to upload file');
			}     
			if (response.success === true) {
				thisInput.attr('value', response.file);
				previewBox.find('.img-responsive').remove();
				picBox.html('<img class="img-responsive" src="{$module_dir|escape:'UTF-8'}views/img/upload/' + response.file + '">');
				showSuccessMessage(response.confirmations);
			} else {
				if (response.msg) {
					errBox.html(response.msg);
				} else {
					errBox.html('Unable to upload file');
				}
			}            
		}
	});
});
</script>
