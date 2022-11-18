<?php echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>" xml:lang="<?php echo $lang; ?>">
<head>
<title><?php echo $title; ?></title>
<base href="<?php echo $base; ?>" />
<meta name="author" content="drivingoutcomes.com, <?php echo php_uname('n');?>" />
<meta name="viewport" content="initial-scale=0.99,width=device-width">
<META HTTP-EQUIV="EXPIRES" CONTENT="Mon, 16 Feb 2022 11:12:01 GMT">
<?php foreach ($links as $link) { ?>
<link href="<?php echo $link['href']; ?>" rel="<?php echo $link['rel']; ?>" />
<?php } ?>
<link rel="stylesheet" type="text/css" href="view/stylesheet/stylesheet.css" />
<link rel="stylesheet" type="text/css" href="view/javascript/jquery/ui/themes/ui-lightness/ui.all.css" />
<?php foreach ($styles as $style) { ?>
<link rel="<?php echo $style['rel']; ?>" type="text/css" href="<?php echo $style['href']; ?>" media="<?php echo $style['media']; ?>" />
<?php } ?>
<?php /*?><link rel="stylesheet" href="view/template/catalog/paps/includes/paps.css" type="text/css">
<?php */?><script type="text/javascript" src="view/javascript/jquery/jquery-1.8.1.min.js"></script>
<script type="text/javascript" src="view/javascript/jquery/ui/ui.core.js"></script>
<script type="text/javascript" src="view/javascript/jquery/superfish/js/superfish.js"></script>
<script type="text/javascript" src="view/javascript/jquery/tab.js"></script>
<script type="text/javascript" src="view/javascript/ajax/ajax-shipping-number-save.js"></script>
<script type="text/javascript" src="view/javascript/ajax/ajax-patient-verification.js"></script>
<script type="text/javascript" src="view/javascript/ajax/ajax-items-pricing-save.js"></script>
<script type="text/javascript" src="view/javascript/ajax/ajax-restore-pricing-defaults.js"></script>
<script type="text/javascript" src="view/javascript/validations.js"></script>
<script src="https://kit.fontawesome.com/928f8b3886.js" crossorigin="anonymous"></script>
<?php foreach ($scripts as $script) { ?>
<script type="text/javascript" src="<?php echo $script; ?>"></script>
<?php } ?>
<script type="text/javascript">
//-----------------------------------------
// Confirm Actions (delete, uninstall)
//-----------------------------------------
$(document).ready(function(){
	$(function() {
  		$("input:visible:enabled:not(input[class*='datex']):first").focus();
	});

    // Confirm Delete
    $('#form').submit(function(){

        if ($(this).attr('action').indexOf('delete',1) != -1) {
            if (!confirm ('<?php echo $text_confirm; ?>')) {
                return false;
            }
			return true;
		} if ($(this).attr('action').indexOf('packingslip',1) != -1) {
            if (!confirm ('<?= "This will create a packing slip with the selected orders. The selected orders will be marked in the Delivery section with a link to this slip. This cannot be undone and it will overwrite any previous packing slips for the selected orders. Do you still want to do this?" ?>')) {
                return false;
            }
			return true;
		} else if ($(this).attr('action').indexOf('print_labels',1) != -1) {
			return true;
        } else if(typeof(filter) == "function"){
			filter();
			return false;
		} else return true;
    });

    // Confirm Uninstall
    $('a').click(function(){
        if ($(this).attr('href') != null && $(this).attr('href').indexOf('uninstall',1) != -1) {
            if (!confirm ('<?php echo $text_confirm; ?>')) {
                return false;
            }
        }
    });
});
</script>
<script type="text/javascript">
$(document).ready(function(){
  $(".list tr:even").css("background-color", "#e0f0fe");


    $(".scrollbox").each(function(i) {
    	$(this).attr('id', 'scrollbox_' + i);
		sbox = '#' + $(this).attr('id');
    	$(this).after('<div style="float:right"><a onclick="$(\'' + sbox + ' :checkbox\').attr(\'checked\', \'checked\');"><?php echo $text_select_all; ?></a> | <a onclick="$(\'' + sbox + ' :checkbox\').attr(\'checked\',false);"><?php echo $text_unselect_all; ?></a></div>');
	});
});
</script>

	<link href="css/html5.css" rel="stylesheet" type="text/css">

</head>
<body>



	<div class="container">
		<div class="header">
<?php if ($logged) { ?>
			<div class="topline">
				<div class="hdrtext"><?php echo $top_header_text; ?></div>
				<div class="hdruser"><?php echo $this->user->getUserName();?> &nbsp; </div>
				<div class="hdrsmall"><?php echo $top_header_login; ?><?php echo $this->user->getLastLogin();?></div>
			</div>
<?php } ?>
			<div class="topmenu">

<?php if ($logged) {
	$urlx = HTTPS_SERVER . 'index.php?route=user/user/update&token=' . $this->session->data['token'] . '&user_id=' . $this->user->getID() ;
?>
            <a href="<?php echo $urlx; ?>"><?php echo $top_header_myaccount; ?></a> <a href="<?php echo $logout; ?>"><?php echo $top_header_signout; ?></a>
<?php } ?>

            <a href="https://soundorthotics.com/clinicians/help-desk/contact-us/" target="_blank"><?php echo $top_header_contact; ?></a>
<?php if ($this->user->getUGstr() == '1') {
	$urlx = HTTPS_SERVER . 'index.php?route=setting/setting&token=' . $this->session->data['token'];
?><a href="<?php echo $urlx; ?>"><img src="view/image/setting.png" width="17" /></a>

<?php } ?>

			</div>
			<div class="cl"></div>
			<div class="logo"> <a href="http://soundorthotics.com" target="_blank"><img src="images/logo.png" alt="SoundorthoticsLogo" name="Insert_logo" width="231" height="97" id="id_logo" /></a> </div>

<?php if ($logged) {  ?>

	<div class="mainmenu">
		<div class="homebutton" onclick="window.location='<?php echo $home; ?>'" style="cursor: pointer;">
  		<span class="fa-layers fa-fw" style="margin-right: 15px;">
				<i class="fa-solid fa-house fa-3x"></i>
    		<span class="fa-layers-text fa-inverse" data-fa-transform="shrink-8 down-5 left-5" style="font-weight:900"><?php echo $nav_home; ?></span>
			</span>
		</div>
		<div class="homebutton" onclick="window.location='<?php echo $order; ?>'" style="cursor: pointer;">
  		<span class="fa-layers fa-fw" style="margin-right: 15px;">
				<i class="fa-solid fa-cart-shopping fa-3x"></i>
    		<span class="fa-layers-text fa-inverse" data-fa-transform="shrink-8 down-5 left-5" style="font-weight:900"><?php echo $nav_orders; ?></span>
			</span>
		</div>
		<div class="homebutton" onclick="window.location='<?php echo $user; ?>'" style="cursor: pointer;">
  		<span class="fa-layers fa-fw" style="margin-right: 15px;">
				<i class="fa-solid fa-user-group fa-3x"></i>
    		<span class="fa-layers-text fa-inverse" data-fa-transform="shrink-8 down-5 left-5" style="font-weight:900"><?php echo $nav_users; ?></i>
			</span>
		</div>
		<div class="homebutton" onclick="window.location='<?php echo $clinic; ?>'" style="cursor: pointer;">
  		<span class="fa-layers fa-fw" style="margin-right: 15px;">
				<i class="fa-solid fa-suitcase-medical fa-3x" data-fa-transform="shrink-8 up-6"></i>
    		<span class="fa-layers-text fa-inverse" data-fa-transform="shrink-8 down-5 left-5" style="font-weight:900"><?php echo $nav_clinics; ?></span>
			</span>
		</div>
		<div class="homebutton" onclick="window.location='<?php echo $patient; ?>'" style="cursor: pointer;">
  		<span class="fa-layers fa-fw" style="margin-right: 15px;">
				<i class="fa-solid fa-user-group fa-3x"></i>
    		<span class="fa-layers-text fa-inverse" data-fa-transform="shrink-8 down-5 left-5" style="font-weight:900"><?php echo $nav_patients; ?></span>
			</span>
		</div>
		<div class="homebutton" onclick="window.location='<?php echo $order; ?>'" style="cursor: pointer;">
  		<span class="fa-layers fa-fw" style="margin-right: 15px;">
				<i class="fa-solid fa-chart-bar fa-3x"></i>
    		<span class="fa-layers-text fa-inverse" data-fa-transform="shrink-8 down-5 left-5" style="font-weight:900"><?php echo $nav_reports; ?></span>
			</span>
		</div>
	</div>

<?php } ?>
		</div>
<?php if ($logged) { ?>

<?php if ($breadcrumbs) { ?>
<div class="breadcrumb-so">
  <?php foreach ($breadcrumbs as $breadcrumb) { ?>
  <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
  <?php } ?>
  <div class="tracking"><a class="" href="<?php echo $tracking; ?>" class="" title="Tracking"><span><?php echo $breadcrumbs_tracking; ?></span></a> </div>
</div>

<?php } ?>

	<div class="submenu">
<?php
$topx = $this->request->isget('route');

if (substr($topx,0,9) == 'user/user') { ?>
	<a href="<?php echo $user;?>"><?php echo $breadcrumbs_users; ?></a> |
	<a href="<?php echo $user_group;?>"><?php echo $breadcrumbs_user_groups; ?></a>
<?php
} elseif(substr($topx,0,11) == 'sale/clinic') {
	$url = HTTPS_SERVER . 'index.php?route=sale/clinician&token=' . $this->session->data['token'];
?>
	<a href="<?php echo $clinic;?>"><?php echo $breadcrumbs_clinics; ?></a> |
	<a href="<?php echo $url;?>"><?php echo $breadcrumbs_clinicians; ?></a>
<?php
} /*elseif($topx == 'sale/clinic') {
	$url = HTTPS_SERVER . 'index.php?route=catalog/category/insert&token=' . $this->session->data['token'];
?>
	<a href="<?php echo $clinic;?>"><?php echo $breadcrumbs_patients; ?></a> |
	<a href="<?php echo $url;?>"><?php echo $breadcrumbs_add_patient; ?></a>
<?php
} */?>
    </div>
<?php } ?>


		<div class="content">




<?php if ($logged) { ?>

<script type="text/javascript" src="view/javascript/hc_header_1.js"></script>

<?php } ?>
