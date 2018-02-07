<p style="text-align:center;"><span>Pagination :</span>
<a href="index.php?tab=AdminTipsa&token={$token}&p=1"><img src="../img/admin/list-prev2.gif" /></a>

{foreach from=$pager.before key=p item=page}
<a href="index.php?tab=AdminTipsa&token={$token}&p={$page}" class="action_module" style="margin-right:5px;">{$page}</a>
{/foreach}
<span style="font-weight:bold; margin-right:5px;">{$pager.actual}</span>
{foreach from=$pager.after key=p item=page}
<a href="index.php?tab=AdminTipsa&token={$token}&p={$page}" class="action_module" style="margin-right:5px;">{$p}</a>
{/foreach}
<a href="index.php?tab=AdminTipsa&token={$token}&p={$pager.next}"><img src="../img/admin/list-next2.gif" /></a>
</p>