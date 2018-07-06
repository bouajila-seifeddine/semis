<?php function infowazz_header() {
$allowed_html = array('ins' => array( 'class' => array(), 'style' => array(),'data-ad-client' => array(),'data-ad-slot' => array(),'data-ad-format' => array()), 'iframe' => array( 'id' => array(),'name' => array(),'src' => array(),'style' => array(),'scrolling' => array(),'frameborder' => array()), 'script' => array( 'async' => array(), 'type' => array(),'src' => array()), 'noscript' => array(), 'small' => array( 'class' => array()), 'img' => array( 'src' => array(), 'alt' => array(), 'class' => array(), 'width' => array(), 'height' => array() ), 'a' => array( 'href' => array(), 'title' => array() ), 'br' => array(), 'i' => array('class' => array()),  'em' => array(), 'strong' => array(), 'div' => array('class' => array()), 'span' => array('class' => array()));
$option = get_option("infowazz_theme_options");
$optioz = get_option("magazin_theme_options");
$page_option = get_post_meta(get_the_ID(), "magazin_menu_background_width", true);
if(!empty($page_option)) {
	if($page_option=="2") {
		$menu_full =  'menu-background';
		$menu_boxed =  '';
	} else {
		$menu_full =  '';
		$menu_boxed =  'menu-background';
	}
}
else if(!empty($option['menu_background_width'])) {
	if($option['menu_background_width']=="full") {
		$menu_full =  'menu-background';
		$menu_boxed =  '';
	} else {
		$menu_full =  '';
		$menu_boxed =  'menu-background';
	}
} else {
	$menu_full =  '';
	$menu_boxed =  'menu-background';
}
?>

<?php if  (!empty($optioz['header_ad_top'])) {  ?>
	<div class="mt-t-ad">
		<div class="mt-t-ad-in">
			<?php echo html_entity_decode($optioz['header_ad_top']); ?>
		</div>
	</div>
<?php } ?>
<div class="topbar">
                        <div class="container">
                        <div class="telefonodiv" style="float:left; margin-top: 1%; font-size: large;"><i class="fa fa-whatsapp" aria-hidden="true" style="font-size:20px; color:green;"></i> <span class="telefono">+34 653 323 445</span></div>
                           <div class="col-lg-8 col-md-7 hidden-xs shortlinks pull-right">
                              <ul class="nolist row pull-right">
                                 <li>
                                    <a href="https://www.semillaslowcost.com/">
                                    Inicio</a>
                                 </li>
                                 <li>
                                    <a href="https://www.semillaslowcost.com/carrito">
                                    Pedido rápido</a>
                                 </li>
                                 <li>
                                    <a href="https://www.semillaslowcost.com/mi-cuenta">
                                    Mi cuenta</a>
                                 </li>
                              </ul>
                           </div>
                        </div>
                     </div>
<div class="header-wrap" itemscope="itemscope" itemtype="https://schema.org/WPHeader">
<div class="head-logo" <?php if(!empty($option['logo_width'])) { ?>  style="width:<?php echo esc_attr($option['logo_width']); ?>" <?php } ?>><?php infowazz_logo(); ?></div>
	<?php if ( true == get_theme_mod( 'mt_header_top', true ) ) {  ?>
		<div class="header-mt-container-wrap">
			<div class="container mt-header-container">
				<div class="row">
					<div class="col-md-12">
						<div class="head container-fluid">
							<!-- <div class="pull-left mt-top-social">
								<?php infowazz_socials(); ?>
							</div> -->
							<div class="pull-right mt-top-social">
								<?php infowazz_socials(); ?>
							</div>
							<?php if ( true == get_theme_mod( 'mt_top_follower', true ) ) { ?>
							<div class="mt-top-followers pull-left mt-top-share">
								<strong></strong> <span><?php esc_html_e( 'Followers', 'infowazz' ); ?></span>
							</div>
							<?php } ?>
							<div class="pull-right mt-top-menu">
								<?php infowazz_top_menu(); ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	<?php } ?>

	<div class="header-menu mt-header-container <?php if(!empty($option['menu_search'])) { if($option['menu_search']=="1") { ?>search-on<?php } } if(!empty($option['menu_small_on'])) { ?> small-on<?php } ?> " style="background: #7bbd42!important;">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<div class="top-nav container-fluid">



						<div class="nav-button mt-radius pointer  <?php if ( false == get_theme_mod( 'mt_menu_small_on', true ) ) { echo "hide-desktop"; } ?>">
							
								<a href="https://www.semillaslowcost.com/" title="Inicio"> <i class="fa fa-home" style="font-size:25px;"></i> <span class="hide">Inicio</span></a>
								
							
						</div>

						

						<div class="nav mt-radius" itemscope="itemscope" itemtype="https://schema.org/SiteNavigationElement" >
							<?php infowazz_nav(); ?>
						</div>

						<?php if ( true == get_theme_mod( 'mt_menu_search', true ) ) { ?>
							<div class="nav-search-wrap  mt-radius">
								<div class="nav-search pointer"></div>
								<div class="nav-search-input mt-radius">
									<form method="get" action="<?php echo esc_url(home_url('/')); ?>/">
										<input type="text" placeholder="<?php esc_html_e( 'Type and hit enter to search ...', 'infowazz' ); ?>"  name="s" >
									</form>
								</div>
							</div>
							<div class="search-close"></div>
						<?php } ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php	if ( true == get_theme_mod( 'mt_menu_share', true ) ) { infowazz_header_fixed();	} ?>
</div>

<?php } add_filter('infowazz_header','infowazz_header');


function infowazz_top_content() { $option = get_option("infowazz_theme_options"); ?>
		<div class="head-nav">
			<?php if(!empty($option['url_latest'])) { ?><a class="mt_l_latest <?php if($option['url_latest']==get_the_ID()) { ?>active<?php } ?>" href="<?php echo get_permalink(esc_html($option['url_latest'])); ?>"><?php esc_html_e( 'LATEST', 'infowazz' ); ?> <span><?php esc_html_e( 'Posts', 'infowazz' ); ?></span></a><?php } ?>
			<?php if(!empty($option['url_popular'])) { ?><a class="mt_l_popular <?php if($option['url_popular']==get_the_ID()) { ?>active<?php } ?>" href="<?php echo get_permalink(esc_html($option['url_popular'])); ?>"><?php esc_html_e( 'POPULAR', 'infowazz' ); ?> <span><?php esc_html_e( 'Posts', 'infowazz' ); ?></span></a><?php } ?>
			<?php if(!empty($option['url_hot'])) { ?><a class="mt_l_hot <?php if($option['url_hot']==get_the_ID()) { ?>active<?php } ?>" href="<?php echo get_permalink(esc_html($option['url_hot'])); ?>"><?php esc_html_e( 'HOT', 'infowazz' ); ?> <span><?php esc_html_e( 'Posts', 'infowazz' ); ?></span></a><?php } ?>
			<?php if(!empty($option['url_trending'])) { ?>	<a class="mt_l_trending <?php if($option['url_trending']==get_the_ID()) { ?>active<?php } ?>" href="<?php echo get_permalink(esc_html($option['url_trending'])); ?>"><?php esc_html_e( 'TRENDING', 'infowazz' ); ?> <span><?php esc_html_e( 'Posts', 'infowazz' ); ?></span></a><?php } ?>
		</div>

		<?php if(!empty($option['header_link_url'])) { ?>
				<div class="head-bookmark">
					<a class="mt-radius" href="<?php echo esc_url($option['header_link_url']);  ?>" <?php if(!empty($option['header_link_blank'])) { if($option['header_link_blank']=="1") {?>target="_blank" <?php }} ?>><?php echo esc_attr(get_theme_mod('infowazz_header_link_name', 'Add Post')); ?></a>
				</div>
		<?php } ?>
<?php }
add_filter('infowazz_top_content','infowazz_top_content');

function infowazz_logo() {

	$option = get_option("infowazz_theme_options");

	// Fix for SSL
	if(!empty($option['header_logo'])) {
		$header_logo = esc_url($option['header_logo']);
		if(is_ssl() and 'http' == parse_url($header_logo, PHP_URL_SCHEME) ){
				$header_logo = str_replace('http://', 'https://', $header_logo);
		}
	}
	if(!empty($option['header_logox2'])) {
		$header_logo2 = esc_url($option['header_logox2']);
		if(is_ssl() and 'http' == parse_url($header_logo2, PHP_URL_SCHEME) ){
				$header_logo2 = str_replace('http://', 'https://', $header_logo2);
		}
	}

	if(!empty($option['header_logo'])) { ?>
		<a class="logo"  href="<?php echo esc_url(home_url('/'));?>">
			<img <?php if(!empty($option['logo_width'])) { ?>  width="<?php echo esc_attr($option['logo_width']); ?>" <?php } if(!empty($option['logo_height'])) { ?>  height="<?php echo esc_attr($option['logo_height']); ?>" <?php } ?>
			src="<?php echo esc_url($header_logo); ?>"
			srcset="<?php echo esc_url($header_logo); ?>, <?php if(!empty($option['header_logox2'])) { echo esc_url($header_logo2); } ?> 2x"  alt="<?php echo the_title(); ?>"  />
		</a>
	<?php } else { ?>
		<a class="logo"  href="<?php echo esc_url(home_url('/'));?>">
			<img src="<?php echo get_template_directory_uri(); ?>/inc/img/logo.png" width="108" height="21" alt="<?php echo the_title(); ?>" />
		</a>
	<?php }
}

add_filter('infowazz_logo','infowazz_logo');

function infowazz_logo_mobile() {

	$option = get_option("infowazz_theme_options"); ?>

	<?php if(!empty($option['mobile_logo'])) { ?>
		<a href="<?php echo esc_url(home_url('/'));?>">
			<img src="<?php echo esc_url($option['mobile_logo']); ?>" alt="<?php echo the_title(); ?>"  />
		</a>
	<?php } else { ?>
		<a href="<?php echo esc_url(home_url('/'));?>">
			<img src="<?php echo get_template_directory_uri(); ?>/inc/img/logo.png" alt="<?php echo the_title(); ?>" />
		</a>
	<?php }
}
add_filter('infowazz_logo_mobile','infowazz_logo_mobile');

function infowazz_nav() {
	if(class_exists('md_walker__')) {
		wp_nav_menu( array('theme_location'=>"primary",  'menu_class' => 'sf-menu', 'walker'	=> new md_walker, 'echo' => true, 'depth' => 3, 'fallback_cb' => false));
	} else {
		wp_nav_menu( array('theme_location'=>"primary",  'menu_class' => 'sf-menu', 'echo' => true, 'depth' => 3));
	}
}
add_filter('infowazz_nav','infowazz_nav');

function infowazz_nav_fixed() {
	wp_nav_menu( array('theme_location'=>"primary",  'menu_class' => 'fixed-menu-ul',  'echo' => true, 'depth' => 1));
}
add_filter('infowazz_nav_fixed','infowazz_nav_fixed');

function infowazz_nav_mobile() {
	wp_nav_menu( array('theme_location'=>"mobile",  'menu_class' => 'mobile',  'echo' => true, 'depth' => 2));
}
add_filter('infowazz_nav_mobile','infowazz_nav_mobile');

function infowazz_top_menu() {
	wp_nav_menu( array('theme_location'=>"top_menu",  'menu_class' => 'top-menu',  'echo' => true, 'depth' => 1));
}
add_filter('infowazz_top_menu','infowazz_top_menu');

function infowazz_socials() { ?>
	<ul class="social"> <?php
			$option = get_option("infowazz_theme_options");
			if(!empty($option['mt_icon_twitter'])) {?><li><a <?php if(!empty($option['mt_icon_blank'])) { if($option['mt_icon_blank']=="on") {?> target="_blank" <?php }} ?> href="<?php echo esc_url($option['mt_icon_twitter']); ?>"><i class="ic-twitter"></i></a></li><?php }
			if(!empty($option['mt_icon_facebook'])) {?><li><a <?php  if(!empty($option['mt_icon_blank'])) { if($option['mt_icon_blank']=="on") {?> target="_blank" <?php }} ?> href="<?php echo esc_url($option['mt_icon_facebook']); ?>" ><i class="ic-facebook"></i></a></li><?php }
			if(!empty($option['mt_icon_intagram'])) {?><li><a <?php if(!empty($option['mt_icon_blank'])) {  if($option['mt_icon_blank']=="on") {?> target="_blank" <?php }} ?> href="<?php echo esc_url($option['mt_icon_intagram']); ?>" ><i class="ic-instagram"></i></a></li><?php }
			if(!empty($option['mt_icon_vimeo'])) {?><li><a <?php  if(!empty($option['mt_icon_blank'])) { if($option['mt_icon_blank']=="on") {?> target="_blank" <?php }} ?> href="<?php echo esc_url($option['mt_icon_vimeo']); ?>"><i class="ic-vimeo"></i></a></li><?php }
			if(!empty($option['mt_icon_youtube'])) {?><li><a <?php  if(!empty($option['mt_icon_blank'])) { if($option['mt_icon_blank']=="on") {?> target="_blank" <?php }} ?> href="<?php echo esc_url($option['mt_icon_youtube']); ?>"><i class="ic-youtube-play"></i></a></li><?php }
			if(!empty($option['mt_icon_linkedin'])) {?><li><a <?php  if(!empty($option['mt_icon_blank'])) { if($option['mt_icon_blank']=="on") {?> target="_blank" <?php }} ?> href="<?php echo esc_url($option['mt_icon_linkedin']); ?>"><i class="ic-linkedin"></i></a></li><?php }
			if(!empty($option['mt_icon_gplus'])) {?><li><a <?php  if(!empty($option['mt_icon_blank'])) { if($option['mt_icon_blank']=="on") {?> target="_blank" <?php }} ?> href="<?php echo esc_url($option['mt_icon_gplus']); ?>"><i class="ic-google-plus"></i></a></li><?php }
			if(!empty($option['mt_icon_dribble'])) {?><li><a <?php  if(!empty($option['mt_icon_blank'])) { if($option['mt_icon_blank']=="on") {?> target="_blank" <?php }} ?> href="<?php echo esc_url($option['mt_icon_dribble']); ?>"><i class="ic-dribbble"></i></a></li><?php }
			if(!empty($option['mt_icon_skype'])) {?><li><a <?php  if(!empty($option['mt_icon_blank'])) { if($option['mt_icon_blank']=="on") {?> target="_blank" <?php }} ?> href="<?php echo esc_url($option['mt_icon_skype']); ?>"><i class="ic-skype"></i></a></li><?php }
			if(!empty($option['mt_icon_pinterest'])) {?><li><a <?php  if(!empty($option['mt_icon_blank'])) { if($option['mt_icon_blank']=="on") {?> target="_blank" <?php }} ?> href="<?php echo esc_url($option['mt_icon_pinterest']); ?>"><i class="ic-pinterest"></i></a></li><?php }
			if(!empty($option['mt_icon_rss'])) {?><li><a <?php  if(!empty($option['mt_icon_blank'])) { if($option['mt_icon_blank']=="on") {?> target="_blank" <?php }} ?> href="<?php echo esc_url($option['mt_icon_rss']); ?>"><i class="ic-rss"></i></a></li><?php }
			?>
	</ul><?php
} add_filter('infowazz_socials','infowazz_socials');

function infowazz_header_fixed() {
	if (is_single()) {
		/* Share Meta from Magazin framework */
		$share = get_post_meta(get_the_ID(), "magazin_share_count", true);
		$share_real = get_post_meta(get_the_ID(), "magazin_share_count_real", true);
		$shares = $share_real;
		if (!empty($share)){
			$shares = $share+$share_real;
		}
		/* View Meta from Magazin framework */
		$view = get_post_meta(get_the_ID(), "magazin_view_count", true);
		$viewes = "0";
		if (!empty($view)){
			$viewes = $view;
		}
		$url = wp_get_attachment_url( get_post_thumbnail_id(get_the_ID()));
		?>
		<?php $option = get_option("infowazz_theme_options"); ?>
				<div class="fixed-top">
					<div class="container-fuild">
						<div class="row">
							<div class="col-md-12">

								<ul class="share">
									<li class="share-facebook"><a class="mt-radius" href="http://www.facebook.com/sharer.php?u=<?php the_permalink();?>" target="_blank" rel="nofollow"><span><?php echo esc_html__('Share on Facebook', 'infowazz'); ?></span></a></li>
									<li class="share-twitter"><a class="mt-radius" href="http://twitter.com/home/?status=<?php the_title(); ?>-<?php the_permalink(); ?>" target="_blank" rel="nofollow"><span><?php echo esc_html__('Tweet on Twitter', 'infowazz'); ?></span></a></li>
									<li class="share-more">
										<div class="share-more-wrap"><div class="share-more-icon mt-radius">+</div></div>
										<a class="mt-radius" href="https://plus.google.com/share?url=<?php the_permalink() ?>" target="_blank" rel="nofollow"><div class="google mt-radius-b"></div></a>
										<a class="mt-radius" href="http://pinterest.com/pin/create/button/?url=<?php the_permalink() ?>&media=<?php echo esc_url($url); ?>" target="_blank" rel="nofollow"><div class="pinterest mt-radius-b"></div></a>
									</li>
								</ul>
							</div>
						</div>
					</div>
				</div>
	<?php } ?>
<?php } add_filter('infowazz_header_fixed','infowazz_header_fixed'); ?>
