<div id="product-prices" class="panel product-tab">
	{if isset($id_product)}
		<h3>{l s='Block product' mod='blockproduct'}</h3>
		<div class="form-group row">
			<label class="control-label col-lg-3">
				{l s='Enabled' mod='blockproduct'}
			</label>
			<div class="col-lg-9">
				<span class="switch prestashop-switch fixed-width-lg">
					<input onclick="showdetail(true)" type="radio" name="active_block" id="active_block_on" value="1" {if $active eq 1}checked="checked"{/if}>
					<label for="active_block_on" class="radioCheck">
						{l s='Yes' mod='blockproduct'}
					</label>
					<input onclick="showdetail(false)" type="radio" name="active_block" id="active_block_off" value="0" {if $active eq 0}checked="checked" {/if} />
					<label for="active_block_off" class="radioCheck">
						{l s='No' mod='blockproduct'}
					</label>
					<a class="slide-button btn"></a>
				</span>
			</div>
		</div>
		<div class="form-group" id="country">
			<label class="control-label col-lg-3"> {l s='Select the countries from which your product is not accessible' mod='blockproduct'} </label>
			<div style="height: 200px; overflow-y: auto;" class="well margin-form col-lg-5">
				<table style="border-spacing : 0; border-collapse : collapse;" class="table">
					<thead>
						<tr>
							<th><input type="checkbox" onclick="checkDelBoxes(this.form, 'countries[]', this.checked)" name="checkAll" ></th>
							<th>{l s='Name' mod='blockproduct'}</th>
						</tr>
					</thead>
					<tbody>
						{if isset($country)}
							{foreach $country as $countrydetail}
						
								<tr>
									<td><input type="checkbox" value="{$countrydetail.iso_code|escape:'html':'UTF-8'}" name="countries[]" {if isset($block_country)}{foreach $block_country as $country}{if $country eq $countrydetail.iso_code}checked{/if}{/foreach}{/if} id="" ></td>
									<td>{$countrydetail.name|escape:'html':'UTF-8'}</td>
								</tr>
								
							{/foreach}
						{/if}						
					</tbody>
				</table>
			</div>
		</div>
		<div class="form-group" id="ip">
			<div>
				<label class="control-label col-lg-3">{l s='Blacklist IP addresses' mod='blockproduct'}</label>
				<div class="col-lg-5">
					<textarea rows="10" cols="10" name="blacklist_ip" class="textarea-autosize" number>{if isset($block_ip)}{$block_ip|escape:'html':'UTF-8'}{/if}</textarea>
				</div>
			</div>
		</div>
	{else}
		<div class="alert alert-warning">
			<button class="close" data-dismiss="alert" type="button">×</button>
			{l s='There is 1 warning.' mod='blockproduct'}
			<ul style='display:block;' id='seeMore'>
				<li>{l s='You must save this product before adding ip address for bolcking.'
				mod='blockproduct'}</li>
			</ul>
		</div>
	{/if}
	<div class="panel-footer">
		<a href="{$link->getAdminLink('AdminProducts')|escape:'html':'UTF-8'}{if isset($smarty.request.page) && $smarty.request.page > 1}&amp;submitFilterproduct={$smarty.request.page|intval}{/if}" class="btn btn-default"><i class="process-icon-cancel"></i> {l s='Cancel' mod='blockproduct'}</a>
		<button type="submit" name="submitAddproduct" class="btn btn-default pull-right" disabled="disabled"><i class="process-icon-loading"></i> {l s='Save' mod='blockproduct'}</button>
		<button type="submit" name="submitAddproductAndStay" class="btn btn-default pull-right" disabled="disabled"><i class="process-icon-loading"></i> {l s='Save and stay' mod='blockproduct'}</button>
	</div>
</div>
<script type="text/javascript">
	$(document).ready(function() {
		
		$(".textarea-autosize").autosize();
		
		//show on page load
		if ($('input[name="active_block"]:checked').val() == 1) {
			$("#country").show();
			$("#ip").show();
		} else {
			$("#country").hide();
			$("#ip").hide();
		}
	});
	
	//hide and show according to switch
	function showdetail(show)
	{
		if(show) {
			$("#country").show();
			$("#ip").show();
		} else {
			$("#country").hide();
			$("#ip").hide();
		}
	}
</script>