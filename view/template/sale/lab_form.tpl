<?php echo $header; ?>



<?php

error_reporting(0);

if (isset($_SESSION['s_print1page']) && $_SESSION['s_print1page']) {

	echo "<script>window.open('hc_scripts/pdf_file.php?oid=$order_id','_blank','height=600,width=700,top=200,left=300,resizable');</script>";

	unset($_SESSION['s_print1page']);

}

$user_name = $this->user->getUserName();

$user_group_string = $this->user->getUGstr();

?>

<script type="text/javascript" src="css/fancybox/jquery.fancybox-1.3.4.pack.js"></script>

<link rel="stylesheet" type="text/css" href="css/fancybox/jquery.fancybox-1.3.4.css" media="screen" />



<?php



$exclusive = "<span style='color:red; float:left;'>**</span>";

// p(get_defined_vars(),__LINE__);



$this->session->data['s_order_id'] = $order_id;



$user_group_string = $this->user->getUGstr();



$user_name = $this->user->getUserName($this->session->data['user_id']);



$route = $this->request->isget('route'); ?>

<?php if (isset($this->session->data['success']) && $this->session->data['success']) { ?>



<div class="success">

	<?php echo $this->session->data['success']; $this->session->data['success']=''; ?>

</div>

<?php } ?>

<?php if ($error_warning) { ?>



<div class="warning">

	<?php echo $error_warning; ?>

</div>

<?php } ?>

<?php /*?><a onclick="alert($('#order_values_35').val());">AAAA</a><?php */?>


<form action="" method="post" enctype="multipart/form-data" id="form_my_custom">

	<?php
	if ($patient_clinic_id) {
      	 		echo '<input type="hidden" value="'.$patient_clinic_id.'" name="patient_clinic_id" />'; } ?>

<!--		<input type="hidden" name="order_id" value="<?php echo $_REQUEST['order_id']?>"/>-->
		<input type="hidden" value="<?php echo $_REQUEST['order_id']?>" name="selected[]" id="orderidx" />

</form>

<form action="" class="cmxform" method="post"

	enctype="multipart/form-data" id="orderform" name="orderform">

	<input type="hidden" id="where_am_i" value="" name="where_am_i" /> <input

		type="hidden" id="orthotic_type" name="orthotic_type"

		value="<?php echo $order_values_7;?>" />

	<div class="h1float">

		<h1>

			<?php echo ($route == 'sale/order/insert' || !isset($order_id) || !$order_id? 'Place a New':'Update')?>

			<strong>Order</strong>

		</h1>

	</div>

	<div class="toptext">

		<table cellspacing="0">

			<tr>

				<?php if($this->request->isget('order_id')) { ?>

				<td><span class="toptitle">ORDER #:</span> <?php echo $order_id;?></td>

				<td><span class="toptitle">REFERENCE #:</span> <?php echo $patient_id;?>

				</td>

				<td><span class="toptitle">PATIENT NAME:</span> <?php echo $patient_name;?>

				</td>

				<?php } ?>

				<td><span class="toptitle">ORDER DATE:</span> <?php echo $order_date_added?$this->hc_functions->ch_date($order_date_added):date('Y-M-d');?>

				</td>

				<?php



				//if(isset($order_id) && ! $order_id) $order_id = $this->request->isget('order_id');

				if ($order_id) {

                //p($order_id.__FILE__.__LINE__);

                //p($order_status_id.__FILE__.__LINE__);

                $sql = "SELECT * FROM order_status WHERE order_status_id = '$order_status_id'";



                $status_name = $this->db->query($sql);



                $statusn = $status_name->row['order_status_name'];

                } else {

                $statusn = "Order Setup";

                }

                ?>

				<td><span class="toptitle">STATUS:</span> <?php echo $statusn;?></td>

			</tr>

		</table>

	</div>



	<div class="cl" style="height: 7px;"></div>



	<div class="lefttabs" id="tabs">

		<a tab="#tab_patient_info" id="a_patient_info"

			onclick="do_my_tabs(this);"><span>1</span>

			<div <?php if(isset($error_1) && $error_1) echo " class='red'";?>>Patient

				Info</div> </a> <a tab="#tab_ortho_type" id="a_ortho_type"

			onclick="do_my_tabs(this);"><span>2</span>

			<div <?php if(isset($error_2) && $error_2) echo " class='red'";?>>Ortho

				Type</div> </a> <a tab="#tab_mods" id="a_mods"

			onclick="do_my_tabs(this);"><span>3</span>

			<div <?php if(isset($error_3) && $error_3) echo " class='red'";?>>Mods</div>

		</a> <a tab="#tab_additions" id="a_additions"

			onclick="do_my_tabs(this);"><span>4</span>

			<div <?php if(isset($error_4) && $error_4) echo " class='red'";?>>Additions</div>

		</a> <a tab="#tab_posting" id="a_posting" onclick="do_my_tabs(this);"><span>5</span>

			<div <?php if(isset($error_5) && $error_5) echo " class='red'";?>>Posting</div>

		</a> <a tab="#tab_covers" id="a_covers" onclick="do_my_tabs(this);"><span>6</span>

			<div <?php if(isset($error_6) && $error_6) echo " class='red'";?>>Covers</div>

		</a> <a tab="#tab_delivery" id="a_delivery"

			onclick="do_my_tabs(this);"><span>7</span>

			<div <?php if(isset($error_7) && $error_7) echo " class='red'";?>>Delivery</div>

		</a> <a tab="#tab_general" id="a_general" onclick="do_my_tabs(this);"><span>8</span>

			<div <?php if(isset($error_8) && $error_8) echo " class='red'";?>>General</div>

		</a> <a tab="#tab_files" id="a_files" onclick="do_my_tabs(this);"><span>9</span>

			<div <?php if(isset($error_9) && $error_9) echo " class='red'";?>>Files</div>

		</a> <a tab="#tab_status" id="a_status" onclick="do_my_tabs(this);"><span>10</span>

			<div <?php if(isset($error_10) && $error_10) echo " class='red'";?>>Status</div>

		</a>

		<?php if ($user_group_string == '1' && $order_id != ''){ ?>

		<a tab="#tab_pricing" id="a_pricing" onclick="do_my_tabs(this);"><span>11</span>

			<div <?php if(isset($error_10) && $error_10) echo " class='red'";?>>Pricing</div>

		</a>

		<?php } ?>



	</div>

	<div class="main">

		<div class="main-top">

			<!--<img class="bg_image" src="images/bg_middle_top.png" />-->

		</div>

		<div class="main-mid"

			style="margin-left: -1px; overflow: visible; margin-right: -1px;">



			<div id="to_be_disabled">

				<div id="tab_patient_info" class="tabs">

					<h2>1. Please enter your patient's information.</h2>

					<?php

					$disabled='';$readonly='';

					if ($this->request->isget('route') == 'sale/order/update') {

                    $disabled = ' disabled="disabled" ';

                    $readonly = ' readonly="readonly" ';

                    }



                    if ($this->request->isget('pid') || $this->request->isget('route') == 'sale/order/insert') {





                    ?>

					<div class="ftitle" style="width: 100%;">

						<label>Select Patient or Enter New Patient Information Below:</label>

						<?php



						$patient_id = $this->request->isget('pid');



						if ($patient_id) 	{

                        $disabled = ' disabled="disabled" ';

                        $readonly = 'readonly="readonly" ';

                        }



                        $this->load->model('sale/patient');



                        $patient_array =  $this->model_sale_patient->getPatients();



                        //print_r($patient_array);



                        //p($patient_array,__LINE__.__FILE__);



                        echo $this->hc_functions->selectFromArray('patient_id',$patient_id,$patient_array ,'patient_id','name','patient_dob',' onchange="get_order_form();"',"Add New...");



                        ?>





					</div>



					<div

						style="clear: both; border-bottom: 1px solid #B8DCFB; height: 5px; margin-bottom: 10px"></div>

					<?php

                    }







                    if (isset($patient_id) && $patient_id) {



                    // HERE ADD WHICH PATIENTS YOU CAN BRING



                    $this->load->model('sale/patient');



                    $patient_info =  $this->model_sale_patient->getPatient($patient_id);

                    //p($patient_info,__LINE__.__FILE__);



                    extract($patient_info);

                    //	p($patient_info);

                    foreach ($patient_info as $k=>$v) {

                    if (!$v && isset($this->request->post[$k])) {

                    ${

$k} = $this->request->post[$k];

$disabled = '';

$readonly = '';

                    }

                    //		p($this->post[$k]);

                    }

                    }

                    ?>

					<fieldset>

						<div class="divleft">

							<div class="ftitle">

								<label><span class="required">*</span> First Name:</label> <input

									type="text" id="patient_firstname" name="patient_firstname"

									value="<?php echo $patient_firstname; ?>"

									<?php if ($patient_firstname) echo $readonly;?> />

								<div class="error">

									<?php if ($error_patient_firstname) { ?>

									<?php echo $error_patient_firstname; ?>

									<?php } ?>

									&nbsp;

								</div>

							</div>

							<div class="ftitle">

								<label><span class="required">*</span> Last Name:</label> <input

									type="text" id="patient_lastname" name="patient_lastname"

									value="<?php echo $patient_lastname; ?>"

									<?php if ($patient_lastname) echo $readonly;?> />

								<div class="error">

									<?php if ($error_patient_lastname) { ?>

									<?php echo $error_patient_lastname; ?>

									<?php } ?>

									&nbsp;

								</div>

							</div>



							<div class="ftitle">

								<label><!--<span class="required">*</span>-->Date of birth:

									(yyyy-mm-dd)</label> <input type="text" class="date_birth"

									onchange="" id="patient_dob" name="patient_dob"

									value="<?php if($patient_dob != '0000-00-00' && $patient_dob != '1900-01-01') echo $patient_dob; ?>">

								<div class="error">

									<?php if ($error_patient_birthdate) { ?>

									<?php echo $error_patient_birthdate; ?>

									<?php } ?>

								</div>

							</div>





							<div class="ftitle">

								<label><span class="required">*</span> Clinic:</label>

								<?php

								if ($patient_clinic_id) {

                                echo '<input type="hidden" value="'.$patient_clinic_id.'" name="patient_clinic_id" />';

                                }

                                ?>

								<select name="patient_clinic_id"

									onchange=" do_casting_method();get_clinicians('<?php echo $patient_clinician_id;?>');get_addresses();<?php if ($patient_id == '') echo "return checkPatientData(document.getElementById('patient_firstname').value,document.getElementById('patient_lastname').value,document.getElementById('patient_dob').value,document.getElementById('patient_clinic_id').value)";?>"

									id="patient_clinic_id"

									<?php if ($patient_clinic_id) echo $disabled;?>>

									<option value="">Select...</option>

									<?php foreach ($clinics as $cliniclist) { ?>

									<option value="<?php echo $cliniclist['clinic_id'];?>"

									<?php if ($cliniclist['clinic_id'] == $patient_clinic_id) echo ' selected="selected"'; ?>>

										<?php echo $cliniclist['clinic_name'];?>

									</option>

									<?php } ?>

								</select>

								<div class="error">

									<?php if ($error_patient_clinic_id) { ?>

									<?php echo $error_patient_clinic_id; ?>

									<?php } ?>

									&nbsp;

								</div>

							</div>

							<div class="ftitle">

								<label>Clinician:</label> <select name="patient_clinician_id"

									id="patient_clinician_id">

									<option value="<?php echo $patient_clinician_id;?>">

										<?php echo $clinician_name;?>

									</option>

								</select>

								<div class="error">&nbsp;</div>

							</div>



							<div class="ftitle" style="width: 180px;">

								<label>Weight:</label> <input type="text" name="patient_weight"

									value="<?php echo $patient_weight; ?>" style="width: 100px" />

								<?php

								$s = "SELECT * FROM lookup_table LEFT JOIN lookup_table_types ON lookup_table_lookup_table_types_id = lookup_table_types_id WHERE lookup_table_lookup_table_types_id = 1 ORDER BY lookup_table_sort";



                                echo $this->hc_functions->selectFromSql('patient_weight_id',$patient_weight_id,$s ,'lookup_table_id','lookup_table_title','style="width:auto;"')?>

								<div class="error">&nbsp;</div>

							</div>





							<div class="ftitle" style="float: right; width: 100px;">

								<label>Gender:</label>

								<?php



								$s = "SELECT * FROM lookup_table LEFT JOIN lookup_table_types ON lookup_table_lookup_table_types_id = lookup_table_types_id WHERE lookup_table_lookup_table_types_id = 2 ORDER BY lookup_table_sort";



								if ($patient_gender_id != '12') $patwe = $disabled; else $patwe = "";



                                echo $this->hc_functions->selectFromSql('patient_gender_id',$patient_gender_id,$s ,'lookup_table_id','lookup_table_title','style="width:auto;" onchange="get_shoesizes(\'\');"' . $patwe); // gender ?>

								<div class="error">&nbsp;</div>

							</div>







						</div>

						<!-- EOF LEFT -->



						<div class="divright">

							<div class="ftitle">

								<label>Shoe Size:</label> <select name="order_values_3"

									id="order_values_3">

								</select>



								<?php //$this->hc_functions->print_select(3,$order_id); //shoe size ?>

							</div>

							<div class="ftitle">

								<?php $this->hc_functions->print_select(4,$order_id); //shoe type ?>

							</div>
                            <div class="ftitle">

								<label>Shoe Width:</label>

								<input type="text" name="shoes_width" value="<?php echo $shoes_width;?>" />

								<div class="error">&nbsp;</div>

							</div>

							<div class="ftitle">

								<label>Activity level:</label> <input type="text"

									name="order_values_5" value="<?php echo $order_values_5; ?>" />



								<?php //$this->hc_functions->print_checkbox(5,$order_id); //activity level  converted from select to free text?>

								<div class="error">&nbsp;</div>



							</div>

							<div class="ftitle">

								<label>Patient Notes:</label>

								<textarea name="patient_notes" style="height: 60px;"<?php if ($patient_notes) echo $readonly;?>><?php echo $patient_notes; ?></textarea>

								<div class="error">&nbsp;</div>

							</div>



							<div class="ftitle">

								<label>Complaints:</label>

								<textarea name="order_values_patient_complaints" style="height: 90px;"><?php echo $order_values_patient_complaints; ?></textarea>

								<div class="error">&nbsp;</div>

							</div>



						</div>

						<!-- EOF RIGHT -->



					</fieldset>

				</div>

				<div id="tab_ortho_type" class="tabs">

					<div class="error">

						<h3>

							<?php echo $error_order_values_37; ?>

						</h3>

						&nbsp;

					</div>

					<h2>2. Choose your patients orthotic type.</h2>

					<fieldset>

						<div class="divleft">



							<?php

							$ortho_categories = $this->db->query("SELECT * FROM lookup_table WHERE lookup_table_lookup_table_types_id = 37 ORDER BY lookup_table_sort");



							//p($ortho_categories,__LINE__.__FILE__);



							/*if (! $order_id) {

							 $order_values_7 = 41;

                            $order_values_53 = 224;

                            $order_values_54 = 235;



                            }

                            */







                            if ($ortho_categories->num_rows) {

                            foreach($ortho_categories->rows as $ortho_category) {



                            $main_cat = $ortho_category['lookup_table_lookup_table_types_id'];



                            $lines = explode(",",$ortho_category['lookup_table_text']);

                            ?>

							<div class="ftitle">

								<label class="orthot"><?php echo $ortho_category['lookup_table_title'];?>:</label>

								<?php





								$line1 = array(); $line2 = array(); $line3=array();

								foreach($lines as $line) {



                                $name = $line;

                                //			$name = $ortho_category['lookup_table_id'] . "-" . $line;

                                $line_name = $this->db->query("SELECT * FROM lookup_table WHERE lookup_table_id = '$line'");

                                $line_name = $line_name->row;



                                $files = glob("images/ortho/". $name. "*");



                                $first = 1;

                                $line1t = "";

                                $this->load->model('tool/image');



                                foreach($files as $img){



                                $line1t .= '<a class="gallery" href="hc_scripts/functions.php?id='.$name.'&c='.urlencode($ortho_category['lookup_table_title']).'&p=fancy&img='.urlencode(str_replace('.','||',$img)).'" title="'.$ortho_category['lookup_table_title'].' - '.$line_name['lookup_table_title'].'" rel="gallery_'.$main_cat.'_'.$name.'">';



                                if ($first){



                                $line1t .= '<img src="' . ($this->model_tool_image->resize('ortho/'.basename($img),80,60)) . '" width="80" style="border:1px solid #BADDFB" />';

                                $first = 0;



                                }



                                $line1t .= '</a>';

                                }

                                $line1[] = $line1t;

                                $line2[] = '<label>' . $line_name['lookup_table_title'] . '</label>';



                                $clname = "order_values_$main_cat";



                                $line3t = '<input type="radio" value="'.$name.'" name="'.$clname.'"';



                                if (${

$clname} == $name) $line3t .= ' checked="checked"';



$line3t .= " onmousedown=\"all_ortho_funct($line);\" onmouseup=\"all_ortho_funct($line);\"";



$line3t .= 'style=margin-top:5px>';

$line3[] = $line3t;

                                }



                                echo "<table><tr>";

                                foreach ($line1 as $val) echo "<td width='60'>$val</td>";

                                echo "</tr><tr>";

                                foreach ($line2 as $val) echo "<td>$val</td>";

                                echo "</tr><tr>";

                                foreach ($line3 as $val) echo "<td>$val</td>";

                                echo "</tr></table>";





                                ?>

								<div class="error">&nbsp;</div>

							</div>

							<?php

                            }

                            }

                            ?>



						</div>

						<!-- EOF LEFT -->



						<div class="divright" >

						<div id="loadingdefaults" style="position: center; display: none;">

								<img src="view/image/loader.gif" width="100" height="100" />

							</div>

						<div id="holdortho">

							<div class="ftitle">

								<div id="div_7"></div>

							</div>



							<div class="ftitle">

								<div id="div_53"></div>

							</div>



							<div class="ftitle">

								<div id="div_54"></div>

							</div>



							<div class="ftitle">

								<label id="ortho_notes">Ortho Type Notes:</label>

								<textarea name="order_values_ortho_type_notes" rows="12"><?php echo $order_values_ortho_type_notes; ?></textarea>

								<div class="error">&nbsp;</div>

							</div>

						</div>







							</div>





					</fieldset>

				</div>

				<div id="tab_mods" class="tabs">

					<!-- Mods -->

					<h2>3. Please enter orthotic modifications.</h2>

					<div class="error">

						<?php if (isset($error_top_3)) echo $error_top_3;?>

					</div>

					<fieldset>

						<div class="divleft">

							<div class="ftitle">

								<?php $this->hc_functions->print_select(9,$order_id,'','class="shortbox"'); //Cast Dressing ?>

							</div>

							<div class="ftitle">

								<?php $this->hc_functions->print_select(10,$order_id,'','class="shortbox"'); //Shell Width ?>

							</div>

							<div class="ftitle">

								<?php $this->hc_functions->print_select(16,$order_id,'','class="shortbox"'); //Shell Grind ?>

							</div>



							<?php /*?>                        <div class="ftitle">

                            <div id="div_16" style="float:left"></div>



                            <?php //$this->hc_functions->print_select(16,$order_id,0,'style="width:200px;"'); //Shell Grind ?>

                            </div>

                            <?php */?>

							<div class="ftitle">

								<?php echo $error_arc_fills;?>

								<div style="float: right; margin-top: -32px;">

									<p>

										<label>Left</label>

									</p>

									<p>

										<label>Right</label>

									</p>

								</div>

								<?php $this->hc_functions->print_select(11,$order_id,'','class="shortbox" onchange="mods_change(11);" style="float:left;"'); //Arch Fill Material ?>



								<div class="shortright">

									<!-- <div style="float:right;">



								<p><input style="width: auto;" type="checkbox" name="order_values_left_61" id="order_values_left_61"

												 value="261" <?php // if($order_values_left_61 == '261')  echo "checked"; ?> onclick="do_check_both(this.id,61);"></p>



								<p><input style="width: auto;" type="checkbox" name="order_values_right_61" id="order_values_right_61"

												 value="260" <?php // if($order_values_right_61 =='260')  echo "checked";?> ></p>



								</div>		-->

									<?php  $this->hc_functions->print_checkbox(61,$order_id,'','',1); //1=notitle ?>

								</div>

							</div>





							<div class="ftitle">

								<?php $this->hc_functions->print_select(14,$order_id,'','class="shortbox"'); //Arch Fill Thickness: ?>

							</div>

							<!--

                                                    <div class="ftitle">

                                                            <div id="div_14" style="float:left"></div>



                                                        <?php //$this->hc_functions->print_select(14,$order_id,'','style="width:200px;"'); //Arch Fill Thickness ?>

                                                    </div>



                            -->

							<div class="ftitle">

								<?php $this->hc_functions->print_select(12,$order_id); //Heel cup depth ?>

							</div>

							<?php

							/*                         <div class="ftitle">

							 <label style="float:left">Heel Cup Depth (mm):</label>

							<div style="float:right">

							<p id="div_12"></p>

							<p id="div_68"></p>

							</div>

							<?php

							$err = "";

							//if (trim($error_order_values_12 .$error_order_values_68)) $err = 'Enter '.$error_order_values_12 . $error_order_values_68;



							//if ($order_values_left_12 == 61) $order_values_left_12 = 66;

							//if ($order_values_right_12 == 61) $order_values_right_12 = 66;



							//							$this->hc_functions->print_select(12,$order_id,'1'); //Heel Cup Depth (mm) ?>

							<div class="error" style="clear:left"><?php echo $err;?></div>



                        </div> */?>







							<div class="ftitle">

								<?php $this->hc_functions->print_select(13,$order_id,'','class="shortbox" onchange="mods_change(13);" style="float:left;"'); //Flange Type ?>



								<div class="shortright">

									<?php $this->hc_functions->print_checkbox(64,$order_id,'','',1); //1=notitle ?>

								</div>

							</div>





							<!--



                                                <div class="ftitle">

                                                        <div id="div_13" style="float:left"></div>




                                                    <?php //$this->hc_functions->print_select(13,$order_id,'','style="width:200px;"'); //Flange Type ?>

                                                    <div class="shortright"><p style="margin-top:3px;">&nbsp;</p>

                                                  <?php $this->hc_functions->print_checkbox(64,$order_id,'','',1); //Flange checkboxes ?></div>



                                                </div> -->



							<div class="ftitle">

								<?php $this->hc_functions->print_checkbox(46,$order_id); //Fascial Accomodation ?>

							</div>

							<div class="ftitle">

								<?php $this->hc_functions->print_checkbox(47,$order_id); //Navicular/Cunliform Sweet Spot ?>

							</div>

							<div class="ftitle">

								<?php echo $error_ray_hallux;?>

								<?php $this->hc_functions->print_checkbox(48,$order_id); //1st Ray Cutout ?>

							</div>





						</div>

						<div class="divright">



							<div class="ftitle">

								<label>Modification Notes:</label>

								<textarea name="order_values_mods_notes" rows="5"><?php echo $order_values_mods_notes; ?></textarea>

								<div class="error">&nbsp;</div>

							</div>

						</div>

					</fieldset>

				</div>

				<div id="tab_additions" class="tabs">

					<h2>4. Please enter any additions.</h2>

					<div class="error">

						<?php if (isset($error_top_4)) echo $error_top_4;?>

					</div>

					<fieldset>

						<div class="divleft">

							<div class="ftitle">

								<div style="float: right">

									<p>

										<label>Left</label>

									</p>

									<p>

										<label>Right</label>

									</p>

								</div>

							</div>

							<div class="hline"></div>

							<div class="ftitle">

								<?php echo $error_heel_horse;?>

								<?php $this->hc_functions->print_select(17,$order_id); //Heel Plugs (mm) ?>



							</div>

							<div class="ftitle">

								<?php $this->hc_functions->print_select(66,$order_id); //Heel Plug hole ?>

							</div>

							<div class="hline"></div>



							<div class="ftitle">

								<?php echo $error_bar_pads;?>

								<?php $this->hc_functions->print_select(18,$order_id,'',' onchange="do_met_pad();"'); //Met Pad (size)?>

							</div>

							<div class="ftitle" id="div_met_pad">

								<?php $this->hc_functions->print_select(19,$order_id); //Met Pad (thickness)?>

								<?php if ($error_mat_pad_thickness) { ?>

								<div class="error" style="clear: both">

									<?php echo $error_mat_pad_thickness; ?>

								</div>

								<?php } ?>

							</div>

							<div class="ftitle" id="div_met_pad_material">

								<?php $this->hc_functions->print_checkbox(88,$order_id); //Met Pad Material?>

							</div>



							<div class="hline"></div>

							<div class="ftitle">

								<?php echo $error_bar_pads;?>

								<?php $this->hc_functions->print_checkbox(15,$order_id); //Met Bar?>

							</div>

							<div class="hline"></div>

							<div class="ftitle">

								<?php echo $error_m_excl;?>

								<?php $this->hc_functions->print_checkbox(39,$order_id); //Morton's Extension (EVA)?>

							</div>

							<div class="hline"></div>

							<div class="ftitle">

								<?php echo $error_m_excl;?>

								<?php $this->hc_functions->print_checkbox(38,$order_id); //Reverse Morton's Extension?>

							</div>

							<div class="hline"></div>

							<div class="ftitle">

								<?php $this->hc_functions->print_checkbox(41,$order_id); //2-5 Bar (EVA)?>

							</div>

							<div class="hline"></div>

							<div class="ftitle">

								<?php echo $error_ray_hallux;?>

								<?php echo $error_m_excl;?>

								<?php $this->hc_functions->print_checkbox(49,$order_id,'','onclick="halux_splint()";'); //Hallux Rigidus Splint Ext?>

								<div class="information-icon"

									onmouseover="return descriptionOver();"

									onmouseout="return descriptionOut();">

									<img src="../admin/images/info.png" width="16px" height="16px">

								</div>

							</div>



							<div id="description" style="display: none;"

								onmouseover="return descriptionOver();"

								onmouseout="return descriptionOut();">

								<label>If your orthotic type is selected as 'Custom </label> <label>Direct

									Milled', this option is disabled.</label> <span>WARNING:</span>

								<label>If you change your orthotic </label> <label>type on Step

									2, any work you have done</label> <label>will be lost.</label>

							</div>





							<div class="ftitle">

								<?php $this->hc_functions->print_checkbox(67,$order_id); //Hallux Rigidus Splint Reinforcement?>

							</div>

							<div class="hline"></div>

							<div class="ftitle">

								<?php $this->hc_functions->print_checkbox(42,$order_id); //Cuboid Pad?>

							</div>
							<div class="hline"></div>

							<div class="ftitle">

								<?php $this->hc_functions->print_checkbox(91,$order_id); //scaphoid pad?>

							</div>
							<div class="hline"></div>

							<div class="ftitle">

								<?php echo $error_m_excl;?>

								<?php $this->hc_functions->print_checkbox(43,$order_id); //Kinetic Wedge?>

							</div>

						</div>

						<div class="divright">

							<div class="ftitle">

								<div style="float: right">

									<p>

										<label>Left</label>

									</p>

									<p>

										<label>Right</label>

									</p>

								</div>

							</div>

							<div class="hline"></div>

							<div class="ftitle">

								<?php echo $error_arc_fills;?>

								<?php $this->hc_functions->print_checkbox(44,$order_id); //Lateral Arch Fill ?>

							</div>

							<div class="hline"></div>

							<div class="ftitle">

								<?php echo $error_heel_horse;?>

								<?php $this->hc_functions->print_checkbox(45,$order_id); //Horseshoe Spur?>

							</div>

							<div class="hline"></div>

							<div class="ftitle">

								<?php echo $error_bar_pads;?>

								<?php $this->hc_functions->print_select(20,$order_id); //Neuroma Web Space?>

							</div>

							<div class="hline"></div>

							<div class="ftitle">

								<?php echo $error_m_excl;?>

								<label style="float: left">Met Cutouts:<br /> <span

									style="font-size: 10px;">(Check all that apply)</span>

								</label>

								<div style="float: right">

									<?php

							$metcutout = $this->db->query("SELECT * FROM lookup_table WHERE lookup_table_lookup_table_types_id = 50 ORDER BY lookup_table_sort");

									$i = 0;
									foreach($metcutout->rows as $metcut) {


									 if (! is_array($met_cutoutsorder_values_left_50)) $order_valuesleft_50 = explode(",",$met_cutoutsorder_values_left_50);

									 if (! is_array($met_cutoutsorder_values_right_50)) $order_valuesright_50 = explode(",",$met_cutoutsorder_values_right_50);

									?>

									<p style="display:none;">
										<label>
											<input type="checkbox" name="order_values_left_50[]" value="<?php echo $metcut['lookup_table_title']?>" id="order_values_left_50_<?php echo $i; ?>" "<?php  echo (in_array($metcut['lookup_table_title'],$order_valuesleft_50)) ? ' checked="checked"' : " "; ?>" onclick="do_check_both(this.id,'50_<?php echo $i; ?>')"/>
										</label>
										<label>
											<input type="checkbox" name="order_values_right_50[]" value="<?php echo $metcut['lookup_table_title']?>" id="order_values_right_50_<?php echo $i; ?>" "<?php  echo (in_array($metcut['lookup_table_title'],$order_valuesright_50)) ? ' checked="checked"' : " "; ?>" onclick="do_check_both(this.id,'50_<?php echo $i; ?>')"/>
										</label>
									</p>

									<?php

                                echo '<p><label>
								<input id="order_values_left_50_'.$i.'" type="checkbox" name="order_values_left_50[]" value="'.$metcut['lookup_table_title'] . '"  onclick="do_check_both(this.id,\'50_'.$i.'\')"';


				echo (in_array($metcut['lookup_table_title'],$order_valuesleft_50)) ? ' checked="checked"' : " ";
                                echo '>'.$metcut['lookup_table_title'].'</label></p>';


                                echo '<p><label>
								<input id="order_values_right_50_'.$i.'" type="checkbox" name="order_values_right_50[]" value="'.$metcut['lookup_table_title'].'" onclick="do_check_both(this.id,\'50_'.$i.'\')"';

			  echo (in_array($metcut['lookup_table_title'],$order_valuesright_50)) ? ' checked="checked"' : " ";
                                echo '>'.$metcut['lookup_table_title'].'</label></p>';

                                echo '<div style="clear:both"></div>';

                                $i++;

                                }

                                ?>

								</div>
							 <div style="clear:both"></div>
                <div>
                <div style="float:right; margin-right:12%;">
                <?php if($fill_with_poron==1) { ?>
                	<input type="checkbox" name="fill_with_poron" checked="checked" value="1" />
                 <?php } else {?>
                 	<input type="checkbox" name="fill_with_poron" value="1" />
                 <?php } ?>
                <label style="margin-top:-5px; float:right; padding-left:5px;">Fill with Poron</label></div>

							</div>

							<div class="hline"></div>

							<div class="ftitle">

								<?php $this->hc_functions->print_select(52,$order_id); //Heel Lift thickness?>

							</div>

							<div class="hline"></div>

							<div class="ftitle">

								<label>Addition Notes:</label>

								<textarea name="order_values_addition_notes" rows="5"><?php echo $order_values_addition_notes; ?></textarea>

								<div class="error">&nbsp;</div>

							</div>

						</div>

					</fieldset>

				</div>



				<div id="tab_posting" class="tabs">

					<h2>5. Please enter posting information.</h2>

					<fieldset>

						<div class="divleft">



							<div class="ftitle">

								<?php



								if (!isset($order_values_55) || !$order_values_55)  $order_values_55 = '238';



								$this->hc_functions->print_radio(55,$order_id,'','onclick="postings_disable();"',$notitle=1,'',$order_values_55); //postings



								?>

								<em style="margin-top: -20px; display: block; color: #00549F">*

									Please select this option to activate the options below</em>

							</div>

							<div class="disabled">

								<div class="ftitle">

									<h2>Rear Foot</h2>

								</div>

								<div class="hline"></div>



								<div class="ftitle">

									<label>Post Type:</label>

									<?php $this->hc_functions->print_select(21,$order_id,'','style="float:left; width:115px;"',1); //Post Type (mm) ?>

								</div>

								<div class="div_22">

									<div class="ftitle">

										<label>Post Material:</label>

										<div class="div_22_112" style="float: left; width: 115px;">

											<label style="float: left; width: 115px;"> <input

												id="order_values_22_112" type="radio" value="112"

												name="order_values_22" style="width: auto"

								<?php if($order_values_22 == '112'){ echo 'checked="checked"';}?>>

												Direct Milled

											</label>

										</div>

										<div class="div_22_113" style="float: left; width: 115px;">

											<label style="float: left; width: 115px;"> <input

												id="order_values_22_113" type="radio" value="113"

												name="order_values_22" style="width: auto"

								<?php if ($order_values_22 == '113') echo ' checked="checked"';?>>

												EVA

											</label>

										</div>

										<div class="error">&nbsp;</div>

										<?php // $this->hc_functions->print_select(22,$order_id,'','style="float:left; width:115px;"',1); //Post material ?>

									</div>

								</div>

								<div id="rf_deg_dir">

									<div class="ftitle">

										<div style="float: right">

											<p>

												<label>Left</label>

											</p>

											<p>

												<label>Right</label>

											</p>

										</div>

									</div>

									<div class="hline"></div>

									<div class="ftitle">

										<?php $this->hc_functions->print_select(56,$order_id); //Degrees ?>

									</div>

									<div class="ftitle">

										<?php $this->hc_functions->print_select(23,$order_id); //Direction ?>

									</div>

								</div>

								<div class="hline"></div>



								<div class="ftitle">

									<br />

									<h2>Fore Foot</h2>

								</div>

								<div class="hline"></div>



								<div class="ftitle">



									<label>Post Type:</label>

									<?php $this->hc_functions->print_select(69,$order_id,'','style="float:left; width:115px;"',1); //Post Type (mm) ?>

								</div>

								<div class="div_70_71">

									<div class="ftitle div_70">

										<label>Post Material:</label>

										<div class="div_70_327" style="float: left; width: 115px;">

											<label style="float: left; width: 115px;"> <input

												id="order_values_70_327" type="radio" value="327"

												name="order_values_70" style="width: auto"

								<?php if ($order_values_70 == '327') echo ' checked="checked"';?>>

												Direct Milled

											</label>

										</div>

										<div class="div_70_328" style="float: left; width: 115px;">

											<label style="float: left; width: 115px;"> <input

												id="order_values_70_328" type="radio" value="328"

												name="order_values_70" style="width: auto"

								<?php if ($order_values_70 == '328') echo ' checked="checked"';?>>

												EVA

											</label>

										</div>

										<?php //$this->hc_functions->print_select(70,$order_id,'','style="float:left; width:115px;"',1); //Post material ?>

									</div>

									<div class="ftitle">

										<div class="div_71">

											<label>Post Length:</label>

											<?php $this->hc_functions->print_select(71,$order_id,'','style="float:left; width:115px;"',1); //Post material ?>

										</div>

									</div>

								</div>

								<div id="ff_deg_dir">

									<div class="ftitle">

										<div style="float: right">

											<p>

												<label>Left</label>

											</p>

											<p>

												<label>Right</label>

											</p>

										</div>

									</div>

									<div class="hline"></div>

									<div class="ftitle">

										<?php $this->hc_functions->print_select(72,$order_id,'','onclick="ff_degrees()"'); //Degrees ?>

									</div>

									<div class="ftitle">

										<?php $this->hc_functions->print_select(73,$order_id); //Direction ?>

									</div>

								</div>



							</div>

						</div>

						<div class="divright">

							<div class="disabled">

								<div class="ftitle">



									<div style="float: right">

										<p>

											<label>Left</label>

										</p>

										<p>

											<label>Right</label>

										</p>

									</div>

								</div>

								<div class="hline"></div>

								<div class="ftitle">

									<?php $this->hc_functions->print_select(57,$order_id); //Skive (mm) ?>

								</div>

								<div class="hline"></div>

								<div class="ftitle">

									<?php $this->hc_functions->print_select(65,$order_id); //Inverted ?>

								</div>

							</div>

							<div class="hline"></div>





							<div class="ftitle">

								<?php $this->hc_functions->print_select(89,$order_id,'','onchange="do_wedge()"'); //wedge ?>

							</div>



							<div class="ftitle" id="wedge_values">

								<?php $this->hc_functions->print_select(90,$order_id); //wedge values ?>

							</div>

							<div class="error error_wedge">

								<?php echo $error_order_values_wedge; ?>

							</div>





							<div class="ftitle">

								<label>Posting Notes:</label>

								<textarea name="order_values_posting_notes" rows="10"><?php echo $order_values_posting_notes; ?></textarea>

								<div class="error">&nbsp;</div>

							</div>



						</div>

					</fieldset>

				</div>



				<div id="tab_covers" class="tabs">

					<div class="error">

						<h3>

							<?php echo $error_order_values_length; ?>

						</h3>

						&nbsp;

					</div>

					<h2>6. Please enter your cover information.</h2>



					<div style="position: absolute; display: none; color: red;"

						id="cover_wait">

						<img src="view/image/loading.gif" />Please Wait...

					</div>

					<fieldset>





						<div class="cover">

							<div class="covers">

								<label>&nbsp;</label>

							</div>

							<div class="covers">

								<label>Material</label>

							</div>

							<div class="covers">

								<label>Colour</label>

							</div>

							<div class="covers">

								<label>Thickness (mm)</label>

							</div>

							<div class="covers">

								<label>Length</label>

							</div>

						</div>

						<div class="hline"></div>



						<div class="cover">

							<div class="covers">

								<label>Top Cover:</label>

							</div>

							<div class="covers">

								<?php $this->hc_functions->print_select(24,$order_id,'','onchange="do_covers(24,this.value,24)"',1); //top cover - material ?>

							</div>

							<div class="covers">

								<?php $this->hc_functions->print_select(26,$order_id,'','onclick="do_covers(26,this.value,24)"',1);  ?>
								<!-- <?php $this->hc_functions->print_select(26,$order_id,'','onclick="do_covers(26,this.value,26)"',1);  ?> -->
							</div>

							<div class="covers">

								<?php $this->hc_functions->print_select(25,$order_id,'','',1);  ?>

							</div>

							<div class="covers">

								<?php $this->hc_functions->print_select(27,$order_id,'','onchange="do_covers(27,this.value,27)"',1);  ?>

							</div>

						</div>

						<div class="hline"></div>

						<div class="cover">

							<div class="covers">

								<label>Mid Layer:</label>

							</div>



							<div class="covers">

								<?php $this->hc_functions->print_select(28,$order_id,'','onchange="do_covers(28,this.value,28)"',1);  ?>

							</div>

							<div class="covers">&nbsp;</div>

							<div class="covers">

								<?php $this->hc_functions->print_select(29,$order_id,'','onclick="do_covers(29,this.value,28)"',1);  ?>

							</div>

							<div class="covers">

								<?php $this->hc_functions->print_select(30,$order_id,'','',166);  ?>

							</div>

						</div>

						<div class="hline"></div>

						<div class="cover">

							<div class="covers">

								<label>Fore Foot Extension:</label>

							</div>

							<div class="covers">

								<?php $this->hc_functions->print_select(31,$order_id,'','onchange="do_covers(31,this.value,31)"',1);  ?>

							</div>

							<div class="covers">&nbsp;</div>

							<div class="covers">

								<?php $this->hc_functions->print_select(32,$order_id,'','onclick="do_covers(32,this.value,31)"',1);  ?>

							</div>

							<div class="covers">

								<?php $this->hc_functions->print_select(33,$order_id,'','',1);  ?>

							</div>

						</div>

						<div class="hline"></div>

						<div class="cover">

							<div class="covers">

								<label>Bottom Cover:</label>

							</div>

							<div class="covers">

								<?php $this->hc_functions->print_select(34,$order_id,'','onChange="do_covers(34,this.value,34)"',1);  ?>

							</div>

							<div class="covers">

								<?php $this->hc_functions->print_select(63,$order_id,'','onclick="do_covers(63,this.value,34)"',1);  ?>

							</div>

							<div class="covers">

								<?php $this->hc_functions->print_select(62,$order_id,'','onclick="do_covers(62,this.value,34)"',1);  ?>

							</div>





							<div class="covers">

								<?php $this->hc_functions->print_select(35,$order_id,'','onclick="do_covers(35,this.value,35)"',1);  ?>

							</div>









						</div>

						<div class="hline"></div>

						<div class="ftitle" style="width: 100%">

							<label>Cover Info Notes:</label>

							<textarea name="order_values_cover_notes" rows="10" style="width: 95%"><?php echo $order_values_cover_notes; ?></textarea>

							<div class="error">&nbsp;</div>

						</div>

					</fieldset>

				</div>











				<div id="tab_delivery" class="tabs">

					<h2>7. Please enter your delivery information.</h2>

					<fieldset>

						<div class="divleft">

							<div class="ftitle">

								<label>Expected Shipping  Date:</label>

								<?php

								date_default_timezone_set('America/Toronto');

								$time = date("H");



								if ($time < "12")

								{

									$day = date("Y-m-d",strtotime("+4 weekday"));

								}

								else

								{

									$day = date("Y-m-d",strtotime("+5 weekday"));

								}



								if (date("N",strtotime($day)) == 6 || date("N",strtotime($day)) == 7) {

                            $day = date("Y-m-d",strtotime($day ." +" . (6 - date("N",strtotime($day))) . "weekday"));

                            }

                            if (!isset($order_id) || !$order_id) {

                            $order_deliverydate = $day;

                            $order_originaldelivery = $day;

                            $calculated_deliverydate = $day;

                            } else {

                            $calculated_deliverydate = $order_deliverydate;

							}

							?>



								<input type="text" name="order_deliverydate"

									id="order_deliverydate"

									value="<?php if ($error_order_rush_date)echo $order_deliverydate; else  echo $this->hc_functions->ch_date($order_deliverydate);?>"

									class="datex" size="12" style="width: 130px;" /> &nbsp;

								<?php if  ($order_deliverydate !=$order_originaldelivery && $order_deliverydate > '1' && $order_originaldelivery > '1')  echo '<span style="color:#00549F">Originally: ' . $this->hc_functions->ch_date($order_originaldelivery). '</span>';?>



								<div class="error">&nbsp;</div>

							</div>

							<?php // echo $this->hc_functions->ch_date($order_date_needed); ?>

							<div class="ftitle">

								<label>Override Shipping Date:</label>

								<?php $this->hc_functions->print_select(75,$order_id,'','style="padding-left:0!important" onclick="f_date_needed_by()" '); //Rush ?>

								<div style="float: left; margin-left: 10px;">&nbsp;</div>

								<input type="text" name="order_date_needed"

									id="order_date_needed"

									value="<?php if ($error_order_rush_date) echo $order_date_needed; else  echo $this->hc_functions->ch_date($order_date_needed);?>"

									class="datex" size="12" style="width: 130px; float: left"

									onchange="rushDateCheck();" />

								<div class="error" style="clear: both">

									<?php if (isset($error_order_values_rush)) echo $error_order_values_rush;?>

								</div>

								<?php

								/*

								Author: Mark Arsenault

								Date: Jan 29, 2014



								Fixed Rush Order bug. Waiting for customer feedback.

								*/

								?>

								<div class="error" style="clear: both">

									<?php if ($error_order_rush_date === true){



										// initailising the alert message variable to null

										$alert_message='';



										// preparing the alert message

										$alert_message .='<script type="text/javascript">';

										$alert_message .='alert("The Rush date is invalid \nPlease select another one");';

										$alert_message .= '</script>';



										echo $error_order_rush_date;



										// displaying the alert to the user

										echo $alert_message;



									}

									?>

								</div>

							</div>



							<div class="ftitle">

								<label>Shipping Date (Lab Use Only):</label> <input type="text"

									name="order_shipping_date"

									value="<?php echo $this->hc_functions->ch_date($order_shipping_date);?>"

									class="date" style="width: 130px" />

								<div class="error">&nbsp;</div>

							</div>



							<div class="ftitle">

								<?php $this->hc_functions->print_select(83,$order_id); //Casting Method ?>



								<?php /*?>                            <label>Shipping Method:</label>



									<input type="text" name="order_shipping_method" value="<?php echo $order_shipping_method;?>" />

									<div class="error">&nbsp;</div>

							<?php */?>

							</div>



							<div class="ftitle">

								<label>Shipping Number:</label> <input type="text"

									name="order_shipping_number"

									value="<?php echo $order_shipping_number;?>" readonly />

								<div class="order-delivery-shipping-comment">This can be updated

									only in tracking screen</div>

								<div class="error">&nbsp;</div>

							</div>



							<? if($order_packing_slip_id != null): ?>

							<div class="ftitle">

								<label>Most Recent Packing List Included In:</label> <a

									style="color: #00549F; font-size: 11px; font-weight: normal;"

									target="_blank"

									href="index.php?route=sale/order/viewpackingslip&packing_slip_ids=<?=$order_packing_slip_id?>&view=1&token=<?=$token;?>">View

									Packing List #<?php echo $order_packing_slip_id;?>

								</a>

							</div>

							<? endif ?>

						</div>

						<div class="divright">

							<div class="ftitle">

								<label>Ship To:</label>

							</div>

							<div class="hline"></div>

							<div class="ftitle">

								<?php



								$sql = "SELECT *,REPLACE(REPLACE(REPLACE(clinic_address_address, CHAR(10), ',  '), CHAR(13), ''), CHAR(9), '') AS lineval FROM clinic LEFT JOIN clinic_address ca ON clinic_shipping_address_id = ca.clinic_address_id WHERE clinic_id = '$patient_clinic_id'";



								$myclinic = $this->db->query($sql);

								if ($myclinic->num_rows) {

                            $myemail = $myclinic->row['clinic_contact_email'];

                            $mycontact = $myclinic->row['clinic_contact'];

                            $myaddress =  $myclinic->row['clinic_address_address'];

                            $myid = $myclinic->row['lineval'];

                            } else {

                            $myemail = '';

                            $mycontact = '';

                            $myaddress =  '';

                            $myid = 0;

                            }

                            ?>

								<label>Contact Name:</label> <input type="text"

									name="order_contact_name"

									value="<?php echo $order_contact_name ? $order_contact_name : $mycontact;?>" />

								<div class="error">&nbsp;</div>

							</div>

							<div class="ftitle">

								<label>Contact Email:</label> <input type="text"

									name="order_values_contact_email"

									value="<?php echo $order_values_contact_email ? $order_values_contact_email : $myemail;?>" />

								<div class="error">&nbsp;</div>

							</div>







							<div class="ftitle">



								<label>Shipping Address:</label>

								<?php $this->hc_functions->print_select(86,$order_id,'','style="padding-left:0!important" onClick="use_patient_address()" '); ?>



								<?php



								$clinic_address = '';

								$address_list = '';

								$add_lists = '';

								if( $patient_clinic_id != ''){





		                    		$address_query = $this->db->query("SELECT * FROM clinic_address  WHERE clinic_address_clinic_id = '".$patient_clinic_id."'ORDER BY clinic_address_sort");



		                    		$add_lists = $address_query->rows;





                    				foreach ($add_lists as $add_list) {



										if ($add_list['clinic_address_sort'] == '4' ){



												$clinic_address = $add_list['clinic_address_address'];

											}



                    					if ($add_list['clinic_address_sort'] == '6' ){



                    							$clinic_address = $add_list['clinic_address_address'];



                    						}



                    				}

                    			}

                    				?>



								<?php
								$order_shipping_address = $order_shipping_address ? $order_shipping_address : $clinic_addresses;
								$address_clinic = $order_shipping_address ? $order_shipping_address : $clinic_address;
								$cnt= count($add_lists);
								?>



								<select id="clinic_addresses" name="clinic_addresses"

									onchange="put_address_in_box(this.value)">

									<option value="">Please select...</option>

									<?php foreach ($add_lists as $add_list) {?>

									<option value="<?php echo $add_list['clinic_address_address']; ?>"

										<?php if($add_list['clinic_address_address'] ==  $address_clinic || $cnt==1) echo 'selected' ?>>
										<?php echo $add_list['clinic_address_address']; ?>
									</option>

									<?php } ?>



								</select>

							<hr/>
								<!--<textarea name="order_shipping_address"	id="order_shipping_address" rows="4"><?php echo $address_clinic; ?></textarea>-->

								<?php
									$address_array = explode(',',$address_clinic);

									if((isset($error_shipping_address) && $error_shipping_address != '') || (isset($error_shipping_address_street) && $error_shipping_address_street != '') ||  (isset($error_shipping_address_city) && $error_shipping_address_city != '') || (isset($error_shipping_address_province) && $error_shipping_address_province != '') || (isset($error_shipping_address_postal) && $error_shipping_address_postal != '')){

									}else{

										$format_address = isset($address_array[0]) ? $address_array[0] :'';
										$format_street = isset($address_array[1]) ? $address_array[1] :'';
										$format_city = isset($address_array[2]) ? $address_array[2] :'';
										$format_province = isset($address_array[3]) ? $address_array[3] :'';
										$format_postal_code = isset($address_array[4]) ? $address_array[4] :'';
									}
									
								?>
								<input type="text" placeholder="Address" name="format_address" id="format_address" value="<?=trim($format_address)?>"/> <br/>
								<div class="error">&nbsp;

								<?php if (isset($error_shipping_address) && $error_shipping_address != ''){



									echo  trim($error_shipping_address);



								 }?>

								</div>
								<input type="text" placeholder="Street" name="format_street" id="format_street"  value="<?=trim($format_street)?>"/> <br/>
								<div class="error">&nbsp;

								<?php if (isset($error_shipping_address_street) && $error_shipping_address_street != ''){



									echo  trim($error_shipping_address_street);



								 }?>

								</div>
								<input type="text" placeholder="City"  name="format_city"  id="format_city" value="<?=trim($format_city)?>"/> <br/>
								<div class="error">&nbsp;

								<?php if (isset($error_shipping_address_city) && $error_shipping_address_city != ''){



									echo  trim($error_shipping_address_city);



								 }?>

								</div>
								<input type="text" placeholder="Province"  name="format_province"  id="format_province" value="<?=trim($format_province)?>"/> <br/>
								<div class="error">&nbsp;

								<?php if (isset($error_shipping_address_province) && $error_shipping_address_province != ''){



									echo  trim($error_shipping_address_province);



								 }?>

								</div>
								<input type="text" placeholder="postal code" name="format_postal_code"  id="format_postal_code" value="<?=trim($format_postal_code)?>">

								<div class="error">&nbsp;

								<?php if (isset($error_shipping_address_postal) && $error_shipping_address_postal != ''){



									echo  trim($error_shipping_address_postal);



								 }?>

								</div>

							</div>

							<div class="ftitle">

								<label>Delivery Notes:</label>

								<textarea name="order_values_delivery_notes" rows="6"><?php echo $order_values_delivery_notes;?></textarea>

								<div class="error">&nbsp;</div>

							</div>

						</div>

					</fieldset>

				</div>

				<div id="tab_general" class="tabs">

					<h2>8. Please enter general order information.</h2>

					<fieldset>

						<div class="divleft">

							<div class="ftitle">

								<label><span class="required">*</span> Quantity:</label> <input

									type="text" name="order_quantity"

									value="<?php echo $order_quantity ? $order_quantity : 1;?>"

									style="width: 100px;" />

								<div class="error">

									<?php if ($error_order_quantity) { ?>

									<?php echo $error_order_quantity; ?>

									<?php } ?>

									&nbsp;

								</div>

							</div>

							<div class="ftitle">

								<label>Foot:</label>



								<?php

                            if (!isset($order_values_58) || !$order_values_58)  $order_values_58 = '248'; ?>



								<?php $this->hc_functions->print_radio(58,$order_id,'','style="float:left; margin-left:20px;"',1,'',$order_values_58); //postings ?>



							</div>



							<div style="clear: both; height: 10px;"></div>

							<div class="ftitle">

								<?php $this->hc_functions->print_select(8,$order_id); //Casting Method ?>

							</div>



							<div class="ftitle">

								<label>Repeat Order:</label>

								<?php

                            if (!isset($order_values_59) || !$order_values_59)  $order_values_59 = '251'; ?>



								<?php $this->hc_functions->print_radio(59,$order_id,'','style="float:left; margin-left:20px;"',1,'',$order_values_59); //postings ?>



							</div>

						</div>

						<div class="divright">



							<div class="ftitle">

								<label>General Order Notes:</label>

								<textarea name="order_values_general_notes" rows="10"><?php echo $order_values_general_notes; ?></textarea>

								<div class="error">&nbsp;</div>

							</div>

						</div>

					</fieldset>

				</div>

				<div id="tab_files" class="tabs">

					<h2>9. Please attach any files needed for this order.</h2>





					<fieldset>

						<div class="divleft" style="width: 100%">

									<div class="error" style="display: inline; font-weight: bold;">

										<?php echo $error_order_values_files_notes;?>

									</div>

							<div

								style="color: red; font-weight: bold; position: absolute; margin: 4px 0 0 130px;"

								id="file_warn"></div>



							<div class="ftitle" style="width: 100%">

								<?php $this->hc_functions->print_radio(60,$order_id,'','onclick="show_hide_file_frame();"',1); //Use Scan on File ?>

								<?php $_SESSION['s_ug'] = $this->user->stepFull($order_status_id);

                            if ($_SESSION['s_ug']) { ?>





								<iframe

									src="hc_scripts/plupload/index.php?id=<?php  echo $order_id;?>"

									id="file_frame" allowtransparency="1"></iframe>

								<?php  } else {

									include "hc_scripts/bring_imgs.php";

								}



								?>



							</div>



							<div class="ftitle">

								<?php $this->hc_functions->print_select(74,$order_id,'','style="padding-left:0 !important"'); //Casting Method ?>

							</div>





							<div class="ftitle" style="width: 100%">

								<label>Attachment Notes:

									<div class="error" style="display: inline;">

										<?php echo $error_order_values_files_notes;?>

									</div>

								</label>

								<textarea name="order_values_files_notes" rows="10" style="width: 100%"><?php echo $order_values_files_notes; ?></textarea>

								<div class="error">&nbsp;</div>

							</div>

						</div>

					</fieldset>

				</div>



			</div>

			<div id="tab_status" class="tabs">

				<h2>10. Order Status.</h2>

				<fieldset>

					<div class="divleft" style="width: 100%">



						<div class="ftitle" style="width: 100%">



							<table class="status">

								<tr>

									<th>Date</th>

									<th>Status</th>

									<th>By</th>

									<th>Notes</th>

								</tr>

								<?php

								$s = "SELECT * FROM order_history LEFT JOIN order_status ON order_status_id = order_history_order_status_id LEFT JOIN `user` ON order_history_user_id = user_id WHERE order_history_order_id = '$order_id' ORDER BY order_history_date";



								$histories = $this->db->query($s);



								if ($histories->num_rows) foreach ($histories->rows as $history) {

                            ?>

								<tr>

									<td nowrap="nowrap"><?php echo $history['order_history_date'];?>

									</td>

									<td nowrap="nowrap"><?php echo $history['order_status_name'];?>

									</td>

									<td nowrap="nowrap"><?php echo $history['user_firstname']. ' '. $history['user_lastname'];?>

									</td>

									<td><?php echo $history['order_history_comment'];?></td>

								</tr>

								<?php

                            }

                            ?>

							</table>

							<br /> <label>Order Status Notes: <span id="order_history_error"

								style="color: red;"></span>

							</label>

							<textarea name="order_history_comment" style="width: 95%;" rows="5" id="order_history_comment"<?php	if (!$this->user->stepDisplay($order_status_id) ) { echo " disabled='disabled'"; }?>></textarea>

						</div>

					</div>



				</fieldset>

			</div>

			<?php if ($user_group_string == '1' && $order_id != ''){ ?>

			<div id="tab_pricing" class="tabs">

				<h2>11. Pricing.</h2>

				<div class="main-bot"></div>

				<br />



				<div class="header-fields-div">

					<label class="heading-labels"><strong>Clinic :</strong> </label> <label

						class="heading-value-labels"><?php if(isset($clinic_name)){

							echo $clinic_name;

						}?> </label>

				</div>

				<div class="header-fields-div">

					<label class="heading-labels"><strong>Order # :</strong> </label> <label

						class="heading-value-labels"><?php echo $order_id; ?> </label>

				</div>

				<div class="header-fields-div">

					<label class="heading-labels"><strong>Rush :</strong> </label> <label

						class="heading-value-labels"><?php if($order_values_rush_75 !='') {

							echo 'Yes';

						} else{ echo 'No';

}?> </label>

				</div>

				<div class="header-fields-div">

					<label class="heading-labels"><strong>Foot :</strong> </label> <label

						class="heading-value-labels"><?php if ($order_values_58 == '248') {

							echo 'Both';

						} else if ($order_values_58 == '249'){

echo 'Left Only';

} else{ echo 'Right Only';

}?> </label>

				</div>
				<div class="header-fields-div">
        <label class="heading-labels"><strong>Device Category :</strong> </label>
        <label class="heading-value-labels">
          <?php
           $line_name = $this->db->query("SELECT lookup_table_title FROM lookup_table WHERE lookup_table_text LIKE '%$orthotic_id%'");
           $category_name = $line_name->row ;

          if(is_array($category_name)){
				echo $category_name['lookup_table_title'];
			}?>
        </label>
      </div>
				<div class="header-fields-div">

					<label class="heading-labels"><strong>Orthotic type :</strong> </label>

					<label class="heading-value-labels"><?php if(isset($orthotic_name)){

						echo $orthotic_name;

					}?> </label>

				</div>

				<div class="header-fields-div">

					<label class="heading-labels"><strong>Order quantity :</strong> </label>

					<label class="heading-value-labels"><?php echo $order_quantity; ?>

					</label>

				</div>

				<div class="header-fields-div">

					<label class="heading-labels"><strong>Repeat order :</strong> </label>

					<label class="heading-value-labels"><?php  if($order_values_59 == '252') {

						echo 'Yes';

					} else{ echo 'No';

} ?> </label>

				</div>



				<fieldset>

					<div class="divleft" style="width: 100%">

						<table id="defaults" class="list">

							<thead>

								<tr>

									<td class="left" width="300px;">Item Name:</td>

									<td class="left" width="150px;">Unit Price:</td>

									<td class="left" width="150px;">Item Price:</td>

									<td class="left" width="150px;">Tax code:</td>

									<td class="left" width="150px;">Tax rate:</td>

									<td class="left" width="150px;">Price including tax</td>

								</tr>

							</thead>

							<tbody>

								<tr>

									<td class="left" width="300px;">Base Price Pairs</td>

									<td class="left" width="150px;"><?php if (isset($pricing_values[0]['tax_rate'])){?>

										<label>$<?php echo $pricing_values[0]['clinic_item_price']; //sprintf('%0.2f', $base_price/$order_quantity); ?>

									</label> <?php }else{ echo '---';

									}?></td>

									<td class="left" width="150px;"><?php if(isset($base_price)) { ?>

										<label> $ <?php echo $base_price; ?>

									</label> <?php } else { echo '---';

									}?></td>

									<td class="left" width="150px;"><label><?php if(isset($pricing_values[0]['tax_item'])) echo $pricing_values[0]['tax_item']; ?>

									</label></td>

									<td class="left" width="150px;"><label><?php if(isset($pricing_values[0]['tax_rate'])) echo $pricing_values[0]['tax_rate'].'%'; ?>

									</label></td>

									<td class="left" width="150px;"><?php if(isset($base_price_tax)) { ?>

										<label> $ <?php echo $base_price_tax; ?>

									</label> <?php } else { echo '---';

									}?></td>

								</tr>



								<tr>

									<td class="left" width="300px;">Discount for Repeat Pairs</td>

									<td class="left" width="150px;"><?php if (isset($pricing_values[9]['clinic_item_price']) && isset($repeat_discount)){?>

										<label style="color: red;">$ (<?php echo $pricing_values[9]['clinic_item_price'].')'; ?>

									</label> <?php }else{ echo '---';

									}?>

									</td>

									<td class="left" width="150px;"><?php if(isset($repeat_discount)) { ?>

										<label style="color: red;">$ (<?php echo $repeat_discount.')'; ?>

									</label> <?php } else { echo '---';

									}?></td>

									<td class="left" width="150px;"><label><?php echo '---'; // echo $pricing_values_tax_rates[8]['tax_item'];?>



									</td>

									<td class="left" width="150px;"><label><?php echo '---'; // echo $pricing_values_tax_rates[8]['tax_rate'].'%';?>



									</td>

									<td class="left" width="150px;"><?php if(isset($repeat_discount)) { ?>

										<label style="color: red;">$ (<?php echo $repeat_discount.')'; ?>

									</label> <?php } else { echo '---';

									}?></td>

								</tr>



								<tr>

									<td class="left" width="300px;">Single Surcharge</td>

									<td class="left" width="150px;"><?php if (isset($pricing_values[10]['clinic_item_price']) && isset($total_single_surcharge)){?>

										<label>$<?php echo $pricing_values[10]['clinic_item_price']; ?>

									</label> <?php }else{ echo '---';

									}?>

									</td>

									<td class="left" width="150px;"><?php if(isset($total_single_surcharge)) { ?>

										<label> $ <?php echo $total_single_surcharge; ?>

									</label> <?php } else { echo '---';

									}?></td>

									<td class="left" width="150px;"><label><?php if(isset($pricing_values_tax_rates[9]['tax_item']))  echo $pricing_values_tax_rates[9]['tax_item'];?>



									</td>

									<td class="left" width="150px;"><label><?php  if(isset($pricing_values_tax_rates[9]['tax_rate']))  echo $pricing_values_tax_rates[9]['tax_rate'].'%';?>



									</td>

									<td class="left" width="150px;"><?php if(isset($total_single_surcharge)) { ?>

										<label> $ <?php echo $total_single_surcharge; ?>

									</label> <?php } else { echo '---';

									}?></td>

								</tr>



								<tr>

									<td class="left" width="300px;">Leather Surcharge</td>

									<td class="left" width="150px;"><?php if (isset($leather_cost) && isset($order_quantity)){?>

										<label>$<?php echo sprintf('%0.2f', $leather_cost/$order_quantity); ?>

									</label> <?php }else{ echo '---';

									}?></td>

									<td class="left" width="150px;"><?php if($order_values_24 == '123') {?>

										<label> $ <?php if(isset($leather_cost)) echo $leather_cost; ?>

									</label> <?php } else { echo '---';

									}?></td>

									<td class="left" width="150px;"><label><?php if(isset($pricing_values_tax_rates[0]['tax_item'])) echo $pricing_values_tax_rates[0]['tax_item'];?>



									</td>

									<td class="left" width="150px;"><label><?php if(isset($pricing_values_tax_rates[0]['tax_rate'])) echo $pricing_values_tax_rates[0]['tax_rate'].'%';?>



									</td>

									<td class="left" width="150px;"><?php if(isset($leather_cost_tax)) {?>

										<label> $ <?php echo $leather_cost_tax; ?>

									</label> <?php } else { echo '---';

									}?></td>

								</tr>



								<tr>

									<td class="left" width="300px;">Arch Fill Cost</td>

									<td class="left" width="150px;"><?php if (isset($arch_fill_cost) && isset($order_quantity)){?>

										<label>$<?php echo sprintf('%0.2f', $arch_fill_cost/$order_quantity); ?>

									</label> <?php }else{ echo '---';

									}?></td>

									<td class="left" width="150px;"><?php if(isset($arch_fill_cost)) { ?>

										<label> $ <?php  echo $arch_fill_cost; ?>

									</label></td>

									<?php } else { echo '---';

} ?>

									<td class="left" width="150px;"><label><?php if(isset($pricing_values_tax_rates[1]['tax_item'])) echo $pricing_values_tax_rates[1]['tax_item'];?>



									</td>

									<td class="left" width="150px;"><label><?php if(isset($pricing_values_tax_rates[1]['tax_rate'])) echo $pricing_values_tax_rates[1]['tax_rate'].'%';?>



									</td>

									<td class="left" width="150px;"><?php if(isset($arch_fill_tax)) { ?>

										<label> $ <?php echo $arch_fill_tax; ?>

									</label></td>

									<?php } else { echo '---';

} ?>

									</td>

								</tr>



								<tr>

									<td class="left" width="300px;">Mid Layer Cost</td>

									<td class="left" width="150px;"><?php if (isset($mid_layer_cost) && isset($order_quantity)){?>

										<label>$<?php echo sprintf('%0.2f', $mid_layer_cost/$order_quantity); ?>

									</label> <?php }else{ echo '---';

									}?></td>

									<td class="left" width="150px;"><?php if(isset($mid_layer_cost)) { ?>

										<label> $ <?php echo $mid_layer_cost; ?>

									</label></td>

									<?php } else { echo '---';

} ?>

									<td class="left" width="150px;"><label><?php if(isset($pricing_values_tax_rates[3]['tax_item'])) echo $pricing_values_tax_rates[3]['tax_item'];?>



									</td>

									<td class="left" width="150px;"><label><?php if(isset($pricing_values_tax_rates[3]['tax_rate'])) echo $pricing_values_tax_rates[3]['tax_rate'].'%';?>



									</td>

									<td class="left" width="150px;"><?php if(isset($mid_layer_tax)) { ?>

										<label> $ <?php echo $mid_layer_tax; ?>

									</label></td>

									<?php } else { echo '---';

} ?>

									</td>

								</tr>



								<tr>

									<td class="left" width="300px;">Additions - Heel Plug</td>

									<td class="left" width="150px;"><?php if (isset($add_heel_plug_cost) && isset($order_quantity)){?>

										<label>$<?php echo sprintf('%0.2f', $add_heel_plug_cost/$order_quantity); ?>

									</label> <?php }else{ echo '---';

									}?></td>

									<td class="left" width="150px;"><?php if(isset($add_heel_plug_cost)) { ?>

										<label> $ <?php  echo $add_heel_plug_cost; ?>

									</label></td>

									<?php } else { echo '---';

} ?>

									<td class="left" width="150px;"><label><?php if(isset($pricing_values_tax_rates[7]['tax_item']))  echo $pricing_values_tax_rates[7]['tax_item'];?>



									</td>

									<td class="left" width="150px;"><label><?php if(isset($pricing_values_tax_rates[7]['tax_rate'])) echo $pricing_values_tax_rates[7]['tax_rate'].'%';?>



									</td>

									<td class="left" width="150px;"><?php if(isset($add_heel_plug)) { ?>

										<label> $ <?php  echo $add_heel_plug; ?>

									</label></td>

									<?php } else { echo '---';

} ?>

									</td>

								</tr>



								<tr>

									<td class="left" width="300px;">Additions - Met Pad</td>

									<td class="left" width="150px;"><?php if (isset($add_met_pad_cost) && isset($order_quantity)){?>

										<label>$<?php echo sprintf('%0.2f', $add_met_pad_cost/$order_quantity); ?>

									</label> <?php }else{ echo '---';

									}?></td>

									<td class="left" width="150px;"><?php if(isset($add_met_pad_cost)) { ?>

										<label> $ <?php echo $add_met_pad_cost; ?>

									</label></td>

									<?php } else { echo '---';

} ?>

									<td class="left" width="150px;"><label><?php if(isset($pricing_values_tax_rates[7]['tax_item'])) echo $pricing_values_tax_rates[7]['tax_item'];?>



									</td>

									<td class="left" width="150px;"><label><?php if(isset($pricing_values_tax_rates[7]['tax_rate'])) echo $pricing_values_tax_rates[7]['tax_rate'].'%';?>



									</td>

									<td class="left" width="150px;"><?php if(isset($add_met_pad)) { ?>

										<label> $ <?php echo $add_met_pad; ?>

									</label></td>

									<?php } else { echo '---';

} ?>

									</td>

								</tr>



								<tr>

									<td class="left" width="300px;">Additions - Met Bar</td>

									<td class="left" width="150px;"><?php if (isset($add_met_bar_cost) && isset($order_quantity)){?>

										<label>$<?php echo sprintf('%0.2f', $add_met_bar_cost/$order_quantity); ?>

									</label> <?php }else{ echo '---';

									}?></td>

									<td class="left" width="150px;"><?php if(isset($add_met_bar_cost)) { ?>

										<label> $ <?php  echo $add_met_bar_cost; ?>

									</label></td>

									<?php } else { echo '---';

} ?>

									<td class="left" width="150px;"><label><?php if(isset($pricing_values_tax_rates[7]['tax_item'])) echo $pricing_values_tax_rates[7]['tax_item'];?>



									</td>

									<td class="left" width="150px;"><label><?php if(isset($pricing_values_tax_rates[7]['tax_rate'])) echo $pricing_values_tax_rates[7]['tax_rate'].'%';?>



									</td>

									<td class="left" width="150px;"><?php if(isset($add_met_bar)) { ?>

										<label> $ <?php echo $add_met_bar; ?>

									</label></td>

									<?php } else { echo '---';

} ?>

									</td>

								</tr>



								<tr>

									<td class="left" width="300px;">Additions - Morton's Extension</td>

									<td class="left" width="150px;"><?php if (isset($add_mortons_ext_cost) && isset($order_quantity)){?>

										<label>$<?php echo sprintf('%0.2f', $add_mortons_ext_cost/$order_quantity); ?>

									</label> <?php }else{ echo '---';

									}?></td>

									<td class="left" width="150px;"><?php if(isset($add_mortons_ext_cost)) { ?>

										<label> $ <?php  echo $add_mortons_ext_cost; ?>

									</label></td>

									<?php } else { echo '---';

} ?>

									<td class="left" width="150px;"><label><?php if(isset($pricing_values_tax_rates[7]['tax_item'])) echo $pricing_values_tax_rates[7]['tax_item'];?>



									</td>

									<td class="left" width="150px;"><label><?php  if(isset($pricing_values_tax_rates[7]['tax_rate'])) echo $pricing_values_tax_rates[7]['tax_rate'].'%';?>



									</td>

									<td class="left" width="150px;"><?php if(isset($add_mortons_ext)) { ?>

										<label> $ <?php echo $add_mortons_ext; ?>

									</label></td>

									<?php } else { echo '---';

} ?>

									</td>

								</tr>



								<tr>

									<td class="left" width="300px;">Additions - Reverse Morton's

										Extension</td>

									<td class="left" width="150px;"><?php if (isset($add_rev_mortans_ext_cost) && isset($order_quantity)){?>

										<label>$<?php echo sprintf('%0.2f', $add_rev_mortans_ext_cost/$order_quantity); ?>

									</label> <?php }else{ echo '---';

									}?></td>

									<td class="left" width="150px;"><?php if(isset($add_rev_mortans_ext_cost)) { ?>

										<label> $ <?php echo $add_rev_mortans_ext_cost; ?>

									</label> <?php } else { echo '---';

									} ?></td>

									<td class="left" width="150px;"><label><?php if(isset($pricing_values_tax_rates[7]['tax_item'])) echo $pricing_values_tax_rates[7]['tax_item'];?>



									</td>

									<td class="left" width="150px;"><label><?php  if(isset($pricing_values_tax_rates[7]['tax_rate'])) echo $pricing_values_tax_rates[7]['tax_rate'].'%';?>



									</td>

									<td class="left" width="150px;"><?php if(isset($add_rev_mortans_ext)) { ?>

										<label> $ <?php echo $add_rev_mortans_ext; ?>

									</label> <?php } else { echo '---';

									} ?></td>

								</tr>



								<tr>

									<td class="left" width="300px;">Additions - 2-5 Bar</td>

									<td class="left" width="150px;"><?php if (isset($add_bar_2_5_cost) && isset($order_quantity)){?>

										<label>$<?php echo sprintf('%0.2f', $add_bar_2_5_cost/$order_quantity); ?>

									</label> <?php }else{ echo '---';

									}?></td>

									<td class="left" width="150px;"><?php if(isset($add_bar_2_5_cost)) { ?>

										<label> $ <?php echo $add_bar_2_5_cost; ?>

									</label> <?php } else { echo '---';

									} ?></td>

									<td class="left" width="150px;"><label><?php if(isset($pricing_values_tax_rates[7]['tax_item'])) echo $pricing_values_tax_rates[7]['tax_item'];?>



									</td>

									<td class="left" width="150px;"><label><?php if(isset($pricing_values_tax_rates[7]['tax_rate'])) echo $pricing_values_tax_rates[7]['tax_rate'].'%';?>



									</td>

									<td class="left" width="150px;"><?php if(isset($add_bar_2_5)) { ?>

										<label> $ <?php echo $add_bar_2_5; ?>

									</label> <?php } else { echo '---';

									} ?></td>

								</tr>



								<tr>

									<td class="left" width="300px;">Additions - Hallux Rigidus

										Splint</td>

									<td class="left" width="150px;"><?php if (isset($add_hallux_spl_cost) && isset($order_quantity)){?>

										<label>$<?php echo sprintf('%0.2f', $add_hallux_spl_cost/$order_quantity); ?>

									</label> <?php }else{ echo '---';

									}?></td>

									<td class="left" width="150px;"><?php if($order_values_left_49 != '' || $order_values_right_49 != '' ) { ?>

										<label> $ <?php if (isset($add_hallux_spl_cost)) echo $add_hallux_spl; ?>

									</label></td>

									<?php } else { echo '---';

} ?>

									<td class="left" width="150px;"><label><?php if(isset($pricing_values_tax_rates[7]['tax_item'])) echo $pricing_values_tax_rates[7]['tax_item'];?>



									</td>

									<td class="left" width="150px;"><label><?php if(isset($pricing_values_tax_rates[7]['tax_rate'])) echo $pricing_values_tax_rates[7]['tax_rate'].'%';?>



									</td>

									<td class="left" width="150px;"><?php if(isset($add_hallux_spl)) { ?>

										<label> $ <?php echo $add_hallux_spl; ?>

									</label></td>

									<?php } else { echo '---';

} ?>

									</td>

								</tr>



								<tr>

									<td class="left" width="300px;">Additions - Cuboid pad</td>

									<td class="left" width="150px;"><?php if (isset($add_cuboid_pad_cost) && isset($order_quantity)){?>

										<label>$<?php echo sprintf('%0.2f', $add_cuboid_pad_cost/$order_quantity); ?>

									</label> <?php }else{ echo '---';

									}?></td>

									<td class="left" width="150px;"><?php if($order_values_left_42 != '' || $order_values_right_42 != '' ) { ?>

										<label> $ <?php if (isset($add_cuboid_pad_cost)) echo $add_cuboid_pad_cost ; ?>

									</label></td>

									<?php } else { echo '---';

} ?>

									<td class="left" width="150px;"><label><?php if(isset($pricing_values_tax_rates[7]['tax_item'])) echo $pricing_values_tax_rates[7]['tax_item'];?>



									</td>

									<td class="left" width="150px;"><label><?php if(isset($pricing_values_tax_rates[7]['tax_rate'])) echo $pricing_values_tax_rates[7]['tax_rate'].'%';?>



									</td>

									<td class="left" width="150px;"><?php if(isset($add_cuboid_pad)) { ?>

										<label> $ <?php echo $add_cuboid_pad; ?>

									</label></td>

									<?php } else { echo '---';

} ?>

									</td>

								</tr>



								<tr>

									<td class="left" width="300px;">Additions - Kinetic wedge</td>

									<td class="left" width="150px;"><?php if (isset($add_kinetic_wedge_cost) && isset($order_quantity)){?>

										<label>$<?php echo sprintf('%0.2f', $add_kinetic_wedge_cost/$order_quantity); ?>

									</label> <?php }else{ echo '---';

									}?></td>

									<td class="left" width="150px;"><?php if($order_values_left_43 != '' || $order_values_right_43 != '' ) { ?>

										<label> $ <?php if (isset($add_kinetic_wedge_cost)) echo $add_kinetic_wedge_cost; ?>

									</label> <?php } else { echo '---';

									} ?></td>

									<td class="left" width="150px;"><label><?php if(isset($pricing_values_tax_rates[7]['tax_item'])) echo $pricing_values_tax_rates[7]['tax_item'];?>



									</td>

									<td class="left" width="150px;"><label><?php if(isset($pricing_values_tax_rates[7]['tax_rate'])) echo $pricing_values_tax_rates[7]['tax_rate'].'%';?>



									</td>

									<td class="left" width="150px;"><?php if(isset($add_kinetic_wedge)) { ?>

										<label> $ <?php echo $add_kinetic_wedge; ?>

									</label> <?php } else { echo '---';

									} ?></td>

								</tr>



								<tr>

									<td class="left" width="300px;">Additions - Lateral Arch Fill</td>

									<td class="left" width="150px;"><?php if (isset($add_lateral_arch_fill_cost) && isset($order_quantity)){?>

										<label>$<?php echo sprintf('%0.2f', $add_lateral_arch_fill_cost/$order_quantity); ?>

									</label> <?php }else{ echo '---';

									}?></td>

									<td class="left" width="150px;"><?php if($order_values_left_44 != '' || $order_values_right_44 != '' ) { ?>

										<label> $ <?php if(isset($add_lateral_arch_fill_cost))  echo $add_lateral_arch_fill_cost; ?>

									</label> <?php } else { echo '---';

									} ?></td>

									<td class="left" width="150px;"><label><?php if(isset($pricing_values_tax_rates[7]['tax_item'])) echo $pricing_values_tax_rates[7]['tax_item'];?>



									</td>

									<td class="left" width="150px;"><label><?php if(isset($pricing_values_tax_rates[7]['tax_rate'])) echo $pricing_values_tax_rates[7]['tax_rate'].'%';?>



									</td>

									<td class="left" width="150px;"><?php if(isset($add_lateral_arch_fill)) { ?>

										<label> $ <?php echo $add_lateral_arch_fill; ?>

									</label> <?php } else { echo '---';

									} ?></td>

								</tr>



								<tr>

									<td class="left" width="300px;">Additions - Horseshoe Spur</td>

									<td class="left" width="150px;"><?php if (isset($add_horse_shoe_cost) && isset($order_quantity)){?>

										<label>$<?php echo sprintf('%0.2f', $add_horse_shoe_cost/$order_quantity); ?>

									</label> <?php }else{ echo '---';

									}?></td>

									<td class="left" width="150px;"><?php if($order_values_left_45 != '' || $order_values_right_45 != '' ) { ?>

										<label> $ <?php if (isset($add_horse_shoe_cost)) echo $add_horse_shoe_cost; ?>

									</label> <?php } else { echo '---';

									} ?></td>

									<td class="left" width="150px;"><label><?php if(isset($pricing_values_tax_rates[7]['tax_item'])) echo $pricing_values_tax_rates[7]['tax_item'];?>



									</td>

									<td class="left" width="150px;"><label><?php if(isset($pricing_values_tax_rates[7]['tax_rate'])) echo $pricing_values_tax_rates[7]['tax_rate'].'%';?>



									</td>

									<td class="left" width="150px;"><?php if(isset($add_horse_shoe)) { ?>

										<label> $ <?php echo $add_horse_shoe; ?>

									</label> <?php } else { echo '---';

									} ?></td>

								</tr>



								<tr>

									<td class="left" width="300px;">Additions - Neuroma Pad</td>

									<td class="left" width="150px;"><?php if (isset($add_neuroma_pad_cost) && isset($order_quantity)){?>

										<label>$<?php echo sprintf('%0.2f', $add_neuroma_pad_cost/$order_quantity); ?>

									</label> <?php }else{ echo '---';

									}?></td>

									<td class="left" width="150px;"><?php if(isset($add_neuroma_pad_cost)) { ?>

										<label> $ <?php  echo $add_neuroma_pad_cost; ?>

									</label> <?php } else { echo '---';

									} ?></td>

									<td class="left" width="150px;"><label><?php if(isset($pricing_values_tax_rates[7]['tax_item'])) echo $pricing_values_tax_rates[7]['tax_item'];?>



									</td>

									<td class="left" width="150px;"><label><?php if(isset($pricing_values_tax_rates[7]['tax_rate'])) echo $pricing_values_tax_rates[7]['tax_rate'].'%';?>



									</td>

									<td class="left" width="150px;"><?php if(isset($add_neuroma_pad)) { ?>

										<label> $ <?php  echo $add_neuroma_pad; ?>

									</label> <?php } else { echo '---';

									} ?></td>

								</tr>



								<tr>

									<td class="left" width="300px;">Additions - Met Cutouts</td>

									<td class="left" width="150px;"><?php if (isset($add_met_cut_outs_cost) && isset($order_quantity)){?>

										<label>$<?php echo sprintf('%0.2f', $add_met_cut_outs_cost/$order_quantity); ?>

									</label> <?php }else{ echo '---';

									}?></td>

									<td class="left" width="150px;"><?php if(is_array($order_values_left_50) && ($order_values_left_50[0] != '' || $order_values_right_50[0] != '') ) { ?>

										<label> $ <?php if(isset($add_met_cut_outs_cost)) echo $add_met_cut_outs_cost; ?>

									</label> <?php } else { echo '---';

									} ?></td>

									<td class="left" width="150px;"><label><?php if(isset($pricing_values_tax_rates[7]['tax_item'])) echo $pricing_values_tax_rates[7]['tax_item'];?>



									</td>

									<td class="left" width="150px;"><label><?php if(isset($pricing_values_tax_rates[7]['tax_rate'])) echo $pricing_values_tax_rates[7]['tax_rate'].'%';?>



									</td>

									<td class="left" width="150px;"><?php if(isset($add_met_cut_outs)) { ?>

										<label> $ <?php echo $add_met_cut_outs; ?>

									</label> <?php } else { echo '---';

									} ?></td>

								</tr>



								<tr>

									<td class="left" width="300px;">Additions - Heel Lift</td>

									<td class="left" width="150px;"><?php if (isset($add_heel_lifts_cost) && isset($order_quantity)){?>

										<label>$<?php echo sprintf('%0.2f', $add_heel_lifts_cost/$order_quantity); ?>

									</label> <?php }else{ echo '---';

									}?></td>

									<td class="left" width="150px;"><?php if(isset($add_heel_lifts_cost)) { ?>

										<label> $ <?php  echo $add_heel_lifts_cost; ?> <?php } else { echo '---';

} ?>

									</label></td>

									<td class="left" width="150px;"><label><?php if(isset($pricing_values_tax_rates[7]['tax_item'])) echo $pricing_values_tax_rates[7]['tax_item'];?>



									</td>

									<td class="left" width="150px;"><label><?php if(isset($pricing_values_tax_rates[7]['tax_rate'])) echo $pricing_values_tax_rates[7]['tax_rate'].'%';?>



									</td>

									<td class="left" width="150px;"><?php if(isset($add_heel_lifts)) { ?>

										<label> $ <?php  echo $add_heel_lifts; ?> <?php } else { echo '---';

} ?>



									</td>

								</tr>



								<tr>

									<td class="left" width="300px;">FF ext. rate</td>

									<td class="left" width="150px;"><?php if (isset($ff_ext_cost) && isset($order_quantity)){?>

										<label>$<?php echo sprintf('%0.2f', $ff_ext_cost/$order_quantity); ?>

									</label> <?php }else{ echo '---';

									}?></td>

									<td class="left" width="150px;"><?php if(isset($ff_ext_cost)) { ?>

										<label> $ <?php  echo $ff_ext_cost; ?>

									</label> <?php } else { echo '---';

									} ?></td>

									<td class="left" width="150px;"><label><?php if(isset($pricing_values_tax_rates[2]['tax_item'])) echo $pricing_values_tax_rates[2]['tax_item'];?>



									</td>

									<td class="left" width="150px;"><label><?php if(isset($pricing_values_tax_rates[2]['tax_rate'])) echo $pricing_values_tax_rates[2]['tax_rate'].'%';?>



									</td>

									<td class="left" width="150px;"><?php if(isset($ff_ext_tax)) { ?>

										<label> $ <?php echo $ff_ext_tax; ?>

									</label> <?php } else { echo '---';

									} ?></td>

								</tr>



								<tr>

									<td class="left" width="300px;">RF EVA post rate</td>

									<td class="left" width="150px;"><?php if (isset($rf_eva_post_cost) && isset($order_quantity)){?>

										<label>$<?php echo sprintf('%0.2f', $rf_eva_post_cost/$order_quantity); ?>

									</label> <?php }else{ echo '---';

									}?></td>

									<td class="left" width="150px;"><?php if($order_values_22 == '113') { ?>

										<label> $ <?php if (isset($rf_eva_post_cost)) echo $rf_eva_post_cost; ?>

									</label> <?php } else { echo '---';

									} ?></td>

									<td class="left" width="150px;"><label><?php if(isset($pricing_values_tax_rates[5]['tax_item'])) echo $pricing_values_tax_rates[5]['tax_item'];?>



									</td>

									<td class="left" width="150px;"><label><?php if(isset($pricing_values_tax_rates[5]['tax_rate'])) echo $pricing_values_tax_rates[5]['tax_rate'].'%';?>



									</td>

									<td class="left" width="150px;"><?php if(isset($rf_eva_post_tax)) { ?>

										<label> $ <?php echo $rf_eva_post_tax; ?>

									</label> <?php } else { echo '---';

									} ?></td>

								</tr>



								<tr>

									<td class="left" width="300px;">FF EVA post rate</td>

									<td class="left" width="150px;"><?php if (isset($ff_eva_post_cost) && isset($order_quantity)){?>

										<label>$<?php echo sprintf('%0.2f', $ff_eva_post_cost/$order_quantity); ?>

									</label> <?php }else{ echo '---';

									}?></td>

									<td class="left" width="150px;"><?php if($order_values_70 == '328') { ?>

										<label> $ <?php if (isset($ff_eva_post_cost)) echo $ff_eva_post_cost; ?>

									</label> <?php } else { echo '---';

									} ?></td>

									<td class="left" width="150px;"><label><?php if(isset($pricing_values_tax_rates[4]['tax_item'])) echo $pricing_values_tax_rates[4]['tax_item'];?>



									</td>

									<td class="left" width="150px;"><label><?php if(isset($pricing_values_tax_rates[4]['tax_rate'])) echo $pricing_values_tax_rates[4]['tax_rate'].'%';?>



									</td>

									<td class="left" width="150px;"><?php if(isset($ff_eva_post_tax)) { ?>

										<label> $ <?php echo $ff_eva_post_tax; ?>

									</label> <?php } else { echo '---';

									} ?></td>

								</tr>



								<tr>

									<td class="left" width="300px;">EVA Shell Surcharge</td>

									<td class="left" width="150px;"><?php if (isset($eva_shell_cost) && isset($order_quantity)){?>

										<label>$<?php echo sprintf('%0.2f', $eva_shell_cost/$order_quantity); ?>

									</label> <?php }else{ echo '---';

									}?></td>

									<td class="left" width="150px;"><?php if(isset($eva_shell_cost)) { ?>

										<label> $ <?php  echo $eva_shell_cost; ?>

									</label> <?php } else { echo '---';

									} ?></td>

									<td class="left" width="150px;"><label><?php if(isset($pricing_values_tax_rates[6]['tax_item'])) echo $pricing_values_tax_rates[6]['tax_item'];?>



									</td>

									<td class="left" width="150px;"><label><?php if(isset($pricing_values_tax_rates[6]['tax_rate'])) echo $pricing_values_tax_rates[6]['tax_rate'].'%';?>



									</td>

									<td class="left" width="150px;"><?php if(isset($eva_shell_tax)){ ?>

										<label> $ <?php echo $eva_shell_tax; ?>

									</label> <?php } else { echo '---';

									} ?></td>

								</tr>



								<tr>

									<td class="left" width="300px;">Rush Surcharge</td>

									<td class="left" width="150px;"><?php if (isset($rush_cost) && isset($order_quantity)){?>

										<label>$<?php echo sprintf('%0.2f', $rush_cost); ?>

									</label> <?php }else{ echo '---';

									}?></td>

									<td class="left" width="150px;"><?php if($order_values_rush_75 == '342') { ?>

										<label> $ <?php if(isset($rush_cost)) echo $rush_cost; ?>

									</label> <?php } else { echo '---';

									} ?></td>

									<td class="left" width="150px;"><label><?php if(isset($pricing_values_tax_rates[10]['tax_item'])) echo $pricing_values_tax_rates[10]['tax_item'];?>



									</td>

									<td class="left" width="150px;"><label><?php if(isset($pricing_values_tax_rates[10]['tax_rate'])) echo $pricing_values_tax_rates[10]['tax_rate'].'%';?>



									</td>

									<td class="left" width="150px;"><?php if(isset($rush_tax)) { ?>

										<label> $ <?php echo $rush_tax; ?>

									</label> <?php } else { echo '---';

									} ?></td>

								</tr>



								<!-- <tr>

			              <td class="left" width="300px;">Base shipping rate</td>

			              <td class="left" width="150px;">Unit Price:</td>

			              <td class="left" width="150px;"><label> $ <?php if(isset($pricing_values[12]['clinic_item_price'])) echo $pricing_values[12]['clinic_item_price']; ?></label></td>

			               <td class="left" width="150px;"><label><?php if(isset($pricing_values_tax_rates[11]['tax_item'])) echo $pricing_values_tax_rates[11]['tax_item'];?></td>

			              <td class="left" width="150px;"><label><?php if(isset($pricing_values_tax_rates[11]['tax_rate'])) echo $pricing_values_tax_rates[11]['tax_rate'].'%';?></td>

			             <td class="left" width="150px;"><label> $ <?php if(isset($base_shipping_tax)) echo $base_shipping_tax; ?></label></td>

			            </tr>  -->



							</tbody>



							<tfoot>

								<tr>

									<td class="left" width="300px;"><strong>Total :</strong></td>

									<td class="left" width="300px;">---</td>

									<td class="left"><?php if(isset($total_unit_cost_pair)){  ?> <strong>$

											<?php echo $total_unit_cost_pair;?>

									</strong> <?php } else {echo '---';

									}?></td>

									<td class="left" width="150px;">---</td>

									<td class="left" width="150px;">---</td>

									<td class="left" width="150px;"><?php if(isset($total_including_tax)){ ?>

										<strong>$ <?php echo $total_including_tax;?>

									</strong> <?php }else {echo '---';

									}?></td>

								</tr>

								<tr>

									<td class="left" width="300px;"><strong>Total Unit Price :</strong><br>

										<label><?php if ($order_values_58 == '248') {

											echo '(Pairs)';

										}else if ($order_values_58 == '249'){

echo '(Left Only)';

}else {echo '(Right Only)';

} ?> </label>

									</td>

									<td class="left" width="300px;">---</td>

									<td class="left"><?php if(isset($total_unit_cost_pair)){  ?> <strong>$

											<?php echo sprintf('%0.2f', $total_unit_cost_pair/$order_quantity);?>

									</strong> <?php } else {echo '---';

									}?></td>

									<td class="left" width="150px;">---</td>

									<td class="left" width="150px;">---</td>

									<td class="left" width="150px;"><?php if(isset($total_including_tax)){ ?>

										<strong>$ <?php echo sprintf('%0.2f', $total_including_tax/$order_quantity);?>

									</strong> <?php }else {echo '---';

									}?></td>

								</tr>

							</tfoot>

						</table>



					</div>

				</fieldset>

			</div>

			<?php } ?>

			<!-- Main-mid -->

			<!-- <div class="main-bot"></div> -->

		</div>

		<!-- main end -->

		<div id="filebuttons">

			<?php if ($order_id) {
            // if($user_group_string!=11){ ?>

			<!--<a href="javascript:;"
				onclick="window.open('<?php echo $_SERVER['REQUEST_URI'];?>&p=1','_blank')"
				class="bbutton bp"><p></p> <span>Print<br /> <?php
				//if ($this->request->isget('w')) {
				//	$t = str_replace(array('tab_','_'),array('',' '),$this->request->isget('w'));
				//	echo $t;
				//} else
				echo 'Order';	?>
                </span> </a> -->
            <a href="javascript:void();" onclick="print_1page(<?php if ($this->user->stepFull($order_status_id)) echo 1; ?>)" class="bbutton bp"><p></p> <span>1 Page<br />Print </span> </a>
            <?php // } ?>
			<?php
			if (isset($order_status_id) && $order_status_id && $this->user->stepDisplay($order_status_id)) {

    // LINKS



	if ($order_values_7 == '42'){


    $s = "SELECT * FROM order_status WHERE order_status_id = '$order_status_id'";



    $order_status = mysql_fetch_assoc(mysql_query($s));



    $steps = explode(',',$order_status['order_status_steps']);



    if ($order_status['order_status_id'] == '300') {



    echo '<a class="bbutton breceive" onclick="update_history(\'40\');"><p></p><span style="color:red;">"Rejected"</br />Send to Lab</span></a> ';





    } elseif ($order_status['order_status_id'] == '180') {

    $steparray = array();

    } else {

    $text = array('next'=>'Send to','receive'=>'','hold'=>'Hold at','back'=>'Send back to');

    foreach($steps as $step) {



    $steparray = explode(':',$step);



    $fulltext = $text[$steparray[1]];



    $spn = "";

    if ($steparray[1] == 'next' && in_array($steparray[0],array(150,170)) || $steparray[1] == 'receive') {

    $fulltext =  $steparray[2];

    $spn = ' style="line-height: 36px;" ';

    } else {

    $fulltext .= "<br />" . $steparray[2];

    }





    echo '<a class="bbutton b'.$steparray[1].'" onclick="update_history('.$steparray[0].');"><p></p><span'.$spn.'>'.$fulltext.'</span></a> ';





    }

    }



    }

    else {

    	$s = "SELECT * FROM order_status_dm WHERE order_status_id = '$order_status_id'";



    	$order_status = mysql_fetch_assoc(mysql_query($s));



    	$steps = explode(',',$order_status['order_status_steps']);



    	if ($order_status['order_status_id'] == '300') {



			echo '<a class="bbutton breceive" onclick="update_history(\'40\');"><p></p><span style="color:red;">"Rejected"</br />Send to Lab</span></a> ';





		} elseif ($order_status['order_status_id'] == '180') {

			$steparray = array();

		} else {

			$text = array('next'=>'Send to','receive'=>'','hold'=>'Hold at','back'=>'Send back to');

			foreach($steps as $step) {



			$steparray = explode(':',$step);

// print_r($step);

			$fulltext = $text[$steparray[1]];



			$spn = "";

			if ($steparray[1] == 'next' && in_array($steparray[0],array(150,170)) || $steparray[1] == 'receive') {

				$fulltext =  $steparray[2];

				$spn = ' style="line-height: 36px;" ';

			} else {

				$fulltext .= "<br />" . $steparray[2];

			}





			echo '<a class="bbutton b'.$steparray[1].'" onclick="update_history('.$steparray[0].');"><p></p><span'.$spn.'>'.$fulltext.'</span></a> ';





			}

		}

    }

  }

									} // order_id

									?>

			<?php if ($user_group_string == '1' && $order_id && ! in_array($order_status_id,array('300','170','180'))) { ?>

			<a href="javascript:;" class="bbutton breject"

				onclick="update_history('300');"><p></p> <span

				style="line-height: 36px; color: red;"><b>Reject</b> </span> </a>

			<?php } ?>


		<? /*if(!in_array($user_group_string, array('11'))): ?>

			 <a onclick="$('#form_my_custom').attr('action', 'hc_scripts/print_labels.php?<?php echo $_REQUEST['order_id']?>'); $('#form_my_custom').attr('target', '_self'); $('#form_my_custom').submit();" class="bbutton print_labels"><p></p><span

				style="line-height: 36px;"><b>Print label</b> </span> </a>

		<? endif; */ ?>
     <?php if (!isset($order_id) || $order_id=='') { ?>
     <? if(!in_array($user_group_string, array('11'))): ?>
		<a href="javascript:void();" onclick="print_1page(<?php if ($this->user->stepFull($order_status_id)) echo 1; ?>)" class="bbutton bp">
     <p></p><span>1 Page<br /> Print </span> </a>
	<? endif;  ?>
   <?php } ?>
		</div>

		<div class="bottombuttons">



			<a id="button_back"

				onclick="$.tabs('#tabs a',$('#button_back').attr('title'));"

				class="bbutton bbb"><p></p> <span>Back</span> </a> <a

				id="button_next"

				onclick="$.tabs('#tabs a',$('#button_next').attr('title'));"

				class="bbutton bn"><p></p> <span>Next</span> </a>



			<?php if ($this->user->stepFull($order_status_id)) { ?>



			<a id="button_save" onclick="submit_orderform();" class="bs bbutton"><p></p>

				<span>Save<br />Changes</span> </a>
			<a id="button_save" onclick="submit_orderform('new');" class="bs bbutton"><p></p>

				<span>Save &<br />New</span> </a>
                <!--<a id="button_save" onclick="submit_orderform();" class="bs bbutton"><p></p>

				<span>Save &<br />Add New</span> </a>-->

			<?php } ?>



			<a id="button_cancel" class="bc bbutton" href="<?php echo $cancel;?>"><p></p>

				<span>Cancel</span> </a>

		</div>



		<input type="hidden" name="patient_id"

			value="<?php echo $patient_id;?>" /> <input type="hidden"

			name="order_id" value="<?php echo $order_id;?>" /> <input

			type="hidden" name="order_status_id"

			value="<?php echo $order_status_id;?>" />



</form>


<!-- OUTSIDE FORM -->



<?php



if ($calculated_deliverydate > date("Y-m-d")) {

$diff = ceil((strtotime($calculated_deliverydate) - strtotime($order_date_added)) / (60*60*24));

} else $diff = 1;

?>

<input

	type="hidden" name="minorderdate" value="<?php echo $diff;?>" />



<script

	type="text/javascript" src="view/javascript/jquery/ui/ui.datepicker.js"></script>



<script

	type="text/javascript"

	src="view/javascript/hc_general.php?json={|prg_id|:|order_form|}"></script>

<script

	type="text/javascript" src="view/javascript/hc_order_form.js"></script>



<script

	type="text/javascript"

	src="view/javascript/hc_order_form_defaults.php?json={|o37|:|<?php echo $order_values_37;?>|}"></script>



<script type="text/javascript"><!--



function update_history(statusx) {

    verifyx = confirm('Status will be updated...\n\nAre You Sure?');

    if (! verifyx) return false;



    var tab_name = document.getElementById('where_am_i').value;



    $.ajax({

    type: 'POST',

    url: 'index.php?route=sale/order/update&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>&w='+tab_name,

    dataType: 'html',

    data: $('#orderform').serialize()+'&order_status_id=' + encodeURIComponent(statusx) + '&comment=' + encodeURIComponent($('#order_history_comment').val()),

    beforeSend: function() {

    $('.bigbutton').attr('disabled', 'disabled');

},

complete: function(data) {

//		alert(data);

},

success: function(data) {

location = "index.php?route=sale/order/update&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>&w="+tab_name;

if (data.error) {

//				$('#tab_history .form').before('<div class="warning">' + data.error + '</div>');

}

}

});



}



function get_order_form(){



url = 'index.php?route=sale/order/insert&token=<?php echo $token; ?>';



var pid = $('select[name=\'patient_id\']').attr('value');



if (pid) {

url += '&pid=' + encodeURIComponent(pid);

}

location = url;

}







function submit_orderform(val){

<?php

if (! $order_id) {

?>

if ($('#order_values_24').val() == '117') {

res = confirm("Are you sure you don't want any top cover?");

if (!res) return false;

}

<?php

}

?>

	if(val == 'new'){
		$('#orderform').append('<input type="hidden" name="save_new" value="true">');
		$('#orderform').submit();
		console.log(val);
	}else{
		$('#orderform').submit();
	}

}







function saveStatusPrint()

{

	var first = getUrlVars()["filter_status"];



	if(first)

	{

	  order_status= 'filter_status='+first;

	}

	else

	{

		order_status= '';

	}



	var tab_name = document.getElementById('where_am_i').value;



$.ajax({

	    type: 'POST',

	    url: 'index.php?route=sale/order/update&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>&w='+tab_name+'&'+order_status,

	    dataType: 'html',

	    data: $('#orderform').serialize()+ '&order_history_comment=' + encodeURIComponent('1 Page Print'),

	    beforeSend: function() {

	    $('.bigbutton').attr('disabled', 'disabled');

	},

	complete: function(data) {

//			alert(data);

	},

	success: function(data) {

	location = "index.php?route=sale/order/update&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>&w="+tab_name+'&'+order_status;

	if (data.error) {

//					$('#tab_history .form').before('<div class="warning">' + data.error + '</div>');

	}

	}

	});

}



function getUrlVars() {

    var vars = {};

    var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {

        vars[key] = value;

    });

    return vars;

}





function all_ortho_funct(id) {

//setTimeout(function(){all_ortho_funct(id)}, 250);

disable_all();

do_div_fade()

do_defaults(id);

do_hallux();

do_wedge();

do_direct_milled();

do_vacuum_pressed();



//do_covers(24,$('#order_values_24').val(),24);



}





function f_date_needed_by(){

alert($diff); // comment this later

diff_date = ($('#order_values_rush_75').attr('checked')?1:<?php echo $diff;?>);



$('#order_date_needed').datepicker('option','minDate',diff_date);

}



function put_address_in_box(data){



	//decoded = data.replace(/&amp;/g,'&');

	var decoded = data.split(',');
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

	    if($('#order_values_patient_address_86').attr('checked'))

	      $('#order_values_patient_address_86').attr('checked',false);

}



// this function is to check the rush date checkbox if the value in textbox is checked.



function rushDateCheck()

{

	 if($('#order_date_needed').val()!='')

	 {

		 $('#order_values_rush_75').attr('checked',true);

	 }

	 if($('#order_date_needed').val()=='')

	 {

		 $('#order_values_rush_75').attr('checked',false);

	 }

}



function use_patient_address(){



patient_address_checked = ($('#order_values_patient_address_86').attr('checked'));



if(patient_address_checked){

$.ajax({

    type: 'GET',

    url: 'index.php?route=sale/order/usePatientAddress&token=<?php echo $token; ?>&patient_id=<?php echo $patient_id; ?>',

    dataType: 'json',

    data: '',

    success: function(data) {

        if(data['success']){

        var areEqual = false;

        $('#ship_addressx option').each(function()

        {

            if($(this).text() == data['patient_addrees'] && !areEqual){

                    $(this).attr('selected',true);

                    areEqual=true;



            }

            if ($(this).val() === ''){

                $(this).remove();

            }

        });

        if(!areEqual)

        	$("#clinic_addresses").hide();

            //$('#ship_addressx').append("<option value="+data['patient_addrees']+" selected='selected'>"+data['patient_addrees']+"</option>")



        $('#order_shipping_address').val(data['patient_addrees']);

    }

    else{

    $('#order_values_patient_address_86').attr('checked',false).attr("disabled", true);

    alert('No patient address was found on file. Please update patient information to use this option.');



    }

     }



    });

}

else {

		var reset_text = '';



		 $("#clinic_addresses").show();

		 $('#order_shipping_address').val(reset_text);

}

}





$(document).ready(function() {



show_hide_file_frame();



if($('#order_values_patient_address_86').attr('checked')){



	$("#clinic_addresses").hide();



}



<?php if($this->request->isget('order_id')) { ?>



//do_covers(26,this.value,24);

//do_covers(28,this.value,28);

//do_covers(31,this.value,31);

//do_covers(34,this.value,34);



<?php } ?>





<?php if ($this->request->isget('p')) { ?>

$('#tabs').hide();

$('#filebuttons').hide();

$('.bottombuttons').hide();

$('.logo').hide();

$('.mainmenu').hide();

$('.breadcrumb').hide();

$('.header').hide();

$('html').css('background','none');

$('body').css('background','none');

$('.container').css('margin','0');

$('.toptext').css('float','left');

$('body').find('input, textarea, button, select').attr('disabled','disabled');

window.print();

<?php } else { ?>

$.tabs('#tabs a');

<?php }



if($this->request->isget('w')) {

echo "$.tabs('#tabs a','#".$this->request->isget('w')."');";

if($this->request->isget('w') == 'tab_covers') {

	// This is the issue with Leather cover. Commented this out.
	//echo "do_covers(24,$('#order_values_24').val(),24);";
	//echo "do_covers(26,$('#order_values_24').val(),24);";

}



}



?>





do_met_pad();



do_hallux();



do_wedge();



do_direct_milled();



do_vacuum_pressed();



do_ortho_cond('<?php echo $order_values_7;?>','<?php echo $order_values_53;?>','<?php echo $order_values_54;?>');



do_mods_cond('<?php echo $order_values_16;?>','<?php echo $order_values_11;?>','<?php echo $order_values_14;?>','<?php echo $order_values_13;?>','<?php echo $order_values_12;?>','<?php echo $order_values_68;?>');



$('.date').datepicker({dateFormat: 'yy-M-dd',changeMonth: true,changeYear: true});




$('.date_birth').datepicker({dateFormat: 'yy-mm-dd',changeMonth: true,changeYear: true , yearRange: '1900:2020'});

//$('#order_deliverydate').datepicker({dateFormat: 'yy-M-dd', beforeShowDay: $.datepicker.noWeekends,changeMonth: true,changeYear: true, minDate:'+1D'});

$('#order_deliverydate').datepicker({dateFormat: 'yy-M-dd', beforeShowDay: $.datepicker.noWeekends,changeMonth: true,changeYear: true});


//$('#order_date_needed').datepicker({dateFormat: 'yy-M-dd',changeMonth: true,changeYear: true, minDate:0, beforeShowDay: $.datepicker.noWeekends,minDate:'+1D'});

$('#order_date_needed').datepicker({dateFormat: 'yy-M-dd', beforeShowDay: $.datepicker.noWeekends,changeMonth: true,changeYear: true});



//f_date_needed_by();







get_clinicians(<?php echo $patient_clinician_id;?>);







// DOCUMENT READY CONT HERE







$('#order_values_right_23').removeAttr('onchange');

$('#order_values_left_23').removeAttr('onchange');

$('#order_values_right_73').removeAttr('onchange');

$('#order_values_left_73').removeAttr('onchange');



/*degrees_directions('left',56,322,23,114)

degrees_directions('right',56,322,23,114)

degrees_directions('left',72,331,73,338)

degrees_directions('right',72,331,73,338)

*/

$('#order_values_left_56').change(function(){

degrees_directions('left',56,322,23,114)

});



$('#order_values_right_56').change(function(){

degrees_directions('right',56,322,23,114)

});



$('#order_values_left_72').change(function(){

degrees_directions('left',72,331,73,338)

});



$('#order_values_right_72').change(function(){

degrees_directions('right',72,331,73,338)

});



//$('#order_values_left_56').trigger('change');

//$('#order_values_right_56').trigger('change');

//$('#order_values_left_72').trigger('change');

//$('#order_values_right_72').trigger('change');



$('#a_covers').click(function(){

do_covers(24,$('#order_values_24').val(),24);

});



//this block of code is written to save the order form data when pricing tab is clicked

$('#a_pricing').click(function(){



	var tab_name = document.getElementById('where_am_i').value;



	$.ajax({

	    type: 'POST',

	    url: 'index.php?route=sale/order/update&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>&w='+tab_name,

	    dataType: 'html',

	    data: $('#orderform').serialize()+'&order_status_id=' + encodeURIComponent(<?php echo $order_status_id;?>) + '&order_history_comment=' + encodeURIComponent('Pricing saved'),

	    beforeSend: function() {

	    $('.bigbutton').attr('disabled', 'disabled');

	},

	complete: function(data) {

//			alert(data);

	},

	success: function(data) {

	location = "index.php?route=sale/order/update&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>&w="+tab_name;

	if (data.error) {

//					$('#tab_history .form').before('<div class="warning">' + data.error + '</div>');

	}

	}

	});

});



});



<?php if ($order_values_7 == '42') echo "$('.div_22_112').hide();";?>





// initialize the posting tab on page load



//rf_none();

//ff_none();

//rf_extrinsic();

//ff_extrinsic();

//postings_disable();

disable_all();



// implement defaults for rear foot and fore foot under posting tab





$("#order_values_21_110").click(function(){

rf_intrinsic();

});



$("#order_values_21_109").click(function(){

rf_extrinsic();

});



$("#order_values_21_108").click(function(){

rf_none();

});



$("#order_values_left_56").click(function(){

rf_degrees();

});



$("#order_values_right_56").click(function(){

rf_degrees();

});





$("#order_values_69_326").click(function(){

ff_intrinsic();

});



$("#order_values_69_324").click(function(){

ff_none();

});



$("#order_values_69_325").click(function(){

ff_extrinsic();

});



/*$("#order_values_left_72").click(function(){

        ff_degrees();

});



$("#order_values_right_72").click(function(){

        ff_degrees();

});

*/





if (($('#order_values_8').val()=='43' || $('#order_values_8').val() == undefined) && ! $('#order_id').val() ) {

do_casting_method();

}



get_shoesizes ('<?php echo $order_values_3;?>');



if($('input[name=order_values_69]:checked').val() == 324) ff_none();

else if($('input[name=order_values_69]:checked').val() == 325) ff_extrinsic();

else if($('input[name=order_values_69]:checked').val() == 326) ff_intrinsic();



if($('input[name=order_values_21]:checked').val() == 108) rf_none();

else if($('input[name=order_values_21]:checked').val() == 109) rf_extrinsic();

else if($('input[name=order_values_21]:checked').val() == 110) rf_intrinsic();



check_select_radio(13,70,64,0)





check_select_radio(11,60,61,0)





<?php if (! $this->user->stepFull($order_status_id)) { ?>

$('#to_be_disabled').find('input, textarea, button').attr('disabled','disabled');



//	$('#to_be_disabled').html('<img style="position:absolute;width:820px; height:400px;" src="images/spacer.gif" />' + $('#to_be_disabled').html());

<?php } ?>



$("a.gallery").fancybox({

'hideOnContentClick': false,

'transitionIn'		: 'none',

'transitionOut'		: 'none',

'titlePosition' 	: 'over',

'titleFormat'		: function(title, currentArray, currentIndex, currentOpts) {

//					return '<span id="fancybox-title-over">Image ' + (currentIndex + 1) + ' / ' + currentArray.length + (title.length ? ' &nbsp; ' + title : '') + '</span>';

return '<span id="fancybox-title-over">Image: ' + (currentIndex + 1) + ' / ' + currentArray.length + '</span>';

}





});



//--></script>

<?php echo $footer; ?>