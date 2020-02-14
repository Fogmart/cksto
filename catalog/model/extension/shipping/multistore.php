<?php
class ModelExtensionShippingMultistore extends Model {
	function getQuote($address) {
		$this->load->language('extension/shipping/multistore');
		
		$status = true;

		if ($this->cart->getSubTotal() < $this->config->get('multistore_total')) {
			$status = false;
		}

		$method_data = array();

		if ($status) {
			
			$this->load->model('extension/module/multistore');
			$this->load->language('extension/shipping/multistore');
			
			$multistores = $this->model_extension_module_multistore->getStores();

			if (!empty($multistores)) {

				$quote_data = array();

				foreach($multistores as $key => $multistore){

					if (isset($multistore['shipping'])) {
						$quote_data['multistore_'.$key] = array(
							'code'         => 'multistore.multistore_'.$key,
							'title'        => $multistore['shipping'],
							'cost'         => 0,
							'tax_class_id' => 0,
							'text'         => $this->currency->format(0.00, $this->session->data['currency'])
						);
					}
				}

				if (!empty($quote_data)) {
					$method_data = array(
						'code'       => 'multistore',
						'title'      => $this->language->get('text_title'),
						'quote'      => $quote_data,
						'sort_order' => $this->config->get('multistore_sort_order'),
						'error'      => false
					);
				}

			}
		}



		return $method_data;
	}
}