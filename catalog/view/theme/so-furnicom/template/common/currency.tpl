<?php if (count($currencies) > 1) { ?>
<div class="form-group currencies-block">
<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-currency">
  
    <a class="btn btn-link dropdown-toggle" data-toggle="dropdown">
	    <?php foreach ($currencies as $currency) { ?>
	    <?php if ($currency['symbol_left'] && $currency['code'] == $code) { ?>
	    <?php } elseif ($currency['symbol_right'] && $currency['code'] == $code) { ?>
	    <?php } ?>
	    <?php } ?>

	    <span class=""> <?php foreach ($currencies as $currency) : if ($currency['code'] == $code) :  echo $currency['title']; endif;  endforeach;?></span>
	     <i class="fa fa-caret-down"></i>
    </a>
    <ul class="dropdown-menu">
      <?php foreach ($currencies as $currency) { ?>
      <?php if ($currency['symbol_left']) { ?>
      <li><button class="currency-select btn-block" type="button" name="<?php echo $currency['code']; ?>"><?php echo $currency['symbol_left']; ?> <?php echo $currency['title']; ?></button></li>
      <?php } else { ?>
      <li><button class="currency-select btn-block" type="button" name="<?php echo $currency['code']; ?>"><?php echo $currency['symbol_right']; ?> <?php echo $currency['title']; ?></button></li>
      <?php } ?>
      <?php } ?>
    </ul>
  
  <input type="hidden" name="code" value="" />
  <input type="hidden" name="redirect" value="<?php echo $redirect; ?>" />
</form>
</div>
<?php } ?>
