
<?php
     if($image_bg_display){
        $bg = 'background: url(\''.HTTP_SERVER.'image/'.$image.'\')';
    }else{
        $bg = 'background-color: #FFF';
    }
?>
<div class="module <?php echo $direction_class?> <?php echo $class_suffix; ?>">

	<?php if($pre_text != '')
	{
	?>
	<div class="form-group">
		<?php echo html_entity_decode($pre_text);?>
	</div>
	<?php
	}
	?>

	<div class="modcontent">
		<?php
		if (!empty($list)) {
		?>
		<!--[if lt IE 9]>
		<div id="<?php echo $tag_id; ?>" class="so-deals-category msie lt-ie9 first-load module"><![endif]-->
		<!--[if IE 9]>
		<div id="<?php echo $tag_id; ?>" class="so-deals-category msie first-load module"><![endif]-->
		<!--[if gt IE 9]><!-->
		<div id="<?php echo $tag_id; ?>" class="so-deals-category first-load"><!--<![endif]-->
			<div class="so-deals-cat-wrap">
				<div class="so-deals-cat-container so-deals-cat-ip" style="<?php echo $bg; ?>"	 data-ajaxurl="<?php echo "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]" ?>" >
					<div class="bg_cat"></div>
					<div class="so-deals-cat">
						<?php include("default_tabs.tpl");	?>
					</div>
				</div>
				<div class="wap-deals-cat wap-deals-cat-ip">
					<div class="deals-cat-items-container so-deals-cat-container-ip deals-cat-ip-transverse"><!--Begin Items-->
					<?php
					foreach ($list as $key => $items) {

					$child_items = isset($items['child']) ? $items['child'] : '';
					$cls = (isset($items['sel']) && $items['sel'] == "sel") ? ' deal-cat-items-selected deal-cat-items-loaded' : '';
					$cls .= ' items-category-' . $items['category_id'];
					$tab_id = isset($list[$key]['sel']) ? $items['category_id'] : '';
					?>
					<div class="deal-cat-items <?php echo $cls; ?>" data-total="<?php echo $items['count'] ?>">
						<?php

						if (!empty($child_items)) {
							include("default_items.tpl");
						} else { ?>
							<div class="deal-cat-loading"></div>
						<?php  } ?>

					</div>
					<?php } ?>
					</div>
				</div>
				<div class="so-deals-cat-container so-deals-cat-all" data-ajaxurl="<?php echo "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]" ?>" >
					<?php if($disp_title_module) { ?>
						<div class="title-deal">
							<h2><?php echo $head_name; ?></h2>
						</div>
					<?php } ?>
					<div class="bg_cat"></div>
					<div class="so-deals-cat">
						<p>Mauris ut tincidunt nisi, id auctor libero. Etiam aliquet felis et consectetur faucibus. Praesent aliquam, lec tempus consequat,deserunt jowl prosciutto boudin.</p>
						<?php include("default_tabs_home2.tpl");	?>
					</div>
				</div> <!--/.so-deals-cat-container-->
			<!--End Items-->
			</div> <!--/.so-deals-cat-wrap-->
		</div> <!--/.so-deals-category-->
		<?php
			include("default_js.tpl");
		} else {
			echo '<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i>';
			echo $objlang->get('text_noitem');
			echo '</div>';
		}
		?>
	</div><!--/.modcontent-->

	<?php if($post_text != '')
	{
	?>
	<div class="form-group">
		<?php echo html_entity_decode($post_text);?>
	</div>
	<?php
	}
	?>

</div><!--/.module-->