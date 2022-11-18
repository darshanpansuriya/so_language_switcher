<?php
include "../config.php";
	
$a = mysql_connect(DB_HOSTNAME,DB_USERNAME,DB_PASSWORD);
	
mysql_select_db(DB_DATABASE);

function create_ortho_select($id,$available,$default){
	$l = mysql_fetch_object(mysql_query("SELECT * FROM lookup_table_types WHERE lookup_table_types_id = '$id'"));
	
	$s = "SELECT * FROM lookup_table WHERE lookup_table_lookup_table_types_id = '$id' AND find_in_set(lookup_table_id,'$available') ORDER BY lookup_table_sort";
	$r = mysql_query($s);
	echo "<label>$l->lookup_table_types_name</label>";
	echo "<select name='order_values_$id' id='order_values_$id' onchange=\"do_ortho_cond(\$('#order_values_7').val(),\$('#order_values_53').val(),\$('#order_values_54').val())\">";
	while($v = mysql_fetch_object($r)) {
		echo "<option value='$v->lookup_table_id'".($default == $v->lookup_table_id ? " selected='selected'":'').">$v->lookup_table_title</option>\n";
	}
	echo "</select>\n";

	
}

$id = $_POST['id'];
$radio = $_POST['r'];
$default_shell = $_POST['s'];
$default_material = $_POST['m'];
$default_thickness = $_POST['t'];

$available_shell = '41,42';
$available_material = '224,226,225,228,227';
$available_thickness = '237,229,230,231,232,233,234,278,279,280,235,236';


if ($radio == '316') {

	$available_shell = '41';
	$available_material = '224';
	
	if (!$default_shell || !in_array($default_shell,explode(',',$available_shell))) $default_shell = 41;
	if (!$default_material || !in_array($default_material,explode(',',$available_material))) $default_material = 224;
	if (!$default_thickness || !in_array($default_thickness,explode(',',$available_thickness))) $default_thickness = 235;

} elseif ($radio == '277') {
	$available_shell = '41,42';
	$available_material = '259,224';
	
	
	if (!$default_shell || !in_array($default_shell,explode(',',$available_shell))) $default_shell = 41;
	if (!$default_material || !in_array($default_material,explode(',',$available_material))) $default_material = 224;
	if (!$default_thickness || !in_array($default_thickness,explode(',',$available_thickness))) $default_thickness = 233;

} elseif ($radio == '317') {


	$available_shell = '42';
	
	if (!$default_shell || !in_array($default_shell,explode(',',$available_shell))) $default_shell = 42;
	if (!$default_material || !in_array($default_material,explode(',',$available_material))) $default_material = 224;

	if ($default_material == 224) {
		$available_thickness = '229,231,233,234';
		if (!$default_thickness || !in_array($default_thickness,explode(',',$available_thickness))) $default_thickness = 231;
	} else {
		$available_thickness = '237';
		if (!$default_thickness || !in_array($default_thickness,explode(',',$available_thickness))) $default_thickness = 237;
	}
//########################################################################################################################3

} elseif ($radio == '183') {
	$available_shell = '42';
	$available_material = '225,226,228,227';
	
	
	if (!$default_shell || !in_array($default_shell,explode(',',$available_shell))) $default_shell = 42;
	if (!$default_material || !in_array($default_material,explode(',',$available_material))) $default_material = 226;
	if (!$default_thickness || !in_array($default_thickness,explode(',',$available_thickness))) $default_thickness = 237;
//########################################################################################################################3
} elseif ($radio == '186') {
	$available_shell = '42';
	$available_material = '225,226,228,227';
	$available_thickness = '237';
	
	if (!$default_shell || !in_array($default_shell,explode(',',$available_shell))) $default_shell = 42;
	if (!$default_material || !in_array($default_material,explode(',',$available_material))) $default_material = 228;
	if (!$default_thickness || !in_array($default_thickness,explode(',',$available_thickness))) $default_thickness = 237;
//########################################################################################################################3

} elseif ($radio == '188') {
	$available_shell = '42';
	
	if (!$default_shell || !in_array($default_shell,explode(',',$available_shell))) $default_shell = 42;
	if (!$default_material || !in_array($default_material,explode(',',$available_material))) $default_material = 224;

	if ($default_material == 224) {
		if (!$default_thickness || !in_array($default_thickness,explode(',',$available_thickness))) $default_thickness = 231;
	} else {
		$available_thickness = '237';
		if (!$default_thickness || !in_array($default_thickness,explode(',',$available_thickness))) $default_thickness = 237;
	}
} else {
	if (!$default_shell || !in_array($default_shell,explode(',',$available_shell))) $default_shell = 41;
	if ($default_shell == 41) {
		$available_material = '259,224';
		if (!$default_material || !in_array($default_material,explode(',',$available_material))) $default_material = 224;
		if (!$default_thickness || !in_array($default_thickness,explode(',',$available_thickness))) $default_thickness = 235;
	} else {
		$available_material = '224';
		if (!$default_material || !in_array($default_material,explode(',',$available_material))) $default_material = 224;
		$available_thickness = '229,231,233,234';
		if (!$default_thickness || !in_array($default_thickness,explode(',',$available_thickness))) $default_thickness = 231;
	}
}
		if ($id == '7') {
			$available = $available_shell;
			$default = $default_shell;
		} elseif($id == '53') {
			$available = $available_material;
			$default = $default_material;
		} elseif($id == '54') {
			$available = $available_thickness;
			$default = $default_thickness;
		}

		
create_ortho_select($id,$available,$default);
