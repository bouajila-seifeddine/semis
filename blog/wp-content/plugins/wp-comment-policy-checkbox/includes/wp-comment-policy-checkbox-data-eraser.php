<?php

/**
 * Erase data
 *
 */

function wpcpc_eraser( $email_address, $page = 1 ) {
    $number = 500; // Limit us to avoid timing out
    $page = (int) $page;

    $comments = get_comments(
        array(
            'author_email' => $email_address,
            'number' => $number,
            'paged' => $page,
            'order_by' => 'comment_ID',
            'order' => 'ASC',
        )
    );

    $items_removed = false;

    foreach ( (array) $comments as $comment ) {
        $data_consent  = get_comment_meta( $comment->comment_ID, 'wpcpc_private_policy_accepted', true );

        if ( ! empty( $data_consent ) ) {
            delete_comment_meta( $comment->comment_ID, 'wpcpc_private_policy_accepted' );
            $items_removed = true;
        }

    }

    // Tell core if we have more comments to work on still
    $done = count( $comments ) < $number;

    return array(
        'items_removed' => $items_removed,
        'items_retained' => false, // always false in this case
        'messages' => array(), // no messages in this case
        'done' => $done,
    );
}




function wpcpc_register_eraser( $erasers ) {

    $erasers['wp-comment-policy-checkbox'] = array(
        'eraser_friendly_name' => __( 'WP Comment Policy Checkbox', 'wp-comment-policy-checkbox' ),
        'callback'             => 'wpcpc_eraser',
    );

    return $erasers;

}

add_filter(
    'wp_privacy_personal_data_erasers',
    'wpcpc_register_eraser',
    // Executed with high priority, before the associated comments are anonymized.
    0
);