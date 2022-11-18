<?php echo $header; ?>
<?php
/* this is test 
$ar = array('one'=>'1111','two'=>'2222');
$json = json_encode($ar);
print_r($json);
print_r(json_decode($json));
*/
//p(get_defined_vars(),__LINE__.__FILE__)

?>
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<?php
//p($patient_id . __FILE__);
$route = $this->request->isget('route'); ?>
     <form action="<?php echo str_replace('&', '&amp;', $action); ?>" method="post" enctype="multipart/form-data" id="form">
		<div class="h1float">
			<h1><?php echo ($route == 'sale/patient/insert'? 'Add':'Update')?> a <strong>Patient</strong></h1>
		</div>
        
    <div class="buttons"><a onclick="$('#form').submit();" class="button s"><p></p><span><strong>Save</strong> Patient</span></a> &nbsp;
    <a onclick="location = '<?php echo $cancel; ?>';" class="button c"><p></p><span><?php echo $button_cancel; ?></span></a></div>

    <div class="cl" style="height:7px;"></div>

    <div class="lefttabs" id="tabs">
    	<a tab="#tab_patient_info"><span>1</span>
        <div>Patient Info</div>
        </a> <a tab="#tab_address"><span>2</span>
        <div>Address</div>
        </a> </div>
    <div class="main">
        <div class="main-top"><!--<img class="bg_image" src="images/bg_middle_top.png" />--></div>
        <div class="main-mid" style="margin-left:-1px; overflow:visible; margin-right:-1px;">
            <div id="tab_patient_info" class="tabs">
                <h2>Please enter patient information.</h2>

                <fieldset>
                
                
                    <div class="divleft">
                    
                        <div class="ftitle">
                            <label>Ref #:</label>
                            <input type="text" name="patient_id" value="<?php echo $patient_id; ?>" disabled="disabled" />
                            <div class="error">&nbsp;</div>
                        </div>
                    
                        <div class="ftitle">

                            <label><span class="required">*</span> First Name:</label>
                            <input type="text" name="patient_firstname" id="patient_firstname" value="<?php echo $patient_firstname; ?>" />
                            <div class="error"><?php if ($error_patient_firstname) { ?><?php echo $error_patient_firstname; ?><?php } ?>&nbsp;</div>
                        </div>
                        
                        <div class="ftitle">
                            <label><span class="required">*</span> Last Name:</label>
                            <input type="text"  name="patient_lastname" id="patient_lastname" value="<?php echo $patient_lastname; ?>" />
                            <div class="error"><?php if ($error_patient_lastname) { ?><?php echo $error_patient_lastname; ?><?php } ?>&nbsp;</div>
                        </div>
                        
                        <div class="ftitle">
                         <label><!--<span class="required">*</span> -->Date of Birth: (yyyy-mm-dd)</label>
                            <input type="text"  class="date" 
                            		 name="patient_dob" id="patient_dob" value="<?php if($patient_dob != '0000-00-00')  echo $patient_dob; ?>"/>
                            <div class="error"><?php if ($error_patient_dob) { ?><?php echo $error_patient_dob; ?><?php } ?></div>
                        </div>
                        
                        <div class="ftitle">
                            <label>Telephone:</label>
                            <input type="text" name="patient_telephone" value="<?php echo $patient_telephone; ?>" />
                            <div class="error"><?php if ($error_patient_telephone) { ?><?php echo $error_patient_telephone; ?><?php } ?>&nbsp;</div>
                        </div>
                        
                        
                        <div class="ftitle">
                            <label><span class="required">*</span> Clinic:</label>
                            <select name="patient_clinic_id" onchange="get_clinicians('<?php echo $patient_clinician_id;?>'); <?php if ($patient_id == '') echo "return checkPatientData(document.getElementById('patient_firstname').value,document.getElementById('patient_lastname').value,document.getElementById('patient_dob').value,document.getElementById('patient_clinic_id').value)";?>" id="patient_clinic_id" >
                                <option value="">Select...</option>
                                    <?php foreach ($clinics as $cliniclist) { ?>
                                        <option value="<?php echo $cliniclist['clinic_id'];?>"<?php if ($cliniclist['clinic_id'] == $patient_clinic_id) echo ' selected="selected"'; ?>><?php echo $cliniclist['clinic_id'] . ' -- ' . $cliniclist['clinic_name'];?></option>
                                    <?php } ?>
                            </select>    
                            <div class="error"><?php if ($error_patient_clinic_id) { ?><?php echo $error_patient_clinic_id; ?><?php } ?>&nbsp;</div>
                        </div>
                        <div class="ftitle">

                            <label>Clinician:</label>
                            <select name="patient_clinician_id" id="patient_clinician_id">
                        <option value="<?php echo $patient_clinician_id;?>"></option>   
                            </select>    
                            <div class="error">&nbsp;</div>
                        </div>

                        
                        <div class="ftitle">
                            <label>Status:</label>
                            <select name="patient_status">
                                <?php if ($patient_status) { ?>
                                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                                <option value="0"><?php echo $text_disabled; ?></option>
                                <?php } else { ?>
                                <option value="1"><?php echo $text_enabled; ?></option>
                                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                                <?php } ?>
                            </select>
                            <div class="error">&nbsp;</div>
                        </div>
                        
                    </div>
                    <!-- EOF LEFT -->
                    
                    <div class="divright">
                        
                        <div class="ftitle">
                            <label>Gender:</label>
                        <?php 
                            
                            $s = "SELECT * FROM lookup_table LEFT JOIN lookup_table_types ON lookup_table_lookup_table_types_id = lookup_table_types_id WHERE lookup_table_lookup_table_types_id = 2 ORDER BY lookup_table_sort";
                            
                            echo $this->hc_functions->selectFromSql('patient_gender_id',$patient_gender_id,$s ,'lookup_table_id','lookup_table_title','style="width:auto;"'); // gender ?>
                            <div class="error">&nbsp;</div>
                        </div>
                        
                        <div class="ftitle">
                            <label>Patient Weight:</label>
                            
                            <input type="text" name="patient_weight" value="<?php echo $patient_weight; ?>" style="width:100px" />
                         
                            <?php 
                            $s = "SELECT * FROM lookup_table LEFT JOIN lookup_table_types ON lookup_table_lookup_table_types_id = lookup_table_types_id WHERE lookup_table_lookup_table_types_id = 1 ORDER BY lookup_table_sort";
                            
                            echo $this->hc_functions->selectFromSql('patient_weight_id',$patient_weight_id,$s ,'lookup_table_id','lookup_table_title','style="width:auto;"'); //  ?>
                            <div class="error">&nbsp;</div>
                         
                        </div>
                        
                        <div class="ftitle">
                            <label>Email:</label>
                            <input type="text" name="patient_email" value="<?php echo $patient_email; ?>" />
                            <div class="error">&nbsp;</div>
                        </div>
                        
                        <div class="ftitle">
                            <label>Fax:</label>
                            <input type="text" name="patient_fax" value="<?php echo $patient_fax; ?>" />
                            <div class="error">&nbsp;</div>
                        </div>
                        
                        <div class="ftitle">
                            <label>Company:</label>
                            <input type="text" name="patient_company" value="<?php echo $patient_company; ?>" />
                            <div class="error">&nbsp;</div>
                        </div>
                        <div class="ftitle">
                            <label>Code:</label>
                            <input type="text" name="patient_code" value="<?php echo $patient_code; ?>" />
                            <div class="error">&nbsp;</div>
                        </div>
                        <div class="ftitle">
                            <label>Notes:</label>
                            <textarea name="patient_notes" style="width:380px; height:100px;"><?php echo $patient_notes; ?></textarea>
                            <div class="error">&nbsp;</div>
                        </div>

                    </div>
                    <!-- EOF RIGHT -->

                </fieldset>
            </div>
            <div id="tab_address" class="tabs">
                <h2>Edit patient address.</h2>
                <fieldset>
                    <div class="divleft">


                        <div class="ftitle">
                            <label>Address 1:</label>
                            <input type="text" name="patient_address_1" value="<?php echo $patient_address_1; ?>" />
                            <div class="error">&nbsp;</div>
                        </div>
                        
                        <div class="ftitle">
                            <label>Address 2:</label>
                            <input type="text" name="patient_address_2" value="<?php echo $patient_address_2; ?>" />
                            <div class="error">&nbsp;</div>
                        </div>
                        
                        <div class="ftitle">
                            <label>City:</label>
                            <input type="text" name="patient_city" value="<?php echo $patient_city; ?>" />
                            <div class="error">&nbsp;</div>
                        </div>
                        
                        <div class="ftitle">
                            <label>Province:</label>
                            <select name="patient_province_id">
                            </select>
                            <div class="error">&nbsp;</div>
                        </div>                        
                        
                        <div class="ftitle">
                            <label>Postal Code:</label>
                            <input type="text" name="patient_postalcode" value="<?php echo $patient_postalcode; ?>" />
                            <div class="error">&nbsp;</div>
                        </div>
                        
                        <div class="ftitle">
                            <label>Country:</label>
                            <select name="patient_country_id" onchange="get_provinces(<?php echo $patient_province_id;?>);">
                            <option value="">Select...</option>
                        <?php
                        if (!$patient_country_id)  $patient_country_id= 38;
                        if (!$patient_province_id) $patient_province_id = 610;
                        ?>
                                          <?php foreach ($countries as $country) { ?>
                                          
                                          <?php if ($country['country_id'] == $patient_country_id) { ?>
                                          <option value="<?php echo $country['country_id']; ?>" selected="selected"><?php echo $country['name']; ?></option>
                                          <?php } else { ?>
                                          <option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>
                                          <?php } ?>
                                          <?php } ?>
                            </select>    
                            
                            <div class="error">&nbsp;</div>
                        
                        </div>         
         
                    </div>
                    <!-- EOF RIGHT -->
                    
                </fieldset>
            </div>
        </div>
        <!-- Main-mid -->
        <div class="main-bot"></div>
    </div>
    <!-- main end -->
	<input type="hidden" name="clinic_id" value="<?php echo $patient_clinic_id;?>" />
	<input type="hidden" name="patient_id" value="<?php echo $patient_id;?>" />
        
</form>

<script type="text/javascript" src="view/javascript/jquery/ui/ui.datepicker.js"></script>

<script type="text/javascript">
$(document).ready(function() {
	$( "#patient_dob" ).click(function() {
		$(this.value = '')
	});
	
	$("#patient_dob").focus(function() {
      $(this).val() == '';
    });
	$('.date').datepicker({dateFormat: 'yy-mm-dd',changeMonth: true,changeYear: true ,yearRange: '1900:2020'});
});
</script>

<?php
$json = "{|prg_id|:|patient_form|,|province_id|:|$patient_province_id|,|patient_clinician_id|:|$patient_clinician_id|}";
?>
<script type="text/javascript" src="view/javascript/hc_general.php?json=<?php echo $json;?>"></script>

<?php echo $footer; ?>