<?php
/*
 * Inventory
 */

// 1. Custom Post Type Registration (Inventory)

add_action( 'init', 'create_inventory_postype' );

function create_inventory_postype() {

$labels = array(
    'name' => _x('Inventory', 'post type general name'),
    'singular_name' => _x('Inventory', 'post type singular name'),
    'add_new' => _x('Add New', 'Inventory'),
    'add_new_item' => __('Add New Inventory Item'),
    'edit_item' => __('Edit Inventory Item'),
    'new_item' => __('New Inventory Item'),
    'view_item' => __('View Inventory Item'),
    'search_items' => __('Search Inventory Item'),
    'not_found' =>  __('No Inventory Item found'),
    'not_found_in_trash' => __('No Inventory Item found in Trash'),
    'parent_item_colon' => '',
);

$args = array(
    'label' => __('Inventory'),
    'labels' => $labels,
    'public' => true,
    'has_archive' => 'inventory',
    'can_export' => true,
    'show_ui' => true,
    '_builtin' => false,
    '_edit_link' => 'post.php?post=%d', // ?
    'capability_type' => 'post',
    'menu_icon' => 'dashicons-cart',
    'hierarchical' => false,
    'rewrite' => array( "slug" => "inventory" ),
    'supports'=> array('title', 'editor') ,
    'show_in_nav_menus' => true,
    'taxonomies' => array( 'inventorycategory')
);

register_post_type( 'inventory', $args);

}

// 2. Custom Taxonomy Registration

function create_inventorycategory_taxonomy() {

    $labels = array(
        'name' => _x( 'Categories', 'taxonomy general name' ),
        'singular_name' => _x( 'Category', 'taxonomy singular name' ),
        'search_items' =>  __( 'Search Categories' ),
        'popular_items' => __( 'Popular Categories' ),
        'all_items' => __( 'All Categories' ),
        'parent_item' => null,
        'parent_item_colon' => null,
        'edit_item' => __( 'Edit Category' ),
        'update_item' => __( 'Update Category' ),
        'add_new_item' => __( 'Add New Category' ),
        'new_item_name' => __( 'New Category Name' ),
        'separate_items_with_commas' => __( 'Separate categories with commas' ),
        'add_or_remove_items' => __( 'Add or remove categories' ),
        'choose_from_most_used' => __( 'Choose from the most used categories' ),
    );

    register_taxonomy('inventorycategory','inventory', array(
        'label' => __('Inventory Category'),
        'labels' => $labels,
        'hierarchical' => true,
        'show_ui' => true,
        'query_var' => true,
        'rewrite' => array( 'slug' => 'inventory-category' ),
    ));

}

add_action( 'init', 'create_inventorycategory_taxonomy', 0 );
