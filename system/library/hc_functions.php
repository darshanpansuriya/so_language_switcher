<?php
final class Hc_functions {
	private $url;
	private $username;
  	private $permission = array();
	private $user_group_string;
	private $user_group_id;
	private $broker_code;
	private $hc_last_login;

  	public function __construct($registry) {
		$this->request = $registry->get('request');
		$this->db = $registry->get('db');
	}

  	public function getURL($data) {

		if (!is_array($data)) return '';

		$url = '';

		foreach ($data as $k=>$valx) {

			if (isset($this->request->get[$valx])) {

				$url .= '&' . $valx .'=' . $this->request->get[$valx];
			}

		}

		return $url;
  	}


	public function getURLvars($data,$so='lastname,firstname',$or='ASC',$p='1') {

		if (!is_array($data)) return '';

		$retarr = array();

		foreach ($data as $k=>$valx) {

			if (isset($this->request->get[$valx])) {
				$retarr[$valx] = $this->request->get[$valx];
			} else {

				if ($valx == 'page') $retarr['page'] = $p;
				elseif($valx == 'sort') $retarr['sort'] = $so;
				elseif ($valx == 'order')  $retarr['order'] = $or;
				else $retarr[$valx] = NULL;
			}


		}
		 return $retarr;

	}

	public function get_field($fld='', $sql="") {
		$t = $this->db->query($sql);
		if ($t->num_rows) return $t->row[$fld];
		else return NULL;
	}

	public function print_select($group=1,$order_id = '',$required=0,$others='',$notitle=0,$fname = '',$default=0,$tab="") {
		$grtable = $this->db->query("SELECT * FROM lookup_table_types WHERE lookup_table_types_id = '$group'");
		if ($grtable->num_rows) {
			$mygroup = $grtable->row;
		} else return '';

		if (in_array($mygroup['lookup_table_types_type'],array(5,9))) {
			$this->print_checkbox($group,$order_id,$required,$others,$notitle);
			return;
		} elseif (in_array($mygroup['lookup_table_types_type'],array(1))) {
			$this->print_radio($group,$order_id,$required,$others,$notitle);
			return;

		}

		$fields = $this->db->query("SELECT * FROM lookup_table WHERE lookup_table_lookup_table_types_id = '$group' ORDER BY lookup_table_sort");

		if ($fields->num_rows) {
			if ($mygroup['lookup_table_types_type'] == 3) {
				$feet = array("_left","_right");
			} elseif ($mygroup['lookup_table_types_type'] == 7) {
				$feet = array("_rear","_fore"); // since float right, print in reverse order
			} elseif ($mygroup['lookup_table_types_type'] == 8) {
				$feet = array("_rear_left","_rear_right","_fore_left","_fore_right"); // since float right, print in reverse order
			} else {
				$feet = array("_");
			}
			if (!$notitle) {
				echo '<label'.(in_array($mygroup['lookup_table_types_type'],array(3,6,7,8)) ? ' style="float:left"':'').'>';
				if ($required) {
					echo '<span class="required">*</span> ';
				}
				echo $mygroup['lookup_table_types_name']. ":</label>\n";
			}

			if (in_array($mygroup['lookup_table_types_type'],array(3,8,6, 7))) echo '<div style="float:right;">';
			foreach($feet as $foot){

				if (in_array($mygroup['lookup_table_types_type'],array(3,8))) echo "<p>";
				elseif (in_array($mygroup['lookup_table_types_type'],array(6,7))) echo '<p class="singleselect">';

				if ($fname) $name = $fname;
				else $name = $this->clear_name('order_values'.  $foot . '_' . $group);

				if (isset($this->request->post[$name])) {
					$value = $this->request->post[$name];
				} else {
					$valtemp = $this->db->query("SELECT * FROM `order_values` WHERE order_values_order_id = '$order_id'");
					if ($valtemp->num_rows) {
						$value = $valtemp->row[$name];
					} else {
						$value = $default;
					}
				}

				echo '<select name="'.$name.'" id="'.$name.'"';
				if ($others) echo " $others";
				if (in_array($mygroup['lookup_table_types_type'],array(3))) echo ' onchange="do_equal_sides(this.id,'.$group.','.$fields->row['lookup_table_id'].')"';
				if ($tab) echo " tabindex='$tab' ";
				echo ">\n";

				if (!$value) $first = 1;
				else $first = 0;

				$first_optgroup = "";

				$first_id = 0;

				foreach($fields->rows as $field) {
					if(!$first_id) $first_id = $field['lookup_table_id'];
					if ($mygroup['lookup_table_types_type'] == 7 &&  !stristr($field['lookup_table_text'],$foot)) continue;

					if ($first_optgroup != $field['lookup_table_text'] && $mygroup['lookup_table_types_type'] == 2) {
						$first_optgroup = $field['lookup_table_text'];
						echo '<optgroup label="'.$first_optgroup.'">'."\n";
					}
					echo '<option value="'.$field['lookup_table_id'].'"';
					if ($first) { echo ' selected="selected"'; $first = 0; }
					if ($value == $field['lookup_table_id']) echo ' selected="selected"';
					echo '>'.$field['lookup_table_title'].'</option>'."\n"; echo $field['lookup_table_title'];
				}
				echo "</select>\n";
				if (in_array($mygroup['lookup_table_types_type'],array(3,6,7,8))) echo "</p>";
			}
			if (in_array($mygroup['lookup_table_types_type'],array(3,8,6,7))) echo '</div>';

			if (! $notitle && ($required && $value == $first_id)) {
				echo '<br clear="all"><div class="error">Required</div>'."\n";
			}
		} else return '';
	}

	public function shiftDeliveryDate() {

		// taking the default time zone
		date_default_timezone_set('America/Toronto');

		// pick up the current date according to the timezone
		$date = date("Y-m-d");

		switch ($date) {

			case "2021-12-24":
					$shippingDate = "2022-01-11";
					break;
			case "2021-12-25":
					$shippingDate = "2022-01-11";
					break;
			case "2021-12-26":
					$shippingDate = "2022-01-11";
					break;
			case "2021-12-27":
					$shippingDate = "2022-01-11";
					break;
			case "2021-12-28":
					$shippingDate = "2022-01-11";
					break;
			case "2021-12-29":
					$shippingDate = "2022-01-11";
					break;
			case "2021-12-30":
					$shippingDate = "2022-01-12";
					break;
			case "2021-12-31":
					$shippingDate = "2022-01-12";
					break;
			case "2022-1-1":
					$shippingDate = "2022-01-12";
					break;
			case "2022-1-2":
					$shippingDate = "2022-01-12";
					break;
			case "2022-1-3":
					$shippingDate = "2022-01-12";
					break;
			case "2022-1-4":
					$shippingDate = "2022-01-12";
					break;
			case "2022-1-5":
					$shippingDate = "2022-01-12";
					break;
		}

		return $shippingDate;

	}

	public function clear_name($val) {
		$val = strtolower(str_replace(array('-','/',"'",'"',' ','.','(mm)','(',')'),'_',$val));

		$val = preg_replace('/[_]+/', '_', $val);

		$exist = $this->db->query("SHOW COLUMNS FROM `order_values` LIKE '$val'");

		if (!$exist->num_rows) {
			$this->db->query("ALTER TABLE `order_values` ADD COLUMN $val VARCHAR(20) NOT NULL");
		}

		return $val;
	}

	public function print_radio ($group=1,$order_id = '',$required=0,$others='',$notitle='',$fname='',$default='') {
		$grtable = $this->db->query("SELECT * FROM lookup_table_types WHERE lookup_table_types_id = '$group'");
		if ($grtable->num_rows) {
			$mygroup = $grtable->row;
		} else return '';

		$fields = $this->db->query("SELECT * FROM lookup_table WHERE lookup_table_lookup_table_types_id = '$group' ORDER BY lookup_table_sort");

		if ($fields->num_rows) {
			if (!$notitle) {
				echo '<label style="float:left">';
				if ($required) {
					echo '<span class="required">*</span> ';
				}
				echo $mygroup['lookup_table_types_name']. ":</label>\n";
			}


			foreach($fields->rows as $field) {

//				$name = $this->clear_name('order_values_'. $field['lookup_table_title'] . '_' . $group);
				$name = ('order_values_'. $group);


				if (isset($this->request->post[$name])) {
					$value = $this->request->post[$name];
				} else {
					$valtemp = $this->db->query("SELECT * FROM `order_values` WHERE order_values_order_id = '$order_id'");
					if ($valtemp->num_rows && $valtemp->row[$name]) {
						$value = $valtemp->row[$name];
					} else {
						$value = $default;
					}
				}


				echo '<label '.$others.' ><input  style="width:auto" type="radio" name="'.$name.'" id="'.$name.'_'.$field['lookup_table_id'].'" value="'.$field['lookup_table_id'].'"';

				if ($value == $field['lookup_table_id']) echo ' checked="checked"';
				echo ' /> '.$field['lookup_table_title'].'</label>';
			}
			echo '<div class="error">';
			if (isset(${"error_$name"})) echo ${"error_$name"};
			echo "&nbsp;</div>\n";
		} else return '';
	}

	public function print_checkbox ($group=1,$order_id = '',$required=0,$others='',$notitle=0,$fname='') {

		$grtable = $this->db->query("SELECT * FROM lookup_table_types WHERE lookup_table_types_id = '$group'");
		if ($grtable->num_rows) {
			$mygroup = $grtable->row;
		} else return '';

		$fields = $this->db->query("SELECT * FROM lookup_table WHERE lookup_table_lookup_table_types_id = '$group' ORDER BY lookup_table_sort");

		if ($fields->num_rows) {

			if($mygroup['lookup_table_types_type'] != 9) {
				if (!$notitle) {
					echo '<label style="float:left">';
					if ($required) {
						echo '<span class="required">*</span> ';
					}
					echo $mygroup['lookup_table_types_name']. ":</label>\n";
				}

				echo "<div style='float:right;'>";
			} else {
				echo "<label class='leftcheck' $others>";
			}
			foreach($fields->rows as $field) {



				$name = $this->clear_name('order_values_'. $field['lookup_table_title'] . '_' . $group);


				if (isset($this->request->post[$name])) {
					$value = $this->request->post[$name];
				} else {
					$valtemp = $this->db->query("SELECT * FROM `order_values` WHERE order_values_order_id = '$order_id'");
					if ($valtemp->num_rows) {
						$value = $valtemp->row[$name];
					} else {
						$value = '';
					}
				}


				if($mygroup['lookup_table_types_type'] != 9) echo '<p>';
				if ($mygroup['lookup_table_types_type'] == 5) echo '<input style="width:40px" type="text" name="'.$name.'" id="'.$name.'" value="'.$value.'"';
				else {
					echo '<input style="width:auto" type="checkbox" name="'.$name.'" id="'.$name.'" value="'.$field['lookup_table_id'].'"';

					if ($value == $field['lookup_table_id']) echo ' checked="checked"';
				}
				if ($mygroup['lookup_table_types_type'] == 4 ) echo ' onclick="do_check_both(this.id,'.$group.');'.($group=='49'?'halux_splint();':'').'"';
				echo " />\n";
				if($mygroup['lookup_table_types_type'] != 9) echo '</p>';
			}
			if($mygroup['lookup_table_types_type'] != 9) {
				echo '</div>';
			} else {
				echo ' &nbsp; '.$mygroup['lookup_table_types_name']. "</label>\n";
			}

			if(!in_array($mygroup['lookup_table_types_type'], array(9)) && $mygroup['lookup_table_types_id'] != 61) {
				echo '<div class="error">';
				if (isset(${"error_$name"})) echo ${"error_$name"};
				echo "&nbsp;</div>\n";
			}
		} else return '';
	}


	public function getErrors($data,$error) { //call as $this->data, $this->error

		if (!is_array($data)) return '';
		$retarr = array();
		foreach ($data as $v) {
	 		if (isset($error[$v])) {
				$retarr['error_' . $v] = $error[$v];
			} else {
				$retarr['error_'.$v] = '';
			}

		}
		return $retarr;
	}

	public function getFormVars($data,$table) {

		if (!is_array($data)) return '';
		$retarr = array();
		foreach ($data as $v) {
			if (isset($this->request->post[$v])) {
				$retarr[$v] = $this->request->post[$v];
			} elseif (isset($table[$v])) {
				$retarr[$v] = $table[$v];
			} else {
				if (substr($v,-6) == 'status') $retarr[$v] = 1;
				else $retarr[$v] = '';
			}

		}
		return $retarr;
	}

	public function selectFromArray($name,$value=0,$data,$valfield, $txtfield,$dobfield,$others="",$addblank=""){
		$sel = "<select name='$name' id='$name' $others>";
		if ($addblank) $sel .= "<option value=''>$addblank</option>";
		foreach($data as $item) {
			$sel .= "<option value='".$item[$valfield]."'";
			if ($item[$valfield] == $value) $sel .=' selected="selected"';
			$sel .= ">".$item[$txtfield]." --- (".$item[$dobfield].")"."</option>";
		}
		$sel .= "</select>";
		return $sel;
	}

	public function selectFromSql($name,$value=0,$sql,$valfield, $txtfield,$others="",$addblank=""){
		$data = $this->db->query($sql);
		$sel = "<select name='$name' $others id='$name'>";
		if ($addblank) $sel .= "<option value='blank'>$addblank</option>";

                foreach($data->rows as $item) {
			$sel .= "<option value='".$item[$valfield]."'";
			if ($item[$valfield] == $value) $sel .=' selected="selected"';
			$sel .= ">".$item[$txtfield]."</option>";
		}

		$sel .= "</select>";
		return $sel;
	}


  	public function hasPermission($key, $value) {
    	if (isset($this->permission[$key])) {
	  		return in_array($value, $this->permission[$key]);
		} else {
	  		return FALSE;
		}
  	}

  	public function isLogged() {
    	return $this->user_id;
  	}

  	public function getId() {
    	return $this->user_id;
  	}

  	public function getUserName() {
    	return $this->username;
  	}
  	public function getUGstr() {
    	return $this->user_group_string;
  	}
  	public function getBroker() {
    	return strtolower($this->broker_code);
  	}
  	public function getLastLogin() {
    	return $this->session->data['last_login'];
  	}
	public function ch_date($str) {
		if ($str < '1' || $str == 'N/A') return '';
		$d = explode('-',$str);

		$fmt = (isset($d[1]) && strlen($d[1]) == 3 ? 'Y-m-d':'Y-M-d');
		return date($fmt,strtotime($str));
	}
}
?>
