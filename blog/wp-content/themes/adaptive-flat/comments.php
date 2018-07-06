<div id="comments">

<ul><?php wp_list_comments( array('avatar_size'       => 100,) ); ?></ol>


<?php comment_form(); ?>

<?php paginate_comments_links()?>

</div>