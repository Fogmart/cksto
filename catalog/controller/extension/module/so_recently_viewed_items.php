<?php
class ControllerExtensionModuleSorecentlyvieweditems extends Controller {
	public function index($setting) {
		$this->load->language('extension/module/so_recently_viewed_items');
		$data['heading_title'] = $setting['name'];
	
		$this->load->model('design/banner');
		$this->load->model('tool/image');
		$this->load->model('catalog/category');
		$this->load->model('catalog/product');
		$this->document->addStyle('catalog/view/javascript/so_recently_viewed_items/css/style.css');
		if (!defined ('OWL_CAROUSEL') && $setting['type_module'] == "slider"){
			$this->document->addScript('catalog/view/javascript/so_recently_viewed_items/js/owl.carousel.js');
			$this->document->addStyle('catalog/view/javascript/so_recently_viewed_items/css/owl.carousel.css');
			$this->document->addStyle('catalog/view/javascript/so_recently_viewed_items/css/animate.css');
			define( 'OWL_CAROUSEL', 1 );
		}
		if (!isset($setting['start'])) {
			$setting['start'] = 0;
		}
		$default = array(
			'objlang'				=> $this->language,
			'name' 					=> '',
			'module_description'	=> array(),
			'disp_title_module'		=> '1',
			'status'				=> '1',
			'class_suffix'			=> '',
			'item_link_target'		=> '_blank',
			'time_cookie'			=> '1',
			'source_limit'			=> '4',
			'position_mod'			=> 'left',

			'display_title'			=> '1',
			'title_maxlength'		=> '15',
			'display_description'	=> '0',
			'description_maxlength' => '100',
			'product_image' 		=> '1',
			'product_image_num' 	=> '1',
			'width' 				=> '200',
			'height' 				=> '200',
			
			'display_rating'		=> '1',
			'display_price'			=> '1',
			'display_sale'			=> '1',
			'display_new'			=> '1',
			'date_day'				=> '7',
			
			'type_module'			=> 'list',
			'style_top'				=> '45px',
			'nb_row'				=> '1',
			'autoplay'				=> '0',
			'autoplay_timeout'		=> '5000',
			'pausehover'			=> '0',
			'autoplaySpeed'			=> '1000',
			'startPosition'			=> '0',
			'mouseDrag'				=> '1',
			'touchDrag'				=> '1',
			'dots'					=> '1',
			'navs'					=> '1',
			'navSpeed'				=> '500',
			'effect'				=> 'starwars',
			'duration'				=> '800',
			'delay'					=> '500',
			
			'post_text'				=> '',
			'pre_text'				=> '',
			'use_cache'				=> '0',
			'cache_time'			=> '3600',
			
			'direction'				=> ($this->language->get('direction') == 'rtl' ? 'true' : 'false'),
			'direction_class'		=> ($this->language->get('direction') == 'rtl' ? 'so-recently-viewed-items-rtl' : 'so-recently-viewed-items-ltr')
		);
		$data =  array_merge($default,$setting);//check data empty setting
		$this->load->model('localisation/language');
		$data['languages'] 			= $this->model_localisation_language->getLanguages();
		
		if (isset($setting['module_description'][$this->config->get('config_language_id')])) {
			$data['head_name'] = html_entity_decode($setting['module_description'][$this->config->get('config_language_id')]['head_name'], ENT_QUOTES, 'UTF-8');
		}else{
			$data['head_name']  = $setting['head_name'];
		}
		// Product option
		$data['nb_rows'] 				= $setting['nb_row'];
		if ($data['autoplay'] == 1) {
			$data['autoplay_timeout'] 	= $setting['autoplay_timeout'];
		}else{
			$data['autoplay_timeout'] 	= 0;
		}
		$data['dots'] 			= ($setting['dots'] == 1 ? "true" : "false");
		$data['nav'] 			= ($setting['navs'] == 1 ? "true" : "false");
	
		$recently_viewed_products = array();

        if (isset($this->request->cookie['recently_viewed'])) {
            $recently_viewed_products = explode(',', $this->request->cookie['recently_viewed']);
        } else if (isset($this->session->data['recently_viewed'])) {
            $recently_viewed_products = $this->session->data['recently_viewed'];
        }

        if (isset($this->request->get['route']) && $this->request->get['route'] == 'product/product') {

            $product_id = $this->request->get['product_id'];   

            $recently_viewed_products = array_diff($recently_viewed_products, array($product_id));

            array_unshift($recently_viewed_products, $product_id);

            setcookie('recently_viewed', implode(',',$recently_viewed_products), time() + 60 * 60 * 24 * $data['time_cookie'], '/', $this->request->server['HTTP_HOST']);

            if (!isset($this->session->data['recently_viewed']) || $this->session->data['recently_viewed'] != $recently_viewed_products) {
                $this->session->data['recently_viewed'] = $recently_viewed_products;
            }
			$products = array_slice($recently_viewed_products,1,$setting['source_limit']); /* delete id first*/
        }else{
			$products = array_slice($recently_viewed_products,0,$setting['source_limit']); /* start id first */
		} 
		
        $data['products'] = $this->getProducts($products,$setting);

		// caching
		$use_cache = (int)$setting['use_cache'];
		$cache_time = (int)$setting['cache_time'];
		$folder_cache = DIR_CACHE.'so/Recently_viewed_items/';
		if(!file_exists($folder_cache))
			mkdir ($folder_cache, 0777, true);
		if (!class_exists('Cache_Lite'))
			require_once (DIR_SYSTEM . 'library/so/recently_viewed_items/Cache_Lite/Lite.php');

		$options = array(
			'cacheDir' => $folder_cache,
			'lifeTime' => $cache_time
		);
		$Cache_Lite = new Cache_Lite($options);
		if ($use_cache){
			$this->hash = md5( serialize($setting));
			$_data = $Cache_Lite->get($this->hash);
			if (!$_data) {
				// Check Version
				if(version_compare(VERSION, '2.1.0.2', '>')) {
					$_data = $this->load->view('extension/module/so_recently_viewed_items/default.tpl', $data);
				}else{
					$tem_url = $this->config->get('config_template') . '/template/extension/module/so_recently_viewed_items/default.tpl';
					$template_file = DIR_TEMPLATE . $tem_url ? DIR_TEMPLATE . $tem_url : '';
					$_data = '';
					if (file_exists($template_file)){
						$_data = $this->load->view($tem_url, $data);
					}
				}
				$Cache_Lite->save($_data);
				return  $_data;
			} else {
				return  $_data;
			}
		}else{
			if(file_exists($folder_cache))
				$Cache_Lite->_cleanDir($folder_cache);
			// Check Version
			if(version_compare(VERSION, '2.1.0.2', '>')) {
				return $this->load->view('extension/module/so_recently_viewed_items/default.tpl', $data);
			}else{
				$tem_url = $this->config->get('config_template') . '/template/extension/module/so_recently_viewed_items/default.tpl';
				$template_file = DIR_TEMPLATE . $tem_url ? DIR_TEMPLATE . $tem_url : '';
				if (file_exists($template_file)) {
					return $this->load->view($tem_url, $data);
				}
			}
		}
	}
	
	public function getProducts($products,$setting){
		$products_arr = array();
		foreach ($products as $product_id) {
            $product_info = $this->model_catalog_product->getProduct($product_id);

            if ($product_info) {
                $product_image = $this->model_catalog_product->getProductImages($product_info['product_id']);
				$image2 = $this->model_tool_image->resize('placeholder.png', $setting['width'], $setting['height']);
				if(count($product_image) >0)
				{
					$image2 = $this->model_tool_image->resize($product_image[0]['image'], $setting['width'], $setting['height']);
				}
				if ($product_info['image']) {
					$image = $this->model_tool_image->resize($product_info['image'], $setting['width'], $setting['height']);
				}elseif(isset($product_image[0]['image'])){
					$image = $this->model_tool_image->resize($product_image[0]['image'], $setting['width'], $setting['height']);
				} else {
					$image = $this->model_tool_image->resize('placeholder.png', $setting['width'], $setting['height']);
				}

                // Check Version
				if(version_compare(VERSION, '2.1.0.2', '>')) {
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
				} else {
					if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
						$price = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')));
					} else {
						$price = false;
					}

					if ((float)$product_info['special']) {
						$special = $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')));
					} else {
						$special = false;
					}

					if ($this->config->get('config_tax')) {
						$tax = $this->currency->format((float)$product_info['special'] ? $product_info['special'] : $product_info['price']);
					} else {
						$tax = false;
					}

					if ($this->config->get('config_review_status')) {
						$rating = $product_info['rating'];
					} else {
						$rating = false;
					}
				}
				// Name
				$name = (($setting['title_maxlength'] != 0 && strlen($product_info['name']) > $setting['title_maxlength']) ? utf8_substr(strip_tags(html_entity_decode($product_info['name'], ENT_QUOTES, 'UTF-8')), 0, $setting['title_maxlength']) . '..' : $product_info['name']);
			
				$datetimeNow = new DateTime();
				$datetimeCreate = new DateTime($product_info['date_available']);
				$interval = $datetimeNow->diff($datetimeCreate);
				$dateDay = $interval->format('%a');
				$productNew = ($dateDay <= $setting['date_day'] ? 1 : 0);
				
                $products_arr[] = array(
                    'product_id' 	=> $product_info['product_id'],
                    'thumb'      	=> $image,
					'thumb2'       	=> $image2,
					'name'		 	=> $name,
                    'nameFull'      => $product_info['name'],
                    'price'      	=> $price,
                    'special'    	=> $special,
					'productNew'	=> $productNew,	
                    'rating'     	=> $rating,
					'tax'         	=> $tax,
                    'reviews'    	=> sprintf($this->language->get('text_reviews'), (int)$product_info['reviews']),
                    'href'       	=> $this->url->link('product/product', 'product_id=' . $product_info['product_id']),
                );
            }
        }
		return $products_arr;
	}
}