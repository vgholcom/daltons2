<?php
/**
 * Header Template
 * @package Wordpress
 * @subpackage daltons
 */
?>
<!DOCTYPE html>
<!--[if lt IE 7 ]><html <?php language_attributes(); ?> class="no-js ie6"><![endif]-->
<!--[if IE 7 ]><html <?php language_attributes(); ?> class="no-js ie7"><![endif]-->
<!--[if IE 8 ]><html <?php language_attributes(); ?> class="no-js ie8"><![endif]-->
<!--[if IE 9 ]><html <?php language_attributes(); ?> class="no-js ie9"><![endif]-->
<!--[if (gt IE 9)|!(IE)]><!-->
<html <?php language_attributes(); ?> class="no-js">
<!--<![endif]-->
	<head>
		<!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
		<!--[if lt IE 9]><script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
        <meta name="description" content="<?php bloginfo( 'description' ); ?>" />
        <meta name="author" content="Tori Holcomb" />
        <meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, user-scalable=no, width=device-width" />
        <meta charset="<?php bloginfo( 'charset' ); ?>" />
        <title><?php bloginfo('name'); wp_title( '|', true, 'left' ); ?></title>
        <link rel="profile" href="http://gmpg.org/xfn/11" />
        <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
        <link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/ico/favicon.ico ?>" type="image/x-icon" /><?php
        wp_head(); ?>
    </head>
    <body <?php body_class(); ?>>
        <nav id="site-navigation" class="navbar navbar-default navbar-static-top" role="navigation">
            <div class="container">  
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#main-navbar-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        </button>
                        <?php $option = get_option( 'daltons_theme_options' ); ?>
                        <a class="navbar-brand" href="<?php bloginfo('url'); ?>"><img id="logo" src="<?php echo isset($option['branding'] ) ? $option['branding']['src'] : ''; ?>"></a>
                </div>
                <div class="navbar-form navbar-right" role="search">
                    <?php get_search_form(); ?>
                </div>
                <!--<a class="skip-link screen-reader-text" href="#content"><?php _e( 'Skip to content', '_s' ); ?></a>-->
                <?php wp_nav_menu( array( 
                    'theme_location'    => 'primary',
                    'container'         => 'div',
                    'container_class'   => 'collapse navbar-collapse navbar-right',
                    'container_id'      => 'main-navbar-collapse',
                    'menu_class'        => 'nav navbar-nav navbar-left',
                    'menu_id'           => '',
                    'echo'              => true,
                    'fallback_cb'       => 'wp_page_menu',
                    'before'            => '',
                    'after'             => '',
                    'link_before'       => '',
                    'link_after'        => '',
                    'items_wrap'        => '<ul id="%1$s" class="%2$s">%3$s</ul>',
                    'depth'             => 0,
                    'walker'            => ''
                )); ?>

            </div>
        </nav><!-- #site-navigation -->
        <div id="main-content">
