{if isset($check) && $check > 0}
<div class="alert alert-danger">
	{if $check eq 1}
		<p>{l s='This product not available in your country.' mod='blockproduct'}</p>
	{elseif $check eq 2}
		<p>{l s='This product not available in this IP.' mod='blockproduct'}</p>
	{/if}
</div>
{/if}
<script type="text/javascript">
{if isset($id_product)}
	var id_product = {$id_product|escape:'html':'UTF-8'};
	console.log(id_product);

	$(document).ready(function() {
		$('.ajax_add_to_cart_button[data-id-product ='+id_product+']').attr("disabled", true);
	});
{/if}
</script>