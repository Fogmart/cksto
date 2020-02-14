<?php
class ControllerExtensionModuleMultistore extends Controller {
	
	private $error = array();
	private $version = '1.1.3';

	public function index() {
		
		$this->load->language('extension/module/multistore');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');
		$this->load->model('extension/module/multistore');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			
			$post = $this->request->post;

			// Если не выбран магазиин по-умолчанию, то выберем первый из списка
			if (!isset($post['multistore_default']) && isset($post['multistores'])) {
				$current = current($post['multistores']);
			}
			
			// Сохраняем настройки модуля
			$this->model_setting_setting->editSetting('multistore', $post);

			// Сохраняем сами магазины в отдельную таблицу
			if (isset($post['multistores'])) {
				$this->model_extension_module_multistore->setMultistores($post['multistores']);
			} else {
				// Удаляем
				$this->model_extension_module_multistore->setMultistores();
			}

			$data['success'] = $this->language->get('text_success');
			
		}

		$data['error'] = array();

		if (isset($this->error['permission'])) {
			$data['error'][] = $this->error['permission'];
		}

		if (!file_exists(DIR_SYSTEM.'library/PHPExcel.php')) {
			$data['error'][] = $this->language->get('error_phpexcel');
		}
		
		$data['multistores'] = $this->model_extension_module_multistore->getMultistores();
		
		if (isset($this->request->post['multistore_status'])) {
			$data['multistore_status'] = $this->request->post['multistore_status'];
		} else {
			$data['multistore_status'] = $this->config->get('multistore_status');
		}

		if (isset($this->request->post['multistore_default'])) {
			$data['multistore_default'] = $this->request->post['multistore_default'];
		} elseif ($this->config->get('multistore_default')) {
			$data['multistore_default'] = $this->config->get('multistore_default');
		} elseif (!empty($data['multistores'])) {
			$data['multistore_default'] = $data['multistores'][0]['multistore_id'];
		} else {
			$data['multistore_default'] = '';
		}

		if (isset($this->request->post['multistore_template'])) {
			$data['multistore_template'] = $this->request->post['multistore_template'];
		} else {
			$data['multistore_template'] = $this->config->get('multistore_template');
		}
		
		if (isset($this->request->post['multistore_modificator'])) {
			$data['multistore_modificator'] = $this->request->post['multistore_modificator'];
		} else {
			$data['multistore_modificator'] = $this->config->get('multistore_modificator');
		}

		if (isset($this->request->post['multistore_empty'])) {
			$data['multistore_empty'] = $this->request->post['multistore_empty'];
		} else {
			$data['multistore_empty'] = $this->config->get('multistore_empty');
		}

		$this->load->model('localisation/geo_zone');
		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		$data['token'] = $this->session->data['token'];

		$data['breadcrumbs'] = array();
 
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'token=' . $this->session->data['token'] . '&type=module', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/module/multistore', 'token=' . $this->session->data['token'], true)
		);

		$data['heading_title'] = $this->language->get('heading_title');
		$data['text_extension'] = $this->language->get('text_extension');
		$data['text_success'] = $this->language->get('text_success');
		$data['text_edit'] = $this->language->get('text_edit');
		$data['tab_general'] = $this->language->get('tab_general');
		$data['tab_store'] = $this->language->get('tab_store');
		$data['tab_import'] = $this->language->get('tab_import');
		$data['tab_about'] = $this->language->get('tab_about');
		$data['tab_multistore'] = $this->language->get('tab_multistore');
		$data['entry_yes'] = $this->language->get('entry_yes');
		$data['entry_no'] = $this->language->get('entry_no');
		$data['entry_support'] = $this->language->get('entry_support');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_template'] = $this->language->get('entry_template');
		$data['entry_modificator'] = $this->language->get('entry_modificator');
		$data['entry_empty'] = $this->language->get('entry_empty');
		$data['entry_template_numeric'] = $this->language->get('entry_template_numeric');
		$data['entry_template_text'] = $this->language->get('entry_template_text');
		$data['entry_name'] = $this->language->get('entry_name');
		$data['entry_description'] = $this->language->get('entry_description');
		$data['entry_type'] = $this->language->get('entry_type');
		$data['entry_sort'] = $this->language->get('entry_sort');
		$data['entry_setting'] = $this->language->get('entry_setting');
		$data['entry_infinity'] = $this->language->get('entry_infinity');
		$data['entry_default'] = $this->language->get('entry_default');
		$data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$data['entry_geo_all'] = $this->language->get('entry_geo_all');
		$data['entry_store'] = $this->language->get('entry_store');
		$data['entry_stock'] = $this->language->get('entry_stock');
		$data['entry_trade'] = $this->language->get('entry_trade');
		$data['entry_download'] = $this->language->get('entry_download');
		$data['entry_loading'] = $this->language->get('entry_loading');
		$data['entry_load'] = $this->language->get('entry_load');
		$data['entry_import'] = $this->language->get('entry_import');
		$data['entry_experement'] = $this->language->get('entry_experement');
		$data['entry_import_success'] = $this->language->get('entry_import_success');
		$data['entry_import_failed'] = $this->language->get('entry_import_failed');
		$data['button_add'] = $this->language->get('button_add');
		$data['button_save'] = $this->language->get('button_add');
		$data['button_cancel'] = $this->language->get('button_add');
		$data['file_column_name'] = $this->language->get('file_column_name');
		$data['file_column_model'] = $this->language->get('file_column_model');
		$data['file_column_sku'] = $this->language->get('file_column_sku');
		$data['file_example_name'] = $this->language->get('file_example_name');
		$data['file_example_model'] = $this->language->get('file_example_model');
		$data['file_example_sku'] = $this->language->get('file_example_sku');
		$data['help_template'] = $this->language->get('help_template');
		$data['help_xlsx'] = $this->language->get('help_xlsx');
		$data['help_default'] = $this->language->get('help_default');
		$data['error_permission'] = $this->language->get('error_permission');
		$data['error_stock'] = $this->language->get('error_stock');
		$data['error_method'] = $this->language->get('error_method');
		$data['error_multistore_empty'] = $this->language->get('error_multistore_empty');
		$data['error_search_products'] = $this->language->get('error_search_products');
		$data['error_file_empty'] = $this->language->get('error_file_empty');
		$data['error_file_upload'] = $this->language->get('error_file_upload');
		$data['error_file_code'] = $this->language->get('error_file_code');
		$data['about_module'] = $this->language->get('about_module');
		$data['version'] = sprintf($this->language->get('version'), $this->version);

		$data['action'] = $this->url->link('extension/module/multistore', 'token=' . $this->session->data['token'], true);

		$data['cancel'] = $this->url->link('marketplace/extension', 'token=' . $this->session->data['token'] . '&type=module', true);
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/multistore', $data));
	}

	protected function validate() {
		
		// Доступ
		if (!$this->user->hasPermission('modify', 'extension/module/multistore')) {
			$this->error['permission'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

	public function import(){

		$this->load->model('extension/module/multistore');
		$this->load->language('extension/module/multistore');

		$json = array();

		// Check method
		if ($this->request->server['REQUEST_METHOD'] != 'POST') {
			$json['error'] = $this->language->get('error_method');
		}

		// Get file code
		if (!$json) {

			if (isset($this->request->post['code'])) {

				$this->load->model('tool/upload');
				$result = $this->model_tool_upload->getUploadByCode($this->request->post['code']);
				if (empty($result['filename'])) {
					$json['error'] = $this->language->get('error_file_code');
				}
				
			} else {
				$json['error'] = $this->language->get('error_file_upload');
			}

		}

		// Получаем адрес файла
		if (!$json) {
				
				$products = $this->model_extension_module_multistore->readFile($result['filename']);

				if (empty($products)) {
					$json['error'] = $this->language->get('error_file_empty');
				}
		}

		if (!$json) {

			$success 	= 0;
			$failed 	= 0;

			$this->load->model('catalog/product');
			foreach($products as $key => $product){

				$search = $this->model_catalog_product->getProducts(array(
					'filter_name' => $product['name'],
					'filter_model' => $product['model']
				));

				if (!empty($search)){
					$products[$key]['product_id'] = $search[0]['product_id'];
					$success++;
				} else {
					unset($products[$key]);
					$failed++;
				}

			}

			if (empty($products)) {
				$json['error'] = $this->language->get('error_search_products');
			}

		}

		if (!$json) {

			foreach($products as $product){
				$this->model_extension_module_multistore->setProductMultistores(
					$product['product_id'], 
					$product
				);
			}

			$json['products'] = $products;
			$json['success'] = $success;
			$json['failed'] = $failed;

		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));

	}

	public function getTemplate(){

		$this->load->language('extension/module/multistore');
		$this->load->model('extension/module/multistore');

		$json = array();

		// Check method
		if ($this->request->server['REQUEST_METHOD'] != 'GET') {
			$json['error'] = $this->language->get('error_method');
		}

		// Get multistores
		if (!$json) {

			$multistores = $this->model_extension_module_multistore->getMultistores();
			if (empty($multistores)) {
				$json['error'] = $this->language->get('error_multistore_empty');
			}

		}

		// Получаем сам файл
		if (!$json) {
			$this->load->model('extension/module/multistore');
			$json['route'] = $this->model_extension_module_multistore->createTemplate($multistores);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));

	}

	public function install() {
        $this->load->model('extension/module/multistore');
        $this->model_extension_module_multistore->install();
    }

    public function uninstall() {
		$this->load->model('extension/module/multistore');
		$this->model_extension_module_multistore->uninstall();
	}

}