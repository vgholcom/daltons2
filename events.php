<?php
/**
 * Template Name: Events Page Template
 * @package WordPress
 * @subpackage daltons
 */
get_header();
$args = array(
	'posts_per_page'   => 5,
	'offset'           => 0,
	'category'         => '',
	'orderby'          => 'post_date',
	'order'            => 'DESC',
	'include'          => '',
	'exclude'          => '',
	'meta_key'         => '',
	'meta_value'       => '',
	'post_type'        => 'events',
	'post_mime_type'   => '',
	'post_parent'      => '',
	'post_status'      => 'publish',
	'suppress_filters' => true );
$myposts = get_posts( $args );
foreach ( $myposts as $post ) : setup_postdata( $post ); ?>
	<article class="row">
		<div class="container">
			<div class="col-md-4"><?php 
				echo get_the_post_thumbnail($post->ID, 'thumbnail', array('class'=>'img-responsive center-block'));?>
				<h2><?php the_title(); ?></h2>
			</div>
			<div class="col-md-8"><?php
				the_content('READ MORE >'); ?>
			</div>
		</div>
	</article><?php
endforeach; 
get_footer();