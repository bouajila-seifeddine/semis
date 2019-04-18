<?php

/**
 * Export data
 *
 */

function wpcpc_exporter( $email_address, $page = 1 ) {
    $number = 500; // Limit us to avoid timing out
    $page = (int) $page;
    
    $export_items = array();
    
    $comments = get_comments(
        array(
            'author_email' => $email_address,
            'number' => $number,
            'paged' => $page,
            'order_by' => 'comment_ID',
            'order' => 'ASC',
        )
    );

    foreach ( (array) $comments as $comment ) {
        $data_consent = get_comment_meta( $comment->comment_ID, 'wpcpc_private_policy_accepted', true );

        // Only add data consent to the export if it is not empty
        if ( ! empty( $data_consent ) ) {
            // Most item IDs should look like postType-postID
            // If you don't have a post, comment or other ID to work with,
            // use a unique value to avoid having this item's export
            // combined in the final report with other items of the same id
            $item_id = "comment-{$comment->comment_ID}";
            
            // Core group IDs include 'comments', 'posts', etc.
            // But you can add your own group IDs as needed
            $group_id = 'comments';
            
            // Optional group label. Core provides these for core groups.
            // If you define your own group, the first exporter to
            // include a label will be used as the group label in the
            // final exported report
            $group_label = __( 'Comments', 'wp-comment-policy-checkbox' );
            
            // Plugins can add as many items in the item data array as they want
            $data = array(
                array(
                    'name' => __( 'Consent email for personal data storage' ),
                    'value' => $data_consent
                )
            );
            
            $export_items[] = array(
                'group_id' => $group_id,
                'group_label' => $group_label,
                'item_id' => $item_id,
                'data' => $data,
            );
        }
    }
    
    // Tell core if we have more comments to work on still
    $done = count( $comments ) < $number;
    return array(
        'data' => $export_items,
        'done' => $done,
    );
}





function wpcpc_register_exporter( $exporters ) {

    $exporters['wp-comment-policy-checkbox'] = array(
        'exporter_friendly_name' => __( 'WP Comment Policy Checkbox', 'wp-comment-policy-checkbox' ),
        'callback' => 'wpcpc_exporter',
    );

    return $exporters;

}

add_filter(
    'wp_privacy_personal_data_exporters',
    'wpcpc_register_exporter',
    10
);