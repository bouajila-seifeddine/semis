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
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

{capture name=path}{l s='Sitemap'}{/capture}

<h1>
    {l s='Sitemap'}
</h1>
<div id="sitemap_content" class="row">

</div>
<div id="listpage_content" class="row">
	<div class="col-xs-12 col-sm-12 col-md-6">
		<div class="categTree box">
            <h3 class="page-heading"><span>{l s='Categories'}</span></h3>
            <div class="tree_top">
                <a href="{$base_dir_ssl}" title="{$categoriesTree.name|escape:'html':'UTF-8'}"></a>
            </div>
            <ul class="tree">
            {if isset($categoriesTree.children)}
                {foreach $categoriesTree.children as $child}
                    {if $child@last}
                        {include file="$tpl_dir./category-tree-branch.tpl" node=$child last='true'}
                    {else}
                        {include file="$tpl_dir./category-tree-branch.tpl" node=$child}
                    {/if}
                {/foreach}
            {/if}
            </ul>
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-6">
		<div class="sitemap_block box">
            <h3 class="page-heading"><span>{l s='Pages'}</span></h3>
            <ul>
            	<li>
                    <a href="{$categoriescmsTree.link|escape:'html':'UTF-8'}" title="{$categoriescmsTree.name|escape:'html':'UTF-8'}">
                        {$categoriescmsTree.name|escape:'html':'UTF-8'}
                    </a>
                </li>
                {if isset($categoriescmsTree.children)}
                    {foreach $categoriescmsTree.children as $child}
                        {if (isset($child.children) && $child.children|@count > 0) || $child.cms|@count > 0}
                            {include file="$tpl_dir./category-cms-tree-branch.tpl" node=$child}
                        {/if}
                    {/foreach}
                {/if}
                {foreach from=$categoriescmsTree.cms item=cms name=cmsTree}
                    <li>
                        <a href="{$cms.link|escape:'html':'UTF-8'}" title="{$cms.meta_title|escape:'html':'UTF-8'}">
                            {$cms.meta_title|escape:'html':'UTF-8'}
                        </a>
                    </li>
                {/foreach}
                <li>
                    <a href="{$link->getPageLink('contact', true)|escape:'html':'UTF-8'}" title="{l s='Contact'}">
                        CONTACTO
                    </a>
                </li>
                <li>
                    <a href="https://www.semillaslowcost.com/opiniones/" title="Opiniones de Clientes">
                        OPINIONES DE CLIENTES
                    </a>
                </li>
                  <li>
                    <a href="https://www.semillaslowcost.com/fertilizantes-abonos-plantas-marihuana/ " title="Los Mejores Abonos y Fertilizantes para la Marihuana">
                        MEJORES ABONOS Y FERTILIZANTES
                    </a>
                </li>
                {if $display_store}
                    <li class="last">
                        <a href="{$link->getPageLink('stores')|escape:'html':'UTF-8'}" title="{l s='List of our stores'}">
                            {l s='Our stores'}
                        </a>
                    </li>
                {/if}
            </ul>
        </div>
    </div>
</div>
