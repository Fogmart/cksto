// Dirty hack to work with Enhanced Admin:
if ($('#filemanager .smkfm-body').length == 0 && $('#filemanager > .modal-content > .modal-body').length) {
	// It won't use our filemanager.tpl template
	$('#filemanager > .modal-content > .modal-body').addClass('smkfm-body');
}

// Russian chars are allowed, too
function smkfm_path_to_id(path) {
	var id = 'smkfm_';
	for (var i = 0; i < path.length; i++) {
		id += (i ? '-' : '') + path.charCodeAt(i);
	}
	return id;
}

function smkfm_file_manager(smkfmp) {
	$(document).ready(function() {
		function smkfm_file_ext(file) {
			var dot = file.lastIndexOf('.');
			return dot != -1 ? file.substr(dot + 1) : '';
		}
		
		function smkfm_rel_path(fileOrFolder) {
			return smkfmp.root + fileOrFolder;
		}
		
		function smkfm_set_title(fileOrFolder) {
			var path = smkfm_rel_path(fileOrFolder);
			$('.smkfm-title').html('<a href="' + path + '" target="_blank">' + path + '</a>');
		}
		
		// menu icons
		$('#smkfm-toolset button:first').button({
			icons: { primary:'ui-icon-plus' }
		}).next().button({
			icons: { primary:'ui-icon-minus' }
		}).next().button({
			icons: { primary:'ui-icon-pencil' }
		}).next().button({
			icons: { primary:'ui-icon-grip-dotted-horizontal' }
		}).next().button({
			icons: { primary:'ui-icon-image' }
		});

		$('#smkfm-column-left').tree({
			plugins : {
				cookie : {
					prefix : "smkfm_",
					keep_selected : true,
					keep_opened : true
				}
			},
			data: {
				async: true,
				type: 'json',
				opts: {
					method: 'post',
					url: 'index.php?route=' + smkfmp.route + '/directory&token=' + smkfmp.token
				}
			},
			selected: smkfmp.selected ? smkfmp.selected : ['smkfm_top'],
			opened: smkfmp.opened ? smkfmp.opened : ['smkfm_top'],
			ui: {
				theme_name: 'apple',
				animation: 50
			},
			types: {
				'default': {
					clickable: true,
					creatable: false,
					renameable: false,
					deletable: false,
					draggable: false,
					max_children: -1,
					max_depth: -1,
					valid_children: 'all'
				}
			},
			callback: {
				beforedata: function(NODE, TREE_OBJ) {
					if (NODE == false) {
						TREE_OBJ.settings.data.opts.static = [ {
							data: smkfmp.catalog,
							attributes: {
								'id': 'smkfm_top',
								'directory': ''
							},
							state: 'closed'
						} ];

						return { 'directory': '' }

						$('#smkfm-column-left a.clicked').prepend('(' + json.length + ')');
					} else {
						TREE_OBJ.settings.data.opts.static = false;

						return { 'directory': $(NODE).attr('directory') }
					}
				},
				onselect: function (NODE, TREE_OBJ) {
					var dr;
					var tree = $.tree.reference('#smkfm-column-left a');
					window.dr = $(tree.selected).attr('directory');

					smkfm_set_title(window.dr);
					
					$.ajax({
						url: 'index.php?route=' + smkfmp.route + '/files&token=' + smkfmp.token,
						type: 'post',
						data: 'directory=' + encodeURIComponent(window.dr),
						dataType: 'json',
						success: function(json) {
							var html = '<div>';

							if (json) {
								if (json.length == 0) {
									html += '<div class="smkfm-feedback">' + smkfmp.text_no_file_found + '</div>';
								} else {
									for (i = 0; i < json.length; i++) {
										var item = json[i];
										var file = item['file'];
										var fn = item['filename'];
										var c = 'smkfm-file';
										
										if (file == window.dr + '/' + smkfmp.file) {
											c += ' smkfm-selected';
										}/* else {
											console.log('- ' + file + ' -- ' + window.dr + '/' + smkfmp.file);
										}*/
										
										html += '<a file="' + file + '" class="' + c + '" title="' + fn + '"><img src="' + item['thumb'] + '" title="' + fn + '" alt="" /><div><span class="smkfm-file-name">' + fn + '</span><span class="smkfm-file-size">' + item['size'] + '</span><input type="hidden" name="image" value="' + file + '" /></div></a>';
									}
								}
							}

							html += '</div>';

							$('#smkfm-column-right').html(html);
						},
						error: function(xhr, ajaxOptions, thrownError) {
							alert(thrownError + "\n" + xhr.statusText + "\n" + xhr.responseText);
						}
					});
				},
				onopen: function(NODE, TREE_OBJ) {
					var tr = $('#smkfm-column-left li#smkfm_top li[directory]');

					tr.each(function(index, e) {
						var dd = smkfm_path_to_id($(e).attr('directory'));
						$(e).attr('id', dd);
					});

					if (smkfmp.onopen_cb) {
						smkfmp.onopen_cb(NODE, TREE_OBJ);
					}
				}
			}
		});

		$('#btnExpand').click(function() {
			var allTree = $.tree.focused();
			allTree.open_all('#smkfm_top');
			allTree.refresh(allTree);
		});

		$('#btnCollapse').click(function() {
			var allTree= $.tree.focused();
			allTree.close_all('#smkfm_top');
			allTree.refresh(allTree);
		});

		/*	switch to text view	*/
		$('#btnTextView').click(function() {
			$('#smkfm-column-right').removeClass('smk-list-view').addClass('smk-text-view');
			$('#smkfm-column-right a').each(function(index, e2) {
				var e = $(this).find('span.smkfm-file-name');
				var sfn = e.data('short-filename');
				if (!sfn) {
					e.data('short-filename', e.html());
				}
				e.html(e.parent().attr('title'));
			});
		});

		$('#btnListView').click(function() {
			$('#smkfm-column-right').removeClass('smk-text-view').addClass('smk-list-view');
			$('#smkfm-column-right a').each(function(index, e2) {
				var e = $(this).find('span.smkfm-file-name');
				var sfn = e.data('short-filename');
				if (sfn)
					e.html(sfn).data('short-filename', '');
			});
		});

		$('#btnThumbView').click(function() {
			$('#smkfm-column-right').removeClass('smk-list-view').removeClass('smk-text-view');
			$('#smkfm-column-right a').each(function(index, e2) {
				var e = $(this).find('span.smkfm-file-name');
				var sfn = e.data('short-filename');
				if (sfn)
					e.html(sfn).data('short-filename', '');
			});
		});

		if (typeof(window.smkfm_file_manager_flag_select_listener_installed) == 'undefined') {
			window.smkfm_file_manager_flag_select_listener_installed = true;
			
			$(document).on('click', '#smkfm-column-right a', function() {
				if ($(this).hasClass('smkfm-selected')) {
					$(this).removeClass('smkfm-selected');
				} else {
					var file = $(this).attr('file');
					$('#smkfm-column-right a').removeClass('smkfm-selected');
					$(this).addClass('smkfm-selected');
					// console.log($(this).attr('class'));
					
					smkfm_set_title(file);
					
					// Documents preview
					var html = '';
					var ext = smkfm_file_ext(file);
					var rp = smkfm_rel_path(file);
					switch (ext.toLowerCase()) {
						case 'pdf':
						case 'doc':
						case 'docx':
						case 'xls':
						case 'xlsx':
							html = '<iframe src="' + smkfmp.admin + 'view/javascript/ViewerJS/#' + rp + '" width="100%" height="724" allowfullscreen webkitallowfullscreen></iframe>';
							break;
						case 'jpg':
						case 'jpeg':
						case 'png':
						case 'gif':
						case 'bmp':
						case 'ico':
							html = '<img src="' + rp + '" width="100%" />';
							break;
						default:
							break;
					}
					$('#smkfm-preview .holder').html(html);
				}
			});
		}

		$('#smkfm-filter').keyup(function() {
			var filter = $(this).val().toLowerCase();

			$('#smkfm-column-right a').each(function() {
				var e = $(this);
				var fileName = e.text();
				var dotPos = fileName.lastIndexOf('.');
				var name = fileName;
				
				if (dotPos != -1)
					name = fileName.substr(0, dotPos);
				
				name = name.toLowerCase();

				if (name.indexOf(filter) != -1) {
					e.show();
				} else {
					e.hide();
				}
			});

			// lazy load
			/*$('#smkfm-column-right a').each(function(index, element) {
				var height = $('#smkfm-column-right').height();
				var offset = $(element).offset();

				if (
					(offset.top > 0) && (offset.top < height) &&
					$(element).find('img').attr('src') == smkfmp.no_image) {
					$.ajax({
						url: 'index.php?route=common/filemanager/image&token=' + smkfmp.token + '&image=' + encodeURIComponent(smkfmp.catalog + '/' + $(element).find('input[name=\'image\']').attr('value')),
						dataType: 'html',
						success: function(html) {
							$(element).find('img').replaceWith('<img src="' + html + '" alt="" title="" />');
						}
					});
				}
			});*/
		});

		$('#smkfm-button-create').bind('click', function() {
			var tree = $.tree.focused();

			if (tree.selected) {
				$('#smkfm-dialog').remove();

				var html  = '<div id="smkfm-dialog">';
				html += '  ' + smkfmp.entry_folder + ' <input type="text" name="name" value="" /><br /><br />';
				html += '  <input type="button" value="' + smkfmp.button_submit + '" />';
				html += '</div>';

				$('#smkfm-column-right').prepend(html);

				$('#smkfm-dialog').dialog({
					title: smkfmp.button_folder,
					resizable: false,
					appendTo: '#filemanager .smkfm-body',
				});

				$('#smkfm-dialog input[type=\'button\']').bind('click', function() {
					$.ajax({
						url: 'index.php?route=' + smkfmp.route + '/create&token='+ smkfmp.token,
						type: 'post',
						data: 'directory=' + encodeURIComponent($(tree.selected).attr('directory')) + '&name=' + encodeURIComponent($('#smkfm-dialog input[name=\'name\']').val()),
						dataType: 'json',
						success: function(json) {
							if (json.success) {
								$('#smkfm-dialog').remove();

								tree.refresh(tree.selected);
							} else {
								alert(json.error);
							}
						},
						error: function(xhr, ajaxOptions, thrownError) {
							alert(thrownError + "\n" + xhr.statusText + "\n" + xhr.responseText);
						}
					});
				});

			} else {
				alert(smkfmp.error_directory);
			}
		});

		$('#smkfm-button-delete').bind('click', function() {
			var path = $('#smkfm-column-right a.smkfm-selected').attr('file');
			var fldr = $('#smkfm-column-left a.clicked').html();

			fldr = fldr.replace("<ins>&nbsp;</ins>", "");

			if (path == undefined) {
				$('#smkfm-dialog').remove();

				var html  = '<div id="smkfm-dialog">';
				html += '  <p>' + smkfmp.text_folder_action + '<span style="font-weight:bold;"> "' + fldr + '"' +'</span><br />';
				html += '  ' + smkfmp.text_folder_content + '<br /><br />';
				html += '  <span style="font-weight:bold; color:Crimson;">' + smkfmp.text_confirm + '</span></p>';
				html += '</div>';

				$('#smkfm-column-right').prepend(html);

				var deleteButtons = {};
				
				deleteButtons[smkfmp.text_yes_delete] = function() {
					var tree = $.tree.focused();

					if (tree.selected) {
						$.ajax({
							url: 'index.php?route=' + smkfmp.route + '/delete&token=' + smkfmp.token,
							type: 'post',
							data: 'path=' + encodeURIComponent($(tree.selected).attr('directory')),
							dataType: 'json',
							success: function(json) {
								if (json.success) {
									var tt = tree.prev(tree.selected);

									tree.refresh(tree.parent(tree.selected));

									tree.select_branch(tt);
								}

								if (json.error) {
									alert(json.error);
								}
							},
							error: function(xhr, ajaxOptions, thrownError) {
								alert(thrownError + "\n" + xhr.statusText + "\n" + xhr.responseText);
							}
						});
					}

					$(this).dialog('close');
				};

				deleteButtons[smkfmp.text_no_cancel] = function() {
					$(this).dialog('close');
				};
				
				$('#smkfm-dialog').dialog({
					resizable: true,
					height: 220,
					width: 400,
					modal: true,
					title: smkfmp.text_folder_delete,
					buttons: deleteButtons,
					appendTo: '#filemanager .smkfm-body',
				});

			} else if (path) {
				var file = path.substring(path.lastIndexOf('/') + 1).toLowerCase();

				$('#smkfm-dialog').remove();

				var html = '<div id="smkfm-dialog">';
				html += '  <p>' + smkfmp.text_file_action + ' ' + '<span style="font-weight:bold;"> "' + file + '"' +'</span><br /><br />';
				html += '  <span style="font-weight:bold; color:Crimson;">' + smkfmp.text_confirm + '</span></p>';
				html += '</div>';

				$('#smkfm-column-right').prepend(html);

				var buttons_object2 = {};

				buttons_object2[smkfmp.text_yes_delete] = function() {
					var tree = $.tree.focused();

					$.ajax({
						url: 'index.php?route=' + smkfmp.route + '/delete&token='+smkfmp.token,
						type: 'post',
						data: 'path=' + path,
						dataType: 'json',
						success: function(json) {
							if (json.success) {
								var tree = $.tree.focused();

								tree.select_branch(tree.selected);
							}

							if (json.error) {
								alert(json.error);
							}
						},
						error: function(xhr, ajaxOptions, thrownError) {
							alert(thrownError + "\n" + xhr.statusText + "\n" + xhr.responseText);
						}
					});

					$(this).dialog('close');
				};
				
				buttons_object2[smkfmp.text_no_cancel] = function() {
					$(this).dialog('close');
				};
				
				$('#smkfm-dialog').dialog({
					resizable: false,
					height: 220,
					width: 400,
					modal: true,
					title: smkfmp.text_file_delete,
					buttons: buttons_object2,
					appendTo: '#filemanager .smkfm-body',
				});
			}
		});

		$('#smkfm-button-move').bind('click', function() {
			$('#smkfm-dialog').remove();

			var html = '<div id="smkfm-dialog" class="smkfm-wide-dialog">';
			html += '  ' + smkfmp.entry_move + ' <select name="to"></select><br /><br />';
			html += '  <input type="button" value="' + smkfmp.button_submit + '" />';
			html += '</div>';

			$('#smkfm-column-right').prepend(html);

			$('#smkfm-dialog').dialog({
				title: smkfmp.button_move,
				resizable: true,
				width: 'auto',
				appendTo: '#filemanager .smkfm-body',
			});

			$('#smkfm-dialog select[name=\'to\']').load('index.php?route=' + smkfmp.route + '/folders&token='+ smkfmp.token);

			$('#smkfm-dialog input[type=\'button\']').bind('click', function() {
				var path = $('#smkfm-column-right a.smkfm-selected').find('input[name=\'image\']').attr('value');

				if (path) {
					$.ajax({
						url: 'index.php?route=' + smkfmp.route + '/move&token=' + smkfmp.token,
						type: 'post',
						data: 'from=' + encodeURIComponent(path) + '&to=' + encodeURIComponent($('#smkfm-dialog select[name=\'to\']').val()),
						dataType: 'json',
						success: function(json) {
							if (json.success) {
								$('#smkfm-dialog').remove();

								var tree = $.tree.focused();

								tree.select_branch(tree.selected);
							}

							if (json.error) {
								alert(json.error);
							}
						},
						error: function(xhr, ajaxOptions, thrownError) {
							alert(thrownError + "\n" + xhr.statusText + "\n" + xhr.responseText);
						}
					});

				} else {
					var tree = $.tree.focused();

					$.ajax({
						url: 'index.php?route=' + smkfmp.route + '/move&token=' + smkfmp.token,
						type: 'post',
						data: 'from=' + encodeURIComponent($(tree.selected).attr('directory')) + '&to=' + encodeURIComponent($('#smkfm-dialog select[name=\'to\']').val()),
						dataType: 'json',
						success: function(json) {
							if (json.success) {
								$('#smkfm-dialog').remove();

								tree.select_branch('#smkfm_top');

								tree.refresh(tree.selected);
							}

							if (json.error) {
								alert(json.error);
							}
						},
						error: function(xhr, ajaxOptions, thrownError) {
							alert(thrownError + "\n" + xhr.statusText + "\n" + xhr.responseText);
						}
					});
				}
			});
		});

		$('#smkfm-button-copy').bind('click', function() {
			$('#smkfm-dialog').remove();

			var html = '<div id="smkfm-dialog">';
			html += '  ' + smkfmp.entry_copy + ' <input type="text" name="name" value="" /><br /><br />'; 
			html += '  <input type="button" value="' + smkfmp.button_submit + '" />';
			html += '</div>';

			$('#smkfm-column-right').prepend(html);

			$('#smkfm-dialog').dialog({
				title: smkfmp.button_copy,
				resizable: false,
				width: 'auto',
				appendTo: '#filemanager .smkfm-body',
			});

			$('#smkfm-dialog select[name=\'to\']').load('index.php?route=' + smkfmp.route + '/folders&token=' + smkfmp.token);

			$('#smkfm-dialog input[type=\'button\']').bind('click', function() {
				var path = $('#smkfm-column-right a.smkfm-selected').find('input[name=\'image\']').attr('value');

				if (path) {
					$.ajax({
						url: 'index.php?route=' + smkfmp.route + '/copy&token=' + smkfmp.token,
						type: 'post',
						data: 'path=' + encodeURIComponent(path) + '&name=' + encodeURIComponent($('#smkfm-dialog input[name=\'name\']').val()),
						dataType: 'json',
						success: function(json) {
							if (json.success) {
								$('#smkfm-dialog').remove();

								var tree = $.tree.focused();

								tree.select_branch(tree.selected);
							}

							if (json.error) {
								alert(json.error);
							}
						},
						error: function(xhr, ajaxOptions, thrownError) {
							alert(thrownError + "\n" + xhr.statusText + "\n" + xhr.responseText);
						}
					});

				} else {
					var tree = $.tree.focused();

					$.ajax({
						url: 'index.php?route=' + smkfmp.route + '/copy&token=' + smkfmp.token,
						type: 'post',
						data: 'path=' + encodeURIComponent($(tree.selected).attr('directory')) + '&name=' + encodeURIComponent($('#smkfm-dialog input[name=\'name\']').val()),
						dataType: 'json',
						success: function(json) {
							if (json.success) {
								$('#smkfm-dialog').remove();

								tree.select_branch(tree.parent(tree.selected));

								tree.refresh(tree.selected);
							}

							if (json.error) {
								alert(json.error);
							}
						},
						error: function(xhr, ajaxOptions, thrownError) {
							alert(thrownError + "\n" + xhr.statusText + "\n" + xhr.responseText);
						}
					});
				}
			});
		});

		$('#smkfm-button-rename').bind('click', function() {
			$('#smkfm-dialog').remove();

			var currentPath = $('#smkfm-column-right a.smkfm-selected').find('input[name=\'image\']').attr('value');
			if (currentPath) {
				var currentName = currentPath.replace(/^.*\/([^\/]*)$/, '$1');
				
				var html = '<div id="smkfm-dialog">';
				html += '  ' + smkfmp.entry_rename + ' <input type="text" name="name" value="' + currentName + '" /><br /><br />';
				html += '  <input type="button" value="' + smkfmp.button_submit + '" />';
				html += '</div>';

				$('#smkfm-column-right').prepend(html);

				$('#smkfm-dialog').dialog({
					title: smkfmp.button_rename,
					resizable: false,
					appendTo: '#filemanager .smkfm-body',
				});

				$('#smkfm-dialog input[type=\'button\']').bind('click', function() {
					var path = $('#smkfm-column-right a.smkfm-selected').find('input[name=\'image\']').attr('value');

					if (path) {
						$.ajax({
							url: 'index.php?route=' + smkfmp.route + '/rename&token=' + smkfmp.token,
							type: 'post',
							data: 'path=' + encodeURIComponent(path) + '&name=' + encodeURIComponent($('#smkfm-dialog input[name=\'name\']').val()),
							dataType: 'json',
							success: function(json) {
								if (json.success) {
									$('#smkfm-dialog').remove();

									var tree = $.tree.focused();

									tree.select_branch(tree.selected);
								}

								if (json.error) {
									alert(json.error);
								}
							},
							error: function(xhr, ajaxOptions, thrownError) {
								alert(thrownError + "\n" + xhr.statusText + "\n" + xhr.responseText);
							}
						});

					} else {
						var tree = $.tree.focused();

						$.ajax({
							url: 'index.php?route=' + smkfmp.route + '/rename&token=' + smkfmp.token,
							type: 'post',
							data: 'path=' + encodeURIComponent($(tree.selected).attr('directory')) + '&name=' + encodeURIComponent($('#smkfm-dialog input[name=\'name\']').val()),
							dataType: 'json',
							success: function(json) {
								if (json.success) {
									$('#smkfm-dialog').remove();

									tree.select_branch(tree.parent(tree.selected));

									tree.refresh(tree.selected);
								}

								if (json.error) {
									alert(json.error);
								}
							},
							error: function(xhr, ajaxOptions, thrownError) {
								alert(thrownError + "\n" + xhr.statusText + "\n" + xhr.responseText);
							}
						});
					}
				});
			} else {
				console.log("error: current path is unknown!");
			}
		});

		new AjaxUpload('#smkfm-button-upload', {
			action: 'index.php?route=' + smkfmp.route + '/upload&token=' + smkfmp.token,
			name: 'image',
			autoSubmit: false,
			responseType: 'json',
			onChange: function(file, extension) {
				var tree = $.tree.focused();

				if (tree.selected) {
					this.setData({'directory': $(tree.selected).attr('directory')});
				} else {
					this.setData({'directory': ''});
				}

				this.submit();
			},
			onSubmit: function(file, extension) { },
			onComplete: function(file, json) {
				if (json.success) {
					var tree = $.tree.focused();

					tree.select_branch(tree.selected);

					alert(json.success);
				}

				if (json.error) {
					alert(json.error);
				}

				$('.loading').remove();
			}
		});

		$('#smkfm-button-refresh').bind('click', function() {
			var tree = $.tree.focused();

			tree.refresh(tree.selected);
		});

		// ----------------------- Multi Upload ------------------------

		$('#smkfm-button-uploadmulti').click(function() {
			// console.log('Click on Multi Upload button');
			
			var html = '<div id="uploadMulti" title="' + smkfmp.text_upload_plus + '">';
			html += '  <div id="smk-uploader"></div>';
			html += '</div>';

			$('#smkfm-column-left').prepend(html);

			$('#uploadMulti').dialog({
				height: '355',
				width: '760',
				modal: true,
				resizable: false,
				create: function(event, ui) {
					var tree = $.tree.focused();

					// console.log('plupload_extensions: ' + smkfmp.plupload_extensions);
					
					// http://www.plupload.com/docs/Image-Resizing-on-Client-Side
					$('#smk-uploader').plupload({
						runtimes : 'html5,flash,silverlight',
						url: 'index.php?route=' + smkfmp.route + '/multi&token=' + smkfmp.token + '&directory=' + window.dr,
						max_file_count: 20,
						max_file_size: smkfmp.plupload_max_file_size,
						chunk_size: '1mb',
						max_retries: 3,
						prevent_duplicates: true,
						// resize: { height:600, width:800, quality:90 },
						filters: [ { title: smkfmp.text_allowed, extensions: smkfmp.plupload_extensions } ],
						flash_swf_url: 'view/javascript/plupload/js/Moxie.swf',
						silverlight_xap_url: 'view/javascript/plupload/js/Moxie.xap'
					});
					
					var uploader = $('#smk-uploader').plupload('getUploader');
					
					/*uploader.bind('Error', function(uploader, error) {
						var msg = 'Error ' + error.code + ': ' + error.message;
						console.log(msg);
						alert(msg);
					});*/
					
					// file upload error
					uploader.bind('FileUploaded', function(uploader, file, result) {
						if (result.response) {
							// console.log(result.response);
							var response = JSON.parse(result.response);
							if (response) {
								if (response.error) {
									var msg = 'Error ' + response.error.code + ': ' + response.error.message;
									// console.log(msg);
									alert(msg);
								}
							}
						}
					});
							
					$('form').submit(function(e) {
						var uploader = $('#smk-uploader').plupload('getUploader');
						var tree = $.tree.reference('#smkfm-column-left a');

						if (uploader.files.length > 0) {
							uploader.bind('StateChanged', function() {
								if (uploader.files.length === (uploader.total.uploaded + uploader.total.failed)) {
									$('form')[0].submit();
								}
							});

							uploader.start();
						} else {
							alert(text_no_selection);

							return false;
						}
					});
				},

				close: function(event, ui) {
					var uploader = $('#smk-uploader').plupload('getUploader');
					var tree = $.tree.reference('#smkfm-column-left a');

					tree.select_branch(tree.selected);
					uploader.destroy();
					$('#uploadMulti').remove();
				},

				appendTo: '#filemanager .smkfm-body',
			});
		});
	});
}