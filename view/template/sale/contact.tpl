<?php echo $header; ?>
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<?php if ($success) { ?>
<div class="success"><?php echo $success; ?></div>
<?php } ?>
<div class="box">
  <div class="left"></div>
  <div class="right"></div>
  <div class="heading">
    <h1 style="background-image: url('view/image/mail.png');"><?php echo $heading_title; ?></h1>
    <div class="buttons"><a onclick="$('#form').submit();" class="button"><span><?php echo $button_send; ?></span></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><span><?php echo $button_cancel; ?></span></a></div>
  </div>
  <div class="content">
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
      <table class="form">
        <tr>
          <td><?php echo $entry_store; ?></td>
          <td><select name="store_id">
              <option value="0"><?php echo $text_default; ?></option>
              <?php foreach ($stores as $store) { ?>
              <?php if ($store['store_id'] == $store_id) { ?>
              <option value="<?php echo $store['store_id']; ?>"><?php echo $store['name']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $store['store_id']; ?>"><?php echo $store['name']; ?></option>
              <?php } ?>
              <?php } ?>
            </select></td>
        </tr>
        <tr>
          <td><?php echo $entry_to; ?></td>
          <td><select name="group">
              <option value=""></option>
              <?php if ($group == 'newsletter') { ?>
              <option value="newsletter" selected="selected"><?php echo $text_newsletter; ?></option>
              <?php } else { ?>
              <option value="newsletter"><?php echo $text_newsletter; ?></option>
              <?php } ?>
              <?php if ($group == 'clinic') { ?>
              <option value="clinic" selected="selected"><?php echo $text_clinic; ?></option>
              <?php } else { ?>
              <option value="clinic"><?php echo $text_clinic; ?></option>
              <?php } ?>
            </select></td>
        </tr>
        <tr>
          <td></td>
          <td><table width="100%">
              <tr>
                <td style="padding: 0;" colspan="3"><input type="text" id="search" value="" style="margin-bottom: 5px;" />
                  <a onclick="getClinics();" style="margin-bottom: 5px;" class="button"><span><?php echo $text_search; ?></span></a></td>
              </tr>
              <tr>
                <td width="49%" style="padding: 0;"><select multiple="multiple" id="clinic" size="10" style="width: 100%; margin-bottom: 3px;">
                  </select></td>
                <td width="2%" style="text-align: center; vertical-align: middle;"><input type="button" value="--&gt;" onclick="addClinic();" />
                  <br />
                  <input type="button" value="&lt;--" onclick="removeClinic();" /></td>
                <td width="49%" style="padding: 0;"><select multiple="multiple" id="to" size="10" style="width: 100%; margin-bottom: 3px;">
                    <?php foreach ($clinics as $clinic) { ?>
                    <option value="<?php echo $clinic['clinic_id']; ?>"><?php echo $clinic['name']; ?></option>
                    <?php } ?>
                  </select></td>
              </tr>
            </table></td>
        </tr>
		<tr>
		  <td><?php echo $entry_product; ?></td>
		  <td><table>
			  <tr>
			    <td style="padding: 0;" colspan="3"><select id="category" style="margin-bottom: 5px;" onchange="getProducts();">
			      <?php foreach ($categories as $category) { ?>
			      <option value="<?php echo $category['category_id']; ?>"><?php echo $category['name']; ?></option>
			      <?php } ?>
			      </select></td>
			  </tr>
			  <tr>
			    <td style="padding: 0;"><select multiple="multiple" id="product" size="10" style="width: 350px;">
			      </select></td>
			    <td style="vertical-align: middle;"><input type="button" value="--&gt;" onclick="addItem();" />
			      <br />
			      <input type="button" value="&lt;--" onclick="removeItem();" /></td>
			    <td style="padding: 0;"><select multiple="multiple" id="item" size="10" style="width: 350px;">
			      </select></td>
			  </tr>
		    </table>
		    <div id="product_item">
		    </div></td>
		</tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_subject; ?></td>
          <td><input name="subject" value="<?php echo $subject; ?>" />
            <?php if ($error_subject) { ?>
            <span class="error"><?php echo $error_subject; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_message; ?></td>
          <td><textarea name="message" id="message"><?php echo $message; ?></textarea>
            <?php if ($error_message) { ?>
            <span class="error"><?php echo $error_message; ?></span>
            <?php } ?></td>
        </tr>
      </table>
      <div id="clinic_to">
        <?php foreach ($clinics as $clinic) { ?>
        <input type="hidden" name="to[]" value="<?php echo $clinic['clinic_id']; ?>" />
        <?php } ?>
      </div>
    </form>
  </div>
</div>
<script type="text/javascript" src="view/javascript/ckeditor/ckeditor.js"></script>
<script type="text/javascript"><!--
CKEDITOR.replace('message', {
	filebrowserBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserImageUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserFlashUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>'
});
//--></script>
<script type="text/javascript"><!--
function addClinic() {
	$('#clinic :selected').each(function() {
		$(this).remove();
		
		$('#to option[value=\'' + $(this).attr('value') + '\']').remove();
		
		$('#to').append('<option value="' + $(this).attr('value') + '">' + $(this).text() + '</option>');
		
		$('#clinic_to').append('<input type="hidden" name="to[]" value="' + $(this).attr('value') + '" />');
	});
}

function removeClinic() {
	$('#to :selected').each(function() {
		$(this).remove();
		
		$('#clinic_to input[value=\'' + $(this).attr('value') + '\']').remove();
	});
}

function getClinics() {
	$('#clinic option').remove();
	
	$.ajax({
		url: 'index.php?route=sale/contact/clinics&token=<?php echo $token; ?>&keyword=' + encodeURIComponent($('#search').attr('value')),
		dataType: 'json',
		success: function(data) {
			for (i = 0; i < data.length; i++) {
	 			$('#clinic').append('<option value="' + data[i]['clinic_id'] + '">' + data[i]['name'] + '</option>');
			}
		}
	});
}
//--></script>
<script type="text/javascript"><!--
function addItem() {
	$('#product :selected').each(function() {
		$(this).remove();
		
		$('#item option[value=\'' + $(this).attr('value') + '\']').remove();
		
		$('#item').append('<option value="' + $(this).attr('value') + '">' + $(this).text() + '</option>');
		
		$('#product_item input[value=\'' + $(this).attr('value') + '\']').remove();
		
		$('#product_item').append('<input type="hidden" name="product[]" value="' + $(this).attr('value') + '" />');
	});
}

function removeItem() {
	$('#item :selected').each(function() {
		$(this).remove();
		
		$('#product_item input[value=\'' + $(this).attr('value') + '\']').remove();
	});
}

function getProducts() {
	$('#product option').remove();
	
	$.ajax({
		url: 'index.php?route=sale/contact/category&token=<?php echo $token; ?>&category_id=' + $('#category').attr('value'),
		dataType: 'json',
		success: function(data) {
			for (i = 0; i < data.length; i++) {
	 			$('#product').append('<option value="' + data[i]['product_id'] + '">' + data[i]['name'] + ' (' + data[i]['model'] + ') </option>');
			}
		}
	});
}

getProducts();
//--></script>
<?php echo $footer; ?>