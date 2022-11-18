<?php
final class Cart {
  	public function __construct($registry) {
		$this->config = $registry->get('config');
		$this->customer = $registry->get('customer');
		$this->session = $registry->get('session');
		$this->db = $registry->get('db');
		$this->tax = $registry->get('tax');
		$this->weight = $registry->get('weight');

		if (!isset($this->session->data['cart']) || !is_array($this->session->data['cart'])) {
      		$this->session->data['cart'] = array();
    	}
	}
	      
  	public function getProducts() {
		$product_data = array();
		
    	foreach ($this->session->data['cart'] as $key => $value) {
      		$array = explode(':', $key);
      		$product_id = $array[0];
      		$quantity = $value;
			$stock = TRUE;

      		if (isset($array[1])) {
        		$options = explode('.', $array[1]);
      		} else {
        		$options = array();
      		} 

//$nowhc = "NOW()";
$nowhc = "'" . $_SESSION['s_week_sunday'] . "'";
		
		$customer_group_id = $this->customer->getCustomerGroupId();
		$sql = "SELECT *, pd.name AS name, p.quantity AS quantity, p.product_id AS product_id, p.image AS image, pg.product_id AS pg_product_id, p.price AS price, pg.price AS pg_price, p.sort_order AS sort_order, wcd.unit AS weight_class, mcd.unit AS length_class FROM  product p 
		LEFT JOIN  product_description pd ON (p.product_id = pd.product_id) 
		LEFT JOIN  weight_class wc ON (p.weight_class_id = wc.weight_class_id) 
		LEFT JOIN  weight_class_description wcd ON (wc.weight_class_id = wcd.weight_class_id) LEFT JOIN  length_class mc ON (p.length_class_id = mc.length_class_id) 
		LEFT JOIN  length_class_description mcd ON (mc.length_class_id = mcd.length_class_id) 
		LEFT JOIN  product_group_price pg ON (p.product_id = pg.product_id AND pg.customer_group_id = '$customer_group_id') 
		WHERE p.product_id = '" . (int)$product_id . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND wcd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.date_available <= $nowhc AND p.status = '1'";
	 
		if ($this->customer->isLogged()) {
			$customer_group_id = $this->customer->getCustomerGroupId();

			$sql .= " AND (".$_SESSION['s_week_number']." BETWEEN p.start_week AND p.end_week) AND pg.price > 0";
		}
      		$product_query = $this->db->query($sql);

//  p($sql);    	  	
			if ($product_query->num_rows) {
      			$option_price = 0;

      			$option_data = array();
      
      			foreach ($options as $product_option_value_id) {
        		 	$option_value_query = $this->db->query("SELECT * FROM  product_option_value pov LEFT JOIN  product_option_value_description povd ON (pov.product_option_value_id = povd.product_option_value_id) WHERE pov.product_option_value_id = '" . (int)$product_option_value_id . "' AND pov.product_id = '" . (int)$product_id . "' AND povd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY pov.sort_order");
					
					if ($option_value_query->num_rows) {
						$option_query = $this->db->query("SELECT pod.name FROM  product_option po LEFT JOIN  product_option_description pod ON (po.product_option_id = pod.product_option_id) WHERE po.product_option_id = '" . (int)$option_value_query->row['product_option_id'] . "' AND po.product_id = '" . (int)$product_id . "' AND pod.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY po.sort_order");
						
        				if ($option_value_query->row['prefix'] == '+') {
          					$option_price = $option_price + $option_value_query->row['price'];
        				} elseif ($option_value_query->row['prefix'] == '-') {
          					$option_price = $option_price - $option_value_query->row['price'];
        				}
        
        				$option_data[] = array(
          					'product_option_value_id' => $product_option_value_id,
          					'name'                    => $option_query->row['name'],
          					'value'                   => $option_value_query->row['name'],
          					'prefix'                  => $option_value_query->row['prefix'],
          					'price'                   => $option_value_query->row['price']
        				);
						
						if ($option_value_query->row['subtract'] && (!$option_value_query->row['quantity'] || ($option_value_query->row['quantity'] < $quantity))) {
							$stock = FALSE;
						}
					}
      			} 
			
				if ($this->customer->isLogged()) {
					$customer_group_id = $this->customer->getCustomerGroupId();
				} else {
					$customer_group_id = $this->config->get('config_customer_group_id');
				}
				
				$discount_quantity = 0;
				foreach ($this->session->data['cart'] as $k => $v) {
					$array2 = explode(':', $k);
					if ($array2[0] == $product_id) {
						$discount_quantity += $v;
					}
				}

$nowhc = "'" . $_SESSION['s_week_sunday'] . "'";
		
				$product_discount_query = $this->db->query("SELECT price FROM  product_discount WHERE product_id = '" . (int)$product_id . "' AND customer_group_id = '" . (int)$customer_group_id . "' AND quantity <= '" . (int)$discount_quantity . "' AND ((date_start = '0000-00-00' OR date_start <= $nowhc) AND (date_end = '0000-00-00' OR date_end >= $nowhc)) ORDER BY quantity DESC, priority ASC, price ASC LIMIT 1");

// first discount, then special, then client, then default

				
				if ($product_discount_query->num_rows) {
					$price = $product_discount_query->row['price'];
				} else {

$nowhc = "'" . $_SESSION['s_week_sunday'] . "'";
		
					
					$product_special_query = $this->db->query("SELECT price FROM  product_special WHERE product_id = '" . (int)$product_id . "' AND customer_group_id = '" . (int)$customer_group_id . "' AND ((date_start = '0000-00-00' OR date_start <= $nowhc) AND (date_end = '0000-00-00' OR date_end >= $nowhc)) ORDER BY priority ASC, price ASC LIMIT 1");
					if ($product_special_query->num_rows) {
						$price = $product_special_query->row['price'];
					} else {
						if ($product_query->row['pg_price']) {
							$price = $product_query->row['pg_price'];
						} else {
							$price = $product_query->row['price'];
						}
					}
				}
				
				$download_data = array();     		
				
				$download_query = $this->db->query("SELECT * FROM  product_to_download p2d LEFT JOIN  download d ON (p2d.download_id = d.download_id) LEFT JOIN  download_description dd ON (d.download_id = dd.download_id) WHERE p2d.product_id = '" . (int)$product_id . "' AND dd.language_id = '" . (int)$this->config->get('config_language_id') . "'");
			
				foreach ($download_query->rows as $download) {
        			$download_data[] = array(
          				'download_id' => $download['download_id'],
						'name'        => $download['name'],
						'filename'    => $download['filename'],
						'mask'        => $download['mask'],
						'remaining'   => $download['remaining']
        			);
				}
				
				if (!$product_query->row['quantity'] || ($product_query->row['quantity'] < $quantity)) {
					$stock = FALSE;
				}

				$custarr = $this->customer->getCustomerInfo();
				
				if (!$custarr) $custarr['delivery_days'] = '';


      			$product_data[$key] = array(
        			'key'          => $key,
        			'product_id'   => $product_query->row['product_id'],
        			'name'         => $product_query->row['name'],
        			'model'        => $product_query->row['model'],
        			'sku'        => $product_query->row['sku'],
        			'location'        => $product_query->row['location'],
        			'customer_sku'        => $product_query->row['customer_sku'],
					'suggested_retail' => $product_query->row['suggested_retail'],

        			'product_hc_categ_code'        => $product_query->row['product_hc_categ_code'],
					'shipping'     => $product_query->row['shipping'],
        			'image'        => $product_query->row['image'],
        			'option'       => $option_data,
					'download'     => $download_data,
        			'quantity'     => $quantity,
        			'minimum'      => $product_query->row['minimum'],
					'subtract'     => $product_query->row['subtract'],
					'stock'        => $stock,
        			'price'        => ($price + $option_price),
        			'total'        => ($price + $option_price) * $quantity,
        			'tax_class_id' => $product_query->row['tax_class_id'],
        			'weight'       => $product_query->row['weight'],
        			'weight_class' => $product_query->row['weight_class'],
        			'length'       => $product_query->row['length'],
					'width'        => $product_query->row['width'],
					'height'       => $product_query->row['height'],
        			'length_class' => $product_query->row['length_class']					
      			);
			} else {
				$this->remove($key);
			}
    	}
		
		return $product_data;
  	}
		  
  	public function add($product_id, $qty = 1, $options = array()) {
    	if (!$options) {
      		$key = $product_id;
    	} else {
      		$key = $product_id . ':' . implode('.', $options);
    	}
//p($this->session->data['cart'][$key]);    	
		if ((int)$qty && ((int)$qty > 0)) {
    		if (!isset($this->session->data['cart'][$key])) {
      			$this->session->data['cart'][$key] = (int)$qty;
    		} else {
      			$this->session->data['cart'][$key] += (int)$qty;
    		}
		}
//p($this->session->data['cart'][$key]);
		$this->setMinQty();

  	}

  	public function update($key, $qty) {
    	if ((int)$qty && ((int)$qty > 0)) {
      		$this->session->data['cart'][$key] = (int)$qty;
    	} else {
	  		$this->remove($key);
		}
		$this->setMinQty();
  	}

  	public function remove($key) {
		if (isset($this->session->data['cart'][$key])) {
     		unset($this->session->data['cart'][$key]);
  		}
	}

  	public function clear() {
		$this->session->data['cart'] = array();
  	}
  	
  	public function getWeight() {
		$weight = 0;
	
    	foreach ($this->getProducts() as $product) {
			if ($product['shipping']) {
      			$weight += $this->weight->convert($product['weight'] * $product['quantity'], $product['weight_class'], $this->config->get('config_weight_class'));
			}
		}
	
		return $weight;
	}

	public function setMinQty() {
		foreach ($this->getProducts() as $product) {
			if ($product['quantity'] < $product['minimum']) {
				$this->session->data['cart'][$product['key']] = $product['minimum'];
			}
		}
  	}
	
  	public function getSubTotal() {
		$total = 0;
		
		foreach ($this->getProducts() as $product) {
			$total += $product['total'];
		}

		return $total;
  	}
	
	public function getTaxes() {
		$taxes = array();
		
		foreach ($this->getProducts() as $product) {
			if ($product['tax_class_id']) {
				if (!isset($taxes[$product['tax_class_id']])) {
					$taxes[$product['tax_class_id']] = $product['total'] / 100 * $this->tax->getRate($product['tax_class_id']);
				} else {
					$taxes[$product['tax_class_id']] += $product['total'] / 100 * $this->tax->getRate($product['tax_class_id']);
				}
			}
		}
		
		return $taxes;
  	}

  	public function getTotal() {
		$total = 0;
		
		foreach ($this->getProducts() as $product) {
			$total += $this->tax->calculate($product['total'], $product['tax_class_id'], $this->config->get('config_tax'));
		}

		return $total;
  	}
  	
  	public function countProducts() {
		return array_sum($this->session->data['cart']);
	}
	
  	public function countNoOfItems() {
		return count($this->session->data['cart']);
	}
	  
  	public function hasProducts() {
    	return count($this->session->data['cart']);
  	}
  
  	public function hasStock() {
		$stock = TRUE;
		
		foreach ($this->getProducts() as $product) {
			if (!$product['stock']) {
	    		$stock = FALSE;
			}
		}
		
    	return $stock;
  	}
  
  	public function hasShipping() {
		$shipping = FALSE;
		
		foreach ($this->getProducts() as $product) {
	  		if ($product['shipping']) {
	    		$shipping = TRUE;
				
				break;
	  		}		
		}
		
		return $shipping;
	}
	
  	public function hasDownload() {
		$download = FALSE;
		
		foreach ($this->getProducts() as $product) {
	  		if ($product['download']) {
	    		$download = TRUE;
				
				break;
	  		}		
		}
		
		return $download;
	}	
}
?>