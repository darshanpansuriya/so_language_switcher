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
          <td><?php echo $entry_position; ?></td>
          <td colspan="3"><select name="slideshow_position">
              <?php if ($slideshow_position == 'middletop') { ?>
              <option value="middletop" selected="selected"><?php echo $text_middletop; ?></option>
              <?php } else { ?>
              <option value="middletop"><?php echo $text_middletop; ?></option>
              <?php } ?>
              <?php if ($slideshow_position == 'left') { ?>
              <option value="left" selected="selected"><?php echo $text_left; ?></option>
              <?php } else { ?>
              <option value="left"><?php echo $text_left; ?></option>
              <?php } ?>
              <?php if ($slideshow_position == 'right') { ?>
              <option value="right" selected="selected"><?php echo $text_right; ?></option>
              <?php } else { ?>
              <option value="right"><?php echo $text_right; ?></option>
              <?php } ?>
              <?php if ($slideshow_position == 'home') { ?>
              <option value="home" selected="selected"><?php echo $text_home; ?></option>
              <?php } else { ?>
              <option value="home"><?php echo $text_home; ?></option>
              <?php } ?>
            </select></td>
        </tr>

        <tr>
          <td><?php echo $entry_status; ?></td>
          <td colspan="3"><select name="slideshow_status">
              <?php if ($slideshow_status) { ?>
              <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
              <option value="0"><?php echo $text_disabled; ?></option>
              <?php } else { ?>
              <option value="1"><?php echo $text_enabled; ?></option>
              <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
              <?php } ?>
            </select></td>
        </tr>

        <tr>
          <td><?php echo $entry_delay; ?>
          <?php echo $help_slidedelay; ?>
          </td>
          <td><select name="slideshow_delay">

              <?php if (isset($slideshow_delay)) {
              $selected = "selected";
              ?>
              <option value="1000" <?php if($slideshow_delay=='1000'){echo $selected;} ?>>1000</option>
              <option value="2000" <?php if($slideshow_delay=='2000'){echo $selected;} ?>>2000</option>
              <option value="3000" <?php if($slideshow_delay=='3000'){echo $selected;} ?>>3000</option>
              <option value="4000" <?php if($slideshow_delay=='4000'){echo $selected;} ?>>4000</option>
              <option value="5000" <?php if($slideshow_delay=='5000'){echo $selected;} ?>>5000</option>
              <option value="6000" <?php if($slideshow_delay=='6000'){echo $selected;} ?>>6000</option>
              <option value="7000" <?php if($slideshow_delay=='7000'){echo $selected;} ?>>7000</option>
              <option value="8000" <?php if($slideshow_delay=='8000'){echo $selected;} ?>>8000</option>
              <option value="9000" <?php if($slideshow_delay=='9000'){echo $selected;} ?>>9000</option>
              <option value="10000" <?php if($slideshow_delay=='10000'){echo $selected;} ?>>10000</option>
              <?php } else { ?>
              <option selected="selected"><?php echo $text_pleaseselect; ?></option>
              <option value="1000">1000</option>
              <option value="2000">2000</option>
              <option value="3000">3000</option>
              <option value="4000">4000</option>
              <option value="5000">5000</option>
              <option value="6000">6000</option>
              <option value="7000">7000</option>
              <option value="8000">8000</option>
              <option value="9000">9000</option>
              <option value="10000">10000</option>
              <?php } ?>
            </select>
            </td>

          <td width="20%"><?php echo $entry_speed; ?>
          <?php echo $help_transitionspeed; ?>
          </td>
          <td><select name="slideshow_speed">
              <?php if (isset($slideshow_speed)) {
              $selected = "selected";
              ?>
              <option value="200" <?php if($slideshow_speed=='200'){echo $selected;} ?>>200</option>
              <option value="300" <?php if($slideshow_speed=='300'){echo $selected;} ?>>300</option>
              <option value="400" <?php if($slideshow_speed=='400'){echo $selected;} ?>>400</option>
              <option value="500" <?php if($slideshow_speed=='500'){echo $selected;} ?>>500</option>
              <option value="600" <?php if($slideshow_speed=='600'){echo $selected;} ?>>600</option>
              <option value="700" <?php if($slideshow_speed=='700'){echo $selected;} ?>>700</option>
              <option value="800" <?php if($slideshow_speed=='800'){echo $selected;} ?>>800</option>
              <option value="900" <?php if($slideshow_speed=='900'){echo $selected;} ?>>900</option>
              <option value="1000" <?php if($slideshow_speed=='1000'){echo $selected;} ?>>1000</option>
              <option value="2000" <?php if($slideshow_speed=='2000'){echo $selected;} ?>>2000</option>
              <?php } else { ?>
              <option selected="selected"><?php echo $text_pleaseselect; ?></option>
              <option value="200">200</option>
              <option value="300">300</option>
              <option value="400">400</option>
              <option value="500">500</option>
              <option value="600">600</option>
              <option value="700">700</option>
              <option value="800">800</option>
              <option value="900">900</option>
              <option value="1000">1000</option>
              <option value="2000">2000</option>
              <?php } ?>
            </select></td>
        </tr>

        <tr>
          <td><?php echo $entry_pause; ?></td>
          <td colspan="3"><select name="slideshow_pause">
              <?php if ($slideshow_pause) { ?>
              <option value="1" selected="selected"><?php echo $text_true; ?></option>
              <option value="0"><?php echo $text_false; ?></option>
              <?php } else { ?>
              <option value="1"><?php echo $text_true; ?></option>
              <option value="0" selected="selected"><?php echo $text_false; ?></option>
              <?php } ?>
            </select></td>
        </tr>

        <!-- not being used yet, more slideshows to come -->
        <!--
        To add more slideshows send your awesome slideshow in static html
        to Readyman(www.alreadymade.com)
        -->
        <!--tr>
          <td><?php echo $entry_slideshowtype; ?>
          <span class="help">choose the type of slideshow to use</span>
          </td>
          <td colspan="3"><select name="slideshow_type">
              <?php if (isset($slideshow_type)) {
              $selected = "selected";
              ?>
              <option value="1" <?php if($slideshow_type=='1'){echo $selected;} ?>>Slideshow type one</option>
              <option value="2" <?php if($slideshow_type=='2'){echo $selected;} ?>>Slideshow type two</option>
              <option value="3" <?php if($slideshow_type=='3'){echo $selected;} ?>>Slideshow type three</option>
              <?php } else { ?>
              <option selected="selected"><?php echo $text_pleaseselect; ?></option>
              <option value="1">Slideshow type one</option>
              <option value="2">Slideshow type two</option>
              <option value="3">Slideshow type three</option>
              <?php } ?>
            </select></td>
        </tr-->
        <tr>
          <td><?php echo $entry_height; ?>
          <span class="help"><?php echo $help_height; ?></span>
</td>
          <td><input name="slideshow_height" type="text" value="<?php if(isset($slideshow_width)) { echo $slideshow_height; } ?>" />
</td>

          <td width="20%"><?php echo $entry_width; ?>
          <span class="help"><?php echo $help_width; ?></span>
          </td>
          <td><input name="slideshow_width" type="text" value="<?php if(isset($slideshow_width)) {echo $slideshow_width; } ?>" />
</td>
        </tr>
        <tr>
          <td colspan="4"><h3><?php echo $help_addslidehdr; ?></h3>
          <?php echo $help_addslidetext; ?>
          </td>
        </tr>
        <tr>
          <td colspan="4">
        <table id="images" class="list">
          <?php $image_row = 0; ?>
          <?php
          if(!empty($slide_images)){
                foreach ($slide_images as $key=>$slide_image) {

          //Capture errors for blank fields
          if(empty($slide_image['file'])){$preview_image = $no_image;}else{$preview_image = HTTP_IMAGE . $slide_image['file']; }
          if(!isset($slide_image['url'])){ $slide_image['url'] == ""; }
          if(!isset($slide_image['alt'])){ $slide_image['alt'] == ""; }
          if(!isset($slide_image['sortorder'])){ $slide_image['sortorder'] == ""; }

          ?>
          <tbody id="image_row<?php echo $image_row; ?>">
            <tr>
              <td class="left">
              <input type="hidden" name="slide_image[<?php echo $image_row; ?>][file]" value="<?php echo $slide_image['file']; ?>" id="image<?php echo $image_row; ?>" />
                <img src="<?php echo $preview_image; ?>" alt="" id="preview<?php echo $image_row; ?>" width="100" height="100" />&nbsp;<img src="view/image/image.png" alt="<?php echo $help_browsealt; ?>" style="cursor: pointer;" align="top" onclick="image_upload('image<?php echo $image_row; ?>', 'preview<?php echo $image_row; ?>');" title="<?php echo $help_browsetitle; ?>" /></td>
              <td><?php echo $entry_url; ?>
              <input type="text" name="slide_image[<?php echo $image_row; ?>][url]" value="<?php echo $slide_image['url'] ?>" id="url<?php echo $image_row; ?>" /><?php echo $help_linkurl; ?><br />
              <?php echo $entry_alt; ?>
              <input type="text" name="slide_image[<?php echo $image_row; ?>][alt]" value="<?php echo $slide_image['alt'] ?>" id="alt<?php echo $image_row; ?>" /><?php echo $help_alttext; ?><br />
              <?php echo $entry_target; ?>
              <select name="slide_image[<?php echo $image_row; ?>][target]">
              <?php if (isset($slide_image['target'])) {
              $selected = "selected";
              ?>
              <option value="_self" <?php if($slide_image['target']=='_self'){echo $selected;} ?>>Same Window</option>
              <option value="_new" <?php if($slide_image['target']=='_new'){echo $selected;} ?>>New Tab</option>
              <option value="_blank" <?php if($slide_image['target']=='_blank'){echo $selected;} ?>>New Window</option>
              <?php } else { ?>
              <option selected="selected"><?php echo $text_pleaseselect; ?></option>
              <option value="_self">Same Window</option>
              <option value="_new">New Tab</option>
              <option value="_blank">New Window</option>
              <?php } ?>
              </select>
              </td>
              <td class="left"><?php echo $entry_sort; ?><input type="text" name="slide_image[<?php echo $image_row; ?>][sortorder]" value="<?php echo $slide_image['sortorder'] ?>" id="sortorder<?php echo $image_row; ?>" size="5" /></td>
              <td class="left"><a onclick="$('#image_row<?php echo $image_row; ?>').remove();" class="button"><span><?php echo $button_remove; ?></span></a></td>
            </tr>
          </tbody>
          <?php $image_row++; ?>
          <?php }
          } ?>
          <tfoot>
            <tr>
              <td class="left" colspan="4"><a onclick="addImage();" class="button"><span><?php echo $button_addslide; ?></span></a></td>
            </tr>
          </tfoot>
        </table>
          </td>
        </tr>
      </table>
    </form>
  </div>
</div>
<script type="text/javascript" src="view/javascript/jquery/ui/ui.draggable.js"></script>
<script type="text/javascript" src="view/javascript/jquery/ui/ui.resizable.js"></script>
<script type="text/javascript" src="view/javascript/jquery/ui/ui.dialog.js"></script>
<script type="text/javascript" src="view/javascript/jquery/ui/external/bgiframe/jquery.bgiframe.js"></script>
<script type="text/javascript"><!--
function image_upload(field, preview) {
	$('#dialog').remove();

	$('#content').prepend('<div id="dialog" style="padding: 3px 0px 0px 0px;"><iframe src="index.php?route=common/filemanager&field=' + encodeURIComponent(field) + '&token=<?php echo $this->session->data['token']; ?>" style="padding:0; margin: 0; display: block; width: 100%; height: 100%;" frameborder="no" scrolling="auto"></iframe></div>');

	$('#dialog').dialog({
		title: '<?php echo $text_image_manager; ?>',
		close: function (event, ui) {
			if ($('#' + field).attr('value')) {
				$.ajax({
					url: 'index.php?route=common/filemanager/image&token=<?php echo $this->session->data['token']; ?>',
					type: 'POST',
					data: 'image=' + encodeURIComponent($('#' + field).attr('value')),
					dataType: 'text',
					success: function(data) {
						$('#' + preview).replaceWith('<img src="' + data + '" alt="" id="' + preview + '" style="border: 1px solid #EEEEEE;" />');
					}
				});
			}
		},
		bgiframe: false,
		width: 800,
		height: 600,
		resizable: true,
		modal: false
	});
};
//--></script>
<script type="text/javascript"><!--
var image_row = <?php echo $image_row; ?>;

function addImage() {
    html  = '<tbody id="image_row' + image_row + '">';
	html += '<tr>';
	html += '<td class="left"><input type="hidden" name="slide_image[' + image_row + '][file]" value="" id="image' + image_row + '" /><img src="<?php echo $no_image; ?>" alt="" id="preview' + image_row + '" style="margin: 4px 0px; border: 1px solid #EEEEEE;" />&nbsp;<img src="view/image/image.png" alt="<?php echo $help_browsealt; ?>" style="cursor: pointer;" align="top" onclick="image_upload(\'image' + image_row + '\', \'preview' + image_row + '\');" title="<?php echo $help_browsetitle; ?>" /></td>';
	html += '<td class="left"><?php echo $entry_url; ?><input type="text" name="slide_image[' + image_row + '][url]" value="" id="url' + image_row + '" /><?php echo $help_linkurl; ?><br />';
	html += '<?php echo $entry_alt; ?><input type="text" name="slide_image[' + image_row + '][alt]" value="" id="alt' + image_row + '" /><?php echo $help_alttext; ?></td>';
	html += '<td class="left"><?php echo $entry_sort; ?><input type="text" name="slide_image[' + image_row + '][sortorder]" value="" id="sortorder' + image_row + '" size="5" /></td>';
	html += '<td class="left"><a onclick="$(\'#image_row' + image_row  + '\').remove();" class="button"><span><?php echo $button_remove; ?></span></a></td>';
	html += '</tr>';
	html += '</tbody>';

	$('#images tfoot').before(html);

	image_row++;
}
//--></script>
<?php echo $footer; ?>