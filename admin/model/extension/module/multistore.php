<?php
class ModelExtensionModuleMultistore extends Model {
	
	public function getMultistores() {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "multistore`");

		if ($query->num_rows == 0) {
			return array();
		}

		$sort_order = array();
		$results = $query->rows;

		foreach ($results as $key => $row) {
			$sort_order[$key] = $row['sort'];
		}

		array_multisort($sort_order, SORT_ASC, $results);

		return $results;
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
	
	public function setMultistores($multistores = array()) {

		$this->db->query("DELETE FROM `" . DB_PREFIX . "multistore`");
		
		if (empty($multistores)) {
			return false;
		}
		 
		foreach($multistores as $multistore){ 

			$infinity = isset($multistore['infinity']) ? 1 : 0;

			$this->db->query("INSERT INTO `" . DB_PREFIX . "multistore` SET 
				multistore_id = '" . (int)$multistore['multistore_id'] . "', 
				name 					= '" . $this->db->escape($multistore['name']) . "',
				description 	= '" . $this->db->escape($multistore['description']) . "',
				type 					= '" . $this->db->escape($multistore['type']) . "',
				geo_zone_id 	= '" . (int)$multistore['geo_zone_id'] . "',
				infinity 			= '" . $infinity . "',
				sort 					= '" . (int)$multistore['sort'] . "'
			");
		}
	}
	
	public function setProductMultistores($product_id, $data) {
		
		$multistores = $data['multistores'];
		$product_quantity = 0;
		
		$this->db->query("DELETE FROM `" . DB_PREFIX . "product_to_multistore` WHERE `product_id` = ".(int)$product_id);
		foreach($multistores as $multistore_id => $quantity){ 
			$product_quantity += $quantity;
			$this->db->query("INSERT INTO `" . DB_PREFIX . "product_to_multistore` SET 
				product_id			= '" . (int)$product_id . "', 
				multistore_id 	= '" . (int)$multistore_id . "',
				quantity 				= '" . (int)$quantity . "'
			");
		}

		$this->setProductQuantity($product_id, $product_quantity);
		
	}

	public function setProductQuantity($product_id, $quantity){
		$this->db->query("UPDATE " . DB_PREFIX . "product 
			SET 
				quantity = '" . (int)$quantity . "' 
			WHERE 
				product_id = '" . (int)$product_id . "'
		");
	}

	public function getStockId() {
		$multistores = $this->db->query("SELECT * FROM " . DB_PREFIX . "multistore WHERE `type` = 'stock'");
		return $multistores->row['multistore_id'];
	}

	public function readFile($route){

		include_once(DIR_SYSTEM.'library/PHPExcel.php');

		$products = array();

		$multistores = $this->getMultistores();

		// Подгружаем саму библиотеку
		$excel = PHPExcel_IOFactory::load(DIR_UPLOAD.$route);

		// Читаем файл
		foreach($excel->getWorksheetIterator() as $worksheet) {
			$lists[] = $worksheet->toArray();
		}

		// Разбираем файл
		foreach($lists as $key_list => $list){
			foreach($list as $key_row => $row){

				// Пропускаем первую строку
				if ($key_row == 0){
					continue;
				}

				// Если первые две ячейки не заполнены - заканчиваем
				if (empty($row[0]) && empty($row[1])) {
					break;
				}

				$product = array();

				foreach($row as $key_col => $col){

					if ($key_col == 0) {
						$product['name'] = htmlentities($col);
					}

					if ($key_col == 1) {
						$product['model'] = htmlentities($col);
					}

					if ($key_col >= 2 && (!empty($col) || $col == 0)) {
						if (!isset($multistores[$key_col - 2])) {
							continue;
						}
						$multistore_id = $multistores[$key_col - 2]['multistore_id'];
						$product['multistores'][$multistore_id] = (int)$col;
					}
					
				}

				$products[$key_row] = $product;
			}
		}

		return $products;

	}

	public function createTemplate($multistores){

		include_once(DIR_SYSTEM.'library/PHPExcel.php');

		$this->load->language('extension/module/multistore');

		$alfabet = array(	
			'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 
			'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN', 'AO', 'AP', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AV', 'AW', 'AX', 'AY', 'AZ',
			'BA', 'BB', 'BC', 'BD', 'BE', 'BF', 'BG', 'BH', 'BI', 'BJ', 'BK', 'BL', 'BM', 'BN', 'BO', 'BP', 'BQ', 'BR', 'BS', 'BT', 'BU', 'BV', 'BW', 'BX', 'BY', 'BZ',
			'CA', 'CB', 'CC', 'CD', 'CE', 'CF', 'CG', 'CH', 'CI', 'CJ', 'CK', 'CL', 'CM', 'CN', 'CO', 'CP', 'CQ', 'CR', 'CS', 'CT', 'CU', 'CV', 'CW', 'CX', 'CY', 'CZ'
		);

		$obj = new PHPExcel();

		// Шапка
		$obj->getActiveSheet()->setCellValue('A1', $this->language->get('file_column_name'));
		$obj->getActiveSheet()->setCellValue('B1', $this->language->get('file_column_model'));

		$column_id = 2;
		foreach( $multistores as $multistore ){
			$obj->getActiveSheet()->setCellValue($alfabet[$column_id].'1', $multistore['name']);
			$column_id++;
		}

		// Товар
		$obj->getActiveSheet()->setCellValue('A2', $this->language->get('file_example_name'));
		$obj->getActiveSheet()->setCellValue('B2', $this->language->get('file_example_model'));

		$column_id = 2;
		foreach( $multistores as $multistore ){
			$obj->getActiveSheet()->setCellValue($alfabet[$column_id].'2', rand(0, 20));
			$column_id++;
		}

		// создаем файл с данными
		$file =  PHPExcel_IOFactory::createWriter($obj, 'Excel2007');

		$filename = 'catalog/multistore/multistore_example'.date("d-m-Y_H-i-s").'.xlsx';

		// Сохраняем файл
		$file->save(DIR_IMAGE.$filename);

		// Возвращаем сссылку
		return HTTP_CATALOG.'image/'.$filename;
		
	}

	public function install() {
		$this->db->query("
			DROP TABLE IF EXISTS `".DB_PREFIX."product_to_multistore`;
		");	
		$this->db->query("
			CREATE TABLE `".DB_PREFIX."product_to_multistore` (
			  `product_id` int(11) NOT NULL,
			  `multistore_id` int(11) NOT NULL,
			  `quantity` int(11) NOT NULL
			) ENGINE=InnoDB DEFAULT CHARSET=utf8;
		");	
		$this->db->query("
			DROP TABLE IF EXISTS `".DB_PREFIX."multistore`;
		");		
		$this->db->query("
			CREATE TABLE `".DB_PREFIX."multistore` (
			  `multistore_id` int(11) NOT NULL,
			  `name` text NOT NULL,
			  `description` text NOT NULL,
			  `type` varchar(10) NOT NULL,
			  `geo_zone_id` int(11) NOT NULL,
			  `infinity` int(1) NOT NULL,
			  `sort` int(11) NOT NULL
			) ENGINE=InnoDB DEFAULT CHARSET=utf8;
		");
	}

	public function uninstall() {
		$this->db->query("
			DROP TABLE IF EXISTS `" . DB_PREFIX . "product_to_multistore`;
		");		
		$this->db->query("	
			DROP TABLE IF EXISTS `" . DB_PREFIX . "multistore`;
		");
	}
	
}