<div class="module <?php echo $class_suffix; ?>">
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
		<div class="box so-popular-tag">
			<?php if($disp_title_module){?>
			<h3 class="box-heading modtitle">
				<?php echo $head_name; ?>
			</h3>
			<?php }?>
			<div class="box-content">
				<?php if($data) { ?>
					<?php echo $data; ?>
				<?php } else { ?>
					<p><?php echo $text_notags; ?></p>
				<?php } ?>
			</div>
		</div>
	</div> <!-- /.modcontent-->
	<?php if($post_text != '')
	{
	?>
		<div class="form-group">
			<?php echo html_entity_decode($post_text);?>
		</div>
	<?php 
	}
	?>
</div>