<?php echo $header; ?>
<?php
//p(microtime());
$user_name = $this->user->getUserName();
$user_group_string = $this->user->getUGstr();

?>
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<?php if ($success) { ?>
<div class="success"><?php echo $success; ?></div>
<?php } ?>

<div class="h1float">
    <h1><strong><?php echo $heading_title; ?></strong></h1>
</div>

<div class="buttons">
    <span style="float:left !important;">
    <a id="batch_save_status" class="button e batch_save_component" name="batch_save_status" style="display:none !important;"><p></p><span><?php echo $button_save; ?></span></a> &nbsp;
                    <select name="batch_save_status_dd" id="batch_save_status_dd" class="batch_save_component" style="display:none !important; width: 196px;">
                        <option value="0" selected="selected"></option>
                        <?php foreach ($order_statuses as $order_status) { ?>
                        <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['order_status_name']; ?></option>
                        <?php } ?>
                    </select>
</span>
    <div id="text_editdate" style="display:none;"></div>
    <?php // if (!in_array($user_group_string, array('11'))) { ?>

    <a id="hc_save_status" class="button e" name="hc_save_status" style="display:none !important;"><p></p><span><?php echo $button_save_status; ?></span></a> &nbsp;

    <?php // } ?>

    <?php if (in_array($user_group_string, array('1','1'))) { ?>
    <span class="sp_hc_filter_order">
		<a href="index.php?route=sale/lab&token=<?php echo $token;?>" class="button f"><p></p><span><?php echo $button_lab_order_list; ?></span></a>
    <a onclick="filterByAdmin()" class="button f"><p></p><span><?php echo $button_filter_order; ?></span></a>
	<span class="sp_hc_filter_order">
    <?php } ?> &nbsp;


    <?php if (in_array($user_group_string, array('1','11'))) { ?>
    <span class="sp_hc_copy_order">
        <a id="hc_copy_order" class="button o"><p></p><span><?php echo $button_copy_order; ?></span></a>
        &nbsp; </span>
    <a href="index.php?route=sale/order/insert&token=<?php echo $token;?>" class="button a"><p></p><span><?php echo $button_add_order; ?></span></a>
    <?php } ?> &nbsp;



<? if(!in_array($user_group_string, array('11'))): ?>
    <span class="sp_hc_print_label">
        <a onclick="$('#form').attr('action', 'hc_scripts/print_labels.php'); $('#form').attr('target', '_self'); $('#form').submit();$('#form').attr('action', '');" class="button pr"><p></p><?php echo $button_create_labels; ?></a> &nbsp; </span>
<? endif; ?>
        <? if(in_array($user_group_string, array('1'))): ?>
    <span class="sp_hc_delete_order">
        <a onclick="$('#form').attr('action', '<?php echo $delete; ?>'); $('#form').attr('target', '_self'); $('#form').submit();" class="button d"><p></p><?php echo $button_delete_order; ?></a> &nbsp; </span>
<span class="sp_hc_print_order">
        <a onclick="printOrder()" class="button pr"><p></p><?php echo $button_print_order; ?></a> &nbsp; </span>
        <span class="sp_hc_print_workorder">
                <a onclick="printWorkOrder()" class="button pr"><p></p><?php echo $button_print_work_order; ?></a> &nbsp; </span>
   <span class="sp_hc_download_order">
        <a onclick="downloadOrder()" class="button e"><p></p><?php echo $button_download_order; ?></a> &nbsp; </span>

   <span class="sp_hc_create_packing_slip">
        <a onclick="handlePackSlip();" class="button pr"><p></p><span><?php echo $button_print_pack_list; ?></span></a> &nbsp; </span>

    <? endif; ?>

    <a class="button d" onclick="clear_filter();"><p></p><span><?php echo $button_clear_filter; ?></span></a>

</div>
<div style="clear:both"></div>

<form action="" method="post" enctype="multipart/form-data" id="form">
    <table class="list">
        <thead>
            <tr>
                <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);bring_buttons();" /></td>
                <td class="right">
                    <a href="<?php echo $sort_order_id; ?>"<?php echo ($sort == 'order_id'?' class="'. strtolower($order).'"':'')?>><?php echo $column_order; ?></a></td>
                <td class="left">
                    <a href="<?php echo $sort_clinic_name; ?>"<?php echo ($sort == 'clinic_name'?' class="'. strtolower($order).'"':'')?>><?php echo $column_clinic_name; ?></a></td>

                <td class="left">
                    <a href="<?php echo $sort_patient_name; ?>"
                       <?php if ($sort == 'patient_name') echo 'class="'.strtolower($order).'"';?>><?php echo $column_patient_name; ?></a></td>

                <td class="left"><?php if ($sort == 'status') { ?>
                    <a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_status; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_status; ?>"><?php echo $column_status; ?></a>
                    <?php } ?></td>
                <td class="left"><?php if ($sort == 'order_date_added') { ?>
                    <a href="<?php echo $sort_date_added; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_date_added; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_date_added; ?>"><?php echo $column_date_added; ?></a>
                    <?php } ?></td>

                <!-- Mod Delivery Date -->
                <td class="left"><?php if ($sort == 'order_deliverydate') { ?>
                    <a href="<?php echo $sort_deliverydate; ?>" class="<?php echo strtolower($order); ?>"><?php //echo $column_deliverydate; ?><?php echo $column_shipping_date; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_deliverydate; ?>"><?php echo $column_shipping_date; ?></a>
                    <?php } ?></td>
                <!-- End:Mod Delivery Date -->

                <?php /*?>            <td class="right"><?php if ($sort == 'order_total') { ?>
                    <a href="<?php echo $sort_total; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_total; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_total; ?>"><?php echo $column_total; ?></a>
                    <?php } ?></td><?php */?>
                <td class="center" width="70"><?php echo $column_action; ?></td>
            </tr>
        </thead>
        <tbody>
            <tr class="filter">
                <td><input type="submit" style="display:none; width:10px;" /></td>

                <td align="right"><input type="text" name="filter_order_id" value="<?php echo $filter_order_id; ?>" size="4" style="text-align: right;" /></td>
                <td><input type="text" name="filter_clinic_name" value="<?php echo $filter_clinic_name; ?>" /></td>
                <td><input type="text" name="filter_patient_name" value="<?php echo $filter_patient_name; ?>" size="8" /></td>

                <td><select name="filter_status">
                        <option value="*"></option>
                        <?php if ($filter_status == '0') { ?>
                        <option value="0" selected="selected"><?php echo $text_missing_orders; ?></option>
                        <?php } else { ?>
                        <option value="0">N/A</option>
                        <?php } ?>
                        <?php foreach ($order_statuses as $order_status) { ?>
                        <?php if ($order_status['order_status_id'] == $filter_status) { ?>
                        <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['order_status_name']; ?></option>
                        <?php } else { ?>
                        <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['order_status_name']; ?></option>
                        <?php } ?>
                        <?php } ?>
                    </select></td>
               <td style="display: block;"><div style="display: flex;"><input type="text" name="filter_date_added" value="<?php echo $filter_date_added;// $this->hc_functions->ch_date($filter_date_added); ?>" size="12" class="date" id="date"   style='width: 50% !important' autocomplete="off"/> - <input type="text" name="filter_date_added_2" value="<?php echo $filter_date_added_2; ?>" size="12" class="date" id="date_2"  style='width: 50% !important' autocomplete="off"/></div>
                </td>
                <!-- Mod Delivery Date -->
                  <td > <div style="display: flex;"><input type="text" name="filter_deliverydate" value="<?php echo $filter_deliverydate; // echo $this->hc_functions->ch_date($filter_deliverydate); ?>" size="12" id="deliverydate" class="date" autocomplete="off"/> - <input type="text" name="filter_deliverydate_2" value="<?php echo $filter_deliverydate_2;  ?>" size="12" id="deliverydate_2" class="date" autocomplete="off"/></div></td>
                <!-- End:Mod Delivery Date -->

                <?php /*?>            <td align="right"><input type="text" name="filter_total" value="<?php echo $filter_total; ?>" size="4" style="text-align: right;" /></td>
                <?php */?>            <td align="center"><a onclick="filter();"><p></p><span><?php echo $button_filter; ?></span></a></td>
            </tr>
            <?php if ($orders) { ?>
            <?php foreach ($orders as $order) { ?>
            <tr<?php // if ($order['order_values_rush_75'] == "Rush") echo " class='red'";?>>
                <td style="text-align: center;">

                    <input type="checkbox" name="selected[]" value="<?php echo $order['order_id']; ?>" <?php if ($order['selected']) { echo 'checked="checked"'; } ?> onclick="bring_buttons();" /></td>
                <td class="right"><?php echo $order['order_id']; ?></td>
                <td class="left"><?php echo $order['clinic_name']; ?></td>
                <td class="left"><?php echo $order['patient_name']; ?><?php if ($order['order_values_59'] == '252'){ echo ' (Repeat)';} ?></td>
                <td class="left">
                    <?php //ALSO CLINIC CAN CHANGE BATCH STAT if ($user_group_string != '11') { ?>
                    <?php

                    if ($order['order_values_7'] == '42'){

                    $s = "SELECT * FROM order_status WHERE order_status_id = '".$order['order_status_id'] . "'";

                    $order_status = mysql_fetch_assoc(mysql_query($s));

                    $thislist = array();

                    $thislist[$order_status['order_status_id']] = $order_status['order_status_name'];

                    $steps = explode(',',$order_status['order_status_steps']);

                    if ($order_status['order_status_id'] == '300') {

                    $thislist[30] = "Send to Lab";


                    } elseif (in_array($order['order_status_id'],array('180'))) {
                    $thislist[180] = 'Archived';
                    } else {
                    $text = array('next'=>'Send to','receive'=>'','hold'=>'Hold at','back'=>'Send back to');


                    foreach($steps as $step) {

                    $steparray = explode(':',$step);

                    $fulltext = $text[$steparray[1]];

                    $spn = "";
                    if ($steparray[1] == 'next' && in_array($steparray[0],array(150,170)) || $steparray[1] == 'receive') {
                    $fulltext =  $steparray[2];
                    $spn = ' style="line-height: 36px;" ';
                    } else {
                    $fulltext .= " " . $steparray[2];
                    }

                    $thislist[$steparray[0]] = $fulltext;

                    }
                    }

                    if (! in_array($order['order_status_id'],array('300','170','180')) && ($user_group_string == '1') ) {
                    $thislist[300] = 'Reject';
                    }
                    ksort($thislist);

                       }
                       else  {

							$s = "SELECT * FROM order_status_dm WHERE order_status_id = '".$order['order_status_id'] . "'";

							$order_status = mysql_fetch_assoc(mysql_query($s));

							$thislist = array();

							$thislist[$order_status['order_status_id']] = $order_status['order_status_name'];

							$steps = explode(',',$order_status['order_status_steps']);

							if ($order_status['order_status_id'] == '300') {

								$thislist[30] = "Send to Lab";


							} elseif (in_array($order['order_status_id'],array('180'))) {
								$thislist[180] = 'Archived';
							} else {
								$text = array('next'=>'Send to','receive'=>'','hold'=>'Hold at','back'=>'Send back to');


								foreach($steps as $step) {

									$steparray = explode(':',$step);

									$fulltext = $text[$steparray[1]];

									$spn = "";
									if ($steparray[1] == 'next' && in_array($steparray[0],array(150,170)) || $steparray[1] == 'receive') {
										$fulltext =  $steparray[2];
										$spn = ' style="line-height: 36px;" ';
									} else {
										$fulltext .= " " . $steparray[2];
									}

									$thislist[$steparray[0]] = $fulltext;

								}
							}

							if (! in_array($order['order_status_id'],array('300','170','180')) && ($user_group_string == '1') ) {
								$thislist[300] = 'Reject';
							}
							ksort($thislist);
                       }
                    //p($order_statuses);

                    if (isset($thislist)) {
                    if ($order['action'][0]['text'] != 'View') {
                    ?>  <select name="hc_order_status_id[<?php echo $order['order_id']; ?>]" id="changed_status_<?php echo $order['order_id'];?>" onchange="show_stat_button('hc_save_status',this,<?php echo $order['order_id']?>)" class="cl_stat_box">
                        <?php foreach ($thislist as $vid => $vkey) { ?>
						<?php // echo "vid " . $vid . "<br>"; echo "order status id" . $order['order_status_id'] . "<br>"; echo "vkey" . $vkey . "<br>"; ?>
                        <?php if ($vid == $order['order_status_id']) { ?>
                        <option value="<?php echo $vid; ?>" selected="selected"><?php echo $vkey; ?></option>
                        <?php } else { ?>
                        <option value="<?php echo $vid; ?>"><?php echo $vkey; ?></option>
                        <?php } ?>
                        <?php }
                        } else {
                        echo $order['order_status']; // if clinic is allowed to see only

                        }
                        } else {
                        echo "Archived";
                        }
                        ?>



                    </select>
                    <?php //ALSO CLINIC CAN CHANGE BATCH STAT } else { ?>
                    <?php //ALSO CLINIC CAN CHANGE BATCH STAT echo $order['order_status']; } ?></td>
                <td class="left"><?php echo $order['order_date_added'];// echo $this->hc_functions->ch_date($order['order_date_added']); ?></td>

                <!-- Mod Delivery Date -->
                <td class="left"<?php  if ($order['order_values_rush_75'] == "342") echo ' style="color:red; font-weight:bold;"';?>><?php
                    /* if (stristr("PendingProcessingPrebooking",$order['status'])){
                    ?>
                    <input type="text" value="<?php $this->mDate2Unix($order['order_deliverydate']); ?>" name="hc_delivery_date[<?php echo $order['order_id']; ?>]" class="date" size="12" onchange="show_stat_button('hc_save_delivery',this)" />

                    <div style="display:none;" id="hc_delivery_date_<?php echo $order['order_id']; ?>"><a onclick="send_ind_mail(<?php echo $order['order_id'];?>)" class="button"><span>Mail</span></a></div>

                    <?php
                    } else {  */

                    if ($order['order_values_rush_75'] == "342") {
					echo $order['order_date_needed'];

                    //echo $this->hc_functions->ch_date($order['order_date_needed']);
                    echo "&nbsp; R";
                    } else {
                    echo $order['order_deliverydate'];
                    //echo $this->hc_functions->ch_date($order['order_deliverydate']);
                    }
                    if ($order['order_deliverydate'] != $order['order_originaldelivery']) echo '<span style="color:red; font-weight:bold;"> C</span>';

                    //}
                    ?></td>
                <!-- End:Mod Delivery Date -->

                <?php /*?>            <td class="right"><?php echo $order['order_total']; ?></td>
                <?php */?>            <td class="center"><?php foreach ($order['action'] as $action) { ?>
                    <a href="<?php echo $action['href']; ?>" ><p></p><span><?php echo $action['text']; ?></span></a>
                    <?php } ?></td>
            </tr>
            <?php } ?>
            <?php } else { ?>
            <tr>
                <td class="center" colspan="9"><?php echo $text_no_results; ?></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
    <input type="hidden" value="" name="orderidx" id="orderidx" />
</form>
<div class="pagination"><?php echo $pagination; ?></div>
</div>
</div>

<script type="text/javascript">
    function filter() {
        url = 'index.php?route=sale/lab&token=<?php echo $token; ?>';

        console.log("test: " + '<?php echo $token; ?>');

        var filter_order_id = $('input[name=\'filter_order_id\']').attr('value');

        if (filter_order_id) {
            url += '&filter_order_id=' + encodeURIComponent(filter_order_id);
        }

        var filter_clinic_name = $('input[name=\'filter_clinic_name\']').attr('value');

        if (filter_clinic_name) {
        url += '&filter_clinic_name=' + encodeURIComponent(filter_clinic_name);
        }

        var filter_patient_name = $('input[name=\'filter_patient_name\']').attr('value');

        if (filter_patient_name) {
        url += '&filter_patient_name=' + encodeURIComponent(filter_patient_name);
        }

        var filter_status = $('select[name=\'filter_status\']').attr('value');

        if (filter_status != '*') {
        url += '&filter_status=' + encodeURIComponent(filter_status);
        }

        var filter_date_added = $('input[name=\'filter_date_added\']').attr('value');

        if (filter_date_added) {
        url += '&filter_date_added=' + encodeURIComponent(filter_date_added);
        }

        var filter_date_added_2 = $('input[name=\'filter_date_added_2\']').attr('value');

        if (filter_date_added_2) {
        url += '&filter_date_added_2=' + encodeURIComponent(filter_date_added_2);
        }

        var filter_deliverydate = $('input[name=\'filter_deliverydate\']').attr('value');

        if (filter_deliverydate) {
        url += '&filter_deliverydate=' + encodeURIComponent(filter_deliverydate);
        }

        var filter_deliverydate_2 = $('input[name=\'filter_deliverydate_2\']').attr('value');

        if (filter_deliverydate_2) {
        url += '&filter_deliverydate_2=' + encodeURIComponent(filter_deliverydate_2);
        }
        var filter_total = $('input[name=\'filter_total\']').attr('value');

        if (filter_total) {
        url += '&filter_total=' + encodeURIComponent(filter_total);
        }
        location = url;
    }

    function clear_filter() {
        $('input[name^=filter]').val('');
        url = 'index.php?route=sale/order&token=<?php echo $token; ?>';
        location=url;
    }

    function handlePackSlip(){
        $('#form').attr('action', '<?php echo $packingslip; ?>').attr('target', '_blank').submit();
    }

    // Added on 07/26/2018
    function filterByAdmin(){
        url = 'index.php?route=sale/order&token=<?php echo $token; ?>&adminfilter=1';
        var filter_total = $('input[name=\'filter_total\']').attr('value');
        if (filter_total) {
            url += '&filter_total=' + encodeURIComponent(filter_total);
        }
        location = url;
        console.log("token: " + '<?php echo $token; ?>');
    }
</script>
<script type="text/javascript" src="view/javascript/jquery/ui/ui.datepicker.js"></script>
<script type="text/javascript">
$('.date').datepicker({dateFormat: 'yy-mm-dd',changeMonth: true,changeYear: true});
</script>


<script type="text/javascript" src="view/javascript/hc_general.php"></script>

<script type="text/javascript" src="view/javascript/hc_order_list.js"></script>
<?php echo $footer; ?>
