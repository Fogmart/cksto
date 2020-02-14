<?php
class ControllerExtensionModuleSodealscategory extends Controller {
	public function index($setting) {
		$this->load->language('extension/module/so_deals_category');
		$data['heading_title'] = $this->language->get('heading_title');
		$this->load->model('design/banner');
		$this->load->model('tool/image');
		$this->load->model('catalog/category');
		$this->load->model('catalog/product');
		$this->load->model('extension/module/so_deals_category');
		$this->document->addStyle('catalog/view/javascript/so_deals_category/css/so-deals-category.css');
		$this->document->addStyle('catalog/view/javascript/so_deals_category/css/animate.css');
		if (!defined ('OWL_CAROUSEL')){
			$this->document->addStyle('catalog/view/javascript/so_deals_category/css/owl.carousel.css');
			$this->document->addScript('catalog/view/javascript/so_deals_category/js/owl.carousel.js');
			define( 'OWL_CAROUSEL', 1 );
		}
		if (!isset($setting['limit'])) {
			$setting['limit'] = 3;
		}
		if (!isset($setting['start'])) {
			$setting['start'] = 0;
		}
		$this->load->model('localisation/language');
		$data['languages'] = $this->model_localisation_language->getLanguages();
		
		$default = array(
			'objlang'				=> $this->language,
			'name'					=> '',
			'head_name' 			=> '',
			'action'				=> '',
			'module_description'	=> array(),
			'disp_title_module'		=> '1',
			'status'				=> '1',
			'class_suffix'			=> '',
			'type_layout'			=> '',
			'item_link_target'		=> '_blank',
			'nb_column0'			=> '4',
			'nb_column1'			=> '4',
			'nb_column2'			=> '3',
			'nb_column3'			=> '2',
			'nb_column4'			=> '1',
			'nb_rows'				=> '1',
			'image_bg_display'		=> '0',
			'image'					=> '',

			'categorys'				=> array(),
			'category'				=> array(),
			'child_category'		=> '1',
			'category_depth'		=> '1',
			'product_sort'			=> 'p.price',
			'product_ordering'		=> 'ASC',
			'source_limit'			=> '4',
			
			'catid_preload'			=> '*',
			'field_product_tab'		=> '',
			'field_preload'			=> '',
			'tab_max_characters'	=> '25',
			'tab_icon_display'		=> '1',
			'cat_order_by'			=> 'name',
			'imgcfgcat_width'		=> '30',
			'imgcfgcat_height'		=> '30',

			'display_title'			=> '1',
			'title_maxlength'		=> '50',
			'display_description'	=> '1',
			'description_maxlength' => '100',
			'product_image_num' 	=> '1',
			'display_price'			=> '1',
			'display_rating'		=> '1',
			'display_countdown' 	=> '1',

			'product_image'			=> '1',
			'product_get_image_data'=> '1',
			'product_get_image_image'=> '1',
			'width'					=> '150',
			'height'				=> '200',
			'product_placeholder_path'=> 'nophoto.png',
			'display_sale'			=> '1',
			'display_new'			=> '1',
			'date_day'				=> '7',

			'autoplay'				=> '0',
			'autoplayTimeout'		=> '5000',
			'pausehover'			=> '0',
			'autoplaySpeed'			=> '1000',
			'mousedrag'				=> '1',
			'touchdrag'				=> '1',
			'display_loop'			=> '1',
			'loop'					=> '1',
			'display_nav'			=> '1',
			'navs'					=> '1',
			
			'post_text'				=> '',
			'pre_text'				=> '',
			'use_cache'				=> '0',
			'cache_time'			=> '3600',
			'direction'				=> ($this->language->get('direction') == 'rtl' ? 'true' : 'false'),
			'direction_class'		=> ($this->language->get('direction') == 'rtl' ? 'so-deals-category-rtl' : 'so-deals-category-ltr')
		);
		$data =  array_merge($default,$setting);//check data empty setting
		$data['start'] 				= $setting['start'];
		
		// Source Option
		$_catids__ = (array)self::processCategory($setting['category']);
		$category_id_list = array();
		
		if (!empty($_catids__))	{
			$category_id_list = self::getCategoryson($_catids__,$setting);
		}
		$data['setting'] = serialize($setting);
		$data['category_id_list'] 		= implode(',',$category_id_list);
		$data['moduleid']  				= $setting['moduleid'];
		$data['tag_id'] 				= 'so_deals_category_'.$data['moduleid'];
		
		//Default
		if (isset($setting['module_description'][$this->config->get('config_language_id')])) {
			$data['head_name'] = html_entity_decode($setting['module_description'][$this->config->get('config_language_id')]['head_name'], ENT_QUOTES, 'UTF-8');
		}else{
			$data['head_name']  = $setting['head_name'];
		}
		$data['autoplay'] 				= (int)$setting['autoplay'] > 0 ? 'true' : 'false' ;
		$data['display_nav'] 			= (int)$setting['display_nav'] > 0 ? 'true' : 'false' ;
		$data['display_loop'] 			= (int)$setting['display_loop'] > 0 ? 'true' : 'false' ;
		$data['touchdrag'] 				= (int)$setting['touchdrag'] > 0 ? 'true' : 'false' ;
		$data['mousedrag'] 				= (int)$setting['mousedrag'] > 0 ? 'true' : 'false' ;
		$data['pausehover'] 			= (int)$setting['pausehover'] > 0 ? 'true' : 'false' ;
		$data['class_ltabs'] 			= 'ltabs00-' . $setting['nb_column0'] . ' ltabs01-' . $setting['nb_column1'] . ' ltabs02-' . $setting['nb_column2'] . ' ltabs03-' . $setting['nb_column3'] .' ltabs04-' . $setting['nb_column4'] ;
		
		$is_ajax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
		if($is_ajax && isset($_POST['is_ajax_deals_category']) && $_POST['is_ajax_deals_category']){
			$objlang					= $this->language;
			$setting 					= unserialize($_POST['setting']);
			$setting['start'] 			= $_POST['ajax_deals_cat_start'];
			$start						= $setting['start'];
			$product_image 				= $setting['product_image'];
			$image_bg_display 			= (isset($setting['image_bg_display']) ? $setting['image_bg_display'] : 0);
			$display_title				= $setting['display_title'];
			$display_description		= $setting['display_description'];
			$product_image_num			= (int)$setting['product_image_num'];
			$display_price 				= (int)$setting['display_price'] ;
			$display_rating 			= (int)$setting['display_rating'] ;
			$display_sale 				= $setting['display_sale'];
			$display_new 				= $setting['display_new'];
			$display_countdown 			= (int)$setting['display_countdown'] ;
			$item_link_target 			= $setting['item_link_target'];
			$tag_id						= 'so_deals_category_'.$_POST['lbmoduleid'];

			$autoplay 					= (int)$setting['autoplay'] > 0 ? 'true' : 'false' ;
			$display_nav 				= (int)$setting['display_nav'] > 0 ? 'true' : 'false' ;
			$display_loop 				= (int)$setting['display_loop'] > 0 ? 'true' : 'false' ;
			$touchdrag 					= (int)$setting['touchdrag'] > 0 ? 'true' : 'false' ;
			$mousedrag 					= (int)$setting['mousedrag'] > 0 ? 'true' : 'false' ;
			$pausehover 				= (int)$setting['pausehover'] > 0 ? 'true' : 'false' ;
			$autoplayTimeout 			= (int)$setting['autoplayTimeout'] ;
			$autoplaySpeed 				= (int)$setting['autoplaySpeed'] ;
			$direction 					=  $this->language->get('direction') == 'rtl' ?  'true' : 'false';
			$categoryid 	= $_POST['categoryid'];
			$category_id 	= self::getCategoryson($categoryid ,$setting);
			$child_items 	= self::getProducts( $category_id,$setting);

			$rl_loaded = $start;
			$tab_id = $_POST['categoryid'];
			$result = new stdClass();
			ob_start();
			if(version_compare(VERSION, '2.1.0.2', '>')){
				if (file_exists(DIR_TEMPLATE . $this->config->get('theme_default_directory') . '/template/extension/module/so_deals_category/default.tpl')) {
					include(DIR_TEMPLATE .$this->config->get('theme_default_directory') . '/template/extension/module/so_deals_category/default_items.tpl');
				} else {
					include(DIR_TEMPLATE .'default/template/extension/module/so_deals_category/default_items.tpl');
				}
			}else{
				if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/extension/module/so_deals_category/default.tpl')) {
					include(DIR_TEMPLATE .$this->config->get('config_template') . '/template/extension/module/so_deals_category/default_items.tpl');
				} else {
					include(DIR_TEMPLATE .'default/template/extension/module/so_deals_category/default_items.tpl');
				}
			}
				
			
			$buffer = ob_get_contents();
			$result->items_markup = preg_replace(
				array(
					'/ {2,}/',
					'/<!--.*?-->|\t|(?:\r?\n[ \t]*)+/s'
				),
				array(
					' ',
					''
				),
				$buffer
			);
			ob_end_clean();
			die (json_encode($result));
		}else
		{
			$data['list'] = self::getListCategoriesFilter($setting);
		}

		// caching
		$use_cache = (int)$setting['use_cache'];
		$cache_time = (int)$setting['cache_time'];
		$folder_cache = DIR_CACHE.'so/DealsCategory/';
		if(!file_exists($folder_cache))
			mkdir ($folder_cache, 0777, true);
		if (!class_exists('Cache_Lite'))
			require_once (DIR_SYSTEM . 'library/so/deals_category/Cache_Lite/Lite.php');

		$options = array(
			'cacheDir' => $folder_cache,
			'lifeTime' => $cache_time
		);
		$Cache_Lite = new Cache_Lite($options);
		
		//=== Theme Custom Code====
		if ($use_cache){
			$this->hash = md5( serialize($setting));
			$_data = $Cache_Lite->get($this->hash);
			if (!$_data) {
				$_data = $this->getLayoutMod('so_deals_category',$data,$data['type_layout']);
				$Cache_Lite->save($_data);
				return  $_data;
			} else {
				return  $_data;
			}
		}else{
			if(file_exists($folder_cache))$Cache_Lite->_cleanDir($folder_cache);
			$_data = $this->getLayoutMod('so_deals_category',$data,$data['type_layout']);
			return  $_data;
		}
		
	}

	//=== Theme Custom Code====
	
	public function getLayoutMod($name=null,$data,$type_layout){
		$log_directory  = DIR_TEMPLATE.$this->config->get('theme_default_directory').'/template/extension/module/'.$name;
		if (is_dir($log_directory)) {
			$files = scandir($log_directory);
			foreach ($files as  $value) {
				if (strpos($value, '.tpl') == true && strpos($value,'_') == false) {
					$fileNames[] = $value;
				}
			}
		} 
		$fileNames = isset($fileNames) ? $fileNames : '';
		foreach($fileNames as $option_id => $option_value){
			if($option_id == $type_layout){
				$type_morelayout = $this->load->view('extension/module/'.$name.'/'.$option_value, $data);
			}
		}
		return $type_morelayout;
	}
	
	public function getListCategoriesFilter($setting)
	{
		$catids = $setting['category'];
		settype($catids, 'array');
		$cat_order_by = $setting['cat_order_by'];
		
		$list = array();
		$cats = array();
		
		if (empty($catids)) return;
		$_catids = (array)self::processCategory($catids);
		if (empty($_catids)) return;
		foreach ($_catids as $cid) {
			$category = $this->model_catalog_category->getCategory($cid);
			$cats[] = $category;
			switch ($cat_order_by) {
				default:
				case 'name':
					usort($cats, create_function('$a, $b', 'return strnatcasecmp( $a["name"], $b["name"]);'));
					break;
				case 'lft':
					usort($cats, create_function('$a, $b', 'return $a->lft < $b->lft;'));
					break;
				case 'random':
					shuffle($cats);
					break;
			}
		}
		$catidpreload = $setting['catid_preload'];
		$selected = false;

		$_cats = array();
		foreach ($cats as $cat) {

			$category_id_list = self::getCategoryson($cat['category_id'],$setting);
			$filter_data = array(
				'filter_category_id'  	=> implode(',',$category_id_list),
				'sort'         			=> $setting['product_sort'],
				'order'        			=> $setting['product_ordering'],
				'limit'        			=> $setting['source_limit'],
				'start' 	   			=> $setting['start']
			);
			$cat['category_id_list'] = $category_id_list;
			$cat['count'] = $this->model_extension_module_so_deals_category->getTotalProducts_deals($filter_data);
			if($cat['count'] > 0){
				$_cats[] = $cat;
			}
			
		}
		
		if (empty($_cats))
			return;
		foreach($_cats as $cat) {	
			if(isset($cat['sel'])){
				unset($cat['sel']);
			}
			if ($cat['count'] > 0) {
				if ($cat['category_id'] == $catidpreload) {
					$cat['sel'] = 'sel';
					$cat['child'] = self::getProducts($cat['category_id_list'], $setting);
					$selected = true;
				}
				if($cat['image'] != null)
				{
					$cat['icon_image'] =$this->model_tool_image->resize($cat['image'], $setting['imgcfgcat_width'], $setting['imgcfgcat_height']);
				}else{
					$cat['icon_image'] =$this->model_tool_image->resize('placeholder.png', $setting['imgcfgcat_width'], $setting['imgcfgcat_height']);
				}
				$list[$cat['category_id']] = $cat;
			}
		}
		
		if (!$selected) {
			foreach ($_cats as $cat) {
				if ($cat['count'] > 0) {
					$cat['sel'] = 'sel';
					$cat['child'] = self::getProducts($cat['category_id_list'], $setting);
					if($cat['image'] != null)
					{
						$cat['icon_image'] =$this->model_tool_image->resize($cat['image'], $setting['imgcfgcat_width'], $setting['imgcfgcat_height']);
					}else{
						$cat['icon_image'] =$this->model_tool_image->resize('placeholder.png', $setting['imgcfgcat_width'], $setting['imgcfgcat_height']);
					}
					$list[$cat['category_id']] = $cat;
					break;
				}
			}
		}
		
		return $list;
	}
	public function getCategoryson($category_id, $setting)
	{
		$category_arr = array();
		if(!is_array($category_id))
		{
			$category_id = array($category_id);
		}
		$category_arr = $category_id; 
		if($setting['child_category'] ==1)
		{
			$category_arr =$category_id;
			for($i=1; $i<= (int)$setting['category_depth'];$i++)
			{
				$filter_data = array(
					'category_id'  => implode(',',$category_arr),
					'category_depth' => $setting['category_depth']
				);
				$categoryss = $this->model_extension_module_so_deals_category->getCategories_son_listing_tabs($filter_data);
				foreach ($categoryss as $category)
				{
					if(!in_array($category['category_id'],$category_arr))
					{
						$category_arr[] = $category['category_id'];
					}
				}
			}
		}
		
		return $category_arr;
	}
	public function getProducts($category_id_list,$setting)
	{
		$list = array();
		if(is_array($category_id_list))
		{
			$filter_data = array(
				'filter_category_id'  => implode(',',$category_id_list),
				'sort'         => $setting['product_sort'],
				'order'        => $setting['product_ordering'],
				'limit'        => (int)$setting['source_limit'],
				'start' 	   => $setting['start']
			);
		}else{
			$filter_data = array(
				'filter_category_id'  => $category_id_list,
				'sort'         => $setting['product_sort'],
				'order'        => $setting['product_ordering'],
				'limit'        => (int)$setting['source_limit'],
				'start' 	   => $setting['start']
			);
		}

		$cat['count'] = $this->model_extension_module_so_deals_category->getTotalProducts_deals($filter_data);
		if ($cat['count'] > 0){
			$products_arr = $this->model_extension_module_so_deals_category->getProducts_deals($filter_data);
			foreach($products_arr as $product_info){
				$product_image = $this->model_catalog_product->getProductImages($product_info['product_id']);
				$setting['width'] = ($setting['width'] == 0 ? "30px" : $setting['width']);
				$setting['height'] = ($setting['height'] == 0 ? "30px" : $setting['height']);
				$product_image_first = array_shift($product_image);
				$image2 = $this->model_tool_image->resize('placeholder.png', $setting['width'], $setting['height']);
				if($product_image_first != null)
				{
					$image2 = $this->model_tool_image->resize($product_image_first['image'], $setting['width'], $setting['height']);
				}
				if ($product_info['image'] && $setting['product_get_image_data']) {
					$image = $this->model_tool_image->resize($product_info['image'], $setting['width'], $setting['height']);
				}elseif(isset($product_image_first['image']) && $setting['product_get_image_image']){
					$image = $this->model_tool_image->resize($product_image_first['image'], $setting['width'], $setting['height']);
				} else {
					$url = file_exists("image/so_deals_category/images/".$setting['product_placeholder_path']);
				if ($url) {
					$image_name = "so_deals_category/images/".$setting['product_placeholder_path'];
				} else {
					$image_name = "no_image.png";
				}
				$image = $this->model_tool_image->resize($image_name, $setting['width'], $setting['height']);
				}
				
				if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
					$price = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
				} else {
					$price = false;
				}

				if ((float)$product_info['special']) {
					$special = $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
				} else {
					$special = false;
				}

				if ($this->config->get('config_tax')) {
					$tax = $this->currency->format((float)$product_info['special'] ? $product_info['special'] : $product_info['price'], $this->session->data['currency']);
				} else {
					$tax = false;
				}

				if ($this->config->get('config_review_status')) {
					$rating = $product_info['rating'];
				} else {
					$rating = false;
				}
				$specialPriceToDate = '';
				if (strtotime ($product_info['date_start']) != false && strtotime ($product_info['date_end']) != false)
				{
					$current = date ('Y/m/d H:i:s');
					$start_date = date ('Y/m/d H:i:s', strtotime ($product_info['date_start']));
					$date_end = date ('Y/m/d H:i:s', strtotime ($product_info['date_end']));
					if (strtotime ($date_end) >= strtotime ($current) && strtotime ($start_date) <= strtotime ($date_end))
						$specialPriceToDate = $date_end;
				}

				
				$name = (($setting['title_maxlength'] != 0 && strlen($product_info['name']) > $setting['title_maxlength']) ? utf8_substr(strip_tags(html_entity_decode($product_info['name'], ENT_QUOTES, 'UTF-8')), 0, $setting['title_maxlength']) . '..' : $product_info['name']);
				$description = (($setting['description_maxlength'] != 0 && strlen($product_info['description']) > $setting['description_maxlength']) ? utf8_substr(strip_tags(html_entity_decode($product_info['description'], ENT_QUOTES, 'UTF-8')), 0, $setting['description_maxlength']) . '..' : $product_info['description']);
				
				$datetimeNow = new DateTime();
				$datetimeCreate = new DateTime($product_info['date_available']);
				$interval = $datetimeNow->diff($datetimeCreate);
				$dateDay = $interval->format('%a');
				$productNew = ($dateDay <= $setting['date_day'] ? 1 : 0);
				
				if ((float)$product_info['special']) {
					$product_discount = '-'.round((($product_info['price'] - $product_info['special'])/$product_info['price'])*100, 0).'%';
				} else {
					$product_discount = false;
				}
				
				$cat['child'][] = array(
					'product_id'  	=> $product_info['product_id'],
					'thumb'       	=> $image,
					'thumb2'		=> $image2,
					'name'        	=> $product_info['name'],
					'name_maxlength'=> mb_substr($name, 0, 25),
					'description' 	=> $product_info['description'],
					'description_maxlength' => $description,
					'price'       	=> $price,
					'special'     	=> $special,
					'productNew'	=> $productNew,
					'discount'		=> $product_discount,
					'tax'         	=> $tax,
					'rating'      	=> $rating,
					'date_added'  	=> $product_info['date_added'],
					'model'  	  	=> $product_info['model'],
					'quantity'    	=> $product_info['quantity'],
					'href'        	=> $this->url->link('product/product', 'product_id=' . $product_info['product_id']),
					'specialPriceToDate' => $specialPriceToDate,
				);
			}
			$list = $cat['child'];
		}
		return $list;
	}
	private  function getLabel($filter){
		switch ($filter) {
			case 'p_price' 			: return $this->language->get('value_price');
			case 'pd_name' 			: return $this->language->get('value_name');
			case 'p_model' 			: return $this->language->get('value_model');
			case 'p_quantity' 		: return $this->language->get('value_quantity');
			case 'rating' 			: return $this->language->get('value_rating');
			case 'p_sort_order' 	: return $this->language->get('value_sort_add');
			case 'p_date_added' 	: return $this->language->get('value_date_add');
			case 'sell' 			: return $this->language->get('value_sell');
		}
	}
	
	private function processCategory($catids)
	{
		$catpubid = array();
		if (empty($catids)) return;
		foreach ($catids as $i => $cid) {
			$category = $this->model_catalog_category->getCategory($cid);
		
			$cats[$i] = $category;
			if (empty($category)) {
				unset($cats[$i]);
			} else {
				$catpubid[] = $category['category_id'];
			}
		}
		return $catpubid;
	}
}