<?php

if (isset($_GET['json'])) $myobj = json_decode(str_replace('|','"',$_GET['json']));

?>
// <script>
function do_defaults(ortho,recursfordef){

document.getElementById('ortho_notes').style.visibility="hidden";
	if (! ortho) ortho = $('#order_values_37').val();
	
	if (!recursfordef) {
		if (ortho == 185 || ortho == 183 || ortho == 186) {
			do_mods_change(11,60,60);
		} else {
			do_mods_change(11,'',60); // '' means all materials, 60 = blank default (11)
		}
		arrs = new Array('_left_','_right_');

		for (i in arrs) {
			if (ortho == 179) {
				$('#order_values' + i + 12).val(64);
			} else if (ortho == 220) {
				$('#order_values' + i + 12).val(67);
			} else if (ortho == 184) {
				$('#order_values' + i + 12).val(62);
			} else if (ortho == 185) {
				$('#order_values' + i + 12).val(61);
			} else if (ortho == 221) {
				$('#order_values' + i + 12).val(64);
			} else if (ortho == 222 || ortho == 187) {
				$('#order_values' + i + 12).val(296);
			} else if (ortho == 188) {
				$('#order_values' + i + 12).val(297);
			} else {
				$('#order_values' + i + 12).val(66);
			}
		}
	}
	
	datastr = 'ortho='+ortho + '&p=ortho';


	$.ajax({

		type: 'post',
		url: 'hc_scripts/functions.php',
		dataType: 'html',
		data: datastr,
		success: function (html) { //return: id:value:type (standard dropdown=0)
		items = html.split(',');
		if (recursfordef) {
				for (i in items) { //AGAIN TO SET DEFAULTS BECAUSE TRIGGER CHANGED DEFAULTS
				rvals = items[i].split(':'); 

				if (rvals[2] == '1') { // field type is radio
					$('#order_values_' + rvals[0] + '_' + rvals[1]).attr('checked','checked')
					// alert($('#order_values_' + rvals[0] + '_' + rvals[1]).val());
				} else if(rvals[2] == '3') {// left/right select
					$('#order_values_left_' + rvals[0]).val(rvals[1])
					$('#order_values_right_' + rvals[0]).val(rvals[1])
				} else if(rvals[2] == '4') {// left/right checkbox
				
					if(rvals[1] != undefined && rvals[1]) {
						$('#order_values_left_' + rvals[0]).attr('checked',true)
						$('#order_values_right_' + rvals[0]).attr('checked',true)
					} else {
						$('#order_values_left_' + rvals[0]).attr('checked',false)
						$('#order_values_right_' + rvals[0]).attr('checked',false)
					}
				} else { //type=(rvals[2] == '0'
					$('#order_values_' + rvals[0]).val(rvals[1]);
//					if (rvals[0] == 35) {
//						alert($('#order_values_35').html());
//						alert(rvals[1] + '-line:72:hc_order_form_defaults.php-' + $('#order_values_35').val());
//					} 
				}

			}
			return;
} else {
	
			for (i in items) {
				rvals = items[i].split(':'); 

				if (rvals[2] == '1') { // field type is radio
					
					$('#order_values_' + rvals[0] + '_' + rvals[1]).attr('checked','checked').trigger('change');

					if(rvals[0] == '55' && rvals[1] == '239') {
					postings_disable();
					}
						
	
					if(rvals[0] == '21' && rvals[1] == '108') {
					rf_none();
					}
	
					if(rvals[0] == '21' && rvals[1] == '109') {
					rf_extrinsic();
					}
					
					if(rvals[0] == '69' && rvals[1] == '324') {
					ff_none();
					}
					
					if(rvals[0] == '69' && rvals[1] == '326') {
					ff_intrinsic();
					}
					
				} else if(rvals[2] == '3') {// left/right select
					$('#order_values_left_' + rvals[0]).val(rvals[1]).trigger('change');
					$('#order_values_right_' + rvals[0]).val(rvals[1]).trigger('change');
				} else if(rvals[2] == '4') {// left/right checkbox
				
					if(rvals[1] != undefined && rvals[1]) {
						$('#order_values_left_' + rvals[0]).attr('checked',true).trigger('change');
						$('#order_values_right_' + rvals[0]).attr('checked',true).trigger('change');
					} else {
						$('#order_values_left_' + rvals[0]).attr('checked',false).trigger('change');
						$('#order_values_right_' + rvals[0]).attr('checked',false).trigger('change');
					}
				} else { //type=(rvals[2] == '0'
					$('#order_values_' + rvals[0]).val(rvals[1]).trigger('change');
					if (rvals[0] == 27) cover_ajax(27,'',$('#order_values_27').val());
					
				}

			}
//			$('#order_values_35').val(160)

		do_defaults(ortho,1);			
}
		}

	});

	
}

function halux_splint() {
	if ($('#order_values_right_49').is(':checked') || $('#order_values_left_4').is(':checked')) {
		$('#order_values_7').val(42);
		$('#order_values_54').val(231);
		$('#order_values_31').val(171).trigger('change');
		$('#order_values_32').val(176);
		$('#order_values_33').val(169);
	}
}
// do_defaults('<?php echo $myobj->o37;?>');
//</script>