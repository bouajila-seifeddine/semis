$(document).ready(function(){

	//show on page load
	if ($('input[name="SHOW_PRODUCT_DETAIL_PAGE"]:checked').val() == 1)
		$(".default_message").closest('.form-group').show();
	else
		$(".default_message").closest('.form-group').hide();

	//hide and show text according to switch
	$('label[for="SHOW_PRODUCT_DETAIL_PAGE_on"]').on("click", function(){
		$(".default_message").closest('.form-group').show();
	});

	$('label[for="SHOW_PRODUCT_DETAIL_PAGE_off"]').on("click", function(){
		$(".default_message").closest('.form-group').hide();
	});
});