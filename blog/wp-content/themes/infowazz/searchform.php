<form method="get" id="searchform" action="<?php echo esc_url(home_url('/')); ?>/">
	<label for="s3">Buscador</label>
	<input type="text" value="¿Qué estas buscando?" onfocus="if(this.value=='¿Qué estas buscando?')this.value='';" onblur="if(this.value=='')this.value='¿Qué estas buscando?';" name="s" id="s3" class="search-input" />
</form>
