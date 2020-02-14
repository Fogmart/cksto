<header id="header" class=" variant typeheader-<?php echo isset($typeheader) ? $typeheader : '1'?>">
	<div class="header-top compact-hidden">
		<div class="container">
			<div class="row">
				<div class="col-lg-7 col-md-6 col-sm-8 header-top-left collapsed-block compact-hidden">
					<!-- LANGUAGE CURENTY -->
					<?php if($lang_status):?>
						<?php echo $currency; ?>
						<?php echo $language; ?>
					<?php endif; ?>

					<div class="tabBlocks form-group">
						<ul class="top-link list-inline">
							<li class="account" id="my_account"><a href="<?php echo $account; ?>" title="<?php echo $text_account; ?>" class="top-link-t btn-xs dropdown-toggle" data-toggle="dropdown"> <span ><i class="fa fa-user hidden-xs"></i><?php echo $text_account; ?></span> <span class="fa fa-caret-down"></span></a>
								<ul class="dropdown-menu ">
									<?php if ($logged) { ?>
									<li><a href="<?php echo $account; ?>"><?php echo $text_account; ?></a></li>
									<li><a href="<?php echo $order; ?>"><?php echo $text_order; ?></a></li>
									<li><a href="<?php echo $transaction; ?>"><?php echo $text_transaction; ?></a></li>
									<li><a href="<?php echo $download; ?>"><?php echo $text_download; ?></a></li>
									<li><a href="<?php echo $logout; ?>"><?php echo $text_logout; ?></a></li>
									<?php } else { ?>
									<li><a href="<?php echo $register; ?>"><i class="fa fa-user"></i> <?php echo $text_register; ?></a></li>
									<li><a href="<?php echo $login; ?>"><i class="fa fa-pencil-square-o"></i> <?php echo $text_login; ?></a></li>
									<?php } ?>
								</ul>
							</li>

							<?php if($wishlist_status):?><li class="wishlist hidden-xs"><a href="<?php echo $wishlist; ?>" id="wishlist-total" class="top-link-t top-link-wishlist" title="<?php echo $text_wishlist; ?>"><span ><i class="fa fa-heart hidden-xs"></i><?php echo $text_wishlist; ?></span></a></li><?php endif; ?>

							<?php if($checkout_status):?><li class="checkout hidden-xs"><a href="<?php echo $checkout; ?>" class="top-link-t top-link-checkout" title="<?php echo $text_checkout; ?>"><span ><i class="fa fa-external-link hidden-xs"></i><?php echo $text_checkout; ?></span></a></li><?php endif; ?>
						</ul>
					</div>
				</div>
				<div class="col-lg-5 col-md-6 col-sm-4 hidden-xs header-top-right  form-inline">
					<div class="inner">
						
						<div class="form-group navbar-welcome hidden-xs">
							<?php
								if (isset($welcome_message) && is_string($welcome_message)) {
									echo html_entity_decode($welcome_message, ENT_QUOTES, 'UTF-8');
								} else {echo 'Default welcome msg!';}
							?>
						</div>
						
						<?php echo '<div class="form-group navbar-phone hidden-sm hidden-xs"><i class="fa fa-phone"></i> '.html_entity_decode($contact_number, ENT_QUOTES, 'UTF-8').'</div>';
						?>
						
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<!-- HEADER CENTER -->
	<div class="header-center">
		<div class="container">
			<div class="row">
				<div class="col-lg-12 col-md-12 header-center-left">
					<?php //HEADER LOGO ?>
					<div class="header-logo">
					   <?php $this->soconfig->get_logo(); ?>
				    </div>
				    <?php //HEADER SEARCH ?>
				    <div class="header-search">
						<?php echo $content_search; ?>
					</div>
					<?php //HEADER CART ?>
					<div class="header-cart">
						<div class="shopping_cart pull-right">
						   <?php echo $cart; ?>
					    </div>
					</div>		
				</div>
			</div>
		</div>
	</div>
	
	<!-- HEADER BOTTOM -->
	<div class="header-bottom">
		<div class="container">
			<?php if ($content_menu): ?>
			<div class="header-navigation">
			   <?php echo $content_menu; ?>
			</div>
			<?php endif ?>
		</div>
	</div>
	
	<!-- Navbar switcher -->
	<?php if (!isset($toppanel_status) || $toppanel_status != 0) : ?>
	<?php if (!isset($toppanel_type) || $toppanel_type != 2 ) :  ?>
	<div class="navbar-switcher-container">
		<div class="navbar-switcher">
			<span class="i-inactive">
				<i class="fa fa-caret-down"></i>
			</span>
			 <span class="i-active fa fa-times"></span>
		</div>
	</div>
	<?php endif; ?>
	<?php endif; ?>
</header>