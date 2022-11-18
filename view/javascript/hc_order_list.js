$(document).ready(function() {
//	$('.hc_delivery_date[385]').css('display','inline-block');
	var valx = 0;

	$('#hc_save_delivery').click(function () {
		$('#text_editdate').html('');
		conf = confirm("Are you sure?");

		if (!conf) return;
		$('#text_editdate').html('Please Wait...<img src="../view/image/loading.gif" />');
		$('#text_editdate').css('display','inline-block');

//		$('#text_editdate').show();

		$.ajax({
			type: 'post',
			url: 'hc_scripts/savedelivery.php',
			dataType: 'html',
			data: $('#form').serialize(),
			success: function (html) {
				$('#text_editdate').html(html);
			},
			complete: function () {
//				alert('done');
			}
		});

	});

	$('#hc_save_status').click(function () {
		$('#text_editdate').html('');
		conf = confirm('Statuses will be updated...\n\nAre you sure?');

		if (!conf) return;
		$('#text_editdate').html('Please Wait...<img src="../view/image/loading.gif" />');
		$('#text_editdate').css('display','inline-block');

		$('#text_editdate').show();

		$.ajax({
			type: 'post',
			url: 'hc_scripts/savestatus.php',
			dataType: 'html',
			data: $('#form').serialize(),
			success: function (html) {
//				$('#text_editdate').html(html);
				$('.cl_stat_box').css('color','#000000');
				alert(html);
				location=location;
			},
			complete: function () {
				//
			}
		});

	});

        $('#batch_save_status').click(function () {

		var batch_status = $('#batch_save_status_dd').val();
//		$('#text_editdate').show();
		if(batch_status>0){

                $('#text_editdate').html('');
		conf = confirm('No validation on statuses is done, statuses for selected orders will be updated...\n\nAre you sure?');

		if (!conf) return;
		$('#text_editdate').html('Please Wait...<img src="../view/image/loading.gif" />');
		$('#text_editdate').css('display','inline-block');

		$.ajax({
			type: 'post',
			url: 'hc_scripts/batchsavestatus.php?status=' + batch_status,
			dataType: 'html',
			data: $('#form').serialize(),
			success: function (html) {
//				$('#text_editdate').html(html);
				//$('.cl_stat_box').css('color','#000000');
				alert(html);
				location=location;
			},
			complete: function () {
				//
			}
		});
               }
               else{
                   alert('Please select a status before using the batch save option');
               }

	});


	$('#hc_copy_order').click(function () {
		$('#text_editdate').html('');
		conf = confirm('You are copying order\n\nAre you sure?');
		if (!conf) return false;

		if ($('input[name="selected[]"]:checked').length != 1) {
			$('#text_editdate').html('Select 1 order');
			$('#text_editdate').css('display','inline-block');
			return false;
		}
		$('#text_editdate').html('Please Wait...<img src="../view/image/loading.gif" />');
		$('#text_editdate').css('display','inline-block');

//		$('#text_editdate').show();

		$.ajax({
			type: 'post',
			url: 'hc_scripts/copyorder.php',
			dataType: 'html',
			data: $('#form').serialize(),
			success: function (html) {
			//$('#text_editdate').html(html);
				alert(html);
				location=location;
			},
			complete: function () {
				//
			location=location;
			}
		});

	});



});

function send_ind_mail(orderid){
		$('#orderidx').val(orderid);
		$.ajax({
			type: 'post',
			url: 'hc_scripts/sendmail.php',
			dataType: 'html',
			data: $('#form').serialize(),
			success: function (html) {
				$('#hc_delivery_date_' + orderid).css('display','none');
			},
			complete: function () {
//				alert('done');
			}
		});
}

function show_stat_button(divx,myx,order_id) {

	$("#"+divx).show();
	$(myx).css('color','red');
	$("#changed_status_"+order_id).attr('name','order_status_id['+order_id+']');

	$('.pagination .links a').unbind('click');
	return $('.pagination .links a').click(function () {
		conf = confirm('There are status changes...\n\nPress OK to go to the page; changes will be lost.\nPress Cancel to commit changes by clicking on \'Save Status\'');
		if (!conf) return false;
	})

}

function bring_buttons() {
	hide = 0;
	hidePrintPackingSlip = 0;

	vchecked = $('input[name="selected[]"]:checked').length;

	if (vchecked == 0) {
            $('.sp_hc_delete_order').hide(500);
			  $('.sp_hc_print_order').hide(500);
				$('.sp_hc_print_workorder').hide(500);
			    $('.sp_hc_download_order').hide(500);
            $('.batch_save_component').hide(500);
            $('.sp_hc_create_packing_slip').hide(500);
            hide=1;
            hidePrintPackingSlip=1;
        }
	else {
            $('.sp_hc_delete_order').show(500);
            $('.batch_save_component').show(500);
			 $('.sp_hc_print_order').show(500);
			 $('.sp_hc_print_workorder').show(500);
			  $('.sp_hc_download_order').show(500);
        }

	if (vchecked == 1)
            $('.sp_hc_copy_order').show(500);
	else {
            $('.sp_hc_copy_order').hide(500);
        }

	$('input[name="selected[]"]:checked').each(function () {
//		alert($('select[name="hc_order_status_id['+$(this).val()+']"]').val()); // value of status
       if($('select[name="hc_order_status_id['+$(this).val()+']"]').val() < 35) hide=1;
       if($('select[name="hc_order_status_id['+$(this).val()+']"]').val() != 130) hidePrintPackingSlip=1;
	});
	if (hide) $('.sp_hc_print_label').hide(500);
	else $('.sp_hc_print_label').show(500);

	if (hidePrintPackingSlip) $('.sp_hc_create_packing_slip').hide(500);
	else $('.sp_hc_create_packing_slip').show(500);
}

function hc_print_label(){
//	$('#form').attr('action','hc_scripts/print_labels.php');
	$('#form').submit();
//	$('#form').attr('action','');
}

function printOrder(){
	  var ids ='';
	 $('input[name="selected[]"]:checked').each(function(index,value){
	 		if(ids == ''){
	 			ids= $(this).val();
	 		}else{
	 			ids+= ","+$(this).val();
	 		}

	 });

	window.open('hc_scripts/print_pdf_file_multiple.php?ids='+ids,'_blank','height=600,width=700,top=200,left=300,resizable');
}

function printWorkOrder(){
	  var ids ='';
	 $('input[name="selected[]"]:checked').each(function(index,value){
	 		if(ids == ''){
	 			ids= $(this).val();
	 		}else{
	 			ids+= ","+$(this).val();
	 		}

	 });

	window.open('hc_scripts/wo_print_pdf_file_multiple.php?ids='+ids,'_blank','height=600,width=700,top=200,left=300,resizable');
}

function downloadOrder(){
	  var ids ='';
	 $('input[name="selected[]"]:checked').each(function(index,value){
	 		if(ids == ''){
	 			ids= $(this).val();
	 		}else{
	 			ids+= ","+$(this).val();
	 		}

	 });

	window.open('hc_scripts/download_orders_file.php?ids='+ids,'_blank','height=600,width=700,top=200,left=300,resizable');
}
