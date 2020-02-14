<?php
class ControllerExtensionShippingMultistore extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/shipping/multistore');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('shipping_multistore', $this->request->post);

			$data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=shipping', true));
		}

		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_none'] = $this->language->get('text_none');

		$data['entry_total'] = $this->language->get('entry_total');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');

		$data['help_total'] = $this->language->get('help_total');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=shipping', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/shipping/multistore', 'token=' . $this->session->data['token'], true)
		);
		
		$data['action'] = $this->url->link('extension/shipping/multistore', 'token=' . $this->session->data['token'], true);

		$data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=shipping', true);

		if (isset($this->request->post['multistore_total'])) {
			$data['multistore_total'] = $this->request->post['multistore_total'];
		} else {
			$data['multistore_total'] = $this->config->get('multistore_total');
		}

		if (isset($this->request->post['multistore_status'])) {
			$data['multistore_status'] = $this->request->post['multistore_status'];
		} else {
			$data['multistore_status'] = $this->config->get('multistore_status');
		}

		if (isset($this->request->post['multistore_sort_order'])) {
			$data['multistore_sort_order'] = $this->request->post['multistore_sort_order'];
		} else {
			$data['multistore_sort_order'] = $this->config->get('multistore_sort_order');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/shipping/multistore', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/shipping/multistore')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}