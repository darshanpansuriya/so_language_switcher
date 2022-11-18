<?php echo $header; ?>
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<?php
$route = $this->request->isget('route'); ?>
		<div class="h1float">
			<h1><?php echo ($route == 'user/user_permission/insert'? 'Insert':'Update')?> <strong>User Group</strong></h1>
		</div>

    <div class="buttons"><a onclick="$('#form').submit();" class="button s"><p></p><span><strong>Save</strong> User Group</span></a> &nbsp;
    <a onclick="location = '<?php echo $cancel; ?>';" class="button c"><p></p><span><?php echo $button_cancel; ?></span></a></div>
    
<div class="formdiv">

			<div class="top"><img src="images/bg_middle_top.png" /></div>
            
            
            
			<div class="mid">
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
      <table class="form">
        <tr>
          <td><span class="required">*</span> <?php echo $entry_name; ?></td>
          <td><input type="text" name="user_group_name" value="<?php echo $user_group_name; ?>" />
            <?php if ($error_user_group_name) { ?>
            <span class="error"><?php echo $error_user_group_name; ?></span>
            <?php  } ?></td>
        </tr>
        <tr>
          <td>Order Steps Display</td>
          <td><div class="scrollbox">
              <?php $class = 'odd'; ?>
              
<?php

$order_statuses = 	$this->db->query("SELECT * FROM order_status ORDER BY order_status_sort");


if ($order_statuses->num_rows) foreach ($order_statuses->rows as $statuses) {
?>  
              
              
              <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
              <div class="<?php echo $class; ?>">
                <input type="checkbox" name="user_group_step_display[display][]" value="<?php echo $statuses['order_status_id']; ?>"   <?php if (in_array($statuses['order_status_id'], $display)) echo 'checked="checked"';?> />
                <?php echo $statuses['order_status_name']; ?>
              </div>
              <?php } ?>
            </div></td></tr>

        <tr>
          <td>Order Steps Full</td>
          <td><div class="scrollbox">
              <?php $class = 'odd'; ?>
              
<?php

$order_statuses = 	$this->db->query("SELECT * FROM order_status ORDER BY order_status_sort");


if ($order_statuses->num_rows) foreach ($order_statuses->rows as $statuses) {
?>  
              
              
              <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
              <div class="<?php echo $class; ?>">
                <input type="checkbox" name="user_group_step_full[full][]" value="<?php echo $statuses['order_status_id']; ?>"   <?php if (in_array($statuses['order_status_id'], $full)) echo 'checked="checked"';?> />
                <?php echo $statuses['order_status_name']; ?>
              </div>
              <?php } ?>
            </div></td></tr>


			<tr>

          <td><?php echo $entry_access; ?></td>
          <td><div class="scrollbox">
              <?php $class = 'odd'; ?>
              <?php foreach ($permissions as $user_group_permission) { ?>
              <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
              <div class="<?php echo $class; ?>">
                <?php if (in_array($user_group_permission, $access)) { ?>
                <input type="checkbox" name="user_group_permission[access][]" value="<?php echo $user_group_permission; ?>" checked="checked" />
                <?php echo $user_group_permission; ?>
                <?php } else { ?>
                <input type="checkbox" name="user_group_permission[access][]" value="<?php echo $user_group_permission; ?>" />
                <?php echo $user_group_permission; ?>
                <?php } ?>
              </div>
              <?php } ?>
            </div></td>
        </tr>
        <tr>
          <td><?php echo $entry_modify; ?></td>
          <td><div class="scrollbox">
              <?php $class = 'odd'; ?>
              <?php foreach ($permissions as $user_group_permission) { ?>
              <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
              <div class="<?php echo $class; ?>">
                <?php if (in_array($user_group_permission, $modify)) { ?>
                <input type="checkbox" name="user_group_permission[modify][]" value="<?php echo $user_group_permission; ?>" checked="checked" />
                <?php echo $user_group_permission; ?>
                <?php } else { ?>
                <input type="checkbox" name="user_group_permission[modify][]" value="<?php echo $user_group_permission; ?>" />
                <?php echo $user_group_permission; ?>
                <?php } ?>
              </div>
              <?php } ?>
            </div></td>
        </tr>
      </table>
    </form>
</div>
			<div class="bot"><img src="images/bg_middle_bot.png" /></div>
			</div>
<?php echo $footer; ?>