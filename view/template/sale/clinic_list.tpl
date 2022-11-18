<?php echo $header; ?>
<?php

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
<a onclick="location = '<?php echo $insert; ?>'" class="button a"><p></p><span><?php echo $button_add_clinic; ?></span></a> &nbsp;
<a onclick="$('form').attr('action', '<?php echo $delete; ?>'); $('form').submit();" class="button d"><p></p><span><?php echo $button_delete_clinic; ?></span></a></div>

<div style="clear:both"></div>

    <form action="" method="post" enctype="multipart/form-data" id="form">
    <input type="hidden" value="" name="clinicidx" id="clinicidx" />
      <table class="list">
        <thead>
          <tr>
            <td width="15" class="center"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked); do_sale_order(0);" /></td>
            <td class="left"><a href="<?php echo $sort_clinic_id; ?>"<?php echo ($sort == 'clinic_id'?' class="'. strtolower($order).'"':''); ?>><?php echo $column_acct; ?></a></td>

             <td class="left"><a href="<?php echo $sort_clinic_name; ?>"<?php echo ($sort == 'clinic_name'?' class="'. strtolower($order).'"':''); ?>><?php echo $column_name; ?></a></td>

             <td class="left"><a href="<?php echo $sort_clinic_contact; ?>"<?php echo ($sort == 'clinic_contact'?' class="'. strtolower($order).'"':''); ?>><?php echo $column_contact; ?></a></td>

             <td class="left"><a href="<?php echo $sort_clinic_telephone; ?>"<?php echo ($sort == 'clinic_telephone'?' class="'. strtolower($order).'"':''); ?>><?php echo $column_phone; ?></a></td>

             <td class="left"><a href="<?php echo $sort_clinic_email; ?>"<?php echo ($sort == 'clinic_contact_email'?' class="'. strtolower($order).'"':''); ?>><?php echo $column_email; ?></a></td>

            <td class="left"><?php if ($sort == 'clinic_status') { ?>
              <a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_status; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_status; ?>"><?php echo $column_status; ?></a>
              <?php } ?></td>

            <td class="left"><?php if ($sort == 'clinic_date_added') { ?>
              <a href="<?php echo $sort_date_added; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_date_added; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_date_added; ?>"><?php echo $column_date_added; ?></a>
              <?php } ?></td>
            <td class="center" width="70"><?php echo $column_action; ?></td>
          </tr>
        </thead>
        <tbody>
          <tr class="filter">
            <td><input type="submit" style="display:none;" /></td>
            <td><input type="text" name="filter_clinic_id" value="<?php echo $filter_clinic_id; ?>" /></td>
            <td><input type="text" name="filter_clinic_name" value="<?php echo $filter_clinic_name; ?>" /></td>
            <td><input type="text" name="filter_contact_name" value="<?php echo $filter_contact_name; ?>" /></td>
            <td><input type="text" name="filter_contact_phone" value="<?php echo $filter_contact_phone; ?>" /></td>
            <td><input type="text" name="filter_contact_email" value="<?php echo $filter_contact_email; ?>" /></td>

            <td><select name="filter_status">
                <option value="*"></option>
                <?php if ($filter_status) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <?php } ?>
                <?php if (!is_null($filter_status) && !$filter_status) { ?>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select></td>

            <td><input type="text" name="filter_date_added" value="<?php echo $this->hc_functions->ch_date($filter_date_added); ?>" size="12" id="date" /></td>
            <td align="center"><a onclick="filter();" class="button f"><p></p><span><strong>Filter</strong></span></a></td>
          </tr>
          <?php if ($clinics) { ?>
          <?php foreach ($clinics as $clinic) { ?>
          <tr>
            <td class="center">
              <input type="checkbox" name="selected[]" value="<?php echo $clinic['clinic_id']; ?>" <?php if ($clinic['selected']) { echo 'checked="checked"'; } ?> onclick="do_sale_order();" /></td>
            <td class="left"><?=$clinic['clinic_id']?></td>
            <td class="left"><?php echo $clinic['clinic_name']; ?></td>
            <td class="left"><?php echo $clinic['clinic_contact']; ?></td>
            <td class="left"><?php echo $clinic['clinic_telephone']; ?></td>
            <td class="left"><?php echo $clinic['clinic_contact_email']; ?></td>
            <td class="left"><?php echo $clinic['clinic_status']; ?></td>
            <td class="left"><?php echo $this->hc_functions->ch_date($clinic['clinic_date_added']); ?></td>
            <td class="center edit"><?php foreach ($clinic['action'] as $action) { ?>
              <a href="<?php echo $action['href']; ?>" class="button e"><p></p><span><?php echo $action['text']; ?></span></a>
              <?php } ?></td>
          </tr>
          <?php } ?>
          <?php } else { ?>
          <tr>
            <td class="center" colspan="11"><?php echo $text_no_results; ?></td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
    </form>
<div class="pagination"><?php echo $pagination; ?></div>

<script type="text/javascript"><!--
function filter() {

	url = 'index.php?route=sale/clinic&token=<?php echo $token; ?>';

	var filter_clinic_id = $('input[name=\'filter_clinic_id\']').attr('value');
	if (filter_clinic_id) {
		url += '&filter_clinic_id=' + encodeURIComponent(filter_clinic_id);
	}

	var filter_clinic_name = $('input[name=\'filter_clinic_name\']').attr('value');
	if (filter_clinic_name) {
		url += '&filter_clinic_name=' + encodeURIComponent(filter_clinic_name);
	}

	var filter_contact_name = $('input[name=\'filter_contact_name\']').attr('value');
	if (filter_contact_name) {
		url += '&filter_contact_name=' + encodeURIComponent(filter_contact_name);
	}

	var filter_contact_email = $('input[name=\'filter_contact_email\']').attr('value');
	if (filter_contact_email) {
		url += '&filter_contact_email=' + encodeURIComponent(filter_contact_email);
	}

	var filter_contact_phone = $('input[name=\'filter_contact_phone\']').attr('value');
	if (filter_contact_phone) {
		url += '&filter_contact_phone=' + encodeURIComponent(filter_contact_phone);
	}

	var filter_status = $('select[name=\'filter_status\']').attr('value');
	if (filter_status != '*') {
		url += '&filter_status=' + encodeURIComponent(filter_status);
	}

	var filter_date_added = $('input[name=\'filter_date_added\']').attr('value');
	if (filter_date_added) {
		url += '&filter_date_added=' + encodeURIComponent(ch_date(filter_date_added));
	}
	location = url;
}
//--></script>
<script type="text/javascript" src="view/javascript/jquery/ui/ui.datepicker.js"></script>
<script type="text/javascript"><!--
$(document).ready(function() {
	$('#date').datepicker({dateFormat: 'yy-M-dd',changeMonth: true,changeYear: true});
});

function do_sale_order(){

	no_of_selected = $("input[name=selected[]]:checked").length;

	if (no_of_selected != 1) {
		$("#hc_create_order").hide();

		return false;
	}
<?php if (in_array($user_group_string,array(1,11))) { ?>

	$("#hc_create_order").show();

	$('#clinicidx').val($("input[name=selected[]]:checked").val());

	$.ajax({
		type: 'post',
		url: 'hc_scripts/create_sale_order.php',
		dataType: 'html',
		data: $('#form').serialize(),
		success: function (html) {
//				document.location.href='orderpage.php';
//				$('#hc_delivery_date_' + orderid).css('display','none');
		},
		complete: function () {
//				alert('done');
		}
	});
<?php } ?>
}

//--></script>
<script type="text/javascript" src="view/javascript/hc_general.php"></script>
<?php echo $footer; ?>
