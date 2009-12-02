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
	    $fh = fopen($myFile, 'a') or die("Can't open debug file. Please manually create the 'debug.log' file (inside the 'wats' directory) and make it writable.");
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
	return (preg_match("/^[\.\,\;\'\"ÀÁÂÃÄÅÇČĎĚÈÉÊËÌÍÎÏŇÒÓÔÕÖŘŠŤÙÚÛÜŮÝŽàáâãäåçčďěèéêëìíîïňðòóôõöřšťùúûüůýÿža-zA-Z-\d ]+$/", $i));
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

/****************************************************************************/
/*								     										*/
/* Fonction de construction d'une liste d'utilisateurs ayant une capabilité */
/* - Type 0 : user ID  			    									    */
/* - Type 1 : user login			    								    */
/*									    									*/
/****************************************************************************/

function wats_get_user_list_with_cap($cap,$type)
{
	global $wpdb;

    $users = $wpdb->get_results("SELECT ID FROM `{$wpdb->prefix}users`");

	$list = array();
    foreach ($users AS $user)
    {
        $my_user = new WP_User($user->ID);
        if ($my_user->has_cap($cap))
		{
			if ($type == 0)
				$list[] = $my_user->ID;
			else
				$list[] = $my_user->user_login;
		}
    }

    return $list;
}

/*****************************************************/
/*                                                   */
/* Fonction de remplissage de la table des capacités */
/*                                                   */
/*****************************************************/

function wats_init_capabilities_table()
{
	
	$wats_capabilities_table = array();
	$wats_capabilities_table['wats_ticket_ownership'] = __('Tickets can be assigned to this user','WATS');
	
	return ($wats_capabilities_table);
}

/*********************************************************/
/*                                                       */
/* Fonction de remplissage de la table des notifications */
/*                                                       */
/*********************************************************/

function wats_init_notification_table()
{
	
	$wats_notification_table = array();
	$wats_notification_table['new_ticket_notification_admin'] = __('Get a mail notification when a new ticket is submitted (admin only)','WATS');
	$wats_notification_table['ticket_update_notification_all_tickets'] = __('Get a mail notification when any ticket is updated','WATS');
	$wats_notification_table['ticket_update_notification_my_tickets'] = __('Get a mail notification when a ticket originated by me is updated','WATS');
	
	return ($wats_notification_table);
}

/***********************************************************/
/*                                                         */
/* Fonction included for backward compatibility before 2.8 */
/*                                                         */
/***********************************************************/

if (!function_exists('esc_attr'))
{
function esc_attr($text) 
{
	$safe_text = wp_check_invalid_utf8($text);
    $safe_text = _wp_specialchars($safe_text, ENT_QUOTES);
    
	return apply_filters('attribute_escape', $safe_text, $text);
}
}

/***********************************************************/
/*                                                         */
/* Fonction included for backward compatibility before 2.8 */
/*                                                         */
/***********************************************************/

if (!function_exists('_wp_specialchars'))
{
function _wp_specialchars( $string, $quote_style = ENT_NOQUOTES, $charset = false, $double_encode = false ) {
	$string = (string) $string;

	if ( 0 === strlen( $string ) ) {
		return '';
	}

	// Don't bother if there are no specialchars - saves some processing
	if ( !preg_match( '/[&<>"\']/', $string ) ) {
		return $string;
	}

	// Account for the previous behaviour of the function when the $quote_style is not an accepted value
	if ( empty( $quote_style ) ) {
		$quote_style = ENT_NOQUOTES;
	} elseif ( !in_array( $quote_style, array( 0, 2, 3, 'single', 'double' ), true ) ) {
		$quote_style = ENT_QUOTES;
	}

	// Store the site charset as a static to avoid multiple calls to wp_load_alloptions()
	if ( !$charset ) {
		static $_charset;
		if ( !isset( $_charset ) ) {
			$alloptions = wp_load_alloptions();
			$_charset = isset( $alloptions['blog_charset'] ) ? $alloptions['blog_charset'] : '';
		}
		$charset = $_charset;
	}
	if ( in_array( $charset, array( 'utf8', 'utf-8', 'UTF8' ) ) ) {
		$charset = 'UTF-8';
	}

	$_quote_style = $quote_style;

	if ( $quote_style === 'double' ) {
		$quote_style = ENT_COMPAT;
		$_quote_style = ENT_COMPAT;
	} elseif ( $quote_style === 'single' ) {
		$quote_style = ENT_NOQUOTES;
	}

	// Handle double encoding ourselves
	if ( !$double_encode ) {
		$string = wp_specialchars_decode( $string, $_quote_style );
		$string = preg_replace( '/&(#?x?[0-9a-z]+);/i', '|wp_entity|$1|/wp_entity|', $string );
	}

	$string = @htmlspecialchars( $string, $quote_style, $charset );

	// Handle double encoding ourselves
	if ( !$double_encode ) {
		$string = str_replace( array( '|wp_entity|', '|/wp_entity|' ), array( '&', ';' ), $string );
	}

	// Backwards compatibility
	if ( 'single' === $_quote_style ) {
		$string = str_replace( "'", '&#039;', $string );
	}

	return $string;
}
}

/***********************************************************/
/*                                                         */
/* Fonction included for backward compatibility before 2.8 */
/*                                                         */
/***********************************************************/

if (!function_exists('esc_html'))
{
function esc_html( $text ) {
	$safe_text = wp_check_invalid_utf8( $text );
	$safe_text = _wp_specialchars( $safe_text, ENT_QUOTES );
	return apply_filters( 'esc_html', $safe_text, $text );
    return $text;
}
}

/***********************************************************/
/*                                                         */
/* Fonction included for backward compatibility before 2.8 */
/*                                                         */
/***********************************************************/

if (!function_exists('esc_url'))
{
function esc_url( $url, $protocols = null ) {
	return clean_url( $url, $protocols, 'display' );
}
}

/***********************************************************/
/*                                                         */
/* Fonction included for backward compatibility before 2.8 */
/*                                                         */
/***********************************************************/

if (!function_exists('esc_attr_e'))
{
function esc_attr_e( $text, $domain = 'default' ) {
    echo esc_attr( translate( $text, $domain ) );
}
}

/***********************************************************/
/*                                                         */
/* Fonction included for backward compatibility before 2.8 */
/*                                                         */
/***********************************************************/

if (!function_exists('esc_js'))
{
function esc_js( $text ) {
	$safe_text = wp_check_invalid_utf8( $text );
	$safe_text = _wp_specialchars( $safe_text, ENT_COMPAT );
	$safe_text = preg_replace( '/&#(x)?0*(?(1)27|39);?/i', "'", stripslashes( $safe_text ) );
	$safe_text = preg_replace( "/\r?\n/", "\\n", addslashes( $safe_text ) );
	return apply_filters( 'js_escape', $safe_text, $text );
}
}

?>