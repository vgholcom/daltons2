<?php
/**
 * Single Page Template
 * @package WordPress
 * @subpackage daltons
 */
get_header();
if ( have_posts() ) : while ( have_posts() ) : the_post();?>
	<article>
		<div class="container">
			<h2><?php the_title(); ?></h2><?php 
			the_content(); ?>
		</div>
	</article><?php
endwhile; else: ?>
	<p><?php _e('Sorry, no posts matched your criteria.'); ?></p><?php 
endif;
get_footer(); ?>