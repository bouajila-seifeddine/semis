{if $deviceType == 'computer'}
	<div class="{if $hook == 'footer'}asagiSabit{/if} whatsappBlock"><a href="https://web.whatsapp.com/send?{if $page_name == 'product' && $shareThis == 1}text={$shareMessage|escape:'html':'UTF-8'}&{/if}phone=+{$whatasppno|escape:'html':'UTF-8'}" rel="nofollow"><img src="{$whataspp_module_dir|escape:'html':'UTF-8'}views/img/whataspp_icon.png" alt="Whataspp" width="24" height="24" />¿Alguna pregunta?</a></div>
{else}
	<div class="{if $hook == 'footer'}asagiSabit{/if} whatsappBlock"><a href="whatsapp://send?{if $page_name == 'product' && $shareThis == 1}text={$shareMessage|escape:'html':'UTF-8'}&{/if}phone=+{$whatasppno|escape:'html':'UTF-8'}" rel="nofollow"><img src="{$whataspp_module_dir|escape:'html':'UTF-8'}views/img/whataspp_icon.png" alt="Whataspp" width="24px" height="24px" />¿Alguna pregunta?</a></div>
{/if}