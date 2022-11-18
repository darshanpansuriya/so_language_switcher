<?php echo $header; ?>
<?php

$user_name = $this->user->getUserName();
$user_group_string = $this->user->getUGstr();

//$this->hc_functions->testx();

//print_r($a);
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
<a onclick="location = '<?php echo $insert; ?>'" class="button a"><p></p><span><?php echo $button_add_patient; ?></span></a> &nbsp;
<a onclick="$('form').attr('action', '<?php echo $delete; ?>'); $('form').submit();" class="button d"><p></p><span><?php echo $button_delete_patient; ?></span></a></div>
<div style="clear:both"></div>

<form action="" method="post" enctype="multipart/form-data" id="form">
    <input type="hidden" value="" name="patientidx" id="patientidx" />

  <table class="list">
    <thead>
      <tr>
        <td width="15" class="center"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked); do_sale_order(0);" /></td>
        <td class="left"><a href="<?php echo $sort_refno; ?>"<?php if ($sort == 'refno') { ?> class="<?php echo strtolower($order); ?>"<?php } ?>><?php echo $column_ref; ?></a></td>

        <td class="left"><a href="<?php echo $sort_fname; ?>"<?php if ($sort == 'fname') { ?> class="<?php echo strtolower($order); ?>"<?php } ?>><?php echo $column_first_name; ?></a></td>

        <td class="left"><a href="<?php echo $sort_lname; ?>"<?php if ($sort == 'lname') { ?> class="<?php echo strtolower($order); ?>"<?php } ?>><?php echo $column_last_name; ?></a></td>

        <td class="left"><a href="<?php echo $sort_dob; ?>"<?php if ($sort == 'dob') { ?> class="<?php echo strtolower($order); ?>"<?php } ?>><?php echo $column_dob; ?></a></td>

        <td class="left"><a href="<?php echo $sort_patient_email; ?>"<?php if ($sort == 'patient_email') { ?> class="<?php echo strtolower($order); ?>"<?php } ?>><?php echo $column_email; ?></a></td>

        <td class="left"><?php echo $column_phone; ?></td>

         <td class="left"><a href="<?php echo $sort_patient_clinic; ?>"<?php if ($sort == 'patient_clinic') { ?> class="<?php echo strtolower($order); ?>"<?php } ?>><?php echo $column_clinic; ?></a></td>

        <td class="left"><a href="<?php echo $sort_patient_status; ?>"<?php if ($sort == 'patient_status') { ?> class="<?php echo strtolower($order); ?>"<?php } ?>><?php echo $column_status; ?></a></td>

        <td class="left"><a href="<?php echo $sort_patient_date_added; ?>"<?php if ($sort == 'patient_date_added') { ?> class="<?php echo strtolower($order); ?>"<?php } ?>><?php echo $column_date_added; ?></a></td>

        <td class="center" style="width:70px;"><?php echo $column_action; ?></td>
      </tr>
    </thead>
    <tbody>
      <tr class="filter">
        <td><input type="submit" style="display:none;" /></td>
        <td class="left"><input type="text" name="filter_refno" value="<?php echo $filter_refno; ?>" style="width:50px;" /></td>

        <td><input type="text" name="filter_fname" value="<?php echo $filter_fname; ?>" /></td>
        <td><input type="text" name="filter_lname" value="<?php echo $filter_lname; ?>" /></td>
        <td><input type="text" name="filter_dob" value="<?php echo $filter_dob; ?>" size="12" id="dateob" class="date" /></td>
        <td><input type="text" name="filter_patient_email" value="<?php echo $filter_patient_email; ?>" /></td>
        <td><input type="text" name="filter_phone" value="<?php echo $filter_phone; ?>" /></td>

        <td><input type="text" name="filter_clinic" value="<?php echo $filter_clinic; ?>" /></td>


        <td><select name="filter_patient_status">
              <option value="*"></option>
              <?php if ($filter_patient_status) { ?>
              <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
              <?php } else { ?>
              <option value="1"><?php echo $text_enabled; ?></option>
              <?php } ?>
              <?php if (!is_null($filter_patient_status) && !$filter_patient_status) { ?>
              <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
              <?php } else { ?>
              <option value="0"><?php echo $text_disabled; ?></option>
              <?php } ?>
          </select></td>

        <td><input type="text" name="filter_date" value="<?php echo $this->hc_functions->ch_date($filter_date); ?>" size="12" id="dateadded" class="date" /></td>
        <td align="center"><a onclick="filter();" class="button f"><p></p><span><strong>Filter</strong></span></a></td>
      </tr>
      <?php if ($patients) { ?>
      <?php foreach ($patients as $patient) { ?>
      <tr>
        <td class="center">
          <input type="checkbox" name="selected[]" value="<?php echo $patient['patient_id']; ?>" <?php if ($patient['selected']) { echo 'checked="checked"'; } ?> onclick="do_sale_order();" /></td>
        <td class="right"><?php echo $patient['patient_id']?></td>
        <td class="left"><?php echo $patient['patient_firstname']; ?></td>
        <td class="left"><?php echo $patient['patient_lastname']; ?></td>
        <td class="left"><?php if($patient['patient_dob'] != '0000-00-00' && $patient['patient_dob'] != '1900-01-01') echo $patient['patient_dob']; ?></td>
        <td class="left"><?php echo $patient['patient_email']; ?></td>
        <td class="left"><?php echo $patient['patient_telephone']; ?></td>
        <td class="left"><?php echo $patient['patient_clinic_name']; ?></td>
        <td class="left"><?php echo $patient['patient_status']; ?></td>
        <td class="left"><?php echo $this->hc_functions->ch_date($patient['patient_date_added']); ?></td>
        <td class="center edit"><?php foreach ($patient['action'] as $action) { ?>
          <a href="<?php echo $action['href']; ?>" class="button e"><p></p><span>Edit</span></a>
          <?php } ?></td>
      </tr>
      <?php } ?>
      <?php } else { ?>
      <tr>
        <td class="center" colspan="10"><?php echo $text_no_results; ?></td>
      </tr>
      <?php } ?>
    </tbody>
  </table>

</form>

<div class="pagination"><?php echo $pagination; ?></div>

<script type="text/javascript"><!--
function filter() {
	url = 'index.php?route=sale/patient&token=<?php echo $token; ?>';

	var filter_refno = $('input[name=\'filter_refno\']').val();
	if (filter_refno) {
		url += '&filter_refno=' + encodeURIComponent(filter_refno);
	}

	var filter_fname = $('input[name=\'filter_fname\']').val();
	if (filter_fname) {
		url += '&filter_fname=' + encodeURIComponent(filter_fname);
	}

	var filter_lname = $('input[name=\'filter_lname\']').val();
	if (filter_lname) {
		url += '&filter_lname=' + encodeURIComponent(filter_lname);
	}

	var filter_dob = $('input[name=\'filter_dob\']').val();

	if (filter_dob) {
		url += '&filter_dob=' + encodeURIComponent(ch_date(filter_dob));
	}

	var filter_patient_email = $('input[name=\'filter_patient_email\']').attr('value');
	if (filter_patient_email) {
		url += '&filter_patient_email=' + encodeURIComponent(filter_patient_email);
	}

	var filter_phone = $('input[name=\'filter_phone\']').attr('value');
	if (filter_phone) {
		url += '&filter_phone=' + encodeURIComponent(filter_phone);
	}

	var filter_clinic = $('input[name=\'filter_clinic\']').attr('value');
	if (filter_clinic) {
		url += '&filter_clinic=' + encodeURIComponent(filter_clinic);
	}

	var filter_patient_status = $('select[name=\'filter_patient_status\']').attr('value');

	if (filter_patient_status != '*') {
		url += '&filter_patient_status=' + encodeURIComponent(filter_patient_status);
	}

	var filter_date = $('input[name=\'filter_date\']').val();

	if (filter_date) {
		url += '&filter_date=' + encodeURIComponent(ch_date(filter_date));
	}
	location = url;
}
//--></script>
<script type="text/javascript" src="view/javascript/jquery/ui/ui.datepicker.js"></script>
<script type="text/javascript" src="view/javascript/hc_general.php"></script>

<?php echo $footer; ?>
