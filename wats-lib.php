<?php

/*****************************************/
/*                                       */
/* Fonction de log d'un message de debug */
/*                                       */
/*****************************************/

function wats_debug($msg)
{
	if (WATS_DEBUG)
	{
	    $today = date("d/m/Y H:i:s ");
	    $myFile = dirname(__file__) . "/debug.log";
	    $fh = fopen($myFile, 'a') or die("Can't open debug file. Please manually create the 'debug.log' file (inside the 'mynet' directory) and make it writable.");
	    $ua_simple = preg_replace("/(.*)\s\(.*/","\\1",$_SERVER['HTTP_USER_AGENT']);
	    fwrite($fh, $today . " [from: ".$_SERVER['REMOTE_ADDR']."|$ua_simple] - " . $msg . "\n");
	    fclose($fh);
	}
}

/*******************************************/
/*                                         */
/* Fonction de vérification d'un numérique */
/*                                         */
/*******************************************/

function wats_is_numeric($i)
{
	return (preg_match("/^\d+$/", $i));
}

/*****************************************/
/*                                       */
/* Fonction de vérification d'une chaîne */
/*                                       */
/*****************************************/

function wats_is_string($i)
{
	return (preg_match("/^[\.\,\;\'\"ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿa-zA-Z-\d ]+$/", $i));
}

/****************************************************/
/*                                                  */
/* Fonction de vérification d'une lettre ou chiffre */
/*                                                  */
/****************************************************/

function wats_is_letter_or_number($i)
{

	return (preg_match("/^[a-zA-Z\d]+$/", $i));
}

/*****************************************/
/*                                       */
/* Fonction de vérification d'une lettre */
/*                                       */
/*****************************************/

function wats_is_letter($i)
{
	return (preg_match("/^[a-zA-Z]+$/", $i));
}

/******************************************/
/*                                        */
/* Fonction de calcul du numéro de ticket */
/*                                        */
/******************************************/

function wats_get_ticket_number($postID)
{
	global $wats_settings;
	
	$number = get_post_meta($postID,'wats_ticket_number',true);
	if (($wats_settings['numerotation'] == 0) || !$number)
		$value = 0;
	else if ($wats_settings['numerotation'] == 1)
	{
		$padding = '';
		if ($number < 10)
			$padding = '0000';
		else if ($number < 100)
			$padding = '000';
		else if ($number < 1000)
			$padding = '00';
		else if ($number < 10000)
			$padding = '0';
		$value = get_the_time("ymd",$postID)."-".$padding.$number;
	}
	else if ($wats_settings['numerotation'] == 2)
		$value = $number;
		
	return $value;
}

/********************************************************/
/*                                                      */
/* Fonction de récupération du dernier numéro de ticket */
/*                                                      */
/********************************************************/

function wats_get_latest_ticket_number()
{
	global $wpdb;
	
	$value = $wpdb->get_var("SELECT meta_value FROM $wpdb->postmeta WHERE meta_key = 'wats_ticket_number' ORDER BY post_id DESC LIMIT 0,1");

	if (!$value)
		$value = 0;
	
	return $value;
}

/*******************************************/
/*                                         */
/* Fonction de calcul du nombre de tickets */
/*                                         */
/*******************************************/

function wats_get_number_of_tickets()
{
	global $wpdb;
	
	$value = $wpdb->get_var("SELECT COUNT(*) FROM $wpdb->postmeta WHERE meta_key = 'wats_ticket_number'");

	if (!$value)
		$value = 0;
	
	return $value;
}

/*********************************************************************/
/*                                                                   */
/* Fonction de calcul du nombre de tickets par status et utilisateur */
/*                                                                   */
/*********************************************************************/

function wats_get_number_of_tickets_by_status($status,$userid)
{
	global $wpdb;
	
	$query = "SELECT COUNT(*) FROM $wpdb->posts";
	if ($status != 0)
		$query .= " LEFT JOIN $wpdb->postmeta ON ($wpdb->posts.ID = $wpdb->postmeta.post_id) WHERE  $wpdb->posts.post_type = 'ticket' AND $wpdb->postmeta.meta_key = 'wats_ticket_status' AND $wpdb->postmeta.meta_value = $status AND $wpdb->posts.post_status = 'publish'";
	else
		$query .= " WHERE  $wpdb->posts.post_type = 'ticket' AND $wpdb->posts.post_status = 'publish'";
		
	if ($userid != 0)
		$query .= " AND $wpdb->posts.post_author = $userid";

	$value = $wpdb->get_var($query);

	if (!$value)
		$value = 0;
	
	return $value;
}

/*****************************************************/
/*                                                   */
/* Fonction de récupération de l'ID du status Closed */
/*                                                   */
/*****************************************************/

function wats_get_closed_status_id()
{
	global $wats_settings;
	
	$wats_ticket_status = $wats_settings['wats_statuses'];

	$closed = 0;
	foreach ($wats_ticket_status as $key => $value)
	{
		if ($wats_ticket_status[$key] == "Closed")
			$closed = $key;
	}
	
	return $closed;
}

?>