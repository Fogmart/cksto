<?php

class ControllerCommonSmkFileManager extends Controller {
	public function smk_version() {
		return '2.1.11';
	}
	
	private function smk_debug_mode() {
		return false;
	}
	
	protected function get_default_file_perms() {
		$perms = 0700;
		// $perms = 0777;
		return $perms;
	}
	
	protected function get_default_dir_perms() {
		$perms = 0700;
		// $perms = 0777;
		return $perms;
	}
	
	protected function get_maximum_file_size() {
		$size = max(3*1024*1024, (int) $this->config->get('config_file_max_size'));
		return $size;
	}
	
	protected function get_thumb_size() {
		return 150;
	}
	
	protected function unix_slashes($s) {
		return str_replace('\\', '/', $s);
	}

	// Check to see if any PHP files are trying to be uploaded	
	protected function is_executable_file($content, $path = NULL) {
		if (!isset($content)) {
			if (isset($path)) {
				$in = fopen($path, "rb");
				if ($in) {
					$buffer_size = 256*1024;
					$prev_buff = '';
					while ($buff = fread($in, $buffer_size)) {
						if ($this->is_executable_file($prev_buff . $buff)) {
							return true;
						}
						$prev_buff = $buff;
					}
					fclose($in);
				} else {
					// can't open file
					return true;
				}
				
				return false;
			}
		}
		
		// simple security check
		return preg_match('/\<\?php/is', $content);
	}
	
	protected function get_allowed_file_extensions() {
		$config_file_ext_allowed = strtolower($this->config->get('config_file_ext_allowed'));
		$config_file_ext_allowed = html_entity_decode($config_file_ext_allowed, ENT_QUOTES, 'UTF-8');
		$config_file_ext_allowed = str_replace("\r", '', $config_file_ext_allowed);
		$a = explode("\n", $config_file_ext_allowed);
		// remove empty elements
		$a = array_filter($a);
		return $a;
	}
	
	protected function get_allowed_mime_types() {
		$config_file_mime_allowed = $this->config->get('config_file_mime_allowed');
		$config_file_mime_allowed = html_entity_decode($config_file_mime_allowed, ENT_QUOTES, 'UTF-8');
		$config_file_mime_allowed = str_replace("\r", '', $config_file_mime_allowed);
		$a = explode("\n", $config_file_mime_allowed);
		// remove empty elements
		$a = array_filter($a);
		return $a;
	}

	// check file type
	protected function is_allowed_file_type($name) {
		$ext = strtolower($this->fileext($name));
		$allowed = $this->get_allowed_file_extensions();
		$is_allowed = in_array($ext, $allowed);
		if ($this->smk_debug_mode()) {
			$this->log->write(sprintf("%s %s in %s", $ext, $is_allowed ? 'is' : 'is not', print_r($allowed, true)));
		}
		return $is_allowed;
	}
	
	protected function get_mime($path) {
		$finfo = finfo_open(FILEINFO_MIME_TYPE);
		$mime = finfo_file($finfo, $path);
		finfo_close($finfo);
		return $mime;
	}
	
	// check file MIME type
	protected function is_allowed_mime_type($mime, $path = NULL) {
		if (!isset($mime)) {
			if (isset($path)) {
				$mime = $this->get_mime($path);
			}
		}
		$allowed = $this->get_allowed_mime_types();
		return in_array($mime, $allowed);
	}
	
	// get icon associated with the file, or file itself for graphical file types
	protected function get_file_icon($path) {
		$ext = $this->fileext($path);
		
		switch ($lext = strtolower($ext)) {
			case 'png':
			case 'jpg':
			case 'jpeg':
			case 'gif':
				$icon = $path;
				break;
			case 'pdf':
			case 'flv':
			case 'swf':
			case 'zip':
			case 'rar':
			case 'doc':
				$icon = "$lext.png";
				break;
			case 'docx':
				$icon = 'doc.png';
				break;
			default:
				$icon = 'regular_file.png';
				break;
		}
		
		return $this->get_relative_image_path($icon);
	}
	
	// this is still needed for some reason
	public function image() {
		if (isset($this->request->get['image'])) {
			$img = $this->decode_check_path($this->request->get['image']);
			$icon = $this->get_file_icon($img);
			
			// TODO: remove?
			if ($icon != $img)
				$this->request->get['image'] = $this->tohtml($icon);

			$this->load->model('tool/image');

			$data['token'] = $this->session->data['token'];

			$this->response->setOutput($this->model_tool_image->resize($icon, $this->get_thumb_size(), $this->get_thumb_size()));
		}
	}

	// get directory listing
	public function directory() {
		$json = array();

		if (isset($this->request->post['directory'])) {
			$directories = (array) $this->globcat($this->decode_check_path($this->request->post['directory']));
			$i = 0;
			foreach ($directories as $directory) {
				$json[$i]['data'] = $this->smk_utf8_basename($directory);
				$json[$i]['attributes']['directory'] = $this->get_relative_to_catalog_image_path($directory);

				$children = $this->globhelp($directory);
				if ($children)
					$json[$i]['children'] = ' ';

				$i++;
			}
		}

		$this->response->setOutput(json_encode($json));
	}

	public function files() {
		$this->load->model('tool/image');

		$data['token'] = $this->session->data['token'];

		$json = array();

		if (!empty($this->request->post['directory'])) {
			$directory = $this->get_absolute_catalog_path($this->check_path($this->request->post['directory']));
		} else {
			$directory = $this->get_catalog_base();
		}

		$files = (array) $this->globhelp($directory, NULL);

		foreach ($files as $file) {
			if (is_file($file) && $this->is_allowed_file_type($file)) {
				$thumb = $this->get_file_icon($file);
				$thumb = $this->model_tool_image->resize($thumb, $this->get_thumb_size(), $this->get_thumb_size());

				if (empty($thumb))
					$this->log->write(sprintf("%s: can't resize file '%s'", __METHOD__, $file));

				$json[] = array(
					'filename'      => $this->smk_utf8_basename($file),
					'file'     		=> $this->unix_slashes($this->get_relative_to_catalog_image_path($file)),
					'size'     		=> $this->get_human_readable_file_size(filesize($file)),
					'thumb'         => $this->unix_slashes($thumb),
				);
			} else {
				if ($this->smk_debug_mode()) {
					$this->log->write(sprintf("%s: file '%s' has not allowed type", __METHOD__, $file));
				}
			}
		}

		$this->response->setOutput(json_encode($json));
	}

	// create new directory
	public function create() {
		$this->load->language('common/' . $this->_name);

		$json = array();

		if (isset($this->request->post['name'])) {
			$name = $this->decode_check_path($this->request->post['name']);
		}
		
		if (isset($this->request->post['directory'])) {
			$directory = $this->get_absolute_catalog_path($this->decode_check_path_noslash($this->request->post['directory']));
			
			if (!empty($name)) {
				if (!is_dir($directory)) {
					$json['error'] = $this->language->get('error_directory');
				} else if (file_exists("$directory/$name")) {
					$json['error'] = $this->language->get('error_exists');
				}
			} else {
				$json['error'] = $this->language->get('error_name');
			}
		} else {
			$json['error'] = $this->language->get('error_directory');
		}

		if (!$this->user->hasPermission('modify', 'common/' . $this->_name)) {
      		$json['error'] = $this->language->get('error_permission');
		}

		if (!isset($json['error'])) {
			if (@mkdir("$directory/$name", $this->get_default_dir_perms())) {
				$json['success'] = $this->language->get('text_create');
			} else {
				$json['error'] = $this->language->get('error_directory');
			}
		}

		$this->response->setOutput(json_encode($json));
	}

	// delete file or directory
	public function delete() {
		$this->load->language('common/' . $this->_name);

		$json = array();

		if (!empty($this->request->post['path'])) {
			$path = $this->get_absolute_catalog_path($this->decode_check_path_noslash($this->request->post['path']));

			if (empty($path) || $path == $this->get_catalog_base()) {
				$json['error'] = $this->language->get('error_delete');
			} else if (!file_exists($path)) {
				$json['error'] = $this->language->get('error_select');
			}
		} else {
			$json['error'] = $this->language->get('error_select');
		}

		if (!$this->user->hasPermission('modify', 'common/' . $this->_name)) {
      		$json['error'] = $this->language->get('error_permission');
		}

		if (!isset($json['error'])) {
			if (is_file($path)) {
				if (!@unlink($path)) {
					$json['error'] = $this->language->get('error_delete');
				}
			} elseif (is_dir($path)) {
				if (!$this->recursiveDelete($path)) {
					$json['error'] = $this->language->get('error_delete');
				}
			}
			
			if (!isset($json['error'])) {
				$json['success'] = $this->language->get('text_delete');
			}
		}

		$this->response->setOutput(json_encode($json));
	}

	protected function recursiveDelete($directory) {
		$result = true;
		
		if (is_dir($directory))
			$handle = opendir($directory);

		if (!$handle)
			return false;

		while (false !== ($file = readdir($handle))) {
			if ($file != '.' && $file != '..') {
				$path = "$directory/$file";
				if (!is_dir($path)) {
					if (!@unlink($path)) {
						$result = false;
					}
				} else {
					if (!$this->recursiveDelete($path)) {
						$result = false;
					}
				}
			}
		}

		closedir($handle);
		
		if (!@rmdir($directory)) {
			$result = false;
		}
		
		return $result;
	}

	public function move() {
		$this->load->language('common/' . $this->_name);

		$json = array();

		if (isset($this->request->post['from']) && isset($this->request->post['to'])) {
			$from = $this->get_absolute_catalog_path($this->decode_check_path_noslash($this->request->post['from']));

			if (!file_exists($from)) {
				$json['error'] = $this->language->get('error_missing');
			}

			if ($from == $this->get_image_data()) {
				$json['error'] = $this->language->get('error_default');
			}

			$to = $this->get_absolute_catalog_path($this->decode_check_path_noslash($this->request->post['to']));

			if (!file_exists($to)) {
				$json['error'] = $this->language->get('error_move');
			}

			if (file_exists($to . '/' . $this->smk_utf8_basename($from))) {
				$json['error'] = $this->language->get('error_exists');
			}
		} else {
			$json['error'] = $this->language->get('error_directory');
		}

		if (!$this->user->hasPermission('modify', 'common/' . $this->_name)) {
      		$json['error'] = $this->language->get('error_permission');
		}

		if (!isset($json['error'])) {
			if (!strstr($to, $from)) {
				if (@rename($from, $to . '/' . $this->smk_utf8_basename($from))) {
					$json['success'] = $this->language->get('text_move');
				} else {
					$json['error'] = $this->language->get('error_move');
				}
			} else {
				$json['error'] = $this->language->get('error_exists');
			}
		}

		$this->response->setOutput(json_encode($json));
	}

	public function copy() {
		$this->load->language('common/' . $this->_name);

		$json = array();

		if (isset($this->request->post['path']) && isset($this->request->post['name'])) {
			$name = $this->decode_check_path_noslash($this->request->post['name']);
			if (utf8_strlen($name) < 3 || utf8_strlen($name) > 255) {
				$json['error'] = $this->language->get('error_filename');
			}

			$old_name = $this->get_absolute_catalog_path($this->decode_check_path_noslash($this->request->post['path']));

			if (!file_exists($old_name) || $old_name == $this->get_image_data()) {
				$json['error'] = $this->language->get('error_copy');
			}

			$new_name = dirname($old_name) . "/$name";
			// if name, provided by user, don't have extension, add it
			if (!$this->fileext($new_name))
				$new_name .= '.' . $this->fileext($old_name);

			if (file_exists($new_name)) {
				$json['error'] = $this->language->get('error_exists');
			}

		} else {
			$json['error'] = $this->language->get('error_select');
		}

		if (!$this->user->hasPermission('modify', 'common/' . $this->_name)) {
      		$json['error'] = $this->language->get('error_permission');
		}

		if (!isset($json['error'])) {
			if ($old_name == $new_name) {
				$json['error'] = $this->language->get('error_exists');
			} else {
				if (is_file($old_name)) {
					if (!copy($old_name, $new_name)) {
						$this->log->write(sprintf("%s: copy('%s', '%s') failed", __METHOD__, $old_name, $new_name));
					}
				} else {
					$this->recursiveCopy($old_name, $new_name);
				}
			}
			$json['success'] = $this->language->get('text_copy');
		}

		$this->response->setOutput(json_encode($json));
	}

	protected function recursiveCopy($source, $destination) {
		$directory = opendir($source);
		@mkdir($destination);
		while (false !== ($file = readdir($directory))) {
			if (($file != '.') && ($file != '..')) {
				$src_path = "$source/$file";
				$dest_path = "$destination/$file";
				if (is_dir($src_path)) {
					$this->recursiveCopy($src_path, $dest_path);
				} else {
					if (!copy($src_path, $dest_path)) {
						$this->log->write(sprintf("%s: copy('%s', '%s') failed", __METHOD__, $src_path, $dest_path));
					}
				}
			}
		}
		closedir($directory);
	}

	public function folders() {
		$this->response->setOutput($this->recursiveFolders($this->get_catalog_base(true)));
	}

	protected function recursiveFolders($directory) {
		$output = '';
		$rel = $this->get_relative_to_catalog_image_path($directory);
		$output .= '<option value="' . $this->htmlattr($rel) . '">' . $this->tohtml($rel) . '</option>';
		$directories = (array) $this->globhelp($this->check_path($directory));
		foreach ($directories as $directory) {
			$output .= $this->recursiveFolders($directory);
		}
		return $output;
	}

	// rename file (TODO: directory)
	public function rename() {
		$this->load->language('common/' . $this->_name);

		$json = array();

		if (isset($this->request->post['path']) && isset($this->request->post['name'])) {
			$name = $this->decode_check_path($this->request->post['name']);
			
			if (utf8_strlen($name) < 3 || utf8_strlen($name) > 255) {
				$json['error'] = $this->language->get('error_filename');
			}

			$old_name = $this->get_absolute_catalog_path($this->decode_check_path_noslash($this->request->post['path']));

			if (!file_exists($old_name) || $old_name == $this->get_catalog_base()) {
				$json['error'] = $this->language->get('error_rename');
			}

			$new_name = dirname($old_name) . "/$name";

			if (file_exists($new_name)) {
				$json['error'] = $this->language->get('error_exists');
			}
		}

		if (!$this->user->hasPermission('modify', 'common/' . $this->_name)) {
      		$json['error'] = $this->language->get('error_permission');
		}

		if (!isset($json['error'])) {
			if (rename($old_name, $new_name)) {
				$json['success'] = $this->language->get('text_rename');
			} else {
				$json['error'] = $this->language->get('error_rename') . ' (2)';
			}
		}

		$this->response->setOutput(json_encode($json));
	}

	public function upload() {
		$this->load->language('common/' . $this->_name);

		$json = array();

		if (!$this->user->hasPermission('modify', 'common/' . $this->_name)) {
      		$json['error'] = $this->language->get('error_permission');
		} else if (isset($this->request->post['directory'])) {
			if (isset($this->request->files['image']) && !empty($this->request->files['image']['tmp_name'])) {
				$filename = $this->smk_utf8_basename($this->decode_check_path($this->request->files['image']['name']));

				if (utf8_strlen($filename) < 3 || utf8_strlen($filename) > 255) {
					$this->log->write(sprintf("%s: filename '%s' not allowed", __METHOD__, $filename));
					$json['error'] = $this->language->get('error_filename');
				}

				$directory = $this->get_absolute_catalog_path($this->decode_check_path_noslash($this->request->post['directory']));

				if (!is_dir($directory)) {
					$this->log->write(sprintf("%s: not a directory: '%s'", __METHOD__, $directory));
					$json['error'] = $this->language->get('error_directory');
				} else {
					$file_max_size = $this->get_maximum_file_size();
					$size = (int) $this->request->files['image']['size'];
					if ($this->request->files['image']['size'] == 0 || $size > $file_max_size) {
						$this->log->write(sprintf("%s: file size %d not allowed", __METHOD__, $size));
						$json['error'] = $this->language->get('error_file_size');
					} else {
						$type_ok = $this->is_allowed_file_type($filename);
						$mime_ok = $this->is_allowed_mime_type($this->request->files['image']['type']);
						$contents_ok = !$this->is_executable_file(NULL, $this->request->files['image']['tmp_name']);
						if (!$type_ok || !$mime_ok || !$contents_ok) {
							$this->log->write(sprintf("%s: file '%s' parameters %d, %d, %d not allowed", __METHOD__, $filename, $type_ok, $mime_ok, $contents_ok));
							$json['error'] = $this->language->get('error_file_type');
						} else if ($this->request->files['image']['error'] != UPLOAD_ERR_OK) {
							$json['error'] = 'error_upload_' . $this->request->files['image']['error'];
						} else if (!isset($json['error'])) {
							if (@move_uploaded_file($this->request->files['image']['tmp_name'], "$directory/$filename")) {
								$json['success'] = $this->language->get('text_uploaded');
							} else {
								$json['error'] = $this->language->get('error_uploaded');
							}
						}
					}
				}
			} else {
				$json['error'] = $this->language->get('error_file');
			}
		} else {
			$json['error'] = $this->language->get('error_directory');
		}

		$this->response->setOutput(json_encode($json));
	}

	// ---------------- Multiple Upload -------------------

	private function uniq_file_name($dir, $filename, $count) {
		return "$dir/{$count}_{$filename}";
	}

	public function multi() {
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: no-store, no-cache, must-revalidate");
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");

		$dir = $this->decode_check_path_noslash($this->request->get['directory']);
		$targetDir = $this->get_absolute_catalog_path($dir, true);

		$chunk = intval(@$this->request->request['chunk']);
		$chunks = intval(@$this->request->request['chunks']);

		$filename = isset($this->request->request['name']) ? $this->smk_utf8_basename($this->decode_check_path($this->request->request['name'])) : '';

		if (!empty($filename)) {
			if ($this->is_allowed_file_type($filename)) {
				if (isset($_SERVER["HTTP_CONTENT_TYPE"]))
					$contentType = $_SERVER["HTTP_CONTENT_TYPE"];
				if (isset($_SERVER["CONTENT_TYPE"]))
					$contentType = $_SERVER["CONTENT_TYPE"];
				
				if (!file_exists($targetDir)) {
					if (!@mkdir($targetDir)) {
						$this->log->write(sprintf("%s: can't create directory '%s'", __METHOD__, $targetDir));
					}
				}

				$path = "$targetDir/$filename";

				// not chunked
				if ($chunks < 2 && file_exists($path)) {
					$count = 0;
					do {
						$newname = $this->uniq_file_name($targetDir, $filename, ++$count);
					} while (file_exists($newname));
					$filename = $newname;
				}

				$buffer_size = 256*1024;
				$prev_buff = '';
				$file_size = 0;
				$max_file_size = $this->get_maximum_file_size();
				// uploaded file is executable (PHP file, for example)
				$security_passed = true;
				if (strpos($contentType, "multipart") !== false) {
					// chunked, this is default
					if (isset($this->request->files['file']['tmp_name']) && is_uploaded_file($this->request->files['file']['tmp_name'])) {
						if ($this->is_allowed_mime_type($this->request->files['file']['type'])) {
							$out = fopen($path, $chunk == 0 ? "wb" : "ab");

							if ($out) {
								$in = fopen($this->request->files['file']['tmp_name'], "rb");

								if ($in) {
									while ($buff = fread($in, $buffer_size)) {
										$file_size += $buffer_size;
										if ($file_size > $max_file_size || $this->is_executable_file($prev_buff . $buff)) {
											$security_passed = false;
											break;
										}
										$prev_buff = $buff;
										fwrite($out, $buff);
									}
								} else {
									die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
								}

								fclose($in);
								fclose($out);

								@unlink($this->request->files['file']['tmp_name']);
								
								// not tested, because there is JavaScript tests also
								if (!$security_passed) {
									@unlink($path);
									die('{"jsonrpc" : "2.0", "error" : {"code": 103, "message": "Uploaded file is executable or is too big and was removed for security reasons."}, "id" : "id"}');
								}
								
								// MIME type is alredy checked
							} else {
								die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
							}
						} else {
							die('{"jsonrpc" : "2.0", "error" : {"code": 103, "message": "File has not allowed mime type."}, "id" : "id"}');
						}
					} else {
						die('{"jsonrpc" : "2.0", "error" : {"code": 103, "message": "Failed to move uploaded file."}, "id" : "id"}');
					}
				} else {
					// not chunked, this is not tested yet
					$out = fopen($path, $chunk == 0 ? "wb" : "ab");

					if ($out) {
						$in = fopen("php://input", "rb");

						if ($in) {
							while ($buff = fread($in, $buffer_size)) {
								$file_size += $buffer_size;
								if ($file_size > $max_file_size || $this->is_executable_file($prev_buff . $buff)) {
									$security_passed = false;
									break;
								}
								$prev_buff = $buff;
								fwrite($out, $buff);
							}
						} else {
							die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream (2)."}, "id" : "id"}');
						}

						fclose($in);
						fclose($out);
						
						// not tested, because there is JavaScript tests also
						if (!$security_passed) {
							@unlink($path);
							die('{"jsonrpc" : "2.0", "error" : {"code": 103, "message": "Uploaded file is executable or is too big and was removed for security reasons (2)."}, "id" : "id"}');
						}
						
						if (!$this->is_allowed_mime_type(NULL, $path)) {
							@unlink($path);
							die('{"jsonrpc" : "2.0", "error" : {"code": 103, "message": "File has not allowed mime type (2)."}, "id" : "id"}');
						}
					} else {
						die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream (2)."}, "id" : "id"}');
					}
				}
			} else {
				if ($this->smk_debug_mode()) {
					$this->log->write(sprintf("%s: File has not allowed type (2).", __METHOD__));
				}
				die('{"jsonrpc" : "2.0", "error" : {"code": 103, "message": "File has not allowed type (2)."}, "id" : "id"}');
			}
		} else {
			if ($this->smk_debug_mode()) {
				$this->log->write(sprintf("%s: file name empty", __METHOD__));
			}
			die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream: file name empty."}, "id" : "id"}');
		}

		die('{"jsonrpc" : "2.0", "result" : null, "id" : "id"}');
	}

	protected function decode_check_path_noslash($s) {
		return $this->noslash($this->decode_check_path($s));
	}

	protected function decode_check_path($s) {
		return $this->check_path($this->fromhtml($s));
	}
	
	protected function check_path_noslash($s) {
		return $this->check_path($this->noslash($s));
	}
	
	protected function fileext($name) {
		$path_parts = pathinfo($name);
		return @$path_parts['extension'];
	}
	
	protected function check_path($s) {
		// '../', '.../' => ''
		$s = preg_replace('@\\.{2,}/@s', '', $s);
		// NULL byte
		$s = str_replace("\0", '', $s);
		return $s;
	}
	
	// remove slash at end of path
	protected function noslash($s) {
		return rtrim($s, '/\\');
	}
	
	// format file size
	protected function get_human_readable_file_size($size) {
		$suffix = array(
			'B',
			'KB',
			'MB',
			'GB',
			'TB',
			'PB',
			'EB',
			'ZB',
			'YB'
		);

		for ($i = 0; ($size / 1024) > 1; $i++)
			$size = $size / 1024;

		return round(utf8_substr($size, 0, utf8_strpos($size, '.') + 4), 2) . ' ' . $suffix[$i];
	}

	protected function fromhtml($s) {
		return html_entity_decode($s, ENT_QUOTES, 'UTF-8');
	}

	protected function tohtml($s) {
		return htmlentities($s, ENT_QUOTES, 'UTF-8');
	}

	// convert absolute path to relative to /image
	protected function get_relative_image_path($path) {
		if (strpos($path, DIR_IMAGE) === 0) {
			$path = utf8_substr($path, utf8_strlen(DIR_IMAGE));
		}

		return $path;
	}
	
	// convert absolute path to relative to /image/catalog
	protected function get_relative_to_catalog_image_path($path) {
		$catalog = $this->get_catalog_base(true);
		
		if (strpos($path, $catalog) === 0) {
			$path = utf8_substr($path, utf8_strlen($catalog));
		}

		return $path;
	}

	protected function globcat($s) {
		return $this->globhelp($this->get_catalog_base(true) . $s);
	}
	
	protected function globhelp($s, $params = GLOB_ONLYDIR) {
		$gexpr = $this->noslash($s) . '/*';
		// $this->log->write($gexpr);
 		$result = glob($gexpr, $params);
		// $this->log->write(print_r($result, true));
		return $result;
	}
	
	// get absolute path for file inside /image/catalog directory
	protected function get_absolute_catalog_path($relative_path, $no_slash_if_empty = false) {
		if (empty($relative_path)) {
			if ($no_slash_if_empty) {
				return $this->get_catalog_base();
			}
		}
		return $this->get_catalog_base(true) . $relative_path;
	}
	
	// get absolute path for /image/catalog directory
	protected function get_catalog_base($with_slash = false) {
		return DIR_IMAGE . $this->_smkfm_catalog . ($with_slash ? '/' : '');
	}

	// get absolute path for /image/data directory
	protected function get_image_data() {
		return DIR_IMAGE . 'data';
	}

	// cleanup string to use as HTML attribute
	protected function htmlattr($s) {
		$s = preg_replace('/[\r\n]+/s', '', $this->tohtml($s));
		return $s;
	}
	
	// Fucked PHP doesn't have UTF-8 equivalent for basename()
	private function smk_utf8_basename($path) {
		// dirname/filename.ext
		$re = '@^.*[\\\\/]([^\\\\/]+)$@s';
		if (preg_match($re, $path, $matches)) {
			return $matches[1];
		}
		
		// filename.ext
		$re = '@^([^\\\\/]+)$@s';
		if (preg_match($re, $path, $matches)) {
			return $matches[1];
		}
		
		// will never be executed
		return '';
	}
}
