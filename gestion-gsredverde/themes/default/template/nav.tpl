<div class="bootstrap">
	<nav id="{if $employee->bo_menu}nav-sidebar{else}nav-topbar{/if}" role="navigation">
		{if !$tab}
			<div class="mainsubtablist" style="display:none;"></div>
		{/if}
		<ul class="menu">
			<li class="searchtab">
				{include file="search_form.tpl" id="header_search" show_clear_btn=1}
			</li>
			{foreach $tabs as $t}
				{if $t.active}
				<li class="maintab {if $t.current}active{/if} {if $t.sub_tabs|@count}has_submenu{/if}" id="maintab-{$t.class_name}" data-submenu="{$t.id_tab}">
					<a href="{if $t.sub_tabs|@count && isset($t.sub_tabs[0].href)}{$t.sub_tabs[0].href|escape:'html':'UTF-8'}{else}{$t.href|escape:'html':'UTF-8'}{/if}" class="title" >
						<i class="icon-{$t.class_name}"></i>
						<span>{if $t.name eq ''}{$t.class_name|escape:'html':'UTF-8'}{else}{$t.name|escape:'html':'UTF-8'}{/if}</span>
					</a>
					{if $t.sub_tabs|@count}
						<ul class="submenu">
						{foreach from=$t.sub_tabs item=t2}
							{if $t2.active}
							<li id="subtab-{$t2.class_name|escape:'html':'UTF-8'}" {if $t2.current} class="active"{/if}>
								<a href="{$t2.href|escape:'html':'UTF-8'}">
									{if $t2.name eq ''}{$t2.class_name|escape:'html':'UTF-8'}{else}{$t2.name|escape:'html':'UTF-8'}{/if}
								</a>
							</li>
							{/if}
						{/foreach}
						</ul>
					{/if}
				</li>
				{/if}
			{/foreach}
			<script type="text/javascript">
				function Confirm() {
					//Ingresamos un mensaje a mostrar
					var mensaje = confirm("ADVERTENCIA. Este proceso puede durar varios minutos, realizalo únicamente en horas de bajo tráfico.");
					//Detectamos si el usuario acepto el mensaje
					if (mensaje) {
							var win = window.open("https://www.semillaslowcost.com/scriptsphp/actualizarstock.php", '_blank');
  							win.focus();
  							document.getElementById("Comprobacion").submit();

					}
					//Detectamos si el usuario denegó el mensaje 
					else {
					}
				}
			</script>
			<li class="maintab" id="updateStock">
			<form method="POST" action="/scriptsphp/actualizarstock.php" id="Comprobacion">
			<input type="hidden" name="id" value="10" />
			</form>
			<input type="hidden" name="admin" value="comprobado">			
					<a href="" class="title" onclick="Confirm()">
						<i class="icon-AdminPaSeoCenterParent"></i>
						<span>Actualizar Stock</span>
						</a>	
			</form>
			</li>
		</ul>
		<span class="menu-collapse">
			<i class="icon-align-justify icon-rotate-90"></i>
		</span>
		{hook h='displayAdminNavBarBeforeEnd'}
	</nav>
</div>
