<div class="ltabs-tabs-wrap <?php echo $class_ltabs; ?>">
	<div class="ltabs-tabs cf">
		<?php
		foreach ($list as $tab){ ?>
			<div class="ltabs-tab <?php echo isset($tab['sel']) ? '  tab-sel tab-loaded' : ''; ?>"
				data-category-id="<?php echo $tab['category_id']; ?>"
				data-active-content-l=".items-category-<?php echo $tab['category_id']; ?>" >
				<div class="deals-cat">
					<?php if ($tab_icon_display == '1'){ ?>
						<div class="ltabs-tab-img">
							<img src="<?php echo $tab['icon_image'] ?>"
								 title="<?php echo $tab['name']; ?>" alt="<?php echo $tab['name']; ?>"
								 style="width: <?php echo $imgcfgcat_width?>px; height:<?php echo $imgcfgcat_height?>px;"/>
						</div>
					<?php   } ?>
					<span class="ltabs-tab-label">
						<?php if(strlen($tab['name']) > $tab_max_characters && $tab_max_characters != '0') {echo
							utf8_substr(strip_tags(html_entity_decode($tab['name'], ENT_QUOTES, 'UTF-8')), 0, $tab_max_characters) . '..';}else{ echo $tab['name'];}
							?>
					</span>
				</div>
			</div>
		<?php } ?>
	</div>
</div>
