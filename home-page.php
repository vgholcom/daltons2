<?php
/**
 * Template Name: Home Page Template
 * @package WordPress
 * @subpackage daltons
 */
get_header(); ?>
<section id="carousel" class="carousel slide" data-ride="carousel"><?php
	$items = get_post_meta($post->ID, 'daltons_about_slides', true);
	$num = count($items); $first = true; ?>
	<ol class="carousel-indicators"><?php
		for ($i=0; $i<$num; $i++) {?>
			<li data-target="#carousel" data-slide-to="<?php echo $i; ?>" class="<?php echo $i==0 ? 'active' : '';?>"></li><?php
		} ?>
	</ol>
	<div class="carousel-inner"><?php
		foreach ( $items as $slide ) :?>
			<div class="item <?php if($first){ echo 'active'; $first=false; } ?>"><?php
				$thumb = wp_get_attachment_image_src( $slide['img'],'full' );
				$image = $thumb['0'];
				//$image = str_replace(site_url().'/', ABSPATH, $thumb['0']); 
				if (get_post_type() == 'video') { ?>
					<a href="<?php the_permalink(); ?>"><i class="fa fa-play-circle-o"></i></a><?php
				} ?>
				<img src="<?php echo $image; ?>" alt="<?php echo get_the_title(get_the_ID()); ?>">
			</div><?php
		endforeach;
		wp_reset_postdata(); ?>
	</div>
	<a class="left carousel-control" href="#carousel" data-slide="prev"></a>
	<a class="right carousel-control" href="#carousel" data-slide="next"></a>
</section>
<section id="featured" class="row-eq-height"><?php
	$args = array(
		'post_type' => 'post',
		'posts_per_page' => 3,
		'category' => get_post_meta($post->ID, 'daltons_post_category', true)
	);
	$recent = new WP_Query( $args );
	while ( $recent->have_posts() ) : $recent->the_post(); ?>
		<article class="col-md-4 col-sm-12"><?php
	    	$id = get_the_ID();
	    	echo get_the_post_thumbnail($id, 'thumbnail', array('class'=>'img-responsive center-block'));?>
	    	<h2><?php the_title(); ?></h2><?php
	    	the_content(); ?>
	   	</article><?php
	endwhile; ?>
</section><?php
get_footer(); ?>