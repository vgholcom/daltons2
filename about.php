<?php
/**
 * Template Name: About Page Template
 * @package WordPress
 * @subpackage daltons
 */
get_header();
if ( have_posts() ) : while ( have_posts() ) : the_post();?>
	<div id="about">
		<div class="row-eq-height">
			<div class="content col-md-5">
				<h1><?php the_title(); ?></h1>
				<?php the_content();?>
			</div>
			<div class="col-md-7">
				<div class="row row-eq-height">
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
									//$image = str_replace(site_url().'/', ABSPATH, $thumb['0']); ?>
									<img src="<?php echo $image; ?>" alt="<?php echo get_the_title(get_the_ID()); ?>">
								</div><?php
							endforeach;
							wp_reset_postdata(); ?>
						</div>
						<a class="left carousel-control" href="#carousel" data-slide="prev"></a>
						<a class="right carousel-control" href="#carousel" data-slide="next"></a>
					</section>
				</div>
				<div class="row row-eq-height">
					<div class="col-md-7">
						<div class="map row">
							<?php $address =  get_post_meta($post->ID, 'daltons_address', true); ?>
							<div id="googleMap" style="width:500px;height:380px;"></div>
						</div>
						<div class="location row">
							<span><?php echo $address; ?></span>
						</div>
					</div>
					<div class="col-md-5">
						<div class="hours row"><?php
							$days = array("Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday");?>
							<h2>Hours</h2>
						    <div class="hours-table span"><?php
					            foreach($days as $day) :
					                $ohour = get_post_meta($post->ID, 'daltons_hours_o_'.$day, true);
					                $chour = get_post_meta($post->ID, 'daltons_hours_c_'.$day, true); ?>
					                <strong><?php echo $day;?></strong>
					                <?php if(isset($ohour)){ echo $ohour.' -'; } ?>
					                <?php if(isset($chour)){ echo ' '.$chour; }  ?><br>
									<?php
					            endforeach;?>
						    </div>
						</div>
						<div class="contact row"><?php
							$phone = get_post_meta($post->ID, 'daltons_phone', true);
	            			$fax = get_post_meta($post->ID, 'daltons_fax', true);
	            			$other = get_post_meta($post->ID, 'daltons_other', true); ?>
	            			<h2>Contact</h2>
	            			<span>Phone: <?php echo $phone; ?></span><br>
	            			<span>Fax: <?php echo $fax; ?></span><br>
	            			<span><?php echo $other; ?></span>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div><?php
endwhile; else: ?>
	<p><?php _e('Sorry, no posts matched your criteria.'); ?></p><?php 
endif;
get_footer();?>
		
