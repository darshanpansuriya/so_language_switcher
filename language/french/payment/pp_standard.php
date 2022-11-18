<?php
// En-tête
$_['heading_title'] = 'PayPal standard' ;

// Texte
$_['text_payment'] = 'Paiement' ;
$_['text_success'] = 'Succès : Vous avez modifié les détails du compte PayPal !';
$_['text_pp_standard'] = '<a onclick="window.open(\'https://www.paypal.com/uk/mrb/pal=W9TBB5DTD6QJW\');"><img src="view/ image/paiement/paypal.png" alt="PayPal" title="PayPal" style="border : 1px solide #EEEEEE ;" /></a>' ;
$_['text_authorization'] = 'Autorisation' ;
$_['text_sale'] = 'Vente' ;

// Entrée
$_['entry_email'] = 'Courriel :';
$_['entry_test'] = 'Mode bac à sable :';
$_['entry_transaction'] = 'Méthode de transaction :';
$_['entry_geo_zone'] = 'Zone géographique :' ;
$_['entry_status'] = 'Statut :';
$_['entry_sort_order'] = 'Ordre de tri :' ;
$_['entry_pdt_token'] = 'PDT Token :<br/><span class="help">Le jeton de transfert de données de paiement est utilisé pour plus de sécurité et de fiabilité. Découvrez comment activer PDT <a href="https://cms.paypal.com/us/cgi-bin/?&cmd=_render-content&content_ID=developer/howto_html_paymentdatatransfer" alt="">ici</a></ étendue>' ;
$_['entry_itemized'] = 'Énumérer les produits :<br/><span class="help">Afficher la liste détaillée des produits sur la facture Paypal au lieu du nom du magasin.</span>' ;
$_['entry_debug'] = 'Mode de débogage :<br/><span class="help">Enregistre des informations supplémentaires dans le journal système.</span>' ;
$_['entry_order_status'] = 'Statut de la commande terminée :<br /><span class="help">Ceci est le statut défini lorsque le paiement a été effectué avec succès.</span>';
$_['entry_order_status_pending'] = 'État de la commande en attente :<br /><span class="help">Le paiement est en attente ; voir la variable pending_reason pour plus d\'informations. Veuillez noter que vous recevrez une autre notification de paiement instantané lorsque le statut du paiement passera à Terminé, Échec ou Refusé.</span>' ;
$_['entry_order_status_denied'] = 'Statut de la commande refusé :<br /><span class="help">Vous, le marchand, avez refusé le paiement. Cela ne se produira que si le paiement était précédemment en attente pour l\'une des raisons en attente suivantes.</span>';
$_['entry_order_status_failed'] = 'Échec de l\'état de la commande :<br /><span class="help">Le paiement a échoué. Cela ne se produira que si le paiement a été tenté à partir du compte bancaire de votre clinique.</span>';
$_['entry_order_status_refunded'] = 'Statut de la commande remboursé :<br /><span class="help">Vous, le marchand, avez remboursé le paiement.</span>' ;
$_['entry_order_status_canceled_reversal'] = 'Statut de la commande Annulé Annulation :<br /><span class="help">Cela signifie qu\'une annulation a été annulée ; par exemple, vous, le commerçant, avez gagné un litige avec la clinique et les fonds de la transaction qui a été annulée vous ont été restitués.</span>' ;
$_['entry_order_status_reversed'] = 'Statut de la commande annulé :<br /><span class="help">Cela signifie qu\'un paiement a été annulé en raison d\'une rétrofacturation ou d\'un autre type d\'annulation. Les fonds ont été débités du solde de votre compte et retournés à la clinique. La raison de l\'inversion est donnée par la variable reason_code.</span>';
$_['entry_order_status_unspecified'] = 'Erreur de statut de commande non spécifié :' ;

// Erreur
$_['error_permission'] = 'Attention : Vous n\'êtes pas autorisé à modifier le paiement PayPal !';
$_['error_email'] = 'E-mail requis !';
?>
