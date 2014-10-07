<?php
/*
 * EVENTS
 */

// 1. Custom Post Type Registration (Events)

add_action( 'init', 'create_event_postype' );

function create_event_postype() {

$labels = array(
    'name' => _x('Events', 'post type general name'),
    'singular_name' => _x('Event', 'post type singular name'),
    'add_new' => _x('Add New', 'events'),
    'add_new_item' => __('Add New Event'),
    'edit_item' => __('Edit Event'),
    'new_item' => __('New Event'),
    'view_item' => __('View Event'),
    'search_items' => __('Search Events'),
    'not_found' =>  __('No events found'),
    'not_found_in_trash' => __('No events found in Trash'),
    'parent_item_colon' => '',
);

$args = array(
    'label' => __('Events'),
    'labels' => $labels,
    'public' => true,
    'has_archive' => 'events',
    'can_export' => true,
    'show_ui' => true,
    '_builtin' => false,
    '_edit_link' => 'post.php?post=%d', // ?
    'capability_type' => 'post',
    'menu_icon' => 'dashicons-calendar',
    'hierarchical' => false,
    'rewrite' => array( "slug" => "events" ),
    'supports'=> array('title', 'thumbnail', 'excerpt', 'editor') ,
    'show_in_nav_menus' => true,
    'taxonomies' => array( 'eventcategory', 'post_tag')
);

register_post_type( 'events', $args);

}

// 2. Custom Taxonomy Registration (Event Types)

function create_eventcategory_taxonomy() {

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

    register_taxonomy('eventcategory','events', array(
        'label' => __('Event Category'),
        'labels' => $labels,
        'hierarchical' => true,
        'show_ui' => true,
        'query_var' => true,
        'rewrite' => array( 'slug' => 'event-category' ),
    ));

}

add_action( 'init', 'create_eventcategory_taxonomy', 0 );

function ep_eventposts_metaboxes() {
    add_meta_box( 'daltons_event_date_start', 'Start Date and Time', 'daltons_event_date', 'events', 'side', 'default', array( 'id' => '_start') );
    add_meta_box( 'daltons_event_date_end', 'End Date and Time', 'daltons_event_date', 'events', 'side', 'default', array('id'=>'_end') );
    add_meta_box( 'daltons_event_location', 'Event Location', 'daltons_event_location', 'events', 'side', 'default', array('id'=>'_end') );
}

add_action( 'admin_init', 'ep_eventposts_metaboxes' );

// Metabox
function daltons_event_date($post, $args) {
    $metabox_id = $args['args']['id'];
    global $post, $wp_locale;
    wp_nonce_field( plugin_basename( __FILE__ ), 'daltons_eventposts_nonce' );
    $time_adj = current_time( 'timestamp' );
    $month = get_post_meta( $post->ID, $metabox_id . '_month', true );
    if ( empty( $month ) ) {
        $month = gmdate( 'm', $time_adj );
    }
    $day = get_post_meta( $post->ID, $metabox_id . '_day', true );
    if ( empty( $day ) ) {
        $day = gmdate( 'd', $time_adj );
    }
    $year = get_post_meta( $post->ID, $metabox_id . '_year', true );
    if ( empty( $year ) ) {
        $year = gmdate( 'Y', $time_adj );
    }
    $hour = get_post_meta($post->ID, $metabox_id . '_hour', true);
    if ( empty($hour) ) {
        $hour = gmdate( 'H', $time_adj );
    }
    $min = get_post_meta($post->ID, $metabox_id . '_minute', true);
    if ( empty($min) ) {
        $min = '00';
    }
    $month_s = '<select name="' . $metabox_id . '_month">';
    for ( $i = 1; $i < 13; $i = $i +1 ) {
        $month_s .= "\t\t\t" . '<option value="' . zeroise( $i, 2 ) . '"';
        if ( $i == $month )
            $month_s .= ' selected="selected"';
        $month_s .= '>' . $wp_locale->get_month_abbrev( $wp_locale->get_month( $i ) ) . "</option>\n";
    }
    $month_s .= '</select>';
    echo $month_s;
    echo '<input type="text" name="' . $metabox_id . '_day" value="' . $day  . '" size="2" maxlength="2" />';
    echo '<input type="text" name="' . $metabox_id . '_year" value="' . $year . '" size="4" maxlength="4" /> @ ';
    echo '<input type="text" name="' . $metabox_id . '_hour" value="' . $hour . '" size="2" maxlength="2"/>:';
    echo '<input type="text" name="' . $metabox_id . '_minute" value="' . $min . '" size="2" maxlength="2" />';
}

function daltons_event_location() {
    global $post;
    wp_nonce_field( plugin_basename( __FILE__ ), 'daltons_eventposts_nonce' );
    $event_location = get_post_meta( $post->ID, '_event_location', true );
    echo '<label for="_event_location">Location:</label>';
    echo '<input type="text" name="_event_location" value="' . $event_location  . '" />';
}

// Save the Metabox Data
function daltons_eventposts_save_meta( $post_id, $post ) {
    $metabox_ids = array( '_start', '_end' );
    foreach ($metabox_ids as $key ) {
        if (isset($_POST[$key . '_month'])) { 
            $events_meta[$key . '_month'] = $_POST[$key . '_month'];
            $events_meta[$key . '_day'] = $_POST[$key . '_day'];
            if($_POST[$key . '_hour']<10){
                $events_meta[$key . '_hour'] = '0'.$_POST[$key . '_hour'];
            } else {
                $events_meta[$key . '_hour'] = $_POST[$key . '_hour'];
            }
            $events_meta[$key . '_year'] = $_POST[$key . '_year'];
            $events_meta[$key . '_hour'] = $_POST[$key . '_hour'];
            $events_meta[$key . '_minute'] = $_POST[$key . '_minute'];
            $events_meta[$key . '_eventtimestamp'] = $events_meta[$key . '_year'] . $events_meta[$key . '_month'] . $events_meta[$key . '_day'] . $events_meta[$key . '_hour'] . $events_meta[$key . '_minute'];
        }
    }
    if (isset($events_meta)) {
        foreach ( $events_meta as $key => $value ) { 
            if ( $post->post_type == 'revision' ) return; 
             $value = implode( ',', (array)$value ); 
            if ( get_post_meta( $post->ID, $key, FALSE ) ) { 
                update_post_meta( $post->ID, $key, $value );
            } else { 
                add_post_meta( $post->ID, $key, $value );
            }
            if ( !$value ) delete_post_meta( $post->ID, $key );
        }
    }
    if( isset($_POST['_event_location']) ){
        update_post_meta( $post_id, '_event_location', $_POST['_event_location'] );
    }
}
add_action( 'save_post', 'daltons_eventposts_save_meta', 1, 2 );

/**
 * Display the date on the front end
 */
// Get the Month Abbreviation
function eventposttype_get_the_month_abbr($month) {
    global $wp_locale;
    if (isset($month)  && $month != '') {
        for ( $i = 1; $i < 13; $i = $i +1 ) {
                    if ( $i == $month )
                        $monthabbr = $wp_locale->get_month_abbrev( $wp_locale->get_month( $i ) );
                    }
        return $monthabbr;
    }
}
// Display the date
function eventposttype_get_the_event_date($event) {
    global $post;
    $eventdate = '';
    $month = get_post_meta($post->ID, $event.'_month', true);
    $eventdate = eventposttype_get_the_month_abbr($month);
    $eventdate .= ' ' . get_post_meta($post->ID, $event.'_day', true) . ',';
    $eventdate .= ' ' . get_post_meta($post->ID, $event.'_year', true);
    $eventdate .= ' at ' . get_post_meta($post->ID, $event.'_hour', true);
    $eventdate .= ':' . get_post_meta($post->ID, $event.'_minute', true);
    echo $eventdate;
}

// Show Columns

add_filter ("manage_edit-events_columns", "daltons_events_edit_columns");
add_action ("manage_posts_custom_column", "daltons_events_custom_columns");

function daltons_events_edit_columns($columns) {
    $columns = array(
        "cb" => "<input type=\"checkbox\" />",
        "daltons_col_ev_cat" => "Category",
        "daltons_col_ev_loc" => "Location",
        "daltons_col_ev_date" => "Date/Time",
        "title" => "Event",
        "daltons_col_ev_desc" => "Description",
        );
    return $columns;
}

function daltons_events_custom_columns($column) {
    global $post;
    switch ($column)
        {
            case "daltons_col_ev_cat":
                // - show taxonomy terms -
                $eventcats = get_the_terms($post->ID, "daltons_eventcategory");
                $eventcats_html = array();
                if ($eventcats) {
                    foreach ($eventcats as $eventcat)
                    array_push($eventcats_html, $eventcat->name);
                    echo implode($eventcats_html, ", ");
                } else {
                _e('None', 'themeforce');;
                }
            break;
            case "daltons_col_ev_loc":
                $event_location = get_post_meta( $post->ID, '_event_location', true );
                echo $event_location;
            break;
            case "daltons_col_ev_date":
                eventposttype_get_the_event_date('_start');
                echo '<br><em>';
                eventposttype_get_the_event_date('_end');
                echo '</em>';            
            break;
            case "daltons_col_ev_desc";
                the_excerpt();
            break;

        }
}
