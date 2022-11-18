<?php echo $header; ?>
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<?php if ($success) { ?>
<div class="success"><?php echo $success; ?></div>
<?php } ?>

		<div class="h1float">
			<h1><strong><?php echo $heading_title; ?></strong></h1>
		</div>
    <div class="buttons"><a onclick="location = '<?php echo $insert; ?>'" class="button a"><p></p><?php echo $button_add_user; ?></a> &nbsp;
    <a onclick="$('form').attr('action', '<?php echo $delete; ?>'); $('form').submit();" class="button d"><p></p><?php echo $button_delete_user; ?></a></div>

<div style="clear:both"></div>

    <form action="" method="post" enctype="multipart/form-data" id="form">

      <table class="list">
        <thead>
          <tr>
            <td width="15" class="center"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
<td><?php if ($sort == 'user_email') { ?>
              <a href="<?php echo $sort_email; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_email; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_email; ?>"><?php echo $column_email; ?></a>
              <?php } ?></td>
<td><?php if ($sort == 'name') { ?>
          <a href="<?php echo $sort_group; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_group; ?></a>
              <?php } else { ?>
          <a href="<?php echo $sort_group; ?>"><?php echo $column_group; ?></a>
              <?php } ?></td>
            <td class="left"><?php if ($sort == 'user_status') { ?>
              <a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_status; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_status; ?>"><?php echo $column_status; ?></a>
              <?php } ?></td>
            <td class="left"><?php if ($sort == 'user_date_added') { ?>
              <a href="<?php echo $sort_date_added; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_date_added; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_date_added; ?>"><?php echo $column_date_added; ?></a>
              <?php } ?></td>
              <td><?php echo $column_last_login; ?></td>
            <td class="center" width="70"><?php echo $column_action; ?></td>
          </tr>
        </thead>
        <tbody>
<tr class="filter">
            <td><input type="submit" style="display:none;" /></td>
        <td class="left"><input type="text" name="filter_email" value="<?php echo $filter_email; ?>" /></td>

            <td><select name="filter_group">
                <option value=""></option>
<?php     foreach($groups as $group) { ?>

                <option value="<?php echo $group['user_group_id'];?>" <?php if ($group['user_group_id']==$filter_group) echo 'selected="selected"';?>><?php echo $group['user_group_name']; ?></option>
<?php
}
?>              </select></td>
            <td><select name="filter_status">
                <option value="*"></option>
                <option value="1" <?php if ($filter_status == 1) echo 'selected="selected"';?>>Enabled</option>
                <option value="0" <?php if (!is_null($filter_status) && !$filter_status) echo 'selected="selected"';?>>Disabled</option>
              </select></td>

            <td><input type="text" name="filter_date" value="<?php echo $this->hc_functions->ch_date($filter_date); ?>" size="12" id="date" /></td>
            <td>&nbsp;</td>
            <td align="center"><a onclick="filter();" class="button f"><p></p><span><strong><?php echo $button_filter; ?></strong></span></a></td>
          </tr>


          <?php if ($users) { ?>
          <?php foreach ($users as $user) { ?>
          <tr>
            <td class="center"><?php if ($user['selected']) { ?>
              <input type="checkbox" name="selected[]" value="<?php echo $user['user_id']; ?>" checked="checked" />
              <?php } else { ?>
              <input type="checkbox" name="selected[]" value="<?php echo $user['user_id']; ?>" />
              <?php } ?></td>
            <td class="left"><?php echo $user['user_email']; ?></td>
<td class="left"><?php

$ax = $this->db->query("SELECT * FROM user_group WHERE find_in_set(user_group_id,'" . $user['user_group_string']. "')");

if ($ax->num_rows) foreach ($ax->rows as $ugnames) echo $ugnames['user_group_name'] . ' / ';
?></td>
            <td class="left"><?php echo $user['user_status']; ?></td>
            <td class="left"><?php echo $this->hc_functions->ch_date($user['user_date_added']); ?></td>
            <td class="left"><?php echo $user['user_last_login']; ?></td>

            <td class="center edit"><?php foreach ($user['action'] as $action) { ?>
              <a href="<?php echo $action['href']; ?>" class="button e"><p></p><span><?php echo $button_edit; ?></span></a>
            <?php } ?></td>
          </tr>
          <?php } ?>
          <?php } else { ?>
          <tr>
            <td class="center" colspan="7"><?php echo $text_no_results; ?></td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
    </form>
<div class="pagination"><?php echo $pagination; ?></div>

<script type="text/javascript"><!--
function filter() {

	url = 'index.php?route=user/user&token=<?php echo $token; ?>';

	var filter_email = $('input[name=\'filter_email\']').attr('value');
	if (filter_email) {
		url += '&filter_email=' + encodeURIComponent(filter_email);
	}

	var filter_group = $('select[name=\'filter_group\']').attr('value');
	if (filter_group) {
		url += '&filter_group=' + encodeURIComponent(filter_group);
	}

	var filter_status = $('select[name=\'filter_status\']').attr('value');

	if (filter_status != '*') {
		url += '&filter_status=' + encodeURIComponent(filter_status);
	}

	var filter_date = $('input[name=\'filter_date\']').attr('value');
	if (filter_date) {
		url += '&filter_date=' + encodeURIComponent(ch_date(filter_date));
	}
	location = url;
}
//--></script>
<script type="text/javascript" src="view/javascript/jquery/ui/ui.datepicker.js"></script>
<script type="text/javascript"><!--
$(document).ready(function() {
	$('#date').datepicker({dateFormat: 'yy-M-dd',changeMonth: true,changeYear: true});
});

//--></script>

<script type="text/javascript" src="view/javascript/hc_general.php"></script>

<?php echo $footer; ?>
