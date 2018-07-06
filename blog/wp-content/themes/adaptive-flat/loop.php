<?php
				if ( have_posts() ) {
					while ( have_posts() ) {
						the_post();
						
						
						//Two posts per line
						if(get_theme_mod('posts_per_line', '2') == 2) {?>
						
						
							<article  <?php post_class('post-prev clearfix');?>>
								
								<?php adaptive_flat_thumbnail(480, 300); 
									
									the_post_thumbnail();
								?>											
								
								<h2 class="prev-title"><a href="<?php the_permalink() ?>"><?php the_title();?></a></h2>
										  
								<?php adaptive_flat_excerpt() ?>
								
							</article>	
			<?php		}//end two posts per line
			
						//One post per line
						else {?>
							
							<article <?php post_class('post-prev2 clearfix'); ?> >
								<h2 class="prev-title"><a href="<?php the_permalink() ?>"><?php the_title() ?></a></h2>
								
								<?php adaptive_flat_thumbnail(200, 125); 
									the_post_thumbnail('prev-2');
								?>
								
								<?php adaptive_flat_excerpt(); ?>
								
							</article>		
							
			<?php		}//end two posts per line
							
							
						} // end while
				} // end if
?>