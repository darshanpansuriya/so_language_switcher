<?php echo $header; ?>
<?php if ($error_warning) { ?>
<div class="warning">
	<?php echo $error_warning; ?>
</div>
<?php } ?>
<?php if ($success) { ?>
<div class="success">
	<?php echo $success; ?>
</div>
<?php } ?>
<form action="<?php echo $action; ?>" method="post"
	enctype="multipart/form-data" id="form">
	<input type="hidden" id="where_am_i" value="" name="where_am_i" />

	<div class="h1float">
		<h1>
			Change <strong>Settings</strong>
		</h1>
	</div>

	<div class="buttons">
		<a onclick="$('#form').submit();" class="button s"><p></p> <span><strong>Save</strong>
				Settings</span> </a> &nbsp; <a
			onclick="location = '<?php echo $cancel; ?>';" class="button c"><p></p>
			<span><?php echo $button_cancel; ?> </span> </a>
	</div>

	<div class="cl" style="height: 7px;"></div>

	<div class="lefttabs" id="tabs">
		<a tab="#tab_general" id="a_general" onclick="do_setting_tabs(this);"><span>1</span>
			<div>General</div> </a> <a tab="#tab_lab_info" id="a_lab_info"
			onclick="do_setting_tabs(this);"><span>2</span>
			<div>Lab Info</div> </a> <a tab="#tab_local" id="a_local"
			onclick="do_setting_tabs(this);"><span>3</span>
			<div>Local</div> </a> <a tab="#tab_option" id="a_option"
			onclick="do_setting_tabs(this);"><span>4</span>
			<div>Option</div> </a> <a tab="#tab_mail" id="a_mail"
			onclick="do_setting_tabs(this);"><span>5</span>
			<div>Mail</div> </a> <a tab="#tab_server" id="a_server"
			onclick="do_setting_tabs(this);"><span>6</span>
			<div>Server</div> </a> <a tab="#tab_defaults" id="a_defaults"
			onclick="do_setting_tabs(this);"><span>7</span>
			<div>Ortho</div> </a> <a tab="#tab_tax" id="a_tax"
			onclick="do_setting_tabs(this);"><span>8</span>
			<div>Tax Codes</div> </a><a tab="#tab_pricing" id="a_pricing"
			onclick="do_setting_tabs(this);"><span>9</span>
			<div>Pricing</div> </a>
		<!--    	<a tab="#tab_image"><span>7</span>
        <div>Image</div></a> -->
	</div>

	<div class="main">
		<div class="main-top"></div>
		<div class="main-mid"
			style="margin-left: -1px; overflow: visible; margin-right: -1px;">
			<div id="tab_general" class="tabs">
				<h2>Please enter general info.</h2>

				<fieldset>

					<table class="form">
						<tr>
							<td><span class="required">*</span> Lab Name:</td>
							<td><input type="text" name="config_name"
								value="<?php echo $config_name; ?>" size="40" /> <?php if ($error_name) { ?>
								<span class="error"><?php echo $error_name; ?> </span> <?php } ?>
							</td>
						</tr>
						<tr>
							<td><span class="required">*</span> Lab URL:</td>
							<td><input type="text" name="config_url"
								value="<?php echo $config_url; ?>" size="40" /> <?php if ($error_url) { ?>
								<span class="error"><?php echo $error_url; ?> </span> <?php } ?>
							</td>
						</tr>
						<tr>
							<td><span class="required">*</span> Lab Owner:</td>
							<td><input type="text" name="config_owner"
								value="<?php echo $config_owner; ?>" size="40" /> <?php if ($error_owner) { ?>
								<span class="error"><?php echo $error_owner; ?> </span> <?php } ?>
							</td>
						</tr>
						<tr>
							<td><span class="required">*</span> Address:</td>
							<td><textarea name="config_address" cols="40" rows="5"><?php echo $config_address; ?></textarea>
							<?php if ($error_address) { ?> <span class="error"><?php echo $error_address; ?>
							</span> <?php } ?></td>
						</tr>
						<tr>
							<td><span class="required">*</span> <?php echo $entry_email; ?></td>
							<td><input type="text" name="config_email"
								value="<?php echo $config_email; ?>" size="40" /> <?php if ($error_email) { ?>
								<span class="error"><?php echo $error_email; ?> </span> <?php } ?>
							</td>
						</tr>
						<tr>
							<td><span class="required">*</span> <?php echo $entry_telephone; ?>
							</td>
							<td><input type="text" name="config_telephone"
								value="<?php echo $config_telephone; ?>" /> <?php if ($error_telephone) { ?>
								<span class="error"><?php echo $error_telephone; ?> </span> <?php } ?>
							</td>
						</tr>
						<tr>
							<td><?php echo $entry_fax; ?></td>
							<td><input type="text" name="config_fax"
								value="<?php echo $config_fax; ?>" /></td>
						</tr>
					</table>
				</fieldset>
			</div>
			<div id="tab_lab_info" class="tabs">
				<h2>Enter lab info.</h2>
				<fieldset>
					<table class="form">
						<tr>
							<td><span class="required">*</span> <?php echo $entry_title; ?></td>
							<td><input type="text" name="config_title"
								value="<?php echo $config_title; ?>" /> <?php if ($error_title) { ?>
								<span class="error"><?php echo $error_title; ?> </span> <?php } ?>
							</td>
						</tr>
						<tr>
							<td><?php echo $entry_meta_description; ?></td>
							<td><textarea name="config_meta_description" cols="40" rows="5">
									<?php echo $config_meta_description; ?>
								</textarea></td>
						</tr>

					</table>
					<?php foreach ($languages as $language) { ?>
					<div id="language<?php echo $language['language_id']; ?>">
						<table class="form">
							<tr>
								<td><?php echo $entry_description; ?></td>
								<td><textarea
										name="config_description_<?php echo $language['language_id']; ?>"
										id="description<?php echo $language['language_id']; ?>">
										<?php echo ${
											'config_description_' . $language['language_id']}; ?>
									</textarea></td>
							</tr>
						</table>
					</div>
					<?php } ?>



				</fieldset>
			</div>
			<div id="tab_local" class="tabs">
				<h2>Enter localization.</h2>
				<fieldset>
					<table class="form">
						<tr>
							<td><?php echo $entry_country; ?></td>
							<td><select name="config_country_id" id="country"
								onchange="$('#zone').load('index.php?route=setting/setting/zone&token=<?php echo $token; ?>&country_id=' + this.value + '&zone_id=<?php echo $config_zone_id; ?>');">
									<?php foreach ($countries as $country) { ?>
									<?php if ($country['country_id'] == $config_country_id) { ?>
									<option value="<?php echo $country['country_id']; ?>"
										selected="selected">
										<?php echo $country['name']; ?>
									</option>
									<?php } else { ?>
									<option value="<?php echo $country['country_id']; ?>">
										<?php echo $country['name']; ?>
									</option>
									<?php } ?>
									<?php } ?>
							</select></td>
						</tr>
						<tr>
							<td><?php echo $entry_zone; ?></td>
							<td><select name="config_zone_id" id="zone">
							</select></td>
						</tr>
						<tr>
							<td><?php echo $entry_language; ?></td>
							<td><select name="config_language">
									<?php foreach ($languages as $language) { ?>
									<?php if ($language['code'] == $config_language) { ?>
									<option value="<?php echo $language['code']; ?>"
										selected="selected">
										<?php echo $language['name']; ?>
									</option>
									<?php } else { ?>
									<option value="<?php echo $language['code']; ?>">
										<?php echo $language['name']; ?>
									</option>
									<?php } ?>
									<?php } ?>
							</select></td>
						</tr>
						<tr>
							<td><?php echo $entry_admin_language; ?></td>
							<td><select name="config_admin_language">
									<?php foreach ($languages as $language) { ?>
									<?php if ($language['code'] == $config_admin_language) { ?>
									<option value="<?php echo $language['code']; ?>"
										selected="selected">
										<?php echo $language['name']; ?>
									</option>
									<?php } else { ?>
									<option value="<?php echo $language['code']; ?>">
										<?php echo $language['name']; ?>
									</option>
									<?php } ?>
									<?php } ?>
							</select></td>
						</tr>
						<tr>
							<td><?php echo $entry_currency; ?></td>
							<td><select name="config_currency">
									<?php foreach ($currencies as $currency) { ?>
									<?php if ($currency['code'] == $config_currency) { ?>
									<option value="<?php echo $currency['code']; ?>"
										selected="selected">
										<?php echo $currency['title']; ?>
									</option>
									<?php } else { ?>
									<option value="<?php echo $currency['code']; ?>">
										<?php echo $currency['title']; ?>
									</option>
									<?php } ?>
									<?php } ?>
							</select></td>
						</tr>
						<tr>
							<td><?php echo $entry_currency_auto; ?></td>
							<td><?php if ($config_currency_auto) { ?> <input type="radio"
								name="config_currency_auto" value="1" checked="checked" /> <?php echo $text_yes; ?>
								<input type="radio" name="config_currency_auto" value="0" /> <?php echo $text_no; ?>
								<?php } else { ?> <input type="radio"
								name="config_currency_auto" value="1" /> <?php echo $text_yes; ?>
								<input type="radio" name="config_currency_auto" value="0"
								checked="checked" /> <?php echo $text_no; ?> <?php } ?></td>
						</tr>
						<tr>
							<td><?php echo $entry_length_class; ?></td>
							<td><select name="config_length_class">
									<?php foreach ($length_classes as $length_class) { ?>
									<?php if ($length_class['unit'] == $config_length_class) { ?>
									<option value="<?php echo $length_class['unit']; ?>"
										selected="selected">
										<?php echo $length_class['title']; ?>
									</option>
									<?php } else { ?>
									<option value="<?php echo $length_class['unit']; ?>">
										<?php echo $length_class['title']; ?>
									</option>
									<?php } ?>
									<?php } ?>
							</select></td>
						</tr>
						<tr>
							<td><?php echo $entry_weight_class; ?></td>
							<td><select name="config_weight_class">
									<?php foreach ($weight_classes as $weight_class) { ?>
									<?php if ($weight_class['unit'] == $config_weight_class) { ?>
									<option value="<?php echo $weight_class['unit']; ?>"
										selected="selected">
										<?php echo $weight_class['title']; ?>
									</option>
									<?php } else { ?>
									<option value="<?php echo $weight_class['unit']; ?>">
										<?php echo $weight_class['title']; ?>
									</option>
									<?php } ?>
									<?php } ?>
							</select></td>
						</tr>

					</table>
				</fieldset>
			</div>
			<div id="tab_option" class="tabs">
				<h2>Enter options.</h2>
				<fieldset>
					<table class="form">
						<tr>
							<td><span class="required">*</span> Default items per page:</td>
							<td><input type="text" name="config_admin_limit"
								value="<?php echo $config_admin_limit; ?>" size="3" /><input
								type="hidden" name="config_catalog_limit"
								value="<?php echo $config_catalog_limit; ?>" /> <?php if ($error_admin_limit) { ?>
								<span class="error"><?php echo $error_admin_limit; ?> </span> <?php } ?>
							</td>
						</tr>
						<?php /*?>          <tr>
											<td><?php echo $entry_tax; ?></td>
											<td><?php if ($config_tax) { ?>
											<input type="radio" name="config_tax" value="1" checked="checked" />
											<?php echo $text_yes; ?>
											<input type="radio" name="config_tax" value="0" />
											<?php echo $text_no; ?>
											<?php } else { ?>
											<input type="radio" name="config_tax" value="1" />
											<?php echo $text_yes; ?>
											<input type="radio" name="config_tax" value="0" checked="checked" />
											<?php echo $text_no; ?>
											<?php } ?></td>
											</tr>
											<tr>
											<td><?php echo $entry_invoice; ?></td>
											<td><input type="text" name="config_invoice_id" value="<?php echo $config_invoice_id; ?>" size="3" /></td>
											</tr>
											<tr>
											<td><?php echo $entry_invoice_prefix; ?></td>
											<td><input type="text" name="config_invoice_prefix" value="<?php echo $config_invoice_prefix; ?>" size="3" /></td>
											</tr>
											<tr>
											<td><?php echo $entry_clinic_price; ?></td>
											<td><?php if ($config_clinic_price) { ?>
											<input type="radio" name="config_clinic_price" value="1" checked="checked" />
											<?php echo $text_yes; ?>
											<input type="radio" name="config_clinic_price" value="0" />
											<?php echo $text_no; ?>
											<?php } else { ?>
											<input type="radio" name="config_clinic_price" value="1" />
											<?php echo $text_yes; ?>
											<input type="radio" name="config_clinic_price" value="0" checked="checked" />
											<?php echo $text_no; ?>
											<?php } ?></td>
											</tr>
											<tr>
											<td><?php echo $entry_clinic_approval; ?></td>
											<td><?php if ($config_clinic_approval) { ?>
											<input type="radio" name="config_clinic_approval" value="1" checked="checked" />
											<?php echo $text_yes; ?>
											<input type="radio" name="config_clinic_approval" value="0" />
											<?php echo $text_no; ?>
											<?php } else { ?>
											<input type="radio" name="config_clinic_approval" value="1" />
											<?php echo $text_yes; ?>
											<input type="radio" name="config_clinic_approval" value="0" checked="checked" />
											<?php echo $text_no; ?>
											<?php } ?></td>
											</tr>
											<tr>
											<td><?php echo $entry_guest_checkout; ?></td>
											<td><?php if ($config_guest_checkout) { ?>
											<input type="radio" name="config_guest_checkout" value="1" checked="checked" />
											<?php echo $text_yes; ?>
											<input type="radio" name="config_guest_checkout" value="0" />
											<?php echo $text_no; ?>
											<?php } else { ?>
											<input type="radio" name="config_guest_checkout" value="1" />
											<?php echo $text_yes; ?>
											<input type="radio" name="config_guest_checkout" value="0" checked="checked" />
											<?php echo $text_no; ?>
											<?php } ?></td>
											</tr>
											<tr>
											<td><?php echo $entry_account; ?></td>
											<td><select name="config_account_id">
											<option value="0"><?php echo $text_none; ?></option>
											<?php foreach ($informations as $information) { ?>
											<?php if ($information['information_id'] == $config_account_id) { ?>
											<option value="<?php echo $information['information_id']; ?>" selected="selected"><?php echo $information['title']; ?></option>
											<?php } else { ?>
											<option value="<?php echo $information['information_id']; ?>"><?php echo $information['title']; ?></option>
											<?php } ?>
											<?php } ?>
											</select></td>
											</tr>
											<tr>
											<td><?php echo $entry_checkout; ?></td>
											<td><select name="config_checkout_id">
											<option value="0"><?php echo $text_none; ?></option>
											<?php foreach ($informations as $information) { ?>
											<?php if ($information['information_id'] == $config_checkout_id) { ?>
											<option value="<?php echo $information['information_id']; ?>" selected="selected"><?php echo $information['title']; ?></option>
											<?php } else { ?>
											<option value="<?php echo $information['information_id']; ?>"><?php echo $information['title']; ?></option>
											<?php } ?>
											<?php } ?>
											</select></td>
											</tr>
											<tr>
											<td><?php echo $entry_stock_display; ?></td>
											<td><?php if ($config_stock_display) { ?>
											<input type="radio" name="config_stock_display" value="1" checked="checked" />
											<?php echo $text_yes; ?>
											<input type="radio" name="config_stock_display" value="0" />
											<?php echo $text_no; ?>
											<?php } else { ?>
											<input type="radio" name="config_stock_display" value="1" />
											<?php echo $text_yes; ?>
											<input type="radio" name="config_stock_display" value="0" checked="checked" />
											<?php echo $text_no; ?>
											<?php } ?></td>
											</tr>
											<tr>
											<td><?php echo $entry_stock_warning; ?></td>
											<td><?php if ($config_stock_warning) { ?>
											<input type="radio" name="config_stock_warning" value="1" checked="checked" />
											<?php echo $text_yes; ?>
											<input type="radio" name="config_stock_warning" value="0" />
											<?php echo $text_no; ?>
											<?php } else { ?>
											<input type="radio" name="config_stock_warning" value="1" />
											<?php echo $text_yes; ?>
											<input type="radio" name="config_stock_warning" value="0" checked="checked" />
											<?php echo $text_no; ?>
											<?php } ?></td>
											</tr>
											<tr>
											<td><?php echo $entry_stock_checkout; ?></td>
											<td><?php if ($config_stock_checkout) { ?>
											<input type="radio" name="config_stock_checkout" value="1" checked="checked" />
											<?php echo $text_yes; ?>
											<input type="radio" name="config_stock_checkout" value="0" />
											<?php echo $text_no; ?>
											<?php } else { ?>
											<input type="radio" name="config_stock_checkout" value="1" />
											<?php echo $text_yes; ?>
											<input type="radio" name="config_stock_checkout" value="0" checked="checked" />
											<?php echo $text_no; ?>
											<?php } ?></td>
											</tr>
											<tr>
											<td><?php echo $entry_order_status; ?></td>
											<td><select name="config_order_status_id">
											<?php foreach ($order_statuses as $order_status) { ?>
											<?php if ($order_status['order_status_id'] == $config_order_status_id) { ?>
											<option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
											<?php } else { ?>
											<option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
											<?php } ?>
											<?php } ?>
											</select></td>
											</tr>
											<tr>
											<td>Prebooked Order Status</td>
											<td><select name="config_prebooked_status_id">
											<?php foreach ($order_statuses as $order_status) { ?>
											<?php if ($order_status['order_status_id'] == $config_prebooked_status_id) { ?>
											<option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
											<?php } else { ?>
											<option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
											<?php } ?>
											<?php } ?>
											</select></td>
											</tr>
											<tr>
											<td><?php echo $entry_stock_status; ?></td>
											<td><select name="config_stock_status_id">
											<?php foreach ($stock_statuses as $stock_status) { ?>
											<?php if ($stock_status['stock_status_id'] == $config_stock_status_id) { ?>
											<option value="<?php echo $stock_status['stock_status_id']; ?>" selected="selected"><?php echo $stock_status['name']; ?></option>
											<?php } else { ?>
											<option value="<?php echo $stock_status['stock_status_id']; ?>"><?php echo $stock_status['name']; ?></option>
											<?php } ?>
											<?php } ?>
											</select></td>
											</tr>
											<tr>
											<td><?php echo $entry_review; ?></td>
											<td><?php if ($config_review) { ?>
											<input type="radio" name="config_review" value="1" checked="checked" />
											<?php echo $text_yes; ?>
											<input type="radio" name="config_review" value="0" />
											<?php echo $text_no; ?>
											<?php } else { ?>
											<input type="radio" name="config_review" value="1" />
											<?php echo $text_yes; ?>
											<input type="radio" name="config_review" value="0" checked="checked" />
											<?php echo $text_no; ?>
											<?php } ?></td>
											</tr>
											<tr>
											<td><?php echo $entry_download; ?></td>
											<td><?php if ($config_download) { ?>
											<input type="radio" name="config_download" value="1" checked="checked" />
											<?php echo $text_yes; ?>
											<input type="radio" name="config_download" value="0" />
											<?php echo $text_no; ?>
											<?php } else { ?>
											<input type="radio" name="config_download" value="1" />
											<?php echo $text_yes; ?>
											<input type="radio" name="config_download" value="0" checked="checked" />
											<?php echo $text_no; ?>
											<?php } ?></td>
											</tr>
											<tr>
											<td><?php echo $entry_download_status; ?></td>
											<td><select name="config_download_status">
											<?php foreach ($order_statuses as $order_status) { ?>
											<?php if ($order_status['order_status_id'] == $config_download_status) { ?>
											<option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
											<?php } else { ?>
											<option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
											<?php } ?>
											<?php } ?>
											</select></td>
											</tr>
											<tr>
											<td><?php echo $entry_cart_weight; ?></td>
											<td><?php if ($config_cart_weight) { ?>
											<input type="radio" name="config_cart_weight" value="1" checked="checked" />
											<?php echo $text_yes; ?>
											<input type="radio" name="config_cart_weight" value="0" />
											<?php echo $text_no; ?>
											<?php } else { ?>
											<input type="radio" name="config_cart_weight" value="1" />
											<?php echo $text_yes; ?>
											<input type="radio" name="config_cart_weight" value="0" checked="checked" />
											<?php echo $text_no; ?>
											<?php } ?></td>
											</tr>
											<tr>
											<td><?php echo $entry_shipping_session; ?></td>
											<td><?php if ($config_shipping_session) { ?>
											<input type="radio" name="config_shipping_session" value="1" checked="checked" />
											<?php echo $text_yes; ?>
											<input type="radio" name="config_shipping_session" value="0" />
											<?php echo $text_no; ?>
											<?php } else { ?>
											<input type="radio" name="config_shipping_session" value="1" />
											<?php echo $text_yes; ?>
											<input type="radio" name="config_shipping_session" value="0" checked="checked" />
											<?php echo $text_no; ?>
											<?php } ?></td>
          </tr><?php */?>
					</table>
				</fieldset>
			</div>
			<div id="tab_image" class="tabs" style="display: none;">
				<h2>Enter image parameters.</h2>
				<fieldset>
					<table class="form">
						<?php /*?>          <tr>
            <td><?php echo $entry_logo; ?></td>
											<td><input type="hidden" name="config_logo" value="<?php echo $config_logo; ?>" id="logo" />
											<img src="<?php echo $preview_logo; ?>" alt="" id="preview_logo" class="image" onclick="image_upload('logo', 'preview_logo');" /></td>
											</tr>
											<tr>
											<td><?php echo $entry_icon; ?></td>
											<td><input type="hidden" name="config_icon" value="<?php echo $config_icon; ?>" id="icon" />
											<img src="<?php echo $preview_icon; ?>" alt="" id="preview_icon" class="image" onclick="image_upload('icon', 'preview_icon');" /></td>
          </tr><?php */?>
						<tr>
							<td><span class="required">*</span> <?php echo $entry_image_thumb; ?>
							</td>
							<td><input type="text" name="config_image_thumb_width"
								value="<?php echo $config_image_thumb_width; ?>" size="3" /> x <input
								type="text" name="config_image_thumb_height"
								value="<?php echo $config_image_thumb_height; ?>" size="3" /> <?php if ($error_image_thumb) { ?>
								<span class="error"><?php echo $error_image_thumb; ?> </span> <?php } ?>
							</td>
						</tr>
						<tr>
							<td><span class="required">*</span> <?php echo $entry_image_popup; ?>
							</td>
							<td><input type="text" name="config_image_popup_width"
								value="<?php echo $config_image_popup_width; ?>" size="3" /> x <input
								type="text" name="config_image_popup_height"
								value="<?php echo $config_image_popup_height; ?>" size="3" /> <?php if ($error_image_popup) { ?>
								<span class="error"><?php echo $error_image_popup; ?> </span> <?php } ?>
							</td>
						</tr>
						<tr>
							<td><span class="required">*</span> <?php echo $entry_image_category; ?>
							</td>
							<td><input type="text" name="config_image_category_width"
								value="<?php echo $config_image_category_width; ?>" size="3" />
								x <input type="text" name="config_image_category_height"
								value="<?php echo $config_image_category_height; ?>" size="3" />
								<?php if ($error_image_category) { ?> <span class="error"><?php echo $error_image_category; ?>
							</span> <?php } ?></td>
						</tr>
						<tr>
							<td><span class="required">*</span> <?php echo $entry_image_product; ?>
							</td>
							<td><input type="text" name="config_image_product_width"
								value="<?php echo $config_image_product_width; ?>" size="3" /> x
								<input type="text" name="config_image_product_height"
								value="<?php echo $config_image_product_height; ?>" size="3" />
								<?php if ($error_image_product) { ?> <span class="error"><?php echo $error_image_product; ?>
							</span> <?php } ?></td>
						</tr>
						<tr>
							<td><span class="required">*</span> <?php echo $entry_image_additional; ?>
							</td>
							<td><input type="text" name="config_image_additional_width"
								value="<?php echo $config_image_additional_width; ?>" size="3" />
								x <input type="text" name="config_image_additional_height"
								value="<?php echo $config_image_additional_height; ?>" size="3" />
								<?php if ($error_image_additional) { ?> <span class="error"><?php echo $error_image_additional; ?>
							</span> <?php } ?></td>
						</tr>
						<tr>
							<td><span class="required">*</span> <?php echo $entry_image_related; ?>
							</td>
							<td><input type="text" name="config_image_related_width"
								value="<?php echo $config_image_related_width; ?>" size="3" /> x
								<input type="text" name="config_image_related_height"
								value="<?php echo $config_image_related_height; ?>" size="3" />
								<?php if ($error_image_related) { ?> <span class="error"><?php echo $error_image_related; ?>
							</span> <?php } ?></td>
						</tr>
						<tr>
							<td><span class="required">*</span> <?php echo $entry_image_cart; ?>
							</td>
							<td><input type="text" name="config_image_cart_width"
								value="<?php echo $config_image_cart_width; ?>" size="3" /> x <input
								type="text" name="config_image_cart_height"
								value="<?php echo $config_image_cart_height; ?>" size="3" /> <?php if ($error_image_cart) { ?>
								<span class="error"><?php echo $error_image_cart; ?> </span> <?php } ?>
							</td>
						</tr>
					</table>
				</fieldset>
			</div>
			<div id="tab_mail" class="tabs">
				<h2>Enter mail parameters.</h2>
				<fieldset>
					<table class="form">
						<tr>
							<td><?php echo $entry_mail_protocol; ?></td>
							<td><select name="config_mail_protocol">
									<?php if ($config_mail_protocol == 'mail') { ?>
									<option value="mail" selected="selected">
										<?php echo $text_mail; ?>
									</option>
									<?php } else { ?>
									<option value="mail">
										<?php echo $text_mail; ?>
									</option>
									<?php } ?>
									<?php if ($config_mail_protocol == 'smtp') { ?>
									<option value="smtp" selected="selected">
										<?php echo $text_smtp; ?>
									</option>
									<?php } else { ?>
									<option value="smtp">
										<?php echo $text_smtp; ?>
									</option>
									<?php } ?>
							</select></td>
						</tr>
						<tr>
							<td><?php echo $entry_mail_parameter; ?></td>
							<td><input type="text" name="config_mail_parameter"
								value="<?php echo $config_mail_parameter; ?>" /></td>
						</tr>
						<tr>
							<td><?php echo $entry_smtp_host; ?></td>
							<td><input type="text" name="config_smtp_host"
								value="<?php echo $config_smtp_host; ?>" /></td>
						</tr>
						<tr>
							<td><?php echo $entry_smtp_username; ?></td>
							<td><input type="text" name="config_smtp_username"
								value="<?php echo $config_smtp_username; ?>" /></td>
						</tr>
						<tr>
							<td><?php echo $entry_smtp_password; ?></td>
							<td><input type="text" name="config_smtp_password"
								value="<?php echo $config_smtp_password; ?>" /></td>
						</tr>
						<tr>
							<td><?php echo $entry_smtp_port; ?></td>
							<td><input type="text" name="config_smtp_port"
								value="<?php echo $config_smtp_port; ?>" /></td>
						</tr>
						<tr>
							<td><?php echo $entry_smtp_timeout; ?></td>
							<td><input type="text" name="config_smtp_timeout"
								value="<?php echo $config_smtp_timeout; ?>" /></td>
						</tr>
						<!--          <tr>
            <td><?php echo $entry_alert_mail; ?></td>
            <td><?php if ($config_alert_mail) { ?>
              <input type="radio" name="config_alert_mail" value="1" checked="checked" />
              <?php echo $text_yes; ?>
              <input type="radio" name="config_alert_mail" value="0" />
              <?php echo $text_no; ?>
              <?php } else { ?>
              <input type="radio" name="config_alert_mail" value="1" />
              <?php echo $text_yes; ?>
              <input type="radio" name="config_alert_mail" value="0" checked="checked" />
              <?php echo $text_no; ?>
              <?php } ?></td>
          </tr>-->
						<?php /*?>          <tr>
              <td>Alert Broker:</td>
              <td><?php if ($config_alert_broker) { ?>
              <input type="radio" name="config_alert_broker" value="1" checked="checked" />
              <?php echo $text_yes; ?>
              <input type="radio" name="config_alert_broker" value="0" />
              <?php echo $text_no; ?>
              <?php } else { ?>
              <input type="radio" name="config_alert_broker" value="1" />
              <?php echo $text_yes; ?>
              <input type="radio" name="config_alert_broker" value="0" checked="checked" />
              <?php echo $text_no; ?>
              <?php } ?></td>
              </tr>
              <?php */?>
						<tr>
							<td><?php echo $entry_alert_emails; ?></td>
							<td><textarea name="config_alert_emails" cols="40" rows="5">
									<?php echo $config_alert_emails; ?>
								</textarea></td>
						</tr>
					</table>
				</fieldset>
			</div>
			<div id="tab_server" class="tabs">
				<h2>Enter server parameters.</h2>
				<fieldset>
					<table class="form">
						<tr>
							<?php /*?>            <td><?php echo $entry_ssl; ?></td>
            <td><?php if ($config_ssl) { ?>
              <input type="radio" name="config_ssl" value="1" checked="checked" />
              <?php echo $text_yes; ?>
              <input type="radio" name="config_ssl" value="0" />
              <?php echo $text_no; ?>
              <?php } else { ?>
              <input type="radio" name="config_ssl" value="1" />
              <?php echo $text_yes; ?>
              <input type="radio" name="config_ssl" value="0" checked="checked" />
              <?php echo $text_no; ?>
              <?php } ?></td>
              </tr>
              <tr>
              <td><?php echo $entry_maintenance; ?></td>
              <td><?php if ($config_maintenance) { ?>
              <input type="radio" name="config_maintenance" value="1" checked="checked" />
              <?php echo $text_yes; ?>
              <input type="radio" name="config_maintenance" value="0" />
              <?php echo $text_no; ?>
              <?php } else { ?>
              <input type="radio" name="config_maintenance" value="1" />
              <?php echo $text_yes; ?>
              <input type="radio" name="config_maintenance" value="0" checked="checked" />
              <?php echo $text_no; ?>
              <?php } ?></td>
              </tr>
              <tr>
              <td><?php echo $entry_encryption; ?></td>
              <td><input type="text" name="config_encryption" value="<?php echo $config_encryption; ?>" /></td>
              </tr>
              <tr>
              <td><?php echo $entry_seo_url; ?></td>
              <td><?php if ($config_seo_url) { ?>
              <input type="radio" name="config_seo_url" value="1" checked="checked" />
              <?php echo $text_yes; ?>
              <input type="radio" name="config_seo_url" value="0" />
              <?php echo $text_no; ?>
              <?php } else { ?>
              <input type="radio" name="config_seo_url" value="1" />
              <?php echo $text_yes; ?>
              <input type="radio" name="config_seo_url" value="0" checked="checked" />
              <?php echo $text_no; ?>
              <?php } ?></td>
              </tr>
              <tr>
              <td><?php echo $entry_compression; ?></td>
              <td><input type="text" name="config_compression" value="<?php echo $config_compression; ?>" size="3" /></td>
              </tr>
              <tr>
<?php */?>
							<td><?php echo $entry_error_display; ?></td>
							<td><?php if ($config_error_display) { ?> <input type="radio"
								name="config_error_display" value="1" checked="checked" /> <?php echo $text_yes; ?>
								<input type="radio" name="config_error_display" value="0" /> <?php echo $text_no; ?>
								<?php } else { ?> <input type="radio"
								name="config_error_display" value="1" /> <?php echo $text_yes; ?>
								<input type="radio" name="config_error_display" value="0"
								checked="checked" /> <?php echo $text_no; ?> <?php } ?></td>
						</tr>
						<tr>
							<td><?php echo $entry_error_log; ?></td>
							<td><?php if ($config_error_log) { ?> <input type="radio"
								name="config_error_log" value="1" checked="checked" /> <?php echo $text_yes; ?>
								<input type="radio" name="config_error_log" value="0" /> <?php echo $text_no; ?>
								<?php } else { ?> <input type="radio" name="config_error_log"
								value="1" /> <?php echo $text_yes; ?> <input type="radio"
								name="config_error_log" value="0" checked="checked" /> <?php echo $text_no; ?>
								<?php } ?></td>
						</tr>
						<tr>
							<td><span class="required">*</span> <?php echo $entry_error_filename; ?>
							</td>
							<td><input type="text" name="config_error_filename"
								value="<?php echo $config_error_filename; ?>" /> <?php if ($error_error_filename) { ?>
								<span class="error"><?php echo $error_error_filename; ?> </span>
								<?php } ?></td>
						</tr>
						<?php /*?>          <tr>
          <td><?php echo $entry_token_ignore; ?></td>
              <td><div class="scrollbox">
              <?php $class = 'odd'; ?>
              <?php foreach ($tokens as $ignore_token) { ?>
              <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
              <div class="<?php echo $class; ?>">
              <?php if (in_array($ignore_token, $config_token_ignore)) { ?>
              <input type="checkbox" name="config_token_ignore[]" value="<?php echo $ignore_token; ?>" checked="checked" />
              <?php echo $ignore_token; ?>
              <?php } else { ?>
              <input type="checkbox" name="config_token_ignore[]" value="<?php echo $ignore_token; ?>" />
              <?php echo $ignore_token; ?>
              <?php } ?>
              </div>
              <?php } ?>
              </div></td>
              </tr>
<?php */?>
					</table>
				</fieldset>
			</div>
			<div id="tab_defaults" class="tabs">
				<h2>Enter ortho defaults.</h2>
				<br /> Define ortho defaults separately from other tabs... After
				selecting default values for each Ortho Type, please save...<br /> <br />

				<fieldset>
					<label
						style="float: left; color: #084598; font-size: 13px; font-weight: bold;">Select
						Ortho Type: </label> &nbsp; <select name="o_id" id="o_id"
						onchange="do_ortho_content()">
						<option value="">Select...</option>
						<option value="99999">Default Values for All...</option>
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
									echo '<option value="'.$line_name['lookup_table_id'].'">'.$title.' - '.$line_name['lookup_table_title'].'</option>';
								}
							}
						}
						?>
					</select><br /> <br />


					<div id="ortho_content"></div>
				</fieldset>
			</div>

			<div id="tab_tax" class="tabs">
				<h2>Defaults for Tax rates and Tax codes</h2>
				<br /> Setup the tax codes and tax rates in this screen.<br /> <br />

				<h3 style="color: #00549F !important; font-weight: normal;">Setup
					Tax Rates</h3>
				<br /> Specify the list of tax rates below.<br /> <br />
				<table class="form">
					<tr>
						<td>Tax Code</td>
						<td
							style="float: left; color: #084598; font-size: 13px; font-weight: bold;">Value</td>
					</tr>
					<tr>
						<td>HST</td>
						<td><input type="text" size="4" class="tax_rate_fields" name="tax_rate_hst" id="tax_rate_hst" value="<?php echo $tax_rate_hst; ?>">%</td>
					</tr>
					<tr>
						<td>PST</td>
						<td><input type="text" size="4" class="tax_rate_fields" name="tax_rate_pst" value="<?php echo $tax_rate_pst;  ?>">%</td>
					</tr>
					<tr>
						<td>GST</td>
						<td><input type="text" size="4" class="tax_rate_fields" name="tax_rate_gst" value="<?php echo $tax_rate_gst?>" >%</td>
					</tr>
					<tr>
						<td>Z</td>
						<td><input type="text" size="4" class="tax_rate_fields" name="tax_rate_z"  value="<?php echo $tax_rate_z?>">%</td>
					</tr>
				</table>
				<br> <br>
				<h3 style="color: #00549F !important; font-weight: normal;">Setup
					Tax Codes</h3>
				<br /> Specify the list of tax codes below for each ortho type /
				expense item..<br /> <br />
				<table class="form">
					<tr>
						<td
							style="color: #084598; font-size: 13px; font-weight: bold; width: 300px;">Ortho
							Type / Item</td>
						<td
							style="float: left; color: #084598; font-size: 13px; font-weight: bold; width: 160px;">Category</td>
						<td
							style="float: left; color: #084598; font-size: 13px; font-weight: bold; width: 160px;">Short
							Form</td>
						<td
							style="float: left; color: #084598; font-size: 13px; font-weight: bold;">Tax
							Code</td>
					</tr>
					<tr>
						<td class="tax-ortho-type">Custom Direct Milled</td>
						<td class="tax-ortho-code">Ortho Type</td>
						<td class="tax-ortho-code"><input type="text" class="short_form_fields" name="short_code_1" value="<?php echo $short_code_1;?>" size="6"></td>
						<td class="ortho-tax-type">
						<select name="tax_type_1">
								<option <?php if($tax_type_1==4) echo 'selected'; ?> value="4">Z</option>
								<option <?php if($tax_type_1==1) echo 'selected'; ?> value="1">HST</option>
								<option <?php if($tax_type_1==2) echo 'selected'; ?> value="2">PST</option>
								<option <?php if($tax_type_1==3) echo 'selected'; ?> value="3">GST</option>
						</select></td>
					</tr>
					<tr>
						<td class="tax-ortho-type">Custom Vacuum Pressed</td>
						<td class="tax-ortho-code">Ortho Type</td>
						<td class="tax-ortho-code"><input type="text" class="short_form_fields" name="short_code_2" value="<?php echo $short_code_2;?>" size="6"></td>
						<td class="ortho-tax-type">
						<select name="tax_type_2">
								<option <?php if($tax_type_2==4) echo 'selected'; ?> value="4">Z</option>
								<option <?php if($tax_type_2==1) echo 'selected'; ?> value="1">HST</option>
								<option <?php if($tax_type_2==2) echo 'selected'; ?> value="2">PST</option>
								<option <?php if($tax_type_2==3) echo 'selected'; ?> value="3">GST</option>
						</select></td>
					</tr>
					<tr>
						<td class="tax-ortho-type">Functional Standard</td>
						<td class="tax-ortho-code">Ortho Type</td>
						<td class="tax-ortho-code"><input type="text" class="short_form_fields" name="short_code_3" value="<?php echo $short_code_3;?>" size="6"></td>
						<td class="ortho-tax-type">
						<select name="tax_type_3">
								<option <?php if($tax_type_3==4) echo 'selected'; ?> value="4">Z</option>
								<option <?php if($tax_type_3==1) echo 'selected'; ?> value="1">HST</option>
								<option <?php if($tax_type_3==2) echo 'selected'; ?> value="2">PST</option>
								<option <?php if($tax_type_3==3) echo 'selected'; ?> value="3">GST</option>
						</select></td>
					</tr>
					<tr>
						<td class="tax-ortho-type">Functional Standard Low</td>
						<td class="tax-ortho-code">Ortho Type</td>
						<td class="tax-ortho-code"><input type="text" class="short_form_fields" name="short_code_4" value="<?php echo $short_code_4;?>" size="6"></td>
						<td class="ortho-tax-type">
						<select name="tax_type_4">
								<option <?php if($tax_type_4==4) echo 'selected'; ?> value="4">Z</option>
								<option <?php if($tax_type_4==1) echo 'selected'; ?> value="1">HST</option>
								<option <?php if($tax_type_4==2) echo 'selected'; ?> value="2">PST</option>
								<option <?php if($tax_type_4==3) echo 'selected'; ?> value="3">GST</option>
						</select></td>
					</tr>
					<tr>
						<td class="tax-ortho-type">Functional Standard Full</td>
						<td class="tax-ortho-code">Ortho Type</td>
						<td class="tax-ortho-code"><input type="text" class="short_form_fields" name="short_code_5" value="<?php echo $short_code_5;?>" size="6"></td>
						<td class="ortho-tax-type">
						<select name="tax_type_5">
								<option <?php if($tax_type_5==4) echo 'selected'; ?> value="4">Z</option>
								<option <?php if($tax_type_5==1) echo 'selected'; ?> value="1">HST</option>
								<option <?php if($tax_type_5==2) echo 'selected'; ?> value="2">PST</option>
								<option <?php if($tax_type_5==3) echo 'selected'; ?> value="3">GST</option>
						</select></td>
					</tr>
					<tr>
						<td class="tax-ortho-type">Sport Impact</td>
						<td class="tax-ortho-code">Ortho Type</td>
						<td class="tax-ortho-code"><input type="text" class="short_form_fields" name="short_code_6" value="<?php echo $short_code_6;?>" size="6"></td>
						<td style="float: left;">
						<select name="tax_type_6">
								<option <?php if($tax_type_6==4) echo 'selected'; ?> value="4">Z</option>
								<option <?php if($tax_type_6==1) echo 'selected'; ?> value="1">HST</option>
								<option <?php if($tax_type_6==2) echo 'selected'; ?> value="2">PST</option>
								<option <?php if($tax_type_6==3) echo 'selected'; ?> value="3">GST</option>
						</select></td>
					</tr>
					<tr>
						<td class="tax-ortho-type">Sport SOS</td>
						<td class="tax-ortho-code">Ortho Type</td>
						<td class="tax-ortho-code"><input type="text" class="short_form_fields" name="short_code_7" value="<?php echo $short_code_7;?>" size="6"></td>
						<td class="ortho-tax-type">
						<select name="tax_type_7">
								<option <?php if($tax_type_7==4) echo 'selected'; ?> value="4">Z</option>
								<option <?php if($tax_type_7==1) echo 'selected'; ?> value="1">HST</option>
								<option <?php if($tax_type_7==2) echo 'selected'; ?> value="2">PST</option>
								<option <?php if($tax_type_7==3) echo 'selected'; ?> value="3">GST</option>
						</select></td>
					</tr>
					<tr>
						<td class="tax-ortho-type">Sport Golf</td>
						<td class="tax-ortho-code">Ortho Type</td>
						<td class="tax-ortho-code"><input type="text" class="short_form_fields" name="short_code_8" value="<?php echo $short_code_8;?>" size="6"></td>
						<td style="float: left;">
						<select name="tax_type_8">
								<option <?php if($tax_type_8==4) echo 'selected'; ?> value="4">Z</option>
								<option <?php if($tax_type_8==1) echo 'selected'; ?> value="1">HST</option>
								<option <?php if($tax_type_8==2) echo 'selected'; ?> value="2">PST</option>
								<option <?php if($tax_type_8==3) echo 'selected'; ?> value="3">GST</option>
						</select></td>
					</tr>
					<tr>
						<td class="tax-ortho-type">Accommodative Gentle</td>
						<td class="tax-ortho-code">Ortho Type</td>
						<td class="tax-ortho-code"><input type="text" class="short_form_fields" name="short_code_9" value="<?php echo $short_code_9;?>" size="6"></td>
						<td class="ortho-tax-type">
						<select name="tax_type_9">
								<option <?php if($tax_type_9==4) echo 'selected'; ?> value="4">Z</option>
								<option <?php if($tax_type_9==1) echo 'selected'; ?> value="1">HST</option>
								<option <?php if($tax_type_9==2) echo 'selected'; ?> value="2">PST</option>
								<option <?php if($tax_type_9==3) echo 'selected'; ?> value="3">GST</option>
						</select></td>
					</tr>
					<tr>
						<td class="tax-ortho-type">Dress Women's</td>
						<td class="tax-ortho-code">Ortho Type</td>
						<td class="tax-ortho-code"><input type="text" class="short_form_fields" name="short_code_10" value="<?php echo $short_code_10;?>" size="6"></td>
						<td class="ortho-tax-type">
						<select name="tax_type_10">
								<option <?php if($tax_type_10==4) echo 'selected'; ?> value="4">Z</option>
								<option <?php if($tax_type_10==1) echo 'selected'; ?> value="1">HST</option>
								<option <?php if($tax_type_10==2) echo 'selected'; ?> value="2">PST</option>
								<option <?php if($tax_type_10==3) echo 'selected'; ?> value="3">GST</option>
						</select></td>
					</tr>
					<tr>
						<td class="tax-ortho-type">Dress Flat Cup</td>
						<td class="tax-ortho-code">Ortho Type</td>
						<td class="tax-ortho-code"><input type="text" class="short_form_fields" name="short_code_11" value="<?php echo $short_code_11;?>" size="6"></td>
						<td class="ortho-tax-type">
						<select name="tax_type_11">
								<option <?php if($tax_type_11==4) echo 'selected'; ?> value="4">Z</option>
								<option <?php if($tax_type_11==1) echo 'selected'; ?> value="1">HST</option>
								<option <?php if($tax_type_11==2) echo 'selected'; ?> value="2">PST</option>
								<option <?php if($tax_type_11==3) echo 'selected'; ?> value="3">GST</option>
						</select></td>
					</tr>
					<tr>
						<td class="tax-ortho-type">Dress Men's</td>
						<td class="tax-ortho-code">Ortho Type</td>
						<td class="tax-ortho-code"><input type="text" class="short_form_fields" name="short_code_12" value="<?php echo $short_code_12;?>" size="6"></td>
						<td class="ortho-tax-type">
						<select name="tax_type_12">
								<option <?php if($tax_type_12==4) echo 'selected'; ?> value="4">Z</option>
								<option <?php if($tax_type_12==1) echo 'selected'; ?> value="1">HST</option>
								<option <?php if($tax_type_12==2) echo 'selected'; ?> value="2">PST</option>
								<option <?php if($tax_type_12==3) echo 'selected'; ?> value="3">GST</option>
						</select></td>
					</tr>
					<tr>
						<td class="tax-ortho-type">Specialty Diabetic</td>
						<td class="tax-ortho-code">Ortho Type</td>
						<td class="tax-ortho-code"><input type="text" class="short_form_fields" name="short_code_13" value="<?php echo $short_code_13;?>" size="6"></td>
						<td class="ortho-tax-type">
						<select name="tax_type_13">
								<option <?php if($tax_type_13==4) echo 'selected'; ?> value="4">Z</option>
								<option <?php if($tax_type_13==1) echo 'selected'; ?> value="1">HST</option>
								<option <?php if($tax_type_13==2) echo 'selected'; ?> value="2">PST</option>
								<option <?php if($tax_type_13==3) echo 'selected'; ?> value="3">GST</option>
						</select></td>
					</tr>
					<tr>
						<td class="tax-ortho-type">Specialty UCBL Met</td>
						<td class="tax-ortho-code">Ortho Type</td>
						<td class="tax-ortho-code"><input type="text" class="short_form_fields" name="short_code_14" value="<?php echo $short_code_14;?>" size="6"></td>
						<td class="ortho-tax-type">
						<select name="tax_type_14">
								<option <?php if($tax_type_14==4) echo 'selected'; ?> value="4">Z</option>
								<option <?php if($tax_type_14==1) echo 'selected'; ?> value="1">HST</option>
								<option <?php if($tax_type_14==2) echo 'selected'; ?> value="2">PST</option>
								<option <?php if($tax_type_14==3) echo 'selected'; ?> value="3">GST</option>
						</select></td>
					</tr>
					<tr>
						<td class="tax-ortho-type">Specialty UCBL Full</td>
						<td class="tax-ortho-code">Ortho Type</td>
						<td class="tax-ortho-code"><input type="text" class="short_form_fields" name="short_code_15" value="<?php echo $short_code_15;?>" size="6"></td>
						<td class="ortho-tax-type">
						<select name="tax_type_15">
								<option <?php if($tax_type_15==4) echo 'selected'; ?> value="4">Z</option>
								<option <?php if($tax_type_15==1) echo 'selected'; ?> value="1">HST</option>
								<option <?php if($tax_type_15==2) echo 'selected'; ?> value="2">PST</option>
								<option <?php if($tax_type_15==3) echo 'selected'; ?> value="3">GST</option>
						</select></td>
					</tr>
					<tr>
						<td class="tax-ortho-type">Specialty Roberts Whitman Full</td>
						<td class="tax-ortho-code">Ortho Type</td>
						<td class="tax-ortho-code"><input type="text" class="short_form_fields" name="short_code_16" value="<?php echo $short_code_16;?>" size="6"></td>
						<td class="ortho-tax-type">
						<select name="tax_type_16">
								<option <?php if($tax_type_16==4) echo 'selected'; ?> value="4">Z</option>
								<option <?php if($tax_type_16==1) echo 'selected'; ?> value="1">HST</option>
								<option <?php if($tax_type_16==2) echo 'selected'; ?> value="2">PST</option>
								<option <?php if($tax_type_16==3) echo 'selected'; ?> value="3">GST</option>
						</select></td>
					</tr>
					<tr>
						<td class="tax-ortho-type">Leather Rate</td>
						<td class="tax-ortho-code">Expense Item</td>
						<td class="tax-ortho-code"><input type="text" class="short_form_fields" name="short_code_17" value="<?php echo $short_code_17;?>" size="6"></td>
						<td class="ortho-tax-type">
						<select name="tax_type_17">
								<option <?php if($tax_type_17==4) echo 'selected'; ?> value="4">Z</option>
								<option <?php if($tax_type_17==1) echo 'selected'; ?> value="1">HST</option>
								<option <?php if($tax_type_17==2) echo 'selected'; ?> value="2">PST</option>
								<option <?php if($tax_type_17==3) echo 'selected'; ?> value="3">GST</option>
						</select></td>
					</tr>
					<tr>
						<td class="tax-ortho-type">Arch Fill Rate</td>
						<td class="tax-ortho-code">Expense Item</td>
						<td class="tax-ortho-code"><input type="text" class="short_form_fields" name="short_code_18" value="<?php echo $short_code_18;?>" size="6"></td>
						<td class="ortho-tax-type">
						<select name="tax_type_18">
								<option <?php if($tax_type_18==4) echo 'selected'; ?> value="4">Z</option>
								<option <?php if($tax_type_18==1) echo 'selected'; ?> value="1">HST</option>
								<option <?php if($tax_type_18==2) echo 'selected'; ?> value="2">PST</option>
								<option <?php if($tax_type_18==3) echo 'selected'; ?> value="3">GST</option>
						</select></td>
					</tr>
					<tr>
						<td class="tax-ortho-type">FF Ext. Rate</td>
						<td class="tax-ortho-code">Expense Item</td>
						<td class="tax-ortho-code"><input type="text" class="short_form_fields" name="short_code_19" value="<?php echo $short_code_19;?>" size="6"></td>
						<td class="ortho-tax-type">
						<select name="tax_type_19">
								<option <?php if($tax_type_19==4) echo 'selected'; ?> value="4">Z</option>
								<option <?php if($tax_type_19==1) echo 'selected'; ?> value="1">HST</option>
								<option <?php if($tax_type_19==2) echo 'selected'; ?> value="2">PST</option>
								<option <?php if($tax_type_19==3) echo 'selected'; ?> value="3">GST</option>
						</select></td>
					</tr>
					<tr>
						<td class="tax-ortho-type">Midlayer Rate</td>
						<td class="tax-ortho-code">Expense Item</td>
						<td class="tax-ortho-code"><input type="text" class="short_form_fields" name="short_code_20" value="<?php echo $short_code_20;?>" size="6"></td>
						<td class="ortho-tax-type">
						<select name="tax_type_20">
								<option <?php if($tax_type_20==4) echo 'selected'; ?> value="4">Z</option>
								<option <?php if($tax_type_20==1) echo 'selected'; ?> value="1">HST</option>
								<option <?php if($tax_type_20==2) echo 'selected'; ?> value="2">PST</option>
								<option <?php if($tax_type_20==3) echo 'selected'; ?> value="3">GST</option>
						</select></td>
					</tr>
					<tr>
						<td class="tax-ortho-type">FFEVA Post Rate</td>
						<td class="tax-ortho-code">Expense Item</td>
						<td class="tax-ortho-code"><input type="text" class="short_form_fields" name="short_code_21" value="<?php echo $short_code_21;?>" size="6"></td>
						<td class="ortho-tax-type">
						<select name="tax_type_21">
								<option <?php if($tax_type_21==4) echo 'selected'; ?> value="4">Z</option>
								<option <?php if($tax_type_21==1) echo 'selected'; ?> value="1">HST</option>
								<option <?php if($tax_type_21==2) echo 'selected'; ?> value="2">PST</option>
								<option <?php if($tax_type_21==3) echo 'selected'; ?> value="3">GST</option>
						</select></td>
					</tr>
					<tr>
						<td class="tax-ortho-type">RFEVA Post Rate</td>
						<td class="tax-ortho-code">Expense Item</td>
						<td class="tax-ortho-code"><input type="text" class="short_form_fields" name="short_code_22" value="<?php echo $short_code_22;?>" size="6"></td>
						<td class="ortho-tax-type">
						<select name="tax_type_22">
								<option <?php if($tax_type_22==4) echo 'selected'; ?> value="4">Z</option>
								<option <?php if($tax_type_22==1) echo 'selected'; ?> value="1">HST</option>
								<option <?php if($tax_type_22==2) echo 'selected'; ?> value="2">PST</option>
								<option <?php if($tax_type_22==3) echo 'selected'; ?> value="3">GST</option>
						</select></td>
					</tr>
					<tr>
						<td class="tax-ortho-type">EVA Shell Rate</td>
						<td class="tax-ortho-code">Expense Item</td>
						<td class="tax-ortho-code"><input type="text" class="short_form_fields" name="short_code_23" value="<?php echo $short_code_23;?>" size="6"></td>
						<td class="ortho-tax-type">
						<select name="tax_type_23">
								<option <?php if($tax_type_23==4) echo 'selected'; ?> value="4">Z</option>
								<option <?php if($tax_type_23==1) echo 'selected'; ?> value="1">HST</option>
								<option <?php if($tax_type_23==2) echo 'selected'; ?> value="2">PST</option>
								<option <?php if($tax_type_23==3) echo 'selected'; ?> value="3">GST</option>
						</select></td>
					</tr>
					<tr>
						<td class="tax-ortho-type">Additions Rate</td>
						<td class="tax-ortho-code">Expense Item</td>
						<td class="tax-ortho-code"><input type="text" class="short_form_fields" name="short_code_24" value="<?php echo $short_code_24;?>" size="6"></td>
						<td class="ortho-tax-type">
						<select name="tax_type_24">
								<option <?php if($tax_type_24==4) echo 'selected'; ?> value="4">Z</option>
								<option <?php if($tax_type_24==1) echo 'selected'; ?> value="1">HST</option>
								<option <?php if($tax_type_24==2) echo 'selected'; ?> value="2">PST</option>
								<option <?php if($tax_type_24==3) echo 'selected'; ?> value="3">GST</option>
						</select></td>
					</tr>
					<tr>
						<td class="tax-ortho-type">Repeat Discount</td>
						<td class="tax-ortho-code">Expense Item</td>
						<td class="tax-ortho-code"><input type="text" class="short_form_fields" name="short_code_25" value="<?php echo $short_code_25;?>" size="6"></td>
						<td class="ortho-tax-type">
						<select name="tax_type_25">
								<option <?php if($tax_type_25==4) echo 'selected'; ?> value="4">Z</option>
								<option <?php if($tax_type_25==1) echo 'selected'; ?> value="1">HST</option>
								<option <?php if($tax_type_25==2) echo 'selected'; ?> value="2">PST</option>
								<option <?php if($tax_type_25==3) echo 'selected'; ?> value="3">GST</option>
						</select></td>
					</tr>
					<tr>
						<td class="tax-ortho-type">Single Surcharge</td>
						<td class="tax-ortho-code">Expense Item</td>
						<td class="tax-ortho-code"><input type="text" class="short_form_fields" name="short_code_26" value="<?php echo $short_code_26;?>" size="6"></td>
						<td class="ortho-tax-type">
						<select name="tax_type_26">
								<option <?php if($tax_type_26==4) echo 'selected'; ?> value="4">Z</option>
								<option <?php if($tax_type_26==1) echo 'selected'; ?> value="1">HST</option>
								<option <?php if($tax_type_26==2) echo 'selected'; ?> value="2">PST</option>
								<option <?php if($tax_type_26==3) echo 'selected'; ?> value="3">GST</option>
						</select></td>
					</tr>
					<tr>
						<td class="tax-ortho-type">Rush Rate</td>
						<td class="tax-ortho-code">Expense Item</td>
						<td class="tax-ortho-code"><input type="text" class="short_form_fields" name="short_code_27" value="<?php echo $short_code_27;?>" size="6"></td>
						<td class="ortho-tax-type">
						<select name="tax_type_27">
								<option <?php if($tax_type_27==4) echo 'selected'; ?> value="4">Z</option>
								<option <?php if($tax_type_27==1) echo 'selected'; ?> value="1">HST</option>
								<option <?php if($tax_type_27==2) echo 'selected'; ?> value="2">PST</option>
								<option <?php if($tax_type_27==3) echo 'selected'; ?> value="3">GST</option>
						</select></td>
					</tr>
				<!-- 	<tr>
						<td class="tax-ortho-type">Base Shipping Rate</td>
						<td class="tax-ortho-code">Expense Item</td>
						<td class="tax-ortho-code"><input type="text" class="short_form_fields" name="short_code_28" value="<?php echo $short_code_28;?>" size="6"></td>
						<td class="ortho-tax-type">
						<select name="tax_type_28">
								<option <?php if($tax_type_28==4) echo 'selected'; ?> value="4">Z</option>
								<option <?php if($tax_type_28==1) echo 'selected'; ?> value="1">HST</option>
								<option <?php if($tax_type_28==2) echo 'selected'; ?> value="2">PST</option>
								<option <?php if($tax_type_28==3) echo 'selected'; ?> value="3">GST</option>
						</select></td>
					</tr>
				<tr>
						<td class="tax-ortho-type">Minimum Ship</td>
						<td class="tax-ortho-code">Expense Item</td>
						<td class="tax-ortho-code"><input type="text" class="short_form_fields" name="short_code_29" value="<?php echo $short_code_29;?>" size="6"></td>
						<td class="ortho-tax-type">
						<select name="tax_type_29">
								<option <?php if($tax_type_29==4) echo 'selected'; ?> value="4">Z</option>
								<option <?php if($tax_type_29==1) echo 'selected'; ?> value="1">HST</option>
								<option <?php if($tax_type_29==2) echo 'selected'; ?> value="2">PST</option>
								<option <?php if($tax_type_29==3) echo 'selected'; ?> value="3">GST</option>
						</select></td> 
					</tr> -->
				</table>
				<table style="margin-top: 6px;">
					<tr>
						<td><span
							style="float: left; color: #084598; font-size: 13px; font-weight: bold; width: 60px;">Legend:</span>Z
							means zero rated non-taxable item.</td>
					</tr>
				</table>
				<br>
			</div>
			<?php 
			//getting the defaults clinic specific values for the settings
			$defaults_query = "SELECT * FROM clinic_items_defaults";
			$clinic_defaults = $this->db->query($defaults_query);
			
			$base_shipping_charges = $clinic_defaults->row['clinic_default_item_value'];
			$minimum_shipping_pairs = $clinic_defaults->row['clinic_default_item_qty'];
			$default_clinic_terms = $clinic_defaults->row['clinic_default_terms'];
			
			?>
			
			<div id="tab_pricing" class="tabs">
				<h2 style="width:680">Enter pricing parameters</h2>
				<div style="float:right;width: 130px; margin-top: -30px;"><label style="color:#084598" id="response"></label></div>
				 <div class="header-fields-div" style="margin-top: 10px;">
	            	<label style="color: #00549f;float: left;width: 180px; line-height: 25px;"><strong>Base Shipping Charge :</strong></label>            	
	            	<input type="text" name="base_shipping_charge" id="base_shipping_charge" value="<?php echo $base_shipping_charges;?>" >
           		 </div>
           		 <div class="header-fields-div">
	            	<label style="color: #00549f;float: left;width: 180px; line-height: 25px;" ><strong>Minimum Shipping Pairs :</strong></label>            	
	            	<input type="text" name="min_shipping_pairs" id="min_shipping_pairs" value="<?php echo $minimum_shipping_pairs;?>" >
           		 </div>
           		 <div class="header-fields-div">
	            	<label style="color: #00549f;float: left;width: 180px; line-height: 25px;" ><strong>Terms :</strong></label>            	
	            	<select id = "default_terms" name="default_terms">
	            		<option value="1" <?php if ($default_clinic_terms == '1') echo 'selected'; ?>>End of month</option>
	            		<option value="2" <?php if ($default_clinic_terms == '2') echo 'selected'; ?>>Due on receipt</option>
	            		<option value="3" <?php if ($default_clinic_terms == '3') echo 'selected'; ?>>Net 30</option>
	            	</select>
           		 </div>
				
			<br />
					Specify the list of itemized rates in this screen for each orthotic type...<br />	<br />				
									
		<fieldset>
		<?php ?>
		<?php 
			if($this->request->isget('ortho_id')) {
				$ortho_id_url= $this->request->isget('ortho_id');	
			} ?>		
		<input type="hidden" value="" id="hidden_ortho" />
					<label style="float:left; color: #084598; font-size: 13px; font-weight: bold;">Select Ortho Type: </label> &nbsp;
					<select name="ortho_id" id="ortho_id" onchange="do_ortho_defaults()">								
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
									
									if(isset($ortho_id_url) &&  $ortho_id_url == $line_name["lookup_table_id"]){
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

		</div>
		<!-- Main-mid -->
		<div class="main-bot"></div>
	</div>
	<!-- main end -->

</form>

<script
	type="text/javascript" src="view/javascript/jquery/ui/ui.datepicker.js"></script>
<script
	type="text/javascript"
	src="view/javascript/hc_general.php?json={|prg_id|:|settings_form|}"></script>

<script
	type="text/javascript" src="view/javascript/ckeditor/ckeditor.js"></script>

<script type="text/javascript"><!--
<?php foreach ($languages as $language) { ?>
CKEDITOR.replace('description<?php echo $language['language_id']; ?>', {
	filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserImageUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserFlashUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>'
});
<?php } ?>	
//--></script>
<script
	type="text/javascript" src="view/javascript/jquery/ui/ui.draggable.js"></script>
<script
	type="text/javascript" src="view/javascript/jquery/ui/ui.resizable.js"></script>
<script
	type="text/javascript" src="view/javascript/jquery/ui/ui.dialog.js"></script>
<script
	type="text/javascript"
	src="view/javascript/jquery/ui/external/bgiframe/jquery.bgiframe.js"></script>
<script type="text/javascript"><!--
function image_upload(field, preview) {
	$('#dialog').remove();
	
	$('#content').prepend('<div id="dialog" style="padding: 3px 0px 0px 0px;"><iframe src="index.php?route=common/filemanager&token=<?php echo $token; ?>&field=' + encodeURIComponent(field) + '" style="padding:0; margin: 0; display: block; width: 100%; height: 100%;" frameborder="no" scrolling="auto"></iframe></div>');
	
	$('#dialog').dialog({
		title: '<?php echo $text_image_manager; ?>',
		close: function (event, ui) {
			if ($('#' + field).attr('value')) {
				$.ajax({
					url: 'index.php?route=common/filemanager/image&token=<?php echo $token; ?>',
					type: 'POST',
					data: 'image=' + encodeURIComponent($('#' + field).val()),
					dataType: 'text',
					success: function(data) {
						$('#' + preview).replaceWith('<img src="' + data + '" alt="" id="' + preview + '" class="image" onclick="image_upload(\'' + field + '\', \'' + preview + '\');" />');
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

function do_ortho_content(){
	vl = 'ot=' + $('#o_id').val() + '&p=setting';
	$.ajax({
		type: 'post',
		url: 'hc_scripts/functions.php',
		dataType: 'html',
		data: vl,
		success: function (html) {

			$('#ortho_content').html(html);
		},	
		complete: function () {
//			alert(pss);
		}
	});
}
function do_ortho_defaults(){

	auto_save_all();
	
	vl = 'ot=' + $('#ortho_id').val() + '&p=defaults';
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

function bring_select(row){
	valx = $('#lookup_table_value_' + row).val();
	oid = $('#o_id').val();
	vl = 'oid='+oid+'&lid='+valx + '&p=bring_select&r=' + row;
	$.ajax({
		type: 'post',
		url: 'hc_scripts/functions.php',
		dataType: 'html',
		data: vl,
		success: function (html) {
//alert(html);
			$('#ortho_val' + row).html(html);
		},	
		complete: function () {
//			alert(pss);
		}
	});
}

function do_setting_tabs(obj) {
	$('#where_am_i').val('tab_' + obj.id.substr(2));
}

//--></script>
<script type="text/javascript"><!--
$('#template').load('index.php?route=setting/setting/template&token=<?php echo $token; ?>&template=' + encodeURIComponent($('select[name=\'config_template\']').attr('value')));

$('#zone').load('index.php?route=setting/setting/zone&token=<?php echo $token; ?>&country_id=<?php echo $config_country_id; ?>&zone_id=<?php echo $config_zone_id; ?>');
//--></script>
<script type="text/javascript"><!--
$(document).ready(function() {

	do_ortho_defaults();

	$('#ortho_id').live('click', function(e) {
		  var orthotic_id = ($(this).val());
		  $("#hidden_ortho").val(orthotic_id);
		});	

	$.tabs('#tabs a');
	$.tabs('#languages a');
	<?php
	if($this->request->isget('w')) {
		echo "$.tabs('#tabs a','#".$this->request->isget('w')."');";	
	}
	?>
});
//--></script>
<?php echo $footer; ?>