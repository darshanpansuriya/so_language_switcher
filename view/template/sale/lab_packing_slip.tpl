<?php

$user_name = $this->user->getUserName();
$user_group_string = $this->user->getUGstr();

?><?php echo '<?phpxml version="1.0" encoding="UTF-8"?>' . "\n"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
<title><?php echo $title; ?></title>
<base href="<?php echo $base; ?>" />
<meta charset="utf-8">
<script type="text/javascript" src="view/javascript/jquery/jquery-1.8.1.min.js"></script>
<style type="text/css">
html, body, form, table {
	margin: 0;
	padding: 0;
	line-height: auto;
	font: 12px "Times New Roman", Times, serif;
}
table {
	width: 100%;
	border: 0;
	border-collapse : collapse;
}
td {
	padding: 0px 2px;
}
#container {
	width: 680px;
	margin: 0 auto;
}
table td {
	text-align: left;
	vertical-align: top;
}
.hf, .bo {
	text-align: right;
	font-weight: bold;
}
.bo {
	text-align: left;
}
.bb {
	vertical-align: bottom;
	font-size: 14px;
	font-weight: bold;
}
.wrap {
	white-space: normal;
}
.sep {
	width: 10px;
}
tr:nth-child(even) {
	background: #eee
}
tr:first-child td {
	border-bottom: 1px solid #333333;
}
.box {
	margin: 0 2px 0 2px;
	padding: 10px;
	width: 315px;
}
.noborder td {
	border: none !important;
}
.total-quantity-value{
  	float: right;
    margin-right: 18px;
    width: 4px;
}
.total-quantity-title{
 	float: right;
    width: 90px;
}

.column { }
.columnone { width:50px; text-align:center; vertical-align:middle; height: 30px;}
.columntwo { width:400px; text-align:center; vertical-align:middle; height: 30px;}
.columnthree { width:30px; text-align:center; vertical-align:middle; height: 30px;}
.address-box
{
color: black;
font-weight: bold;
width: 320px;
float: left;
min-height: 30px;
line-height: 30px;
}

* {
 font-size: 100%;
 font-family: Arial;
}
</style>
</head>

<body>
<div id="container">
   
	<?php foreach($master_packing_list_data as $packing_list_data) {?>

	<div style="page-break-after: always;">
	<div class="box" style="float:left"><img src="images/logo.jpg" width="225" height="87">
	
	<div class="address-box">
		<label>8 Murray Point Rd McDougall, ON P2A 2W9</label>
	</div>
		<div class="box" style="text-align:left; float:left; padding: 15px 0 0px 0px; font-weight:bold;"> 
			<table cellspacing="0">
				<tr>
					<td class="bb">Ship To:</td>
				</tr>
				<tr>
					<td>
						<div style="padding:5px;">
							<div><?php echo $packing_list_data['general-info']['ship_to'];?></div>
						</div>
					</td>
				</tr>
			</table>
		</div>
	</div>
	
	<div class="box" style="float:left">
		
		<table cellspacing="0">
			<tr>
				<td width="40%" style="text-align: right;" colspan="2"><h1 style="font-size:30px">Packing Slip</h1></td>
			</tr>
			<tr>
				<td width="40%" style="text-align: right;"><h3>Date:</h3></td>
				<td style="text-align: center;"> <h3><?php echo $packing_list_data['general-info']['create_date'];?></h3></td>
			</tr>
			<tr>
				<td width="40%" style="text-align: right;"><h3>Number:</h3></td>
				<td style="text-align: center;"> <h3><?php echo $packing_list_data['general-info']['id'];?></h3></td>
			</tr>
			<tr>
				<td width="40%" style="text-align: right;"><h3>Ship Date:</h3></td>
				<td style="text-align: center;"> <h3><?php echo $packing_list_data['general-info']['ship_date'];?></h3></td>
			</tr>
			<tr>
				<td width="40%" style="text-align: right;"><h3>Ship Via:</h3></td>
				<td style="text-align: center;"> <h3><?php echo $packing_list_data['general-info']['ship_via'];?></h3></td>
			</tr>
		</table>
	</div>			
	<br clear="all" />				
		
							
			<div id="items-list-div">
			
				
			<div class="items-box" style="width:652px">
				<table cellspacing="0" cellpadding="10">										
				<tr>					
					<td colspan="1" class="bb">Item</td>
					<td colspan="1" class="bb">Item Description</td>
					<td colspan="1" class="bb">Qty</td>
				</tr>		
				<tr>
					<td height = "10px"></td>
				</tr>
				<tr>
			<?php $total_order_qty = ''; ?>	
			<?php foreach($packing_list_data['clinic-order-info'] as $packing_list_clinic ){ ?>	
			
					<td><label style="font-weight: bold;"><?php  echo($packing_list_clinic['clinic-name']);?></label></td>
				</tr>
												
						
						<?php foreach( $packing_list_clinic['clinic-orders'] as $order): ?>	
						
												
									<?php 	if ($order) {
									
										$s = "SELECT lookup_table_title FROM lookup_table WHERE lookup_table_id = " . (int) $order['order_values_37'];

											$order_query = $this->db->query($s);

											if ($order_query->num_rows) {

												foreach ($order_query->row as $k => $v) {
													$packing_orders_extra['item-name'] = $this->model_sale_order->getItemName($order['order_values_37']);
													$file = "";										
												foreach (glob("hc_scripts/plupload/uploads/" . $order['order_id'] . "/*.*") as $filename) {
													$file = basename($filename);
														}
														$packing_orders_extra['filename'] = $file;	
													}
													
												} 
										
									
																		
									}									
									?>
												
								<?php $total_order_qty += $order['order_quantity']; ?>										
												<tr>						
													<td style="text-align:left; vertical-align:middle; width:170px;" class="columnone column">
														<?php if (isset($packing_orders_extra['item-name'])) echo $packing_orders_extra['item-name']; ?>
													</td>
													<td style="text-align:left;" class="columntwo column">
														<?php echo $order['order_id'];?>--<?php echo $order['patient_firstname']; ?> <?php echo $order['patient_lastname']; ?>
														- <?php //echo  $order['order_values_files_notes']; ?> - <?php if(isset($packing_orders_extra['filename'])) echo $packing_orders_extra['filename']; ?>
														<br />
													</td>
													<td class="columnthree column">
														<?php if(isset($order['order_quantity'])) echo $order['order_quantity']; ?>
													</td>
											
											  </tr>	
																
													<?php  endforeach; ?>
						<?php  }?>
			</table>	
			<div class="total-quantity-value">
					<label><?php  echo $total_order_qty; ?></label>
				</div>	
				<div class="total-quantity-title">
					<label>Total Quantity :</label>
				</div>		
			
			<table style="margin-top:20px">
			  <tr></tr>
			</table>		
		</div>	
	</div>	
			
			</div>
			<?php } ?>	

</div>
<script src="view/javascript/jquery.masonry.min.js"></script> 
<script type="text/javascript">
$('#masonrydiv').masonry({
  itemSelector: '.box'
});
window.print();//window.close();
</script>
</body>
</html>