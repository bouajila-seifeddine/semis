<!DOCTYPE html>
<html <?php language_attributes(); ?>>
	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>" />
		<meta name="viewport" content="initial-scale=1">
		<title><?php wp_title( '|', true, 'right' ); ?></title>
		<?php wp_head(); ?>	
		<?php adaptive_flat_style(); ?>
	</head>
	<body <?php body_class(); ?> >
	
			<div id="top-bar"></div>
	
	
			<header id="header">
				<div class="center">
					<div id="header-left">
							<?php if(get_theme_mod('header_image') == "") {
							
									
										if (is_front_page()) {?>
											
											<h1 class="site-title"><?php bloginfo('name'); ?> </h1>
												
								<?php	}
										else {?>
											<span class="site-title"><a href="<?php echo get_site_url(); ?>"><?php bloginfo('name'); ?> </a></span>	
								<?php	}?>
									
							
											<p class="color-3-font"><?php bloginfo('description'); ?></p>
							<?php }
										else if(!is_front_page()) {?>
											<a href="<?php echo get_site_url(); ?>"><img src="<?php echo get_theme_mod('header_image'); ?>" alt="<?php bloginfo('name') ?>"/></a>
								<?php	}
										
										else {?>
											<img src="<?php echo get_theme_mod('header_image'); ?>" alt="<?php bloginfo('name') ?>"/>
								<?php	} ?>
					</div>
							
						<?php get_search_form(); ?>
						
				</div>
			</header>
			
			<div id="menu-wrapper">
					<nav class="menu-bar">
						<div class="center header-menu"><?php wp_nav_menu( array( 'theme_location' => 'header-menu' ) ); ?> <div class="clearfix"></div></div>
					</nav>
			</div>