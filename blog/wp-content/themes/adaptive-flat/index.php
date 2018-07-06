<?php get_header(); ?>
	
	<div id="main-content" class="center <?php adaptive_flat_content_class(); ?>">
		
		<div id="content" class="clearfix">
			
		
			
			<?php 
			
			
			if(!is_front_page()) {
			
				if (is_search()) {
					$mptitle = get_theme_mod('search_text', 'Search results for:') . " " . get_search_query();
				}
	
				else if ( is_tag()) {
					$mptitle = single_tag_title("", false);
					$mpdescription = tag_description();
				}
				else if (is_category()) {
					$mptitle = single_cat_title("", false);
					$mpdescription = category_description();
				}
				else if (is_404())
				{
					$mptitle = "Not Found, Error 404";
				}
				
				?>
				<div id="title-container" > <h1><?php echo($mptitle); ?></h1> </div>
				
				
			
				
				<?php
					
					if(isset($mpdescription) && $mpdescription != "") {
						?>
							
							<div id="description"><?php echo($mpdescription); ?></div>
						
						<?php
					}		
					
				?>
				
<?php		}
				
				get_template_part("loop");
		
								
				if(is_404())
				{
					echo("Something went wrong :(");
				}
				
				
					
			?>
			<?php adaptive_flat_numeric_posts_nav();?>
	
		</div>
		
		
		<?php if(get_theme_mod('layout_archive', 'cs') != 'c')
				{
					get_sidebar();
					
				}?>
		
		
		
	</div>
	
	

<?php get_footer(); ?>