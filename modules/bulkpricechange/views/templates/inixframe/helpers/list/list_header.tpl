{extends file="{$frame_local_path}template/helpers/list/list_header.tpl"}
{block name="preTable"}
    <div class="portlet-body">
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
    </div>
    </div>
{/block}