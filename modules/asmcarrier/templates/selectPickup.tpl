﻿    <script type="text/javascript">        function abrirIframe() {            var s = 'https://www.asmred.com/Extranet/public/asmpshop/seleccionador.aspx?css=asm&cp=' + document.getElementById("codigoPostal").value + '&q=i&p=-1';            document.getElementById('iframe1').src = s;            var iframe = document.getElementById("iframe1");            iframe.style.display = "block";        }        function esconderIframe() {            var iframe = document.getElementById("iframe1");            iframe.style.display = "none";        }        window.addEventListener("message", function(event) {			var nombre = '';            var data = event.data;            var res = data.split("|");            var codigo = res[0];            var nombre =res[1];            var direccion = res[2];            var cp = res[3];            var localidad =res[4];            var codigoForm = document.getElementById("parcel_codigo");            codigoForm.value = codigo;            var direccionForm = document.getElementById("parcel_direccion");            direccionForm.value = direccion;            var cpForm = document.getElementById("parcel_cp");            cpForm.value = cp;            var nombreForm = document.getElementById("parcel_nombre");            nombreForm.value = nombre;            var localidadForm = document.getElementById("parcel_localidad");            localidadForm.value = localidad;									var msg = '<div class="panel panel-info"><div class="panel-heading"><h3 class="panel-title">'+"{l s='Punto de recogida seleccionado' mod='asmcarrier'}"+'</h3></div>'					+ '<div class="panel-body">'+nombre+'</div></div>';			if(nombre != '')					document.getElementById("parcelinfo").innerHTML  = msg;			            esconderIframe();        });</script>	<input id="codigoPostal" type="hidden" name="codigoPostal" value="{$postalcode}" />	<br><br>    <input class="button btn btn-default" type="button" value="{l s='Elegir un punto de recogida' mod='asmcarrier'}" onclick="abrirIframe()" />    <input id="Red" type="hidden" name="Red" value="-1" />	<input id="parcel_codigo" type="hidden" name="parcel[codigo]" value="" onfocus="esconderIframe()"/>	<input id="parcel_nombre" type="hidden" name="parcel[nombre]" value="" onfocus="esconderIframe()"/>	<input id="parcel_direccion" type="hidden" name="parcel[direccion]" value=""/>	<input id="parcel_cp" type="hidden" name="parcel[cp]" value=""/>	<input id="parcel_localidad" type="hidden" name="parcel[localidad]" value=""/>	<br><br>    <div id="parcelinfo"></div>    <iframe id="iframe1" src="" hidden="hidden" style="width:100%; min-height: 600px;"></iframe>