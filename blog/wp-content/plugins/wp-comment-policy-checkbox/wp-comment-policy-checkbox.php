<?php

/**
 * WP Comment Policy Checkbox bootstrap file
 *
 * @wordpress-plugin
 * Plugin Name:       WP Comment Policy Checkbox
 * Plugin URI:        https://github.com/fcojgodoy/wp-comment-policy-checkbox
 * Description:       Add a checkbox and custom text to the comment forms so that the user can be informed and give consent to the web's privacy policy. And save this consent in the database.
 * Version:           0.3.1
 * Author:            Fco. J. Godoy
 * Author URI:        franciscogodoy.es
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wp-comment-policy-checkbox
 * Domain Path:       /languages
 */


// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}


/**
 * Admin
 *
 */
if ( is_admin() ) {
    // We are in admin mode
    require_once ( plugin_dir_path ( __FILE__ ) . 'includes/wp-comment-policy-checkbox-admin.php' );
}


/**
 * Personal data exporter and eraser
 *
 */
    require_once ( plugin_dir_path ( __FILE__ ) . 'includes/wp-comment-policy-checkbox-data-exporter.php' );
    require_once ( plugin_dir_path ( __FILE__ ) . 'includes/wp-comment-policy-checkbox-data-eraser.php' );


/**
 * Load the plugin text domain for translation.
 *
 */
function wpcpc_load_plugin_textdomain() {

	load_plugin_textdomain(
		'wp-comment-policy-checkbox',
		false,
		basename( dirname( __FILE__ ) ) . '/languages/'
	);

}

add_action( 'plugins_loaded', 'wpcpc_load_plugin_textdomain' );


/**
 * Create custom fields
 *
 */
function wpcpc_custom_fields( $fields ) {

	if ( get_option( 'wpcpc_external_policy_page' ) ) {
		$url = get_option( 'wpcpc_external_policy_page' );
	} else {
		$url = get_permalink( get_option( 'wpcpc_policy_page_id' ) );
	}

	$privacy_policy = __( 'Privacy Policy', 'wp-comment-policy-checkbox' );
	$link = '<a href="https://www.semillaslowcost.com/content/11-politica-de-privacidad" target="_blank" class="comment-form-policy__see-more-link">' . esc_html( $privacy_policy ) . '</a>';

    $fields[ 'top_copy' ] =
        '<div role="note" class="comment-form-policy-top-copy" style="font-size:80%">'.
            wpautop( get_option( 'wpcpc_policy_top_copy' ) ) .
        '</div>';

    $fields[ 'policy' ] =
        '<p class="comment-form-policy">
            <label for="policy" style="display:block !important">
                <input id="policy" name="policy" value="policy-key" class="comment-form-policy__input" type="checkbox" style="width:auto; margin-right:7px;" aria-required="true">' .
					sprintf(
						/* translators: %s: Privacy Policy page link */
						__( 'I have read and accepted the %s', 'wp-comment-policy-checkbox' ),
						$link
					) .
                '<span class="comment-form-policy__required required"> *</span>
            </label>
        </p><br> <p class="legal-text-comments">Ecommerce and Quality Trading, S.L.U, como responsable del Tratamiento recabará los datos en el presente formulario para proceder a mostrar su comentario en el post del Blog seleccionado cuya base jurídica es su consentimiento expreso del artículo 6.1.a RGPD. Sus datos serán conservados durante el tiempo necesario para cumplir con la finalidad descrita.</p> <p class="legal-text-comments">En cualquier momento usted podrá ejercitar sus derechos de acceso, rectificación, supresión, limitación del tratamiento, portabilidad de datos u oposición, incluida la oposición a decisiones individuales automatizadas dirigiéndose a la dirección postal: P.I. La Figuera / Camino Bovalar nº 29 - 46970 - Valencia – VALENCIA o al correo electrónico: info@semillaslowcost.com. También puede acudir ante la Agencia Española de Protección de Datos.</p>';

    return $fields;
}

add_filter('comment_form_default_fields', 'wpcpc_custom_fields');


/**
 * Add comment meta for each comment with checkbox approved
 *
 */
function wpcpc_add_custom_comment_field( $comment_id ) {
	add_comment_meta( $comment_id, 'wpcpc_private_policy_accepted', $_POST[ 'email' ], true );
}

add_action( 'comment_post', 'wpcpc_add_custom_comment_field' );


/**
 * Add the filter to check whether the comment meta data has been filled
 *
 */
function wpcpc_verify_policy_check( $policydata ) {
    if ( ! isset( $_POST['policy'] ) && ! is_user_logged_in() )

    	wp_die( '<strong>' . __( 'WARNING: ' ) . '</strong>' . __( 'you must accept the Privacy Policy.', 'wp-comment-policy-checkbox' ) . '<p><a href="javascript:history.back()">' . __( '&laquo; Back' ) . '</a></p>');

    return $policydata;
}

add_filter( 'preprocess_comment', 'wpcpc_verify_policy_check' );
