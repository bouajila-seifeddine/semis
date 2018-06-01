
</table>

<div class="row">
    <div class="col-lg-8">

            <div class="btn-group bulk-actions">

                    <a href="#" class="btn btn-default" onclick="javascript:checkDelBoxes($(this).closest('form').get(0), '{$list_id}Box[]', true);return false;">
                        <i class="icon-check-sign"></i>&nbsp;{l s='Select all' mod='bulkpricechange'}
                    </a>
                    <a href="#" class="btn btn-default" onclick="javascript:checkDelBoxes($(this).closest('form').get(0), '{$list_id}Box[]', false);return false;">
                        <i class="icon-check-empty"></i>&nbsp;{l s='Unselect all' mod='bulkpricechange'}
                    </a>
                <a href="#" class="btn btn-primary" onclick="sendBulkAction($(this).closest('form').get(0), 'submitBulkcsvproduct');">
                    <i class="icon-download"></i>&nbsp;{l s='Save CSV File' mod='bulkpricechange'}
                </a>
            </div>

    </div>
    {if !$simple_header && $list_total > $pagination[0]}
        <div class="col-lg-4">
            {* Choose number of results per page *}
            <span class="pagination">
			{l s='Display' mod='bulkpricechange'}:
			<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                {$selected_pagination}
                <i class="icon-caret-down"></i>
            </button>
			<ul class="dropdown-menu">
                {foreach $pagination AS $value}
                    <li>
                        <a href="javascript:void(0);" class="pagination-items-page" data-items="{$value|intval}" data-list-id="{$list_id}">{$value}</a>
                    </li>
                {/foreach}
            </ul>
			/ {$list_total} {l s='result(s)' mod='bulkpricechange'}
                <input type="hidden" id="{$list_id}-pagination-items-page" name="{$list_id}_pagination" value="{$selected_pagination|intval}" />
		</span>
            <script type="text/javascript">
                $('.pagination-items-page').on('click',function(e){
                    e.preventDefault();
                    $('#'+$(this).data("list-id")+'-pagination-items-page').val($(this).data("items")).closest("form").submit();
                });
            </script>
            <ul class="pagination pull-right">
                <li {if $page <= 1}class="disabled"{/if}>
                    <a href="javascript:void(0);" class="previous pagination-link" data-page="1" data-list-id="{$list_id}">
                        <i class="icon-double-angle-left"></i>&nbsp;
                    </a>
                </li>
                <li {if $page <= 1}class="disabled"{/if}>
                    <a href="javascript:void(0);" class="previous pagination-link" data-page="{$page - 1}" data-list-id="{$list_id}">
                        <i class="icon-angle-left"></i>&nbsp;
                    </a>
                </li>
                {assign p 0}
                {while $p++ < $total_pages}
                    {if $p < $page-2}
                        <li class="disabled">
                            <a href="javascript:void(0);">&hellip;</a>
                        </li>
                        {assign p $page-3}
                    {elseif $p > $page+2}
                        <li class="disabled">
                            <a href="javascript:void(0);">&hellip;</a>
                        </li>
                        {assign p $total_pages}
                    {else}
                        <li {if $p == $page}class="active"{/if}>
                            <a href="javascript:void(0);" class="pagination-link" data-page="{$p}" data-list-id="{$list_id}">{$p}</a>
                        </li>
                    {/if}
                {/while}
                <li {if $page >= $total_pages}class="disabled"{/if}>
                    <a href="javascript:void(0);" class="next pagination-link" data-page="{$page + 1}" data-list-id="{$list_id}">
                        <i class="icon-angle-right"></i>&nbsp;
                    </a>
                </li>
                <li {if $page >= $total_pages}class="disabled"{/if}>
                    <a href="javascript:void(0);" class="next pagination-link" data-page="{$total_pages}" data-list-id="{$list_id}">
                        <i class="icon-double-angle-right"></i>&nbsp;
                    </a>
                </li>
            </ul>
            <script type="text/javascript">
                $('.pagination-link').on('click',function(e){
                    e.preventDefault();

                    if (!$(this).parent().hasClass('disabled'))
                        $('#submitFilter'+$(this).data("list-id")).val($(this).data("page")).closest("form").submit();
                });
            </script>
        </div>
    {/if}
</div>
{block name="footer"}
    {foreach from=$toolbar_btn item=btn key=k}
        {if $k == 'back'}
            {assign 'back_button' $btn}
            {break}
        {/if}
    {/foreach}
    {if isset($back_button)}
        <div class="panel-footer">
            <a id="desc-{$table}-{if isset($back_button.imgclass)}{$back_button.imgclass}{else}{$k}{/if}" class="btn btn-default" {if isset($back_button.href)}href="{$back_button.href}"{/if} {if isset($back_button.target) && $back_button.target}target="_blank"{/if}{if isset($back_button.js) && $back_button.js}onclick="{$back_button.js}"{/if}>
                <i class="process-icon-back {if isset($back_button.class)}{$back_button.class}{/if}" ></i> <span {if isset($back_button.force_desc) && $back_button.force_desc == true } class="locked" {/if}>{$back_button.desc}</span>
            </a>
        </div>
    {/if}
{/block}
</div>
{if !$simple_header}
    <input type="hidden" name="token" value="{$token}" />
{/if}
</div>
{block name="endForm"}
</form>
{/block}
{block name="after"}{/block}