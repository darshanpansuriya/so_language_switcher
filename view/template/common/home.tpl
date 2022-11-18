<?php echo $header; ?>
<div class="success"><?php echo $success_message; ?></div>
<?php

$user_name = $this->user->getUserName();
$user_group_string = $this->user->getUGstr();

echo htmlspecialchars_decode($this->config->get('config_description_1'));

?>
<?php /*?>

      <div style="float: left; width: 49%;">
        <div class="listdiv"><?php echo $text_overview; ?></div>
        <div style="background: #FCFCFC; border: 1px solid #8EAEC3; padding: 10px;">
          <table style="width: 100%;" class='slist'>
            <tr>
              <td width="80%"><?php echo $text_total_sale; ?></td>
              <td align="right"><?php echo $total_sale; ?></td>
              </tr>
            <tr>
              <td><?php echo $text_total_sale_year; ?></td>
              <td align="right"><?php echo $total_sale_year; ?></td>
            </tr>
            <tr>
              <td><?php echo $text_total_order; ?></td>
              <td align="right"><?php echo $total_order; ?></td>
            </tr>
            <tr>
              <td><?php echo $text_total_clinic; ?></td>
              <td align="right"><?php echo $total_clinic; ?></td>
            </tr>
          </table>
        </div>
      </div>
<?php if ($user_group_string == '1') { ?>
      <div style="float: right; width: 49%;">
        <div  class="listdiv" style="padding:2px;">
          <div style="width: 100%; display: inline-block;">
            <div style="float: left; padding:4px;"><?php echo $text_statistics; ?></div>
            <div style="float: right;"><?php echo $entry_range; ?>
              <select id="range" onchange="getSalesChart(this.value)" style="margin: 2px 3px 0 0;">
                <option value="day"><?php echo $text_day; ?></option>
                <option value="week"><?php echo $text_week; ?></option>
                <option value="month"><?php echo $text_month; ?></option>
                <option value="year"><?php echo $text_year; ?></option>
              </select>
            </div>
          </div>
        </div>
        <div style="background: #FCFCFC; border: 1px solid #8EAEC3; padding: 10px; height: 49%;">
          <div id="report" style="width: 400px; height: 180px; margin: auto;"></div>
        </div>
<?php } ?>
      </div>

<?php */?>
    <div style="clear:both; height:10px;"></div>
      <div class="listdiv"><?php echo $text_latest_10_orders; ?></div>
      <div style="background: #FCFCFC; border: 1px solid #8EAEC3; padding: 10px; ">
        <table class="list">
          <thead>
            <tr>
              <td class="right"><?php echo $column_order; ?></td>
              <td class="left"><?php echo $column_name; ?></td>
              <td class="left"><?php echo $column_patient; ?></td>
              <td class="left"><?php echo $column_status; ?></td>
              <td class="left"><?php echo $column_date_added; ?></td>
              <td class="left"><?php echo $column_shipping_date; ?></td>
<?php /* if ($user_group_string == '1') { ?>
             <td class="right"><?php echo $column_total; ?></td>
<?php } */?>
              <td class="center" width="70"><?php echo $column_action; ?></td>
            </tr>
          </thead>
          <tbody>
            <?php if ($orders) { ?>
            <?php foreach ($orders as $order) { ?>
            <tr>
              <td class="right"><?php echo $order['order_id']; ?></td>
              <td class="left"><?php echo $order['clinic_name']; ?></td>
              <td class="left"><?php echo $order['patient_name']; ?></td>
              <td class="left"><?php echo $order['status']; ?></td>
              <td class="left"><?php echo $order['date_added']; ?></td>
              <td class="left"><?php echo $this->hc_functions->ch_date($order['order_deliverydate']); ?></td>
<?php /*if ($user_group_string == '1') { //p($order);?>
              <td class="right"><?php echo $order['total']; ?></td>
<?php } */?>
              <td align="center" class="edit"><?php foreach ($order['action'] as $action) { ?>
                <a href="<?php echo $action['href']; ?>" class="button <?php echo $action['text'] == 'View'?'v':'e';?>"><p></p><span><?php echo $action['text']; ?></span></a>
                <?php } ?></td>
            </tr>
            <?php } ?>
            <?php } else { ?>
            <tr>
              <td class="center" colspan="<?php echo $user_group_string == '1' ? 8 : 7;?>"><?php echo $text_no_results; ?></td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
<?php
// }
/*?>



<!--[if IE]>
<script type="text/javascript" src="view/javascript/jquery/flot/excanvas.js"></script>
<![endif]-->
<script type="text/javascript" src="view/javascript/jquery/flot/jquery.flot.js"></script>
<script type="text/javascript"><!--
function getSalesChart(range) {

	$.ajax({
		type: 'GET',
		url: 'index.php?route=common/home/chart&token=<?php echo $token; ?>&range=' + range,
		dataType: 'json',
		async: false,
		success: function(json) {
			var option = {
				shadowSize: 0,
				lines: {
					show: true,
					fill: true,
					lineWidth: 1
				},
				grid: {
					backgroundColor: '#FFFFFF'
				},
				xaxis: {
            		ticks: json.xaxis
				}
			}
			$.plot($('#report'), [json.order, json.clinic], option);
		}
	});
}

getSalesChart($('#range').val());
//--></script>

<?php */ echo $footer; ?>
