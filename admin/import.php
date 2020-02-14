<?php
//init controllers:
ob_start();
  require_once("index.php");
  ob_get_contents();
ob_end_clean();


if(isset($_GET['import_id'])){
  require_once('model/tool/profi_import.php');
  if (!isset($registry)){
    $registry = new Registry();

    $loader = new Loader($registry);
    $registry->set('load', $loader);

    $config = new Config();
    $registry->set('config', $config);

    $db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE, DB_PORT);
    $registry->set('db', $db);
  }
  $importClass = new ModelToolProfiImport($registry);

  
  
  $import_id = (int)$_GET['import_id'];
  $import_data = $importClass->getImport($import_id);

  if(isset($_GET['part'])){
    $parts = htmlspecialchars($_GET['part']);
  }else{
    $parts = '1_1';
  }
  $parts = explode("_",$parts);
  $actual_part = $parts[0]-1;
  $total_parts = $parts[1];



  $import_tags = $importClass->getImportTags($import_id);
  if(isset($_GET['part']) AND $actual_part == 0){
    $importClass->downloadXML($import_id);
  }


  if(isset($_GET['action'])){
    $action = htmlspecialchars($_GET['action']);
    if($action == "clearInsertedAndUpdated"){
      $stats = $importClass->clearCacheLogStats();
      echo json_encode('done');
      die();
    }
  }
  
  //convert: SHOP;SHOPITEM;CATEGORIES;CATEGORY ... To ... CATEGORIES;CATEGORY
  $clear_import_tags = array();
  $i = 0;
  foreach($import_tags as $tag){
    $tag_name = $tag['tag_name'];
    $tag_name = explode($import_data['product_tag'].";",$tag_name);
    if(!isset($tag_name[1])){echo 'Wrong "Product tag"'; die();}
    $tag_name = $tag_name[1];
    $clear_import_tags[$i]             = $tag;
    $clear_import_tags[$i]['tag_name'] = $tag_name;
    $i++;
  }
  $import_tags = $clear_import_tags;
  
  
  
  
  
  $xml_file = '../system/download/xml/feed_'.$import_id.'.xml';
  if(VERSION >= '2.1.0.1'){
    $xml_file = '../system/storage/download/xml/feed_'.$import_id.'.xml';
  }


  $xml = simplexml_load_file($xml_file);


//NNEEWW
  $product_array = array();
  $total_products = 0;
 
  $clear_product_tag = $import_data['product_tag'];
  if(strpos($import_data['product_tag'],';')){
    $clear_product_tag = explode(";",$import_data['product_tag']);
    $clear_product_tag = str_replace($clear_product_tag[0].";","",$import_data['product_tag']);
  }
  
  
  if(strpos($clear_product_tag,';')){ //SHOPTITEMS > SHOPTITEMS2 > SHOPITEM > ITEM etc :))
    $product_tags = explode(";",$clear_product_tag);
    $i = 0;
    $xml_tmp = $xml;
    if(isset($product_tags[0])){
    if(isset($product_tags[0]) AND !isset($product_tags[1])){
      $total_products = $total_products+count($xml_tmp->{$product_tags[0]});
      $product_array[] = $xml_tmp;
    }
    foreach($xml_tmp->{$product_tags[0]} as $xml_tmp_1){
        if(isset($product_tags[1])){
        if(isset($product_tags[1]) AND !isset($product_tags[2])){
          $total_products = $total_products+count($xml_tmp_1->{$product_tags[1]});
          $product_array[] = $xml_tmp_1;
        }
          foreach($xml_tmp_1->{$product_tags[1]} as $xml_tmp_2){
            if(isset($product_tags[2])){
            if(isset($product_tags[2]) AND !isset($product_tags[3])){
              $total_products = $total_products+count($xml_tmp_2->{$product_tags[2]});
              $product_array[] = $xml_tmp_2;
            }
              foreach($xml_tmp_2->{$product_tags[2]} as $xml_tmp_3){
                if(isset($product_tags[3])){
                if(isset($product_tags[3]) AND !isset($product_tags[4])){
                  $total_products = $total_products+count($xml_tmp_3->{$product_tags[3]});
                  $product_array[] = $xml_tmp_3;
                }
                }
              }
            }
          }
        }
      }
    }


  }else{
    if($xml->{$clear_product_tag}){
      $total_products = count($xml->{$clear_product_tag});
    }
    $product_array[] = $xml->{$clear_product_tag};
  }
 
 
 
 
    
  if(isset($_GET['action']) AND $_GET['action'] == "getTotalProductsInXML"){
    echo json_encode($total_products);
    die();
  }




  $product_per    = round($total_products/$total_parts);
  $product_number = 1;
  
  
  $product_number_start = ($product_per*$actual_part);
  $product_number_stop  = $product_number_start+$product_per;

  
  if(($actual_part+1) == $total_parts){
    $product_number_stop = $total_products;
  }
  

  if(($actual_part+1) == 1){
    $importClass->saveImportStart($import_id);
    $importClass->setAllProductsInActive($import_id); //for disable products what is in eshop BUT not in XML
  }

  foreach($product_array as $products){
  foreach($products as $product){
  
    if($product_number > $product_number_start AND $product_number <= $product_number_stop){
  
  
    $product_data = array();
    
  
  //get product data from XML:
    foreach($import_tags as $tag){
      $sub_tags = explode(";",$tag['tag_name']);
      
      if(count($sub_tags) == 1 AND count($product->{$tag['tag_name']}) == 1){ //one element:
        $product_data[$tag['tag_content']] = $product->{$tag['tag_name']};
      }elseif(count($sub_tags) == 1 AND count($product->{$tag['tag_name']}) > 1){
        foreach($product->{$tag['tag_name']} as $tag_value){
          $product_data[$tag['tag_content']][] = $tag_value;
        }
      }else{
        $tag_content = getSubTagArray($product,$tag['tag_name']);
        if(count($tag_content) == 1){
          $product_data[$tag['tag_content']] = $tag_content[0];
        }else{
          $product_data[$tag['tag_content']] = $tag_content;
        }
      }
      
    }
  
  
  //convert to better product array:
    $clear_product_data = array();
    foreach($product_data as $key => $value){


  
  
  if(strpos($key,"product_category_name[") !== false){
    $i = 0;
    if(!is_array($value)){$value = array($value);}
    if($value){
      foreach($value as $val){
        if($val != false){
          $language = explode("product_category_name[",$key);
          $language = explode("]",$language[1]);
          $language_id = $language[0];
          $clear_product_data['categories'][$i][$language_id] = $val;
        }
        $i++;
      }
    }
  }

  elseif(strpos($key,"product_name[") !== false){
    if(!is_array($value)){$value = array($value);}
    if($value){
      foreach($value as $val){
        if($val != false){
          $language = explode("product_name[",$key);
          $language = explode("]",$language[1]);
          $language_id = $language[0];
          $clear_product_data['product_descriptions'][$language_id]['name'] = $val;
        }
      }
    }
  }
  
  
  elseif(strpos($key,"product_description[") !== false){
    if(!is_array($value)){$value = array($value);}
    if($value){
      foreach($value as $val){
        if($val != false){
          $language = explode("product_description[",$key);
          $language = explode("]",$language[1]);
          $language_id = $language[0];
          $clear_product_data['product_descriptions'][$language_id]['description'] = $val;
        }
      }
    }
  }
  elseif(strpos($key,"product_meta_description[") !== false){
    if(!is_array($value)){$value = array($value);}
    if($value){
      foreach($value as $val){
        if($val != false){
          $language = explode("product_meta_description[",$key);
          $language = explode("]",$language[1]);
          $language_id = $language[0];
          $clear_product_data['product_descriptions'][$language_id]['meta_description'] = $val;
        }
      }
    }
  }
  elseif(strpos($key,"product_meta_keyword[") !== false){
    if(!is_array($value)){$value = array($value);}
    if($value){
      foreach($value as $val){
        if($val != false){
          $language = explode("product_meta_keyword[",$key);
          $language = explode("]",$language[1]);
          $language_id = $language[0];
          $clear_product_data['product_descriptions'][$language_id]['meta_keyword'] = $val;
        }
      }
    }
  }
  elseif(strpos($key,"product_tag[") !== false){
    if(!is_array($value)){$value = array($value);}
    if($value){
      foreach($value as $val){
        if($val != false){
          $language = explode("product_tag[",$key);
          $language = explode("]",$language[1]);
          $language_id = $language[0];
          $clear_product_data['product_descriptions'][$language_id]['tag'] = $val;
        }
      }
    }
  }
  
  
  





  
  
  
  elseif(strpos($key,"product_attribute_group[") !== false){
    $i = 0;
    if(!is_array($value)){$value = array($value);}
    if($value){
      foreach($value as $val){
        if($val != false){
          $language = explode("product_attribute_group[",$key);
          $language = explode("]",$language[1]);
          $language_id = $language[0];
          $clear_product_data['product_attributes'][$i][$language_id]['group'] = $val;
        }
        $i++;
      }
    }
  }
  elseif(strpos($key,"product_attribute_name[") !== false){
    $i = 0;
    if(!is_array($value)){$value = array($value);}
    if($value){
      foreach($value as $val){
        if($val != false){
          $language = explode("product_attribute_name[",$key);
          $language = explode("]",$language[1]);
          $language_id = $language[0];
          $clear_product_data['product_attributes'][$i][$language_id]['name'] = $val;
        }
        $i++;
      }
    }
  }
  elseif(strpos($key,"product_attribute_value[") !== false){
    $i = 0;
    if(!is_array($value)){$value = array($value);}
    if($value){
      foreach($value as $val){
        if($val != false){
          $language = explode("product_attribute_value[",$key);
          $language = explode("]",$language[1]);
          $language_id = $language[0];
          $clear_product_data['product_attributes'][$i][$language_id]['text'] = $val;
          $i++;
        }
      }
    }
  }
  












  
  
  elseif(strpos($key,"product_option_name[") !== false){
    $i = 0;
    if(!is_array($value)){$value = array($value);}
    if($value){
      foreach($value as $val){
        if($val != false){
          $language = explode("product_option_name[",$key);
          $language = explode("]",$language[1]);
          $language_id = $language[0];
          $clear_product_data['product_options'][$i][$language_id]['name'] = $val;
        }
        $i++;
      }
    }
  }
  elseif(strpos($key,"product_option_value[") !== false){
    $i = 0;
    if(!is_array($value)){$value = array($value);}
    if($value){
      foreach($value as $val){
        if($val != false){
          $language = explode("product_option_value[",$key);
          $language = explode("]",$language[1]);
          $language_id = $language[0];
          $clear_product_data['product_options'][$i][$language_id]['value'] = $val;
        }
        $i++;
      }
    }
  }
  elseif($key == "product_option_price"){
      $i = 0;
      $languages = $importClass->getLanguages();
      if(!is_array($value)){$value = array($value);}
      if($value){
        foreach($value as $val){
          foreach($languages as $language){
            $clear_product_data['product_options'][$i][$language['language_id']]['price'] = $val;
          }
          $i++;
        }
      }
  }
  
  elseif($key == "product_option_quantity"){
      $i = 0;
      $languages = $importClass->getLanguages();
      if(!is_array($value)){$value = array($value);}
      if($value){
        foreach($value as $val){
          foreach($languages as $language){
            $clear_product_data['product_options'][$i][$language['language_id']]['quantity'] = (int)$val;
          }
          $i++;
        }
      }
  }
  
  
  
  
  

      
      elseif($key == 'image'){
      foreach ($product->IMAGES as $image) {
          foreach ($image as $img) {
            if ($img != $clear_product_data['main_image']) {
              $clear_product_data['images'][] = (string)$img;
            }
          }
        }
        
        if($value){
          if(is_array($value)){
            foreach($value as $val){
              if($val != false){
                $clear_product_data['images'][] = (string)$val;
              }
            }
          }else{
            $clear_product_data['images'][] = (string)$value;
          }
        }
      }
  
  //attributes
      elseif($key == 'product_attribute_name' || $key == 'product_attribute_value'){
        $j = 0;
        if($key == 'product_attribute_name'){$sub_key = 'name';}
        if($key == 'product_attribute_value'){$sub_key = 'value';}
        if($value){
          foreach($value as $val){
            if($val != false){
              $clear_product_data['attributes'][$j][$sub_key] = $val;
            }
            $j++;
          }
        }
      }
  
  
      else{
        $clear_product_data[$key] = $value;
      }
    }
    $product_data = $clear_product_data;
    
    
    
    
    $image_dir        = '../image/catalog/';
    $import_image_dir = $image_dir."feed_".$import_id."/";
    if(!file_exists($import_image_dir)){
      mkdir($import_image_dir);
    }
    $import_data['image_dir'] = $import_image_dir;
    
  //image main vs additional:
    $split_product_images   = array();
    if(isset($clear_product_data['main_image'])){
      $split_product_images[] = $clear_product_data['main_image'];
    }
    if(isset($clear_product_data['images'])){
      foreach($clear_product_data['images'] as $image){
        $split_product_images[] = $image;
      }
    }
    $clear_product_data['main_image'] = false;
    $clear_product_data['images']     = $split_product_images;

//for custom edit:
  if(file_exists('import_edit.php')){
    include('import_edit.php');
  }

    $importClass->importProduct($product_data,$import_data);



  //debugProduct($product_data);
  }
  $product_number++;
  }
  }


  if(($actual_part+1) == $total_parts){
    $importClass->saveImportEnd($import_id);    //save time end of import
    $importClass->oldProductAction($import_id); //disable or delete product what is in eshop but not in xml
  }

//importing from admin page:
  if(isset($_GET['import_from_admin'])){
    echo 'done';
    die();
  }



}else{
  echo 'Please select import id!';
  die();
}









///////// xml functions:


function debugProduct($product_data){
  foreach($product_data as $key => $value){
    if(is_array($value)){
      foreach($value as $key_1 => $value_1){
        if(!is_array($value_1)){
          echo $key."=".$value_1."<br />";
        }else{
          echo $key.":<br />";
          foreach($value_1 as $key_2 => $value_2){
            if(!is_array($value_2)){
              echo $key_2."=".$value_2."<br />";
            }else{
              echo $key_1.":<br />";
              foreach($value_2 as $key_3 => $value_3){
                 echo $key_3."=".$value_3."<br />";   
              }
            }  
          }
        }
      }
    }else{
      echo $key." = ".$value."<br />";
    }
    echo "---<br />";
  }
  
  echo "<br /><br />__________________________________________________________<br />";
}




function getSubTagArray($product,$tag_key){
  
  $return_array = array();
  $sub_tags     = explode(";",$tag_key);
  

  if(count($sub_tags) == 2){ //ONE;THIS
    $tag_contents = $product->{$sub_tags[0]};
    if($tag_contents){
      foreach($tag_contents as $tag_content){
        if($tag_content->{$sub_tags[1]}){
          foreach($tag_content->{$sub_tags[1]} as $tag_content){
            if((string)$tag_content){
              $return_array[] = (string)$tag_content;
            }else{$return_array[] = false;}
          }
        }else{$return_array[] = false;}
      }
    }else{$return_array[] = false;}
  }
  
  
  
  
  if(count($sub_tags) == 3){ //ONE;TWO;THIS
    $tag_contents = $product->{$sub_tags[0]};
    if($tag_contents){
      foreach($tag_contents as $tag_content){
        if($tag_content->{$sub_tags[1]}){
          foreach($tag_content->{$sub_tags[1]} as $tag_content){
            if($tag_content->{$sub_tags[2]}){
              foreach($tag_content->{$sub_tags[2]} as $tag_content){
              
                if((string)$tag_content){
                  $return_array[] = (string)$tag_content;
                }else{$return_array[] = false;}
                
                
              }
            }else{$return_array[] = false;}
          }
        }else{$return_array[] = false;}
      }
    }else{$return_array[] = false;}
  }
  
  
  
  
  if(count($sub_tags) == 4){ //ONE;TWO;THREE;THIS
    $tag_contents = $product->{$sub_tags[0]};
    if($tag_contents){
      foreach($tag_contents as $tag_content){
        if($tag_content->{$sub_tags[1]}){
          foreach($tag_content->{$sub_tags[1]} as $tag_content){
            if($tag_content->{$sub_tags[2]}){
              foreach($tag_content->{$sub_tags[2]} as $tag_content){
              
                if($tag_content->{$sub_tags[3]}){
                
                foreach($tag_content->{$sub_tags[3]} as $tag_content){
                  if((string)$tag_content){
                    $return_array[] = (string)$tag_content;
                  }else{
                    $return_array[] = false;
                  }
                }
                
                
                }else{$return_array[] = false;}
                
                
              }
            }else{$return_array[] = false;}
          }
        }else{$return_array[] = false;}
      }
    }else{$return_array[] = false;}
  }
  
  
  
  
  if(count($sub_tags) == 5){ //ONE;TWO;THREE;FOUR;THIS
    $tag_contents = $product->{$sub_tags[0]};
    if($tag_contents){
      foreach($tag_contents as $tag_content){
        if($tag_content->{$sub_tags[1]}){
          foreach($tag_content->{$sub_tags[1]} as $tag_content){
            if($tag_content->{$sub_tags[2]}){
              foreach($tag_content->{$sub_tags[2]} as $tag_content){
              
                if($tag_content->{$sub_tags[3]}){
                
                foreach($tag_content->{$sub_tags[3]} as $tag_content){
                  if($tag_content->{$sub_tags[4]}){
  
                    
                    foreach($tag_content->{$sub_tags[4]} as $tag_content){
                    
                    if((string)$tag_content){
                      $return_array[] = (string)$tag_content;
                    }else{
                      $return_array[] = false;
                    }

  
                    }
  
  
  
                  }else{
                    $return_array[] = false;
                  }
                }
                
                
                }else{$return_array[] = false;}
                
                
              }
            }else{$return_array[] = false;}
          }
        }else{$return_array[] = false;}
      }
    }else{$return_array[] = false;}
  }
  
  return $return_array;
}
?>