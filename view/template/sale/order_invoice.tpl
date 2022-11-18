<?php

$user_name = $this->user->getUserName();
$user_group_string = $this->user->getUGstr();

?><?php echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="<?php echo $direction; ?>" lang="<?php echo $language; ?>" xml:lang="<?php echo $language; ?>">
<head>
<title><?php echo $title; ?></title>
<base href="<?php echo $base; ?>" />
<link rel="stylesheet" type="text/css" href="view/stylesheet/invoice.css" />
</head>
<body>
<?php foreach ($orders as $order) { ?>
<div style="page-break-after: always;">

 
  <div class="div1">
    <table width="100%">
      <tr><td style="text-align:center; vertical-align:middle; width:200px;"><img src="../image/logoorg.png" height="60" /></td>
        <td  style="text-align:left; vertical-align:top;" ><?php echo $order['store_name']; ?><br />
          <?php echo $order['address']; ?><br />
          <?php echo $text_telephone; ?> <?php echo $order['telephone']; ?><br />
          <?php if ($order['fax']) { ?>
          <?php echo $text_fax; ?> <?php echo $order['fax']; ?><br />
          <?php } ?>
          <?php echo $order['email']; ?><br />
<?php echo $order['store_url']; ?>
          </td>
        <td align="right" valign="top">
         <h1><?php echo $text_invoice; ?></h1>
        <table>
            <tr>
              <td><b><?php echo $text_date_added; ?></b></td>
              <td><?php echo $order['date_added']; ?></td>
            </tr>
            <?php if ($order['invoice_id']) { ?>
            <tr>
              <td><b><?php echo $text_invoice_id; ?></b></td>
              <td><?php echo $order['invoice_id']; ?></td>
            </tr>
            <?php } ?>
            <tr>
              <td><b><?php echo $text_order_id; ?></b></td>
              <td><?php echo $order['order_id'] . ($user_group_id == '12'?"-$broker_code":""); ?></td>
            </tr>
            <tr>
              <td><b>Delivery Date:</b></td>
              <td><?php echo $order['deliverydate']; ?></td>
            </tr>
            <tr>
              <td><b>Clinic Code:</b></td>
              <td><?php
			  
			  $q1 = $this->db->query("SELECT * FROM clinic WHERE clinic_id = '". $order['order_info']['clinic_id']."'");
			  $q = $q1->row;
			   echo $q['clinic_code'] ?></td>
            </tr>
          </table></td>
      </tr>
    </table>
  </div>
  <table class="address">
    <tr class="heading">
      <td width="50%"><b><?php echo $text_to; ?></b></td>
      <td width="50%"><b><?php echo $text_ship_to; ?></b></td>
    </tr>
    <tr>
      <td>
        <?php echo $order['payment_address']; ?><br/>
        <?php echo $order['clinic_email']; ?><br/>
        <?php echo $order['clinic_telephone']; ?>
      </td>
      <td><?php echo ($order['payment_address']!= $order['shipping_address']?$order['shipping_address']:''); ?></td>
    </tr>
  </table>
  
  
  
  
  
<table class="product" width="100%" cellpadding="2" cellspacing="0">
    <tr class="heading" style="font-weight:bold;">
    <td colspan="5" rowspan="2" align="left" style="border-top:1px solid #999999; border-bottom:1px solid #999999;">Shipped</td>
    <td colspan="2" rowspan="2" align="left" style="border-top:1px solid #999999; border-bottom:1px solid #999999;">Total Sales</td>
    <td colspan="3" align="left" style="border-top:1px solid #999999; ">Product Description</td>
    <td colspan="2" rowspan="2" style="border-top:1px solid #999999; border-bottom:1px solid #999999;">Pricing</td>
    <td rowspan="2" align="right" style="border-top:1px solid #999999; border-bottom:1px solid #999999;">Price</td>
  </tr>
    <tr class="heading" style="font-weight:bold;">
      <td align="left" style="border-top:1px solid #999999; border-bottom:1px solid #999999;">Size</td>
      <td align="left" style="border-top:1px solid #999999; border-bottom:1px solid #999999;">UPC</td>
      <td align="left" style="border-top:1px solid #999999; border-bottom:1px solid #999999;">SKU</td>
    </tr>
        <?php 
		
		$estimated_carts = 0;
//p($order);	
		$clinic_id = $order['order_info']['clinic_id']	;
		
		$subtotal = 0;
		$tax = 0;
		
    	foreach ($order['product'] as $product) { ?>
<?php    
 
            $q1 = $this->db->query("SELECT * FROM product p LEFT JOIN product_description pd ON p.product_id = pd.product_id WHERE p.product_id = '".$product['order_products']['product_id']."'");
			$product_details = $q1->row;
			if ($broker_code && $broker_code != strtolower($product['order_products']['manufacturer_code']))continue;
//			p($product_details, __LINE__,__FILE__);
?>		
<?php
//			p($product, __LINE__,__FILE__);
			$subtotal += $product['order_products']['total'];
			$tax += $product['order_products']['tax'] * $product['order_products']['total'] / 100.00;
			$ss = $this->db->query("SELECT * FROM count_units WHERE cou_id = '" . $product_details['product_unit_id']."'");
			$ss1 = $ss->row;
			
			$unitofmeasure = $ss1['cou_name'] ? $ss1['cou_name'] : 'ea';


        	$vv = $product_details['product_id'];
		
?>        
        
  <tr>
    <td align="right" valign="top"><b><?php echo $product['quantity']?></b></td>
    <td align="right" valign="top"><?php echo $unitofmeasure?></td>
    <td align="center" valign="top">x</td>
    <td align="left" valign="top"><?php echo $product_details['package_quantity']?></td>
    <td align="center" valign="top">=</td>
    <td align="left" valign="top"><b><?php echo $product_details['package_quantity'] * $product['quantity']?></b></td>
    <td align="left" valign="top">units</td>
    <td colspan="3" align="left" valign="top" style="white-space:normal;"><b><?php echo $product['name']; ?>
<?php foreach ($product['option'] as $option) { 
	echo "<br />
	&nbsp;<small> - ". $option['name'] . " " . $option['value'] . "</small>";
 } ?></b><em> <?php echo $product_details['desc_upgrade']?></em>
    </td>
    <td align="right" valign="top">$<?php echo number_format(str_replace(array("$",","),"",$product['price']) / $product_details['package_quantity'],2)?></td>
    <td align="left" valign="top">/ea<br></td>
    <td align="right" valign="top"><?php echo $product['total']?></td>
  </tr>
  <tr>
    <td colspan="5" align="left" valign="top" style="border-bottom:1px solid #DDDDDD; color:#666666;"><?php echo $product['model']?></td>
    <td colspan="2" align="right" valign="top" style="border-bottom:1px solid #DDDDDD; color:#666666;"><?php echo $product['order_products']['manufacturer_code'];?></td>
    <td align="left" valign="top" style="border-bottom:1px solid #DDDDDD;"><?php echo $product_details['product_size']?></td>
    <td align="left" valign="top" style="border-bottom:1px solid #DDDDDD; white-space:nowrap"><?php echo $product_details['sku']?></td>
    <td align="left" valign="top" style="border-bottom:1px solid #DDDDDD; white-space:nowrap"><?php echo $cust_related['clinic_sku']?></td>
    <td align="right" valign="top" style="border-bottom:1px solid #DDDDDD;"><?php echo $product['price'];?></td>
    <td align="left" valign="top" style="border-bottom:1px solid #DDDDDD;"><?php echo $unitofmeasure?></td>
    <td align="right" valign="top" style="border-bottom:1px solid #DDDDDD;">&nbsp;</td>
  </tr>
  
	  <?php } ?>
      <?php 
	  $f = 1;
	  $cols = 4;
	  
	  foreach ($order['total'] as $total) { 
	  
	  ?>
      <tr>
        <?php
        if ($f) {
			echo '<td align="left" colspan="8"><b>Est. Carts: </b>'.ceil($estimated_carts). ' ('.number_format($estimated_carts,2).')'.'</td>';
		}
		?>
        <td align="right" colspan="<?php echo $cols?>"><b><?php echo $total['title']; ?></b></td>
        <td align="right"><?php echo $total['text']; ?></td>
      </tr>
      <?php
	  $f = 0;
	  $cols = 12;
	   } ?>
    </table>

  <table class="product">
    <tr class="heading">
      <td><b><?php echo $column_comment; ?></b></td>
    </tr>
    <tr>
      <td><?php echo $order['comment']; ?></td>
    </tr>
  </table>
</div>
<?php } ?>
</body>
</html>