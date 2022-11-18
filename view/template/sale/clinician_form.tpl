<?php echo $header; ?>
<?php
$user_id = (isset($_SESSION['user_id'])?$_SESSION['user_id']:false);
$patient_id = (isset($_GET['patient_id'])?$_GET['patient_id']:false);

//$s = "SELECT * FROM user_hc_patient WHERE user_id = '$user_id' AND patient_id = '$patient_id'";

$user_group = $this->db->query("SELECT * FROM user WHERE user_id = '$user_id'");
/*$qr = $this->db->query($s);
if (! $qr->num_rows && $user_group->row['user_group_string'] != 1) {
	$error_warning = "You are not permitted to see this patient";
	echo "<div class='warning'>$error_warning</div>";
	exit();
}
*/
?>
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<?php
$route = $this->request->isget('route'); ?>
		<div class="h1float">
			<h1><?php echo ($route == 'sale/clinician/insert'? 'Add':'Update')?> a <strong>Clinician</strong></h1>
		</div>
        
    <div class="buttons"><a onclick="$('#form').submit();" class="button s"><p></p><span><strong>Save</strong> Clinician</span></a> &nbsp; 
    <a onclick="location = '<?php echo $cancel; ?>';" class="button c"><p></p><span><?php echo $button_cancel; ?></span></a></div>

<div class="formdiv">

    <div class="top"><img src="images/bg_middle_top.png" /></div>
    <div class="mid" style="padding: 20px; 0">

      <form action="<?php echo str_replace('&', '&amp;', $action); ?>" method="post" enctype="multipart/form-data" id="form">

<div class="ftitle">
	<label><span class="required">*</span> Clinician Name:</label>
	<input type="text" name="clinician_name" value="<?php echo $clinician_name; ?>" />
	<div class="error"><?php if ($error_clinician_name) { ?><?php echo $error_clinician_name; ?><?php } ?>&nbsp;</div>
</div>

<div class="ftitle">
  <label><span class="required">*</span> Telephone:</label>
	<input type="text" name="clinician_telephone" value="<?php echo $clinician_telephone; ?>" />
	<div class="error"><?php echo $error_clinician_telephone; ?>&nbsp;</div>
</div>


<div class="ftitle">
	<label>Email:</label>
	<input type="text" name="clinician_email" value="<?php echo $clinician_email; ?>" />
	<div class="error"><?php echo $error_clinician_email; ?>&nbsp;</div>
</div>
<?php
if ($this->request->isget('cid')) $clinician_clinic_id = $this->request->isget('cid');
?>


<div class="ftitle">
	<label><span class="required">*</span> Clinic:</label>
	<select name="clinician_clinic_id">
    	<option value="">Select...</option>
        	<?php foreach ($clinics as $cliniclist) { ?>
				<option value="<?php echo $cliniclist['clinic_id'];?>"<?php if ($cliniclist['clinic_id'] == $clinician_clinic_id) echo ' selected="selected"'; ?>><?php echo $cliniclist['clinic_name'].' ( '.$cliniclist['clinic_id'].' ) ' ;?></option>
            <?php } ?>
    </select>    
	<div class="error"><?php if ($error_clinician_clinic_id) { ?><?php echo $error_clinician_clinic_id; ?><?php } ?>&nbsp;</div>
</div>

<div class="ftitle">
	<label>Notes:</label>
	<textarea name="clinician_notes" style="width:380px; height:100px;"><?php echo $clinician_notes; ?></textarea>
	<div class="error">&nbsp;</div>
</div>

<div class="ftitle">
	<label>Status:</label>
	<select name="clinician_status">
		<?php if ($clinician_status) { ?>
        <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
        <option value="0"><?php echo $text_disabled; ?></option>
        <?php } else { ?>
        <option value="1"><?php echo $text_enabled; ?></option>
        <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
        <?php } ?>
    </select>
    <div class="error">&nbsp;</div>
</div>

      </form>
<div style="clear:both"></div>
</div>
			<div class="bot"><img src="images/bg_middle_bot.png" /></div>
			</div>

<script type="text/javascript"><!--
$.tabs('.vtabs a');
$('form input[type=radio]').live('click', function () {
	$('form input[type=radio]').attr('checked', false);
	$(this).attr('checked', true);
});


//--></script> 
<?php echo $footer; ?>