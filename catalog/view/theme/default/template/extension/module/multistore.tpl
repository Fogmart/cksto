<?php if (!empty($multistores)) { ?>
<h3><?= $text_multistore; ?></h3>
<ul class="list-unstyled">
	<?php foreach($multistores as $multistore){ ?>
	<?php if ($multistore['type'] == 'store') { ?>
		<?php if ($multistore['description']) { ?>
			<li data-toggle="tooltip" data-placement="left" title="<?= $multistore['description']; ?>"><?= $multistore['name']; ?> - <?= $multistore['quantity']; ?></li>
		<?php } else { ?>
			<li><?= $multistore['name']; ?> - <?= $multistore['quantity']; ?></li>
		<?php } ?>
	<?php } ?>
<?php } ?></ul>
<?php } ?>