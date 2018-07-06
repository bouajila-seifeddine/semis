<?php
/** 
 * Configuración básica de WordPress.
 *
 * Este archivo contiene las siguientes configuraciones: ajustes de MySQL, prefijo de tablas,
 * claves secretas, idioma de WordPress y ABSPATH. Para obtener más información,
 * visita la página del Codex{@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} . Los ajustes de MySQL te los proporcionará tu proveedor de alojamiento web.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */
// ** Ajustes de MySQL. Solicita estos datos a tu proveedor de alojamiento web. ** //
/** El nombre de tu base de datos de WordPress */
define('DB_NAME', 'slc_wp');
/** Tu nombre de usuario de MySQL */
define('DB_USER', 'slc_user_wp');
/** Tu contraseña de MySQL */
define('DB_PASSWORD', 'sGzaLTYXWcf6HM792582nNM');
/** Host de MySQL (es muy probable que no necesites cambiarlo) */
define('DB_HOST', '213.162.211.156');
/** Codificación de caracteres para la base de datos. */
define('DB_CHARSET', 'utf8mb4');
/** Cotejamiento de la base de datos. No lo modifiques si tienes dudas. */
define('DB_COLLATE', '');
/**#@+
 * Claves únicas de autentificación.
 *
 * Define cada clave secreta con una frase aleatoria distinta.
 * Puedes generarlas usando el {@link https://api.wordpress.org/secret-key/1.1/salt/ servicio de claves secretas de WordPress}
 * Puedes cambiar las claves en cualquier momento para invalidar todas las cookies existentes. Esto forzará a todos los usuarios a volver a hacer login.
 *
 * @since 2.6.0
 */
define('AUTH_KEY', '10dV]i}3`P@ ITS[X8XMH5rk*;:Y#{6>YGnaFsi>o~I[[CyJfac|`v!t-+J)(g*J');
define('SECURE_AUTH_KEY', '{8*>w83LW|M<xKr !fr^gc=8ePqw;#C xv9ShPSBTK9MFH^ .`&X|}bpHr8eIRbh');
define('LOGGED_IN_KEY', 'Uwr,-?9|-}htdJa8AnO]XA^DrIO[7o?F{edQKHExEtY{m^zH@,W?;E{DO:Z$j8tC');
define('NONCE_KEY', 'iSV5}&c8>yDzB!<&M9$<0|N#Ysf(XzMh>Le[N6vSCJ3Ns{#2~i3nXGzYUiq{N;>)');
define('AUTH_SALT', ';(AVHwUCDyzYU37ktPmXy0Vq`=N[-k`$ZpJIhiFg +Wp>/rjOtj>Yx:|o7oCG dY');
define('SECURE_AUTH_SALT', ' @J]n>h}gIA7Ru8_?Q^SCBk&Ka*|#5s3M:,<Kep#R3!o@5wDeoK@{53NY;=.^j-2');
define('LOGGED_IN_SALT', '6+7P/MEzAv5#MKeHI4l6||l6v60ECa4#QG>kC /]tl5S,7!!?V?J.XLR|lmYx-XR');
define('NONCE_SALT', 'qJ/c1ySb_hU~@I{4^Ob49x|!Kgp [~ALa@]C|mYa|$-mPi1seha3T&}EW{?jBzPq');
/**#@-*/
/**
 * Prefijo de la base de datos de WordPress.
 *
 * Cambia el prefijo si deseas instalar multiples blogs en una sola base de datos.
 * Emplea solo números, letras y guión bajo.
 */
$table_prefix  = 'wp_';
/**
 * Para desarrolladores: modo debug de WordPress.
 *
 * Cambia esto a true para activar la muestra de avisos durante el desarrollo.
 * Se recomienda encarecidamente a los desarrolladores de temas y plugins que usen WP_DEBUG
 * en sus entornos de desarrollo.
 */
define('WP_DEBUG', false);
/* ¡Eso es todo, deja de editar! Feliz blogging */
/** WordPress absolute path to the Wordpress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');
/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
