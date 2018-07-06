<?php get_header(); ?>

	<div class="center <?php adaptive_flat_content_class(); ?>">
		
		<div id="content">
			
			
			<?php 
				if ( have_posts() ) {
					while ( have_posts() ) {
						the_post();?> 
						
						<article id="single-game">
								
									<div id="title-container"> <h1><?php the_title() ?></h1> </div>
											  <div id="game">
											  
											   <?php do_action("giga_embed") ?>
											   	
									</div>
									<?php the_content()?>		
						
						</article>	
						
							
							
			<?php	} // end while
				} // end if
			?>
			
			
		</div>
		
		
		<?php if(get_theme_mod('layout_posts') != 'c') {
					get_sidebar();
			  }   ?>
		
		
		
	</div>
	
	

<?php get_footer(); ?>