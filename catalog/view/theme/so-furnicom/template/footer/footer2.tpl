<footer class="footer-container typefooter-<?php echo isset($typefooter) ? $typefooter : '1'?>">
	<div class="container content">
		
		<!-- FOOTER TOP -->
		<div class="footer-top">
			<div class="row">
				<!-- CUSTOM BLOCK 1 -->
				<?php if (isset($socials_status) && $socials_status != 0) : ?>
				<div class="col-lg-4 col-sm-4  box-about">
					<div class="module clearfix">
						<div  class="modcontent" >
							<?php
							if (isset($footer_socials) && $footer_socials != '' && is_string($footer_socials)) :
								echo html_entity_decode($footer_socials, ENT_QUOTES, 'UTF-8');
							endif;
							?>
						</div>
					</div>
				</div>
				<?php endif; ?>

				
				<!-- CUSTOM BLOCK 2 -->
				<?php if (isset($footerpayment_status) && $footerpayment_status != 0) : ?>
				<div class="col-lg-4 col-sm-4  box-social">
					<div class="module clearfix">
						<div  class="modcontent" >
							<?php
							if (isset($footerpayment) && $footerpayment != '' && is_string($footerpayment)) :
								echo html_entity_decode($footerpayment, ENT_QUOTES, 'UTF-8');
							endif;
							?>
						</div>
					</div>
				</div>
				<?php endif; ?>

				<!-- CUSTOM BLOCK 3 -->
				<?php if (isset($customblock_status) && $customblock_status != 0) : ?>
				<div class="col-lg-4 col-sm-4  collapsed-block ">
					<div class="module clearfix">
						<div  class="modcontent" >
							<?php
							if (isset($customblock) && $customblock != '' && is_string($customblock)) :
							echo html_entity_decode($customblock, ENT_QUOTES, 'UTF-8');
							endif;
							?>
						</div>
					</div>
				</div>
				<?php endif; ?>

			</div>
		</div>
		<!-- FOOTER CENTER -->
		<div class="footer-center">
			<div class="row">
				<!-- BOX MY ACOUNT -->
				<div class="col-sm-3 col-md-3 box-account">
					<div class="module clearfix">
						<h3 class="footer-title"><?php echo $text_account; ?></h3>
						<div  class="modcontent" >
							<ul class="menu">
								<li><a href="<?php echo $account; ?>"><?php echo $text_account; ?></a></li>
								<li><a href="<?php echo $wishlist; ?>"><?php echo $text_wishlist; ?></a></li>
								<li><a href="<?php echo $affiliate; ?>"><?php echo $text_affiliate; ?></a></li>
								<li><a href="<?php echo $special; ?>"><?php echo $text_special; ?></a></li>
							</ul>
						</div>
					</div>
				</div>
				<!-- BOX INFOMATION --> 
				
				<?php if ($informations) :?>
					<div class="col-sm-3 col-md-3 box-information">
						<div class="module clearfix">
							<h3 class="footer-title"><?php echo $text_information; ?></h3>
							<div  class="modcontent" >
								<ul class="menu">
									<?php foreach ($informations as $information) { ?>
									<li><a href="<?php echo $information['href']; ?>"><?php echo $information['title']; ?></a></li>
									<?php } ?>
								</ul>
							</div>
						</div>
					</div>
				<?php endif; ?>
				
				<div class="col-sm-3 col-md-3 box-information">
					<div class="module clearfix">
						<h3 class="footer-title"><?php echo $text_extra; ?></h3>
						<div  class="modcontent" >
							<ul class="menu">
								<li><a href="<?php echo $manufacturer; ?>"><?php echo $text_manufacturer; ?></a></li>
								<li><a href="<?php echo $special; ?>"><?php echo $text_special; ?></a></li>
								<li><a href="<?php echo $voucher; ?>"><?php echo $text_voucher; ?></a></li>
								<li><a href="<?php echo $order; ?>"><?php echo $text_order; ?></a></li>
							</ul>
						</div>
					</div>
				</div>
				
				<!-- BOX SEVICER -->
				<div class="col-sm-3 col-md-3 box-service">
					<div class="module clearfix">
						<h3 class="footer-title"><?php echo $text_service; ?></h3>
						<div  class="modcontent" >
							<ul class="menu">
								<li><a href="<?php echo $contact; ?>"><?php echo $text_contact; ?></a></li>
								<li><a href="<?php echo $return; ?>"><?php echo $text_return; ?></a></li>
								<li><a href="<?php echo $sitemap; ?>"><?php echo $text_sitemap; ?></a></li>
							</ul>
						</div>
					</div>
				</div>	
			</div>
		</div>
		<!-- FOOTER BOTTOM -->
		<div class="footer-bottom ">
			<div class="row">
				<div class="col-sm-6 copyright-text">
					<?php 
					$datetime = new DateTime();
					$cur_year	= $datetime->format('Y');
					echo (!isset($copyright) || !is_string($copyright) ? $powered : str_replace('{year}', $cur_year, $copyright));?>
				</div>

				<?php if (isset($imgpayment_status) && $imgpayment_status != 0) : ?>
				<div class="col-sm-6 text-right">
					<?php
					if ((isset($imgpayment) && $imgpayment != '') ) { ?>
						<img src="image/<?php echo  $imgpayment ?>"  alt="imgpayment">
					<?php } ?>
				</div>
				<?php endif; ?>

			</div>

		</div>
	</div>

</footer>