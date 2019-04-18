<?php

if ( ! function_exists( 'infowazz_paging_nav' ) ) :

function infowazz_paging_nav() {
	global $wp_query,  $infowazz_allowed_html_array;

	// Don't print empty markup if there's only one page.
	if ( $wp_query->max_num_pages < 2 )
		return;
		if ( false == get_theme_mod( 't_p_older_posts', false ) ) { $t_p_older_posts = wp_kses(__( '<span class="meta-nav">&larr;</span> Older posts', 'infowazz' ), $infowazz_allowed_html_array );  } else { $t_p_older_posts = get_theme_mod( 't_p_older_posts' ); $t_p_older_posts = wp_kses(( '<span class="meta-nav">&larr;</span> '.$t_p_older_posts), $infowazz_allowed_html_array ); }
		if ( false == get_theme_mod( 't_p_newer_posts', false ) ) { $t_p_newer_posts = wp_kses(__( 'Newer posts <span class="meta-nav">&rarr;</span>', 'infowazz' ), $infowazz_allowed_html_array );  } else { $t_p_newer_posts = get_theme_mod( 't_p_newer_posts' ); $t_p_newer_posts = wp_kses(( $t_p_newer_posts.' <span class="meta-nav">&rarr;</span>'), $infowazz_allowed_html_array ); }

	?>
	<nav class="navigation paging-navigation" role="navigation">

		<div class="nav-links">

			<?php if ( get_next_posts_link() ) : ?>
			<div class="nav-previous pagination-link"><?php next_posts_link($t_p_older_posts); ?></div>
			<?php endif; ?>

			<?php if ( get_previous_posts_link() ) : ?>
			<div class="nav-next pagination-link"><?php previous_posts_link($t_p_newer_posts); ?></div>
			<?php endif; ?>
			<div class="clear"></div>
		</div><!-- .nav-links -->
	</nav><!-- .navigation -->
	<?php
}
endif;

$navigation_speed = "4.9";


if ( ! function_exists( 'infowazz_entry_meta' ) ) {
/**
 * Prints HTML with meta information for current post: categories, tags, permalink, author, and date.
 *
 * Create your own _entry_meta() to override in a child theme.
 *
 */
	function infowazz_entry_meta() {
		$infowazz_allowed_html_array = array('a' => array( 'href' => array(), 'title' => array() ), 'br' => array(), 'i' => array('class' => array()),  'em' => array(), 'strong' => array(), 'div' => array('class' => array()), 'span' => array('class' => array()));
		if ( false == get_theme_mod( 't_p_view_all_posts_by', false ) ) { $t_p_view_all_posts_by = esc_html__("View all posts by %s", "infowazz");  } else { $t_p_view_all_posts_by = get_theme_mod( 't_p_view_all_posts_by' ); $t_p_view_all_posts_by = $t_p_view_all_posts_by.' %s'; }
		if ( false == get_theme_mod( 't_p_posted_on', false ) ) { $t_p_posted_on = wp_kses(__( '<div class="mt-meta">Posted on <i class="fa fa-calendar"></i> %3$s </div>', 'infowazz' ), $infowazz_allowed_html_array );  } else { $t_p_posted_on = get_theme_mod( 't_p_posted_on' ); $t_p_posted_on = ' <div class="mt-meta">'.$t_p_posted_on.' <i class="fa fa-calendar"></i> %3$s </div>'; }
		global $infowazz_allowed_html_array;
		// Translators: used between list items, there is a space after the comma.
		$categories_list = get_the_category_list( esc_html__( ', ', 'infowazz' ) );

		// Translators: used between list items, there is a space after the comma.
		$tag_list = get_the_tag_list( '', esc_html__( ', ', 'infowazz' ) );

		$date = sprintf( '<a href="%1$s" title="%2$s" rel="bookmark"><time class="entry-date" datetime="%3$s"> %4$s</time></a>',
			esc_url( get_permalink() ),
			esc_attr( get_the_time() ),
			esc_attr( get_the_date( 'c' ) ),
			esc_html( get_the_date() )
		);

		$author = sprintf( '<span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s" rel="author">%3$s</a></span>',
			esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
			esc_attr( sprintf( $t_p_view_all_posts_by, get_the_author() ) ),
			get_the_author()
		);

		// Translators: 1 is category, 2 is tag, 3 is the date and 4 is the author's name.
		if ( $tag_list ) {
			$utility_text = wp_kses(__( '<div class="mt-meta"><i class="fa fa-folder-open-o"></i> %1$s <span class="mt_space"></span> <i class="fa fa-tag"></i> %2$s <span class="mt_space"></span> <i class="fa fa-calendar"></i> %3$s </div>', 'infowazz' ), $infowazz_allowed_html_array );
		} elseif ( $categories_list ) {
			$utility_text = wp_kses(__( '<div class="mt-meta"><i class="fa fa-folder-open-o"></i> %1$s <span class="mt_space"></span> <i class="fa fa-calendar"></i> %3$s </div>', 'infowazz' ), $infowazz_allowed_html_array );
		} else {
			$utility_text = $t_p_posted_on;
		}

		printf(
			$utility_text,
			$categories_list,
			$tag_list,
			$date,
			$author
		);
	}
}

if ( ! function_exists( 'infowazz_content_nav' ) ) {
/**
 * Displays navigation to next/previous pages when applicable.
 *
 */
	function infowazz_content_nav( $nav_id ) {
		global $wp_query,  $infowazz_allowed_html_array;
		if ( false == get_theme_mod( 't_p_older_posts', false ) ) { $t_p_older_posts = wp_kses(__( '<span class="meta-nav">&larr;</span> Older posts', 'infowazz' ), $infowazz_allowed_html_array );  } else { $t_p_older_posts = get_theme_mod( 't_p_older_posts' ); $t_p_older_posts = wp_kses(( '<span class="meta-nav">&larr;</span> '.$t_p_older_posts), $infowazz_allowed_html_array ); }
		if ( false == get_theme_mod( 't_p_newer_posts', false ) ) { $t_p_newer_posts = wp_kses(__( 'Newer posts <span class="meta-nav">&rarr;</span>', 'infowazz' ), $infowazz_allowed_html_array );  } else { $t_p_newer_posts = get_theme_mod( 't_p_newer_posts' ); $t_p_newer_posts = wp_kses(( $t_p_newer_posts.' <span class="meta-nav">&rarr;</span>'), $infowazz_allowed_html_array ); }

		if ( $wp_query->max_num_pages > 1 ) : ?>
			<nav id="<?php echo esc_attr($nav_id); ?>" class="navigation" role="navigation">
				<div class="nav-links">
					<div class="nav-previous alignleft"><?php next_posts_link( $t_p_older_posts ); ?></div>
					<div class="nav-next alignright"><?php previous_posts_link( $t_p_newer_posts ); ?></div>
					<div class="clear"></div>
				</div>
			</nav><!-- #<?php echo esc_attr($nav_id); ?> .navigation -->
		<?php endif;
	}
}

/** Theme Speed Improve **/

/**
 * Creates a nicely formatted and more specific title element text
 * for output in head of document, based on current view.
 *
 *
 * @param string $title Default title text for current view.
 * @param string $sep Optional separator.
 * @return string Filtered title.
 */
function infowazz_wp_title( $title, $sep ) {
	global $paged, $page;

	if ( is_feed() )
		return $title;

	// Add the site name.
	$title .= get_bloginfo( 'name' );

	// Add the site description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		$title = "$title $sep $site_description";
		if ( false == get_theme_mod( 't_p_page', false ) ) { $t_p_page = esc_html__("Page %s", "infowazz");  } else { $t_p_page = get_theme_mod( 't_p_page' ); $t_p_page = $t_p_page.' %s'; }
	// Add a page number if necessary.
	if ( $paged >= 2 || $page >= 2 )
		$title = "$title $sep " . sprintf( $t_p_page, max( $paged, $page ) );

	return $title;
}
add_filter( 'wp_title', 'infowazz_wp_title', 10, 2 );


/**
 * Makes our wp_nav_menu() fallback -- wp_page_menu() -- show a home link.
 *
 */
function infowazz_page_menu_args( $args ) {
	$args['show_home'] = true;
	return $args;
}
add_filter( 'wp_page_menu_args', 'infowazz_page_menu_args' );

/**
 * Enqueues scripts and styles for front-end.
 *
 */
function infowazz_scripts_styles() {
	/*
	 * Adds JavaScript to pages with the comment form to support
	 * sites with threaded comments (when in use).
	 */
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) )
		wp_enqueue_script( 'comment-reply' );


}
add_action( 'wp_enqueue_scripts', 'infowazz_scripts_styles' );


?>
