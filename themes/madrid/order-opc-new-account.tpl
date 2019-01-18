<div class="clearfix clear-mobile"></div>

<div id="opc_new_account" class="opc-main-block">
	<div class=" col-md-5 row">
	<h3 style="text-transform: 	uppercase; margin-top: 0px;">1. INTRODUCE TUS DATOS</h3>
	<div id="opc_new_account-overlay" class="opc-overlay" style="display: none;"></div>

	<form action="{$link->getPageLink('authentication', true, NULL, "back=order-opc")|escape:'html':'UTF-8'}" method="post" id="login_form" class="std box">
		
		<fieldset>
			<a href="" id="openNewFormBlock" class="button-peque button-active"  style=" margin-bottom:10px;">{l s='Nuevo Cliente'}</a>
			<a href="" id="openLoginFormBlock" class="button-peque">{l s='Iniciar Sesión'}</a>
			
			
			<div id="login_form_content" style="display:none;">

				<p class="form-group"  style="text-align: center; width: 100%; margin-top: 10px;">
					<input type="text" class="form-control validate" id="login_email" name="email" placeholder="{l s='Email address'}" data-validate="isEmail" />
				</p>
				<p class="form-group"  style="text-align: center; width: 100%;">
					<input class="form-control validate" type="password" placeholder="{l s='Password'}" id="login_passwd" name="login_passwd" data-validate="isPasswd" />
				</p>
				
				<a href="{$link->getPageLink('password', true)|escape:'html':'UTF-8'}" class="lost_password">{l s='Forgot your password?'}</a>
				<p class="submit">
					{if isset($back)}<input type="hidden" class="hidden" name="back" value="{$back|escape:'html':'UTF-8'}" />{/if}
					<button type="submit" id="SubmitLogin" name="SubmitLogin" class="button" style="background-color: #7bbd42;"><span>{l s='Sign in'}</span></button>
				</p>
			</div>
		</fieldset>
		<div id="opc_login_errors" class="alert alert-danger" style="display:none;"></div>

	</form>
	<form action="{$link->getPageLink('authentication', true)|escape:'html':'UTF-8'}" method="post" id="new_account_form" class="std" autocomplete="on" autofill="on">
		<fieldset>
			<div class="box">
				<div id="opc_account_choice" class="row">
					<div class="col-xs-12 col-md-6">
						<p class="title_block">{l s='Instant Checkout'}</p>
						<p class="opc-button">
							<button type="submit" class="button btn-primary" id="opc_guestCheckout"><span>{l s='Guest checkout'}</span></button>
						</p>
					</div>
					<div class="col-xs-12 col-md-6">
						<p class="title_block">{l s='Create your account today and enjoy:'}</p>
						<ul class="nolist">
							<li>- {l s='Personalized and secure access'}</li>
							<li>- {l s='A fast and easy check out process'}</li>
							<li>- {l s='Separate billing and shipping addresses'}</li>
						</ul>
						<p class="opc-button">
							<button type="submit" class="button btn-primary" id="opc_createAccount"><span>{l s='Create an account'}</span></button>
						</p>
					</div>
				</div>
				<div id="opc_account_form" class="unvisible">
					<p class="required opc-required">
						<sup>*</sup>{l s='Required field'}				
					</p>
					{$HOOK_CREATE_ACCOUNT_TOP}
				
					<!-- Account -->
					<input type="hidden" id="is_new_customer" name="is_new_customer" value="0" />
					<input type="hidden" id="opc_id_customer" name="opc_id_customer" value="{if isset($guestInformations) && isset($guestInformations.id_customer) && $guestInformations.id_customer}{$guestInformations.id_customer}{else}0{/if}" />
					<input type="hidden" id="opc_id_address_delivery" name="opc_id_address_delivery" value="{if isset($guestInformations) && isset($guestInformations.id_address_delivery) && $guestInformations.id_address_delivery}{$guestInformations.id_address_delivery}{else}0{/if}" />
					<input type="hidden" id="opc_id_address_invoice" name="opc_id_address_invoice" value="{if isset($guestInformations) && isset($guestInformations.id_address_delivery) && $guestInformations.id_address_delivery}{$guestInformations.id_address_delivery}{else}0{/if}" />
					<div class="required text form-group">
						<input type="text" class="text form-control validate" placeholder="{l s='Email'} *" id="email" name="email" data-validate="isEmail" value="{if isset($guestInformations) && isset($guestInformations.email) && $guestInformations.email}{$guestInformations.email}{/if}" />
					</div>
					<div class="required password is_customer_param form-group">
						<input type="password" class="text form-control validate"  placeholder="{l s='Password'} * {l s='(five characters min.)'}" name="passwd" id="passwd" data-validate="isPasswd" />
						
					</div>
					<div class="required clearfix gender-line radio"  style="display: none;">
						<label>{l s='Social title'}</label>
						{foreach from=$genders key=k item=gender}
						<div class="radio-inline">
							<input type="radio" name="id_gender" id="id_gender{$gender->id_gender}" value="{$gender->id_gender}"{if isset($smarty.post.id_gender) && $smarty.post.id_gender == $gender->id_gender || (isset($guestInformations) && $guestInformations.id_gender == $gender->id_gender)} checked="checked"{/if} />
							<label for="id_gender{$gender->id_gender}" class="top">{$gender->name}</label></div>
						{/foreach}
					</div>
					<div class="required form-group">
				
						<input type="text" class="text form-control validate" id="customer_firstname" name="customer_firstname" onblur="$('#firstname').val($(this).val());" placeholder="{l s='First name'} *" data-validate="isName" value="{if isset($guestInformations) && isset($guestInformations.customer_firstname) && $guestInformations.customer_firstname}{$guestInformations.customer_firstname}{/if}" />
					</div>
					<div class="required form-group">
						<input type="text" class="form-control validate" id="customer_lastname" name="customer_lastname" onblur="$('#lastname').val($(this).val());" data-validate="isName" placeholder="{l s='Last name'} *"  value="{if isset($guestInformations) && isset($guestInformations.customer_lastname) && $guestInformations.customer_lastname}{$guestInformations.customer_lastname}{/if}" />
					</div>
					<div class="clearfix"></div>
					<div class="select form-group date-select">
						
						<div class="row">
							<div class="col-md-12">{l s='Date of Birth'}</div>
							<div class="col-md-4   col-xs-4 opc-date-day">
								<select id="days" name="days" class="form-control">
									<option value="">Día</option>
									{foreach from=$days item=day}
									<option value="{$day|escape:'html':'UTF-8'}" {if isset($guestInformations) && isset($guestInformations.sl_day) && ($guestInformations.sl_day == $day)} selected="selected"{/if}>{$day|escape:'html':'UTF-8'}&nbsp;&nbsp;</option>
									{/foreach}
								</select>
								{*
								{l s='January'}
								{l s='February'}
								{l s='March'}
								{l s='April'}
								{l s='May'}
								{l s='June'}
								{l s='July'}
								{l s='August'}
								{l s='September'}
								{l s='October'}
								{l s='November'}
								{l s='December'}
								*}
							</div>
							<div class="col-md-4  col-xs-4  opc-date-month">
								<select id="months" name="months" class="form-control">
									<option value="">Mes</option>
									{foreach from=$months key=k item=month}
									<option value="{$k|escape:'html':'UTF-8'}" {if isset($guestInformations) && isset($guestInformations.sl_month) && ($guestInformations.sl_month == $k)} selected="selected"{/if}>{l s=$month}&nbsp;</option>
									{/foreach}
								</select>
							</div>
							<div class="col-md-4  col-xs-4 opc-date-year">
								<select id="years" name="years" class="form-control">
									<option value="">Año</option>
									{foreach from=$years item=year}
									<option value="{$year|escape:'html':'UTF-8'}" {if isset($guestInformations) && isset($guestInformations.sl_year) && ($guestInformations.sl_year == $year)} selected="selected"{/if}>{$year|escape:'html':'UTF-8'}&nbsp;&nbsp;</option>
									{/foreach}
								</select>
							</div>
						</div>
					</div>


					{$stateExist = false}
					{$postCodeExist = false}
					{$dniExist = false}
					{foreach from=$dlv_all_fields item=field_name}
					{if $field_name eq "company"}
					
					{elseif $field_name eq "vat_number"}
					<div id="vat_number_block" style="display:none;">
						<div class="form-group">
							<label for="vat_number">CIF de la empresa: {if in_array($field_name, $required_fields)} <sup>*</sup>{/if}</label>
							<input type="text" class="text form-control" name="vat_number" id="vat_number" value="{if isset($guestInformations) && isset($guestInformations.vat_number) && $guestInformations.vat_number}{$guestInformations.vat_number}{/if}" />
						</div>
					</div>
					{elseif $field_name eq "dni"}
					{assign var='dniExist' value=true}
					<div class="required dni form-group">
						<label for="dni">{l s='Identification number'} <sup>*</sup></label>
						<input type="text" class="text form-control validate" name="dni" id="dni" data-validate="isDniLite" value="{if isset($guestInformations) && isset($guestInformations.dni) && $guestInformations.dni}{$guestInformations.dni}{/if}" />
						<span class="form_info">{l s='DNI / NIF / NIE'}</span>
					</div>
					{elseif $field_name eq "firstname"}
					<div class="required text  nombre-add">
						<input type="text" class="text form-control validate" id="firstname" placeholder="{l s='First name'} *" name="firstname" data-validate="isName" value="{if isset($guestInformations) && isset($guestInformations.firstname) && $guestInformations.firstname}{$guestInformations.firstname}{/if}" />
					</div>
					{elseif $field_name eq "lastname"}
					<div class="required text apellido-add">
						<input type="text" class="text form-control validate" placeholder="{l s='Last name'} *" id="lastname" name="lastname" data-validate="isName" value="{if isset($guestInformations) && isset($guestInformations.lastname) && $guestInformations.lastname}{$guestInformations.lastname}{/if}" />
					</div>
					{elseif $field_name eq "address1"}
					<div class="required text form-group-addres">
						<input type="text" class="text form-control validate" placeholder="{l s='Address'} *" name="address1" id="address1" data-validate="isAddress" value="{if isset($guestInformations) && isset($guestInformations.address1) && isset($guestInformations) && isset($guestInformations.address1) && $guestInformations.address1}{$guestInformations.address1}{/if}" />
					</div>
					{elseif $field_name eq "address2"}
				
					{elseif $field_name eq "postcode"}
					{$postCodeExist = true}
					<div class="required postcode text form-group">
						<input type="text" class="text form-control validate" placeholder="{l s='Zip/Postal code'} *" name="postcode" id="postcode" data-validate="isPostCode" value="{if isset($guestInformations) && isset($guestInformations.postcode) && $guestInformations.postcode}{$guestInformations.postcode}{/if}" onkeyup="$('#postcode').val($('#postcode').val().toUpperCase());" />
					</div>
					{elseif $field_name eq "city"}
					<div class="required text form-group">
						<input type="text" class="text form-control validate" name="city" placeholder="{l s='City'} *" id="city" data-validate="isCityName" value="{if isset($guestInformations) && isset($guestInformations.city) && $guestInformations.city}{$guestInformations.city}{/if}" />
					</div>
					{elseif $field_name eq "country" || $field_name eq "Country:name"}
					<div class="required select form-group">
						<select name="id_country" id="id_country" class="form-control">
							{foreach from=$countries item=v}
							<option value="{$v.id_country}"{if (isset($guestInformations) && isset($guestInformations.id_country) && $guestInformations.id_country == $v.id_country) || (!isset($guestInformations) && $sl_country == $v.id_country)} selected="selected"{/if}>{$v.name|escape:'html':'UTF-8'}</option>
							{/foreach}
						</select>
					</div>
					{elseif $field_name eq "state" || $field_name eq 'State:name'}
					{$stateExist = true}
					<div class="required id_state form-group" style="display:none;">
						<select name="id_state" id="id_state" class="form-control">
							<option value="">{l s='State'} *</option>
						</select>
					</div>
					{/if}
					{/foreach}

					{if !$postCodeExist}
					<div class="required postcode form-group unvisible">
						<label for="postcode">{l s='Zip/Postal code'} <sup>*</sup></label>
						<input type="text" class="text form-control validate" name="postcode" id="postcode" data-validate="isPostCode" value="{if isset($guestInformations) && isset($guestInformations.postcode) && $guestInformations.postcode}{$guestInformations.postcode}{/if}" onkeyup="$('#postcode').val($('#postcode').val().toUpperCase());" />
					</div>
					{/if}
					{if !$stateExist}
					<div class="required id_state form-group unvisible">
						<select name="id_state" id="id_state" class="form-control">
							<option value="">{l s='State'} *</option>
						</select>
					</div>
					{/if}
					<div class="clearfix"></div>
					{if !$dniExist}
					<div class="required dni form-group">
						<label for="dni">{l s='Identification number'} <sup>*</sup></label>
						<input type="text" class="text form-control validate" name="dni" id="dni" data-validate="isDniLite" value="{if isset($guestInformations) && isset($guestInformations.dni) && $guestInformations.dni}{$guestInformations.dni}{/if}" />
						<span class="form_info">{l s='DNI / NIF / NIE'}</span>
					</div>
					{/if}
					<div class="required form-group is_customer_param">
						
						<textarea class="form-control" name="other" placeholder="{l s='Additional information'}" id="other" cols="26" rows="1" ></textarea>
					</div>
				
					<div class="form-group is_customer_param"    style="width: 48%; float: 	left; ">
						<input type="text" class="text form-control validate" name="phone" placeholder="{l s='Home phone'}" id="phone"  data-validate="isPhoneNumber" value="{if isset($guestInformations) && isset($guestInformations.phone) && $guestInformations.phone}{$guestInformations.phone}{/if}" />
					</div>
										<div class="clearfix"></div>

											
																{if isset($newsletter) && $newsletter}
					<div class="checkbox">
						<input type="checkbox" name="newsletter" id="newsletter" value="1"  autocomplete="off"/>
						<label for="newsletter">
						{l s='Sign up for our newsletter!'}</label>
						{if array_key_exists('newsletter', $field_required)}
							<sup> *</sup>
						{/if}
					</div>
					{/if}
					<div class="clearfix"></div>
					<input type="hidden" name="alias" id="alias" value="{l s='My address'}"/>


					<div id="opc_invoice_address" class="is_customer_param">
						{assign var=stateExist value=false}
						{assign var=postCodeExist value=false}
						{assign var='dniExist' value=false}
						<h3 class="page-heading top-indent"><span>{l s='Invoice address'}</span></h3>
						{foreach from=$inv_all_fields item=field_name}
						{if $field_name eq "company"}
					
						{elseif $field_name eq "vat_number"}
						<div id="vat_number_block_invoice" class="is_customer_param" style="display:none;">
							<div class="form-group">
								<label for="vat_number_invoice">{l s='VAT number'}{if in_array($field_name, $required_fields)} <sup>*</sup>{/if}</label>
								<input type="text" class="form-control" id="vat_number_invoice" name="vat_number_invoice" value="{if isset($guestInformations) && isset($guestInformations.vat_number_invoice) && $guestInformations.vat_number_invoice}{$guestInformations.vat_number_invoice}{/if}" />
							</div>
						</div>
						{elseif $field_name eq "dni"}
						{assign var='dniExist' value=true}
						<div class="required form-group dni_invoice">
							<label for="dni">{l s='Identification number'} <sup>*</sup></label>
							<input type="text" class="text form-control validate" name="dni_invoice" id="dni_invoice" data-validate="isDniLite" value="{if isset($guestInformations) && isset($guestInformations.dni_invoice) && $guestInformations.dni_invoice}{$guestInformations.dni_invoice}{/if}" />
							<span class="form_info">{l s='DNI / NIF / NIE'}</span>
						</div>
						{elseif $field_name eq "firstname"}
						<div class="required form-group">
							<label for="firstname_invoice">{l s='First name'} <sup>*</sup></label>
							<input type="text" class="form-control validate" id="firstname_invoice" name="firstname_invoice" data-validate="isName" value="{if isset($guestInformations) && isset($guestInformations.firstname_invoice) && $guestInformations.firstname_invoice}{$guestInformations.firstname_invoice}{/if}" />
						</div>
						{elseif $field_name eq "lastname"}
						<div class="required form-group">
							<label for="lastname_invoice">{l s='Last name'} <sup>*</sup></label>
							<input type="text" class="form-control validate" id="lastname_invoice" name="lastname_invoice" data-validate="isName" value="{if isset($guestInformations) && isset($guestInformations.lastname_invoice) && $guestInformations.lastname_invoice}{$guestInformations.lastname_invoice}{/if}" />
						</div>
						{elseif $field_name eq "address1"}
						<div class="required form-group">
							<label for="address1_invoice">{l s='Address'} <sup>*</sup></label>
							<input type="text" class="form-control validate" name="address1_invoice" id="address1_invoice" data-validate="isAddress" value="{if isset($guestInformations) && isset($guestInformations.address1_invoice) && isset($guestInformations) && isset($guestInformations.address1_invoice) && $guestInformations.address1_invoice}{$guestInformations.address1_invoice}{/if}" />
						</div>
						{elseif $field_name eq "address2"}
					
						{elseif $field_name eq "postcode"}
						{$postCodeExist = true}
						<div class="required postcode_invoice form-group">
							<label for="postcode_invoice">{l s='Zip/Postal Code'} <sup>*</sup></label>
							<input type="text" class="form-control validate" name="postcode_invoice" id="postcode_invoice" data-validate="isPostCode" value="{if isset($guestInformations) && isset($guestInformations.postcode_invoice) && $guestInformations.postcode_invoice}{$guestInformations.postcode_invoice}{/if}" onkeyup="$('#postcode_invoice').val($('#postcode_invoice').val().toUpperCase());" />
						</div>
						{elseif $field_name eq "city"}
						<div class="required form-group">
							<label for="city_invoice">{l s='City'} <sup>*</sup></label>
							<input type="text" class="form-control validate" name="city_invoice" id="city_invoice" data-validate="isCityName" value="{if isset($guestInformations) && isset($guestInformations.city_invoice) && $guestInformations.city_invoice}{$guestInformations.city_invoice}{/if}" />
						</div>
						{elseif $field_name eq "country" || $field_name eq "Country:name"}
						<div class="required form-group">
							<label for="id_country_invoice">{l s='Country'} <sup>*</sup></label>
							<select name="id_country_invoice" id="id_country_invoice" class="form-control">
								<option value="">-</option>
								{foreach from=$countries item=v}
								<option value="{$v.id_country}"{if (isset($guestInformations) && isset($guestInformations.id_country_invoice) && $guestInformations.id_country_invoice == $v.id_country) || (!isset($guestInformations) && $sl_country == $v.id_country)} selected="selected"{/if}>{$v.name|escape:'html':'UTF-8'}</option>
								{/foreach}
							</select>
						</div>
						{elseif $field_name eq "state" || $field_name eq 'State:name'}
						{$stateExist = true}
						<div class="required id_state_invoice form-group" style="display:none;">
							<label for="id_state_invoice">{l s='State'} <sup>*</sup></label>
							<select name="id_state_invoice" id="id_state_invoice" class="form-control">
								<option value="">-</option>
							</select>
						</div>
						{/if}
						{/foreach}
						{if !$postCodeExist}
						<div class="required postcode_invoice form-group unvisible">
							<label for="postcode_invoice">{l s='Zip/Postal Code'} <sup>*</sup></label>
							<input type="text" class="form-control validate" name="postcode_invoice" id="postcode_invoice" data-validate="isPostCode" value="{if isset($guestInformations) && isset($guestInformations.postcode_invoice) && $guestInformations.postcode_invoice}{$guestInformations.postcode_invoice}{/if}" onkeyup="$('#postcode').val($('#postcode').val().toUpperCase());" />
						</div>
						{/if}
						{if !$stateExist}
						<div class="required id_state_invoice form-group unvisible">
							<label for="id_state_invoice">{l s='State'} <sup>*</sup></label>
							<select name="id_state_invoice" id="id_state_invoice" class="form-control">
								<option value="">-</option>
							</select>
						</div>
						{/if}
						{if !$dniExist}
						<div class="required form-group dni_invoice">
							<label for="dni">{l s='Identification number'} <sup>*</sup></label>
							<input type="text" class="text form-control validate" name="dni_invoice" id="dni_invoice" data-validate="isDniLite" value="{if isset($guestInformations) && isset($guestInformations.dni_invoice) && $guestInformations.dni_invoice}{$guestInformations.dni_invoice}{/if}" />
							<span class="form_info">{l s='DNI / NIF / NIE'}</span>
						</div>
						{/if}
						<div class="form-group is_customer_param">
							<label for="other_invoice" style="margin-left: 	1.5%;">{l s='Additional information'}</label>
							<textarea class="form-control" name="other_invoice" id="other_invoice" cols="26" rows="3"  style="margin-left: 	0.5%;"></textarea>
						</div>
						<div class="clearfix"></div>

						{if isset($one_phone_at_least) && $one_phone_at_least}
							<p class="inline-infos required is_customer_param">{l s='You must register at least one phone number.'}</p>
						{/if}
						<div class="form-group is_customer_param">
							<label for="phone_invoice">{l s='Home phone'}</label>
							<input type="text" class="form-control validate" name="phone_invoice" id="phone_invoice" data-validate="isPhoneNumber" value="{if isset($guestInformations) && isset($guestInformations.phone_invoice) && $guestInformations.phone_invoice}{$guestInformations.phone_invoice}{/if}" />
						</div>
						
						<div class="clearfix"></div>

						<input type="hidden" name="alias_invoice" id="alias_invoice" value="{l s='My Invoice address'}" />
					</div>
					{$HOOK_CREATE_ACCOUNT_FORM}
				<!-- Error return block -->	
				<div id="opc_account_errors" class="alert alert-danger" style="display:none;"></div>

					<div class="submit opc-add-save clearfix">
						
						<button type="submit" name="submitAccount" id="submitAccount" class="button btn-primary"><span>{l s='Proceder al Pago'}</span></button>

					</div>
									<!-- Error return block -->
				<!-- END Error return block -->
					
					<!-- END Error return block -->
					<div style="display: none;" id="opc_account_saved" class="alert alert-success">
						{l s='Account information saved successfully'}
					</div>
				<!-- END Account -->
				</div>
			</div>
		</fieldset>
	</form>
	</div>
</div>
{strip}
{if isset($guestInformations) && isset($guestInformations.id_state) && $guestInformations.id_state}
	{addJsDef idSelectedState=$guestInformations.id_state|intval}
{else}
	{addJsDef idSelectedState=false}
{/if}
{if isset($guestInformations) && isset($guestInformations.id_state_invoice) && $guestInformations.id_state_invoice}
	{addJsDef idSelectedStateInvoice=$guestInformations.id_state_invoice|intval}
{else}
	{addJsDef idSelectedStateInvoice=false}
{/if}
{if isset($guestInformations) && isset($guestInformations.id_country) && $guestInformations.id_country}
	{addJsDef idSelectedCountry=$guestInformations.id_country|intval}
{else}
	{addJsDef idSelectedCountry=false}
{/if}
{if isset($guestInformations) && isset($guestInformations.id_country_invoice) && $guestInformations.id_country_invoice}
	{addJsDef idSelectedCountryInvoice=$guestInformations.id_country_invoice|intval}
{else}
	{addJsDef idSelectedCountryInvoice=false}
{/if}
{if isset($countries)}
	{addJsDef countries=$countries}
{/if}
{if isset($vatnumber_ajax_call) && $vatnumber_ajax_call}
	{addJsDef vatnumber_ajax_call=$vatnumber_ajax_call}
{/if}
{/strip}