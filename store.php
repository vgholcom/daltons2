<?php
/**
 * Template Name: Store Page Template
 * @package WordPress
 * @subpackage daltons
 */
get_header(); ?>
<div class="container"><?php 
	if ( have_posts() ) : while ( have_posts() ) : the_post();
		the_content(); 
	endwhile; endif;
	$categories = get_terms( 'inventorycategory');
	foreach ($categories as $category) {
		if ($category->parent == 0) {
			$parentID = $category->term_id;
			$child_cat = get_term_children( $parentID,'inventorycategory' ); 
			if (!empty($child_cat)) :?>
				<h1><?php echo $category->name; ?></h1><?php
				$count = count($child_cat); $i=0; 
				foreach ($child_cat as $child) { $i++;
					$category_link = get_term_link( $child,'inventorycategory' ); 
					if ($i ==1 ) {?>
						<div class="row"><?php
					}
					$t_id = $child;
					$cat_meta = get_option( "category_$t_id");
   					$img_src = wp_get_attachment_image_src( $cat_meta['img'], 'full' );
					$link = get_term_link( $category );
					$term = get_term($child,'inventorycategory');?>
					<a class="col-md-3 col-sm-12 inventory" href='<?php echo $link; ?>'><?php 
						if($cat_meta['img'] != '') { ?>
							<img id="inventory_image" class="img-responsive" src="<?php if($cat_meta['img'] != '') echo $img_src[0]; ?>"/><?php
						} ?>
						<h3><?php echo $term->name; ?></h3>
		    		</a><?php
		    		if ($i%3==0) { ?>
		    			</div><?php
		    			if ($i != $count) {?>
                           <div class="item row"><?php
                        }
		    		}
				}
				if ($i%3!=0){ ?>
                    </div><?php
                }
            endif;
		}
	}?>
</div>
<?php get_footer(); ?>
