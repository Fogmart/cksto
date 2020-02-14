<!-- id 'filemanager' is original OpenCart identifier -->
<div id="filemanager" class="modal-dialog modal-lg">
  <div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close">&times;</button>
      <h4 class="modal-title smkfm-title"><?php echo empty($heading_title) ? '&nbsp;' : $heading_title; ?></h4>
    </div>
    <div class="modal-body smkfm-body">
	  <!-- filemanager -->
	  <div class="row smkfm-wrapper">
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
			<!-- dummy element, that receives fake 'click' event for compatibility with Summernote text editor -->
			<a class="thumbnail btn" href="#" style="display: none;" title="Insert this image in SummerNote text editor">Summernote</a>
			<!-- end -->
			
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
	  <!-- /filemanager -->
    </div>
  </div>
</div>
<script type="text/javascript"><!--

// gallery item
var smkfm_cra_sel = '#smkfm-column-right a';

// exit by escape, in old Firefox works not in all cases!
$.fn.smkfm_escape = function (callback) {
    return this.each(function () {
		if (typeof(window.smkfm_exit_by_escape_hook_installed) == 'undefined') {
			window.smkfm_exit_by_escape_hook_installed = true;
			$(document).on('keydown', this, function (e) {
				var keycode = ((typeof e.keyCode != 'undefined' && e.keyCode) ? e.keyCode : e.which);
				if (keycode === 27) {
					callback.call(this, e);
				}
			});
		}
    });
};

// handle exit by escape
$('#filemanager').smkfm_escape(function () {
    $('#filemanager .modal-header .close').trigger('click');
});

// handle click on close button
$('#filemanager .modal-header .close').click(function() {
	smkfm_exit();
});

// load this in head, to prevend loading again if change multiple images on same page
var smkfm_scripts_and_styles = [
	'view/stylesheet/filemanager_full.css',
	'view/javascript/jquery/ui/jquery-ui.min.css',
	'view/javascript/plupload/js/jquery.ui.plupload/css/jquery.ui.plupload.css',

	'view/javascript/jquery/ui/jquery-ui.min.js',
	'view/javascript/jquery/jstree-smkfm/jquery.tree.js',
	'view/javascript/jquery/jstree-smkfm/lib/jquery.cookie.js',
	'view/javascript/jquery/jstree-smkfm/plugins/jquery.tree.cookie.js',
	'view/javascript/plupload/js/plupload.full.min.js',
	'view/javascript/plupload/js/jquery.ui.plupload/jquery.ui.plupload.js',
	'view/javascript/jquery/ajaxupload.js',
	// must be last
	'view/javascript/smkfm.js',
];

// current scripts and styles list
var smkfm_scripts_and_styles_present = [];

// fill smkfm_scripts_and_styles_present
$('head > *').each(function() {
	var e = $(this), tag = e.prop('tagName').toLowerCase();
	if (tag == 'link' || tag == 'script') {
		var src = e.attr('src');
		var href = e.attr('href');
		if (tag == 'script' && src)
			smkfm_scripts_and_styles_present.push(src);
		if (tag == 'link' && href)
			smkfm_scripts_and_styles_present.push(href);
	}
});

// load all from smkfm_scripts_and_styles
for (var i = 0; i < smkfm_scripts_and_styles.length; i++) {
	var j, src = smkfm_scripts_and_styles[i];
	
	for (j = 0; j < smkfm_scripts_and_styles_present.length; j++) {
		if (smkfm_scripts_and_styles_present[j] == src) {
			// alredy
			break;
		}
	}
	
	if (j >= smkfm_scripts_and_styles_present.length) {
		// extension
		switch (src.substr(src.lastIndexOf('.') + 1).toLowerCase()) {
			case 'css':
				$('head').append('<link href="' + src + '" type="text/css" rel="stylesheet" />');
				break;
			case 'js':
				$('head').append('<script src="' + src + '" type="text/javascript"></script>');
				break;
			default:
				break;
		}
		smkfm_scripts_and_styles_present.push(src);
	}
}

// wait for scripts and styles loading, events is not reliable
var smkfm_timer = setInterval(function () {
	if (typeof(smkfm_file_manager) != 'undefined') {
		smkfm_init();
	}
}, 500);

function smkfm_onopen_callback(NODE, TREE_OBJ) {
	// unused currently
	if (!smkfm_onopen_callback.prototype.start_path_opened) {
		smkfm_onopen_callback.prototype.start_path_opened = true;
	}
}

// selected file will be set as value of this element
function smkfm_get_target_element() {
	var id = '<?=$target?>';
	if (id) {
		var e = $('#' + id);
		if (e.length)
			return e;
	}
	return null;
}

// get path relative to Catalog
function smkfm_get_path(file) {
	return '<?=$smkfm_catalog?>/' + file;
}

// both product image list and editors (CKeditor and SummerNote)
function smkfm_set_target_file(file) {
	var path = smkfm_get_path(file);
	var e = smkfm_get_target_element();

	if (e) {
		e.val(path);
		return;
	}

	// CKeditor
	/*if ('<?=$fckeditor?>') {
		window.opener.CKEDITOR.tools.callFunction('<?=$fckeditor?>', path);
	}*/
	<?php if (!empty($ckedialog)) { ?>
		if (typeof(CKEDITOR) != "undefined") {
			var dialog = CKEDITOR.dialog.getCurrent();
			var targetElement = '<?php echo $ckedialog; ?>' || null;
			if (targetElement) {
				var target = targetElement.split( ':' );
				dialog.setValueOf(target[0], target[1], '<?=$smkfm_image?>' + path);
			}
		}
	<?php } ?>
	
	// TODO: This not works (why?), works only human click.
	// Tested only on Mozilla Firefox
	//e.trigger('click');
	//e.click();
}

// SummerNote only
function smkfm_set_target_file_summernote(e, file) {
	e.attr('href', '<?=$smkfm_root?>' + file);
}

// thumb in product image list
function smkfm_set_target_thumb(src) {
	if ('<?=$thumb?>') {
		var dest_e = $('#<?=$thumb?>');
		if (dest_e.length) {
			dest_e.find('img').attr('src', src);
		}
	}
}

// current image in product images list
function smkfm_get_target_value() {
	var e = smkfm_get_target_element();
	if (e)
		return e.val();
	return '';
}

// preview file
function smkfm_on_preview() {
	var file = $(this).attr('file');
	// dummy element for summernote
	var summernote = $('#smkfm-toolset a.thumbnail');
	if (summernote) {
		smkfm_set_target_file_summernote(summernote, file);
		summernote.fadeIn();
	}
}

// file is choosen
function smkfm_on_choose() {
	var file = $(this).attr('file');
	var thumb = $(this).find('img').attr('src');
	smkfm_set_target_file(file);
	smkfm_set_target_thumb(thumb);
	smkfm_exit();
}

// close file manager
function smkfm_exit() {
	// preview
	$(document).off('click', smkfm_cra_sel, smkfm_on_preview);
	// image is choosen		
	$(document).off('dblclick', smkfm_cra_sel, smkfm_on_choose);

	var d = $('#smkfm-dialog');
	if (d.size()) {
		d.dialog('close');
		d.remove();
	}
	
	$('#modal-image').modal('hide');
}

// open file manager
function smkfm_init() {
	// wait for scripts and styles loading, events is not reliable
	if (typeof(smkfm_timer) != "undefined" && smkfm_timer != null) {
		clearInterval(smkfm_timer);
		smkfm_timer = null;
	}
	
	// last folder is managed by tree cookie plugin
	var lastFolder = $.cookie('smkfm_selected');
	var selected = [ lastFolder ? lastFolder : 'smkfm_top' ];
	var opened = ['smkfm_top'];
	
	// current file
	var cat = '<?=$smkfm_catalog?>/';
	var path = smkfm_get_target_value();
	// console.log('smkfm_get_target_value: ' + path);
	var slash;
	var currentFileName = '';
	if (path && (slash = path.lastIndexOf('/')) != -1) {
		currentFileName = path.substr(slash + 1);
		if (path = path.substr(0, slash)) {
			if (path.indexOf(cat) == 0) {
				if (path = path.substr(cat.length)) {
					// relative to catalog path
					var chunks = path.split('/');
					for (var i = 0, cur = ''; i < chunks.length; i++) {
						// folder by folder
						cur += (cur ? '/' : '') + chunks[i];
						var id = smkfm_path_to_id(cur);
						opened.push(id);
					}
					selected = [smkfm_path_to_id(path)];
				}
			}
		}
	}
	
	// console.log(selected);
	
	// because tree cookie plugin fuck ups order
	$.cookie('smkfm_selected', '');

	smkfm_file_manager({
		route: 'common/filemanager',
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
		onopen_cb: smkfm_onopen_callback,
		plupload_max_file_size: '<?=$smkfm_plupload_max_file_size?>b',
		plupload_extensions: '<?=$smkfm_plupload_extensions?>',
		file: currentFileName
	});

	// this will fuck up text range in SummerNote!
	// $('#filemanager .filter input[name=filter]').focus();
	
	// dummy element for summernote
	var summernote = $('#smkfm-toolset a.thumbnail');
	
	// preview
	$(document).on('click', smkfm_cra_sel, smkfm_on_preview);

	// image is choosen		
	$(document).on('dblclick', smkfm_cra_sel, smkfm_on_choose);

	// User clicks on $(summernote) =>
	//	=>
	//	1) Summernote inserts an image (selected image is stored in 'href').
	//		Summernote handles this event by itself.
	//	2) Samarkand File Manager exits
	
	summernote.click(function(e) {
		e.preventDefault();
		$(smkfm_cra_sel + '.smkfm-selected').trigger('dblclick');
	});
}
//--></script>
