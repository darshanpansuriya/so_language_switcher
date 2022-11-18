//<script>
function do_ortho(id, shell, material, thickness) {

	datastr = 'id=' + id + '&s=' + shell + '&m=' + material + '&t=' + thickness
			+ '&r=' + $('input[name=order_values_37]:checked').val();

	$.ajax({
		type : 'post',
		url : 'hc_scripts/orthodefaults.php',
		dataType : 'html',
		data : datastr,
		success : function(html) {
			// alert(html);
			$("#div_" + id).html(html);
		},
		complete : function() {

			// alert($('#order_values_7').val() + '-' +
			// $('#order_values_53').val()+'-' + $('#order_values_54').val());
			// alert("done...");
		}
	});
}


function do_div_fade() {
    $('#holdortho').fadeOut('fast', function() {
    		$('#loadingdefaults').show();
        setTimeout(function() { $('#holdortho').fadeIn('slow'); }, 2500);
        setTimeout(function() { $('#loadingdefaults').fadeOut('fast'); }, 2250);


    });
}

function do_ortho_cond(shell, material, thickness) {
	do_ortho(7, shell, material, thickness);
	do_ortho(53, shell, material, thickness);
	do_ortho(54, shell, material, thickness);
	if ($('#order_values_7').val() == '42') {
		$('.div_22_112').hide();
	} else {
		$('.div_22_112').show();
	}
}

function do_mods(id, grind, material, thickness, flange, hl, hr) {

	ortho = $('input[name=order_values_37]:checked').val();

	datastr = 'id=' + id + '&g=' + grind + '&m=' + material + '&t=' + thickness
			+ '&f=' + flange + '&hl=' + hl + '&hr=' + hr + '&r=' + ortho;

	$.ajax({
		type : 'post',
		url : 'hc_scripts/modsdefaults.php',
		data : datastr,
		dataType : 'html',
		success : function(html) {
			// alert(html);
			$("#div_" + id).html(html);
			if (!ortho == '185'
					&& (!$('#order_values_12').val() || $('#order_values_12')
							.val() == 61))
				$("#div_error_12").html('Warning: this is 0');
			if (ortho == '183' || ortho == '186') {
				// $('input[name=order_values_left_61]').attr('checked',true);
				// $('input[name=order_values_right_61]').attr('checked',true);
				// $('#order_values_11').val('55');
			}
			if (ortho == '181' || ortho == '219') {
				$('input[name=order_values_left_61]').attr('readonly',
						'readonly');
				$('input[name=order_values_left_61]').attr('readonly',
						'readonly');
			}
			if (ortho == '181' && $('#order_values_11').val() == 55) {
				$('#order_values_left_61').attr('checked', 'checked');
				$('#order_values_right_61').attr('checked', 'checked');
			}

			if (ortho == '316' || ortho == '317') {
				// $('#order_values_24').val('118').trigger('change');
				// $('#order_values_26').val('129').trigger('change');
				// $('#order_values_25').val('126').trigger('change');
				// $('#order_values_27').val('143').trigger('change');
			}

		},
		complete : function() {
			// alert($('#order_values_7').val() + '-' +
			// $('#order_values_53').val()+'-' + $('#order_values_54').val());
			// alert("done...");
		}
	});
}

function do_mods_cond(grind, material, thickness, flange, heelleft, heelright) {
	idsx = [ 16, 11, 14, 13, 12, 68 ];
	for (i in idsx) {
		id = idsx[i];
		do_mods(id, grind, material, thickness, flange, heelleft, heelright);
	}

	check_select_radio(13, 70, 64, 1);
	check_select_radio(11, 60, 61, 1);

}

function in_array(needle, haystack) {
	for ( var i in haystack) {
		if (haystack[i] == needle)
			return true;
	}
	return false;
}

function do_met_pad() {
	if ($("#order_values_18").val() == '214') {
		$("#order_values_met_pad_material_88").removeAttr('checked').trigger('change');
		$("#div_met_pad").hide(300);
		$("#div_met_pad_material").hide(300);
		$("#order_values_left_19").val('264');
		$("#order_values_right_19").val('264');
	} else {
		$("#div_met_pad").show(300);
		$("#div_met_pad_material").show(300);
	}
}

/**
 * this block is written to control the wedge fields in posting tab
 */
function do_wedge() {
	if ($("#order_values_89").val() == '412') {
		$("#wedge_values").hide(300);
		$("#order_values_left_90").val('415');
		$("#order_values_right_90").val('415');
	} else {
		$("#wedge_values").show(300);
	}

}

function show_hide_file_frame() {
	$("#file_warn").html('');
	if ($("input[name=order_values_60]:checked").val() == '254') {
		$("#file_frame").show();
	} else {
		$("#file_frame").hide();
		if ($("input[name=order_values_60]:checked").val() == '253')
			$("#file_warn").html(
					"Please reference the Scan File # in the notes below");
	}
	// alert($("#order_values_60:checked").val());
}

function do_my_tabs(obj) {
	$('#button_back').show();
	$('#button_next').show();
	// $('#filebuttons').hide();
	$('#where_am_i').val('tab_' + obj.id.substr(2));

	if (obj.id == 'a_patient_info') {
		$('#button_back').hide();
		$('#button_next').attr('title', '#tab_ortho_type');
	} else if (obj.id == 'a_ortho_type') {
		$('#button_back').attr('title', '#tab_patient_info');
		$('#button_next').attr('title', '#tab_mods');
	} else if (obj.id == 'a_mods') {
		$('#button_back').attr('title', '#tab_ortho_type');
		$('#button_next').attr('title', '#tab_additions');
	} else if (obj.id == 'a_additions') {
		$('#button_back').attr('title', '#tab_mods');
		$('#button_next').attr('title', '#tab_posting');
	} else if (obj.id == 'a_posting') {
		$('#button_back').attr('title', '#tab_additions');
		$('#button_next').attr('title', '#tab_covers');
    } else if (obj.id == 'a_covers') {
		$('#button_back').attr('title', '#tab_posting');
		$('#button_next').attr('title', '#tab_delivery');
	} else if (obj.id == 'a_delivery') {
		$('#button_back').attr('title', '#tab_covers');
		$('#button_next').attr('title', '#tab_general');
	} else if (obj.id == 'a_general') {
		$('#button_back').attr('title', '#tab_delivery');
		$('#button_next').attr('title', '#tab_files');
	} else if (obj.id == 'a_files') {
		$('#button_back').attr('title', '#tab_general');
		$('#button_next').attr('title', '#tab_status');
	} else if (obj.id == 'a_status') {
		// $('#filebuttons').show();
		$('#button_next').hide();
		$('#button_back').attr('title', '#tab_files');
	}
}

function do_equal_sides(elx, vid, vzero) {
	if (elx == 'order_values_left_' + vid
			&& $('#order_values_right_' + vid).val() == vzero) {
		$('#order_values_right_' + vid).val($('#' + elx).val())
	} else if (elx == 'order_values_right_' + vid
			&& $('#order_values_left_' + vid).val() == vzero) {
		$('#order_values_left_' + vid).val($('#' + elx).val())
	}
}

function do_check_both(elx, vid) {

	if (elx == 'order_values_left_' + vid) {
		$('#order_values_right_' + vid).attr('checked',
				$('#order_values_left_' + vid).attr('checked'));
	}
}

function do_ortho_shell_type(obj) {

	if ($(obj).attr('checked')) {
		if (obj.value == '316')
			$('#order_values_7').val('41').trigger('change');
		else if (obj.value == '317')
			$('#order_values_7').val('42').trigger('change');
	}
}

function do_ortho_thickness() {

	$('#order_values_54').val('235').trigger('change');

}
function do_custom_check() {

	/*
	 * if ($("#order_values_7").val() == '41') // make chechbox xhecked; else if
	 * ($("#order_values_7").val() == '42') // make chechbox xhecked; alert(2);
	 */
}

function put_address() {

	add = $('#clinic_addresses').val().replace(/\,\ \ /g, '\n').replace('&quot;','"');
	decoded = add.replace(/&amp;/g,'&');
	var decoded = decoded.split(',');
	console.log(decoded);
	var format_address = decoded[0]!==undefined ? decoded[0] :'';
	var format_street =  decoded[1]!==undefined ? decoded[1] :'';
	var format_city =  decoded[2]!==undefined ? decoded[2] :'';
	var format_province =  decoded[3]!==undefined ? decoded[3] :'';
	var format_postal_code =  decoded[4]!==undefined ? decoded[4] :'';

	 $('#format_address').val(format_address.trim());
	 $('#format_street').val(format_street.trim());
	 $('#format_city').val(format_city.trim());
	 $('#format_province').val(format_province.trim());
	 $('#format_postal_code').val(format_postal_code.trim());
	//$('#order_shipping_address').val(decoded);

}
/**
 * this function is written to disable the hallux field if the ortho type is
 * custom direct milled
 */

function do_hallux() {

	if ($('input[name=order_values_37]:checked').val() == '316') {

		$('input[name=order_values_left_49]').attr('disabled', 'disabled');

		$('input[name=order_values_right_49]').attr('disabled', 'disabled');

		$(
				'input[name=order_values_hallux_rigidus_splint_with_reinforcement_67]')
				.attr('disabled', 'disabled');

	} else {

		$('input[name=order_values_left_49]').removeAttr('disabled');

		$('input[name=order_values_right_49]').removeAttr('disabled');

		$(
				'input[name=order_values_hallux_rigidus_splint_with_reinforcement_67]')
				.removeAttr('disabled');

	}

}
/*
 * this function is written to display the information on the additions tab on
 * orders page
 */
function descriptionOver() {
	document.getElementById('description').style.display = "";
	return false;
}
function descriptionOut() {
	document.getElementById('description').style.display = "none";
	return false;
}

/**
 * this function is written to control the options available under direct milled
 */

function do_direct_milled() {

	if ($('input[name=orthotic_type]').val() == '41'
			&& $('input[name=order_values_69]:checked').val() == '325') {

		$('.div_70_327').hide(300);

	}
	if ($('input[name=orthotic_type]').val() == '41'
		&& $('input[name=order_values_21]:checked').val() == '110') {

		$('.div_22').show(300);
		$('#order_values_22_112').attr('checked', 'checked');
		$('.div_22_112').show(300);
		$('.div_22_113').hide(300);

}

}

function do_vacuum_pressed() {

	if ($('input[name=orthotic_type]').val() == '42') {

		if ($('input[name=order_values_21]:checked').val() == '109') {

			$('.div_22_112').hide(300);
		}
		if ($('input[name=order_values_69]:checked').val() == '325') {

			$('.div_70_327').hide(300);
		}
	}
}

function disable_all() {

	if ($('input[name=order_values_55]:checked').val() == '238') {
		$('.disabled').find('input, textarea, button, select').attr('disabled',
			'disabled');
		$('#order_values_89').attr('disabled', 'disabled');
		$('#order_values_right_90').attr('disabled', 'disabled');
		$('#order_values_left_90').attr('disabled', 'disabled');
		$('#order_values_21_108').attr('checked', 'checked').trigger('change');
		$('#order_values_69_324').attr('checked', 'checked').trigger('change');
	} else {
		$('.disabled').find('input, textarea, button, select').removeAttr(
				'disabled');
		$('#order_values_89').removeAttr('disabled');
		$('#order_values_right_90').removeAttr('disabled');
		$('#order_values_left_90').removeAttr('disabled');
	}

	$('#order_values_left_50_0').prop("checked", false);
	$('#order_values_right_50_0').prop("checked", false);

	$('#order_values_left_50_1').prop("checked", false);
	$('#order_values_right_50_1').prop("checked", false);

	$('#order_values_left_50_2').prop("checked", false);
	$('#order_values_right_50_2').prop("checked", false);

	$('#order_values_left_50_3').prop("checked", false);
	$('#order_values_right_50_3').prop("checked", false);

	$('#order_values_left_50_4').prop("checked", false);
	$('#order_values_right_50_4').prop("checked", false);

}

function postings_disable() {
	if ($('input[name=order_values_55]:checked').val() == '238') {
		$('.disabled').find('input, textarea, button, select').attr('disabled',
				'disabled');
		$('#order_values_89').attr('disabled', 'disabled');
		$('#order_values_right_90').attr('disabled', 'disabled');
		$('#order_values_left_90').attr('disabled', 'disabled');
		$('#order_values_21_108').attr('checked', 'checked').trigger('change');
		$('#order_values_69_324').attr('checked', 'checked').trigger('change');

	} else {
		$('.disabled').find('input, textarea, button, select').removeAttr(
				'disabled');
		$('#order_values_89').removeAttr('disabled');
		$('#order_values_right_90').removeAttr('disabled');
		$('#order_values_left_90').removeAttr('disabled');
		if ($('input[name=order_values_55]:checked').val() == '239') {
			$('#order_values_22_113').attr('checked', 'checked').trigger(
					'change');

			// $('#order_values_69_326').attr('checked','checked').trigger('change');

			if ($('input[name=order_values_21]:checked').val() == '108') {
				$('#order_values_21_108').attr('checked', 'checked').trigger(
						'change');
			}
			if ($('input[name=order_values_21]:checked').val() == '109') {
				$('#order_values_21_109').attr('checked', 'checked').trigger(
						'change');
			}
			if ($('input[name=order_values_21]:checked').val() == '110') {
				$('#order_values_21_110').attr('checked', 'checked').trigger(
						'change');
			}
			if ($('input[name=order_values_69]:checked').val() == '326') {
				$('#order_values_69_326').attr('checked', 'checked').trigger(
						'change');
			}

			if ($('input[name=order_values_21]:checked').val() == undefined) {
				$('#order_values_21_108').attr('checked', 'checked').trigger(
						'change');
			}
			if ($('input[name=order_values_69]:checked').val() == undefined) {
				$('#order_values_69_324').attr('checked', 'checked').trigger(
						'change');
			}

		}
	}

	if ($('input[name=order_values_21]:checked').val() == '108') {
		$('#order_values_22_112').removeAttr('checked').trigger('change');
		$('#order_values_22_113').removeAttr('checked').trigger('change');
		$('.div_22').hide(300);
	}

	if ($('input[name=order_values_69]:checked').val() == '324') {
		$('#order_values_70_327').removeAttr('checked').trigger('change');
		$('#order_values_70_328').removeAttr('checked').trigger('change');
		$('#order_values_71_329').removeAttr('checked').trigger('change');
		$('#order_values_71_330').removeAttr('checked').trigger('change');
		$('.div_70_71').hide(300);
	}

}

function rf_none() {

	$('input[name=order_values_22]').removeAttr('checked').trigger('change');

	$('#order_values_left_56').val(322).trigger('change');
	$('#order_values_right_56').val(322).trigger('change');

	$('#order_values_left_23').val(114).trigger('change');
	$('#order_values_right_23').val(114).trigger('change');

	$('#order_values_22_112').removeAttr('checked').trigger('change');
	$('#order_values_22_113').removeAttr('checked').trigger('change');
	$('.div_22').hide(300);
	$('#rf_deg_dir').hide(300);
}

function rf_extrinsic() {
	$('#rf_deg_dir').show(300);
	$('.div_22').show(300);
	$('.div_22_112').show(300);
	$('.div_22_113').show(300);
	if ($('#order_values_7').val() == '41'
			&& $('input[name=order_values_21]:checked').val() == '109') {

		$('#order_values_22_112').attr('checked', 'checked').trigger('change');
	} else if ($('#order_values_7').val() == '42'
			&& $('input[name=order_values_21]:checked').val() == '109') {

		$('#order_values_22_112').removeAttr('checked').trigger('change');
		$('#order_values_22_113').attr('checked', 'checked').trigger('change');
		$('.div_22_112').hide(300);
	}
}

function rf_intrinsic() {

	$('#rf_deg_dir').show(300);
	$('#order_values_22_112').removeAttr('checked').trigger('change');
	$('#order_values_22_113').removeAttr('checked').trigger('change');
	$('.div_22').hide(300);

//	$('.div_22_112').show(300);
//	$('.div_22_113').show(300);
	if ($('#order_values_7').val() == '41'
			&& $('input[name=order_values_21]:checked').val() == '110') {

		$('#order_values_22_112').attr('checked', 'checked').trigger('change');
		$('.div_22').show(300);
		$('.div_22_113').hide(300);
	} else if ($('#order_values_7').val() == '42'
			&& $('input[name=order_values_21]:checked').val() == '110') {
		$('#order_values_22_112').removeAttr('checked').trigger('change');
		$('#order_values_22_113').removeAttr('checked').trigger('change');
		$('.div_22').hide(300);
	}

	return;
}

function rf_degrees() {
	if ($('#order_values_7').val() == '42'
			&& $('input[name=order_values_21]:checked').val() == '110') {
		// no p
		// } else if($('input[name=order_values_21]:checked').val()==undefined
		// || $('input[name=order_values_22]:checked').val()==undefined) {
	} else if ($('input[name=order_values_21]:checked').val() == undefined
			|| $('input[name=order_values_21]:checked').val() == 108) {
		// alert('Select Post Type and Post material'); return false;
		alert('Select Post Type');
		return false;
	}
}

function ff_none() {
	$('input[name=order_values_70]').removeAttr('checked').trigger('change');
	$('input[name=order_values_71]').removeAttr('checked').trigger('change');
	$('#order_values_left_72').val(331).trigger('change');
	$('#order_values_right_72').val(331).trigger('change');

	$('#order_values_left_73').val(338).trigger('change');
	$('#order_values_right_73').val(338).trigger('change');

	$('.div_70_71').hide(300);
	$('#ff_deg_dir').hide(300);

}

function ff_extrinsic() {
	$('#ff_deg_dir').show(300);
	$('.div_70_71').show(300);
	$('.div_70').show(300);
	$('.div_70_327').show(300);
	$('.div_70_328').show(300);
	$('.div_71').show(300);
	if ($('#order_values_7').val() == '41'
			&& $('input[name=order_values_69]:checked').val() == '325') {
		$('#order_values_71_330').attr('checked', 'checked').trigger('change');
		$('#order_values_70_328').attr('checked', 'checked').trigger('change');
		$('.div_70_327').hide(300);
	} else if ($('#order_values_7').val() == '42'
			&& $('input[name=order_values_69]:checked').val() == '325') {
		$('#order_values_70_328').attr('checked', 'checked').trigger('change');
		$('.div_70_327').hide(300);
	}

}

function ff_intrinsic() {
	$('#ff_deg_dir').show(300);
	$('input[name=order_values_70]').removeAttr('checked').trigger('change');
	$('input[name=order_values_71]').removeAttr('checked').trigger('change');
	$('.div_70_71').hide(300);

	return;

	$('.div_70_71').show(300);
	$('.div_70_327').show();
	$('.div_70_328').show();
	$('.div_71').show(300);
	$('#order_values_71_329').attr('checked', false).trigger('change');
	$('#order_values_71_330').attr('checked', false).trigger('change');
	if ($('#order_values_7').val() == '41'
			&& $('input[name=order_values_69]:checked').val() == '326') {
		$('#order_values_70_327').attr('checked', 'checked').trigger('change');
		$('.div_70_328').hide(300);
	} else if ($('#order_values_7').val() == '42'
			&& $('input[name=order_values_69]:checked').val() == '326') {
		$('#order_values_70_327').attr('checked', false).trigger('change');
		$('#order_values_70_328').attr('checked', false).trigger('change');
		$('.div_70_71').hide(300);
	}
}

function ff_degrees() {
	if ($('#order_values_7').val() == '42'
			&& $('input[name=order_values_69]:checked').val() == '326') {
		// no p
		// } else if($('input[name=order_values_69]:checked').val()==undefined
		// || $('input[name=order_values_70]:checked').val()==undefined) {
	} else if ($('input[name=order_values_69]:checked').val() == undefined
			|| $('input[name=order_values_69]:checked').val() == 324) {
		// alert('Select Post Type and Post material'); return false;
		alert('Select Post Type');
		return false;
	}
}

function do_casting_method() {
	vl = 'id=' + $('#patient_clinic_id').val() + '&p=cast';

	$.ajax({
		type : 'post',
		url : 'hc_scripts/functions.php',
		dataType : 'html',
		data : vl,
		success : function(html) {
			$('#order_values_8').val(html);
		},
		complete : function() {
			// $('#order_values_8').val(aaa);
		}
	});
}

function do_midlayer(id) {
	if ($('#order_values_30').val() == '162'
			|| $('#order_values_35').val() == undefined) {
		if (id == 141)
			$('#order_values_30').val(164).trigger('change');
		else if (id == 142)
			$('#order_values_30').val(166).trigger('change');
		else if (id == 143)
			$('#order_values_30').val(168).trigger('change');
	}
}

function cover_ajax(id, vals, def) {
	$('#cover_wait').show();
	datastr = 'id=' + id + '&vals=' + vals + '&def=' + def;
	$.ajax({
		type : 'post',
		url : 'hc_scripts/coverdefaults.php',
		dataType : 'html',
		data : datastr,
		success : function(html) {

			$('#order_values_' + id).html(html);
			$('#cover_wait').hide();

		}
	});
}

function do_covers(idx, valx, mid) {

	var def = new Array();
	var vals = new Array();
	// alert(idx);
	def[idx] = valx;
	/*
	 * idsx = [24,26,25,27,28,29,30,31,32,33,34,63,62,35]; for (i in idsx) { id =
	 * idsx[i]; def[id] = $('#order_values_'+id).val(); if (idx == id) def[id] =
	 * valx; vals[id] = ''; // all is available }
	 */
	idsx = [];
	if (idx == '316' || idx == '317') {
		$('#order_values_24').val().trigger('change');
		$('#order_values_24').val().attr('selected', true);
		$('#order_values_26').val(129).trigger('change');
		$('#order_values_26').val().attr('selected', true);
		$('#order_values_25').val(126).trigger('change');
		$('#order_values_25').val().attr('selected', true);
		$('#order_values_27').val(143).trigger('change');
	}

	if (mid == 24) {

		idsx = [ 26, 25, 27 ];
		if ($('#order_values_24').val() == 117) {
			def[26] = 133;
			vals[26] = 133;
			def[25] = 134;
			vals[25] = 134;
			def[27] = 140;
			vals[27] = 140;

		} else {
			def[27] = $('#order_values_27').val();
			vals[27] = '';
		}

		if ($('#order_values_24').val() == 118) {
			id = 26;
			vals[id] = '129,130,135,131,132,136,444';
			def[id] = ($('#order_values_' + id).val() == 133 ? 129 : $(
					'#order_values_' + id).val());
			$('#order_values_' + id).val(def[id]);

			if ($('#order_values_26').val() == 129) {
				id = 25;
				vals[id] = '125,126,127';
				def[id] = $('#order_values_' + id).val() == 134 ? 126 : $(
						'#order_values_' + id).val();
				$('#order_values_' + id).val(def[id]);
			} else if ($('#order_values_26').val() == 136) {
				id = 25;
				vals[id] = '126';
				def[id] = 126;
				$('#order_values_' + id).val(def[id]);
			} else {
				id = 25;
				vals[id] = '125,126,127';
				def[id] = $('#order_values_' + id).val() == 125 ? 125 : 126;
				$('#order_values_' + id).val(def[id]);
			}
		} else if ($('#order_values_24').val() == 120
				|| $('#order_values_24').val() == 354) {
			id = 26;
			vals[id] = '129';
			def[id] = 129;
			id = 25;
			vals[id] = '128';
			def[id] = 128;
		} else if ($('#order_values_24').val() == 124) {
			id = 26;
			vals[id] = '129,139';
			def[id] = $('#order_values_' + id).val() == 139 ? 139 : 129;
			$('#order_values_' + id).val(def[id]);
			id = 25;
			vals[id] = '128';
			def[id] = 128;
			$('#order_values_' + id).val(def[id]);
		} else if ($('#order_values_24').val() == 123) {
			id = 26;
			vals[id] = '129,137,138';
			if ($('#order_values_' + id).val() != 137
					&& $('#order_values_' + id).val() != 138)
				def[id] = 129;
			id = 25;
			vals[id] = '128';
			def[id] = 128;
			$('#order_values_' + id).val(def[id]);
		} else if ($('#order_values_24').val() == 121) {
			id = 26;
			vals[id] = '129';
			def[id] = 129;
			$('#order_values_' + id).val(def[id]);
			id = 25;
			vals[id] = '128,126';
			if ($('#order_values_' + id).val() != 128)
				def[id] = 126;
		} else if ($('#order_values_24').val() == 122) {
			id = 26;
			vals[id] = '129';
			def[id] = 129;
			$('#order_values_' + id).val(def[id]);
			id = 25;
			vals[id] = '125,126';
			// def[id] = $('#order_values_' + id).val()== 125 ? 125 :128;
			// $('#order_values_' + id).val(def[id]);
		} else if ($('#order_values_24').val() == 119) {
			id = 26;
			vals[id] = '129,135,130';
			if ($('#order_values_' + id).val() == 135) {
				def[id] = 135;
				$('#order_values_25').val(def[126]);
				vals[25] = '126';
			} else if ($('#order_values_' + id).val() == 130) {
				def[id] = 130;
				$('#order_values_25').val(def[126]);
				vals[25] = '126';
			} else {
				def[id] = 129;
				vals[id] = '129,135,130';
				$('#order_values_25').val(def[126]);
				vals[25] = '126';
			}
			// def[id] = ($('#order_values_' + id).val()==135 ? 135 : 129);

			cover_ajax(id, vals[id], def[id]);

			$('#order_values_' + id).val(def[id]);

			if ($('#order_values_26').val() == 129) {
				id = 25;
				vals[id] = '126';
				def[id] = 126;
				$('#order_values_' + id).val(def[id]);
			} else if ($('#order_values_26').val() == 130) {
				id = 25;
				vals[id] = '126';
				def[id] = 126;
				$('#order_values_' + id).val(def[id]);
			} else if ($('#order_values_26').val() == 135) {
				id = 25;
				vals[id] = '126';
				if ($('#order_values_' + id).val() != 125
						&& $('#order_values_' + id).val() != 358)
					def[id] = 126;
				$('#order_values_' + id).val(def[id]);
			}
		}
	} else if (mid == 28) {
		idsx = [ 29, 30 ];
		if ($('#order_values_28').val() != 144) {
			/*if ($('#order_values_27').val() == 140) {
				id = 30;
				def[id] = 163;
				vals[id] = 163;
			} else if ($('#order_values_27').val() == 141) {
				id = 30;
				def[id] = 164;
				vals[id] = 164;
			} else if ($('#order_values_27').val() == 142) {
				id = 30;
				def[id] = 164;
				vals[id] = 164;
			} else if ($('#order_values_27').val() == 143) {
				id = 30;
				def[id] = 168;
				vals[id] = 168;
			}*/
		}

		if ($('#order_values_28').val() == 144) {
			def[29] = 148;
			def[30] = 162;
			vals[29] = 148;
			vals[30] = 162;
		} else if ($('#order_values_28').val() == 146) {
			id = 29;
			vals[id] = '149,150';
			def[id] = $('#order_values_' + id).val() == 150 ? 150 : 149;
			$('#order_values_' + id).val(def[id]);
		} else {
			id = 29;
			vals[id] = '149,150';
			def[id] = $('#order_values_' + id).val() == 150 ? 150 : 149;
			$('#order_values_' + id).val(def[id]);
		}
	} else if (mid == 31) {
		idsx = [ 32, 33 ];

		if ($('#order_values_31').val() != 170) {
			/*if ($('#order_values_27').val() == 140) {
				id = 33;
				def[id] = 163;
				vals[id] = 163;
			} else if ($('#order_values_27').val() == 141) {
				id = 33;
				def[id] = 165;
				vals[id] = 165;
			} else if ($('#order_values_27').val() == 142) {
				id = 33;
				def[id] = 167;
				vals[id] = '165,167';
			} else if ($('#order_values_27').val() == 143) {
				id = 33;
				def[id] = 169;
				vals[id] = '165,167,169';
			}*/
		}

		if ($('#order_values_31').val() == 170) {
			def[32] = 174;
			def[33] = 163;
			vals[32] = 174;
			vals[33] = 163;
		} else if ($('#order_values_31').val() == 172) {
			id = 32;
			vals[id] = '175,176';
			def[id] = $('#order_values_' + id).val() == 176 ? 176 : 360;
			$('#order_values_' + id).val(def[id]);
		} else if ($('#order_values_31').val() == 151) {
			id = 32;
			vals[id] = '175,176';
			def[id] = $('#order_values_' + id).val() == 175 ? 175 : 176;
			$('#order_values_' + id).val(def[id]);
		} else {
			id = 32;
			vals[id] = '175,176';
			def[id] = $('#order_values_' + id).val() == 176 ? 176 : 175;
			$('#order_values_' + id).val(def[id]);
		}
	} else if (mid == 34) {
		idsx = [ 63, 62, 35 ];

		if ($('#order_values_34').val() != 152) {
			/*if ($('#order_values_27').val() == 140) {
				id = 35;
				def[id] = 155;
				vals[id] = '155';
			} else if ($('#order_values_27').val() == 141) {
				id = 35;
				//def[id] = 156;
				vals[id] = '156,159';
			} else if ($('#order_values_27').val() == 142) {
				// alert('589 hc_order_form.js');
				id = 35;
				//def[id] = 157;
				vals[id] = '157,160';
			} else if ($('#order_values_27').val() == 143) {
				id = 35;
				//def[id] = 158;
				vals[id] = '158,161';
			}*/
		}


		if ($('#order_values_34').val() == 152) {
			def[63] = 353;
			def[62] = 257;
			def[35] = 155;
			vals[63] = 353;
			vals[62] = 257;
			vals[35] = 155;
		} else if ($('#order_values_34').val() == 153) {
			id = 63;
			vals[id] = '255,258';
			def[id] = $('#order_values_' + id).val() == 258 ? 258 : 255;
			// def[id] = 255;
			$('#order_values_' + id).val(def[id]);

			id = 62;
			vals[id] = '256';
			def[id] = 256;
			$('#order_values_' + id).val(def[id]);
		} else if ($('#order_values_34').val() == 154) {
			id = 63;
			vals[id] = '255';
			def[id] = 255;
			$('#order_values_' + id).val(def[id]);

			id = 62;
			vals[id] = '256';
			def[id] = 256;
			$('#order_values_' + id).val(def[id]);
		} else if ($('#order_values_34').val() == 424) {
			id = 63;
			vals[id] = '255,427';
			def[id] = $('#order_values_' + id).val() == 427 ? 427 : 255;
			// def[id] = 255;
			$('#order_values_' + id).val(def[id]);

			console.log("424");
			id = 62;
			vals[id] = '256';
			def[id] = 256;
			$('#order_values_' + id).val(def[id]);
		}

	} else if (mid == 27) {

		idsx = [ 30, 33, 35 ];
		if ($('#order_values_27').val() == 140) {
			id = 30;
			def[id] = 162;
			vals[id] = 162;
			id = 33;
			def[id] = 163;
			vals[id] = 163;
			id = 35;
			def[id] = 155;
			vals[id] = 155;
		} else if ($('#order_values_27').val() == 141) {
			if ($('#order_values_28').val() == 144) {
				id = 30;
				def[id] = 162;
				vals[id] = 162;
			} else {
				id = 30;
				def[id] = 164;
				vals[id] = 164;
			}
			if ($('#order_values_31').val() == 170) {
				id = 33;
				def[id] = 163;
				vals[id] = 163;
			} else {
				id = 33;
				def[id] = 165;
				vals[id] = 165;
			}
			if ($('#order_values_34').val() == 152) {
				id = 35;
				def[id] = 155;
				vals[id] = 155;
			} else {
				id = 35;
				def[id] = 156;
				vals[id] = '156,159';
			}
		} else if ($('#order_values_27').val() == 142) {
			if ($('#order_values_28').val() == 144) {
				id = 30;
				def[id] = 162;
				vals[id] = 162;
			} else {
				id = 30;
				def[id] = 166;
				vals[id] = 166;
			}
			if ($('#order_values_31').val() == 170) {
				id = 33;
				def[id] = 163;
				vals[id] = 163;
			} else {
				id = 33;
				def[id] = 167;
				vals[id] = '165,167';
			}
			if ($('#order_values_34').val() == 152) {
				id = 35;
				def[id] = 155;
				vals[id] = 155;
			} else {
				// alert('646 hc_order_form.js');
				id = 35;
				def[id] = 157;
				vals[id] = '157,160';
			}

		} else if ($('#order_values_27').val() == 143) {

			if ($('#order_values_28').val() == 144) {
				id = 30;
				def[id] = 162;
				vals[id] = 162;
			} else {
				id = 30;
				def[id] = 168;
				vals[id] = 168;
			}
			if ($('#order_values_31').val() == 170) {
				id = 33;
				def[id] = 163;
				vals[id] = 163;
			} else {
				id = 33;
				def[id] = 169;
				vals[id] = '165,167,169';
			}
			if ($('#order_values_34').val() == 152) {
				id = 35;
				def[id] = 155;
				vals[id] = 155;
			} else {
				id = 35;
				def[id] = 158;
				vals[id] = '158,161';
			}
		}


		if ($('#order_values_28').val() == 145) {

			/*if ($('#order_values_27').val() == 141) {
				id = 30;
				def[id] = 164;
				vals[id] = 164;
			} else if ($('#order_values_27').val() == 142) {
				id = 30;
				def[id] = 166;
				vals[id] = 166;
			} else if ($('#order_values_27').val() == 143) {
				id = 30;
				def[id] = 168;
				vals[id] = 168;
			}*/

		} else if ($('#order_values_28').val() == 146) {
			/*if ($('#order_values_27').val() == 141) {
				id = 30;
				def[id] = 164;
				vals[id] = 164;
			} else if ($('#order_values_27').val() == 142) {
				id = 30;
				def[id] = 166;
				vals[id] = 166;
			} else if ($('#order_values_27').val() == 143) {
				id = 30;
				def[id] = 168;
				vals[id] = 168;
			}*/
		} else if ($('#order_values_28').val() == 147) {
			/*if ($('#order_values_27').val() == 141) {
				id = 30;
				def[id] = 164;
				vals[id] = 164;
			} else if ($('#order_values_27').val() == 142) {
				id = 30;
				def[id] = 166;
				vals[id] = 166;
			} else if ($('#order_values_27').val() == 143) {
				id = 30;
				def[id] = 168;
				vals[id] = 168;
			}*/
		}


		if (idx == 99) {
			def[30] = $('#order_values_30').val();
			def[33] = $('#order_values_33').val();
			def[35] = $('#order_values_35').val();
		}
	} else if (mid != 30 && mid != 33 && mid != 35) { // below is the same as
		// idx == 27; 27 is also
		// changed with ortho
		// type page
		// alert('675 hc_orde_form.js');
		do_covers(99, $('#order_values_27').val(), 27);
	}

	for (i in idsx) {
		id = idsx[i];
		if (vals[id] === undefined) {
			vals[id] = '';
		} // '' means all available values

		cover_ajax(id, vals[id], def[id]);
	}

	// alert($('#order_values_35').val());

}

function get_addresses() {
	vl = 'id=' + $('#patient_clinic_id').val() + '&p=address';

	$.ajax({
		type : 'post',
		url : 'hc_scripts/functions.php',
		dataType : 'html',
		data : vl,
		success : function(html) {
			$('#clinic_addresses').html(html);
		},
		complete : function() {
			put_address();
		}
	});
}

function get_shoesizes(pss) { // patient shoe size
	vl = 'sid=' + pss + '&gid=' + $('#patient_gender_id').val() + '&p=shoe';
	$.ajax({
		type : 'post',
		url : 'hc_scripts/functions.php',
		dataType : 'html',
		data : vl,
		success : function(html) {
			// alert(html);
			$('#order_values_3').html(html);
		},
		complete : function() {
			// alert(pss);
		}
	});
}

function degrees_directions(leftright, deg, val, dir, dval) {
	other = leftright == 'left' ? 'right' : 'left';
	do_equal_sides('order_values_' + leftright + '_' + deg, deg, val);

	if ($('#order_values_' + leftright + '_' + deg).val() == val) {
		// alert(1);
		$('#order_values_' + leftright + '_' + dir).append(
				new Option("Neutral", dval));
		$('#order_values_' + other + '_' + dir).append(
				new Option("Neutral", dval));
		$('#order_values_' + leftright + '_' + dir).val(dval);
		$('#order_values_' + leftright + '_' + dir)
				.attr('disabled', 'disabled');
	} else {
		$('#order_values_' + leftright + '_' + dir).removeAttr('disabled');
		$('#order_values_' + other + '_' + dir).removeAttr('disabled');
		$(
				'#order_values_' + leftright + '_' + dir + " option[value='"
						+ dval + "']").remove();
		$(
				'#order_values_' + other + '_' + dir + " option[value='" + dval
						+ "']").remove();

	}

}

function print_1page(v) {

	$.ajax({
		type : 'POST',
		url : 'hc_scripts/functions.php',
		dataType : 'html',
		data : 'p=print_1page',
		complete : function(data) {
			// alert(data);
		},
		success : function(data) {
			if (v)
			{
				submit_orderform();
			}
			else {

				location = location;
			}
		}
	});
}

function print_1page_fr(v) {

	$.ajax({
		type : 'POST',
		url : 'hc_scripts/functions.php',
		dataType : 'html',
		data : 'p=print_1page_fr',
		complete : function(data) {
			// alert(data);
		},
		success : function(data) {
			if (v)
			{
				submit_orderform();
			}
			else {

				location = location;
			}
		}
	});
}

function print_workorder(v) {

	$.ajax({
		type : 'POST',
		url : 'hc_scripts/functions.php',
		dataType : 'html',
		data : 'p=print_workorder',
		complete : function(data) {
			// alert(data);
		},
		success : function(data) {
			if (v)
			{
				submit_orderform();
			}
			else {

				location = location;
			}
		}
	});
}

function check_select_radio(vwhat, vval, vcheck, vrecurs) {
	// this checks if the select is none, then resets the checkboxes accordingly
	// vrecurs == 0 means add functions to checkboxes, except onready function
	// use 1.

	if ($('#order_values_' + vwhat).val() == vval) {
		$('#order_values_left_' + vcheck).attr('checked', false).attr(
				'disabled', 'disabled');
		$('#order_values_right_' + vcheck).attr('checked', false).attr(
				'disabled', 'disabled');
	} else {
		$('#order_values_left_' + vcheck).attr('disabled', false);
		$('#order_values_right_' + vcheck).attr('disabled', false);
	}
	if (vrecurs)
		return;
	$('#order_values_left_' + vcheck).click(function() {
		check_select_radio(vwhat, vval, vcheck, 1)
	});
	$('#order_values_right_' + vcheck).click(function() {
		check_select_radio(vwhat, vval, vcheck, 1)
	});

}

function do_mods_change(id, vals, def) {
	datastr = 'id=' + id + '&vals=' + vals + '&def=' + def;
	$.ajax({
		type : 'post',
		url : 'hc_scripts/coverdefaults.php',
		dataType : 'html',
		data : datastr,
		success : function(html) {
			// alert(html);
			$('#order_values_' + id).html(html);

		}
	});
}

function mods_change(thid) {

	valx = $('#order_values_' + thid).val();

	if (thid == 11) {
		if (valx == 60) {
			$('#order_values_left_61').attr('checked', false).attr('readonly',
					'readonly');
			$('#order_values_right_61').attr('checked', false).attr('readonly',
					'readonly');

			id = 14;
			vals = 274;
			def = 274;
		} else {
			$('#order_values_left_61').attr('readonly', false).attr('disabled',
					false).attr('checked', true);
			$('#order_values_right_61').attr('readonly', false).attr(
					'disabled', false).attr('checked', true);
			id = 14;
			curval = $('#order_values_' + id).val();
			if (valx == 55) {
				vals = '76,77,300,399';
				def = (curval == 274 ? def = 77 : curval);
			} else if (valx == 58) {
				vals = '77,300,399';
				def = (curval == 274 ? def = 77 : curval);
			} else if (valx == 56) {
				vals = '76';
				def = (curval == 274 ? def = 76 : curval);
			} else if (valx == 59) {
				vals = '76,77';
				def = (curval == 274 ? def = 77 : curval);
				;
			} else if (valx == 57) {
				vals = '400,76,77';
				def = (curval == 274 ? def = 77 : curval);
			} else if (valx == 410) {
				vals = '76,77';
				def = (curval == 274 ? def = 77 : curval);
				;
			}

		}
	} else if (thid == 13) {
		if (valx == 70) {
			$('#order_values_left_64').attr('checked', false).attr('readonly',
					'readonly');
			$('#order_values_right_64').attr('checked', false).attr('readonly',
					'readonly');
		} else {
			$('#order_values_left_64').attr('readonly', false).attr('disabled',
					false).attr('checked', true);
			$('#order_values_right_64').attr('readonly', false).attr(
					'disabled', false).attr('checked', true);
		}

	}
	do_mods_change(id, vals, def);

}
mods_change(11);
