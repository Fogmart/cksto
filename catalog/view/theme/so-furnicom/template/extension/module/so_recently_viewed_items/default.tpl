<?php
$tag_id = "so_recently_viewed_items_".rand().time();
$count_item = count($products);
?>
<div id="<?php echo $tag_id;?>" class="recently-viewed module <?php echo $direction_class?> <?php echo $class_suffix; ?> position-mod-<?php echo $position_mod;?>" style = "top:<?php echo $style_top;?>">
	<div class="modhead1">
		<div class="icon-view" data-toggle="tooltip" data-placement="top" title="<?php echo $head_name; ?>">
			<i class="fa fa-eye"></i>
		</div>
	</div>
	<div class="modhead2">
		<i class="fa fa-close"></i>
		<?php if($disp_title_module) { ?>
			<h3 class="modtitles"><?php echo $head_name; ?></h3>
		<?php } ?>
	</div>


<div class="modcontent">
	<?php if($pre_text != '')
	{
	?>
	<div class="form-group">
		<?php echo html_entity_decode($pre_text);?>
	</div>
	<?php
	}
	?>
<?php if($products){?>
<div class="item-wrap <?php if($type_module == "slider") echo "slider";?>">
<?php $i=0; foreach($products as $product)  { $i++;?>
	<?php if ($i % $nb_rows == 1 && $type_module == "slider" || $nb_rows == 1 && $type_module == "slider") { ?>
	<div class="item">
	<?php } ?>
		<div class="item-element">
			<div class="item-inner">
				<div class="product-thumb transition">
				<?php if($product_image) { ?>
					<div class="image media-left">
					<?php if ($product['special'] && $display_sale) : ?>
						<span class="label label-sale"><?php echo $objlang->get('text_sale'); ?></span>
						<?php endif; ?>
						<?php if ($product['productNew'] && $display_new) : ?>
						<span class="label label-new"><?php echo $objlang->get('text_new'); ?></span>
						<?php endif; ?>
						<a class="lt-image" href="<?php echo $product['href'] ?>" target="<?php echo $item_link_target ?>" title="<?php echo $product['nameFull'] ?>">

							<?php if($product_image_num ==2){?>
								<img src="<?php echo $product['thumb']?>" class="img-thumb1" alt="<?php echo $product['nameFull'] ?>">
								<img src="<?php echo $product['thumb2']?>" class="img-thumb2" alt="<?php echo $product['nameFull'] ?>">
							<?php }else{?>
								<img src="<?php echo $product['thumb']?>" alt="<?php echo $product['nameFull'] ?>">
							<?php }?>
						</a>
					</div>
				<?php } ?>
				<?php if($display_title || $display_price)
				{
				?>
					<div class="caption media-right">
					<?php if($display_title){ ?>
						<h4><a href="<?php echo $product['href']; ?>" target="<?php echo $item_link_target;?>"><?php echo html_entity_decode($product['name']); ?></a></h4>
					<?php } ?>
					<?php if ($display_price) { ?>
						<?php if ($product['price']) { ?>
						<p class="price">
						   <?php echo $product['price']; ?>
						</p>
						<?php } ?>
					<?php } ?>
					<?php if($display_rating):?>
						<?php if ($product['rating']) { ?>
							<div class="ratings">
								<?php for ($k = 1; $k <= 5; $k++) { ?>
								<?php if ($product['rating'] < $k) { ?>
								<span class="fa fa-stack"><i class="fa fa-star-o fa-stack-2x"></i></span>
								<?php } else { ?>
								<span class="fa fa-stack"><i class="fa fa-star fa-stack-2x"></i><i class="fa fa-star-o fa-stack-2x"></i></span>
								<?php } ?>
								<?php } ?>
							</div> <!-- /.rating -->
						<?php }else{ ?>
							<div class="ratings">
								<?php for ($j = 1; $j <= 5; $j++) { ?>
								<span class="fa fa-stack"><i class="fa fa-star-o fa-stack-2x"></i></span>
								<?php } ?>
							</div> <!-- /.rating -->
						<?php } ?>
					<?php endif;?>
					</div>
				<?php
				}
				?>
				</div>
			</div>
		</div>
	<?php if ($i % $nb_rows == 0 && $type_module == "slider" || $i == $count_item && $type_module == "slider") { ?>
	</div> <!-- /.item -->
	<?php } ?>
<?php
} ?>
</div>
<?php if($type_module == "slider"):?>
<?php
$btn_prev = '&#139;';
$btn_next = '&#155;';
?>
<script type="text/javascript">
	//<![CDATA[
	jQuery(document).ready(function ($) {
		(function (element) {
			var id = $("#<?php echo $tag_id; ?>");
			var $element = $(element),
				$extraslider = $(".slider", $element),
				_delay = <?php echo $delay; ?>,
				_duration = <?php echo $duration; ?>,
				_effect = '<?php echo $effect; ?>';

			$extraslider.on("initialized.owl.carousel2", function () {
				var $item_active = $(".owl2-item.active", $element);
				if (_effect != "none") {
					_getAnimate($item_active);
				}
				else {
					var $item = $(".owl2-item", $element);
					$item.css({"opacity": 1, "filter": "alpha(opacity = 100)"});
				}
			});

			$extraslider.owlCarousel2({
				rtl: <?php echo $direction?>,
				margin: 0,
				slideBy: 1,
				autoplay: <?php echo $autoplay;?>,
				autoplayHoverPause: <?php echo $pausehover ;?>,
				autoplayTimeout: <?php echo $autoplay_timeout; ?>,
				autoplaySpeed: <?php echo $autoplaySpeed; ?>,
				startPosition: <?php echo $startPosition; ?>,
				mouseDrag: <?php echo $mouseDrag;?>,
				touchDrag: <?php echo $touchDrag; ?>,
				autoWidth: false,
						responsive: {
					0:{	items: 1
					},
					480:{ items: 1
					},
					768:{ items: 1
					},
					992:{ items: 1
					},
					1200:{ items: 1
					}
				},
				dots: <?php echo $dots;?>,
				dotClass: "owl2-dot",
				dotsClass: "owl2-dots",
				nav: <?php echo $nav; ?>,
				loop: true,
						navSpeed: <?php echo $navSpeed; ?>,
				navText: ["<?php echo $btn_prev; ?>", "<?php echo $btn_next; ?>"],
						navClass: ["owl2-prev", "owl2-next"]

			});

		$extraslider.on("translate.owl.carousel2", function (e) {

			var $item_active = $(".owl2-item.active", $element);
			_UngetAnimate($item_active);
			_getAnimate($item_active);
		});

		$extraslider.on("translated.owl.carousel2", function (e) {


			var $item_active = $(".owl2-item.active", $element);
			var $item = $(".owl2-item", $element);

			_UngetAnimate($item);

			if (_effect != "none") {
				_getAnimate($item_active);
			} else {

				$item.css({"opacity": 1, "filter": "alpha(opacity = 100)"});

			}
		});

		function _getAnimate($el) {
			if (_effect == "none") return;
			//if ($.browser.msie && parseInt($.browser.version, 10) <= 9) return;
			$extraslider.removeClass("extra-animate");
			$el.each(function (i) {
				var $_el = $(this);
				$(this).css({
					"-webkit-animation": _effect + " " + _duration + "ms ease both",
					"-moz-animation": _effect + " " + _duration + "ms ease both",
					"-o-animation": _effect + " " + _duration + "ms ease both",
					"animation": _effect + " " + _duration + "ms ease both",
					"-webkit-animation-delay": +i * _delay + "ms",
					"-moz-animation-delay": +i * _delay + "ms",
					"-o-animation-delay": +i * _delay + "ms",
					"animation-delay": +i * _delay + "ms",
					"opacity": 1
				}).animate({
					opacity: 1
				});

				if (i == $el.size() - 1) {
					$extraslider.addClass("extra-animate");
				}
			});
		}

		function _UngetAnimate($el) {
			$el.each(function (i) {
				$(this).css({
					"animation": "",
					"-webkit-animation": "",
					"-moz-animation": "",
					"-o-animation": "",
					"opacity": 0
				});
			});
		}

	})("#<?php echo $tag_id ; ?>");
	});
	//]]>
</script>
<?php endif;?>

<?php
} else {
    echo '<p style="padding: 10px">'.$objlang->get('text_noitem').'</p>';
} ?>
<?php if($post_text != '')
{
?>
	<div class="form-group">
		<?php echo html_entity_decode($post_text);?>
	</div>
<?php
}
?>
</div> <!-- /.modcontent -->
<script>
jQuery(document).ready(function ($) {
	<?php if($type_module != 'slider'):?>
	var heightWindow = parseInt($(window).height());
	var heightItem = parseInt($('.recently-viewed .modcontent .item-element').height());
	var countItem = $('.recently-viewed .modcontent .item-element').length;
	$('.recently-viewed .modcontent .item-element').css('display','none');
	$('.recently-viewed .modcontent .item-element').each(function(){
		$(this).css('display','block');
		var heightMod = parseInt($('.recently-viewed').outerHeight( true ));
		var topMod = parseInt($('.recently-viewed').css('top'));
		var heightAllMod = parseInt(heightMod + topMod);
		if(heightAllMod > heightWindow)
		{
			$(this).css('display','none');
		}
	});

	<?php endif;?>
	$('.recently-viewed .modhead2 .fa-close').click(function(){
		$(this).parent().parent().children('.modcontent').hide();
		$(this).parent().parent().children('.modhead1').show();
		$(this).parent().parent().children('.modhead2').hide();
	});
	$('.recently-viewed .modhead1 .icon-view').click(function(){
		$(this).parent().parent().children('.modcontent').show();
		$(this).parent().parent().children('.modhead1').hide();
		$(this).parent().parent().children('.modhead2').show();
	});



});
</script>
</div> <!-- /.module -->


