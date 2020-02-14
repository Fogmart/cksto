<div class="deal-cat-items-inner owl2-carousel">
<?php
	if (!empty($child_items)) {
		$i = 0 ;$k = isset($rl_loaded ) ? $rl_loaded : 0; $count = count($child_items);
		foreach ($child_items as $product) {
			$i++; $k++; ?>
				<div class="deal-cat-item ">
				<div class="item-inner product-thumb transition">
					<?php if($product_image ==1){ ?>
						 <div class="image image-ip image-ip-transverse image-ipad">
							<div class="box-label">
							 <?php if ($product['special'] && $display_sale) : ?>
								<span class="label label-sale">
									<?php echo $objlang->get('text_sale'); ?>
									<?php  echo $product['discount']; ?>
								</span>
							<?php endif; ?>
							 <?php if ($product['productNew'] && $display_new) : ?>
								<span class="label label-new"><?php echo $objlang->get('text_new'); ?></span>
							<?php endif; ?>
							</div>	
							<a class="lt-image"
							   href="<?php echo $product['href'] ?>" target="<?php echo $item_link_target ?>"
							   title="<?php echo $product['name'] ?>">
								<?php if($product_image_num ==2){?>
									<img src="<?php echo $product['thumb']?>" class="img-thumb1" alt="<?php echo $product['name'] ?>">
									<img src="<?php echo $product['thumb2']?>" class="img-thumb2" alt="<?php echo $product['name'] ?>">
								<?php }else{?>
									<img src="<?php echo $product['thumb']?>" alt="<?php echo $product['name'] ?>">
								<?php }?>
							</a>
						</div>
						<?php if($display_title || $display_description){ ?>
							<div class="caption caption-ip caption-ipad" >
								<?php if ($display_title) { ?>
									<h4>
										<a href="<?php echo $product['href'] ?>"
										   title="<?php echo $product['name'] ?>" target="<?php echo $item_link_target ?>">
										   <?php  echo $product['name_maxlength'];?>
										</a>
									</h4>
								<?php } ?>
								<?php if ($product['price'] && $display_price) { ?>
								<div class="price">
									<?php if (!$product['special']) { ?>
									<span class="price product-price"><?php echo $product['price']; ?></span>
									<?php } else { ?>
									<span class="price-new"><?php echo $product['special']; ?></span><span class="price-old"><?php echo $product['price']; ?></span>
									<?php } ?>
								</div>
								<?php } ?>
								<?php if($display_rating) { ?>
								<?php if ($product['rating']) { ?>
									<div class="ratings">
									  <?php for ($j = 1; $j <= 5; $j++) { ?>
									  <?php if ($product['rating'] < $j) { ?>
									  <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-2x"></i></span>
									  <?php } else { ?>
									  <span class="fa fa-stack"><i class="fa fa-star fa-stack-2x"></i><i class="fa fa-star-o fa-stack-2x"></i></span>
									  <?php } ?>
									  <?php } ?>
									</div>
								<?php }else{ ?>
								<div class="ratings">
									<?php for ($j = 1; $j <= 5; $j++) { ?>
									<span class="fa fa-stack"><i class="fa fa-star-o fa-stack-2x"></i></span>
									<?php } ?>
								</div>
								<?php } ?>
								<?php } ?>
								<?php if ($display_description) { ?>
									<p class="desc"><?php echo  html_entity_decode($product['description_maxlength']); ?></p>
								<?php }	?>
								<?php if($display_countdown) { ?>
								<div class="item-time item-time-ip">
									<div class="item-timer product_time_<?php echo $product['product_id']?>" data-countdown="<?php echo $product['specialPriceToDate'] ?>" ></div>
								</div>
								<?php } ?>
							</div>
						<?php }?>
					<?php }else{ if($display_title || $display_description){ ?>
					<div class="caption caption-ip caption-ipad" style="width: 100%">
						<?php if ($display_title) { ?>
						<h4>
							<a href="<?php echo $product['href'] ?>"
							   title="<?php echo $product['nameFull'] ?>" target="<?php echo $item_link_target ?>">
								<?php  echo $product['name'];?>
							</a>
						</h4>
						<?php } ?>
						<?php if ($product['price'] && $display_price) { ?>
						<p class="price">
							<?php if (!$product['special']) { ?>
							<?php echo $product['price']; ?>
							<?php } else { ?>
							<span class="price-new"><?php echo $product['special']; ?></span>
							<?php } ?>
						</p>
						<?php } ?>
						<?php if($display_rating) { ?>
						<?php if ($product['rating']) { ?>
						<div class="ratings">
							<?php for ($j = 1; $j <= 5; $j++) { ?>
							<?php if ($product['rating'] < $j) { ?>
							<span class="fa fa-stack"><i class="fa fa-star-o fa-stack-2x"></i></span>
							<?php } else { ?>
							<span class="fa fa-stack"><i class="fa fa-star fa-stack-2x"></i><i class="fa fa-star-o fa-stack-2x"></i></span>
							<?php } ?>
							<?php } ?>
						</div>
						<?php }else{ ?>
						<div class="ratings">
							<?php for ($j = 1; $j <= 5; $j++) { ?>
							<span class="fa fa-stack"><i class="fa fa-star-o fa-stack-2x"></i></span>
							<?php } ?>
						</div>
						<?php } ?>
						<?php } ?>
						<?php if ($display_description) { ?>
						<p class="desc"><?php echo  html_entity_decode($product['description']); ?></p>
						<?php }	?>
						<?php if($display_countdown) { ?>
						<div class="item-time item-time-ip">
							<div class="item-timer product_time_<?php echo $product['product_id']?>" data-countdown="<?php echo $product['specialPriceToDate'] ?>" ></div>
						</div>
						<?php }	?>
					</div>
					<?php }} ?>
					</div>
				</div>
		<?php
		}
	} ?>
</div>
<script type="text/javascript">
	jQuery(document).ready(function($){
		var $tag_id = $('#<?php echo $tag_id; ?>'),
		parent_active = 	$('.items-category-<?php echo $tab_id; ?>', $tag_id),
		total_product = parent_active.data('total'),
		tab_active = $('.deal-cat-items-inner',parent_active),
		column = 1;
		tab_active.owlCarousel2({
			nav: <?php echo $display_nav ; ?>,
			dots: false,
			margin: 0,
			loop:  <?php echo $display_loop ; ?>,
			autoplay: <?php echo $autoplay; ?>,
			autoplayHoverPause: <?php echo $pausehover ; ?>,
			autoplayTimeout: <?php echo $autoplayTimeout ; ?>,
			autoplaySpeed: <?php echo $autoplaySpeed ; ?>,
			mouseDrag: <?php echo  $mousedrag; ?>,
			touchDrag: <?php echo $touchdrag; ?>,
			navRewind: true,
			navText: [ '', '' ],
			rtl: <?php echo $direction; ?>,
		    responsive: {
					0: {
						items: column,
						nav: total_product <= column ? false : ((<?php echo $display_nav ; ?>) ? true: false),
					},
					480: {
						items: column,
						nav: total_product <= column ? false : ((<?php echo $display_nav ; ?>) ? true: false),
					},
					768: {
						items: column,
						nav: total_product <= column ? false : ((<?php echo $display_nav ; ?>) ? true: false),
					},
					992: {
						items: column,
						nav: total_product <= column ? false : ((<?php echo $display_nav ; ?>) ? true: false),
					},
					1200: {
						items: column,
						nav: total_product <= column ? false : ((<?php echo $display_nav ; ?>) ? true: false),
					},
				}
		 });

	});
</script>
