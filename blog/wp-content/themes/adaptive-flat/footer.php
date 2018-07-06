			<footer id="footer">
				
				<div id="footer-top">
					<div class="center">
					<?php	
							if( function_exists( 'dynamic_sidebar' ) ) {
								 	dynamic_sidebar( 'Footer 1' );
									dynamic_sidebar( 'Footer 2' );
									dynamic_sidebar( 'Footer 3' );
									dynamic_sidebar( 'Footer 4' );
								}
						
						
						?>
					</div>
				</div>
			
				<div id="footer-bottom">
						<div class="center">
							<?php echo(get_theme_mod( 'footer_text', '<p>Theme created by <a href="http://mickdegraaf.nl" rel="nofollow">Mick de Graaf</a></p>' )); ?>
						</div>
				</div>
				
			</footer>
		<?php wp_footer(); ?>
	</body>
</html>