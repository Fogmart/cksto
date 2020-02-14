<script type="text/javascript">
//<![CDATA[
jQuery(document).ready(function ($) {
	;
	(function (element) {
		var $element = $(element),
			$tab = $('.ltabs-tab', $element),
			$tab_label = $('.ltabs-tab-label', $tab),
			$tabs = $('.deals-tabs', $element),
			ajax_url = $tabs.parents('.ltabs-tabs-container').attr('data-ajaxurl'),
			effect = $tabs.parents('.ltabs-tabs-container').attr('data-effect'),
			delay = $tabs.parents('.ltabs-tabs-container').attr('data-delay'),
			duration = $tabs.parents('.ltabs-tabs-container').attr('data-duration'),
			type_source = $tabs.parents('.ltabs-tabs-container').attr('data-type_source'),
			$items_content = $('.deal-cat-items', $element),
			$items_inner = $('.deal-cat-items-inner', $items_content),
			$items_first_active = $('.deal-cat-items-selected', $element),
			$load_more = $('.ltabs-loadmore', $element),
			$btn_loadmore = $('.ltabs-loadmore-btn', $load_more),
			$select_box = $('.ltabs-selectbox', $element),
			$tab_label_select = $('.ltabs-tab-selected', $element),
			setting = '<?php echo $setting; ?>',
			cat_cative = $('.item-timer',$element);
			cat_cative.each(function(){
				var _that = $(this), _date_time = new Date(_that.data('countdown'));
				CountDown(_date_time,_that);
			});
		enableSelectBoxes();
		function enableSelectBoxes() {
			$tab_wrap = $('.ltabs-tabs-wrap', $element),
				$tab_label_select.html($('.ltabs-tab', $element).filter('.tab-sel').children('.ltabs-tab-label').html());
			if ($(window).innerWidth() <= 479) {
				$tab_wrap.addClass('ltabs-selectbox');
			} else {
				$tab_wrap.removeClass('ltabs-selectbox');
			}
		}

		$('span.ltabs-tab-selected, span.ltabs-tab-arrow', $element).click(function () {
			if ($('.deals-tabs', $element).hasClass('ltabs-open')) {
				$('.deals-tabs', $element).removeClass('ltabs-open');
			} else {
				$('.deals-tabs', $element).addClass('ltabs-open');
			}
		});

		$(window).resize(function () {
			if ($(window).innerWidth() <= 479) {
				$('.ltabs-tabs-wrap', $element).addClass('ltabs-selectbox');
			} else {
				$('.ltabs-tabs-wrap', $element).removeClass('ltabs-selectbox');
			}
		});
		$tab.on('click.ltabs-tab', function () {
			var $this = $(this);
			if ($this.hasClass('tab-sel')) return false;
			if ($this.parents('.deals-tabs').hasClass('ltabs-open')) {
				$this.parents('.deals-tabs').removeClass('ltabs-open');
			}
			$tab.removeClass('tab-sel');
			$this.addClass('tab-sel');
			var items_active = $this.attr('data-active-content-l');
			var _items_active = $(items_active,$element);
			$items_content.removeClass('deal-cat-items-selected');
			_items_active.addClass('deal-cat-items-selected');
			$tab_label_select.html($tab.filter('.tab-sel').children('.ltabs-tab-label').html());
			var $loading = $('.deal-cat-loading', _items_active);
			var loaded = _items_active.hasClass('deal-cat-items-loaded');
			if (!loaded && !_items_active.hasClass('ltabs-process')) {
				_items_active.addClass('ltabs-process');
				var category_id 		= $this.attr('data-category-id');
				$loading.show();
				$.ajax({
					type: 'POST',
					url: ajax_url,
					data: {
						is_ajax_deals_category: 1,
						ajax_deals_cat_start: 0,
						categoryid: category_id,
						setting : setting,
						lbmoduleid: <?php echo $moduleid; ?>
					},
					success: function (data) {
						if (data.items_markup != '') {
							$('.deal-cat-loading', _items_active).replaceWith(data.items_markup);
							_items_active.addClass('deal-cat-items-loaded').removeClass('ltabs-process');
							$loading.remove();
							cat_cative = $('.item-timer',$element);
							cat_cative.each(function(){
								var _that = $(this), _date_time = new Date(_that.data('countdown'));
								CountDown(_date_time,_that);
							});
							if(typeof(_SoQuickView) != 'undefined'){
								_SoQuickView();
							}
						}
					},
					dataType: 'json'
				});

			} else {
				 var owl = $('.owl2-carousel' , _items_active);
				 owl = owl.data('owlCarousel2');
				 if (typeof owl !== 'undefined') {
					owl.onResize();
				 }
			}
		});
		function CountDown(date, id) {
			dateNow = new Date();
			amount = date.getTime() - dateNow.getTime();
			if (amount < 0 && id.length) {
				id.html("Now!");
			} else {
				days = 0;
				hours = 0;
				mins = 0;
				secs = 0;
				out = "";
				amount = Math.floor(amount / 1000);
				days = Math.floor(amount / 86400);
				amount = amount % 86400;
				hours = Math.floor(amount / 3600);
				amount = amount % 3600;
				mins = Math.floor(amount / 60);
				amount = amount % 60;
				secs = Math.floor(amount);
				if (days != 0) {
					out += "<div class='time-item time-day'>" + "<div class='num-time'>" + days + "</div>" + " <div class='name-time'>" + ((days == 1) ? "Day" : "Days") + "</div>" + "</div> ";
				}
				if(days == 0 && hours != 0)
				{
					out += "<div class='time-item time-hour' style='width:33.33%'>" + "<div class='num-time'>" + hours + "</div>" + " <div class='name-time'>" + ((hours == 1) ? "Hour" : "Hours") + "</div>" + "</div> ";
				}else if (hours != 0) {
					out += "<div class='time-item time-hour'>" + "<div class='num-time'>" + hours + "</div>" + " <div class='name-time'>" + ((hours == 1) ? "Hour" : "Hours") + "</div>" + "</div> ";
				}
				if(days == 0 && hours != 0)
				{
					out += "<div class='time-item time-min' style='width:33.33%'>" + "<div class='num-time'>" + mins + "</div>" + " <div class='name-time'>" + ((mins == 1) ? "Min" : "Mins") + "</div>" + "</div> ";
					out += "<div class='time-item time-sec' style='width:33.33%'>" + "<div class='num-time'>" + secs + "</div>" + " <div class='name-time'>" + ((secs == 1) ? "Sec" : "Secs") + "</div>" + "</div> ";
					out = out.substr(0, out.length - 2);
				}else if(days == 0 && hours == 0)
				{
					out += "<div class='time-item time-min' style='width:50%'>" + "<div class='num-time'>" + mins + "</div>" + " <div class='name-time'>" + ((mins == 1) ? "Min" : "Mins") + "</div>" + "</div> ";
					out += "<div class='time-item time-sec' style='width:50%'>" + "<div class='num-time'>" + secs + "</div>" + " <div class='name-time'>" + ((secs == 1) ? "Sec" : "Secs") + "</div>" + "</div> ";
					out = out.substr(0, out.length - 2);
				}else{
					out += "<div class='time-item time-min'>" + "<div class='num-time'>" + mins + "</div>" + " <div class='name-time'>" + ((mins == 1) ? "Min" : "Mins") + "</div>" + "</div> ";
					out += "<div class='time-item time-sec'>" + "<div class='num-time'>" + secs + "</div>" + " <div class='name-time'>" + ((secs == 1) ? "Sec" : "Secs") + "</div>" + "</div> ";
					out = out.substr(0, out.length - 2);
				}

				id.html(out);

				setTimeout(function () {
					
					CountDown(date, id);
				}, 1000);
				return out;
			}
		}
	})('#<?php echo $tag_id; ?>');
});
//]]>
</script>