<?php 

// Register custom post type
function auction_list_register_post_type() {
    register_post_type('auction_list', [
        'labels' => [
            'name' => __('Auction Lists', 'auction-list'),
            'singular_name' => __('Auction List', 'auction-list'),
        ],
        'public' => true,
        'has_archive' => true,
        'show_in_menu' => true,
        'supports' => ['title'],
    ]);
}
add_action('init', 'auction_list_register_post_type');