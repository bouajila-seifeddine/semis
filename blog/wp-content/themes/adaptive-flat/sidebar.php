<div id="sidebar">
		
		<?php
					if (is_front_page())   { //If page is the front-page show home sidebar
							dynamic_sidebar( 'Home Sidebar' )	;		   			
					}
					else if( get_post_type() == "giga_game" && !is_search() ) {//if it is a giga games plugin
							dynamic_sidebar( 'Single Game' )	;
					}
					else
					{
							dynamic_sidebar( 'General Sidebar' );
					}
		?>
</div>