<?php
class ModelExtensionModuleMultistore extends Model {
	
	public function getStockId() {
		$multistores = $this->db->query("SELECT * FROM " . DB_PREFIX . "multistore WHERE `type` = 'stock'");
		return $multistores->row['multistore_id'];
	
	}

	public function getMultistores() {
		
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "multistore`");

		if ($query->num_rows == 0) {
			return array();
		}
		
		$multistores = $query->rows;
		
		if (!empty($this->session->data['shipping_address']['zone_id'])) {
			$zone_id = $this->session->data['shipping_address']['zone_id'];
		} elseif (!empty($this->session->data['prmn.city_manager'])){
			$zone_id = $this->progroman_city_manager->getZoneId();
		} else {
			$zone_id = 0;
		}
		
		foreach($multistores as $key => $multistore) {
			
			if ($multistore['geo_zone_id'] != 0 && !$this->isZone($zone_id, $multistore['geo_zone_id'])) {
				unset($multistores[$key]);
			}
			
		}

		$sort_order = array();

		foreach ($multistores as $key => $row) {
			$sort_order[$key] = $row['sort'];
		}

		array_multisort($sort_order, SORT_ASC, $multistores);

		return $multistores;
	}

	public function getProductMultistores($product_id) {
		
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "product_to_multistore` WHERE `product_id` = ".(int)$product_id);
		
		if ($query->num_rows > 0) {
			$result = array();
			foreach($query->rows as $row){
				$result[$row['multistore_id']] = $row['quantity'];
			}
			return $result;
		}
		
		return array();
	}

	public function getMultostoresForProduct($product_id, $text = true){

		$this->load->language('extension/module/multistore');

		// Получаем все магазины, склады, поставщики
		$multistores = $this->getMultistores();

		// Получаем информацию о складах для конкретного товара
		$product_to_multistores = $this->getProductMultistores($product_id);

		// Объединяем картину происходящего
		foreach($multistores as $key => $multistore){
			$multistore_id = $multistore['multistore_id'];
			if ($multistore['infinity']) {
				$multistores[$key]['quantity'] = 99999;
			} elseif (!empty($product_to_multistores[$multistore_id]) && $product_to_multistores[$multistore_id] !== 0) {
				$multistores[$key]['quantity'] = $product_to_multistores[$multistore_id];
			} elseif ($this->config->get('multistore_empty')) {
				$multistores[$key]['quantity'] = 0;
			} else {
				unset($multistores[$key]);
			}
		}

		// Заменяем значения на текстовые описания
		if ( $this->config->get('multistore_template') === 'text' && $text === true){
			$multistores = $this->getStockText($multistores);
		}

		return $multistores;

	}

	public function getStockText($multistores){

		$stock_all = 0;
		$trade_all = 0;

		// Объединяем склады и поставщиков, приниминяем условную бесконечность
		foreach($multistores as $multistore){
			if ($multistore['type'] == 'stock') {

				if ($multistore['infinity']) {
					$stock_all = 99999;
				}
					
				$stock_all += $multistore['quantity'];

			} elseif ($multistore['type'] == 'trade') {

				if ($multistore['infinity']) {
					$trade_all = 99999;
				}			

				$trade_all += $multistore['quantity'];
			}	
		}

		// Формируем текстовое описание
		foreach($multistores as $key => $multistore){
			
			if ($multistore['type'] == 'store') {

				$stock_text = '';
				
				// Товар есть в магазине
				if ($multistore['quantity'] > 0) {
					$stock_text = $this->language->get('entry_instore');

				// Товар есть на каком-то из складах	
				} elseif ($stock_all > 0) {
					$stock_text = $this->language->get('entry_instock');

				// Товар есть у какого-либо поставщика
				} elseif ($trade_all > 0) {
					$stock_text = $this->language->get('entry_intrade');

				// Товара нигде нет	
				} else {
					$stock_text = $this->language->get('entry_null');
				}

				$multistores[$key]['quantity'] = $stock_text;
				
			}
		
		}

		return $multistores;

	}
	
	public function getStores() {
		
		$this->load->language('extension/module/multistore');
		
		$quote_data = array();
		
		// Получаем все магазины, склады, поставщики
		$multistores = array();
		foreach($this->getMultistores() as $multistore){
			$multistore_id = $multistore['multistore_id'];
			$multistores[$multistore_id] = $multistore;
			$multistores[$multistore_id]['in_stores'] = true; // типа все есть
		}
		
		$in_stock_all = 0;
		$in_trade_all = 0;

		// Получаем содержимое корзины
		$products = $this->cart->getProducts();

		// Получаем обобщенный массив товар - склады
		$multistores_product = array();
		
		foreach($products as $product){
			$product_id = $product['product_id'];
			foreach($this->getMultostoresForProduct($product_id, false) as $multistore){
				$multistore_id = $multistore['multistore_id'];
				$multistores_product[$product_id][$multistore_id] = (int)$multistore['quantity'];
				
				if ($multistore['quantity'] <= 0) {
					$multistores[$multistore_id]['in_stores'] = false;
				}
				
				if ($multistore['type'] == 'stock') {
					$in_stock_all += (int)$multistore['quantity'];
				}
				
				if ($multistore['type'] == 'trade') {
					$in_trade_all += (int)$multistore['quantity'];
				}
			}
		}
		
		// Формируем способы доставки
		foreach($multistores as $key => $multistore){  
			
			if ($multistore['type'] == 'store') {
				
				$in = true;
				
				$multistore_id = $multistore['multistore_id'];
				
				if ($multistore['in_stores']) {
					$in_text = $this->language->get('entry_instore');
				} elseif ( $in_stock_all > 0 ) {
					$in_text = $this->language->get('entry_instock');
				} elseif ( $in_trade_all > 0 ) {
					$in_text = $this->language->get('entry_intrade');
				} else {
					$in = false;
					$in_text = $this->language->get('entry_null');
				}
				
				if ($in) {
					$multistores[$key]['shipping'] = $multistore['name']." (".$in_text.")";
				}
				
			}
		
		}
		
		return $multistores;
		
	}

	private function isZone($zone_id, $geo_zone_id){
		
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone_to_geo_zone` WHERE geo_zone_id = ".$geo_zone_id." AND zone_id = ".$zone_id);
		
		if ($query->num_rows == 1) {
			return true;
		}
		
		return false;
		
	}


	
}