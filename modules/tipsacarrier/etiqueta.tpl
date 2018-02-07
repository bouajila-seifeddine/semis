<table style="border: 0px;">
<tr>
	<td style="width:100px;"><img src="{$path_img_logo}" /></td>
	<td><span style="color: #119BBB;font-size: 24px;">Transportista TIPSA</span></td>
</tr>
</table>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>

{if $errores}
	{$errores}
{else}
	<a href="{$download_pdf}" target="_blank" title="Descargar Codigo Barras" ><h3>Download PDF</h3></a>
	<p>&nbsp;</p>
	<p>&nbsp;</p>
{/if}

<p>{$volver}</p>
