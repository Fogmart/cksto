<?php echo $header; ?>
<?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_exit; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
	  </div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <!-- id 'filemanager' is original OpenCart identifier -->
  <div class="container-fluid" id="filemanager">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title smkfm-title"><?php echo $heading_title; ?></h3>
      </div>
      <div class="panel-body smkfm-body">
	    <div class="container-fluid smkfm-wrapper">
		  <div id="files-menu">
			<a id="smkfm-button-create" class="filemanager-button"><i></i><?=$button_folder?></a>
			<a id="smkfm-button-delete" class="filemanager-button"><i></i><?=$button_delete?></a>
			<a id="smkfm-button-move" class="filemanager-button"><i></i><?=$button_move?></a>
			<a id="smkfm-button-copy" class="filemanager-button"><i></i><?=$button_copy?></a>
			<a id="smkfm-button-rename" class="filemanager-button"><i></i><?=$button_rename?></a>
			<a id="smkfm-button-upload" class="filemanager-button"><i></i><?=$button_upload?></a>
			<a id="smkfm-button-uploadmulti" class="filemanager-button"><i></i><?=$button_uploads?>+</a>
			<a id="smkfm-button-refresh" class="filemanager-button"><i></i><?=$button_refresh?></a>
		  </div>
		  <div id="smkfm-column-right"></div>
		  <div id="smkfm-column-left"></div>
		  <div class="filter">
			<input type="text" name="filter" id="smkfm-filter" placeholder="<?=$smk_start_typing?>" autocomplete="off" spellcheck="false" autocorrect="off" /><img src="view/image/filemanager/filter.png" alt="" />
		  </div>
		  <div id="smkfm-toolset">
			<button id="btnExpand" class="btn"><?php echo $button_expand; ?></button>
			<button id="btnCollapse" class="btn"><?php echo $button_collapse; ?></button>
			<button id="btnTextView" class="btn"><?php echo $button_view_text; ?></button>
			<button id="btnListView" class="btn"><?php echo $button_view_list; ?></button>
			<button id="btnThumbView" class="btn"><?php echo $button_view_thumb; ?></button>
			<span class="smkfm-copyright">
				<a href="http://samarkand.000space.com/?from=filemanager" target="_blank">OpenCart 2.x version by The Samarkand</a>
				<br/>
				<a href="//villagedefrance.net/" title="villagedefrance" target="_blank">Overclocked Edition</a>
			</span>
			<div class="clearfix"></div>
		  </div>
		  <div id="smkfm-preview">
			<div class="holder"></div>
			<div class="clearfix"></div>
		  </div>
		</div>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">// <!--

// last folder is managed by tree cookie plugin
var lastFolder = $.cookie('smkfm_selected');
var selected = [ lastFolder ? lastFolder : 'smkfm_top' ];
var opened = ['smkfm_top'];

// because tree cookie plugin fuck ups order
$.cookie('smkfm_selected', '');

smkfm_file_manager({
	route: 'common/filemanager_full',
	token: '<?=$token?>',
	root: '<?=$smkfm_root?>',
	admin: '<?=$smkfm_admin?>',
	selected: selected,
	opened: opened,
	catalog: '<?=$smkfm_catalog?>',
	text_no_file_found: '<?=$text_no_file_found?>',
	text_no_selection: '<?=$text_no_selection?>',
	text_folder_action: '<?=$text_folder_action?>',
	text_folder_content: '<?=$text_folder_content?>',
	text_confirm: '<?=$text_confirm?>',
	text_folder_delete: '<?=$text_folder_delete?>',
	text_yes_delete: '<?=$text_yes_delete?>',
	text_no_cancel: '<?=$text_no_cancel?>',
	text_file_action: '<?=$text_file_action?>',
	text_file_delete: '<?=$text_file_delete?>',
	text_upload_plus: '<?=$text_upload_plus?>',
	text_allowed: '<?=$text_allowed?>',
	no_image: '<?=$no_image?>',
	button_submit: '<?=$button_submit?>',
	button_folder: '<?=$button_folder?>',
	button_copy: '<?=$button_copy?>',
	button_move: '<?=$button_move?>',
	button_rename: '<?=$button_rename?>',
	entry_folder: '<?=$entry_folder?>',
	entry_move: '<?=$entry_move?>',
	error_directory: '<?=$error_directory?>',
	entry_copy: '<?=$entry_copy?>',
	entry_rename: '<?=$entry_rename?>',
	plupload_max_file_size: '<?=$smkfm_plupload_max_file_size?>b',
	plupload_extensions: '<?=$smkfm_plupload_extensions?>',
	file: ''
});

$('#filemanager .filter input[name=filter]').focus();

//--></script>
<?php echo $footer; ?>