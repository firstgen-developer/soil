<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package fgbase_
 */

?>
<!doctype html>
<html <?php language_attributes(); ?> role='main'>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<!-- hamburgers! -->
	<!-- <link href="dist/hamburgers.css" rel="stylesheet"> -->

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'fgbase_' ); ?></a>

	<header id='masthead'>
		
		<div class="container">

			<?php get_template_part( 'template-parts/header/branding' ); ?>
			<?php get_template_part( 'template-parts/header/site-header' ); ?>

		</div>


		<!-- hidden mobile nav -->
		<div class='mobile-nav'>
			<?php get_template_part( 'template-parts/header/site-header' ); ?>
		</div>

		<div class="toggle-wrapper nav-toggle-wrapper has-expanded-menu">
			<button input-button-name='mobile nav open and close' button-name='mobile hamburger' aria-label="mobile nav hamburger" id='hamburger-btn' type='button' class="hamburger hamburger--squeeze nav-toggle mobile-nav-toggle" data-toggle-target=".menu-modal"  data-toggle-body-class="showing-menu-modal" aria-expanded="false" data-set-focus=".close-nav-toggle">
				<span class="hamburger-box">
					<span class="hamburger-inner">
						<!-- <?php fgbase_the_theme_svg( 'ellipsis' ); ?> -->
					</span>
				</span>
			</button><!-- .nav-toggle -->
		</div><!-- .nav-toggle-wrapper -->

	</header>


	
	<div id="content" class="site-content">
