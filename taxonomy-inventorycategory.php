<?php
/**
 * Taxonomy Page Template
 * @package WordPress
 * @subpackage daltons
 */
get_header();
if ( have_posts() ) : while ( have_posts() ) : the_post();?>
	<article class="row">
		<div class="container">
			<div class="col-md-4"><?php 
				$gallery = get_post_meta($post->ID, 'daltons_inventory_gallery', true);
				$i = 0; 
				foreach ( $gallery as $image ) :
					if ($i==0) : 
						$img_srct = wp_get_attachment_image_src( $image['gal'], 'thumbnail');
						$img_src = wp_get_attachment_image_src( $image['gal'], 'full' );?>
						<a class="img-responsive center-block" href="<?php echo $img_src[0]; ?>" rel="prettyPhoto[gal-<?php echo $post->ID; ?>]"><img class="img-responsive center-block" src="<?php echo $img_srct[0]; ?>" /></a><?php
					else :
						$img_src = wp_get_attachment_image_src( $image['gal'], 'full' ); ?>
	                	<a href="<?php echo $img_src[0]; ?>" rel="prettyPhoto[gal-<?php echo $post->ID; ?>]"><img style="display:none;" src="<?php echo $img_src[0]; ?>" /></a><?php
					endif;
					$i++;
	            endforeach;?>
				<h2><?php the_title(); ?></h2>
			</div>
			<div class="col-md-8"><?php
				the_content(); ?>
			</div>
		</div>
	</article><?php
endwhile; else: ?>
	<p><?php _e('Sorry, no posts matched your criteria.'); ?></p><?php 
endif;
get_footer(); ?>