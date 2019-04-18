<?php

/**
 * Register settings
 *
 */
function wpcpc_register_settings() {

    register_setting (
        'discussion',
        'wpcpc_policy_page_id'
    );

    register_setting (
        'discussion',
        'wpcpc_policy_top_copy'
    );

}

add_action( 'admin_init', 'wpcpc_register_settings' );


/**
 * Creating setting field
 *
 */
function wpcpc_add_setting_fields () {

    add_settings_field (
        'wpcpc_settings_policy_page_id',
        __( 'Comments policy checkbox', 'wp-comment-policy-checkbox'),
        'wpcpc_settings_policy_page_id_callback',
        'discussion'
    );

    add_settings_field (
        'wpcpc_settings_policy_top_copy',
        __( 'Comments policy basic information', 'wp-comment-policy-checkbox'),
        'wpcpc_settings_top_copy_callback',
        'discussion'
    );

}

add_action('admin_init','wpcpc_add_setting_fields');


/**
 * Rendering option page
 *
 */
function wpcpc_settings_policy_page_id_callback() { ?>

    <div class='wrap'>

        <p> <?php esc_html_e( 'The check box for reading and accepting the privacy policy in the comment forms is active. To deactivate it you must deactivate the WP Comment Policy Check plugin.', 'wp-comment-policy-checkbox') ?> </p>

        <p>
            <?php esc_html_e( 'The page with the privacy policy to which you will link the text of the checkbox will be: ', 'wp-comment-policy-checkbox') ?>

            <select name='wpcpc_policy_page_id'>

                <?php $empty_option_value = __( '-- none --', 'wp-comment-policy-checkbox' ); ?>

                <option> <?php echo esc_attr( $empty_option_value ) ?> </option>

                <?php $pages = get_pages( array( 'lang' => '' ) ); ?>
                <?php if ( $pages ) { ?>
                    <?php foreach ( $pages as $page ) { ?>
                        <option
                            value='<?php echo esc_attr( $page -> ID ); ?>'
                            <?php selected( get_option( 'wpcpc_policy_page_id' ), $page -> ID ); ?>>
                            <?php echo esc_attr( $page -> post_title ); ?>
                        </option>
                    <?php } ?>
                <?php } ?>

            </select>
        </p>

    </div>

<?php }

function wpcpc_settings_top_copy_callback() { ?>

    <div class='wrap'>
        
        <p>
            <?php esc_html_e( 'Write down the basic information about data protection.', 'wp-comment-policy-checkbox') ?>
            <br>
            <?php esc_html_e( 'This information will appear above the check box of the privacy policy.', 'wp-comment-policy-checkbox' ) ?>
        </p>

            <?php 
            $content   = get_option( 'wpcpc_policy_top_copy' );
            $editor_id = 'wpcpcpolicytopcopy';
            $settings = [
                'textarea_name' => 'wpcpc_policy_top_copy',
                'media_buttons' => false,
                'teeny' => true,
                'textarea_rows' => 10
            ]; 
            wp_editor( $content, $editor_id, $settings );                
            ?>
                
    </div>

<?php }

