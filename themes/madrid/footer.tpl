{*
* 2007-2015 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2015 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}
{if !isset($content_only) || !$content_only}
						</div><!-- .background -->
					</div><!-- #center_column -->

					{if isset($left_column_size) && !empty($left_column_size)}
					<aside class="sidebar col-md-3 col-sm-4 col-xs-12 col-md-pull-9 col-sm-pull-8">
						{$HOOK_LEFT_COLUMN}
					</aside>
					{/if}

					{if isset($right_column_size) && !empty($right_column_size)}
					<aside class="sidebar right-column col-md-3 col-sm-4 col-xs-12">
						{$HOOK_RIGHT_COLUMN}
					</aside>
					{/if}

				</div><!-- #columns -->
				{hook h="displayAfterContent"}
			</div><!-- .content -->

			{if isset($HOOK_FOOTER)}
			<div class="prefooter">
				<div class="container">
					{hook h='displayBeforeFooter'}
					<div class="col-md-5 col-sm-12 col-xs-12 social-icons">
						<div class="row">
							{if $theme_options['ph_socialbox_dribbble']}
							<div class="item dribbble">
								<a href="https://www.instagram.com/semillaslowcost/" rel="nofollow"><i class="icon icon-instagram"></i></a>
							</div>
							{/if}

							{if $theme_options['ph_socialbox_fb']}
							<div class="item facebook">
								<a href="{$theme_options['ph_socialbox_fb']}" rel="nofollow"><i class="icon icon-facebook"></i></a>
							</div>
							{/if}

							{if $theme_options['ph_socialbox_tw']}
							<div class="item twitter">
								<a href="{$theme_options['ph_socialbox_tw']}" rel="nofollow"><i class="icon icon-twitter"></i></a>
							</div>
							{/if}

							{if $theme_options['ph_socialbox_g']}
							<div class="item google-plus">
								<a href="{$theme_options['ph_socialbox_g']}" rel="nofollow"><i class="icon icon-google-plus"></i></a>
							</div>
							{/if}

							{if $theme_options['ph_socialbox_be']}
							<div class="item behance">
								<a href="https://www.youtube.com/channel/UCQ6QPUE3DcMnTCKTrfcMWBA" rel="nofollow"><i class="icon icon-youtube"></i></a>
							</div>
							{/if}
						</div><!--. row -->
					</div><!-- .social-icons -->
				</div><!-- .container -->	

				<div class="prefooter-blocks">
					<div class="container">
						<div class="row clearBoth">
						{$HOOK_FOOTER}
						</div><!--. row -->
					</div><!-- .container -->
				</div><!-- .prefooter-blocks -->
			</div><!-- .prefooter -->
			{/if}

		<footer class="bottom">
			<div class="container">

				<a href="https://www.semillaslowcost.com/content/1-envios" rel="nofollow"><img src="https://www.semillaslowcost.com/img/pagos1.png" class="pagos-img pagos1" alt="formas de pagos y envios SLC"><img src="https://www.semillaslowcost.com/img/pagos2.png"  class="pagos-img" alt="formas de pagos y envios SLC"></a>
			<p>{$theme_options['copyright']} <strong>&copy; {$smarty.now|date_format:"%Y"} SemillasLowCost</strong></p>
			</div><!-- .container -->

		</footer>
	</div><!-- .boxed-wrapper -->

{/if}


{include file="$tpl_dir./global.tpl"}


	</body>
</html>
