{*
* @author    Jamoliddin Nasriddinov <jamolsoft@gmail.com>
* @copyright (c) 2016, Jamoliddin Nasriddinov
* @license   http://www.gnu.org/licenses/gpl-2.0.html  GNU General Public License, version 2
*}
<div class="elegantalBootstrapWrapper">
    <div class="panel">
        <div class="panel-heading">
            <div class="pull-left">
                <i class="icon-picture-o"></i> {l s='Image Compressor With' mod='elegantaltinypngimagecompress'} <a href="https://tinypng.com" target="_blank"> TinyPNG</a>
            </div>
            <div class="pull-right">
                <a href="#" class="elegantal_readme_btn {if !$is_readme_read}elegantal_readme_not_read_yet{/if}">{l s='Readme' mod='elegantaltinypngimagecompress'}</a>
            </div>
        </div>
        <div class="panel-body">
            {if $cron_last_error}
                <div class="module_error alert alert-danger">
                    {l s='Last CRON execution ended with an error: ' mod='elegantaltinypngimagecompress'} {$cron_last_error|escape:'html':'UTF-8'}
                </div>
                <br>
            {/if}
            <div class="row elegantal_buttons_row">
                <div class="col-xs-12">
                    <a href="{$adminUrl|escape:'html':'UTF-8'}&event=editSettings" class="btn btn-default btn-lg">
                        <i class="icon-cogs"></i> {l s='Edit settings' mod='elegantaltinypngimagecompress'}
                    </a>
                    <a href="{$adminUrl|escape:'html':'UTF-8'}&event=viewCron" class="btn btn-default btn-lg">
                        <i class="icon-time"></i> {l s='CRON Job' mod='elegantaltinypngimagecompress'}
                    </a>
                    <a href="{$adminUrl|escape:'html':'UTF-8'}&event=imagesLog" class="btn btn-default btn-lg">
                        <i class="icon-list"></i> {l s='Images log' mod='elegantaltinypngimagecompress'}
                    </a>
                    <div class="btn-group">
                        <button type="button" class="btn btn-success btn-lg dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="icon-paw"></i> {l s='Compress images' mod='elegantaltinypngimagecompress'} <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu elegantal_images_group">
                            {foreach from=$imageGroups item=imageGroup}
                                <li>
                                    {if $imageGroup == 'custom'}
                                        <a href="{$adminUrl|escape:'html':'UTF-8'}&event=customDir">
                                            <span>{l s='Custom Directory' mod='elegantaltinypngimagecompress'}</span>
                                        </a>
                                    {else}
                                        <a href="{$adminUrl|escape:'html':'UTF-8'}&event=analyze&image_group={$imageGroup|escape:'html':'UTF-8'}">
                                            <span>{$imageGroup|escape:'html':'UTF-8'} {l s='images' mod='elegantaltinypngimagecompress'}</span>
                                            {if $imageGroup == 'other'}
                                                <br>
                                                <small>{l s='Images not mentioned above. It includes cms images, logo, icons, etc.' mod='elegantaltinypngimagecompress'}</small>
                                            {/if}
                                        </a>
                                    {/if}
                                </li>
                            {/foreach}
                        </ul>
                    </div>
                    {if $documentationUrls}
                        <div class="btn-group">
                            <button type="button" class="btn btn-default btn-lg dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="icon-file-text-o"></i> {l s='Documentation' mod='elegantaltinypngimagecompress'} <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu elegantal_images_group" style="left:0; right: auto;">
                                {foreach from=$documentationUrls key=docLang item=documentationUrl}
                                    <li>
                                        <a href="{$documentationUrl|escape:'html':'UTF-8'}" target="_blank">
                                            {if $docLang == 'en'}
                                                {l s='English' mod='elegantaltinypngimagecompress'}
                                            {elseif $docLang == 'fr'}
                                                {l s='French' mod='elegantaltinypngimagecompress'}
                                            {elseif $docLang == 'de'}
                                                {l s='German' mod='elegantaltinypngimagecompress'}
                                            {elseif $docLang == 'it'}
                                                {l s='Italian' mod='elegantaltinypngimagecompress'}
                                            {elseif $docLang == 'pt'}
                                                {l s='Portuguese' mod='elegantaltinypngimagecompress'}
                                            {elseif $docLang == 'es'}
                                                {l s='Spanish' mod='elegantaltinypngimagecompress'}
                                            {elseif $docLang == 'ru'}
                                                {l s='Russian' mod='elegantaltinypngimagecompress'}
                                            {else}
                                                {$docLang|escape:'html':'UTF-8'}
                                            {/if}
                                        </a>
                                    </li>
                                {/foreach}
                            </ul>
                        </div>
                    {/if}
                    <a href="{$rateModuleUrl|escape:'html':'UTF-8'}" target="_blank" class="btn btn-default btn-lg">
                        <i class="icon-star"></i> {l s='Rate module' mod='elegantaltinypngimagecompress'}
                    </a>
                    <a href="{$contactDeveloperUrl|escape:'html':'UTF-8'}" target="_blank" class="btn btn-default btn-lg">
                        <i class="icon-envelope-o"></i> {l s='Contact developer' mod='elegantaltinypngimagecompress'}
                    </a>
                </div>
            </div>
            {if $models}
                <div class="table-responsive">
                    <table class="table table-hover elegantal_history_table">
                        <thead>
                            <tr>
                                <th>
                                    {l s='Date' mod='elegantaltinypngimagecompress'} 
                                    <a href="{$adminUrl|escape:'html':'UTF-8'}&orderBy=created_at&orderType=desc"><i class="icon-caret-down"></i></a>
                                    <a href="{$adminUrl|escape:'html':'UTF-8'}&orderBy=created_at&orderType=asc"><i class="icon-caret-up"></i></a>
                                </th>
                                <th>
                                    {l s='Image group' mod='elegantaltinypngimagecompress'} 
                                    <a href="{$adminUrl|escape:'html':'UTF-8'}&orderBy=image_group&orderType=desc"><i class="icon-caret-down"></i></a>
                                    <a href="{$adminUrl|escape:'html':'UTF-8'}&orderBy=image_group&orderType=asc"><i class="icon-caret-up"></i></a>
                                </th>
                                <th>
                                    {l s='Total Images' mod='elegantaltinypngimagecompress'} 
                                    <a href="{$adminUrl|escape:'html':'UTF-8'}&orderBy=images_count&orderType=desc"><i class="icon-caret-down"></i></a>
                                    <a href="{$adminUrl|escape:'html':'UTF-8'}&orderBy=images_count&orderType=asc"><i class="icon-caret-up"></i></a>
                                </th>
                                <th>
                                    {l s='Compressed' mod='elegantaltinypngimagecompress'} 
                                    <a href="{$adminUrl|escape:'html':'UTF-8'}&orderBy=compressed&orderType=desc"><i class="icon-caret-down"></i></a>
                                    <a href="{$adminUrl|escape:'html':'UTF-8'}&orderBy=compressed&orderType=asc"><i class="icon-caret-up"></i></a>
                                </th>
                                <th>
                                    {l s='Not Started' mod='elegantaltinypngimagecompress'} 
                                    <a href="{$adminUrl|escape:'html':'UTF-8'}&orderBy=not_compressed&orderType=desc"><i class="icon-caret-down"></i></a>
                                    <a href="{$adminUrl|escape:'html':'UTF-8'}&orderBy=not_compressed&orderType=asc"><i class="icon-caret-up"></i></a>
                                </th>
                                <th>
                                    {l s='Failed' mod='elegantaltinypngimagecompress'} 
                                    <a href="{$adminUrl|escape:'html':'UTF-8'}&orderBy=failed&orderType=desc"><i class="icon-caret-down"></i></a>
                                    <a href="{$adminUrl|escape:'html':'UTF-8'}&orderBy=failed&orderType=asc"><i class="icon-caret-up"></i></a>
                                </th>
                                <th>
                                    {l s='Size Before' mod='elegantaltinypngimagecompress'} 
                                    <a href="{$adminUrl|escape:'html':'UTF-8'}&orderBy=images_size_before&orderType=desc"><i class="icon-caret-down"></i></a>
                                    <a href="{$adminUrl|escape:'html':'UTF-8'}&orderBy=images_size_before&orderType=asc"><i class="icon-caret-up"></i></a>
                                </th>
                                <th>
                                    {l s='Size After' mod='elegantaltinypngimagecompress'} 
                                    <a href="{$adminUrl|escape:'html':'UTF-8'}&orderBy=images_size_after&orderType=desc"><i class="icon-caret-down"></i></a>
                                    <a href="{$adminUrl|escape:'html':'UTF-8'}&orderBy=images_size_after&orderType=asc"><i class="icon-caret-up"></i></a>
                                </th>
                                <th>
                                    {l s='Disk Space Saved' mod='elegantaltinypngimagecompress'} 
                                    <a href="{$adminUrl|escape:'html':'UTF-8'}&orderBy=disk_space_saved&orderType=desc"><i class="icon-caret-down"></i></a>
                                    <a href="{$adminUrl|escape:'html':'UTF-8'}&orderBy=disk_space_saved&orderType=asc"><i class="icon-caret-up"></i></a>
                                </th>
                                <th>
                                    {l s='Status' mod='elegantaltinypngimagecompress'} 
                                    <a href="{$adminUrl|escape:'html':'UTF-8'}&orderBy=status&orderType=desc"><i class="icon-caret-down"></i></a>
                                    <a href="{$adminUrl|escape:'html':'UTF-8'}&orderBy=status&orderType=asc"><i class="icon-caret-up"></i></a>
                                </th>
                            </tr>
                        </thead>
                        {if $models|@count > 1}
                            <tfoot>
                                <tr>
                                    <td colspan="2">{l s='TOTALS:' mod='elegantaltinypngimagecompress'}</td>
                                    <td>{$total_images|escape:'html':'UTF-8'}</td>
                                    <td>{$total_compressed|escape:'html':'UTF-8'}</td>
                                    <td>{$total_not_compressed|escape:'html':'UTF-8'}</td>
                                    <td>{$total_failed|escape:'html':'UTF-8'}</td>
                                    <td>{$total_size_before|escape:'html':'UTF-8'}</td>
                                    <td>{$total_size_after|escape:'html':'UTF-8'}</td>
                                    <td>{$total_disk_saved|escape:'html':'UTF-8'}</td>
                                    <td>&nbsp;</td>
                                </tr>
                            </tfoot>
                        {/if}
                        <tbody>
                            {foreach from=$models item=model}
                                <tr>
                                    <td title="Started:   {$model.created_at|escape:'html':'UTF-8'|date_format:'%e %b, %Y %H:%M:%S'} &#xA;Finished: {$model.updated_at|escape:'html':'UTF-8'|date_format:'%e %b, %Y %H:%M:%S'}">
                                        {$model.created_at|escape:'html':'UTF-8'|date_format:'%e %b %Y'}
                                    </td>
                                    <td {if $model.image_group == 'custom'}title="{$model.custom_dir|escape:'html':'UTF-8'}"{/if}>
                                        {$model.image_group|escape:'html':'UTF-8'}
                                    </td>
                                    <td>
                                        {$model.images_count|intval}
                                    </td>
                                    <td>
                                        {$model.compressed|intval}
                                    </td>
                                    <td>
                                        {$model.not_compressed|intval}
                                    </td>
                                    <td>
                                        {$model.failed|intval}
                                    </td>
                                    <td>
                                        {$model.images_size_before|escape:'html':'UTF-8'}
                                    </td>
                                    <td>
                                        {$model.images_size_after|escape:'html':'UTF-8'}
                                    </td>
                                    <td>
                                        {$model.disk_space_saved|escape:'html':'UTF-8'}
                                    </td>
                                    <td>                                       
                                        {if $model.status == $status_completed}
                                            <span class="label label-success">{l s='Completed' mod='elegantaltinypngimagecompress'}</span>
                                        {else}
                                            <div class="btn-group btn-group-xs elegantal_resume_btn_group" role="group">
                                                {if $model.status == $status_analyzing}
                                                    <a href="{$adminUrl|escape:'html':'UTF-8'}&event=analyze&id_elegantaltinypngimagecompress={$model.id_elegantaltinypngimagecompress|intval}" class="btn btn-warning btn-sm">
                                                        <i class="icon-play"></i> {l s='Resume' mod='elegantaltinypngimagecompress'}
                                                    </a>
                                                {elseif $model.status == $status_compressing}
                                                    <a href="{$adminUrl|escape:'html':'UTF-8'}&event=compress&id_elegantaltinypngimagecompress={$model.id_elegantaltinypngimagecompress|intval}" class="btn btn-warning btn-sm">
                                                        <i class="icon-play"></i> {l s='Resume' mod='elegantaltinypngimagecompress'}
                                                    </a>
                                                {/if}
                                            </div>
                                        {/if}
                                    </td>
                                </tr>                    
                            {/foreach}
                        </tbody>
                    </table>
                    {*START PAGINATION*}
                    {if $pages > 1}
                        {assign var="pMax" value=2 * $halfVisibleLinks + 1} {*Number of visible pager links*}
                        {assign var="pStart" value=$currentPage - $halfVisibleLinks} {*Starter link*}
                        {assign var="moveStart" value=$currentPage - $pages + $halfVisibleLinks} {*Numbers that pStart can be moved left to fill right side space*}
                        {if $moveStart > 0}
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
                                            <a href="{$adminUrl|escape:'html':'UTF-8'}&orderBy={$orderBy|escape:'html':'UTF-8'}&orderType={$orderType|escape:'html':'UTF-8'}&page=1" aria-label="Previous">
                                                <span aria-hidden="true">&lt;&lt; {l s='First' mod='elegantaltinypngimagecompress'}</span>
                                            </a>
                                        </li>
                                        {if $pPrev > 1}
                                            <li>
                                                <a href="{$adminUrl|escape:'html':'UTF-8'}&orderBy={$orderBy|escape:'html':'UTF-8'}&orderType={$orderType|escape:'html':'UTF-8'}&page={$pPrev|intval}" aria-label="Previous">
                                                    <span aria-hidden="true">&lt; {l s='Prev' mod='elegantaltinypngimagecompress'}</span>
                                                </a>
                                            </li>
                                        {/if}
                                    {/if}
                                    {for $i=$pStart to $pages max=$pMax}
                                        <li{if $i == $currentPage} class="active" onclick="return false;"{/if}>
                                            <a href="{$adminUrl|escape:'html':'UTF-8'}&orderBy={$orderBy|escape:'html':'UTF-8'}&orderType={$orderType|escape:'html':'UTF-8'}&page={$i|intval}">{$i|intval}</a>
                                        </li>
                                    {/for}
                                    {if $pNext > $currentPage && $pNext <= $pages}
                                        {if $pNext < $pages}
                                            <li>
                                                <a href="{$adminUrl|escape:'html':'UTF-8'}&orderBy={$orderBy|escape:'html':'UTF-8'}&orderType={$orderType|escape:'html':'UTF-8'}&page={$pNext|intval}" aria-label="Next">
                                                    <span aria-hidden="true">{l s='Next' mod='elegantaltinypngimagecompress'} &gt;</span>
                                                </a>
                                            </li>
                                        {/if}
                                        <li>
                                            <a href="{$adminUrl|escape:'html':'UTF-8'}&orderBy={$orderBy|escape:'html':'UTF-8'}&orderType={$orderType|escape:'html':'UTF-8'}&page={$pages|intval}" aria-label="Next">
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
            {else}
                <div style="padding: 20px; color: #999; text-align: center; font-size: 22px;">
                    {l s='You have not compressed images yet' mod='elegantaltinypngimagecompress'}
                </div>
            {/if}
        </div>
        <div class="panel-footer elegantal_list_footer">        
            {if $apiCompressionsCount}
                <div class="row">
                    <div class="col-xs-12">
                        <span class="elegantal_compression_count">
                            {l s='Your compression usage this month' mod='elegantaltinypngimagecompress'}: <strong>{$apiCompressionsCount|intval}</strong>
                        </span>
                    </div>
                </div>
            {/if}
        </div>
    </div>
    {include file='./modal.tpl'}
</div>