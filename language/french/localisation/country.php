<?php
// Heading
$_['heading_title']          = 'Pays';

// Text
$_['text_success']           = 'Succès : vous avez modifié les pays !';

// Column
$_['column_name']            = 'Nom du pays';
$_['column_iso_code_2']      = 'Norme ISO (2)';
$_['column_iso_code_3']      = 'Norme ISO (3)';
$_['column_action']          = 'Action';

// Entry
$_['entry_status']           = 'Statut du pays:';
$_['entry_name']             = 'Nom du pays:';
$_['entry_iso_code_2']       = 'Norme ISO (2):';
$_['entry_iso_code_3']       = 'Norme ISO (3):';
$_['entry_address_format']   = 'Format d\'adresse :<br /><span class="help">
Prénom = {patient_firstname}<br />
Nom = {patient_lastname}<br />
Entreprise = {entreprise}<br />
Adresse 1 = {adresse_1}<br />
Adresse 2 = {adresse_2}<br />
Ville = {ville}<br />
Code postal = {code postal}<br />
Zone = {zone}<br />
Code de zone = {code_zone}<br />
Pays = {country}</span>';
$_['entry_postcode_required']= 'Code postal requis:';

// Error
$_['error_permission']       = 'Attention : Vous n\'êtes pas autorisé à modifier les pays !';
$_['error_name']             = 'Le nom du pays doit comporter entre 3 et 128 caractères !';
$_['error_default']          = 'Attention : ce pays ne peut pas être supprimé car il est actuellement défini comme pays de magasin par défaut !';
$_['error_store']            = 'Attention : ce pays ne peut pas être supprimé car il est actuellement attribué à %s magasins !';
$_['error_address']          = 'Attention : ce pays ne peut pas être supprimé car il est actuellement attribué à %s entrées du carnet d\'adresses !';
$_['error_zone']             = 'Attention : ce pays ne peut pas être supprimé car il est actuellement affecté à %s zones !';
$_['error_zone_to_geo_zone'] = 'Attention : ce pays ne peut pas être supprimé car il est actuellement affecté à %s zones vers des zones géographiques !';
?>
