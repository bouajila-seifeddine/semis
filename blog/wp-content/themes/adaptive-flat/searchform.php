<form class="searchform" method="get" action="/index.php">
	
	<input value="<?php the_search_query();?>" placeholder="<?php echo get_theme_mod('search_placeholder_text', 'Search');?>" type="text" name="s" class="s" size="15" />
	<input type="submit" class="searchsubmit" value=""  />

</form>