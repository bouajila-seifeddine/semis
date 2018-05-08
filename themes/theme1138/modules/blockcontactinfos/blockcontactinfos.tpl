<!-- MODULE Block contact infos -->
<section id="block_contact_infos" class="footer-block col-xs-12 col-sm-3 col-md-4 col-lg-4 col-lg-offset-2">
	<div>
        <h4>{l s='Store Information' mod='blockcontactinfos'}</h4>
        <ul class="toggle-footer">
            {if $blockcontactinfos_company != ''}
            	<li class="address">{$blockcontactinfos_company|escape:'html':'UTF-8'} <br>
		{if $blockcontactinfos_address != ''} {$blockcontactinfos_address|escape:'html':'UTF-8'}{/if}</li>
            {/if}
            {if $blockcontactinfos_phone != ''}
            	<li class="phone">
                    {l s='Call us now:' mod='blockcontactinfos'} 
            		<span><i class="fa fa-phone"></i>{$blockcontactinfos_phone|escape:'html':'UTF-8'}</span>
            	</li>
            {/if}
            {if $blockcontactinfos_email != ''}
            	<li>
            		{l s='Email:' mod='blockcontactinfos'} 
            	<!--	<span>{mailto address=$blockcontactinfos_email|escape:'html':'UTF-8'}</span> -->
			<span>{$blockcontactinfos_email|escape:'html':'UTF-8'}</span>
            	</li>
            {/if}
<br><br>
<img src="/themes/theme1138/img/icon-footer-ssl.jpg" alt="Certificado SSL Semillas Low Cost" title="Certificado SSL Semillas Low Cost" width="100" height="100">
<img src="/themes/theme1138/img/icon-footer-calidad.jpg" alt="Certificado Calidad Garantizada Semillas Low Cost" title="Certificado Calidad Garantizada Semillas Low Cost" width="100" height="100">
<!-- <img src="/themes/theme1138/img/icon-footer-lopd.png" alt="Certificado LOPD Semillas Low Cost" title="Certificado LOPD Semillas Low Cost" width="100" height="100"> -->
        </ul>
    </div>
</section>
<!-- /MODULE Block contact infos -->
