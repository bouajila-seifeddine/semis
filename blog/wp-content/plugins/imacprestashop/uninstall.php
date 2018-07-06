<?php
if (!defined('ABSPATH') && !defined('WP_UNINSTALL_PLUGIN')) exit();
//eliminar base de datos
global $wpdb;
//Borrar tabla de mysql
$table_name=$wpdb->prefix.'datos_privados';
$wpdb->query( "DROP TABLE $table_name");

delete_option('imacPrestashop_options');
?>