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
	{assign var="contador" value=0}
	{foreach from=$homeslider_slides item=slide}
		{if $slide.active}
		<li>
			<a href="{$slide.url|escape:'html':'UTF-8'}" title="{$slide.legend|escape:'html':'UTF-8'}">
				<img class="img-responsive"   alt="{$slide.legend|escape:'htmlall':'UTF-8'}" 
				{if $contador == 0 } 
					src="{$link->getMediaLink("`$smarty.const._MODULE_DIR_`homeslider/images/`$slide.image|escape:'htmlall':'UTF-8'`")}"
				{else}
					data-src="{$link->getMediaLink("`$smarty.const._MODULE_DIR_`homeslider/images/`$slide.image|escape:'htmlall':'UTF-8'`")}" 

				{/if}
				/>
				
				
			</a>
			{if isset($slide.description) && trim($slide.description) != ''}
				<div class="homeslider-description text hidden-xs row">{$slide.description}</div>
			{/if}
		</li>
		{/if}
		{assign var="contador" value="1"}
	{/foreach}
	</ul>
	<a href="#" class="unslider-arrow prev arrow-ph arrow-prev" title="{l s='Previous slide' mod='homeslider'}"><i class="icon icon-angle-left"></i></a>
	<a href="#" class="unslider-arrow next arrow-ph arrow-next" title="{l s='Next slide' mod='homeslider'}"><i class="icon icon-angle-right"></i></a>
</div>
{/if}
<!-- /Module HomeSlider -->
{literal}
<script>
window.addEventListener('load', function(){
    var allimages= document.getElementsByTagName('img');
    for (var i=0; i<allimages.length; i++) {
        if (allimages[i].getAttribute('data-src')) {
            allimages[i].setAttribute('src', allimages[i].getAttribute('data-src'));
        }
    }
}, false);
</script>
{/literal}