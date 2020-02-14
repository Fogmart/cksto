<?php

// use absolute path to make it vqmod compatible
require_once DIR_APPLICATION . 'controller' . DIRECTORY_SEPARATOR . 'common' . DIRECTORY_SEPARATOR . 'smkfilemanager_class.php';

class ControllerCommonFileManager extends ControllerCommonSmkFileManager {
	protected $error = array();
	protected $_name = 'filemanager';
	protected $_smkfm_catalog = 'catalog';

	public function index() {
		$this->load->language('common/' . $this->_name);

		$data['entry_folder'] = $this->language->get('entry_folder');
		$data['entry_move'] = $this->language->get('entry_move');
		$data['entry_copy'] = $this->language->get('entry_copy');
		$data['entry_rename'] = $this->language->get('entry_rename');
		$data['smk_start_typing'] = $this->language->get('smk_start_typing');

		$data['button_exit'] = $this->language->get('button_exit');
		$data['button_folder'] = $this->language->get('button_folder');
		$data['button_delete'] = $this->language->get('button_delete');
		$data['button_move'] = $this->language->get('button_move');
		$data['button_copy'] = $this->language->get('button_copy');
		$data['button_rename'] = $this->language->get('button_rename');
		$data['button_upload'] = $this->language->get('button_upload');
		$data['button_uploads'] = $this->language->get('button_uploads');
		$data['button_refresh'] = $this->language->get('button_refresh');
		$data['button_submit'] = $this->language->get('button_submit');
		$data['button_expand'] = $this->language->get('button_expand');
		$data['button_collapse'] = $this->language->get('button_collapse');
		$data['button_view_text'] = $this->language->get('button_view_text');
		$data['button_view_list'] = $this->language->get('button_view_list');
		$data['button_view_thumb'] = $this->language->get('button_view_thumb');

		$data['text_loading'] = $this->language->get('text_loading');
		$data['text_no_file_found'] = $this->language->get('text_no_file_found');
		$data['text_confirm'] = $this->language->get('text_confirm');
		$data['text_folder_delete'] = $this->language->get('text_folder_delete');
		$data['text_folder_action'] = $this->language->get('text_folder_action');
		$data['text_folder_content'] = $this->language->get('text_folder_content');
		$data['text_file_delete'] = $this->language->get('text_file_delete');
		$data['text_file_action'] = $this->language->get('text_file_action');
		$data['text_no_image']	= $this->language->get('text_no_image');
		$data['text_select_image'] = $this->language->get('text_select_image');
		$data['text_update_image'] = $this->language->get('text_update_image');
		$data['text_yes_execute']= $this->language->get('text_yes_execute');
		$data['text_yes_delete'] = $this->language->get('text_yes_delete');
		$data['text_no_cancel'] = $this->language->get('text_no_cancel');
		$data['text_upload_plus'] = $this->language->get('text_upload_plus');
		$data['text_no_selection'] = $this->language->get('text_no_selection');
		$data['text_allowed'] = $this->language->get('text_allowed');

		$data['error_directory'] = $this->language->get('error_directory');

		$data['token'] = $this->session->data['token'];

		$server = '';
		if (preg_match('@^http://.*(/.+)/admin/$@', HTTP_SERVER, $matches)) {
			$server = $matches[1];
		}
		
		$data['directory'] = DIR_IMAGE . $this->_smkfm_catalog . DIRECTORY_SEPARATOR;
		$data['smkfm_image'] = sprintf('/%s/', basename(DIR_IMAGE));
		$data['smkfm_root'] = sprintf('%s%s%s/', $server, $data['smkfm_image'] , $this->_smkfm_catalog);
		$data['smkfm_catalog'] = $this->_smkfm_catalog;

		$this->load->model('tool/image');

		$data['no_image'] = $this->model_tool_image->resize('no_image.jpg', $this->get_thumb_size(), $this->get_thumb_size());

		if (isset($this->request->get['field'])) {
			$data['field'] = $this->request->get['field'];
		} else {
			$data['field'] = '';
		}

		// TODO
		if (isset($this->request->get['CKEditorFuncNum'])) {
			$data['fckeditor'] = $this->request->get['CKEditorFuncNum'];
		} else {
			$data['fckeditor'] = false;
		}
		
		// info:txtUrl
		if (isset($this->request->get['ckedialog'])) {
			$data['ckedialog'] = $this->request->get['ckedialog'];
		} else if (isset($this->request->get['cke'])) {
			$data['ckedialog'] = $this->request->get['cke'];
		}
		
		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$data['smkfm_admin'] = HTTPS_SERVER;
			$data['smkfm_site_root'] = HTTPS_CATALOG;
		} else {
			$data['smkfm_admin'] = HTTP_SERVER;
			$data['smkfm_site_root'] = HTTP_CATALOG;
		}

		$data['smkfm_plupload_max_file_size'] = $this->get_maximum_file_size();
		$data['smkfm_plupload_extensions'] = implode(',', $this->get_allowed_file_extensions());

		// Return the target ID for the file manager to set the value
		if (isset($this->request->get['target'])) {
			$data['target'] = $this->request->get['target'];
		} else {
			$data['target'] = '';
		}

		// Return the thumbnail for the file manager to show a thumbnail
		if (isset($this->request->get['thumb'])) {
			$data['thumb'] = $this->request->get['thumb'];
		} else {
			$data['thumb'] = '';
		}

		$this->response->setOutput($this->load->view($this->smkGetTemplateName(), $data));
	}

	private function smkGetTemplateName() {
		$template = 'common/' . $this->_name;
		
		if (in_array(VERSION, ['2.1.0.2.1'])) {
			$template .= '.tpl';
		}
		
		return $template;
	}
}

?>