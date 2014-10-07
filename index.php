<?php
/**
 * Index Template
 * @package WordPress
 * @subpackage daltons
 */
get_header();
if ( have_posts() ) : while ( have_posts() ) : the_post();?>
	<article class="row"><?php 
			echo get_the_post_thumbnail($post->ID, 'thumbnail', array('class'=>'img-responsive center-block'));?>
			<h2><?php the_title(); ?></h2><?php
			the_content(); ?>
	</article><?php
endwhile; else: ?>
	<p><?php _e('Sorry, no posts matched your criteria.'); ?></p><?php 
endif;
get_footer();