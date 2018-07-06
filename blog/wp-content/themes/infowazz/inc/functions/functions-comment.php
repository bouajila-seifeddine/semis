<?php
if ( ! function_exists( 'infowazz_posted_on' ) ) :

function infowazz_posted_on() {
	$infowazz_allowed_html_array = array('a' => array( 'href' => array(), 'title' => array() ), 'br' => array(), 'i' => array('class' => array()),  'em' => array(), 'strong' => array(), 'div' => array('class' => array()), 'span' => array('class' => array()));
	printf( wp_kses(__( '<span class="date_links">%2$s</span>', 'infowazz' ), $infowazz_allowed_html_array ),
		'meta-prep meta-prep-author',
		sprintf( '<a href="%1$s" title="%2$s" rel="bookmark"><span class="entry-date">%3$s</span></a>',
			get_permalink(),
			esc_attr( get_the_time() ),
			get_the_date()
		),
		sprintf( '<span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s">%3$s</a></span>',
			get_author_posts_url( get_the_author_meta( 'ID' ) ),
			sprintf( esc_attr__( 'View all posts by %s', 'infowazz' ), get_the_author() ),
			get_the_author()
		)
	);
}

endif;

//POST IN
if ( ! function_exists( 'infowazz_posted_in' ) ) :

function infowazz_posted_in() {
	$infowazz_allowed_html_array = array('a' => array( 'href' => array(), 'title' => array() ), 'br' => array(), 'i' => array('class' => array()),  'em' => array(), 'strong' => array(), 'div' => array('class' => array()), 'span' => array('class' => array()));
	// Retrieves tag list of current post, separated by commas.
	$tag_list = get_the_tag_list( '', ', ' );
	if ( $tag_list ) {
		$posted_in = wp_kses(__( 'This entry was posted in %1$s and tagged %2$s. Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', 'infowazz'  ), $infowazz_allowed_html_array );
	} elseif ( is_object_in_taxonomy( get_post_type(), 'category' ) ) {
		$posted_in = esc_html__( '%1$s.', 'infowazz' );
	} else {
		$posted_in = wp_kses(__( 'Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', 'infowazz'  ), $infowazz_allowed_html_array );
	}
	// Prints the string, replacing the placeholders.
	printf(
		$posted_in,
		get_the_category_list( ', ' ),
		$tag_list,
		get_permalink(),
		the_title_attribute( 'echo=0' )
	);
}

endif;

/*-----------------------------------------------------------------------------------*/
/*	Coment Function
/*-----------------------------------------------------------------------------------*/
if ( ! function_exists( 'infowazz_comment' ) ) :

function infowazz_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case 'pingback' :
		case 'trackback' :
	?>
	<li class="post pingback">
		<p><?php esc_html_e( 'Pingback:', 'infowazz' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( esc_html__( 'Edit', 'infowazz' ), '<span class="edit-link">', '</span>' ); ?></p>
	<?php
			break;
		default :
	?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<article id="comment-<?php comment_ID(); ?>" class="comment">
			<footer class="comment-meta">
				<div class="comment-author vcard">
					<?php
						$avatar_size = 80;
						if ( '0' != $comment->comment_parent )
							$avatar_size = 80;

						echo get_avatar( $comment, $avatar_size );

						/* translators: 1: comment author, 2: date and time */
						printf( esc_html__( '%1$s %2$s', 'infowazz' ),
							sprintf( '<span class="fn">%s</span>', get_comment_author_link() ),
							sprintf( '<a href="%1$s" class="mt_comment_date"> <time pubdate datetime="%2$s">%3$s</time></a>',
								esc_url( get_comment_link( $comment->comment_ID ) ),
								get_comment_time( 'n-j-Y' ),
								/* translators: 1: date, 2: time */
								sprintf( esc_html__( '%1$s at %2$s', 'infowazz' ), get_comment_date('n-j-Y' ), get_comment_time() )
							)
						);
					?>
<?php edit_comment_link( esc_html__( ' Edit', 'infowazz' ), '<span class="edit-link">', '</span>' ); ?>

				</div><!-- .comment-author .vcard -->

				<?php if ( $comment->comment_approved == '0' ) : ?>
					<em class="comment-awaiting-moderation"><?php esc_html_e( 'Your comment is awaiting moderation.', 'infowazz' ); ?></em>
					<br />
				<?php endif; ?>

			</footer>

			<div class="comment-content"><?php comment_text(); ?></div>
			<div class="clear"></div>
			<?php comment_reply_link( array_merge( $args, array( 'reply_text' => esc_html__( 'Reply', 'infowazz' ), 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
		</article><!-- #comment-## -->

	<?php
			break;
	endswitch;
}
endif; // ends check for _comment()
?>
