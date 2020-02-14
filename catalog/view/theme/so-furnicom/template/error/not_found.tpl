<?php echo $header; ?>
<!-- BREADCRUMB -->
<div class="full-breadcrumb">
  <div class="title-breadcrumb font-sn">
      <?php $count = count($breadcrumbs);?>
      <?php $i=0; ?>
      <?php foreach ($breadcrumbs as $breadcrumb) { ?>
          <?php if( $i == $count-1){ ?>
             <?php echo '<b>'.$breadcrumb['text'].'</b>'; ?>
          <?php }else{ ?>
            <a href="<?php echo $breadcrumb['href']; ?>"></a>
          <?php } ?>      

        <?php $i++; ?> 
      <?php } ?>
  </div>
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
</div>
<div class="container">
  <div class="row"><?php echo $column_left; ?>
		<?php if ($column_left && $column_right) { ?>
		<?php $class = 'col-sm-6'; ?>
		<?php } elseif ($column_left || $column_right) { ?>
		<?php $class = 'col-sm-9'; ?>
		<?php } else { ?>
		<?php $class = 'col-sm-12'; ?>
		<?php } ?>
		<div id="content" class="bg-page-404 <?php echo $class; ?>">
			<?php echo $content_top; ?>
			<div class="text-center">
				<div style="margin: 30px 0 50px"><img src="image/catalog/cms/404/404-img-text.png" alt=""></div>
				<h1><?php echo $heading_title; ?></h1>
				<p><?php echo $text_error; ?></p>
				<a href="<?php echo $continue; ?>" class="btn btn-primary" title="<?php echo $button_continue; ?>"><?php echo $button_continue; ?></a>
			</div>
			
		<?php echo $column_right; ?>
		</div>
	</div>
</div>
<?php echo $footer; ?>
