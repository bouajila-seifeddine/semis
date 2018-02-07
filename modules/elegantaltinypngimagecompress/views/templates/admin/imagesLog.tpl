{*
* @author    Jamoliddin Nasriddinov <jamolsoft@gmail.com>
* @copyright (c) 2016, Jamoliddin Nasriddinov
* @license   http://www.gnu.org/licenses/gpl-2.0.html  GNU General Public License, version 2
*}
<div class="elegantalBootstrapWrapper">
    <div class="panel">
        <div class="panel-heading">
            <i class="icon-picture-o"></i> {l s='Image Compressor With' mod='elegantaltinypngimagecompress'} <a href="https://tinypng.com" target="_blank"> TinyPNG</a>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-xs-12 col-md-offset-1 col-md-10">
                    <div class="panel">
                        <div class="panel-heading">
                            <i class="icon-file-text-o"></i> {l s='Images Log' mod='elegantaltinypngimagecompress'}
                        </div>
                        <div class="panel-body elegantal_log_panel">
                            <div class="row">
                                <div class="col-xs-12">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>
                                                    {l s='Path' mod='elegantaltinypngimagecompress'} 
                                                    <a href="{$adminUrl|escape:'html':'UTF-8'}&event=imagesLog&orderBy=image_path&orderType=desc"><i class="icon-caret-down"></i></a>
                                                    <a href="{$adminUrl|escape:'html':'UTF-8'}&event=imagesLog&orderBy=image_path&orderType=asc"><i class="icon-caret-up"></i></a>
                                                </th>
                                                <th>
                                                    {l s='Before' mod='elegantaltinypngimagecompress'} 
                                                    <a href="{$adminUrl|escape:'html':'UTF-8'}&event=imagesLog&orderBy=image_size_before&orderType=desc"><i class="icon-caret-down"></i></a>
                                                    <a href="{$adminUrl|escape:'html':'UTF-8'}&event=imagesLog&orderBy=image_size_before&orderType=asc"><i class="icon-caret-up"></i></a>
                                                </th>
                                                <th>
                                                    {l s='After' mod='elegantaltinypngimagecompress'} 
                                                    <a href="{$adminUrl|escape:'html':'UTF-8'}&event=imagesLog&orderBy=image_size_after&orderType=desc"><i class="icon-caret-down"></i></a>
                                                    <a href="{$adminUrl|escape:'html':'UTF-8'}&event=imagesLog&orderBy=image_size_after&orderType=asc"><i class="icon-caret-up"></i></a>
                                                </th>
                                                <th>
                                                    {l s='Status' mod='elegantaltinypngimagecompress'} 
                                                    <a href="{$adminUrl|escape:'html':'UTF-8'}&event=imagesLog&orderBy=status&orderType=desc"><i class="icon-caret-down"></i></a>
                                                    <a href="{$adminUrl|escape:'html':'UTF-8'}&event=imagesLog&orderBy=status&orderType=asc"><i class="icon-caret-up"></i></a>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {if $images}
                                                {foreach from=$images item=image}
                                                    <tr>
                                                        <td style="max-width:300px;overflow:hidden;text-overflow:ellipsis;" title="{$image.image_path|escape:'html':'UTF-8'}">{$image.image_path|escape:'html':'UTF-8'|truncate:100}</td>
                                                        <td>{$image.image_size_before|escape:'html':'UTF-8'}</td>
                                                        <td>{$image.image_size_after|escape:'html':'UTF-8'}</td>
                                                        <td>
                                                            {if $image.status == $status_not_compressed}
                                                                <span class="label label-warning">{l s='Pending' mod='elegantaltinypngimagecompress'}</span>
                                                            {elseif $image.status == $status_compressed}
                                                                <span class="label label-success">{l s='Compressed' mod='elegantaltinypngimagecompress'}</span>
                                                            {elseif $image.status == $status_failed}
                                                                <span class="label label-danger">{l s='Failed' mod='elegantaltinypngimagecompress'}</span>
                                                            {/if}
                                                        </td>
                                                    </tr>
                                                {/foreach}
                                            {/if}
                                        </tbody>
                                    </table>
                                    {*START PAGINATION*}
                                    {if $pages > 1}
                                        {assign var="pMax" value=2 * $halfVisibleLinks + 1} {*Number of visible pager links*}
                                        {assign var="pStart" value=$currentPage - $halfVisibleLinks} {*Starter link*}
                                        {assign var="moveStart" value=$currentPage - $pages + $halfVisibleLinks} {*Numbers that pStart can be moved left to fill right side space*}
                                        {if $moveStart > 0 && $moveStart <= $halfVisibleLinks}
                                            {assign var="pStart" value=$pStart - $moveStart}
                                        {/if}                                    
                                        {if $pStart < 1}
                                            {assign var="pStart" value=1}
                                        {/if}
                                        {assign var="pNext" value=$currentPage + 1} {*Next page*}
                                        {if $pNext > $pages}
                                            {assign var="pNext" value=$pages}
                                        {/if}
                                        {assign var="pPrev" value=$currentPage - 1} {*Previous page*}
                                        {if $pPrev < 1}
                                            {assign var="pPrev" value=1}
                                        {/if}
                                        <div class="text-center">
                                            <br>
                                            <nav>
                                                <ul class="pagination pagination-sm">
                                                    {if $pPrev < $currentPage}
                                                        <li>
                                                            <a href="{$adminUrl|escape:'html':'UTF-8'}&event=imagesLog&orderBy={$orderBy|escape:'html':'UTF-8'}&orderType={$orderType|escape:'html':'UTF-8'}&page=1" aria-label="Previous">
                                                                <span aria-hidden="true">&lt;&lt; {l s='First' mod='elegantaltinypngimagecompress'}</span>
                                                            </a>
                                                        </li>
                                                        {if $pPrev > 1}
                                                            <li>
                                                                <a href="{$adminUrl|escape:'html':'UTF-8'}&event=imagesLog&orderBy={$orderBy|escape:'html':'UTF-8'}&orderType={$orderType|escape:'html':'UTF-8'}&page={$pPrev|intval}" aria-label="Previous">
                                                                    <span aria-hidden="true">&lt; {l s='Prev' mod='elegantaltinypngimagecompress'}</span>
                                                                </a>
                                                            </li>
                                                        {/if}
                                                    {/if}
                                                    {for $i=$pStart to $pages max=$pMax}
                                                        <li{if $i == $currentPage} class="active" onclick="return false;"{/if}>
                                                            <a href="{$adminUrl|escape:'html':'UTF-8'}&event=imagesLog&orderBy={$orderBy|escape:'html':'UTF-8'}&orderType={$orderType|escape:'html':'UTF-8'}&page={$i|intval}">{$i|intval}</a>
                                                        </li>
                                                    {/for}
                                                    {if $pNext > $currentPage && $pNext <= $pages}
                                                        {if $pNext < $pages}
                                                            <li>
                                                                <a href="{$adminUrl|escape:'html':'UTF-8'}&event=imagesLog&orderBy={$orderBy|escape:'html':'UTF-8'}&orderType={$orderType|escape:'html':'UTF-8'}&page={$pNext|intval}" aria-label="Next">
                                                                    <span aria-hidden="true">{l s='Next' mod='elegantaltinypngimagecompress'} &gt;</span>
                                                                </a>
                                                            </li>
                                                        {/if}
                                                        <li>
                                                            <a href="{$adminUrl|escape:'html':'UTF-8'}&event=imagesLog&orderBy={$orderBy|escape:'html':'UTF-8'}&orderType={$orderType|escape:'html':'UTF-8'}&page={$pages|intval}" aria-label="Next">
                                                                <span aria-hidden="true">{l s='Last' mod='elegantaltinypngimagecompress'} &gt;&gt;</span>
                                                            </a>
                                                        </li>
                                                    {/if}
                                                </ul>
                                            </nav>
                                        </div>
                                    {/if}
                                    {*END PAGINATION*}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>                        
        <div class="panel-footer">
            <a href="{$adminUrl|escape:'html':'UTF-8'}" class="btn btn-default">
                <i class="process-icon-back"></i> {l s='Back' mod='elegantaltinypngimagecompress'}
            </a>
        </div>
    </div>
</div>