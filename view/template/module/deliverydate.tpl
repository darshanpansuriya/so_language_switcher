<?php echo $header; ?>
<style type="text/css">
/* css for timepicker */
#ui-timepicker-div dl{ text-align: left; }
#ui-timepicker-div dl dt{ height: 25px; }
#ui-timepicker-div dl dd{ margin: -25px 0 10px 65px; }
</style>
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<div class="box">
  <div class="left"></div>
  <div class="right"></div>
  <div class="heading">
    <h1 style="background-image: url('view/image/module.png');"><?php echo $heading_title; ?></h1>
    <div class="buttons"><a onclick="$('#form').submit();" class="button"><span><?php echo $button_save; ?></span></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><span><?php echo $button_cancel; ?></span></a></div>
  </div>
  <div class="content">
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
      <table class="form">
        <tr>
          <td><?php echo $entry_status; ?></td>
          <td><select name="deliverydate_status">
              <?php if ($deliverydate_status) { ?>
              <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
              <option value="0"><?php echo $text_disabled; ?></option>
              <?php } else { ?>
              <option value="1"><?php echo $text_enabled; ?></option>
              <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
              <?php } ?>
            </select></td>
        </tr>
        <tr>
          <td><?php echo $entry_interval; ?></td>
          <td><input type="text" name="deliverydate_interval_days" value="<?php echo $deliverydate_interval_days; ?>" size="12" /></td>
        </tr>
        <tr>
          <td><?php echo $entry_unavailable_after; ?></td>
          <td><input type="text" name="deliverydate_unavailable_after" value="<?php echo $deliverydate_unavailable_after; ?>" size="12" class="time" /></td>
        </tr>
        <tr>
          <td><?php echo $entry_display_same_day; ?></td>
          <td><select name="deliverydate_same_day">
              <?php if ($deliverydate_same_day) { ?>
			  <option value="1" selected="selected"><?php echo $text_yes; ?></option>
			  <option value="0"><?php echo $text_no; ?></option>
			  <?php } else { ?>
			  <option value="1"><?php echo $text_yes; ?></option>
			  <option value="0" selected="selected"><?php echo $text_no; ?></option>
			  <?php } ?>
			</select></td>
        </tr>
      </table>
    </form>
  </div>
</div>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.4/jquery-ui.min.js"></script>
<script type="text/javascript" src="view/javascript/jquery/ui/ui-timepicker.js"></script>
<script type="text/javascript"><!--
$(document).ready(function() {
	$('.time').timepicker({timeFormat: 'hh:mm:ss'});
});
//--></script>
<?php echo $footer; ?>