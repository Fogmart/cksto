<?php if (count($languages) > 1) { ?>
<div class="form-group languages-block">
<div id="langmark">

    <a class="btn btn-xs dropdown-toggle" data-toggle="dropdown">
	    <?php foreach ($languages as $language) { ?>
	    <?php if ($language['code'] == $code) { ?>
	    <img src="catalog/language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" alt="<?php echo $language['name']; ?>" title="<?php echo $language['name']; ?>">
	    <span class="hidden-xs"><?php echo $language['code']; ?></span>	
	    <?php } ?>
	    <?php } ?>
	    <span class="fa fa-caret-down"></span>
    </a>
    <ul class="dropdown-menu">
      <?php foreach ($languages as $language) { ?>
      <li><button class="btn-block language-select" type="button" name="<?php echo $language['code']; ?>"><img src="catalog/language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" alt="<?php echo $language['name']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></button></li>
      <?php } ?>
    </ul>

  <input type="hidden" name="code" value="" />
  <input type="hidden" name="redirect" value="<?php echo $redirect; ?>" />
</div>
</div>
<?php } ?>
