<?php
/**
 * Theme Functions
 *
 * @package Wordpress
 * @subpackage daltons
 */

include 'admin/events.php';
include 'admin/inventory.php';

/**
 * Enqueue styles
 */
function daltons_styles() {
	wp_enqueue_style( 'bootstrap-css', get_template_directory_uri() . '/css/bootstrap.min.css', array(), '3.1.1' );
    wp_enqueue_style( 'prettyPhoto-css', get_template_directory_uri() . '/css/prettyPhoto.css');
    wp_enqueue_style( 'icons', '//netdna.bootstrapcdn.com/font-awesome/3.2.0/css/font-awesome.css' );
    wp_enqueue_style( 'font', 'http://fonts.googleapis.com/css?family=Lato:100,300,400,700,900,100italic,300italic,400italic,700italic,900italic' );
    wp_enqueue_style( 'daltons-css', get_stylesheet_uri(), array('bootstrap-css'), '1.0' );
}
add_action( 'wp_enqueue_scripts', 'daltons_styles' );

/**
 * Enqueue scripts
 */
function daltons_scripts() {
    wp_enqueue_script( 'jquery' );
    wp_enqueue_script( 'bootstrap-js', get_template_directory_uri() . '/js/bootstrap.min.js', array('jquery'), '3.1.1' );    
    wp_enqueue_script( 'prettyPhoto-js', get_template_directory_uri() . '/js/jquery.prettyPhoto.js', array('jquery') );    
    wp_enqueue_script( 'google-maps', 'http://maps.googleapis.com/maps/api/js?key=AIzaSyDY0kkJiTPVd2U7aTOAwhc9ySH6oHxOIYM&sensor=false', array('jquery'), '3.1.1' );
    wp_enqueue_script( 'main-js', get_template_directory_uri() . '/js/main.js', array('jquery','bootstrap-js') );
}
add_action( 'wp_enqueue_scripts', 'daltons_scripts' );

function daltons_admin_init() {
    wp_enqueue_style( 'dashicons' );
    wp_enqueue_script( 'jquery' );
    wp_enqueue_style('thickbox');
    wp_enqueue_script('media-upload');
    wp_enqueue_script( 'jquery-ui-tabs' );    
    wp_enqueue_style( 'admin-css', get_template_directory_uri() . '/css/admin.css' );
    wp_enqueue_script( 'admin-js', get_template_directory_uri() . '/js/admin.js', array('jquery') );
    wp_enqueue_media();
}
add_action('admin_enqueue_scripts','daltons_admin_init');

function daltons_theme_setup() {
    add_theme_support( 'html5', array('search-form') );
    add_theme_support( 'post-thumbnails' );
    register_nav_menus( array(
        'primary' => __('Primary', 'daltons'),
    ) );
}
add_action( 'after_setup_theme', 'daltons_theme_setup' );

function daltons_admin_menu() {
    add_theme_page('Theme Options', 'Theme Options', 'edit_theme_options', 'daltons-theme-options','daltons_theme_options');
}
add_action('admin_menu', 'daltons_admin_menu');

function daltons_theme_options() { 
    $option = get_option( 'daltons_theme_options' );?>
    <div id="daltons-theme-options-wrap" class="wrap">
        <h2>Theme Options</h2>
        <p><i>From here you can modify different settings for this theme.</i></p>
        <div id="theme-options-frame" class="metabox-holder">
            <section id="branding" class="postbox">
                <h3>Branding</h3>
                <div class="inside">
                    <img id="daltons-logo-src" src="<?php echo isset($option['branding'] ) ? $option['branding']['src'] : ''; ?>"><br>
                    <input type="hidden" id="daltons-logo-id" value="<?php echo isset($option['branding'] ) ? $option['branding']['id'] : ''; ?>">
                    <input type="button" id="daltons-logo-change" value="Set Image">
                    <input type="button" id="daltons-logo-remove" value="Remove Image">
                </div>
            </section>
        </div>
        <div>
            <p class="submit"><input id="save-changes-btn" name="Submit" type="submit" class="button-primary" value="<?php esc_attr_e('Save Changes'); ?>"></p>
            <h2 id="ajax-response-field" style="text-align: left"></h2>
        </div>
    </div>
    <script type="text/javascript">
        jQuery(function($) {
            //handle image edit
            var uploadID = '';
            $(document).on("click", "#daltons-logo-change", function() { // button
                uploadID = "logo";
                formfield = 'add image';
                tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
                return false;
            });

            window.send_to_editor = function(html) {
                var imgurl = $('img',html).attr('src');
                var imgid = $('img', html).attr('class');
                imgid = imgid.replace(/(.*?)wp-image-/, '');
                tb_remove();
                $("#daltons-"+uploadID+"-src").attr( 'src', imgurl ); // image preview
                $("#daltons-"+uploadID+"-id").val(imgid); // hidden id 
            }
            $(document).mouseup(function(e) {
                if ( $("#TP_iframeContent").has(e.target).length === 0 ) {
                    tb_remove();
                }
            });
            $(document).on("click", "#daltons-logo-remove", function() { // button
                $("#daltons-logo-src").attr('src', '');
                $("#daltons-logo-id").val('');
            });
            
            //handle save
            $("#save-changes-btn").click(function() {
                $("#save-changes-btn").val( 'Saving...' );
                //send ajax request to update
                var data = { 
                    action: 'daltons_theme_options_ajax_action',
                    daltons_theme_options: { 
                        branding: { src: $("#daltons-logo-src").attr('src'), id: $("#daltons-logo-id").val() },
                    }
                    
                };
                //console.log(data);
                $.post(ajaxurl, data, function( msg ) 
                {
                    $("#save-changes-btn").val( msg );
                });//enf of .post()
            });
        });
    </script>
<?php }

function daltons_theme_options_ajax_callback() {
    global $wpdb; // this is how you get access to the database
    update_option( 'daltons_theme_options', $_POST['daltons_theme_options'] );

    echo 'Changes Saved'; // save confirmation
    exit(); // this is required to return a proper result
}
add_action( 'wp_ajax_daltons_theme_options_ajax_action', 'daltons_theme_options_ajax_callback' );

function is_edit_page($new_edit = null){
    global $pagenow;
    //make sure we are on the backend
    if (!is_admin()) return false;

    if($new_edit == "edit")
        return in_array( $pagenow, array( 'post.php',  ) );
    elseif($new_edit == "new") //check for new post page
        return in_array( $pagenow, array( 'post-new.php' ) );
    else //check for either new or edit
        return in_array( $pagenow, array( 'post.php', 'post-new.php' ) );
}

function daltons_metabox() {    
    global $post; 
    $type = get_post_type( $post );
    if (is_edit_page('new')){
        add_meta_box('daltons-about-hours-meta', 'Hours', 'daltons_about_hours_meta', 'page', 'side', 'low');
        add_meta_box('daltons-about-contact-meta', 'Contact', 'daltons_about_contact_meta', 'page', 'normal', 'low');
        add_meta_box('daltons-about-slide-meta', 'Slides', 'daltons_about_slide_meta', 'page', 'normal', 'high');
        add_meta_box('daltons-about-slide-meta', 'Slides', 'daltons_about_slide_meta', 'page', 'normal', 'high');
        add_meta_box('daltons-page-post-category-meta', 'Post Category', 'daltons_page_post_category_meta', 'page', 'side', 'low');
    } else {
        if ($type == 'page') :
            $post_id = $_GET['post'] ? $_GET['post'] : $_POST['post_ID'] ;
            $template_file = get_post_meta($post_id,'_wp_page_template',TRUE);
            if ($template_file == 'about.php') {
                add_meta_box('daltons-about-hours-meta', 'Hours', 'daltons_about_hours_meta', 'page', 'side', 'low');
                add_meta_box('daltons-about-contact-meta', 'Contact', 'daltons_about_contact_meta', 'page', 'normal', 'low');
                add_meta_box('daltons-about-slide-meta', 'Slides', 'daltons_about_slide_meta', 'page', 'normal', 'high');
            } elseif ($template_file == 'home-page.php') {
                add_meta_box('daltons-about-slide-meta', 'Slides', 'daltons_about_slide_meta', 'page', 'normal', 'high');
                add_meta_box('daltons-page-post-category-meta', 'Post Category', 'daltons_page_post_category_meta', 'page', 'side', 'low');
            }
        endif;  
    }
    add_meta_box('daltons-inventory-gallery-meta', 'Gallery', 'daltons_inventory_gallery_meta', 'inventory', 'normal', 'high');
    add_meta_box('daltons-inventory-status-meta', 'Status', 'daltons_status_meta', 'inventory', 'normal', 'high');
}
add_action( 'add_meta_boxes', 'daltons_metabox' );

function daltons_about_slide_meta() {
    global $post;
    $slides = get_post_meta($post->ID, 'daltons_about_slides', true); ?>
    <table id="about-slide-table">
        <tbody><?php
            if ( $slides ) :
                foreach ( $slides as $slide ) {
                    $img_src = wp_get_attachment_image_src( $slide['img'] ); ?>
                    <tr class="about-slide">
                        <td>
                            <a class="button remove-slide" href="#">Remove Slide</a><a class="sort"><i class="fa fa-arrows"></i></a>
                            <input type="hidden" name="img[]" value="<?php if($slide['img'] != '') echo esc_attr( $slide['img'] ); ?>" />
                            <input type="button" id="img[]" class="upload-image-button button btn" value="Upload Image" />
                            <div class="preview-container"><img id="slide_image-preview" src="<?php if($slide['img'] != '') echo $img_src[0]; ?>"/></div>
                        </td>
                    </tr><?php
                }
            else : ?>
                <tr class="about-slide">
                    <td>
                        <a class="button remove-slide" href="#">Remove Slide</a><a class="sort"><i class="fa fa-arrows"></i></a>
                        <input type="hidden" name="img[]" placeholder="Image URL" />
                        <input type="button" id="img[]" class="upload-image-button button btn" value="Upload Image" />
                        <div class="preview-container"><img id="slide_image-preview" src="" /></div>
                    </td>
                </tr><?php 
            endif; ?>
            <tr class="empty-row screen-reader-text about-slide">
                <td>
                    <a class="button remove-slide" href="#">Remove Slide</a><a class="sort"><i class="fa fa-arrows"></i></a>
                    <input type="hidden" name="img[]" placeholder="Image URL"/>
                    <input type="button" id="img[]" class="upload-image-button button btn" value="Upload Image" />
                    <div class="preview-container"><img id="slide_image-preview" src="" /></div>
                </td>
            </tr>
        </tbody>
    </table>
    <p>
        <a id="add-slide" class="button" href="#">Add Slide</a>
        <input type="submit" class="metabox_submit button" value="Save" />
    </p><?php
}

function daltons_about_hours_meta() {
    global $post;
    $days = array("Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday");?>
    <table>
        <tbody><?php
            foreach($days as $day) :
                $ohour = get_post_meta($post->ID, 'daltons_hours_o_'.$day, true);
                $chour = get_post_meta($post->ID, 'daltons_hours_c_'.$day, true); ?>
                <tr>
                    <td>
                        <strong><?php echo $day;?></strong>
                    </td>
                    <td>
                        <input id="daltons_hours_o_<?php echo $day; ?>" name="daltons_hours_o_<?php echo $day; ?>" style="width:75px;" value="<?php if(isset($ohour)){ echo $ohour; } ?>" placeholder="00:00" />
                    </td>
                    <td>
                        <input id="daltons_hours_c_<?php echo $day; ?>" name="daltons_hours_c_<?php echo $day; ?>" style="width:75px;" value="<?php if(isset($chour)){ echo $chour; }  ?>" placeholder="00:00" />
                    </td>
                </tr><?php
            endforeach;?>
        <tbody>
    </table><?php
}

function daltons_about_contact_meta() {
    global $post;?>
    <table style="width:100%;">
        <tbody><?php
            $phone = get_post_meta($post->ID, 'daltons_phone', true);
            $fax = get_post_meta($post->ID, 'daltons_fax', true);
            $other = get_post_meta($post->ID, 'daltons_other', true);
            $address =  get_post_meta($post->ID, 'daltons_address', true); ?>
            <tr>
                <td>
                    <strong>Phone</strong>
                </td>
                <td>
                    <input id="daltons_phone" name="daltons_phone" style="width:100%;" value="<?php if(isset($phone)){ echo $phone; } ?>" placeholder="(000)000-0000" />
                </td>
            </tr>
            <tr>
                <td>
                    <strong>Fax</strong>
                </td>
                <td>
                    <input id="daltons_fax" name="daltons_fax" style="width:100%;" value="<?php if(isset($fax)){ echo $fax; } ?>" placeholder="(000)000-0000" />
                </td>
            </tr>
            <tr>
                <td>
                    <strong>Other</strong>
                </td>
                <td>
                    <input id="daltons_other" name="daltons_other" style="width:100%;" value="<?php if(isset($other)){ echo $other; } ?>" placeholder=" " />
                </td>
            </tr>
            <tr>
                <td>
                    <strong>Address</strong>
                </td>
                <td>
                    <input id="daltons_address" name="daltons_address" style="width:100%;" value="<?php if(isset($address)){ echo $address; } ?>" placeholder=" " />
                </td>
            </tr>
        <tbody>
    </table><?php
}

function daltons_inventory_gallery_meta() {
    global $post;
    $gallery = get_post_meta($post->ID, 'daltons_inventory_gallery', true); ?>
    <table id="inventory-gallery-table">
        <tbody><?php
            if ( $gallery ) :
                foreach ( $gallery as $image ) {
                    $img_src = wp_get_attachment_image_src( $image['gal'] ); ?>
                    <tr class="inventory-slide">
                        <td>
                            <a class="button remove-slide" href="#">Remove Image</a><a class="sort"><i class="fa fa-arrows"></i></a>
                            <input type="hidden" name="gal[]" value="<?php if($image['gal'] != '') echo esc_attr( $image['gal'] ); ?>" />
                            <input type="button" id="gal[]" class="upload-image-button button btn" value="Upload Image" />
                            <div class="preview-container"><img id="gallery_image-preview" src="<?php if($image['gal'] != '') echo $img_src[0]; ?>"/></div>
                        </td>
                    </tr><?php
                }
            else : ?>
                <tr class="inventory-slide">
                    <td>
                        <a class="button remove-slide" href="#">Remove Image</a><a class="sort"><i class="fa fa-arrows"></i></a>
                        <input type="hidden" name="gal[]" placeholder="Image URL" />
                        <input type="button" id="gal[]" class="upload-image-button button btn" value="Upload Image" />
                        <div class="preview-container"><img id="gallery_image-preview" src="" /></div>
                    </td>
                </tr><?php 
            endif; ?>
            <tr class="empty-row screen-reader-text inventory-slide">
                <td>
                    <a class="button remove-slide" href="#">Remove Image</a><a class="sort"><i class="fa fa-arrows"></i></a>
                    <input type="hidden" name="gal[]" placeholder="Image URL"/>
                    <input type="button" id="gal[]" class="upload-image-button button btn" value="Upload Image" />
                    <div class="preview-container"><img id="gallery_image-preview" src="" /></div>
                </td>
            </tr>
        </tbody>
    </table>
    <p>
        <a id="add-slide" class="button" href="#">Add Slide</a>
        <input type="submit" class="metabox_submit button" value="Save" />
    </p><?php
}

function daltons_status_meta() {
    global $post;
    $status = get_post_meta($post->ID, 'daltons_status', TRUE);
    if (!$status) $status = 'sale';  ?>
    <input type="radio" name="daltons_status" value="sold" <?php if ($status == 'sold') echo "checked=1";?>> Sold.<br/>
    <input type="radio" name="daltons_status" value="sale" <?php if ($status == 'sale') echo "checked=1";?>> For Sale.<br/>
    <?php
}


function daltons_page_post_category_meta ( $post ) { ?>
    <label for="daltons_post_category">Select which posts by category to feed on the front page: </label>
    <?php wp_dropdown_categories( array(
        'selected'=> get_post_meta($post->ID, 'daltons_post_category', true),
        'name' => 'daltons_post_category',
        'show_option_none' => 'None',
        'class' => 'postform daltons-dropdown',
        'hide_empty' => false
    ) ); ?>
    <script type="text/javascript">
        jQuery(document).ready(function($){
            $(".daltons-dropdown").change(function(){
                if( $(this).val()!=-1 ) {
                    $(this).siblings().each(function(){
                        $(this).val(-1);
                    });
                }
            });
        });
    </script>
<?php }

/**
 * Save Meta Boxes
 */
function daltons_metabox_save( $post_id ) {
    $old = get_post_meta($post_id, 'daltons_about_slides', true);
    $new = array();
    $imgs = isset($_POST['img']) ? $_POST['img'] : false;
    $count = count( $imgs );
    if ($imgs) {
        for ( $i = 0; $i < $count; $i++ ) {
            if ( $imgs[$i] != '' ) :
                $new[$i]['img'] = stripslashes( $imgs[$i] ); 
            endif;
        }
        if ( !empty( $new ) && $new != $old )
            update_post_meta( $post_id, 'daltons_about_slides', $new );
        elseif ( empty($new) && $old )
            delete_post_meta( $post_id, 'daltons_about_slides', $old );
    }
    $days = array("Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday");
    foreach($days as $day) :
        if (isset($_POST['daltons_hours_o_'.$day])) :
            update_post_meta( $post_id, 'daltons_hours_o_'.$day, $_POST['daltons_hours_o_'.$day] );
        endif;
        if (isset($_POST['daltons_hours_c_'.$day])) :
            update_post_meta( $post_id, 'daltons_hours_c_'.$day, $_POST['daltons_hours_c_'.$day] );
        endif;
    endforeach;
    if (isset($_POST['daltons_phone'])) :
        update_post_meta( $post_id, 'daltons_phone', $_POST['daltons_phone'] );
    endif;
    if (isset($_POST['daltons_fax'])) :
        update_post_meta( $post_id, 'daltons_fax', $_POST['daltons_fax'] );
    endif;
    if (isset($_POST['daltons_other'])) :
        update_post_meta( $post_id, 'daltons_other', $_POST['daltons_other'] );
    endif;
    if (isset($_POST['daltons_address'])) :
        update_post_meta( $post_id, 'daltons_address', $_POST['daltons_address'] );
    endif;
    $oldg = get_post_meta($post_id, 'daltons_inventory_gallery', true);
    $newg = array();
    $gals = isset($_POST['gal']) ? $_POST['gal'] : false;
    $count = count( $gals );
    if ($gals) {
        for ( $i = 0; $i < $count; $i++ ) {
            if ( $gals[$i] != '' ) :
                $newg[$i]['gal'] = stripslashes( $gals[$i] ); 
            endif;
        }
        if ( !empty( $newg ) && $newg != $oldg )
            update_post_meta( $post_id, 'daltons_inventory_gallery', $newg );
        elseif ( empty($new) && $old )
            delete_post_meta( $post_id, 'daltons_inventory_gallery', $oldg );
    }
    if( isset($_POST['daltons_status']) ) {
        update_post_meta($post_id, 'daltons_status', esc_attr($_POST['daltons_status']) );
    }
    if( isset($_POST['daltons_post_category']) ) {
        update_post_meta($post_id, 'daltons_post_category', esc_attr($_POST['daltons_post_category']) );
    }
}
add_action( 'save_post', 'daltons_metabox_save' );

//add extra fields to category edit form
add_action ( 'inventorycategory_edit_form_fields', 'extra_category_fields');
function extra_category_fields( $tag ) {    //check for existing featured ID
    $t_id = $tag->term_id;
    $cat_meta = get_option( "category_$t_id");
    $img_src = wp_get_attachment_image_src( $cat_meta['img'] );?>
    <tr class="form-field">
        <th scope="row" valign="top"><label for="cat_Image_url"><?php _e('Category Image'); ?></label></th>
        <td>
            <input type="hidden" name="Cat_meta[img]" id="Cat_meta[img]" value="<?php echo $cat_meta['img'] ? $cat_meta['img'] : ''; ?>" />
            <input type="button" name="Cat_meta[img]" id="Cat_meta[img]" class="upload-image-button button btn" value="Upload Image" />
            <div class="preview-container"><img id="slide_image-preview" src="<?php if($cat_meta['img'] != '') echo $img_src[0]; ?>"/></div>
        </td>
    </tr>
<?php
}

// save extra category extra fields
add_action ( 'edited_inventorycategory', 'save_extra_category_fileds');
function save_extra_category_fileds( $term_id ) {
    if ( isset( $_POST['Cat_meta'] ) ) {
        $t_id = $term_id;
        $cat_meta = get_option( "category_$t_id");
        $cat_keys = array_keys($_POST['Cat_meta']);
            foreach ($cat_keys as $key){
            if (isset($_POST['Cat_meta'][$key])){
                $cat_meta[$key] = $_POST['Cat_meta'][$key];
            }
        }
        //save the option array
        update_option( "category_$t_id", $cat_meta );
    }
}
