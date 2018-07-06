<?php get_header(); ?>

	<div class="center">
		
		<div id="content">
			
			
			<?php 
				if ( have_posts() ) {
					while ( have_posts() ) {
						the_post();?> 
						
						<article id="single-page">
							
							
									
		
									<div id="title-container"> <h1><?php the_title() ?></h1> </div>
									 
									  
									  									
									  		 
									  		<?php the_content()?>								  
									  
						
							
									  		
						</article>
						
						<?php wp_link_pages(); ?>
							
							
			<?php	} // end while
			
				comments_template();
			
				} // end if
			?>
			
			
		</div>
		
		
		<?php get_sidebar();  ?>
		
		
		
	</div>
	
	

<?php get_footer(); 
	
?>