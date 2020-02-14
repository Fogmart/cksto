<header id="header" class=" variant typeheader-<?php echo isset($typeheader) ? $typeheader : '1'?>">
	<div class="header-box compact-hidden">
		<div class="container">
			<div class="block-header">
				<div class="header-logo">
					<div class="navbar-logo">
					  <?php  $this->soconfig->get_logo();?>
				    </div>		
				</div>
				<div class="header-garenal">
					<div class="inner-full">
						<div class="inner-1">
							<div class="box-toplink">
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
									<!-- WISHLIST  -->
									<?php if($wishlist_status):?><li class="wishlist"><a href="<?php echo $wishlist; ?>" id="wishlist-total" class="top-link-t top-link-wishlist" title="<?php echo $text_wishlist; ?>"><span ><i class="fa fa-heart hidden-xs"></i><?php echo $text_wishlist; ?></span></a></li><?php endif; ?>
									<!-- COMPARE -->
									<?php if($checkout_status):?><li class="checkout"><a href="<?php echo $checkout; ?>" class="top-link-t top-link-checkout" title="<?php echo $text_checkout; ?>"><span ><i class="fa fa-external-link hidden-xs"></i><?php echo $text_checkout; ?></span></a></li><?php endif; ?>
								</ul>
							</div>
						</div>
						<div class="inner-2">
							<div class="box-lang-cur">
								<!-- LANGUAGE CURENTY -->
								<?php if($lang_status):?>
									<!--<?php echo $currency; ?>-->
									<?php echo $language; ?>
								<?php endif; ?>
								<!-- LEFT -->
								<ul class="form-group top-link">
									<?php
									if($phone_status)echo '<li class="navbar-phone"><i class="fa fa-phone"></i> '.html_entity_decode($contact_number, ENT_QUOTES, 'UTF-8').'</li>';
									?>
									<?php if($welcome_message_status):?>
									<li class="navbar-welcome hidden-xs">
										<?php
											if (isset($welcome_message) && is_string($welcome_message)) {
												echo html_entity_decode($welcome_message, ENT_QUOTES, 'UTF-8');
											} else {echo 'Default welcome msg!';}
										?>
									</li>
									<?php endif; ?>
								</ul>
								
								
							</div>
					
							<?php if ($content_search): ?>
							<div class="box-search">
							   <?php echo $content_search; ?>
							</div>
							<?php endif ?>						
						</div>
					</div>
				</div>
				<div class="header-navigation">
					<div class="header-bottom-right">
					 	<?php echo $content_menu; ?>
					</div>
				</div>
				<div class="header-cart">
					<div class="shopping_cart pull-right">
					   <?php echo $cart; ?>
				    </div>
				</div>
			</div>
		</div>
	</div>
	<?php if (!isset($soconfig_general["toppanel_status"]) || $soconfig_general["toppanel_status"] != 0) : ?>
	<?php if (!isset($soconfig_general["toppanel_type"]) || $soconfig_general["toppanel_type"] != 2 ) : ?>
	<div class="navbar-switcher-container">
		<div class="navbar-switcher">
			<span class="i-inactive">
				<?php if ((isset($soconfig_toppanel) && $soconfig_toppanel !== '') ) { ?>
					<img src="image/<?php echo  $soconfig_toppanel ?>" width="35" height="35" alt="toppanel">
				<?php } else { ?>
					<i class="fa fa-caret-down"></i>
				<?php } ?>
			</span>
			 <span class="i-active fa fa-times"></span>
		</div>
	</div>
	<?php endif; ?>
	<?php endif; ?>
</header>