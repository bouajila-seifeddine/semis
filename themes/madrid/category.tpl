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
{include file="$tpl_dir./errors.tpl"}
{if isset($category)}
    {if $category->id AND $category->active}
        {if $scenes || $category->description || $category->id_image}
            <div class="content_scene_cat">
                {if $theme_options['show_category_image'] == '1'}
                    {if $scenes}
                        <div class="content_scene">
                            <!-- Scenes -->
                            {include file="$tpl_dir./scenes.tpl" scenes=$scenes}
                            {if $category->description}
                                <div class="cat_desc rte">
                                    {if Tools::strlen($category->description) > 350}
                                        <div id="category_description_short">{$description_short}</div>
                                        <div id="category_description_full" class="unvisible">{$category->description}</div>
                                        <a href="{$link->getCategoryLink($category->id_category, $category->link_rewrite)|escape:'html':'UTF-8'}" class="lnk_more">{l s='More'}</a>
                                    {else}
                                        <div>{$category->description}</div>
                                    {/if}
                                </div>
                            {/if}
                        </div>
                    {else}
                        <!-- Category image -->
                        <div class="content_scene_cat_bg"{if $category->id_image} style="background:url({$link->getCatImageLink($category->link_rewrite, $category->id_image, 'category_default')|escape:'html':'UTF-8'}) right center no-repeat; background-size:cover; min-height:{$categorySize.height}px;"{/if}>
                            {if $category->description}
                                <div class="cat_desc">
                                    {if $theme_options['show_category_title'] == '1'}
                                        <h1 class="main-color">
                                            {strip}
                                                {$category->name|escape:'html':'UTF-8'}
                                                {if isset($categoryNameComplement)}
                                                    {$categoryNameComplement|escape:'html':'UTF-8'}
                                                {/if}
                                            {/strip}
                                        </h1>
                                    {/if} 
                                    {*{if $theme_options['show_category_description'] == '1'}
                                        <div class="txt col-md-7 col-sm-11 col-xs-12">
                                            {if Tools::strlen($category->description) > 350}
                                                <div id="category_description_short" class="rte">{$description_short}</div>
                                                <div id="category_description_full" class="unvisible rte">{$category->description}</div>
                                                <a href="{$link->getCategoryLink($category->id_category, $category->link_rewrite)|escape:'html':'UTF-8'}" class="lnk_more">{l s='More'}</a>
                                            {else}
                                                <div class="rte">{$category->description}</div>
                                            {/if}
                                        </div>
                                    {/if}*}
                                </div>
                            {/if}
                        </div>
                    {/if}
                {/if}
            </div>
        {/if}

        {if isset($category->description)}
              <br/>

                      <div class="panel panel-default" role="tablist">
                                    <div class="panel-heading" role="tab" id="headingOne">
                                        
                                              <a data-toggle="collapse" data-parent="#accordion2" href="#collapseOne2" aria-expanded="true" aria-controls="collapseOne2" class="link-collapse">
                                                       <h3 class="panel-title">VER DESCRIPCIÓN DE {$category->name|escape:'html':'UTF-8'} </h3>
                                               </a>
                                          
                                   </div><!-- .panel-heading -->
                                    <div id="collapseOne2" class="panel-collapse collapse in primera-accion" role="tabpanel" aria-labelledby="headingOne" aria-expanded="true" style="">
                                         <div class="panel-body">
                     <div class="rte">{$category->description}</div>
                                          </div><div class="separador"></div>
           
                                       </div> </div>
        {/if}
        {if isset($subcategories) && $theme_options['show_subcategories'] == '1'}
            {if (isset($display_subcategories) && $display_subcategories eq 1) || !isset($display_subcategories) }
                <!-- Subcategories --> 
              
                                
                               
                              
                                
                
                <div id="subcategories">
                    <h4 class="main-color-txt row">{l s='Subcategories'}</h4>

                    <ul class="nolist row">
                        {foreach from=$subcategories item=subcategory}
                            <li class="col-md-3 col-sm-6 col-xs-6">
                                <div class="subcategory-image">
                                    <a href="{$link->getCategoryLink($subcategory.id_category, $subcategory.link_rewrite)|escape:'html':'UTF-8'}" title="{$subcategory.name|escape:'html':'UTF-8'}" class="img">
                                        {if $subcategory.id_image}
                                            <img class="replace-2x img-responsive lazy" data-src="{$link->getCatImageLink($subcategory.link_rewrite, $subcategory.id_image, 'medium_default')|escape:'html':'UTF-8'}" alt="{$subcategory.name|escape:'html':'UTF-8'}" width="{$mediumSize.width}" height="{$mediumSize.height}" />
                                        {else}
                                            <img class="replace-2x img-responsive lazy" data-src="{$img_cat_dir}{$lang_iso}-default-medium_default.jpg" alt="{$subcategory.name|escape:'html':'UTF-8'}" width="{$mediumSize.width}" height="{$mediumSize.height}" />
                                        {/if}
                                    </a>
                                </div>
                                {if $theme_options['show_subcategories_title'] == '1'}
                                    <div class="subcategory-name">

                                        <h5 class="main-color"><a href="{$link->getCategoryLink($subcategory.id_category, $subcategory.link_rewrite)|escape:'html':'UTF-8'}">{$subcategory.name|truncate:25:'...'|escape:'html':'UTF-8'}</a></h5>
                                        <a href="{$link->getCategoryLink($subcategory.id_category, $subcategory.link_rewrite)|escape:'html':'UTF-8'}" aria-label="{$subcategory.name}" class="link-category"></a>
                                    </div>
                                {/if}
                            </li>
                        {/foreach}
                    </ul>
                </div>
            {/if}
        {/if}
        {if $products}
            <div class="content_sortPagiBar clearBoth">
                <div class="sortPagiBar">
                    {include file="./product-sort.tpl"}
                    {include file="./nbr-product-page.tpl"}
                    {include file="./product-compare.tpl"}
 
                </div>
            </div>
            {include file="./product-list.tpl" products=$products}
            <div class="bottom-pagination-content clearBoth">
                {include file="./pagination.tpl" paginationId='bottom'}
            </div>
        {/if}
        {if $category->have_opinions}
            {assign var="link_opiniones" value="{$category->link_rewrite}"}            
            {assign var="link_opiniones" value="{$link_opiniones|replace:'-feminizadas':''}"}
            {assign var="link_opiniones" value="{$link_opiniones|replace:'-regulares':''}"}
            {assign var="link_opiniones" value="{$link_opiniones|replace:'-autoflorecientes':''}"}







            <a href="https://www.semillaslowcost.com/opiniones/{$link_opiniones}/" class="button-opinion btn-primary"><span>Opiniones</span></a>
        {/if}
        {if $theme_options['show_category_description'] == '1'}
            <div class="txt col-md-12 col-sm-12 col-xs-12">
                {*{if Tools::strlen($category->description) > 3000}
                <div id="category_description_short" class="rte">{$description_short}</div>
                <div id="category_description_full" class="unvisible rte">{$category->description}</div>
                <a href="{$link->getCategoryLink($category->id_category, $category->link_rewrite)|escape:'html':'UTF-8'}" class="lnk_more">{l s='More'}</a>
                {else}*}
                 
                {*{/if}*}
            </div>
        {/if}
    {elseif $category->id}
        <p class="alert alert-warning">{l s='This category is currently unavailable.'}</p>
    {/if}
{/if}
