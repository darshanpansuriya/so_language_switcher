$(document).ready(function() {	
	$(".name_fields").keyup(function() {
		if (this.value.match(/[^a-zA-Z]/g)) {
			this.value = this.value.replace(/[^a-zA-Z\ ]/g, '');
		}
	});
	$(".phone_fields").keyup(function() {
		if (this.value.match(/[^0-9]/g)) {
			this.value = this.value.replace(/[^0-9\-\ ]/g, '');
		}
	});
	$("#clinic_new_label_name").keyup(function() {
		if (this.value.match(/[^0-9a-zA-Z\-\_]/g)) {
			this.value = this.value.replace(/[^0-9a-zA-Z\-\_]/g, '');
		}
	});
	$(".tax_rate_fields").keyup(function() {
		if (this.value.match(/[^0-9]/g)) {
			this.value = this.value.replace(/[^0-9\.]/g, '');
		}
	});
	$(".short_form_fields").keyup(function() {
		if (this.value.match(/[^a-zA-Z]/g)) {
			this.value = this.value.replace(/[^a-zA-Z]/g, '');
		}
	});
	$("#email").keyup(function() {
		if (this.value.match(/[^0-9a-zA-Z]/g)) {
			this.value = this.value.replace(/[^0-9a-zA-Z\_\@\-\.]/g, '');
		}
	});
	$(".itemprice").live('blur',function() {
		if (this.value.match(/[^0-9]/g)) {
			this.value = this.value.replace(/[^0-9\.]/g, '');
		}
	});
	$("#confirmpassword").keyup(function() {
		if (this.value.match(/[^0-9a-zA-Z]/g)) {
			this.value = this.value.replace(/[^0-9a-zA-Z]/g, '');
		}
	});
		$(".pick,.priority").keyup(function() {
		if (this.value.match(/[^0-9]/g)) {
			this.value = this.value.replace(/[^0-9]/g, '');
		}
	});
});