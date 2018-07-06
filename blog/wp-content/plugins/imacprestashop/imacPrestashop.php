<?php
	/*
	Plugin Name: imacPrestashop
	Plugin URI: https://imacreste.com/productos-prestashop-en-wordpress/
	Description: Con este plugin podrás incluir en tus entradas de Wordpress los productos de tu tienda prestashop.
	Author: imacreste
	Version: 1.0.14
	Author URI: https://imacreste.com
	============================================================================================================
	Copyright 2016 imacreste (email: imacreste@gmail.com).
	
	This program is free software; you can redistribute it and/or
	modify it under the terms of the GNU General Public License
	as published by the Free Software Foundation; either version 2
	of the License, or (at your option) any later version.
	
	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.
	
	For full license details see license.txt.
		
	============================================================================================================
	*/
	
	if ( ! defined( 'ABSPATH' ) ) {
		die('Error de acceso.');
	}
	
	//estilo frontend
	add_action( 'wp_enqueue_scripts','cargar_css_js');
	function cargar_css_js(){
		wp_register_style('imacPrestashop_css', plugins_url('/css/style.css',__FILE__ ));
		wp_enqueue_style('imacPrestashop_css');		
	}	
	
	//estilo admin
	add_action( 'admin_enqueue_scripts','cargar_css_js_admin');
	function cargar_css_js_admin(){
		wp_register_style('imacPrestashop_css', plugins_url('/css/admin_style.css',__FILE__ ));
		wp_enqueue_style('imacPrestashop_css');		
	}	
	
	//menú dentro de Ajustes
	add_action('admin_menu','imacPrestashop_menus');
	function imacPrestashop_menus(){		
		add_options_page('Configuraciones Prestashop','imacPrestashop', 'manage_options', 'imacPrestashop', 'imacPrestashop_fc');		
		add_submenu_page(NULL,'Ayuda','Ayuda','manage_options','imacPrestashop_help','imacPrestashop_help_fc');
		add_action('admin_init', 'imacPrestashop_settings');
	}
	
	//matriz para guardar variables de configuración
	function imacPrestashop_settings(){		
		register_setting('imacPrestashop-grupo-config', 'imacPrestashop_options', 'imacPrestashop_sanitize');		
	}
	
	//enlace en menú plugins
	function plugin_add_settings_link( $links ) {
    $settings_link = '<a href="options-general.php?page=imacPrestashop">' . __( 'Settings' ) . '</a>';
    array_push( $links, $settings_link );
  	return $links;
	}
	$plugin = plugin_basename( __FILE__ );
	add_filter( "plugin_action_links_$plugin", 'plugin_add_settings_link' );
	
	//submenu
	function imacPrestashop_help_fc(){	
		global $wpdb;		
	?>
		<div class="wrap">								
			<h2 class="nav-tab-wrapper">
				<a class="nav-tab" href="?page=imacPrestashop"><?php _e("Configuración de Prestashop", "imacPrestashop");?></a>
				<a class="nav-tab nav-tab-active" href="?page=imacPrestashop_help"><?php _e("Ayuda", "imacPrestashop");?></a>
			</h2>
			<div class="wrap imacreste-alerts">				
				<div class="imacreste-container imacreste-container__alert">
					<p class="donativo"><a href="https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=P9DG5TCRGDYAW&lc=ES&item_name=imacreste&currency_code=EUR&bn=PP%2dDonationsBF%3abtn_donate_LG%2egif%3aNonHostedGuest" target="_blank"><?php _e("Con un donativo, contribuiras a mejorar este plugin. Gracias.", "imacPrestashop");?></a></p>					
					<h3><?php _e("Seguridad:", "imacPrestashop");?></h3>
					<div id="imacreste-alerts">
						<p><?php _e("Los datos de configuración de: Servidor, Nombre BD, Usuario, Contraseña y Prefijo, <strong>se guardan encriptados</strong>.","");?></p> 													
						<?php if ($wpdb->base_prefix=="wp_"){?>
							<p><?php _e("<strong>Si el prefijo de tu Wordpress es wp_</strong>, que es la configuración por defecto, <strong>te recomendamos que lo cambies</strong>, no solo por el funcionamiento de este plugin sino porque al ser genérico es inseguro.", "imacPrestashop");?></p>
							<p><?php _e("Si no sabes cómo cambiarlo, contacta con tu webmaster o si prefieres que te asesoremos puedes mandarnos un <a href='mailto:imacreste@gmail.com'>mail</a> o entrando en la web <a href='https://imacreste.com' target='_blank'>https://imacreste.com</a>", "imacPrestashop");?></p>
						<?php }?>
					</div>
					<br>
					<h3><?php _e("Configuración:", "imacPrestashop");?></h3>
					<div id="imacreste-alerts">
						<p><?php _e("Para poder conectarnos con Prestashop necesitamos acceder al servidor y a la base de datos, para lo que se necesita:", "imacPrestashop");?></p>
						<ol>
							<li><?php _e("<strong>Servidor BD:</strong> Si la instalación de Prestashop es la misma que este blog, puedes probar a poner: lcoalhost. Si no puedes probar poniendo la url de la web del Prestashop pero sin el inicio http ni las www. Ejemplo: miweb.com", "imacPrestashop");?></li>
							<li><?php _e("<strong>Nombre BD:</strong> Todo Prestashop necesita una base de datos, y es a la que nos conectaremos para extraer la información de los productos. Si no la conoces pregunta a tu webmaster o hosting.", "imacPrestashop");?></li>
							<li><?php _e("<strong>Usuario BD:</strong> Es el usuario de la base de datos, también es necesario en cualquier instalación Prestashop. Si no la conoces pregunta a tu webmaster o hosting.", "imacPrestashop");?></li>
							<li><?php _e("<strong>Contraseña BD:</strong> Es la contraseña del usuario de la base de datos, también es necesario en cualquier instalación Prestashop. Si no la conoces pregunta a tu webmaster o hosting.", "imacPrestashop");?></li>
							<li><?php _e("<strong>Url de la tienda:</strong> En este caso es necesario que sea con http, www, etc. para evitar errores copia la URL de la web. Es necesario que al final tenga un /. Ejemplo: https://miweb.com/ o Ejemplo: http://www.miweb.com/", "imacPrestashop");?></li>
							<li><?php _e("<strong>Prefijo:</strong> Es el prefijo de la base de datos.", "imacPrestashop");?></li>
							<li><?php _e("<strong>ID categoría con productos:</strong> Es la categoría en la que se buscarán productos. Asegúrate de que el ID exista en tu Prestashop. Puedes verlo desde el menú catálogo -> categorías.", "imacPrestashop");?></li>
							<li><?php _e("<strong>ID idioma:</strong> Es el ID del idioma de Prestashop. Puedes verlo desde localización -> idiomas. Se usa para sacar los productos y URL en el idioma requerido.", "imacPrestashop");?></li>
							<li><?php _e("<strong>Enlaces Nofollow:</strong> Si esta seleccionada la casilla, los enlaces serán nofollow, es un atributo SEO. <a href='https://support.google.com/webmasters/answer/96569?hl=es' target='_blank'>Más Información</a>.", "imacPrestashop");?></li>
							<li><?php _e("<strong>Ocultar Ofertas:</strong> Si esta seleccionada la casilla, No se visualizarán las ofertas, solo el precio de los productos.", "imacPrestashop");?></li>
							<li><?php _e("<strong>URLs de producto:</strong> Las URLS de Prestashop se pueden configurar desde:
										<br>parámetros de la tienda -> Tráfico y SEO -> Ruta a los productos. 
										<br>Esto permite múltiples combinaciones. Nos hemos basado en 2 alternativas
										<br>1º) La que viene por defecto basada en: id-nombre_producto.html (2-blusa.html)
										<br>2º) nombre_producto-id.html (blusa-2.html)
										<br>Si habré un producto puede verlo en la URL.", "imacPrestashop");?></li>
						</ol>
						<p><?php _e("Antes de usar las funcionalidades prueba la conexión pulsando el botón. Si todo esta correcto debajo de los botones se mostrarán como ejemplo 3 productos:", "imacPrestashop");?></p>												
						<em class="clave"><?php _e("Si Wordpress y Prestashop están en diferentes servidores, es posible que tengas que solicitar al servidor de Wordpress que te habilite en su firewall un acceso a la IP del prestashop.", "imacPrestashop");?></em>
						<br><img src="<?php echo plugins_url('images/probando_conexion.jpg',__FILE__);?>" width="85%" height="85%" />
					</div>
					<br>
					<h3><?php _e("Funcionamiento:", "imacPrestashop");?></h3>
					<div id="imacreste-alerts">
						<p><?php _e("En estos momentos el plugin se puede usar de 3 formas:", "imacPrestashop");?></p>
						<p>	
							<ol>
								<li>
									<?php _e("Dentro de los artículos podemos poner los <strong>productos de una categoría concreta</strong>:", "imacPrestashop");?>
									<strong><pre>[imacPrestashop_categorias cant_productos="6" categoria="1" idioma="1"]</pre></strong>
									<ul>
										<li><strong>cant_productos</strong> => <?php _e("Número de productos que se mostraran. La visualización se adapta a los formatos de pantalla pudiendo quedar en 3, 2 o 1 única columna por fila para mvls.", "imacPrestashop");?></li>
										<li><strong>categoria</strong> => <?php _e("Es el ID de la categoría. Por defecto se coge la establecida en la configuración. Los productos se extraen de esta categoría, y se muestran el número de productos indicado seleccionándolos de forma aleatoria.", "imacPrestashop");?></li>
										<li><strong>idioma</strong> => <?php _e("Es el ID del idioma. Por defecto se coge la establecida en la configuración. Se usa para extraer los productos en el idioma indicado.", "imacPrestashop");?></li>
									</ul>
									<br>
									<?php _e("El código mínimo para que funcione es:", "imacPrestashop");?>
									<strong><pre>[imacPrestashop_categorias]</pre></strong>									
									<ul>										
										<li><?php _e("En este caso el idioma se cogerá el establecido en la configuración (si se deja en blanco será 1), cant_productos = 6 de la categoría configurada.", "imacPrestashop");?></li>										
									</ul>
									<br>
									<img src="<?php echo plugins_url('images/ej1.jpg',__FILE__);?>" width="85%" height="85%" />
								</li>
								<li>
									<?php _e("Dentro de los artículos podemos poner un <strong>grupo de productos</strong>:", "imacPrestashop");?>
									<strong><pre>[imacPrestashop_productos productos="1,2,3,4,5,6" idioma="1"]</pre></strong>
									<ul>										
										<li><strong>productos</strong> => <?php _e("Son los ids de los productos, puedes verlos desde tu Prestashop en Catalogo -> Productos. Cada id_producto se debe separar con una coma.", "imacPrestashop");?></li>
										<li><strong>idioma</strong> => <?php _e("Es el ID del idioma. Por defecto se coge la establecida en la configuración. Se usa para extraer los productos en el idioma indicado.", "imacPrestashop");?></li>
									</ul>
									<br>
									<?php _e("El código mínimo para que funcione es:", "imacPrestashop");?>
									<strong><pre>[imacPrestashop_productos]</pre></strong>									
									<ul>										
										<li><?php _e("En este caso el idioma se cogerá el establecido en la configuración (si se deja en blanco será 1), y solo mostrará el id_producto = 1.", "imacPrestashop");?></li>										
									</ul>
									<br>
									<img src="<?php echo plugins_url('images/ej2.jpg',__FILE__);?>" width="75%" height="75%" />
								</li>
								<li>
									<?php _e("Se puede arrastrar un <strong>widget</strong> en el sidebar.", "imacPrestashop");?><br><br>
									<ul>										
										<li><?php _e("Entrando en Apariencia -> Widgets, puedes ver un bloque llamado: prestashop, que puedes arrastrar a los cuadros de la derecha.", "imacPrestashop");?></li>										
										<li><strong>Título</strong> => <?php _e("No es obligatorio. Sería la cabecera..", "imacPrestashop");?></li>
										<li><strong>ID Categoría productos</strong> => <?php _e("No es obligatorio pero si recomendable. Es la categoría de la que se extraen productos. Puedes verlo desde el menú catálogo -> categorías.", "imacPrestashop");?></li>
										<li><strong>Número productos</strong> => <?php _e("No es obligatorio pero si recomendable. Es el número de productos que se visualizarán, si en la categoría hay más de los indicados, se mostrarán de forma aleatoria.", "imacPrestashop");?></li>
										<li><strong>ID Idioma</strong> => <?php _e("No es obligatorio pero si recomendable. Indica el id_idioma en caso de tener varios. Puedes verlo desde localización -> idiomas. por defecto = 1", "imacPrestashop");?></li>
									</ul>
									<br>
									<img src="<?php echo plugins_url('images/ej3.jpg',__FILE__);?>" width="85%" height="85%" />
								</li>
							</ol>
						</p>
					</div>	
					<h3><?php _e("Funcionalidades implementadas:", "imacPrestashop");?></h3>
					<div id="imacreste-alerts">
						<p><?php _e("Listado de funcionalidades testeadas en algunas versiones de Prestashop: ", "imacPrestashop");?></p>
						<ol>
							<li>
								<?php _e("Se muestran productos estándar.", "imacPrestashop");?>
							</li>
							<li>
								<?php _e("Se muestran precios base y con ofertas en %.", "imacPrestashop");?>
							</li>
							<li>
								<?php _e("En el filtro de categoría y widget no salen productos sin stock y productos no activos.", "imacPrestashop");?>
							</li>
							<li>
								<?php _e("Es posible que otras combinaciones se muestre sin problemas pero no han sido testeadas", "imacPrestashop");?>
							</li>							
						</ol>
						<p><?php _e("Aunque subiremos mejoras, cualquier sugerencia será bienvenida. Necesitaremos el máximo de información posible como: Versión de Prestashop y descripción completa del problema detectado. Contacto: <a href='mailto:imacreste@gmail.com'>mail</a> o entrando en la web <a href='https://imacreste.com' target='_blank'>https://imacreste.com</a>", "imacPrestashop");?></p>
						<div class="red"></div>
					</div>				
				</div>			
			</div>
		</div>
	<?php
	}
	
	//función de recogida de datos de conexión
	function imacPrestashop_fc(){				
	?>
		<script type="text/javascript">			
			//$(function(){	
			jQuery(document).ready(function($) {
				$('.probar_conexion').click(function(){
					$('#probando_bd').show();
				});		
			});		
		</script>
		<div class="wrap">								
			<h2 class="nav-tab-wrapper">
				<a class="nav-tab nav-tab-active" href="?page=imacPrestashop"><?php _e("Configuración de Prestashop", "imacPrestashop");?></a>
				<a class="nav-tab" href="?page=imacPrestashop_help"><?php _e("Ayuda", "imacPrestashop");?></a>
			</h2>
			<?php 
					settings_fields('imacPrestashop-grupo-config');
					$imacPrestashop_options=get_option('imacPrestashop_options');
					
					if (isset($_POST['option_localhost'])){
						check_admin_referer('guardar_imac_settings','imacPrestashop_guardar_settings');
						$imacPrestashop_options['option_localhost']=imacPrestashop_encriptacion::encriptar($_POST['option_localhost']);
						$imacPrestashop_options['option_name']=imacPrestashop_encriptacion::encriptar($_POST['option_name']);
						$imacPrestashop_options['option_user']=imacPrestashop_encriptacion::encriptar($_POST['option_user']);
						$imacPrestashop_options['option_pass']=imacPrestashop_encriptacion::encriptar($_POST['option_pass']);
						$imacPrestashop_options['option_prefijo']=imacPrestashop_encriptacion::encriptar($_POST['option_prefijo']);
						$imacPrestashop_options['option_url']=sanitize_text_field($_POST['option_url']);
						$imacPrestashop_options['option_idioma']=sanitize_text_field($_POST['option_idioma']);
						$imacPrestashop_options['option_categoria']=sanitize_text_field($_POST['option_categoria']);
						$imacPrestashop_options['option_urlP']=sanitize_text_field($_POST['option_urlP']);
						$imacPrestashop_options['option_nofollow']=0;
						if (isset($_POST['option_nofollow']))$imacPrestashop_options['option_nofollow']=sanitize_text_field($_POST['option_nofollow']);
						$imacPrestashop_options['option_ofertas']=0;
						if (isset($_POST['option_ofertas']))$imacPrestashop_options['option_ofertas']=sanitize_text_field($_POST['option_ofertas']);											
						update_option( 'imacPrestashop_options', $imacPrestashop_options );						
					}			
			?>
			<form method="post" action="options-general.php?page=imacPrestashop">
				<?php wp_nonce_field('guardar_imac_settings','imacPrestashop_guardar_settings');?>
				<p><br><em class="clave"><?php _e("Los datos de base de datos se guardan encriptados. (Servidor, Nombre BD, Usuario, Contraseña y Prefijo). <br>Si no sabes cómo cambiarlo, contacta con tu webmaster o si prefieres que te asesoremos puedes mandarnos un <a href='mailto:imacreste@gmail.com'>mail</a> o entrando en la web <a href='https://imacreste.com' target='_blank'>https://imacreste.com</a>.<br>Los productos fuera de stock y los no activos no se muestran.", "imacPrestashop");?></em></p>	
				<table class="form-table">					
					<tr valign="top">
						<th scope="row">
							<?php _e("Servidor BD:", "imacPrestashop");?>
						</th>
						<td>							
							<input type="text" name="option_localhost" required value="<?php echo (isset($imacPrestashop_options['option_localhost'])) ? imacPrestashop_encriptacion::desencriptar($imacPrestashop_options['option_localhost']) : '';?>" />	
							<em><?php _e("<br>Es el servidor de acceso a la base de datos.<br>Puedes probar con localhost o con el dominio de la web sin http. Ejemplo: miweb.com", "imacPrestashop");?></em>						
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<?php _e("Nombre BD:", "imacPrestashop");?> 
						</th>
						<td>							
							<input type="text" name="option_name" required value="<?php echo (isset($imacPrestashop_options['option_name'])) ? imacPrestashop_encriptacion::desencriptar($imacPrestashop_options['option_name']) : '';?>" />		
							<em><?php _e("<br>Es el nombre de la base de datos.", "imacPrestashop");?></em>						
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<?php _e("Usuario BD:", "imacPrestashop");?>
						</th>
						<td>							
							<input type="text" name="option_user" required value="<?php echo (isset($imacPrestashop_options['option_user'])) ? imacPrestashop_encriptacion::desencriptar($imacPrestashop_options['option_user']) : '';?>" />	
							<em><?php _e("<br>Es el usuario de acceso a la base de datos.", "imacPrestashop");?></em>							
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<?php _e("Contraseña BD:", "imacPrestashop");?>
						</th>
						<td>
							<input type="text" name="option_pass" required value="<?php echo (isset($imacPrestashop_options['option_pass'])) ? imacPrestashop_encriptacion::desencriptar($imacPrestashop_options['option_pass']) : '';?>" />			
							<em><?php _e("<br>Es la contraseña de acceso a la base de datos.", "imacPrestashop");?></em>					
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<?php _e("Url de la tienda:","imacPrestashop");?>
						</th>
						<td>							
							<input type="text" name="option_url" required value="<?php echo (isset($imacPrestashop_options['option_url'])) ? esc_attr($imacPrestashop_options['option_url']) : '';?>" />
							<em><?php _e("<br>Incluir http o https, ejemplo: https://imacreste.com/ o http://www.orainbai.es/, y la barra al final.", "imacPrestashop");?></em>								
						</td>
					</tr>	
					<tr valign="top">
						<th scope="row">
							<?php _e("Prefijo:","imacPrestashop");?>
						</th>
						<td>							
							<input type="text" name="option_prefijo" required value="<?php echo (isset($imacPrestashop_options['option_prefijo'])) ?  imacPrestashop_encriptacion::desencriptar($imacPrestashop_options['option_prefijo']) : '';?>" />
							<em><?php _e("<br>Es el prefijo de las tablas de prestashop. Si tienes acceso a la base de datos fíjate que todas empiezan igual.", "imacPrestashop");?></em>								
						</td>
					</tr>			
					<tr valign="top">
						<th scope="row">
							<?php _e("ID categoría con productos:", "imacPrestashop");?>
						</th>
						<td>							
							<input type="number" name="option_categoria" required value="<?php echo (isset($imacPrestashop_options['option_categoria'])) ? esc_attr($imacPrestashop_options['option_categoria']) : 1;?>" />	
							<em><?php _e("<br>Este campo se usara por defecto, si no se introduce una categoría en el shortcode", "imacPrestashop");?></em>						
						</td>
					</tr>		
					<tr valign="top">
						<th scope="row">
							<?php _e("ID idioma:", "imacPrestashop");?>
						</th>
						<td>							
							<input type="number" name="option_idioma" required value="<?php echo (isset($imacPrestashop_options['option_idioma'])) ? esc_attr($imacPrestashop_options['option_idioma']) : 1;?>" />	
							<em><?php _e("<br>Este campo se usara por defecto, si no se introduce un idioma se usara el id_idioma=1", "imacPrestashop");?></em>						
						</td>
					</tr>	
					<tr valign="top">
						<th scope="row">
							<?php _e("Enlaces Nofollow:", "imacPrestashop");?>
						</th>
						<td>							
							<input type="checkbox" name="option_nofollow" value="1" <?php checked( $imacPrestashop_options['option_nofollow'], '1' ); ?> />	
							<em><?php _e("<br>Si esta seleccionada la casilla, los enlaces serán nofollow, es un atributo SEO. <a href='https://support.google.com/webmasters/answer/96569?hl=es' target='_blank'>Más Información</a>.", "imacPrestashop");?></em>						
						</td>
					</tr>	
					<tr valign="top">
						<th scope="row">
							<?php _e("Ocultar Ofertas:", "imacPrestashop");?>
						</th>
						<td>							
							<input type="checkbox" name="option_ofertas" value="1" <?php checked( $imacPrestashop_options['option_ofertas'], '1' ); ?> />	
							<em><?php _e("<br>Si esta seleccionada la casilla, No se visualizarán las ofertas, solo el precio de los productos.", "imacPrestashop");?></em>						
						</td>
					</tr>						
					<tr valign="top">
						<th scope="row">
							<?php _e("URLs de producto:", "imacPrestashop");?>
						</th>
						<td>																					
							<select name="option_urlP">
								<option value='0' <?php selected($imacPrestashop_options['option_urlP'],0);?>><?php _e("ID-name_product.html", "imacPrestashop");?></option>
								<option value='1' <?php selected($imacPrestashop_options['option_urlP'],1);?>><?php _e("name_product-ID.html", "imacPrestashop");?></option>
							</select>
							<em><?php _e("<br>Las URLS de Prestashop se pueden configurar desde:<br> parámetros de la tienda -> Tráfico y SEO -> Ruta a los productos. <br>Esto permite múltiples combinaciones. Nos hemos basado en <strong>2 alternativas</strong><br>1º) por defecto, basada en: <strong>id-nombre_producto.html (2-blusa.html)</strong><br> 2º) <strong>nombre_producto-id.html (blusa-2.html)</strong><br><em>Si habré un producto puede verlo en la URL.</em>", "imacPrestashop");?></em>						
						</td>
					</tr>									 
				</table>
				<p class="submit"><input type="submit" class="button-primary" value="Guardar" /> &nbsp; <a class="button-primary probar_conexion"><?php _e("Prueba la Conexión (Si haces cambios guarda primero)", "imacPrestashop");?></a></p>
				<div id="probando_bd" style="display:none;">
					<?php												
						$desencriptar_host=imacPrestashop_encriptacion::desencriptar($imacPrestashop_options['option_localhost']);						
						$desencriptar_nombre=imacPrestashop_encriptacion::desencriptar($imacPrestashop_options['option_name']);						
						$desencriptar_user=imacPrestashop_encriptacion::desencriptar($imacPrestashop_options['option_user']);												
						$desencriptar_pass=imacPrestashop_encriptacion::desencriptar($imacPrestashop_options['option_pass']);		
						$desencriptar_prefijo=imacPrestashop_encriptacion::desencriptar($imacPrestashop_options['option_prefijo']);									
										
						$db_host=$desencriptar_host;						
						$db_nombre=$desencriptar_nombre;						
						$db_user=$desencriptar_user;						
						$db_pass=$desencriptar_pass;						
						$prefijo=$desencriptar_prefijo;						
						$url_tienda=esc_attr($imacPrestashop_options['option_url']);    								
						$categoria_base=esc_attr($imacPrestashop_options['option_categoria']);									
						$idioma_base=esc_attr($imacPrestashop_options['option_idioma']);
						$url_base=(isset($imacPrestashop_options['option_urlP']) ? $imacPrestashop_options['option_urlP'] : 0);
						$nofollow=$imacPrestashop_options['option_nofollow'];
						$ofertas=$imacPrestashop_options['option_ofertas'];								
						$txt_nofollow='';
						if ($nofollow==1){
							$txt_nofollow='rel="nofollow"';	
						}						
						$link=mysqli_connect($db_user, $db_pass, $db_nombre);									
						if (!$link) {
						    _e("<strong>No ha sido posible conectarse con prestashop:</strong> <br><ul style='margin-left:20px;'><li>1º) Confirma que los datos de arriba son correctos.</li><li>2º) Contacta con tu webmaster y confirma estos datos.</li><li>3º) Confirma con tu Webmaster o Hosting que su firewall permita conectarse con un hosting externo.</li></ul>", "imacPrestashop");
						   	_e("Revisa el siguiente error por si te da alguna pista:<br>", "imacPrestashop");
						    _e("- errno de depuración: ", "imacPrestashop");
						    echo mysqli_connect_errno() . PHP_EOL;
						    _e("<br>- error de depuración: ", "imacPrestashop");
						    echo mysqli_connect_error() . PHP_EOL;
						     _e("<br><br>Necesitas ayuda, mándame un <a href='mailto:imacreste@gmail.com'>mail</a>.", "imacPrestashop");
						    exit;
						}else{
								echo "Exito: Se realizo una conexion apropiada a MySQL! Puedes ver un ejemplo:<br>" . PHP_EOL;
						}								
						mysqli_select_db($link, $db_nombre) or die("Error seleccionando la base de datos.");		
											
						$cant_productos=3;
						$id_idioma=$idioma_base;
						$categoria=$categoria_base;
												
						$images=$prefijo."image";
						$product=$prefijo."product";
						$prodyct_lang=$prefijo."product_lang";
						$category_product=$prefijo."category_product";
						$product_attribute=$prefijo."product_attribute";
						$category_lang=$prefijo."category_lang";
						$image_lang=$prefijo."image_lang";
						$tax_rule=$prefijo."tax_rule";
						$tax=$prefijo."tax";
						$specific_price=$prefijo."specific_price";
						$stock_available=$prefijo."stock_available";													
						$language=$prefijo."lang";
						
						$sqllangCount="select * from $language where active=1";	
						$consultalangCount = mysqli_query($link, $sqllangCount);		
						$total_resultadoslang = mysqli_num_rows($consultalangCount);						
						
						$sqllang="select * from $language where id_lang='$id_idioma'";			
						$consultalang = mysqli_query($link, $sqllang);				
						$registrosqllang=mysqli_fetch_array($consultalang);										
						$lang_txt='';
						if ($total_resultadoslang>1)$lang_txt=$registrosqllang['iso_code']."/";							
						$sql ="
							SELECT p.*, pa.id_product_attribute, pl.description, pl.description_short, pl.available_now, pl.available_later, pl.link_rewrite, pl.meta_description,
						 	pl.meta_keywords, pl.meta_title, pl.name, i.id_image, il.legend, 	p.price as precio_base,
						  ROUND(p.price * (COALESCE(ptx.rate, 0) / 100 + 1), 2) AS 'regular_price', ptx.rate as iva,
						  IF(pr.reduction_type = 'amount', pr.reduction, '') AS 'Discount_amount', IF(pr.reduction_type = 'percentage', pr.reduction, '') AS 'Discount_percentage', pr.reduction_tax as reduction_offer, s.quantity						  
						  FROM $category_product cp 
						  LEFT JOIN $product p ON p.id_product = cp.id_product 
						  LEFT JOIN $product_attribute pa ON (p.id_product = pa.id_product AND default_on = 1) 
						  LEFT JOIN $category_lang cl ON (p.id_category_default = cl.id_category AND cl.id_lang = '.$id_idioma.') 
						  LEFT JOIN $specific_price pr ON(p.id_product = pr.id_product)
						  LEFT JOIN $prodyct_lang pl ON (p.id_product = pl.id_product AND pl.id_lang = '.$id_idioma.') 
						  LEFT JOIN $images i ON (i.id_product = p.id_product AND i.cover = 1) 
						  LEFT JOIN $image_lang il ON (i.id_image = il.id_image AND il.id_lang = '.$id_idioma.')   
						  LEFT JOIN $tax_rule ptxgrp ON ptxgrp.id_tax_rules_group = p.id_tax_rules_group
							LEFT JOIN $tax ptx ON ptx.id_tax = ptxgrp.id_tax    
							LEFT JOIN $stock_available s ON (p.id_product = s.id_product)							
						  WHERE cp.id_category IN ($categoria) AND p.active = 1 AND s.quantity>0
						  GROUP BY cp.id_product
						  order by rand() LIMIT $cant_productos
						";										
					
						$consulta = mysqli_query($link, $sql);		
						$total_resultados = mysqli_num_rows($consulta);		
						$content='<ul class="short-products3">';						
						if ($total_resultados>0)
						{		
							$i = 1;
							while ($row = $consulta->fetch_object())
							{				
								$resto = ($i % 3);
								$sqlobtener="select name, link_rewrite from $prodyct_lang where id_product='$row->id_product' and id_lang='$id_idioma'";			
								$consutlasql=mysqli_query($link, $sqlobtener);
								$registrosql=mysqli_fetch_array($consutlasql);		
								$content.="<li>";
								if ($url_base==0){
									$content.="<a ".$txt_nofollow." title='".utf8_encode($registrosql['name'])."' href='".$url_tienda.$lang_txt.$row->id_product."-".$registrosql['link_rewrite'].".html' target='blank_'>";								
								}else{
									$content.="<a ".$txt_nofollow." title='".utf8_encode($registrosql['name'])."' href='".$url_tienda.$lang_txt.$registrosql['link_rewrite']."-".$row->id_product.".html' target='blank_'>";								
								}
								$content.="<img style='border-color:#F90' src='".$url_tienda.$row->id_image."-home_default/".$registrosql['link_rewrite'].".jpg' alt='Imagén: ".utf8_encode($registrosql['name'])."' /></a>";
								if ($url_base==0){
									$content.="<br><a ".$txt_nofollow." class='product_name' title='".utf8_encode($registrosql['name'])."' href='".$url_tienda.$lang_txt.$row->id_product."-".$registrosql['link_rewrite'].".html' target='blank_'>";
								}else{
									$content.="<br><a ".$txt_nofollow." class='product_name' title='".utf8_encode($registrosql['name'])."' href='".$url_tienda.$lang_txt.$registrosql['link_rewrite']."-".$row->id_product.".html' target='blank_'>";
								}
								$content.=utf8_encode($registrosql['name']);								
								$content.="</a><br>";
								$precio=$row->regular_price;
								$preu = number_format($precio, 2, ',', '');	
								$discount_iva=0;					
								if ($row->reduction_offer==1){										
									$Discount_amount =$row->Discount_amount;		
									$Discount_percentage =$row->Discount_percentage;
									if ($Discount_amount!=''){		
										$preu_dto=number_format($precio-$Discount_amount, 2, ',', '');		
									}
									if ($Discount_percentage!=''){		
										$preu_dto=number_format($precio-($precio*$Discount_percentage), 2, ',', '');		
									}					
								}else{					
									$Discount_amount = $row->Discount_amount;					
									if ($Discount_amount!=''){
										$discount_iva =(($Discount_amount * 21)/100);			
										$Discount_amount=$Discount_amount+$discount_iva;				
										$preu_dto=number_format($precio-$Discount_amount, 2, ',', '');		
									}
									$Discount_percentage =$row->Discount_percentage;
									if ($Discount_percentage!=''){		
										$discount_iva =(($Discount_percentage * 21)/100);			
										$Discount_percentage=$Discount_percentage+$discount_iva;
										$preu_dto=number_format($precio-($precio*$Discount_percentage), 2, ',', '');		
									}	
								}				
								
								if ($ofertas==1){
									$content.="<span class='price'>".$preu." €</span><br>";
								}else{
									if (($Discount_amount!=0) || ($Discount_percentage!=0)){
										if ($Discount_percentage!=0){
											$content.="<span class='offer'>".$preu."</span><span class='price'>".$preu_dto." €</span> <span class='dto'>(".number_format($Discount_percentage*100, 2, ',', '')." %)</span><br>";
										}else{
											$content.="<span class='offer'>".$preu."</span><span class='price'>".$preu_dto." €</span> <span class='dto'>(- ".number_format($Discount_amount, 2, ',', '')." €)</span><br>";
										}
									}else{
										$content.="<span class='price'>".$preu." €</span><br>";
									}			
								}										
								$content.="</li>";			
								$i++;
							}	
								$content.="</ul>";
								
								$content.="<p>Ahora que todo funciona, puedes probar a meter estos códigos dentro de la descripción de una entrada: <strong>[imacPrestashop_productos] o [imacPrestashop_productos]</strong>. <br>Si quieres aprender a mostrar X productos de una categoría concreta o mostrar unos productos concretos <strong>visita la <a href='options-general.php?page=imacPrestashop_help'>ayuda</a></strong>.</p>";
							
						}else{
							$content=__("La conexión a la Base de datos sido correcta.<br> Pero no hemos encontrado resultados en esa categoría.<br>Revisa si el ID categoría, el ID idioma y el prefijo son correctos.", "imacPrestashop");
						}		
						
						mysqli_close($link);
						echo $content;						
					?>
				</div>
			</form>
		</div>
	<?php
	}		
	
	//Antes de guardar los datos de conexión, los saneamos
	function imacPrestashop_sanitize($input){
		
		return $input;
	}	
		
	//shotcode categorias
	function imacPrestashop_shortcode_categorias($atts) {		    		
		$imacPrestashop_options=get_option('imacPrestashop_options');						  
		
		$desencriptar_host=imacPrestashop_encriptacion::desencriptar($imacPrestashop_options['option_localhost']);						
		$desencriptar_nombre=imacPrestashop_encriptacion::desencriptar($imacPrestashop_options['option_name']);						
		$desencriptar_user=imacPrestashop_encriptacion::desencriptar($imacPrestashop_options['option_user']);												
		$desencriptar_pass=imacPrestashop_encriptacion::desencriptar($imacPrestashop_options['option_pass']);		
		$desencriptar_prefijo=imacPrestashop_encriptacion::desencriptar($imacPrestashop_options['option_prefijo']);
						
		$db_host=$desencriptar_host;						
		$db_nombre=$desencriptar_nombre;						
		$db_user=$desencriptar_user;						
		$db_pass=$desencriptar_pass;						
		$prefijo=$desencriptar_prefijo;			
		$url_tienda=esc_attr($imacPrestashop_options['option_url']);    		
		$categoria_base=esc_attr($imacPrestashop_options['option_categoria']);
		$idioma_base=esc_attr($imacPrestashop_options['option_idioma']);
		$url_base=(isset($imacPrestashop_options['option_urlP']) ? $imacPrestashop_options['option_urlP'] : 0);
		$nofollow=$imacPrestashop_options['option_nofollow'];
		$ofertas=$imacPrestashop_options['option_ofertas'];	
		$txt_nofollow='';
		if ($nofollow==1){
			$txt_nofollow='rel="nofollow"';	
		}
		
		$link=mysqli_connect($db_host, $db_user, $db_pass, $db_nombre);			
		if (!$link) {		    
		    exit;
		}			
		mysqli_select_db($link, $db_nombre) or die("Error seleccionando la base de datos.");			
		$datos = shortcode_atts( array(   
        'cant_productos' => '6',
        'idioma' => $idioma_base,
        'categoria' => $categoria_base,
   	), $atts );
   				
		$cant_productos=$datos["cant_productos"];
		$id_idioma=$datos["idioma"];
		$categoria=$datos["categoria"];
		
		$images=$prefijo."image";
		$product=$prefijo."product";
		$prodyct_lang=$prefijo."product_lang";
		$category_product=$prefijo."category_product";
		$product_attribute=$prefijo."product_attribute";
		$category_lang=$prefijo."category_lang";
		$image_lang=$prefijo."image_lang";
		$tax_rule=$prefijo."tax_rule";
		$tax=$prefijo."tax";
		$specific_price=$prefijo."specific_price";
		$stock_available=$prefijo."stock_available";
		$language=$prefijo."lang";
						
		$sqllangCount="select * from $language where active=1";	
		$consultalangCount = mysqli_query($link, $sqllangCount);		
		$total_resultadoslang = mysqli_num_rows($consultalangCount);						
		
		$sqllang="select * from $language where id_lang='$id_idioma'";			
		$consultalang = mysqli_query($link, $sqllang);				
		$registrosqllang=mysqli_fetch_array($consultalang);										
		$lang_txt='';
		if ($total_resultadoslang>1)$lang_txt=$registrosqllang['iso_code']."/";	
		
		$sql ="
			SELECT p.*, pa.id_product_attribute, pl.description, pl.description_short, pl.available_now, pl.available_later, pl.link_rewrite, pl.meta_description,
		 	pl.meta_keywords, pl.meta_title, pl.name, i.id_image, il.legend, 	p.price as precio_base,
		  ROUND(p.price * (COALESCE(ptx.rate, 0) / 100 + 1), 2) AS 'regular_price', ptx.rate as iva,
		  IF(pr.reduction_type = 'amount', pr.reduction, '') AS 'Discount_amount', IF(pr.reduction_type = 'percentage', pr.reduction, '') AS 'Discount_percentage', pr.reduction_tax as reduction_offer, s.quantity
		  FROM $category_product cp 
		  LEFT JOIN $product p ON p.id_product = cp.id_product 
		  LEFT JOIN $product_attribute pa ON (p.id_product = pa.id_product AND default_on = 1) 
		  LEFT JOIN $category_lang cl ON (p.id_category_default = cl.id_category AND cl.id_lang = '.$id_idioma.') 
		  LEFT JOIN $specific_price pr ON(p.id_product = pr.id_product)
		  LEFT JOIN $prodyct_lang pl ON (p.id_product = pl.id_product AND pl.id_lang = '.$id_idioma.') 
		  LEFT JOIN $images i ON (i.id_product = p.id_product AND i.cover = 1) 
		  LEFT JOIN $image_lang il ON (i.id_image = il.id_image AND il.id_lang = '.$id_idioma.')   
		  LEFT JOIN $tax_rule ptxgrp ON ptxgrp.id_tax_rules_group = p.id_tax_rules_group
			LEFT JOIN $tax ptx ON ptx.id_tax = ptxgrp.id_tax        
			LEFT JOIN $stock_available s ON (p.id_product = s.id_product) 
		  WHERE cp.id_category IN ($categoria) AND p.active = 1 AND s.quantity>0
		  GROUP BY cp.id_product
		  order by rand() LIMIT $cant_productos
		";														
		$consulta = mysqli_query($link, $sql);		
		$total_resultados = mysqli_num_rows($consulta);		
					
		
		$content='<ul class="short-products">';
		if ($total_resultados>0)
		{		
			$i = 1;
			while ($row = $consulta->fetch_object())
			{				
				$resto = ($i % 3);
				$sqlobtener="select name, link_rewrite from $prodyct_lang where id_product='$row->id_product' and id_lang='$id_idioma'";			
				$consutlasql=mysqli_query($link, $sqlobtener);
				$registrosql=mysqli_fetch_array($consutlasql);	
				
				$content.="<li>";
				if ($url_base==0){
					$content.="<a ".$txt_nofollow." title='".utf8_encode($registrosql['name'])."' href='".$url_tienda.$lang_txt.$row->id_product."-".$registrosql['link_rewrite'].".html' target='blank_'>";								
				}else{
					$content.="<a ".$txt_nofollow." title='".utf8_encode($registrosql['name'])."' href='".$url_tienda.$lang_txt.$registrosql['link_rewrite']."-".$row->id_product.".html' target='blank_'>";								
				}
				$content.="<img style='border-color:#F90' src='".$url_tienda.$row->id_image."-home_default/".$registrosql['link_rewrite'].".jpg' alt='Imagén: ".utf8_encode($registrosql['name'])."' /></a>";		
				if ($url_base==0){
					$content.="<br><a ".$txt_nofollow." class='product_name' title='".utf8_encode($registrosql['name'])."' href='".$url_tienda.$lang_txt.$row->id_product."-".$registrosql['link_rewrite'].".html' target='blank_'>";
				}else{
					$content.="<br><a ".$txt_nofollow." class='product_name' title='".utf8_encode($registrosql['name'])."' href='".$url_tienda.$lang_txt.$registrosql['link_rewrite']."-".$row->id_product.".html' target='blank_'>";
				}
				$content.=utf8_encode($registrosql['name']);								
				$content.="</a><br>";
								
				$precio=$row->regular_price;
				$preu = number_format($precio, 2, ',', '');	
				$discount_iva=0;					
				if ($row->reduction_offer==1){										
					$Discount_amount =$row->Discount_amount;		
					$Discount_percentage =$row->Discount_percentage;
					if ($Discount_amount!=''){		
						$preu_dto=number_format($precio-$Discount_amount, 2, ',', '');		
					}
					if ($Discount_percentage!=''){		
						$preu_dto=number_format($precio-($precio*$Discount_percentage), 2, ',', '');		
					}					
				}else{					
					$Discount_amount = $row->Discount_amount;					
					if ($Discount_amount!=''){
						$discount_iva =(($Discount_amount * 21)/100);			
						$Discount_amount=$Discount_amount+$discount_iva;				
						$preu_dto=number_format($precio-$Discount_amount, 2, ',', '');		
					}
					$Discount_percentage=$row->Discount_percentage;
					if ($Discount_percentage!=''){		
						$discount_iva =(($Discount_percentage * 21)/100);			
						$Discount_percentage=$Discount_percentage+$discount_iva;
						$preu_dto=number_format($precio-($precio*$Discount_percentage), 2, ',', '');		
					}	
				}		
				if ($ofertas==1){
					$content.="<span class='price'>".$preu." €</span><br>";
				}else{
					if (($Discount_amount!=0) || ($Discount_percentage!=0)){
						if ($Discount_percentage!=0){
							$content.="<span class='offer'>".$preu."</span><span class='price'>".$preu_dto." €</span> <span class='dto'>(".number_format($Discount_percentage*100, 2, ',', '')." %)</span><br>";
						}else{
							$content.="<span class='offer'>".$preu."</span><span class='price'>".$preu_dto." €</span> <span class='dto'>(- ".number_format($Discount_amount, 2, ',', '')." €)</span><br>";
						}
					}else{
						$content.="<span class='price'>".$preu." €</span><br>";
					}	
				}				
				$content.="</li>";							
				
				$i++;
			}	
				$content.="</ul>";
			
		}else{
			$content="";
		}			
		return $content;
	}
	add_shortcode('imacPrestashop_categorias', 'imacPrestashop_shortcode_categorias');
	
	//shotcode productos
	function imacPrestashop_shortcode_productos($atts) {		        
		$imacPrestashop_options=get_option('imacPrestashop_options');	
		
		$desencriptar_host=imacPrestashop_encriptacion::desencriptar($imacPrestashop_options['option_localhost']);						
		$desencriptar_nombre=imacPrestashop_encriptacion::desencriptar($imacPrestashop_options['option_name']);						
		$desencriptar_user=imacPrestashop_encriptacion::desencriptar($imacPrestashop_options['option_user']);												
		$desencriptar_pass=imacPrestashop_encriptacion::desencriptar($imacPrestashop_options['option_pass']);		
		$desencriptar_prefijo=imacPrestashop_encriptacion::desencriptar($imacPrestashop_options['option_prefijo']);
						
		$db_host=$desencriptar_host;						
		$db_nombre=$desencriptar_nombre;						
		$db_user=$desencriptar_user;						
		$db_pass=$desencriptar_pass;						
		$prefijo=$desencriptar_prefijo;			
		$url_tienda=esc_attr($imacPrestashop_options['option_url']);    		
		$categoria_base=esc_attr($imacPrestashop_options['option_categoria']);
		$idioma_base=esc_attr($imacPrestashop_options['option_idioma']);
		$url_base=(isset($imacPrestashop_options['option_urlP']) ? $imacPrestashop_options['option_urlP'] : 0);
		$nofollow=$imacPrestashop_options['option_nofollow'];
		$ofertas=$imacPrestashop_options['option_ofertas'];	
		$txt_nofollow='';
		if ($nofollow==1){
			$txt_nofollow='rel="nofollow"';	
		}
		
		$link=mysqli_connect($db_host, $db_user, $db_pass, $db_nombre);			
		if (!$link) {		   
		    exit;
		}			
		mysqli_select_db($link, $db_nombre) or die("Error seleccionando la base de datos.");	
		
		$datos = shortcode_atts( array(           
        'cant_productos' => '6',
        'idioma' => $idioma_base,
        'productos' => '1',
   	), $atts );
						
		$cant_productos=$datos["cant_productos"];
		$id_idioma=$datos["idioma"];
		$productos=$datos["productos"];
		
		$images=$prefijo."image";
		$product=$prefijo."product";
		$prodyct_lang=$prefijo."product_lang";
		$category_product=$prefijo."category_product";
		$product_attribute=$prefijo."product_attribute";
		$category_lang=$prefijo."category_lang";
		$image_lang=$prefijo."image_lang";
		$tax_rule=$prefijo."tax_rule";
		$tax=$prefijo."tax";
		$specific_price=$prefijo."specific_price";
		$language=$prefijo."lang";
						
		$sqllangCount="select * from $language where active=1";	
		$consultalangCount = mysqli_query($link, $sqllangCount);		
		$total_resultadoslang = mysqli_num_rows($consultalangCount);						
		
		$sqllang="select * from $language where id_lang='$id_idioma'";			
		$consultalang = mysqli_query($link, $sqllang);				
		$registrosqllang=mysqli_fetch_array($consultalang);										
		$lang_txt='';
		if ($total_resultadoslang>1)$lang_txt=$registrosqllang['iso_code']."/";	
		
		$sql ="
			SELECT p.*, pa.id_product_attribute, pl.description, pl.description_short, pl.available_now, pl.available_later, pl.link_rewrite, pl.meta_description,
		 	pl.meta_keywords, pl.meta_title, pl.name, i.id_image, il.legend, 	p.price as precio_base,
		  ROUND(p.price * (COALESCE(ptx.rate, 0) / 100 + 1), 2) AS 'regular_price', ptx.rate as iva,
		  IF(pr.reduction_type = 'amount', pr.reduction, '') AS 'Discount_amount', IF(pr.reduction_type = 'percentage', pr.reduction, '') AS 'Discount_percentage', pr.reduction_tax as reduction_offer
		  FROM $category_product cp 
		  LEFT JOIN $product p ON p.id_product = cp.id_product 
		  LEFT JOIN $product_attribute pa ON (p.id_product = pa.id_product AND default_on = 1) 
		  LEFT JOIN $category_lang cl ON (p.id_category_default = cl.id_category AND cl.id_lang = '.$id_idioma.') 
		  LEFT JOIN $specific_price pr ON(p.id_product = pr.id_product)
		  LEFT JOIN $prodyct_lang pl ON (p.id_product = pl.id_product AND pl.id_lang = '.$id_idioma.') 
		  LEFT JOIN $images i ON (i.id_product = p.id_product AND i.cover = 1) 
		  LEFT JOIN $image_lang il ON (i.id_image = il.id_image AND il.id_lang = '.$id_idioma.')   
		  LEFT JOIN $tax_rule ptxgrp ON ptxgrp.id_tax_rules_group = p.id_tax_rules_group
			LEFT JOIN $tax ptx ON ptx.id_tax = ptxgrp.id_tax        
		  WHERE p.id_product IN ($productos) AND p.active = 1
		  GROUP BY cp.id_product
		  order by rand()
		";	
		//echo $sql;									
		$consulta = mysqli_query($link, $sql);		
		$total_resultados = mysqli_num_rows($consulta);		
		$content='<ul class="short-products">';
		if ($total_resultados>0)
		{		
			$i = 1;
			while ($row = $consulta->fetch_object())
			{				
				$resto = ($i % 3);
				$sqlobtener="select name, link_rewrite from $prodyct_lang where id_product='$row->id_product' and id_lang='$id_idioma'";			
				$consutlasql=mysqli_query($link, $sqlobtener);
				$registrosql=mysqli_fetch_array($consutlasql);			
				
				$content.="<li>";
				if ($url_base==0){
					$content.="<a ".$txt_nofollow." title='".utf8_encode($registrosql['name'])."' href='".$url_tienda.$lang_txt.$row->id_product."-".$registrosql['link_rewrite'].".html' target='blank_'>";								
				}else{
					$content.="<a ".$txt_nofollow." title='".utf8_encode($registrosql['name'])."' href='".$url_tienda.$lang_txt.$registrosql['link_rewrite']."-".$row->id_product.".html' target='blank_'>";								
				}
				$content.="<img style='border-color:#F90' src='".$url_tienda.$row->id_image."-home_default/".$registrosql['link_rewrite'].".jpg' alt='Imagén: ".utf8_encode($registrosql['name'])."' /></a>";		
				if ($url_base==0){
					$content.="<br><a ".$txt_nofollow." class='product_name' title='".utf8_encode($registrosql['name'])."' href='".$url_tienda.$lang_txt.$row->id_product."-".$registrosql['link_rewrite'].".html' target='blank_'>";
				}else{
					$content.="<br><a ".$txt_nofollow." class='product_name' title='".utf8_encode($registrosql['name'])."' href='".$url_tienda.$lang_txt.$registrosql['link_rewrite']."-".$row->id_product.".html' target='blank_'>";
				}
				$content.=utf8_encode($registrosql['name']);								
				$content.="</a><br>";
								
				$precio=$row->regular_price;
				$preu = number_format($precio, 2, ',', '');	
				$discount_iva=0;					
				if ($row->reduction_offer==1){										
					$Discount_amount =$row->Discount_amount;		
					$Discount_percentage =$row->Discount_percentage;
					if ($Discount_amount!=''){		
						$preu_dto=number_format($precio-$Discount_amount, 2, ',', '');		
					}
					if ($Discount_percentage!=''){		
						$preu_dto=number_format($precio-($precio*$Discount_percentage), 2, ',', '');		
					}					
				}else{					
					$Discount_amount = $row->Discount_amount;					
					if ($Discount_amount!=''){
						$discount_iva =(($Discount_amount * 21)/100);			
						$Discount_amount=$Discount_amount+$discount_iva;				
						$preu_dto=number_format($precio-$Discount_amount, 2, ',', '');		
					}
					$Discount_percentage =$row->Discount_percentage;
					if ($Discount_percentage!=''){		
						$discount_iva =(($Discount_percentage * 21)/100);			
						$Discount_percentage=$Discount_percentage+$discount_iva;
						$preu_dto=number_format($precio-($precio*$Discount_percentage), 2, ',', '');		
					}	
				}					
				if ($ofertas==1){
					$content.="<span class='price'>".$preu." €</span><br>";
				}else{
					if (($Discount_amount!=0) || ($Discount_percentage!=0)){
						if ($Discount_percentage!=0){
							$content.="<span class='offer'>".$preu."</span><span class='price'>".$preu_dto." €</span> <span class='dto'>(".number_format($Discount_percentage*100, 2, ',', '')." %)</span><br>";
						}else{
							$content.="<span class='offer'>".$preu."</span><span class='price'>".$preu_dto." €</span> <span class='dto'> (- ".number_format($Discount_amount, 2, ',', '')." €)</span><br>";
						}
					}else{
						$content.="<span class='price'>".$preu." €</span><br>";
					}
				}					
				$content.="</li>";
				
				$i++;
			}	
				$content.="</ul>";
			
		}else{
			$content="";
		}			
	
		return $content;
	}
	add_shortcode('imacPrestashop_productos', 'imacPrestashop_shortcode_productos');
	
	//widgetcategorias
	add_action('widgets_init','imacPrestashop_widget');
	function imacPrestashop_widget() {	
		register_widget( 'imacPrestashop_1_widget' );	
	}	
	
	class imacPrestashop_1_widget extends WP_Widget{
		function __construct(){
			$options = array(
				'classname' => 'imacPrestashop_class',
				'description' => 'Mostrar productos prestashop.'
			);
			parent::__construct('imacPrestashop_widget','Prestashop',$options);
		}
		
		function form($instance){			
			$imacPrestashop_options=get_option('imacPrestashop_options');			
			
			$defaults= array(
				'title' => 'Productos',
				'idioma' => '1',
				'category' => esc_attr($imacPrestashop_options['option_categoria']),
				'n_products' => ''
			);
			$instance=wp_parse_args((array) $instance, $defaults);
						
			$category=$instance['category'];
			$n_products=$instance['n_products'];
			$title=$instance['title'];
			$idioma=$instance['idioma'];
			?>			
				<p><?php _e("Título:", "imacPrestashop");?> <input type="text" class="widefat" name="<?php echo $this->get_field_name('title')?>" value="<?php echo esc_attr($title)?>" /></p>
				<p><?php _e("ID categoría Prestashop:", "imacPrestashop");?> <input type="text" class="widefat" name="<?php echo $this->get_field_name('category')?>" value="<?php echo esc_attr($category)?>" /></p>
				<p><?php _e("Número productos:", "imacPrestashop");?> <input type="text" class="widefat" name="<?php echo $this->get_field_name('n_products')?>" value="<?php echo esc_attr($n_products)?>" /></p>
				<p><?php _e("ID idioma:", "imacPrestashop");?> <input type="text" class="widefat" name="<?php echo $this->get_field_name('idioma')?>" value="<?php echo esc_attr($idioma)?>" /></p>
			<?php
		}
		
		function update($new_instance,$old_instance){
			global $file_prefix;
	    if ( function_exists( 'wp_cache_clean_cache' ) ) wp_cache_clean_cache( $file_prefix );
	    
			$instance=$old_instance;
			$instance['category']=sanitize_text_field($new_instance['category']);
			$instance['n_products']=sanitize_text_field($new_instance['n_products']);
			$instance['title']=sanitize_text_field($new_instance['title']);
			$instance['idioma']=sanitize_text_field($new_instance['idioma']);
			
			return $instance;
		}
		
		function widget($args, $instance){
			extract($args);
			
			echo $before_widget;					
			global $file_prefix;
	    if ( function_exists( 'wp_cache_clean_cache' ) ) wp_cache_clean_cache( $file_prefix );
													
			$imacPrestashop_options=get_option('imacPrestashop_options');	
						
			$categoria_base=(!empty($instance['category']) ? $instance['category'] : esc_attr($imacPrestashop_options['option_categoria']));
			$n_products=(!empty($instance['n_products']) ? $instance['n_products'] : 6);
			$title=$instance['title'];			
			$idioma=(!empty($instance['idioma']) ? $instance['idioma'] : 1);
			
			$desencriptar_host=imacPrestashop_encriptacion::desencriptar($imacPrestashop_options['option_localhost']);						
			$desencriptar_nombre=imacPrestashop_encriptacion::desencriptar($imacPrestashop_options['option_name']);						
			$desencriptar_user=imacPrestashop_encriptacion::desencriptar($imacPrestashop_options['option_user']);												
			$desencriptar_pass=imacPrestashop_encriptacion::desencriptar($imacPrestashop_options['option_pass']);		
			$desencriptar_prefijo=imacPrestashop_encriptacion::desencriptar($imacPrestashop_options['option_prefijo']);
							
			$db_host=$desencriptar_host;						
			$db_nombre=$desencriptar_nombre;						
			$db_user=$desencriptar_user;						
			$db_pass=$desencriptar_pass;						
			$prefijo=$desencriptar_prefijo;			
			$url_tienda=esc_attr($imacPrestashop_options['option_url']);   
			$url_base=(isset($imacPrestashop_options['option_urlP']) ? $imacPrestashop_options['option_urlP'] : 0); 	
			$nofollow=$imacPrestashop_options['option_nofollow'];
			$ofertas=$imacPrestashop_options['option_ofertas'];	
			$txt_nofollow='';
			if ($nofollow==1){
				$txt_nofollow='rel="nofollow"';	
			}
													
			$link=mysqli_connect($db_host, $db_user, $db_pass, $db_nombre);			
			if (!$link) {			    
			    exit;
			}			
			mysqli_select_db($link, $db_nombre) or die("Error seleccionando la base de datos.");
			
			
			if ( !empty($title) ){echo $before_title.esc_html($title).$after_title;}
			
			$cant_productos=$n_products; 
			$id_idioma=$idioma;
			$categoria=$categoria_base;
		
			$images=$prefijo."image";
			$product=$prefijo."product";
			$prodyct_lang=$prefijo."product_lang";
			$category_product=$prefijo."category_product";
			$product_attribute=$prefijo."product_attribute";
			$category_lang=$prefijo."category_lang";
			$image_lang=$prefijo."image_lang";
			$tax_rule=$prefijo."tax_rule";
			$tax=$prefijo."tax";
			$specific_price=$prefijo."specific_price";	
			$stock_available=$prefijo."stock_available";					
			$language=$prefijo."lang";
						
			$sqllangCount="select * from $language where active=1";	
			$consultalangCount = mysqli_query($link, $sqllangCount);		
			$total_resultadoslang = mysqli_num_rows($consultalangCount);						
			
			$sqllang="select * from $language where id_lang='$id_idioma'";			
			$consultalang = mysqli_query($link, $sqllang);				
			$registrosqllang=mysqli_fetch_array($consultalang);										
			$lang_txt='';
			if ($total_resultadoslang>1)$lang_txt=$registrosqllang['iso_code']."/";	
				
			$sql ="
			SELECT p.*, pa.id_product_attribute, pl.description, pl.description_short, pl.available_now, pl.available_later, pl.link_rewrite, pl.meta_description,
		 	pl.meta_keywords, pl.meta_title, pl.name, i.id_image, il.legend, 	p.price as precio_base,
		  ROUND(p.price * (COALESCE(ptx.rate, 0) / 100 + 1), 2) AS 'regular_price', ptx.rate as iva,
		  IF(pr.reduction_type = 'amount', pr.reduction, '') AS 'Discount_amount', IF(pr.reduction_type = 'percentage', pr.reduction, '') AS 'Discount_percentage', pr.reduction_tax as reduction_offer, s.quantity
		  FROM $category_product cp 
		  LEFT JOIN $product p ON p.id_product = cp.id_product 
		  LEFT JOIN $product_attribute pa ON (p.id_product = pa.id_product AND default_on = 1) 
		  LEFT JOIN $category_lang cl ON (p.id_category_default = cl.id_category AND cl.id_lang = '.$id_idioma.') 
		  LEFT JOIN $specific_price pr ON(p.id_product = pr.id_product)
		  LEFT JOIN $prodyct_lang pl ON (p.id_product = pl.id_product AND pl.id_lang = '.$id_idioma.') 
		  LEFT JOIN $images i ON (i.id_product = p.id_product AND i.cover = 1) 
		  LEFT JOIN $image_lang il ON (i.id_image = il.id_image AND il.id_lang = '.$id_idioma.')   
		  LEFT JOIN $tax_rule ptxgrp ON ptxgrp.id_tax_rules_group = p.id_tax_rules_group
			LEFT JOIN $tax ptx ON ptx.id_tax = ptxgrp.id_tax 
			LEFT JOIN $stock_available s ON (p.id_product = s.id_product)       
		  WHERE cp.id_category IN ($categoria) AND p.active = 1 AND s.quantity>0
		  GROUP BY cp.id_product
		  order by rand() LIMIT $cant_productos
			";								
			$consulta = mysqli_query($link, $sql);		
			$total_resultados = mysqli_num_rows($consulta);		
			$content='<ul class="short-products2 widget-title">';
			if ($total_resultados>0)
			{		
				$i = 1;
				while ($row = $consulta->fetch_object())
				{				
					$resto = ($i % 3);
					$sqlobtener="select name, link_rewrite from $prodyct_lang where id_product='$row->id_product' and id_lang='$id_idioma'";			
					$consutlasql=mysqli_query($link, $sqlobtener);
					$registrosql=mysqli_fetch_array($consutlasql);			
									
					$content.="<li>";
					if ($url_base==0){
						$content.="<a ".$txt_nofollow." title='".utf8_encode($registrosql['name'])."' href='".$url_tienda.$lang_txt.$row->id_product."-".$registrosql['link_rewrite'].".html' target='blank_'>";								
					}else{
						$content.="<a ".$txt_nofollow." title='".utf8_encode($registrosql['name'])."' href='".$url_tienda.$lang_txt.$registrosql['link_rewrite']."-".$row->id_product.".html' target='blank_'>";								
					}
					$content.="<img style='border-color:#F90' src='".$url_tienda.$row->id_image."-home_default/".$registrosql['link_rewrite'].".jpg' alt='Imagén: ".utf8_encode($registrosql['name'])."' /></a>";		
					if ($url_base==0){
						$content.="<br><a ".$txt_nofollow." class='product_name' title='".utf8_encode($registrosql['name'])."' href='".$url_tienda.$lang_txt.$row->id_product."-".$registrosql['link_rewrite'].".html' target='blank_'>";
					}else{
						$content.="<br><a ".$txt_nofollow." class='product_name' title='".utf8_encode($registrosql['name'])."' href='".$url_tienda.$lang_txt.$registrosql['link_rewrite']."-".$row->id_product.".html' target='blank_'>";
					}
					$content.=utf8_encode($registrosql['name']);								
					$content.="</a><br>";
									
					$precio=$row->regular_price;
					$preu = number_format($precio, 2, ',', '');	
					$discount_iva=0;					
					if ($row->reduction_offer==1){										
						$Discount_amount =$row->Discount_amount;		
						$Discount_percentage =$row->Discount_percentage;
						if ($Discount_amount!=''){		
							$preu_dto=number_format($precio-$Discount_amount, 2, ',', '');		
						}
						if ($Discount_percentage!=''){		
							$preu_dto=number_format($precio-($precio*$Discount_percentage), 2, ',', '');		
						}					
					}else{					
						$Discount_amount = $row->Discount_amount;					
						if ($Discount_amount!=''){
							$discount_iva =(($Discount_amount * 21)/100);			
							$Discount_amount=$Discount_amount+$discount_iva;				
							$preu_dto=number_format($precio-$Discount_amount, 2, ',', '');		
						}
						$Discount_percentage =$row->Discount_percentage;
						if ($Discount_percentage!=''){		
							$discount_iva =(($Discount_percentage * 21)/100);			
							$Discount_percentage=$Discount_percentage+$discount_iva;
							$preu_dto=number_format($precio-($precio*$Discount_percentage), 2, ',', '');		
						}	
					}			
					if ($ofertas==1){
						$content.="<span class='price'>".$preu." €</span><br>";
					}else{		
						if (($Discount_amount!=0) || ($Discount_percentage!=0)){
							if ($Discount_percentage!=0){
								$content.="<span class='offer'>".$preu."</span><span class='price'>".$preu_dto." €</span> <span class='dto'>(".number_format($Discount_percentage*100, 2, ',', '')." %)</span><br>";
							}else{
								$content.="<span class='offer'>".$preu."</span><span class='price'>".$preu_dto." €</span> <span class='dto'>(- ".number_format($Discount_amount, 2, ',', '')." €)</span><br>";
							}
						}else{
							$content.="<span class='price'>".$preu." €</span><br>";
						}	
					}				
					$content.="</li>";
						
					$i++;
				}	
					$content.="</ul>";
				
			}else{
				$content="";
			}			
		
			echo $content;
			
			echo $after_widget;
		}
	}	
	
	class imacPrestashop_encriptacion{				
		public static function encriptar($cadena){	
			global $wpdb;
			$key ='asasest&A2oeds3-asdwas23'.$wpdb->base_prefix.'Acunt#33ddasd_asextod2Dseprueba31';		
			$iv = '12as16as78as12as';			
	    $encrypted = openssl_encrypt($cadena,'AES-256-CBC',$key,0,$iv); 
	    return $encrypted; 
		} 
		public static function desencriptar($cadena){  		
			global $wpdb;
			$key ='asasest&A2oeds3-asdwas23'.$wpdb->base_prefix.'Acunt#33ddasd_asextod2Dseprueba31';	
			$iv = '12as16as78as12as';	   	
	   	$decrypted = openssl_decrypt($cadena,'AES-256-CBC',$key,0,$iv); 	
	    return $decrypted;
		}
	}
?>