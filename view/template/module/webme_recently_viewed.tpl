<?php echo $header; ?>
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
          <td><select name="webme_recently_viewed_status">
              <?php if ($webme_recently_viewed_status) { ?>
              <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
              <option value="0"><?php echo $text_disabled; ?></option>
              <?php } else { ?>
              <option value="1"><?php echo $text_enabled; ?></option>
              <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
              <?php } ?>
            </select></td>
        </tr>
        <tr>
          <td><?php echo $entry_position; ?></td>
          <td><select name="webme_recently_viewed_position">
              <?php if ($webme_recently_viewed_position == 'left') { ?>
              <option value="left" selected="selected"><?php echo $text_left; ?></option>
              <?php } else { ?>
              <option value="left"><?php echo $text_left; ?></option>
              <?php } ?>
              <?php if ($webme_recently_viewed_position == 'right') { ?>
              <option value="right" selected="selected"><?php echo $text_right; ?></option>
              <?php } else { ?>
              <option value="right"><?php echo $text_right; ?></option>
              <?php } ?>
            </select></td>
        </tr>
        <tr>
          <td><?php echo $entry_sort_order; ?></td>
          <td><input type="text" name="webme_recently_viewed_sort_order" value="<?php echo $webme_recently_viewed_sort_order; ?>" size="1" /></td>
        </tr>
        <tr>
          <td><?php echo $entry_personal_limit; ?></td>
          <td><input type="text" name="webme_recently_viewed_personal_limit" value="<?php echo $webme_recently_viewed_personal_limit; ?>" size="1" /></td>
        </tr>
        <tr>
          <td><?php echo $entry_overall_status; ?></td>
          <td><?php echo $entry_overall_status_on; ?><input type="radio" name="webme_recently_viewed_overall_status" value="1" <?php echo $webme_recently_viewed_overall_status_on; ?> /><?php echo $entry_overall_status_off; ?><input type="radio" name="webme_recently_viewed_overall_status" value="0" <?php echo $webme_recently_viewed_overall_status_off; ?> /></td>
        </tr>
        <tr>
          <td><?php echo $entry_overall_limit; ?></td>
          <td><input type="text" name="webme_recently_viewed_overall_limit" value="<?php echo $webme_recently_viewed_overall_limit; ?>" size="1" /></td>
        </tr>
      </table>
    </form>
  </div>
</div>
<?php echo $footer; ?>