<?php
class ControllerExtensionModuleMultistore extends Controller {

	public function index() {
		
		$this->load->language('extension/module/multistore');
		$this->load->model('extension/module/multistore');

		$data['multistores'] = array();

		if ($this->request->server['REQUEST_METHOD'] == 'GET' && isset($this->request->get['product_id'])) {
			$data['multistores'] = $this->model_extension_module_multistore->getMultostoresForProduct($this->request->get['product_id']);
		}

		$data['text_multistore'] = $this->language->get('text_multistore');

		return $this->load->view('extension/module/multistore', $data);
	}

	public function getStoreId(){
		$this->load->model('extension/module/multistore');
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($this->model_extension_module_multistore->getStockId()));
	}
}