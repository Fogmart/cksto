<?php echo $header; ?>
<?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-latest" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php foreach($error as $error_message) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_message; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <?php if (!empty($success)) { ?>
    <div class="alert alert-success"><i class="fa fa-exclamation-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
      </div>
      <div class="panel-body">  
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-module" class="form-horizontal">

					<ul class="nav nav-tabs">
						<li class="active"><a href="#tab-general" data-toggle="tab"><?= $tab_general; ?></a></li>
						<li><a href="#tab-store" data-toggle="tab"><?= $tab_store; ?></a></li>
						<li><a href="#tab-import" data-toggle="tab"><?= $tab_import; ?></a></li>
						<li><a href="#tab-about" data-toggle="tab"><?= $tab_about; ?></a></li>
					</ul>		

					<div class="tab-content">

						<div class="tab-pane active" id="tab-general">
				  		<div class="form-group">
				        <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
								<div class="col-sm-10">
									<select name="multistore_status" id="input-status" class="form-control">
										<?php if (isset($multistore_status) && $multistore_status) { ?>
										<option value="1" selected="selected"><?php echo $entry_yes; ?></option>
										<option value="0"><?php echo $entry_no; ?></option>
										<?php } else { ?>
										<option value="1"><?php echo $entry_yes; ?></option>
										<option value="0" selected="selected"><?php echo $entry_no; ?></option>
										<?php } ?>
									</select>
								</div>
				      </div>
				      <div class="form-group">
				        <label class="col-sm-2 control-label" for="input-template"><?php echo $entry_template; ?></label>
								<div class="col-sm-10">
									<select name="multistore_template" id="input-template" class="form-control">
										<?php if (isset($multistore_template) && $multistore_template == 'numeric') { ?>
										<option value="numeric" selected="selected"><?php echo $entry_template_numeric; ?></option>
										<option value="text"><?php echo $entry_template_text; ?></option>
										<?php } else { ?>
										<option value="numeric"><?php echo $entry_template_numeric; ?></option>
										<option value="text" selected="selected"><?php echo $entry_template_text; ?></option>
										<?php } ?>
									</select>
								</div>
				      </div>
				      <div class="form-group">
				        <label class="col-sm-2 control-label" for="input-modificator"><?php echo $entry_modificator; ?></label>
								<div class="col-sm-10">
									<select name="multistore_modificator" id="input-modificator" class="form-control">
										<?php if (isset($multistore_modificator) && $multistore_modificator) { ?>
										<option value="1" selected="selected"><?php echo $entry_yes; ?></option>
										<option value="0"><?php echo $entry_no; ?></option>
										<?php } else { ?>
										<option value="1"><?php echo $entry_yes; ?></option>
										<option value="0" selected="selected"><?php echo $entry_no; ?></option>
										<?php } ?>
									</select>
								</div>
				      </div>
				      <div class="form-group">
				        <label class="col-sm-2 control-label" for="input-modificator"><?php echo $entry_empty; ?></label>
								<div class="col-sm-10">
									<select name="multistore_empty" id="input-empty" class="form-control">
										<?php if (isset($multistore_empty) && $multistore_empty) { ?>
										<option value="1" selected="selected"><?php echo $entry_yes; ?></option>
										<option value="0"><?php echo $entry_no; ?></option>
										<?php } else { ?>
										<option value="1"><?php echo $entry_yes; ?></option>
										<option value="0" selected="selected"><?php echo $entry_no; ?></option>
										<?php } ?>
									</select>
								</div>
				      </div>
						</div>

						<div class="tab-pane" id="tab-store">
							<?php $multistore_id = 0 ?>
							<div id="multistores">
								<?php foreach($multistores as $multistore){ ?>
									<?php $multistore_id = $multistore['multistore_id']; ?>
									<div class="panel panel-default" id="multistore-<?= $multistore_id; ?>">
										<div class="panel-heading">
											<span><?= $multistore['name']; ?></span>
											<button class="btn btn-danger btn-xs pull-right" onclick="$('#multistore-<?= $multistore_id; ?>').remove();">
												<i class="glyphicon glyphicon-minus" aria-hidden="true"></i>
											</button>
											<input type="hidden" name="multistores[<?= $multistore_id; ?>][multistore_id]" value="<?= $multistore_id; ?>" class="form-control" >
										</div>
										<div class="panel-body" style="display: none;">

											<div class="form-group">
									      <label class="col-sm-2 control-label" for="input-multistore-name-<?= $multistore_id; ?>"><?= $entry_name; ?></label>
									      <div class="col-sm-4">
									        <input type="text" name="multistores[<?= $multistore_id; ?>][name]" value="<?= $multistore['name']; ?>" placeholder="<?= $entry_name; ?>" class="form-control" />
									      </div>
									      <label class="col-sm-1 control-label" for="input-multistore-type-<?= $multistore_id; ?>"><?= $entry_type; ?></label>
									      <div class="col-sm-2">
									        <select name="multistores[<?= $multistore_id; ?>][type]" class="form-control">
														<option value="store" <?php if ($multistore['type'] == 'store') { ?> selected="selected" <?php } ?>><?= $entry_store; ?></option>
														<option value="stock" <?php if ($multistore['type'] == 'stock') { ?> selected="selected" <?php } ?>><?= $entry_stock; ?></option>
														<option value="trade" <?php if ($multistore['type'] == 'trade') { ?> selected="selected" <?php } ?>><?= $entry_trade; ?></option>
													</select>
									      </div>
									      <label class="col-sm-2 control-label" for="input-multistore-sort-<?= $multistore_id; ?>"><?= $entry_sort; ?></label>
									      <div class="col-sm-1">
									        <input type="number" name="multistores[<?= $multistore_id; ?>][sort]" value="<?= $multistore['sort']; ?>" placeholder="0" class="form-control" />
									      </div>
									    </div>

									    <div class="form-group">
									      <label class="col-sm-2 control-label" for="input-multistore-description-<?= $multistore_id; ?>"><?= $entry_description; ?></label>
									      <div class="col-sm-10">
									        <textarea id="input-multistore-description-<?= $multistore_id; ?>" name="multistores[<?= $multistore_id; ?>][description]" placeholder="<?= $entry_description; ?>" class="form-control" ><?= $multistore['description']; ?></textarea>
									      </div>
									    </div>

									    <div class="form-group">
									      <label class="col-sm-2 control-label" for="input-multistore-zone-<?= $multistore_id; ?>"><?= $entry_geo_zone; ?></label>
									      <div class="col-sm-10">
									        <select name="multistores[<?= $multistore_id; ?>][geo_zone_id]" id="input-multistore-zone-<?= $multistore_id; ?>" class="form-control">
									          <option value="0"><?= $entry_geo_all; ?></option>
									          <?php foreach($geo_zones as $geo_zone) { ?>
									          <?php if ($geo_zone['geo_zone_id'] == $multistore['geo_zone_id']) { ?>
									          <option value="<?= $geo_zone['geo_zone_id']; ?>" selected="selected"><?= $geo_zone['name']; ?></option>
									          <?php } else { ?>
									          <option value="<?= $geo_zone['geo_zone_id']; ?>"><?= $geo_zone['name']; ?></option>
									          <?php } ?>
									          <?php } ?>
									        </select>
									      </div>
									    </div>

									    <div class="form-group">
												<label class="col-sm-2 control-label"><?= $entry_setting; ?></label>
									      <div class="col-sm-2">
									      	<label class="checkbox-inline">
									      		<?php if ( isset($multistore['infinity']) && $multistore['infinity'] )  { ?>
									        		<input type="checkbox" value="1" name="multistores[<?= $multistore_id; ?>][infinity]" checked="checked">
									        	<?php } else { ?>
															<input type="checkbox" value="1" name="multistores[<?= $multistore_id; ?>][infinity]">
									        	<?php } ?>
									        	<span><?= $entry_infinity; ?></span>
									        </label>
									      </div>
									      <div class="col-sm-2">
									      	<label class="radio-inline">
									      		<?php if (isset($multistore_default) && $multistore_default == $multistore_id) { ?>
									        		<input type="radio" value="<?= $multistore_id; ?>" name="multistore_default" checked="checked">
									        	<?php } else { ?>
															<input type="radio" value="<?= $multistore_id; ?>" name="multistore_default">
									        	<?php } ?>
									        	<span><span data-toggle="tooltip" title="" data-original-title="<?= $help_default; ?>"><?= $entry_default; ?></span></span>
									        </label>
									      </div>
									    </div>

										</div>
									</div>		
								<?php } ?>
							</div>
							<button class="btn btn-info" id="multistore-add"><?= $button_add; ?></button>
						</div>

						<div class="tab-pane" id="tab-import">

							<div class="alert alert-warning" role="alert"><?= $entry_experement; ?></div>
							<p id="help-template"><?= $entry_import; ?></p>

							<script>
								$('#help-template a').on('click', function(e){
									e.preventDefault();
									$.ajax({
										url: 'index.php?route=extension/module/multistore/getTemplate&token=<?= $token; ?>',
										type: 'get',
										dataType: 'json',
										success: function(json) {
											if (json['error']) {
												console.log(json['error'])
											} else {
												window.open(json['route'])
											}
										},
										error: function(xhr, ajaxOptions, thrownError) {
											console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
										}
									});
								});
							</script>

							<div class="form-group required">
				        <label class="col-sm-2 control-label" for="button-upload"><span data-toggle="tooltip" title="" data-original-title="<?= $help_xlsx; ?>"><?= $entry_download; ?></span></label>
				        <div class="col-sm-10">
				          <button type="button" id="button-upload" data-loading-text="<?= $entry_loading; ?>" class="btn btn-primary"><?= $entry_load; ?></button>
				        </div>
				      </div>

				      <script>
				      	$('#button-upload').on('click', function() {
									$('#form-upload').remove();

									$('body').prepend('<form enctype="multipart/form-data" id="form-upload" style="display: none;"><input type="file" name="file" /></form>');

									$('#form-upload input[name=\'file\']').trigger('click');

									if (typeof timer != 'undefined') {
								    	clearInterval(timer);
									}

									timer = setInterval(function() {
										if ($('#form-upload input[name=\'file\']').val() != '') {
											clearInterval(timer);

											$.ajax({
												url: 'index.php?route=tool/upload/upload&token=<?= $token; ?>',
												type: 'post',
												dataType: 'json',
												data: new FormData($('#form-upload')[0]),
												cache: false,
												contentType: false,
												processData: false,
												beforeSend: function() {
													$('#button-upload').button('loading');
													$('#import-result').html('');
												},
												complete: function() {
													$('#button-upload').button('reset');
												},
												success: function(json) {
													if (json['code']) {
														$.ajax({
															url: 'index.php?route=extension/module/multistore/import&token=<?= $token; ?>',
															type: 'post',
															dataType: 'json',
															data: {code: json['code']},
															beforeSend: function() {
																$('#button-upload').button('loading');
															},
															complete: function() {
																$('#button-upload').button('reset');
															},
															success: function(json) {
																if (json['success']) {
																	$('#import-result').append(`<div class="alert alert-success" role="alert"><?= $entry_import_success; ?> ${json['success']}</div>`);
																}

																if (json['failed']) {
																	$('#import-result').append(`<div class="alert alert-warning" role="alert"><?= $entry_import_failed; ?> ${json['failed']}</div>`);
																}

																if (json['error']) {
																	$('#import-result').append(`<div class="alert alert-danger" role="alert">${json['error']}</div>`);
																}
															},
															error: function(xhr, ajaxOptions, thrownError) {
																console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
															}
														});

													} else if (json['error']) {
														console.log(json['error']);
													}
												},
												error: function(xhr, ajaxOptions, thrownError) {
													console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
												}
											});
										}
									}, 500);
								});
				      </script>

				      <div id="import-result"></div>
						</div>

						<div class="tab-pane" id="tab-about">
							<p><?= $version; ?></p>
							<p><?= $about_module; ?></p>
							<div class="panel panel-default">
							  <div class="panel-heading"><?= $entry_support; ?></div>
							  <div class="panel-body">
							    <script src="https://yastatic.net/q/forms-frontend-ext/_/embed.js"></script><iframe src="https://forms.yandex.ru/u/5d2c8cbd6b6a500283b57573/?iframe=1" frameborder="0" name="ya-form-5d2c8cbd6b6a500283b57573" width="650"></iframe>
							  </div>
							</div>
						</div>

					</div>	
        </form>
     	</div>	
    </div>    
	<script>
		
		let multistore_id = <?= $multistore_id; ?> + 1;
		
		$('#multistore-add').on('click', function(e){

			e.preventDefault();

			$('#multistores').append(`<div class="panel panel-default" id="multistore-${multistore_id}">
			<div class="panel-heading">
			<span><?= $entry_name; ?> ${multistore_id}</span>
			<button class="btn btn-danger btn-xs pull-right" onclick="$('#multistore-${multistore_id}').remove();">
			<i class="glyphicon glyphicon-minus" aria-hidden="true"></i>
			</button>
			<input type="hidden" name="multistores[${multistore_id}][multistore_id]" value="${multistore_id}" class="form-control" >
			</div>
			<div class="panel-body" style="display: none;">
			<div class="form-group">
			<label class="col-sm-2 control-label" for="input-multistore-name-${multistore_id}"><?= $entry_name; ?></label>
			<div class="col-sm-4">
			<input type="text" name="multistores[${multistore_id}][name]" value="<?= $entry_name; ?> ${multistore_id}" placeholder="<?= $entry_name; ?>" class="form-control" />
			</div>
			<label class="col-sm-1 control-label" for="input-multistore-type-${multistore_id}"><?= $entry_type; ?></label>
			<div class="col-sm-2">
			<select name="multistores[${multistore_id}][type]" class="form-control">
			<option value="store"><?= $entry_store; ?></option>
			<option value="stock"><?= $entry_stock; ?></option>
			<option value="trade"><?= $entry_trade; ?></option>
			</select>
			</div>
			<label class="col-sm-2 control-label" for="input-multistore-sort-${multistore_id}"><?= $entry_sort; ?></label>
			<div class="col-sm-1">
			<input type="number" name="multistores[${multistore_id}][sort]" value="0" placeholder="0" class="form-control" />
			</div>
			</div>
			<div class="form-group">
			<label class="col-sm-2 control-label" for="input-multistore-description-${multistore_id}"><?= $entry_description; ?></label>
			<div class="col-sm-10">
			<textarea id="input-multistore-description-${multistore_id}" name="multistores[${multistore_id}][description]" placeholder="<?= $entry_description; ?>" class="form-control" /></textarea>
			</div>
			</div>
			<div class="form-group">
			<label class="col-sm-2 control-label" for="input-multistore-zone-${multistore_id}"><?= $entry_geo_zone; ?></label>
			<div class="col-sm-10">
			<select name="multistores[${multistore_id}][geo_zone_id]" id="input-multistore-zone-${multistore_id}" class="form-control">
			<option value="0"><?= $entry_geo_all; ?></option>
			<?php foreach($geo_zones as $geo_zone) { ?>
      <option value="<?= $geo_zone['geo_zone_id']; ?>"><?= $geo_zone['name']; ?></option>
      <?php } ?>
			</select>
			</div>
			</div>
			<div class="form-group">
			<label class="col-sm-2 control-label"><?= $entry_setting; ?></label>
			<div class="col-sm-2">
			<label class="checkbox-inline">
			<input type="checkbox" value="1" name="multistores[${multistore_id}][infinity]"><span><?= $entry_infinity; ?></span>
			</label>
			</div>
			<div class="col-sm-2">
			<label class="radio-inline">
			<input type="radio" value="${multistore_id}" name="multistore_default">
			<span><span data-toggle="tooltip" title="" data-original-title="<?= $help_default; ?>"><?= $entry_default; ?></span></span>
			</label>
			</div>
			</div>
			</div>
			</div>
			</div>`);

			multistore_id++;

		});

		$(document).delegate('[id^=multistore-] .panel-heading', 'click', function() {
			$(this).siblings('.panel-body').toggle();
		});

		$(document).delegate('[name*=name]', 'input', function() {
			$(this).parents('[id^=multistore-]').find('.panel-heading span').text( this.value );
		});
		
	</script>
	
  </div>
</div>
<?php echo $footer; ?>