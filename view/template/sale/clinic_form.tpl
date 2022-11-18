<?php echo $header; ?>
<?php
$user_id = $this->session->data['user_id'];

$clinic_id = $this->request->isget('clinic_id');

$s = "SELECT * FROM user_to_clinic WHERE user_to_clinic_user_id = '$user_id' AND user_to_clinic_clinic_id = '$clinic_id'";

$user_group = $this->db->query("SELECT * FROM user WHERE user_id = '$user_id'");
$qr = $this->db->query($s);
if (! $qr->num_rows && $user_group->row['user_group_string'] != '1') {
	$error_warning = "You are not permitted to see this clinic";
	echo "<div class='warning'>$error_warning</div>";
	exit();
}

?>
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<?php if (isset($this->session->data['success']) && $this->session->data['success']) { ?>
<div class="success"><?php echo $this->session->data['success']; $this->session->data['success'] = ''; ?></div>
<?php } ?>

<?php
$route = $this->request->isget('route'); ?>

     <form action="<?php echo str_replace('&', '&amp;', $action); ?>" method="post" enctype="multipart/form-data" id="form">
<input type="hidden" name="add_clinician_f" id="add_clinician_f" value="" />
<input type="hidden" id="where_am_i" value="" name="where_am_i" />
		<div class="h1float">
			<h1><?php echo ($route == 'sale/clinic/insert'? 'Add':'Update')?> a <strong>Clinic</strong></h1>
		</div>

    <div class="buttons">
    <a onclick="submit_add_clinician();"  class="button a"><p></p><span>Add <strong>Clinician</strong></span></a> &nbsp;

    <a onclick="$('#form').submit();" class="button s"><p></p><span><strong>Save</strong> Clinic</span></a> &nbsp; 
    <a onclick="location = '<?php echo $cancel; ?>';" class="button c"><p></p><span><?php echo $button_cancel; ?></span></a></div>

    <div class="cl" style="height:7px;"></div>

    <div class="lefttabs" id="tabs">
    	<a tab="#tab_clinic_info" id="a_clinic_info" onclick="do_clinics_tabs(this);"><span>1</span>
        <div>Clinic Info</div>
        </a> <a tab="#tab_addresses" id="a_addresses" onclick="do_clinics_tabs(this);"><span>2</span>
        <div>Addresses</div>
        </a> <a tab="#tab_defaults" id="a_defaults" onclick="do_clinics_tabs(this);"><span>3</span>
        <div>Defaults</div></a>
        <?php if (! $qr->num_rows && $user_group->row['user_group_string'] == '1') { ?>
        <a tab="#tab_pricing" id="a_pricing" onclick="do_clinics_tabs(this);"><span>4</span>
        <div>Pricing</div></a>
        <?php }?>
        </div>
    <div class="main">
        <div class="main-top"><!--<img class="bg_image" src="images/bg_middle_top.png" />--></div>
        <div class="main-mid" style="margin-left:-1px; overflow:visible; margin-right:-1px;">
            <div id="tab_clinic_info" class="tabs">
                <h2>Please enter clinic information.</h2>

                <fieldset>
                    <div class="divleft">
                        <div class="ftitle">
                            <label>Account #:</label>
                            <input type="text" name="clinic_id" id="clinic_id" value="<?php echo $clinic_id; ?>" readonly="readonly" />
                            <div class="error">&nbsp;</div>
                        </div>
                        
                        <div class="ftitle">
                            <label><span class="required">*</span> Company Name:</label>
                            <input type="text" class="" name="clinic_name" value="<?php echo $clinic_name; ?>" />
                            <div class="error"><?php echo $error_clinic_name; ?>&nbsp;</div>
                        </div>
                        
                        <div class="ftitle">
                            <label><span class="required">*</span> Contact Name:</label>
                            <input type="text" class="" name="clinic_contact" value="<?php echo $clinic_contact; ?>" />
                            <div class="error"><?php echo $error_clinic_contact; ?>&nbsp;</div>
                        </div>
                        
                        <div class="ftitle">
                          <label><span class="required">*</span> Contact Email:</label>
                            <input type="text" name="clinic_contact_email" value="<?php echo $clinic_contact_email; ?>" />
                            <div class="error"><?php echo $error_clinic_contact_email; ?>&nbsp;</div>
                        </div>
                        
                        <div class="ftitle">
                          <label><span class="required">*</span> Telephone:</label>
                            <input type="text" class="phone_fields" name="clinic_telephone" value="<?php echo $clinic_telephone; ?>" />
                            <div class="error"><?php echo $error_clinic_telephone; ?>&nbsp;</div>
                        </div>

                    </div>
                    <!-- EOF LEFT -->
                    
                    <div class="divright">
                        <div class="ftitle">
                          <label>Fax:</label>
                            <input type="text" class="phone_fields" name="clinic_fax" value="<?php echo $clinic_fax; ?>" />
                            <div class="error">&nbsp;</div>
                        </div>
                        
                        <div class="ftitle">
                          <label>Clinic Code:</label>
                            <input type="text" name="clinic_code" value="<?php echo $clinic_code; ?>" />
                            <div class="error">&nbsp;</div>
                        </div>
                        
                        <div class="ftitle">
                          <label>Default Clinician:</label>
                            <select name="clinic_default_clinician">
                            <option value=""></option>
                            <?php foreach($clinicians as $clinician) { ?>
                              <option value="<?php echo $clinician['clinician_id']?>"<?php if ($clinician['clinician_id'] == $clinic_default_clinician) echo ' selected="selected"';?>><?php echo $clinician['clinician_name'];?></option>
                            <?php } ?>  
                            </select>
                            <div class="error">&nbsp;</div>
                        </div>
                        
                        
                        <div class="ftitle">
                          <label>Status:</label>
                            <select name="clinic_status">
                              <?php if ($clinic_status) { ?>
                              <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                              <option value="0"><?php echo $text_disabled; ?></option>
                              <?php } else { ?>
                              <option value="1"><?php echo $text_enabled; ?></option>
                              <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                              <?php } ?>
                            </select>
                            <div class="error">&nbsp;</div>
                        </div>
                        
                        <div class="ftitle">
                          <label>Clinic Notes:</label>
                            <textarea name="clinic_notes" style="height:100px;"><?php echo $clinic_notes; ?></textarea>
                            <div class="error">&nbsp;</div>
                        </div>
                    </div>
                    <!-- EOF RIGHT -->
                    
                </fieldset>
            </div>
            <div id="tab_addresses" class="tabs">
                <h2>Add/Edit clinic addresses.</h2>
                <fieldset>
                    <div class="divleft-address">

                        <div class="ftitle"><label><a href="javascript:;" onclick="add_address();">[ Add ]</a></label></div>
                        <div style="clear:both; margin-top:-20px;"></div>
                        <?php 
                            $address_row = 1; 
                        ?>
                            <?php foreach ($addresses as $address) { ?>
                        
                        <div class="address-ftitle" id="faddress_<?php echo $address_row;?>">
                            <!--<textarea name="clinic_address[<?php echo $address_row;?>]" style="width:380px; float:left; height:100px;"><?php echo $address['clinic_address_address'] ?></textarea>-->
                            <?php
                                    $address_array = explode(',',$address['clinic_address_address']);
                                    $format_address = isset($address_array[0]) ? $address_array[0] :'';
                                    $format_street = isset($address_array[1]) ? $address_array[1] :'';
                                    $format_city = isset($address_array[2]) ? $address_array[2] :'';
                                    $format_province = isset($address_array[3]) ? $address_array[3] :'';
                                    $format_postal_code = isset($address_array[4]) ? $address_array[4] :'';
                                ?>
                                <div style="width:100%; float:left; height:100px; margin-bottom: 20px">
                                    <input type="text" placeholder="Address" name="clinic_address[<?php echo $address_row;?>][format_address]" id="format_address" value="<?=trim($format_address)?>"/> <br/>
                                    <input type="text" placeholder="Street" name="clinic_address[<?php echo $address_row;?>][format_street]" id="format_street"  value="<?=trim($format_street)?>"/> <br/>
                                    <input type="text" placeholder="City"  name="clinic_address[<?php echo $address_row;?>][format_city]"  id="format_city" value="<?=trim($format_city)?>"/> <br/>
                                    <input type="text" placeholder="Province"  name="clinic_address[<?php echo $address_row;?>][format_province]"  id="format_province" value="<?=trim($format_province)?>"/> <br/>
                                    <input type="text" placeholder="postal code" name="clinic_address[<?php echo $address_row;?>][format_postal_code]"  id="format_postal_code" value="<?=trim($format_postal_code)?>">
                                </div>
                            <!--<div class="address-guide">
		                       <strong>Please maintain the following format:</strong>
		                        <label class="guide-label">Clinic Name, Street Address, City, State, Post Code </label>
                        	</div>-->
                        	<div class="add-radio">
	                            <label class="ftl"><input type="radio" value="<?php echo $address_row;?>" name="default" <?php if ($address['default']) echo 'checked="checked"';?> />Default</label> &nbsp; 
	                            <label class="ftl"><input type="radio" value="<?php echo $address_row;?>" name="shipping" id="default_shipping"  <?php if ($address['shipping']) echo 'checked="checked"';?>  />Default Shipping Address</label> &nbsp;
	                            <a href="javascript:;" onclick="remove_address(<?php echo $address_row;?>);" style="float:right;">[ Remove ]</a> 
	                        </div>  
	                        
                        	<div class="error">&nbsp;</div>
                        </div>
                       
                                <?php $address_row++; ?>
                            <?php } ?>
                        <div id="add_address"></div>
                    </div>
                    <!-- EOF RIGHT -->
                    
                </fieldset>
            </div>
            <div id="tab_defaults" class="tabs">
                <h2>Select defaults for the clinic.</h2>
                <fieldset>
                    <div class="divleft" id="clinic_defaults">
<?php 
//p($clinic_orthotic_labels,__LINE__);
?>
                        <div class="ftitle" >
                          <label>Orthotic Labels:</label>
                            <label class="ftl"><input type="radio" value="Y" name="clinic_orthotic_labels" <?php if ($clinic_orthotic_labels  != "N" ) echo 'checked="checked"';?> />Yes</label> &nbsp; 
                            <label class="ftl"><input type="radio" value="N" name="clinic_orthotic_labels" <?php if ($clinic_orthotic_labels =="N") echo 'checked="checked"';?> />No</label> &nbsp; 
                            <div class="error">&nbsp;</div>
                        </div>
                        <div class="ftitle" id="ortho-label-name" >
<?php
$r = $this->db->query("SELECT * FROM lookup_table LEFT JOIN lookup_table_types ON lookup_table_types_id = lookup_table_lookup_table_types_id WHERE lookup_table_types_id = 84 ORDER BY lookup_table_sort");
$sel = ' selected="selected" ';
?>
<label><?php echo $r->row['lookup_table_types_name'];?>: <?php echo $clinic_orthotic_label_name;?></label>
<select name="clinic_orthotic_label_name" id="clinic_orthotic_label_name" onchange="displayButtons();" >
<option value="">Select..</option>
<?php foreach ($r->rows as $labelnames) { ?>
<option value="<?php echo $labelnames['lookup_table_id'];?>"<?php if ($labelnames['lookup_table_id'] == $clinic_orthotic_label_name) echo $sel;?>><?php echo $labelnames['lookup_table_title'];?></option>
<?php } ?>
</select>
                             <div class="error">&nbsp;</div> 
                       
                       </div>
                       
                       
                       <div class="ftitle" id="new-orthotic-container" style="display: none;">
                       
	                       <div class="new-orthotic-label">
	                       		<label>New Orthotic Label Name:</label>
	                       </div>
		                       <div class="new-orthotic-input">
		                       		<input type="text" name="clinic_new_label_name" id="clinic_new_label_name" />
		                       </div>
                       </div>
                       
                                       
                      

                        <div class="ftitle">
<?php
$r = $this->db->query("SELECT * FROM lookup_table LEFT JOIN lookup_table_types ON lookup_table_types_id = lookup_table_lookup_table_types_id WHERE lookup_table_types_id = 85 ORDER BY lookup_table_sort");
$sel = ' selected="selected" ';
?>
<label><?php echo $r->row['lookup_table_types_name'];?>:</label>
<select name="clinic_orthotic_printer" id="clinic_orthotic_printer" >
<?php foreach ($r->rows as $labelnames) { ?>
<option value="<?php echo $labelnames['lookup_table_id'];?>"<?php if ($labelnames['lookup_table_id'] == $clinic_orthotic_printer) echo $sel;?>><?php echo $labelnames['lookup_table_title'];?></option>
<?php } ?>
</select>
                            <div class="error">&nbsp;</div>
                        </div>


                        
                        <div class="ftitle">
						<label>Casting Method:</label>						
                        <select name="clinic_casting_method" id = "clinic_casting_method">
<?php

		$grtable = $this->db->query("SELECT * FROM lookup_table_types WHERE lookup_table_types_id = '8'");
		echo "<label>".$grtable->row['lookup_table_types_name']."</label>";
		
		$fields = $this->db->query("SELECT * FROM lookup_table WHERE lookup_table_lookup_table_types_id = '8' ORDER BY lookup_table_sort");
		
		if ($fields->num_rows) {
			foreach($fields->rows as $line) {
				echo '<option value="'.$line['lookup_table_id'].'"';
				if ($clinic_casting_method == $line['lookup_table_id']) 
					echo ' selected="selected"';
				echo '>' . $line['lookup_table_title'] . '</option>';
			}
		}
?>				
</select>

                           
                            <div class="error">&nbsp;</div>
                        </div>
                        
                        <div class="ftitle">
                          <label>Scan:</label>
                            <label class="ftl"><input type="radio" value="Y" name="clinic_scan" <?php if ($clinic_scan != "N" ) echo 'checked="checked"';?> />Yes</label> &nbsp; 
                            <label class="ftl"><input type="radio" value="N" name="clinic_scan" <?php if ($clinic_scan=="N") echo 'checked="checked"';?> />No</label> &nbsp; 
                            <div class="error">&nbsp;</div>
                        </div>

                    </div>
                    
                    
                    
                    <div class="add-new-button" id="add-new-button">                       
                       		<a onclick="showNewOrtho()" class="button a"><p></p><span><strong>Add New</strong></span></a>
                    </div>  
                    
                    <div class="save-new-button" id="save-new-button" style="display: none;">
                    	<a class="button s" onclick="addNewOrthoLabel(document.getElementById('clinic_new_label_name').value);"><p></p><span><strong>Save</strong></span></a>
                    </div>    
                   
                    <div class="rename-new-button" id="rename-new-button" style="display: none;">
                    	<a class="button e" onclick="renameNewOrtho();"><p></p><span>Rename</span></a>
                    </div>
                    
                    <div class="rename-new-button" id="delete-new-button" style="display: none;">
                    	<a class="button d" onclick="deleteClick(); deleteOrtho(document.getElementById('clinic_orthotic_label_name').value);"><p></p><span>Delete</span></a>
                    </div>
                    
                     <div class="update-new-button" id="update-new-button" style="display: none;">
                    	<a class="button s" onclick="updateOrthoLabel(document.getElementById('clinic_orthotic_label_name').value,document.getElementById('clinic_new_label_name').value);"><p></p><span><strong>Update</strong></span></a>
                    </div>
                    <div class="response-div" id="response-div">
                    
                    </div>
                    <!-- EOF RIGHT -->
                    
                </fieldset>
            </div>
       <?php if (! $qr->num_rows && $user_group->row['user_group_string'] == '1') { ?>
            <div id="tab_pricing" class="tabs">
            <?php if($clinic_id != ''){ ?>
            		<div class="buttons">
						 <a class="button s" onclick="restoreItemPricingDefaults(document.getElementById('clinic_id').value);"><p></p><span><strong>Load</strong> Defaults</span></a>
					</div>						
					<?php } ?>					
                <h2>Enter pricing parameters</h2>
                <?php 
                $defaults_query = "SELECT * FROM clinic_items_defaults";
                
                $clinic_defaults = $this->db->query($defaults_query);
                
                $items_default_value = $clinic_defaults->row['clinic_default_item_value'];
                $items_default_qty = $clinic_defaults->row['clinic_default_item_qty'];
                $clinic_default_terms = $clinic_defaults->row['clinic_default_terms'];              
                
                ?>
	                <!-- markup for the base shipping charge  -->
						<div class="header-fields-div" style="margin-top: 10px;">
		            	<label style="color: #00549f;float: left;width: 180px; line-height: 25px;"><strong>Base Shipping Charge :</strong></label>            	
		            	<input type="text" name="base_shipping_charge" id="base_shipping_charge" value="<?php if ($clinic_id == '') { echo $items_default_value; } else { echo $clinic_base_shipping_charge;}?>" >
	           			 </div>
	           			<!--  -->
	           		 
	           		 <!-- markup for the minimum shipping pairs -->
	           		 <div class="header-fields-div">
		            	<label style="color: #00549f;float: left;width: 180px; line-height: 25px;" ><strong>Minimum Shipping Pairs :</strong></label>            	
		            	<input type="text" name="min_shipping_pairs" id="min_shipping_pairs" value="<?php if ($clinic_id == '') { echo $items_default_qty; } else { echo $clinic_min_ship_pairs;}?>" >
	           		 </div>
	           		 <!--  -->
	           		 <input type="hidden" value="" id="ortho_hidden" />
	           		 <!-- markup for the terms -->
	           		 <div class="header-fields-div">
		            	<label style="color: #00549f;float: left;width: 180px; line-height: 25px;" ><strong>Terms :</strong></label>
		            	<?php if($clinic_terms == '') {?>            	
		            	<select id="clinic_terms_value" name="clinic_terms_value">
		            		<option value="1" <?php if($clinic_default_terms !='' && $clinic_default_terms == '1') echo 'selected'?>>End of month</option>
		            		<option value="2" <?php if($clinic_default_terms !='' && $clinic_default_terms == '2') echo 'selected'?>>Due on receipt</option>
		            		<option value="3" <?php if($clinic_default_terms !='' && $clinic_default_terms == '3') echo 'selected'?>>Net 30</option>
		            	</select>
		            	<?php } else {?>
		            	<select id="clinic_terms_value" name="clinic_terms_value">
		            		<option value="1" <?php if($clinic_terms == '1') echo 'selected'?>>End of month</option>
		            		<option value="2" <?php if($clinic_terms == '2') echo 'selected'?>>Due on receipt</option>
		            		<option value="3" <?php if($clinic_terms == '3') echo 'selected'?>>Net 30</option>
		            	</select>
		            	<?php } ?>
	           		 </div>
	           		 <!--  -->
                <div style="float:right;width: 130px;"><label style="color:#084598" id="response"></label></div>
					<br />
					Specify the list of itemized rates in this screen for each orthotic type...<br />	<br />		
							
									
		<fieldset>
		<?php 
			if($this->request->isget('ortho_id')) {
				$ortho_id = $this->request->isget('ortho_id');	
			} ?>	
					<label style="float:left; color: #084598; font-size: 13px; font-weight: bold;">Select Ortho Type: </label> &nbsp;
					<select name="o_id" id="o_id" onchange="do_ortho_pricing()">								
					<?php

						$ortho_categories = $this->db->query("SELECT * FROM lookup_table WHERE lookup_table_lookup_table_types_id = 37 ORDER BY lookup_table_sort");
						
						
						if ($ortho_categories->num_rows) {
							foreach($ortho_categories->rows as $ortho_category) {
								
								$main_cat = $ortho_category['lookup_table_lookup_table_types_id'];
								$title = $ortho_category['lookup_table_title'];
						
								$lines = explode(",",$ortho_category['lookup_table_text']);
						
								foreach($lines as $line) {
									
									$line_name = $this->db->query("SELECT * FROM lookup_table WHERE lookup_table_id = '$line'");
									$line_name = $line_name->row;
									if(isset($ortho_id) &&  $ortho_id == $line_name["lookup_table_id"]){
										echo '<option selected="selected" value="'.$line_name['lookup_table_id'].'">'.$title.' - '.$line_name['lookup_table_title'].'</option>';
									} else {
										echo '<option value="'.$line_name['lookup_table_id'].'">'.$title.' - '.$line_name['lookup_table_title'].'</option>';									
									}
								}
							}
						}
						?>		</select><br /><br />
							
						
				<div id="ortho_pricing_content"></div>
				<div ></div>
		</fieldset>
            </div>
  <?php } ?>
        </div>
        <!-- Main-mid -->
        <div class="main-bot"></div>
    </div>
    <!-- main end -->
	<input type="hidden" id="hidden_clinic_id" name="clinic_id" value="<?php echo $clinic_id;?>" />
        
</form>


<script type="text/javascript"><!--
var address_row = <?php echo $address_row; ?>;
function add_address() {	
	/*html  = '<div id="faddress_' + address_row + '" class="address-ftitle">';
	html += '<textarea name="clinic_address['+ address_row +']" style="width:380px; float:left; height:100px;"></textarea>';
	html += '<div class="address-guide"><strong>Please maintain the following format:</strong><label class="guide-label">Clinic Name, Street Address, City, State, Post Code </label></div>';
    html += '<div class="add-radio"><label style="display:inline; font-weight:normal;"><input style="width:auto" type="radio" value="' + address_row + '" name="default" />Default</label> &nbsp; ';
	html += '<label style="display:inline; font-weight:normal;"><input style="width:auto" type="radio" value="' + address_row + '" name="shipping" />Default Shipping Address</label>&nbsp; ';
	html += '<a href="javascript:;" onclick="remove_address(' + address_row + ')";" style="float:right;">[ Remove ]</a> </div>';
	html += '<div class="error">&nbsp;</div>';
    html += '</div>';*/
	
	 html=`<div class="address-ftitle" id="faddress_`+ address_row +`">
                                <div style="width:100%; float:left; height:100px; margin-bottom: 20px">
                                    <input type="text" placeholder="Clinic Name" name="clinic_address[`+ address_row +`][format_address]" id="format_address" value=""/> <br/>
                                    <input type="text" placeholder="Street Address" name="clinic_address[`+ address_row +`][format_street]" id="format_street"  value=""/> <br/>
                                    <input type="text" placeholder="City"  name="clinic_address[`+ address_row +`][format_city]"  id="format_city" value=""/> <br/>
                                    <input type="text" placeholder="Province"  name="clinic_address[`+ address_row +`][format_province]"  id="format_province" value=""/> <br/>
                                    <input type="text" placeholder="Postal code" name="clinic_address[`+ address_row +`][format_postal_code]"  id="format_postal_code" value="">
                                </div>
                            <!--<div class="address-guide">
                               <strong>Please maintain the following format:</strong>
                                <label class="guide-label">Clinic Name, Street Address, City, State, Post Code </label>
                            </div>-->
                            <div class="add-radio"><label style="display:inline; font-weight:normal;"><input style="width:auto" type="radio" value="' + address_row + '" name="default" />Default</label> &nbsp;
                               <label style="display:inline; font-weight:normal;"><input style="width:auto" type="radio" value="' + address_row + '" name="shipping" />Default Shipping Address</label>&nbsp; 
                               <a href="javascript:;" onclick="remove_address(` + address_row + `)";" style="float:right;">[ Remove ]</a>
                            </div>  
                            
                            <div class="error">&nbsp;</div>
                        </div>`;

	address_row++;

	$('#add_address').append(html);

}


function showNewOrtho()
{	
	document.getElementById('clinic_new_label_name').value = "";
	document.getElementById('ortho-label-name').style.display = "none";
	document.getElementById('new-orthotic-container').style.display = "";
	document.getElementById('add-new-button').style.display = "none";
	document.getElementById('delete-new-button').style.display = "none";
	document.getElementById('save-new-button').style.display = "";
	document.getElementById('rename-new-button').style.display = "none";
	document.getElementById('clinic_new_label_name').focus();


}

function displayButtons()
{
	if(document.getElementById('clinic_orthotic_label_name').value != '')
	{
		document.getElementById('delete-new-button').style.display = "block";
		document.getElementById('rename-new-button').style.display = "block";
	}
	else
	{
		document.getElementById('delete-new-button').style.display = "none";
		document.getElementById('rename-new-button').style.display = "none";
	}
}

function deleteClick()
{
	document.getElementById('delete-new-button').style.display = "none";
	document.getElementById('save-new-button').style.display = "none";
	document.getElementById('add-new-button').style.display = "none";
	document.getElementById('rename-new-button').style.display = "none";
}


function renameNewOrtho()
{

 	var ortho_name_ext = document.getElementById('clinic_orthotic_label_name').options[document.getElementById('clinic_orthotic_label_name').selectedIndex].text;
	
	var ortho_name = ortho_name_ext.split('.');
	
	document.getElementById('ortho-label-name').style.display = "none";
	document.getElementById('new-orthotic-container').style.display = "";
	document.getElementById('add-new-button').style.display = "none";
	document.getElementById('delete-new-button').style.display = "none";
	document.getElementById('update-new-button').style.display = "";
	document.getElementById('rename-new-button').style.display = "none";
	document.getElementById('clinic_new_label_name').value = ortho_name[0];


}

function do_ortho_pricing(){

	auto_save_clinics();
	
	vl = 'ot=' + $('#o_id').val() + '&cid='+ $('#clinic_id').val() + '&p=pricing';
	$.ajax({
		type: 'post',
		url: 'hc_scripts/functions.php',
		dataType: 'html',
		data: vl,
		success: function (html) {

			$('#ortho_pricing_content').html(html);
		},	
		complete: function () {
//			alert(pss);
		}
	});
}


function do_clinics_tabs(obj) {
	$('#where_am_i').val('tab_' + obj.id.substr(2));
}


function remove_address(id) {
	$('#faddress_' + id).remove();
	
	
}
$(document).ready(function() {

	 <?php if (! $qr->num_rows && $user_group->row['user_group_string'] == '1') { ?>
	 	do_ortho_pricing();	
	 <?php }?>
	 
	displayButtons();

	$('#o_id').live('click', function(e) {
		  var ortho_id = ($(this).val());
		  $("#ortho_hidden").val(ortho_id);
		});	

	$.tabs('#tabs a');
	
	<?php
	if($this->request->isget('w')) {
		echo "$.tabs('#tabs a','#".$this->request->isget('w')."');";	
	}
	?>	

	//this block of code is written to save the clinic form data when pricing tab is clicked
	$('#a_pricing').click(function(){
		
		$('#form').submit();
	});
	
});

function submit_add_clinician(){
	$('#add_clinician_f').val("<?php echo $insert_clinician;?>&cid=<?php echo $clinic_id?>");
	$('#form').submit();	
}
//--></script> 

<?php echo $footer; ?>