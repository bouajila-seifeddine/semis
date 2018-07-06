<?php 

/**
*Code for the styles added to the header to display colors
*
*/

function adaptive_flat_style() {
?>

	<style>
	
		body {
			background: <?php echo( get_theme_mod( 'body_color', '#ffffff' ) )?>;
			color: <?php echo( get_theme_mod( 'text_color', '#323a46' ) ) ?>;
		}
		article a,
		#comments a{
			color: <?php echo( get_theme_mod( 'content_link_color', '#f37934'  ) );?>;
		}
		article a:hover{
			color:<?php echo( get_theme_mod( 'content_link_hover_color', '#d65a2c' ) );?>;
		}
		#top-bar {
			background: <?php echo( get_theme_mod( 'top_bar_color', '#1fbca7' ) );?>;
		}
		#header {
			background-color: <?php echo( get_theme_mod( 'header_color', '#ffffff' ) );?>;
		}
		#header .site-title {
			color: <?php echo( get_theme_mod( 'site_title_color', '#f37934' ) );?>;
		}
		#header p {
			color: <?php echo( get_theme_mod( 'site_description_color', '#323a46' ) );?>;
		}
		.menu-bar {
			background: <?php echo( get_theme_mod( 'menu_background', '#323a46' ) );?>;
			color: <?php echo( get_theme_mod( 'menu_text_color', '#ffffff' ) );?>;
		}
		.header-menu ul{
			background: <?php echo( get_theme_mod( 'menu_background', '#323a46' ) );?>;
		}
		
		.header-menu li:hover {
			background: <?php echo( get_theme_mod( 'menu_hover_color', '#1fbca7' ) );?>;
			color: <?php echo( get_theme_mod( 'menu_text_hover_color', '#ffffff' ) );?>;
		}
		.header-menu ul ul {
			background: <?php echo( get_theme_mod( 'menu_background', '#323a46' ) );?>;
		}
		.searchform {	
			border-color: <?php echo( get_theme_mod( 'search_border_color', '#EEEEEE' ) );?>;
		}
		.searchform .s{	
			color: <?php echo( get_theme_mod( 'search_text_color', '#000000' ) );?>;
			background: <?php echo( get_theme_mod( 'search_input_color', '#ffffff' ) );?>;
		}
		.searchform .s::-webkit-input-placeholder {
		    color: <?php echo( get_theme_mod( 'search_placeholder_color', '#c6c6c6' ) );?>;
		}    
		.searchform .s:-moz-placeholder {
		    /* FF 4-18 */
		    color: <?php echo( get_theme_mod( 'search_placeholder_color', '#c6c6c6' ) );?>;
		}
		.searchform .s::-moz-placeholder {
		    /* FF 19+ */
		   	color: <?php echo( get_theme_mod( 'search_placeholder_color', '#c6c6c6' ) );?>;
		}    
		.searchform .s:-ms-input-placeholder {
		    /* IE 10+ */
		    color: <?php echo( get_theme_mod( 'search_placeholder_color', '#c6c6c6' ) );?>;
		}
		.searchform .searchsubmit {
			background: <?php echo( get_theme_mod( 'search_button_color', '#f37934' ) );?>;
			color: <?php echo( get_theme_mod( 'search_button_logo_color', '#ffffff' ) );?>;
		}
		h1, h2, h3, h4, h5, h6 {
			color: <?php echo( get_theme_mod( 'headings_color', '#707070' ) );?>;
		}
		#title-container {
			border-color: <?php echo( get_theme_mod( 'page_title_underline_color', '#f37934' ) );?>;
		}
		#title-container h1 {
			color: <?php echo (get_theme_mod( 'page_title_color', '#707070' ) );?>;
		}
		.post-prev,
		.post-prev2,
		#single-game, 
		#single-page,
		#content legend,
		#content fieldset,
		#comments .comment { 
			border-color: <?php echo (get_theme_mod( 'post_prev_bottom_border_color', '#eeeeee' ) );?>;
		}
		.prev-title a{
			color: <?php echo( get_theme_mod( 'post_prev_title_color', '#6b6b6b' ) );?>;
		}
		.prev-title:hover a {
			color: <?php echo( get_theme_mod( 'post_prev_title_hover_color', '#f37934' ) );?>;
		}
		.img-placeholder {
			background: <?php echo( get_theme_mod( 'thumb_placeholder_color', '#1fbca7' ) );?>;
		}
		.img-placeholder .icon:before {
			color: <?php echo( get_theme_mod( 'thumb_placeholder_logo_color', '#ffffff' ) );?>;
		}
		.img-placeholder:hover .icon:before {
			color: <?php echo( get_theme_mod( 'thumb_placeholder_logo_hover_color', '#f4f4f4' ) );?>;
		}
		#footer-top {
			background: <?php echo( get_theme_mod( 'footer_top_color', '#1fbca7' ) );?>;
			color: <?php echo( get_theme_mod( 'footer_top_text_color', '#ffffff' ) );?>;
		}
		#footer-top .widget-head h4{
			background: <?php echo( get_theme_mod( 'footer_widget_head_color', '#1aad99' ) );?>;
			color: <?php echo( get_theme_mod( 'footer_widget_head_text_color', '#ffffff' ) );?>;
		}
		#footer-top .widget-head{
			border-color: <?php echo( get_theme_mod( 'footer_widget_head_color', '#1aad99' ) );?>;
		}
		#footer-top .widget li {
			border-color: <?php echo( get_theme_mod( 'footer_list_seperator_color', '#ececec' ) );?>;
		}
		#footer-bottom {
			background: <?php echo( get_theme_mod( 'footer_bottom_color', '#323a46' ) );?>;
			color: <?php echo( get_theme_mod( 'footer_bottom_text_color', '#ffffff' ) );?>;
		}
		#footer-bottom a {
			color: <?php echo( get_theme_mod( 'footer_bottom_link_color', '#ffffff' ) );?>;
		}
		
		.widget-head {
			border-color: <?php echo( get_theme_mod( 'widget_head_background_color', '#f37934' ) );?>;
		}
		.widget-head h4 {
			background: <?php echo( get_theme_mod( 'widget_head_background_color', '#f37934' ) );?>;
			color: <?php echo( get_theme_mod( 'widget_head_text_color', '#ffffff' ) );?>;
		}
		#sidebar li:before	{
			color: <?php echo ( get_theme_mod( 'widget_list_marker_color', '#323a46' ) );?>;
		}
		.widget li {	
			border-color: <?php echo( get_theme_mod( 'widget_list_seperator_color', '#ECECEC' ) );?>;
		}
		.navigation ul li {
			background: <?php echo( get_theme_mod( 'pagination_color', '#1fbca7' ) );?>;
			color: <?php echo( get_theme_mod( 'pagination_text_color', '#ffffff' ) );?>;
		}
		.navigation ul li.active, .navigation ul li:hover {
			background: <?php echo( get_theme_mod( 'pagination_active_color', '#f37934' ) );?>;
			color: <?php echo ( get_theme_mod( 'pagination_active_text_color', '#ffffff' ) );?>;
		}
		
		<?php echo( get_theme_mod( 'custom_css', '' ) );?>
	
	</style>
<?php
}

/**
* Add customizer js to live preview
*
*/
function adaptive_flat_customizer_live_preview()
{
	wp_enqueue_script( 
		  'mp-themecustomizer',			
		  get_template_directory_uri().'/js/theme-customizer.js',//Point to file
		  array( 'jquery','customize-preview' ),	//Define dependencies
		  '1.0.8',						//Define a version (optional) 
		  true						//Put script in footer?
	);
}
add_action( 'customize_preview_init', 'adaptive_flat_customizer_live_preview' );

?>