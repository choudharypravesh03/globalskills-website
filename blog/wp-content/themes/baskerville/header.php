<!DOCTYPE html>

<html <?php language_attributes(); ?>>

	<head>

		<meta charset="<?php bloginfo( 'charset' ); ?>">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" >

		<?php wp_head(); ?>
		<!-- modify by Neova match style to main site -->
		<link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
		<link rel="stylesheet" type="text/css"
			href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css" />

		<link rel="stylesheet" href="<?php echo getMainURL() ?>app/css/style.min.css">

	</head>

	<body <?php body_class(); ?>>

		<?php $background_image_url = get_header_image() ? get_header_image() : get_template_directory_uri() . '/images/header.jpg'; ?>

		<header>
			<div class="container">
				<div class="flex items-align-center space-between">
					<div class="navbar-brand">
						<a href="<?php echo getMainURL() ?>">
							<img src="<?php echo getMainURL() ?>app/images/logo.png">
						</a>
						<div class="toggle-btn">
							<span class="menu-item"></span>
							<span class="menu-item"></span>
							<span class="menu-item"></span>
						</div>
					</div>
					<div class="navigation">
						<ul>
							<li><a href="<?php echo getMainURL() ?>faq.html">FAQ</a></li>
							<li><a href="<?php echo home_url() ?>">Blog</a></li>
							<li><a href="<?php echo getMainURL() ?>about.html">About</a></li>
							<li><a class="scroll-btn" href="<?php echo getMainURL() ?>#contactSection">Contact</a></li>
						</ul>
						<ul class="social-icon">
							<li><a target="_blank" href="https://www.linkedin.com/company/get-global-skills"><i class="fa fa-linkedin" aria-hidden="true"></i></a></li>
							<li><a target="_blank" href="#"><i class="fa fa-instagram" aria-hidden="true"></i></a></li>
							<li><a target="_blank" href="#"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
							<li><a target="_blank" href="#"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
						</ul>
					</div>
				</div>
			</div>
		</header>
		<div class="banner-section" style="background-image: url(<?php echo getMainURL() ?>app/images/banner-img.jpg)">
			<div class="container">
				<div class="banner-text color-white">
					<a href="<?php echo home_url() ?>/"><h1 data-aos="fade-up">Global Skills Hub</h1></a>
					<a href="<?php echo home_url() ?>/"><p data-aos="fade-up" data-aos-delay="200">Streamline your global talent search</p></a>
				</div>
				<!-- <div class="header-search-block bg-graphite hidden">
					<?php //get_search_form(); ?>
				</div> -->
				<!-- .header-search-block -->
			</div>
		</div><!-- .header -->


		<!-- <div class="no-padding bg-dark">
			<div class="section-inner">
				 <a class="search-toggle fright" href="#"></a>
				 <div class="clear"></div>
			</div>
		</div> -->
