<?php echo $header; ?>
<div class="h1float">
	<h1>
		Track <strong>Orders</strong>
	</h1>
</div>

<div style="float: left; width: 730px;">
	<label id="response" style="float: right; line-height: 3;"></label>
</div>
<div class="buttons">
	<a onclick="saveAllTrackingNumbers();" class="button s">
		<p></p> <span>Save all</span>
	</a>
</div>
<div style="clear: both"></div>
<form action="" method="post" enctype="multipart/form-data" id="form">
	<table class="list">
		<thead>
			<tr>
				<td class="center"><input id="select-all-packing" type="checkbox"
					onclick="selectAllTracking();" /></td>

				<td class="center"><a href="<?php echo $sort_packing_list_num; ?>"
				<?php if ($sort == 'packing_list_num') { ?>
					class="<?php echo strtolower($order); ?>" <?php } ?>><?php echo $packing_number; ?>
				</a></td>

				<td class="center"><?php echo $packing_orders; ?></a></td>

				<td class="center"><a href="<?php echo $sort_shipping_date; ?>"
				<?php if ($sort == 'ship_date') { ?>
					class="<?php echo strtolower($order); ?>" <?php } ?>><?php echo $shipping_date; ?>
				</a></td>

				<td class="center"><?php echo $tracking_number; ?></a></td>

				<td class="center"><?php echo $action; ?></a></td>

			</tr>
		</thead>
		<tbody>
			<tr class="filter">

				<td class="center"></td>

				<td class="center"><input style="width: 100px; text-align: center;"
					type="text" name="filter_packing_num"
					value="<?php echo $filter_packing_num;  ?>" /></td>

				<td class="center"></td>

				<td class="center"><input class="date"
					style="width: 100px; text-align: center;" type="text"
					name="filter_shipping_date"
					value="<?php echo $filter_shipping_date; ?>" /></td>

				<td class="center"><input style="width: 200px; text-align: center;"
					type="text" name="filter_tracking_num"
					value="<?php echo $filter_tracking_num;  ?>" /></td>

				<td align="center"><a onclick="filter();" class="button f"><p></p> <span>Filter</span>
				</a></td>
			</tr>

			<?php if($orders != ''){ 
					
          foreach($orders as $order){ ?>

			<tr style="background-color: rgb(224, 240, 254);">

				<td class="center"><input
					id="<?php echo $order['packing_list_num']; ?>"
					class="select-packing" type="checkbox"
					onclick="selectOneTracking(<?php echo $order['packing_list_num']; ?>);" />
				</td>

				<td class="center"><input
					name="packing-<?php echo $order['packing_list_num']; ?>"
					type="hidden" class="select-packing-number" /> <?php echo $order['packing_list_num']; ?>
				</td>

				<td class="center">
				<?php
					$original = $order['pack_orders'];
					$parts = str_split($original, 50);
					$final = implode("<br>", $parts); 
					
					echo $final;
				 ?>
				</td>

				<td class="center"><?php echo $order['ship_date']; ?></td>

				<td class="center"><input style="width: 200px; text-align: center;"
					type="text"
					onchange="trackingUpdate(<?php echo $order['packing_list_num']; ?>,document.getElementById('tracking-<?php echo $order['packing_list_num']; ?>').value);" 
					id="tracking-<?php echo $order['packing_list_num']; ?>"
					name="tracking_number"
					value="<?php echo $order['order_shipping_number']; ?>" /></td>

				<td class="center"><a
					onclick="updateTrackingNumber(<?php echo $order['packing_list_num']; ?>,document.getElementById('tracking-<?php echo $order['packing_list_num']; ?>').value);"
					class="button s"><p></p> <span>Save</span> </a></td>

			</tr>
			<?php } 
}?>

		</tbody>
	</table>
</form>

<div class="pagination">
	<?php echo $pagination; ?>
</div>

<script type="text/javascript"><!--
    function filter() {
    url = 'index.php?route=common/tracking&token=<?php echo $token; ?>';
	
    var filter_packing_num = $('input[name=\'filter_packing_num\']').attr('value');
	
    if (filter_packing_num) {
    url += '&filter_packing_num=' + encodeURIComponent(filter_packing_num);
}
	
var filter_shipping_date = $('input[name=\'filter_shipping_date\']').attr('value');
	
if (filter_shipping_date) {
url += '&filter_shipping_date=' + encodeURIComponent(filter_shipping_date);
}
	
var filter_tracking_num = $('input[name=\'filter_tracking_num\']').attr('value');
	
if (filter_tracking_num) {
url += '&filter_tracking_num=' + encodeURIComponent(filter_tracking_num);
}

location = url;
}

</script>
<script
	type="text/javascript" src="view/javascript/jquery/ui/ui.datepicker.js"></script>
<script type="text/javascript">
$('.date').datepicker({dateFormat: 'yy-mm-dd',changeMonth: true,changeYear: true});
</script>

<?php echo $footer; ?>