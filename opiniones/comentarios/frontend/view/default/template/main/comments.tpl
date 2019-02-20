						



 
						 <!-- Generador del slider -->
						<?php	
								
								$contador2 = 0;
								foreach($test as $key => $value):
								$banco = $value['category_name'];
								$bancolimpio = str_replace(' ', '-', $banco);
								$bancolimpio = str_replace('\'', '-', $bancolimpio);
								$banco = str_replace('Autoflorecientes', '', $banco);
								$banco = str_replace('Feminizadas', '', $banco);
								if($contador2 == 0){		
										echo '<h3>Variedades de '.$banco.'</h3>';
										echo '<ul class="opiniones-geneticas">';
									}	

								echo '<li><a href="https://www.semillaslowcost.com/'
									.$value['category_link'].
									'/'
									.$value['id_product'].
									'-'
									.$value['link_rewrite'].
									'.html" title="">'.$value['name'].'</a></li>';
								

									$contador2 = $ontador2 +1;			
								endforeach;
								echo '</ul>';

								$contador = 0;
								$banco = '';
								foreach($test as $key => $value):
								$contador = $contador + 1;
								$banco = $value['category_name'];

									if($contador <= 4){
									$imagenesHtml = $imagenesHtml.'<div class=" col-md-3 col-sm-6 col-xs-12 product" itemtype="http://schema.org/Product" itemscope="">
						                                     <div class="inner second-image imgopi'.$contador.'"><div class="img_hover"></div>
						                                          <a itemprop="url" href="https://www.semillaslowcost.com/index.php?id_product='.$value['id_product'].'&controller=product" title="'.$value['name'].'">
						                                          <img itemprop="image" src="https://www.semillaslowcost.com/'.$value['id_image'].'-home_default/'.$value['link_rewrite'].'.jpg" alt="Comprar '.$value['name'].'" title="Comprar '.$value['name'].'" class="img-responsive first-image">
						                                          </a>
						                                           <div class="info">
						                                             <h3 itemprop="name">
						                                                <a itemprop="url" href="https://www.semillaslowcost.com/index.php?id_product='.$value['id_product'].'&controller=product" title="'.$value['name'].'">'.$value['name'].'
						                                                </a>
						                                             </h3>
						                                            </div>
						                                          </div>
						                                       
						                                    </div>
						                                    ';
										}
								endforeach;

									echo  '<div class="popular_products carousel-style animated activate fadeInRight" data-fx="fadeInRight">
													<div class="heading_block margin-bottom"><h3 class="pull-left"> <img class="icon icon-money main-color" width="38px" height="38px" style="padding:1%;" src="https://www.semillaslowcost.com/img/hojaico.png"> Algunas genéticas del banco '.$banco.'</h3></div>
														<div class="product_list_ph clearBoth owl-carousel-ph items-3 owl-carousel owl-theme owl-loaded" data-max-items="3">
															<div class="row">	
															<div class="product_list_ph clearBoth owl-carousel-ph items-3 owl-carousel owl-theme owl-loaded" data-max-items="3">	
															 	<div class="owl-stage-outer">	
															<div class="owl-stage" style="width: 100%; transform: translate3d(0px, 0px, 0px); transition: 0s;">			
																	                                    <!-- -inicio 3 imagenes -->
						                             '.$imagenesHtml.'
						                                 </div> </div> </div></div></div></div>
						                                 <!-- Fin row imagenes -->'
						?>

                              


<div id="cmtx_comments_container" class="cmtx_comments_container cmtx_clear">
	<h3 class="cmtx_comments_heading">
		<?php echo $lang_heading_comments; ?>
			
	</h3>

	<?php if ($comments) { ?>
		<?php if ($rich_snippets_enabled) { ?>
			<div itemscope itemtype="http://schema.org/<?php echo $rich_snippets_type; ?>">
		<?php } ?>

		<?php if ($row_two) { ?>
			<div class="cmtx_comments_row_two cmtx_clear">
				<div class="cmtx_row_left <?php if ($comments_position_4 == '&nbsp;') { echo 'cmtx_empty_position'; } ?>"><?php echo $comments_position_4; ?></div>
				<div class="cmtx_row_middle <?php if ($comments_position_5 == '&nbsp;') { echo 'cmtx_empty_position'; } ?>"><?php echo $comments_position_5; ?></div>
				<div class="cmtx_row_left <?php if ($comments_position_1 == '&nbsp;') { echo 'cmtx_empty_position'; } ?>"><?php echo $comments_position_1; ?></div>
			</div>
		<?php } ?>

		<div class="cmtx_comment_boxes">
			<?php foreach ($comments as $comment) { ?>
				<?php $has_replies = false; ?>
				<?php $reply_depth = 0; ?>
				<?php $reply_num = 0; ?>

				<?php if ($comment['reply_id']) { ?>
					<?php $has_replies = true; ?>
				<?php } ?>

				<section class="cmtx_comment_section">
					<?php require($this->loadTemplate('main/comment')); ?>

					<?php if ($has_replies) { ?>
						<div class="cmtx_replies_group">
					<?php } ?>

					<?php foreach ($comment['reply_id'] as $reply) { ?>
						<?php $reply_depth = 1; ?>
						<?php $reply_num++; ?>
						<?php $comment = $reply; ?>
						<?php require($this->loadTemplate('main/comment')); ?>

						<?php foreach ($comment['reply_id'] as $reply) { ?>
							<?php $reply_depth = 2; ?>
							<?php $reply_num++; ?>
							<?php $comment = $reply; ?>
							<?php require($this->loadTemplate('main/comment')); ?>

							<?php foreach ($comment['reply_id'] as $reply) { ?>
								<?php $reply_depth = 3; ?>
								<?php $reply_num++; ?>
								<?php $comment = $reply; ?>
								<?php require($this->loadTemplate('main/comment')); ?>

								<?php foreach ($comment['reply_id'] as $reply) { ?>
									<?php $reply_depth = 4; ?>
									<?php $reply_num++; ?>
									<?php $comment = $reply; ?>
									<?php require($this->loadTemplate('main/comment')); ?>

									<?php foreach ($comment['reply_id'] as $reply) { ?>
										<?php $reply_depth = 5; ?>
										<?php $reply_num++; ?>
										<?php $comment = $reply; ?>
										<?php require($this->loadTemplate('main/comment')); ?>
									<?php } ?>
								<?php } ?>
							<?php } ?>
						<?php } ?>
					<?php } ?>

					<?php if ($has_replies) { ?>
						</div>
					<?php } ?>
				</section>
			<?php } ?>
		</div>

		<?php if ($is_permalink || $is_search) { ?>
			<div class="cmtx_return"><?php echo $lang_text_return; ?></div>
		<?php } ?>

		

		<?php if ($row_four) { ?>
			<div class="cmtx_comments_row_four cmtx_clear">
				<div class="cmtx_row_left <?php if ($comments_position_10 == '&nbsp;') { echo 'cmtx_empty_position'; } ?>"><?php echo $comments_position_10; ?></div>
				<div class="cmtx_row_middle <?php if ($comments_position_11 == '&nbsp;') { echo 'cmtx_empty_position'; } ?>"><?php echo $comments_position_11; ?></div>
				<div class="cmtx_row_right <?php if ($comments_position_12 == '&nbsp;') { echo 'cmtx_empty_position'; } ?>"><?php echo $comments_position_12; ?></div>
			</div>
		<?php } ?>

		<div class="cmtx_loading_icon"></div>

		<div class="cmtx_action_message cmtx_action_message_success"></div>
		<div class="cmtx_action_message cmtx_action_message_error"></div>

		<?php if ($show_share) { ?>
			<div class="cmtx_share_box">
				<?php if ($show_share_digg) { ?>
					<a href="#" <?php if ($share_new_window) { echo 'target="_blank"'; } ?> title="<?php echo $lang_title_digg; ?>"><span class="cmtx_share cmtx_share_digg"></span></a>
				<?php } ?>

				<?php if ($show_share_facebook) { ?>
					<a href="#" <?php if ($share_new_window) { echo 'target="_blank"'; } ?> title="<?php echo $lang_title_facebook; ?>"><span class="cmtx_share cmtx_share_facebook"></span></a>
				<?php } ?>

				<?php if ($show_share_google) { ?>
					<a href="#" <?php if ($share_new_window) { echo 'target="_blank"'; } ?> title="<?php echo $lang_title_google; ?>"><span class="cmtx_share cmtx_share_google"></span></a>
				<?php } ?>

				<?php if ($show_share_linkedin) { ?>
					<a href="#" <?php if ($share_new_window) { echo 'target="_blank"'; } ?> title="<?php echo $lang_title_linkedin; ?>"><span class="cmtx_share cmtx_share_linkedin"></span></a>
				<?php } ?>

				<?php if ($show_share_reddit) { ?>
					<a href="#" <?php if ($share_new_window) { echo 'target="_blank"'; } ?> title="<?php echo $lang_title_reddit; ?>"><span class="cmtx_share cmtx_share_reddit"></span></a>
				<?php } ?>

				<?php if ($show_share_stumbleupon) { ?>
					<a href="#" <?php if ($share_new_window) { echo 'target="_blank"'; } ?> title="<?php echo $lang_title_stumbleupon; ?>"><span class="cmtx_share cmtx_share_stumbleupon"></span></a>
				<?php } ?>

				<?php if ($show_share_twitter) { ?>
					<a href="#" <?php if ($share_new_window) { echo 'target="_blank"'; } ?> title="<?php echo $lang_title_twitter; ?>"><span class="cmtx_share cmtx_share_twitter"></span></a>
				<?php } ?>
			</div>
		<?php } ?>

		<?php if ($show_flag) { ?>
			<div id="cmtx_flag_dialog" title="<?php echo $lang_dialog_flag_title; ?>" style="display:none">
				<span class="ui-icon ui-icon-alert"></span> <?php echo $lang_dialog_flag_content; ?>
			</div>
		<?php } ?>

		<?php if ($show_permalink) { ?>
			<div class="cmtx_permalink_box">
				<div><?php echo $lang_text_permalink; ?></div>

				<input type="text" name="cmtx_permalink" id="cmtx_permalink" class="cmtx_permalink" value="">

				<div><a href="#"><?php echo $lang_link_close; ?></a></div>
			</div>
		<?php } ?>

		<script>cmtx_js_settings_comments = <?php echo json_encode($cmtx_js_settings_comments); ?>;</script>

		<?php if ($rich_snippets_enabled) { ?>
			</div>
		<?php } ?>
	<?php } else if ($permalink_no_results) { ?>
		<div class="cmtx_no_permalink"><?php echo $lang_text_no_permalink; ?></div>
	<?php } else if ($search_no_results) { ?>
		<div class="cmtx_no_results"><?php echo $lang_text_no_results; ?></div>
	<?php } else { ?>
		<div class="cmtx_no_comments"><?php echo $lang_text_no_comments; ?></div>
	<?php } ?>
</div>