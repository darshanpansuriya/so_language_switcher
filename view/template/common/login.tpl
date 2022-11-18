<?php echo $header;?>
  <div class="content" style="width:400px; margin:0 auto; float:none;">
		<div class="h1float">
			<h1><?php if($this->request->isget('route') == 'common/login/forgot') echo "Enter Your <strong>Email</strong>"; else { ?>
            
			
			Enter Your <strong>Login</strong> Details<?php } ?></h1>
		</div>
<div class="formdiv">

			<div class="top"><img src="images/bg_middle_top.png" /></div>
			<div class="mid">
    <?php
function rnd_pass($len=7) {
    $chars = "abcdefghijkmnopqrstuvwxyz023456789";
	$pass="";
    for($i = 0; $i < $len; $i++) {
        $num = mt_rand(0,strlen($chars) - 1);
        $tmp = substr($chars, $num, 1);
        $pass .= $tmp;
    }
    return $pass;
}

if($this->request->isget('route') == 'common/login/reset')	{
	$t = $this->db->escape($this->request->isget('t'));
	$id = (int) $this->request->isget('id');
	
	$un = $this->db->query("SELECT * FROM user WHERE user_id = '$id' AND user_token = '$t'");
	
	if ($un->num_rows) {
		$pwd = rnd_pass();
//		$pwd = mt_rand(100000,999999);
		$this->db->query("UPDATE user set user_password = md5('$pwd'), user_token = '' WHERE user_id = '$id'");
		$h = "From: ".$this->config->get('config_email')."\r\nReply-To: ".$this->config->get('config_email')."\r\n";
		$msg = "Dear User,\n\n";
		$msg .= "Your new password:\n\n";
		$msg .= "$pwd\n";
		$msg .= "Please do not forget to change your new password after logging in by clicking on 'My Account' at the top of the page.";
		mail($un->row['user_email'],"Your new password for SoundOrthotics",$msg,$h);
		$error_success = "Your new password has been sent";
		
	} else $error_success = "F.U.N";
	
}
	 if ($error_warning) { ?>
    <div class="warning" style="padding: 3px;"><?php echo $error_warning; ?></div>
    <?php } 
	 if ($error_success) { ?>
    <div class="success" style="padding: 3px;"><?php echo $error_success; ?></div>
    <?php } ?>
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
      <table class="loginf">
        <tr>
          <td style="text-align: center;" rowspan="4"><img src="view/image/login.png" alt="<?php echo $text_login; ?>" /><input type="image" src="images/spacer.gif" style="style:width:1px;height:1px; border: none; outline:none;" /></td>
        </tr>
        <tr>
          <td><label>Email</label><br />
            <input type="text" name="username" value="<?php echo $username; ?>" style="margin-top: 4px;" />
            <br />
<?php if($this->request->isget('route') != 'common/login/forgot') { ?>           
            <br />
            <?php echo $entry_password; ?><br />
            <input type="password" name="password" value="<?php echo $password; ?>" style="margin-top: 4px;" />
<?php } ?></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td valign="bottom" style="text-align: right; padding-right:10px;">
          
<?php if($this->request->isget('route') != 'common/login/forgot') { ?>           
		<div style="float:left;">
          <a style="font-weight:100;" href="?route=common/login/forgot">Forgot Password</a></div><a onclick="$('#form').submit(); return false;" href="#" class="button p"><span><strong><?php echo $button_login; ?></strong></span></a>
<?php } else { ?> 
	<a onclick="$('#form').submit(); return false;" href="#" class="button p"><span><strong>Send</strong></span></a>    <input type="hidden" name="forgotpass" value="forgot" />
<?php } ?>
    </td>
        </tr>
      </table>
      <?php if ($redirect) { ?>
      <input type="hidden" name="redirect" value="<?php echo HTTPS_SERVER . "index.php?route=common/login"; ?>" />
      <?php } ?>

    </form>
</div>
			<div class="bot"><img src="images/bg_middle_bot.png" /></div>
			</div>
<?php echo $footer; ?> 