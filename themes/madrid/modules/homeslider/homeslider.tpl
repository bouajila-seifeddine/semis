{*
* 2007-2015 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2015 PrestaShop SA
*  @version  Release: $Revision$
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

<!-- Module HomeSlider -->
{if isset($homeslider_slides)}
<div class="slider">
	<ul>
	{foreach from=$homeslider_slides item=slide}
		{if $slide.active}
		<li>
			
				<img class="img-responsive" src="{$link->getMediaLink("`$smarty.const._MODULE_DIR_`homeslider/images/`$slide.image|escape:'htmlall':'UTF-8'`")}" alt="{$slide.legend|escape:'htmlall':'UTF-8'}" />
			
			{if isset($slide.description) && trim($slide.description) != ''}
				<div class="homeslider-description text hidden-xs row">{$slide.description}</div>
			{/if}
		</li>
		{/if}
	{/foreach}
	</ul>
</div>
{/if}
<!-- /Module HomeSlider -->