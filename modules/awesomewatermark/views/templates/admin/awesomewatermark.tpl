{extends file="./awesomewatermark_layout.tpl"}

{block "awcontent"}
<div class="row">
    <div class="col-lg-12">
        {if isset($content)}
            {$content}
        {/if}
    </div>
</div>

<script type="text/javascript">
{literal}
$(document).ready(function () {
    window.aw.init()
})
{/literal}
</script>
{/block}