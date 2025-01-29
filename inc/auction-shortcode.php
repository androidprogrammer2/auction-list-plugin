<?php 

// Add shortcode column to post type list
function auction_list_add_shortcode_column($columns) {
    $columns['shortcode'] = __('Shortcode', 'auction-list');
    return $columns;
}
add_filter('manage_auction_list_posts_columns', 'auction_list_add_shortcode_column');


function auction_list_shortcode_column_content($column, $post_id) {
    if ($column === 'shortcode') {
        echo '<code>[auction_list id="' . $post_id . '"]</code>';
    }
}
add_action('manage_auction_list_posts_custom_column', 'auction_list_shortcode_column_content', 10, 2);

// Display Shortcode in the Single Auction List Post Backend Page
function auction_list_display_shortcode_on_single_post($post) {
    if ($post->post_type === 'auction_list') {
        $shortcode = '[auction_list id="' . $post->ID . '"]';
        echo '<div style="margin-top: 20px; font-size: 16px; padding: 10px; background-color: #f1f1f1; border-radius: 5px;">
                <strong>Shortcode:</strong> <code>' . esc_html($shortcode) . '</code>
              </div>';
    }
}
add_action('edit_form_after_title', 'auction_list_display_shortcode_on_single_post');



function auction_list_shortcode($atts) {

    $atts = shortcode_atts(['id' => 0], $atts, 'auction_list');
    $post_id = intval($atts['id']);
    $stored_data = get_post_meta($post_id, '_auction_list_data', true);
    $url_sorted_data_active = get_post_meta($post_id, '_auction_list_data', true);
    $url_sorted_data_complete = get_post_meta($post_id, '_auction_list_data_complete', true);
    $selected_option = get_post_meta($post_id, '_auction_list_option', true); 
    
    ob_start();

    if ($selected_option == 'id') {
        if (!empty($stored_data)) {
            $stored_data = maybe_unserialize($stored_data);
            

            if (is_array($stored_data)) {
               
                    include (plugin_dir_path(__FILE__) . '../template-parts/defualt-auction-list-from-id.php');

            } else {
                echo __('Stored data is not valid.', 'auction-list');
            }
        } else {
            echo __('No data available for this auction.', 'auction-list');
        }
    } else {
        if (!empty($url_sorted_data_active)) {
            $url_sorted_data_active = maybe_unserialize($url_sorted_data_active);
            $url_sorted_data_complete = maybe_unserialize($url_sorted_data_complete);

            if (is_array($url_sorted_data_active)) {
                include (plugin_dir_path(__FILE__) . '../template-parts/active-complete-data.php');
            }
        }
    }

    return ob_get_clean();
}
add_shortcode('auction_list', 'auction_list_shortcode');

function format_time_remaining($end_time) {
  
    $current_time = time();

    $remaining_seconds = $end_time - $current_time;

    if ($remaining_seconds <= 0) {
        return "0D, 0H, 0M";
    }

    $days = floor($remaining_seconds / 86400);
    $remaining_seconds %= 86400;

    $hours = floor($remaining_seconds / 3600);
    $remaining_seconds %= 3600;

    $minutes = floor($remaining_seconds / 60);


    return "{$days}D, {$hours}H, {$minutes}M";
}
