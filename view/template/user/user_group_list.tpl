<?php echo $header; ?>
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<?php if ($success) { ?>
<div class="success"><?php echo $success; ?></div>
<?php } ?>
		<div class="h1float">
			<h1>List <strong>User Groups</strong></h1>
		</div>


    <div class="buttons"><a onclick="location = '<?php echo $insert; ?>'" class="button a"><p></p><span>Add <strong>User Group</strong></span></a> &nbsp; 
    <a onclick="$('form').submit();" class="button d"><p></p><span>Delete <strong>User Group</strong></span></a></div>
<div style="clear:both"></div>
<form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
      <table class="list">
        <thead>
          <tr>
            <td width="15" class="center"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
            <td class="left"><?php if ($sort == 'user_group_name') { ?>   
              <a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_name; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_name; ?>"><?php echo $column_name; ?></a>
              <?php } ?></td>
            <td class="center"><?php echo $column_action; ?></td>
          </tr>
        </thead>
        <tbody>
          <?php if ($user_groups) { ?>
          <?php foreach ($user_groups as $user_group) { ?>
          <tr>
            <td class="center"><?php 
if(! in_array($user_group['user_group_id'],array('1','11'))) {
			
			if ($user_group['selected']) { ?>
              <input type="checkbox" name="selected[]" value="<?php echo $user_group['user_group_id']; ?>" checked="checked" />
              <?php } else { ?>
              <input type="checkbox" name="selected[]" value="<?php echo $user_group['user_group_id']; ?>" />
              <?php }
} ?></td>
            <td class="left"><?php echo $user_group['user_group_name']; ?></td>
            <td class="center edit"><?php foreach ($user_group['action'] as $action) { ?>
              <a href="<?php echo $action['href']; ?>" class="button e"><p></p><span><?php echo $action['text']; ?></span></a>
            <?php } ?></td>
          </tr>
          <?php } ?>
          <?php } else { ?>
          <tr>
            <td class="center" colspan="3"><?php echo $text_no_results; ?></td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
    </form>
<div class="pagination"><?php echo $pagination; ?></div>
<?php echo $footer; ?>