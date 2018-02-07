{if !Module::isInstalled({$field.module})}
	<div class="alert alert-warning">
		{l s='Module [1]%s[/1] is not installed, options for this module will not be displayed.' sprintf=[$field.module] tags=['<b>'] mod='prestahome'}
	</div>
{else}
	<div class="col-lg-3">
		<span class="switch prestashop-switch fixed-width-lg module-switch">
			<input data-module="{$field.module|escape:'UTF-8'}" type="radio" name="{$field.id|escape:'UTF-8'}" id="{$field.id|escape:'UTF-8'}_on" value="1" {if Module::isEnabled($field.module)}checked="checked"{/if} />
			<label for="{$field.id|escape:'UTF-8'}_on" class="radioCheck">
				{if isset($field.label_on)}
					{$field.label_on|escape:'UTF-8'}
				{else}
					{l s='Enabled' mod='prestahome'}
				{/if}
			</label>

			<input data-module="{$field.module|escape:'UTF-8'}" type="radio" name="{$field.id|escape:'UTF-8'}" id="{$field.id|escape:'UTF-8'}_off" value="0" {if !Module::isEnabled($field.module)}checked="checked"{/if} />
			<label for="{$field.id|escape:'UTF-8'}_off" class="radioCheck">
				{if isset($field.label_off)}
					{$field.label_off|escape:'UTF-8'}
				{else}
					{l s='Disabled' mod='prestahome'}
				{/if}
			</label>

			<a class="slide-button btn"></a>
		</span>
	</div>

	{if isset($field.configuration) && $field.configuration eq true}
	<div class="col-lg-2">
		<a class="btn btn-default _blank" href="index.php?controller=AdminModules&configure={$field.module|escape:'UTF-8'}&token={Tools::getAdminTokenLite("AdminModules")|escape:'UTF-8'}">
			<i class="icon-gears"></i>
			{l s='Configure' mod='prestahome'}
		</a>
	</div>
	{/if}
{/if}