<?php
if (isset($_GET['json'])) $myobj = json_decode(str_replace('|','"',$_GET['json']));
?>
// <script>
function get_provinces(prid) {
	datastr = 'countryid=' + $("select[name='patient_country_id']").val() + '&p=provinces&pid=' + prid;
	$.ajax({
		type: 'post',
		url: 'hc_scripts/functions.php',
		dataType: 'html',
		data:datastr,
		success: function (html) {
			$("select[name='patient_province_id']").html(html); 
		}
	});
}


function get_clinicians(pclid) {
	datastr = 'srcval='+$('#patient_clinic_id').val() + '&p=clinicians&f=patient_clinic_id&cid=' + pclid;
	
	$.ajax({
		type: 'post',
		url: 'hc_scripts/functions.php',
		dataType: 'html',
		data: datastr,
		success: function (html) {
			$('#patient_clinician_id').html(html); 
		},	
		complete: function () {
//					alert("done...");
		}
	});
}

function ch_date(str){
	m = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
	ma = str.split('-'); 
	if($.isNumeric(ma[1])) {
		d = ma[0] + '-' + m[ma[1] - 1] + '-' +ma[2]
	} else {
		t = '0' + ($.inArray(ma[1],m) + 1);
		d = ma[0] + '-' + t.slice(-2) + '-' + ma[2]
	}
	return d;
}

$(document).ready(function() {
	$('.date').datepicker({dateFormat: 'yy-M-dd',changeMonth: true,changeYear: true, yearRange: '-100:+5'});

<?php
	if ($myobj->prg_id == "patient_form") {
?>			
	get_provinces('<?php echo $myobj->province_id;?>');
	get_clinicians('<?php echo $myobj->patient_clinician_id;?>');
<?php
	}
?>	
	$('form input[type=radio]').css('width','auto');
	$('form input[type=checkbox]').css('width','auto');
	
<?php
	if (!in_array($myobj->prg_id,array("order_form","settings_form"))) {
?>			
	$.tabs('#tabs a');
<?php } ?>
});
//</script>