<?php echo $header; ?>
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<?php if (isset($this->session->data['warning']) && $this->session->data['warning']) { ?>
<div class="success"><?php echo $this->session->data['warning'];$this->session->data['warning']=''; ?></div>
<?php } ?>
<?php
$route = $this->request->isget('route'); ?>
		<div class="h1float">
			<h1><?php echo ($route == 'user/user/insert'? 'Insert':'Update')?> a <strong>User</strong></h1>
		</div>

    <div class="buttons"><a onclick="$('#form').submit();" class="button s"><p></p><span><strong>Save</strong> User</span></a> &nbsp;
<?php
if ($this->user->getUGstr() != '1') $cancel = str_replace('user/user','common/home',$cancel);
?>
    <a onclick="location='<?php echo $cancel;?>'" class="button c"><p></p><span>Cancel</span></a></div>
    

<div class="formdiv">

			<div class="top"><img src="images/bg_middle_top.png" /></div>
            
            
            
			<div class="mid" style="padding: 20px; 0">
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
<div class="divleft">
<div class="ftitle">
	<label><span class="required">*</span> <?php echo $entry_email; ?></label>
	<input type="text" name="user_email" value="<?php echo $user_email; ?>" />
	<div class="error"><?php if ($error_user_email) { ?><?php echo $error_user_email; ?><?php } ?>&nbsp;</div>
</div>

<div class="ftitle ftitler">
	<label><span class="required">*</span> <?php echo $entry_firstname; ?></label>
    <input type="text" name="user_firstname" value="<?php echo $user_firstname; ?>" />
    <div class="error"><?php if ($error_user_firstname) { ?><?php echo $error_user_firstname; ?><?php } ?>&nbsp;</div>
</div>

<div class="ftitle">
	<label><span class="required">*</span> <?php echo $entry_lastname; ?></label>
	<input type="text" name="user_lastname" value="<?php echo $user_lastname; ?>" autocomplete="off" />
	<div class="error"><?php if ($error_user_lastname) { ?><?php echo $error_user_lastname; ?><?php } ?>&nbsp;</div>
</div>
<div class="ftitle">
	<label><?php echo $entry_password; ?></label>
	<input type="password" name="user_password" value="<?php echo $user_password; ?>"  />
    <div class="error"><?php if ($error_user_password) { ?><?php echo $error_user_password; ?><?php  } ?>&nbsp;</div>
</div>

<div class="ftitle">
	<label>Confirm Password:</label>
	<input type="password" name="user_confirm" value="<?php echo $user_confirm; ?>"  />
    <div class="error"><?php if ($error_user_confirm) { ?><?php echo $error_user_confirm; ?><?php  } ?>&nbsp;</div>
</div>
                    </div>
                    <!-- EOF LEFT -->
<?php 

//p($this->user->getUGstr(),__LINE__);
// ONLY ADMIN can change status and group

if (! isset($this->request->post['user_group_string'])) {
	$user_group_string = explode(",",$user_group_string);
}


if (in_array('1',explode(',',$this->user->getUGstr()))) { ?>        
                    
                    <div class="divright">


    <div class="ftitle">
        <label><?php echo $entry_user_group; ?></label>

            <div class="scrollbox" style="width:98%; height:200px;">

<?php 
			$class = 'odd'; 
        	foreach ($user_groups as $user_group) { 
			
				$class = ($class == 'even' ? 'odd' : 'even'); ?>
<label><div class="<?php echo $class; ?>">			
<input type="checkbox" name="user_group_string[]" value="<?php echo $user_group['user_group_id']; ?>" <?php
if (in_array($user_group['user_group_id'],$user_group_string)) echo " checked='checked'";
if($user_group['user_group_id'] == '11') echo " id='user_group_string' onchange='show_hide_clinics()';";
?> /> <?php echo $user_group['user_group_name']; ?>
</div></label>
<?php 
			} ?>
			</div>
        <div class="error"><?php if ($error_user_group_string) { ?><?php echo $error_user_group_string; ?><?php  } ?>&nbsp;</div>
    </div>

    <div class="ftitle">
        <label><?php echo $entry_status; ?></label>
        <select name="user_status">
                  <?php if ($user_status) { ?>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <?php } else { ?>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <?php } ?>
        </select>
        <div class="error">&nbsp;</div>
    </div>
    
</div>

        <div class="ftitle" id="d_clinics" style="display:none;">
            <label style="float:left; padding:0 0 4px 0">Clinics:</label>
            <div style="float:right;">          
            <a onclick="resize_div(1,'scrollbox_1')">Maximize</a> | <a onclick="resize_div(0,'scrollbox_1');">Normal</a></div>
            
            <div class="scrollbox" style="width:850px;">

<?php 
			$class = 'odd'; 

			$user_id = isset($_GET['user_id'])?$_GET['user_id']:0;
			
			$s = "SELECT * FROM user_to_clinic WHERE user_to_clinic_user_id = '$user_id'";
			$custs = mysql_query($s);
			$selected = array();
			while($sel = mysql_fetch_assoc($custs)) {
				$selected[] = $sel['user_to_clinic_clinic_id'];
			}


			foreach($clinics as $clinic){	
//			$cust = mysql_query($s = "SELECT * FROM clinic ORDER BY user_firstname, user_lastname, clinic_code");

//			while ($clinic = mysql_fetch_assoc($cust)){
			
				$class = ($class == 'even' ? 'odd' : 'even'); ?>
			<label><div class="<?php echo $class; ?>">
			  <?php if (in_array($clinic['clinic_id'], $selected)) { ?>
			  <input style="width:auto"  type="checkbox" name="user_to_clinic[]" value="<?php echo $clinic['clinic_id']; ?>" checked="checked" />
			  <?php } else { ?>
			  <input style="width:auto" type="checkbox" name="user_to_clinic[]" value="<?php echo $clinic['clinic_id']; ?>" />
			  <?php } 
				
				echo $clinic['clinic_name'].' ( '.$clinic['clinic_id'].' ) ';
			
			  ?>
			
			</div></label>
			
			<?php } ?>

          
       	 </div></div>
<?php
//        }
?>
<?php
		
} else {
	 $user_group_string = $this->user->getUGstr();
	echo "<input type='hidden' name='ugids' value='$user_group_string' />";
}

?>
    </form>
<div style="clear:both"></div>
</div>			<div class="bot"><img src="images/bg_middle_bot.png" /></div></div>

<script type="text/ecmascript">
//<!--
function resize_div(par,fdiv){
	if(par) {
		$('#'+fdiv).css('height','500px');
	} else {
		$('#'+fdiv).css('height','100px');
	}
}

function show_hide_clinics() {
	$("#d_clinics").hide();

//	str = $('select#user_group_string').val().toString();

	str = $('#user_group_string').attr('checked');
	
	if (str) {
		$("#d_clinics").show();
	}
	
}
show_hide_clinics();

//-->
</script>
<?php echo $footer; ?>