<?php

/**************************************/
/*                                    */
/* Fonction de chargement des options */
/*                                    */
/**************************************/

function wats_load_settings()
{
    global $wats_settings, $wats_default_ticket_status, $wats_version, $wats_default_ticket_priority, $wats_default_ticket_type;

    if (!get_option('wats'))
	{
		foreach ($wats_default_ticket_status as $key => $value)
		{
			$default['wats_statuses'][$key] = __($value,'WATS');
		}
		foreach ($wats_default_ticket_priority as $key => $value)
		{
			$default['wats_priorities'][$key] = __($value,'WATS');
		}
		foreach ($wats_default_ticket_type as $key => $value)
		{
			$default['wats_types'][$key] = __($value,'WATS');
		}

		$default['numerotation'] = 0;
		$default['wats_version'] = $wats_version;
		$default['wats_guest_user'] = -1;
		$default['wats_home_display'] = 1;
		$default['visibility'] = 0;
		$default['ticket_assign'] = 0;
		$default['ticket_assign_level'] = 0;
		$default['new_ticket_notification_admin'] = 0;
		$default['comment_menuitem_visibility'] = 0;
		$default['tickets_tagging'] = 0;
		$default['tickets_custom_fields'] = 0;
		$default['dashboard_stats_widget_level'] = 0;
		$default['ticket_edition_media_upload'] = 0;
		$default['ticket_edition_media_upload_tabs'] = 0;
		$default['ticket_assign_user_list'] = 0;
		$default['ticket_update_notification_all_tickets'] = 0;
		$default['ticket_update_notification_my_tickets'] = 0;
		$default['call_center_ticket_creation'] = 0;
		$default['user_selector_format'] = 'user_login';
		$default['filter_ticket_listing'] = 0;
		$default['filter_ticket_listing_meta_key'] = 'None';
		$default['meta_column_ticket_listing'] = 0;
		$default['meta_column_ticket_listing_meta_key'] = 'None';
		$default['notification_signature'] = 'Regads,<br /><br />WATS Notification engine';
		$default['user_selector_order_1'] = 'last_name';
		$default['user_selector_order_2'] = 'first_name';
		$default['frontend_submit_form_access'] = 0;
		$default['frontend_submit_form_ticket_status'] = 0;
		$default['submit_form_default_author'] = wats_get_first_admin_login();
		$default['ms_ticket_submission'] = 0;
		$default['ms_mail_server'] = 'mail.example.com';
		$default['ms_port_server'] = '110';
		$default['ms_mail_address'] = 'login@example.com';
		$default['ms_mail_password'] = 'password';
		$closed = 0;
		foreach ($wats_default_ticket_status as $key => $value)
		{
			if ($value == "Closed")
				$closed = $key;
		}
		$default['closed_status_id'] = $closed;
		$default['ticket_notification_bypass_mode'] = 0;
								
   	    add_option('wats', $default);
	}
        
    $wats_settings = get_option('wats');

	// Mise à jour des options après installation d'une nouvelle version
	if ($wats_settings['wats_version'] != $wats_version)
	{
		if (!isset($wats_settings['wats_home_display']))
		{
			$wats_settings['wats_home_display'] = 1;
		}

		if (!isset($wats_settings['visibility']))
		{
			$wats_settings['visibility'] = 0;
		}

		if (!isset($wats_settings['ticket_assign']))
		{
			$wats_settings['ticket_assign'] = 0;
		}

		if (!isset($wats_settings['ticket_assign_level']))
		{
			$wats_settings['ticket_assign_level'] = 0;
		}
		
		if (!isset($wats_settings['new_ticket_notification_admin']))
		{
			$wats_settings['new_ticket_notification_admin'] = 0;
		}
		
		if (!isset($wats_settings['comment_menuitem_visibility']))
		{
			$wats_settings['comment_menuitem_visibility'] = 0;
		}
		
		if (!isset($wats_settings['tickets_tagging']))
		{
			$wats_settings['tickets_tagging'] = 0;
		}
		
		if (!isset($wats_settings['tickets_custom_fields']))
		{
			$wats_settings['tickets_custom_fields'] = 0;
		}
		
		if (!isset($wats_settings['dashboard_stats_widget_level']))
		{
			$wats_settings['dashboard_stats_widget_level'] = 0;
		}
		
		if (!isset($wats_settings['ticket_edition_media_upload']))
		{
			$wats_settings['ticket_edition_media_upload'] = 0;
		}
		
		if (!isset($wats_settings['ticket_edition_media_upload_tabs']))
		{
			$wats_settings['ticket_edition_media_upload_tabs'] = 0;
		}
		
		if (!isset($wats_settings['ticket_assign_user_list']))
		{
			$wats_settings['ticket_assign_user_list'] = 0;
		}
		
		if (!isset($wats_settings['ticket_update_notification_all_tickets']))
		{
			$wats_settings['ticket_update_notification_all_tickets'] = 0;
		}
		
		if (!isset($wats_settings['ticket_update_notification_my_tickets']))
		{
			$wats_settings['ticket_update_notification_my_tickets'] = 0;
		}
		
		if (!isset($wats_settings['call_center_ticket_creation']))
		{
			$wats_settings['call_center_ticket_creation'] = 0;
		}
		
		if (!isset($wats_settings['user_selector_format']))
		{
			$wats_settings['user_selector_format'] = 'user_login';
		}
		
		if (!isset($wats_settings['filter_ticket_listing']))
		{
			$wats_settings['filter_ticket_listing'] = 0;
		}
		
		if (!isset($wats_settings['filter_ticket_listing_meta_key']))
		{
			$wats_settings['filter_ticket_listing_meta_key'] = 'None';
		}
		
		if (!isset($wats_settings['meta_column_ticket_listing']))
		{
			$wats_settings['meta_column_ticket_listing'] = 0;
		}
		
		if (!isset($wats_settings['meta_column_ticket_listing_meta_key']))
		{
			$wats_settings['meta_column_ticket_listing_meta_key'] = 'None';
		}
		
		if (!isset($wats_settings['notification_signature']))
		{
			$wats_settings['notification_signature'] = 'Regads,<br /><br />WATS Notification engine';
		}
		
		if (!isset($wats_settings['user_selector_order_1']))
		{
			$wats_settings['user_selector_order_1'] = 'last_name';
		}
		
		if (!isset($wats_settings['user_selector_order_2']))
		{
			$wats_settings['user_selector_order_2'] = 'first_name';
		}
		
		if (!isset($wats_settings['frontend_submit_form_access']))
		{
			$wats_settings['frontend_submit_form_access'] = 0;
		}
		
		if (!isset($wats_settings['frontend_submit_form_ticket_status']))
		{
			$wats_settings['frontend_submit_form_ticket_status'] = 0;
		}

		if (!isset($wats_settings['frontend_submit_form_ticket_status']))
		{
			$wats_settings['frontend_submit_form_ticket_status'] = 0;
		}		

		if (!isset($wats_settings['submit_form_default_author']))
		{
			$wats_settings['submit_form_default_author'] = wats_get_first_admin_login();
		}

		if (!isset($wats_settings['ms_ticket_submission']))
		{
			$wats_settings['ms_ticket_submission'] = 0;
		}
		
		if (!isset($wats_settings['ms_mail_server']))
		{
			$wats_settings['ms_mail_server'] = 'mail.example.com';
		}
		
		if (!isset($wats_settings['ms_port_server']))
		{
			$wats_settings['ms_port_server'] = '110';
		}
		
		if (!isset($wats_settings['ms_mail_address']))
		{
			$wats_settings['ms_mail_address'] = 'login@example.com';
		}
		
		if (!isset($wats_settings['ms_mail_password']))
		{
			$wats_settings['ms_mail_password'] = 'password';
		}
		
		if (!isset($wats_settings['closed_status_id']))
		{
			$wats_ticket_status = $wats_settings['wats_statuses'];
			$closed = 0;
			foreach ($wats_ticket_status as $key => $value)
			{
				if ($value == "Closed")
					$closed = $key;
			}
			$wats_settings['closed_status_id'] = $closed;
		}
		
		if (!isset($wats_settings['ticket_notification_bypass_mode']))
		{
			$wats_settings['ticket_notification_bypass_mode'] = 0;
		}
				
		$wats_settings['wats_version'] = $wats_version;
		update_option('wats', $wats_settings);
	}
	
	return;
}

/*********************************************/
/*                                           */
/* Fonction Ajax de mise à jour d'une option */
/*                                           */
/*********************************************/

function wats_admin_update_option_entry()
{
	global $wats_settings;

	wats_load_settings();
	$idvalue = stripslashes_deep($_POST[idvalue]);
	$idprevvalue = stripslashes_deep($_POST[idprevvalue]);
	$idtable = $_POST[idtable];
	
	check_ajax_referer('update-wats-options');
	
	if (strlen($_POST[idvalue]) == 0)
	{
		$message_result = array('id' => "", 'idvalue' => "",'success' => "FALSE", 'error' => __("Error : please enter an entry!",'WATS'));
	}
	else
    {
		$res = 0;
		switch($idtable)
		{
			case "tabletype" : $type = "wats_types"; break;
			case "tablepriority" : $type = "wats_priorities"; break;
			case "tablestatus" : $type = "wats_statuses"; break;
			default : $res = 1; break;
		}
		
		if ($res == 1)
			$message_result = array('id' => "", 'idvalue' => "",'success' => "FALSE", 'error' => __("Error : please enter an entry!",'WATS'));
		else
		{
			$wats_options = $wats_settings[$type];
			foreach ($wats_options as $key => $value)
			{
				if ($value == $idprevvalue)
					$res = $key;
			}
			
			foreach ($wats_options as $key => $value)
			{
				if ($value == $idvalue)
					$res = -1;
			}

			if ($res == 0)
			{
				$message_result = array('id' => "", 'idvalue' => "",'success' => "FALSE", 'error' => __("Error : entry not found!",'WATS'));
			}
			else if ($res == -1)
			{
				$message_result = array('id' => "", 'idvalue' => "",'success' => "FALSE", 'error' => __("Error : another entry has the same value!",'WATS'));
			}
			else
			{
				$wats_options[$res] = $idvalue;
				$wats_settings[$type] = $wats_options;
				update_option('wats', $wats_settings);
				$message_result = array('id' => "", 'idvalue' => $idvalue,'success' => "TRUE", 'error' => __("Entry successfully updated!",'WATS'));
			}
        }
	}
	
	echo json_encode($message_result);
	exit;
}

/*********************************************/
/*                                           */
/* Fonction Ajax de suppression d'une option */
/*                                           */
/*********************************************/

function wats_admin_remove_option_entry()
{
	global $wats_settings;

	$idvalue = stripslashes_deep($_POST[idvalue]);
	$type = $_POST[type];
	
	check_ajax_referer('update-wats-options');
	wp_cache_flush();
	wats_load_settings();
	$wats_options = $wats_settings[$type];
	if ($wats_options[$idvalue])
	{
		unset($wats_options[$idvalue]);
		$wats_settings[$type] = $wats_options;
		update_option('wats', $wats_settings);
		$message_result = array('id' => $idvalue,'success' => "TRUE", 'error' => __("Entry successfully removed!",'WATS'));
	}
	else
	{
		$message_result = array('id' => $idvalue,'success' => "FALSE", 'error' => __("Error : entry not existing!",'WATS'));
	}
	
	echo json_encode($message_result);
	exit;
}

/*********************************/
/*                               */
/* Fonction d'ajout d'une option */
/*                               */
/*********************************/

function wats_admin_insert_option_entry()
{
	global $wats_settings;

	wats_load_settings();
	$idvalue = stripslashes_deep($_POST[idvalue]);
	$type = $_POST[type];
	$idcat = $_POST[idcat];
	
	check_ajax_referer('update-wats-options');
	
	if (strlen($_POST[idvalue]) == 0)
	{
		$message_result = array('id' => "", 'idvalue' => "",'success' => "FALSE", 'error' => __("Error : please enter an entry!",'WATS'));
	}
	else
    {
		$res = 0;
		$length = 0;
		if ($wats_settings[$type])
		{
			$wats_options = $wats_settings[$type];
			foreach ($wats_options as $key => $value)
			{
				if ($key > $length)
					$length = $key;
				if (($value == $idvalue) || ($key == $idcat))
				{
					$res = 1;
				}
			}
		}

		if ($res == 1)
        {
            $message_result = array('id' => $id, 'idvalue' => "",'success' => "FALSE", 'error' => __("Error : already existing entry!",'WATS'));
		}
        else
        {
			if ($idcat > 0)
				$length = $idcat;
			else
				$length++;
			$wats_options[$length] = $idvalue;
			$wats_settings[$type] = $wats_options;
			update_option('wats', $wats_settings);
			$message_result = array('id' => $length, 'idvalue' => $idvalue,'success' => "TRUE", 'error' => __("Entry successfully added!",'WATS'));
        }
	}
	
	echo json_encode($message_result);
	exit;
}

/*************************************************************/
/*                                                           */
/* Fonction d'affichage de l'interface d'ajout de catégories */
/*                                                           */
/*************************************************************/

function wats_admin_add_category_interface($resultsup,$resultadd,$idsup,$idadd,$value,$input)
{

	echo '<input type="submit" class="button-primary" id="'.$idsup.'" value="'.__('Remove selected categories','WATS').'" /><div id="'.$resultsup.'"></div><br /><br />';

	echo '<table class="form-table" cellspacing="1" cellpadding="1">';
	echo '<tr><th><label>'.__($value,'WATS').'</label></th><td>';
	echo '<select name="catlist" id="catlist" size="1">';
	$categories = get_categories('type=post&hide_empty=0');
	foreach ($categories as $category)
	{
        echo '<option value="'.$category->cat_ID.'" >'.esc_html($category->cat_name).'</option>';
	}
	echo '</select></td><td></td></tr>';
	echo '</table><br />';
	echo '<input type="submit" id="'.$idadd.'" value="'.__('Add this category','WATS').'" class="button-primary" /><div id="'.$resultadd.'"></div>';

	return;
}

/************************************************************/
/*                                                          */
/* Fonction d'affichage des tables dans la page des options */
/*                                                          */
/************************************************************/

function wats_admin_add_table_interface($resultsup,$resultadd,$idsup,$idadd,$value,$input)
{

	echo '<input type="submit" class="button-primary" id="'.$idsup.'" value="'.__('Remove selected items','WATS').'" /><div id="'.$resultsup.'"></div><br /><br />';

	echo '<table class="form-table" cellspacing="1" cellpadding="1">';
	echo '<tr><th><label>'.__($value,'WATS').'</label></th><td><input type="text" name="'.$input.'" id="'.$input.'" size="30" class="regular-text" /></td><td></td></tr>';
	echo '</table><br />';
	echo '<input type="submit" id="'.$idadd.'" value="'.__('Add this entry','WATS').'" class="button-primary" /><div id="'.$resultadd.'"></div><br /><br />';

	return;
}

/***************************************************************/
/*                                                             */
/* Fonction de remplissage des tables dans la page des options */
/*                                                             */
/***************************************************************/

function wats_admin_display_options_list($type,$check)
{
    global $wats_settings;
	
    $x = 0;
    $alt = false;
	if ($wats_settings[$type])
	{
		$wats_options = $wats_settings[$type];
		foreach ($wats_options AS $key => $value)
		{
			$x = 1;
		
			echo '<tr valign="middle"';
			echo ($alt == true) ? ' class="alternate"' : '';
			echo '>';
			echo '<td>'.$key.'</td>';
			echo '<td';
			if ($type != 'wats_categories')
				echo ' class="wats_editable">';
			else
				echo '>';
			echo esc_html($value).'</td>';
			echo '<td><input type="checkbox" name="'.$check.'" id="'.$check.'" value="'.$key.'" /></td>';
			echo '</tr>';

			$alt = !$alt;
		}
    }

    if ($x == 0)
    {
        echo '<tr valign="middle"><td colspan="3" style="text-align:center">'.__('No entry','WATS').'</td></tr>';
    }
	echo '</tbody></table><br />';
	
    return;
}

/***********************************************/
/*                                             */
/* Fonction d'affichage de la page des options */
/*                                             */
/***********************************************/

function wats_options_admin_menu()
{
	global $wpdb, $wats_version, $wats_settings;

	if (isset($_POST['save']))
	{
		check_admin_referer('update-wats-options');
		$wats_settings['wats_version'] = $wats_version;
		$wats_settings['numerotation'] = $_POST['group1'];
		$wats_settings['visibility'] = $_POST['group2'];
		$wats_settings['ticket_assign'] = $_POST['group3'];
		$wats_settings['ticket_assign_user_list'] = $_POST['group4'];
		$wats_settings['ticket_assign_level'] = $_POST['ticket_assign_level'];
		$wats_settings['wats_guest_user'] = $_POST['guestlist'];
		$wats_settings['wats_home_display'] = isset($_POST['homedisplay']) ? 1 : 0;
		$wats_settings['new_ticket_notification_admin'] = isset($_POST['new_ticket_notification_admin']) ? 1 : 0;
		$wats_settings['ticket_update_notification_all_tickets'] = isset($_POST['ticket_update_notification_all_tickets']) ? 1 : 0;
		$wats_settings['ticket_update_notification_my_tickets'] = isset($_POST['ticket_update_notification_my_tickets']) ? 1 : 0;
		$wats_settings['comment_menuitem_visibility'] = isset($_POST['comment_menuitem_visibility']) ? 1 : 0;
		$wats_settings['tickets_tagging'] = isset($_POST['tickets_tagging']) ? 1 : 0;
		$wats_settings['tickets_custom_fields'] = isset($_POST['tickets_custom_fields']) ? 1 : 0;
		$wats_settings['dashboard_stats_widget_level'] = $_POST['dashboard_stats_widget_level'];
		$wats_settings['ticket_edition_media_upload'] = isset($_POST['ticket_edition_media_upload']) ? 1 : 0;
		$wats_settings['ticket_edition_media_upload_tabs'] = isset($_POST['ticket_edition_media_upload_tabs']) ? 1 : 0;
		$wats_settings['call_center_ticket_creation'] = isset($_POST['call_center_ticket_creation']) ? 1 : 0;
		$wats_settings['user_selector_format'] = wats_is_string(stripslashes($_POST['user_selector_format'])) ? esc_html(stripslashes($_POST['user_selector_format'])) : 'user_login';
		$wats_settings['filter_ticket_listing'] = isset($_POST['filter_ticket_listing']) ? 1 : 0;
		$wats_settings['filter_ticket_listing_meta_key'] = $_POST['metakeylistfilter'];
		$wats_settings['meta_column_ticket_listing'] = isset($_POST['meta_column_ticket_listing']) ? 1 : 0;
		$wats_settings['meta_column_ticket_listing_meta_key'] = $_POST['metakeylistcolumn'];
		$wats_settings['user_selector_order_1'] = $_POST['user_selector_order_1'];
		$wats_settings['user_selector_order_2'] = $_POST['user_selector_order_2'];
		$wats_settings['notification_signature'] = esc_html(preg_replace("/(\r\n|\n|\r)/", "",nl2br($_POST['notification_signature'])));
		$wats_settings['frontend_submit_form_access'] = $_POST['group5'];
		$wats_settings['frontend_submit_form_ticket_status'] = $_POST['group6'];
		$wats_settings['submit_form_default_author'] = $_POST['defaultauthorlist'];
		$wats_settings['ms_ticket_submission'] = isset($_POST['ms_ticket_submission']) ? 1 : 0;
		$wats_settings['ms_mail_server'] = wats_is_string(stripslashes($_POST['ms_mail_server'])) ? esc_html(stripslashes($_POST['ms_mail_server'])) : 'mail.example.com';
		$wats_settings['ms_port_server'] = wats_is_numeric(stripslashes($_POST['ms_port_server'])) ? esc_html(stripslashes($_POST['ms_port_server'])) : '110';
		$wats_settings['ms_mail_address'] = wats_is_string(stripslashes($_POST['ms_mail_address'])) ? esc_html(stripslashes($_POST['ms_mail_address'])) : 'login@example.com';
		$wats_settings['ms_mail_password'] = wats_is_string(stripslashes($_POST['ms_mail_password'])) ? esc_html(stripslashes($_POST['ms_mail_password'])) : 'password';
		$wats_settings['closed_status_id'] = $_POST['closedstatusselector'];
		$wats_settings['ticket_notification_bypass_mode'] = isset($_POST['ticket_notification_bypass_mode']) ? 1 : 0;
		
		update_option('wats', $wats_settings);
	}
	
	wats_load_settings();
	echo '<H2><div style="text-align:center">WATS '.$wats_settings['wats_version'].'</div></H2>';
	
	echo '<h3>'.__('Donation','WATS').' :</h3>';
	echo __('WATS is free to use, cool isn\'t it? It has however required hundreds of hours to be developed.','WATS');
	echo __(' It still requires a huge amount of time in order to provide technical support for users and new releases with bugfixes and new features.','WATS');
	echo __(' By making a donation, you recognize my work and encourage me to go on with the development and support of WATS. Thanks for this!','WATS');
	echo '<br /><br /><p align="center"><center><form action="https://www.paypal.com/cgi-bin/webscr" enctype="application/x-www-form-urlencoded" method="post">';
	echo '<input name="cmd" type="hidden" value="_s-xclick" />';
	echo '<input name="hosted_button_id" type="hidden" value="6412724" />';
	echo '<input alt="PayPal - The safer, easier way to pay online!" name="submit" src="https://www.paypal.com/en_US/FR/i/btn/btn_donateCC_LG.gif" type="image" style="border: none" /> <img src="https://www.paypal.com/fr_FR/i/scr/pixel.gif" border="0" alt="pixel WATS going on..." width="1" height="1" title="WATS going on..." /><br /></form></center></p>';
	
	echo '<h3>'.__('Help','WATS').' :</h3>';
	echo __('If you want to get some details about an option, just click on the option title, this will display some inline details.','WATS').'<br /><br />';
	echo __('In the tables, you can directly edit items by clicking on the following icon : ','WATS').'<img src="'.WATS_URL.'img/modify.png" /><br /><br />';
	echo __('If you need some help with the plugin setup, you can :','WATS');
	echo '<ul style="list-style-type:disc;padding-left:40px;padding-top:5px;"><li><a href="http://www.lautre-monde.fr/wats-going-on/">'.__('Read the documentation','WATS').'</a></li>';
	echo '<li><a href="http://www.lautre-monde.fr/wats-going-on/#respond">'.__('Leave a comment','WATS').'</a></li>';
	echo '<li><a href="http://www.lautre-monde.fr/contactez-moi/">'.__('Drop me a mail','WATS').'</a></li></ul><br />';
	echo __('If you are looking for a Wordpress expert, please ','WATS').'<a href="http://www.lautre-monde.fr/contactez-moi/">'.__(' contact me','WATS').'</a>! ';
	echo __('I can perform theme customization, Wordpress installation and configuration, plugin customization and even more...','WATS').'<br /><br /><br />';
		
	echo '<form action="" method="post">';
	wp_nonce_field('update-wats-options');
	
	echo '<h3><a style="cursor:pointer;" title="'.__('Click to get some help!', 'WATS').'" onclick=javascript:wats_invert_visibility("group1_tip");>'.__('Ticket numerotation','WATS').' :</a></h3>';
	echo '<table class="form-table">';
	echo '<tr><td><input type="radio" name="group1" value="0" ';
	echo ($wats_settings['numerotation'] == 0) ? 'checked' : '';
	echo '>'.__('None','WATS').' </td></tr>';
	echo '<tr><td><input type="radio" name="group1" value="1" ';
	echo ($wats_settings['numerotation'] == 1) ? 'checked' : '';
	echo '>'.__('Dated','WATS').' (ex : 090601-00001)</td></tr>';
	echo '<tr><td><input type="radio" name="group1" value="2" ';
	echo ($wats_settings['numerotation'] == 2) ? 'checked' : '';
	echo '>'.__('Numbered','WATS').' (ex : 1)</td></tr><tr><td>';
	echo '<div class="wats_tip" id="group1_tip">';
	echo __('Select the preferred option. Based on this, a number will be associated to a ticket and displayed at the beginning of the title.','WATS').'</div></td></tr></table><br />';
	
	if ($wats_settings['numerotation'] > 0)
	{
		echo '<h3>'.__('Latest ticket ID','WATS').' : '.wats_get_latest_ticket_number().'</h3><br />';
	}
	
	echo '<h3><a style="cursor:pointer;" title="'.__('Click to get some help!', 'WATS').'" onclick=javascript:wats_invert_visibility("wats_home_display_tip");>'.__('Tickets display','WATS').' :</a></h3>';
	echo '<table class="form-table">';
	echo '<tr><td><input type="checkbox" name="homedisplay"';
	if ($wats_settings['wats_home_display'] == 1)
		echo ' checked';
	echo '> '.__('Include tickets on homepage together with posts','WATS').'</td></tr><tr><td>';
	echo '<div class="wats_tip" id="wats_home_display_tip">';
	echo __('Check this option if you want to display tickets on homepage along with usual posts. If the option is unchecked, only posts will be displayed.','WATS').'</div></td></tr></table><br />';
	
	echo '<h3><a style="cursor:pointer;" title="'.__('Click to get some help!', 'WATS').'" onclick=javascript:wats_invert_visibility("notification_admin_tip");>'.__('Notifications','WATS').' :</a></h3>';
	echo '<table class="form-table">';
	echo '<tr><td><input type="checkbox" name="new_ticket_notification_admin"';
	if ($wats_settings['new_ticket_notification_admin'] == 1)
		echo ' checked';
	echo '> '.__('Notify admin by email upon new ticket submission','WATS').'</td></tr><tr><td>';
	echo '<tr><td><input type="checkbox" name="ticket_update_notification_all_tickets"';
	if ($wats_settings['ticket_update_notification_all_tickets'] == 1)
		echo ' checked';
	echo '> '.__('Notify user by email when ticket is updated. Applies to all tickets and will notify all users.','WATS').'</td></tr><tr><td>';
	echo '<tr><td><input type="checkbox" name="ticket_update_notification_my_tickets"';
	if ($wats_settings['ticket_update_notification_my_tickets'] == 1)
		echo ' checked';
	echo '> '.__('Notify user by email when ticket is updated. Applies only to tickets originated by the user and will notify only ticket originator and updaters.','WATS').'</td></tr><tr><td>';
	echo '<tr><td><input type="checkbox" name="ticket_notification_bypass_mode"';
	if ($wats_settings['ticket_notification_bypass_mode'] == 1)
		echo ' checked';
	echo '> '.__('Enable local user profile notifications options to allow bypass of global options.','WATS').'</td></tr><tr><td>';
	echo '<div class="wats_tip" id="notification_admin_tip">';
	echo __('Check the options according to the notifications you want the system to send to users after specific events happened. ','WATS');
	echo __('If the option is enabled here, then by default, it will be enabled for the user but he can disable it under his profile. ','WATS');
	echo __('When a new user is added, the profile option is disabled by default. ','WATS').'<br /><br />';
	echo __('If the option is disabled here, then it will be disabled for everybody and it couldn\'t be enabled individually. ','WATS');
	echo __('The update notification is fired upon the following events : new comment added to a ticket, ownership, priority, status or type change in the ticket edition admin page.','WATS').'<br /><br />';
	echo __('These are global options which can be enabled or disabled individually under user profile if the bypass option is set. ','WATS');
	echo __('If the bypass option isn\'t set, only global notifications options will be relevant and user profile options couldn\'t be modified. ','WATS');
	echo __('Warning : with these options enabled, the system may send a lot of emails, especially if you have many users. So please make sure that you really understand the implications before enabling these.','WATS').'</div></td></tr></table><br />';

	echo '<h3><a style="cursor:pointer;" title="'.__('Click to get some help!', 'WATS').'" onclick=javascript:wats_invert_visibility("notification_signature_tip");>'.__('Mail notifications signature','WATS').' : </a></h3>';
	echo '<table class="form-table">';
	echo '<tr><td><textarea id="notification_signature" name="notification_signature" cols="40" rows="5">';
	echo esc_html(str_replace(array('\r\n','\r','<br />'),"\n",html_entity_decode(stripslashes($wats_settings['notification_signature']))));
	echo '</textarea></td></tr><tr><td>';
	echo '<div class="wats_tip" id="notification_signature_tip">';
	echo __('Enter the signature to be put into every notification email sent by the system.','WATS').'</div></td></tr></table><br />';
	
	echo '<h3><a style="cursor:pointer;" title="'.__('Click to get some help!', 'WATS').'" onclick=javascript:wats_invert_visibility("group2_tip");>'.__('Tickets visibility','WATS').' :</a></h3>';
	echo '<table class="form-table">';
	echo '<tr><td><input type="radio" name="group2" value="0" ';
	echo ($wats_settings['visibility'] == 0) ? 'checked' : '';
	echo '>'.__('Everybody can see all tickets','WATS').' </td></tr>';
	echo '<tr><td><input type="radio" name="group2" value="1" ';
	echo ($wats_settings['visibility'] == 1) ? 'checked' : '';
	echo '>'.__('Only registered users can see tickets','WATS').'</td></tr>';
	echo '<tr><td><input type="radio" name="group2" value="2" ';
	echo ($wats_settings['visibility'] == 2) ? 'checked' : '';
	echo '>'.__('Only ticket creator and admins can see tickets','WATS').'</td></tr><tr><td>';
	echo '<div class="wats_tip" id="group2_tip">';
	echo __('Select the preferred option. Tickets access and display in frontend and admin sides will be adjusted based on this option and user privileges.','WATS');
	echo __(' This option will also affect author and owner selectors filters display for the ticket listing table which will be available for everybody, only logged in users or only admins based on the selected option.','WATS').'</div></td></tr></table><br />';
	
	echo '<h3><a style="cursor:pointer;" title="'.__('Click to get some help!', 'WATS').'" onclick=javascript:wats_invert_visibility("group3_tip");>'.__('Tickets assignment','WATS').' :</a></h3>';
	echo '<table class="form-table">';
	echo '<tr><td><input type="radio" name="group3" value="0" ';
	echo ($wats_settings['ticket_assign'] == 0) ? 'checked' : '';
	echo '>'.__('No assignment possible','WATS').' </td></tr>';
	echo '<tr><td><input type="radio" name="group3" value="1" ';
	echo ($wats_settings['ticket_assign'] == 1) ? 'checked' : '';
	echo '>'.__('Everybody can assign a ticket','WATS').'</td></tr>';
	echo '<tr><td><input type="radio" name="group3" value="2" ';
	echo ($wats_settings['ticket_assign'] == 2) ? 'checked' : '';
	echo '>'.__('Only registered users can assign a ticket','WATS').'</td></tr><tr><td>';
	echo '<div class="wats_tip" id="group3_tip">';
	echo __('Select the preferred option. Tickets assignment possibilities in frontend and admin sides will be adjusted based on this option and user privileges.','WATS').'</div></td></tr></table><br />';

	echo '<h3><a style="cursor:pointer;" title="'.__('Click to get some help!', 'WATS').'" onclick=javascript:wats_invert_visibility("group4_tip");>'.__('Target users for tickets assignment','WATS').' :</a></h3>';
	echo '<table class="form-table">';
	echo '<tr><td><input type="radio" name="group4" value="0" ';
	echo ($wats_settings['ticket_assign_user_list'] == 0) ? 'checked' : '';
	echo '>'.__('Any registered user','WATS').' </td></tr>';
	echo '<tr><td><input type="radio" name="group4" value="1" ';
	echo ($wats_settings['ticket_assign_user_list'] == 1) ? 'checked' : '';
	echo '>'.__('Ticket originator and admins','WATS').'</td></tr>';
	echo '<tr><td><input type="radio" name="group4" value="2" ';
	echo ($wats_settings['ticket_assign_user_list'] == 2) ? 'checked' : '';
	echo '>'.__('Ticket originator and any user with wats_ticket_ownership capability','WATS').'</td></tr><tr><td>';
	echo '<div class="wats_tip" id="group4_tip">';
	echo __('Select the preferred option. The list of users a ticket can be assigned to will be adjusted based on this option. The wats_ticket_ownership capability can be granted under user profile by admins.','WATS').'</div></td></tr></table><br />';
	
	echo '<h3><a style="cursor:pointer;" title="'.__('Click to get some help!', 'WATS').'" onclick=javascript:wats_invert_visibility("ticket_assign_level_tip");>'.__('Ticket assignment capability minimum level requirement','WATS').' : </a></h3>';
	echo '<table class="form-table"><tr><td>'.__('Level','WATS').' : <select name="ticket_assign_level" id="ticket_assign_level" size="1">';
	for ($i = 0; $i != 11; $i++)
	{
        echo '<option value="'.$i.'" ';
        if ($i == $wats_settings['ticket_assign_level']) echo 'selected';
			echo '>'.$i.'</option>';
	}
	echo '</select></td></tr><tr><td>';
	echo '<div class="wats_tip" id="ticket_assign_level_tip">';
	echo __('Select the level. Only users with this minimum level value will be able to assign tickets. To learn more about users levels, check out this page : ','WATS').'<a href="http://codex.wordpress.org/Roles_and_Capabilities">WP roles and capabilities</a>.</div></td></tr></table><br />';

	echo '<h3><a style="cursor:pointer;" title="'.__('Click to get some help!', 'WATS').'" onclick=javascript:wats_invert_visibility("comment_menuitem_visibility_tip");>'.__('Admin menu access','WATS').' : </a></h3>';
	echo '<table class="form-table">';
	echo '<tr><td><input type="checkbox" name="comment_menuitem_visibility"';
	if ($wats_settings['comment_menuitem_visibility'] == 1)
		echo ' checked';
	echo '> '.__('Block comments menu access for users without moderate_comments capability','WATS').'</td></tr><tr><td>';
	echo '<div class="wats_tip" id="comment_menuitem_visibility_tip">';
	echo __('Check this option if you want to prevent users without the comments moderation capability to browse the comments list page (on this page, they could see updates on all tickets).','WATS').'</div></td></tr></table><br />';

	echo '<h3><a style="cursor:pointer;" title="'.__('Click to get some help!', 'WATS').'" onclick=javascript:wats_invert_visibility("tickets_tagging_tip");>'.__('Tickets tagging','WATS').' : </a></h3>';
	echo '<table class="form-table">';
	echo '<tr><td><input type="checkbox" name="tickets_tagging"';
	if ($wats_settings['tickets_tagging'] == 1)
		echo ' checked';
	echo '> '.__('Allow tickets tagging','WATS').'</td></tr><tr><td>';
	echo '<div class="wats_tip" id="tickets_tagging_tip">';
	echo __('Check this option if you want to allow tag association to tickets.','WATS').'</div></td></tr></table><br />';
	
	echo '<h3><a style="cursor:pointer;" title="'.__('Click to get some help!', 'WATS').'" onclick=javascript:wats_invert_visibility("tickets_custom_fields_tip");>'.__('Custom fields','WATS').' : </a></h3>';
	echo '<table class="form-table">';
	echo '<tr><td><input type="checkbox" name="tickets_custom_fields"';
	if ($wats_settings['tickets_custom_fields'] == 1)
		echo ' checked';
	echo '> '.__('Allow custom fields association to tickets','WATS').'</td></tr><tr><td>';
	echo '<div class="wats_tip" id="tickets_custom_fields_tip">';
	echo __('Check this option if you want to allow custom fields association to tickets.','WATS').'</div></td></tr></table><br />';
	
	echo '<h3><a style="cursor:pointer;" title="'.__('Click to get some help!', 'WATS').'" onclick=javascript:wats_invert_visibility("dashboard_stats_widget_level_tip");>'.__('Global statistics dashboad widget visibility','WATS').' : </a></h3>';
	echo '<table class="form-table"><tr><td>'.__('Minimum required level','WATS').' : <select name="dashboard_stats_widget_level" id="dashboard_stats_widget_level" size="1">';
	for ($i = 0; $i != 11; $i++)
	{
        echo '<option value="'.$i.'" ';
        if ($i == $wats_settings['dashboard_stats_widget_level']) echo 'selected';
			echo '>'.$i.'</option>';
	}
	echo '</select></td></tr><tr><td>';
	echo '<div class="wats_tip" id="dashboard_stats_widget_level_tip">';
	echo __('Select the level. Only users with this minimum level value will be able to view the global statistics under the stats widget on the dashboard. To learn more about users levels, check out this page : ','WATS').'<a href="http://codex.wordpress.org/Roles_and_Capabilities">WP roles and capabilities</a>.</div></td></tr></table><br />';
	
	echo '<h3><a style="cursor:pointer;" title="'.__('Click to get some help!', 'WATS').'" onclick=javascript:wats_invert_visibility("ticket_edition_media_upload_tip");>'.__('Media upload','WATS').' : </a></h3>';
	echo '<table class="form-table">';
	echo '<tr><td><input type="checkbox" name="ticket_edition_media_upload"';
	if ($wats_settings['ticket_edition_media_upload'] == 1)
		echo ' checked';
	echo '> '.__('Allow media upload on ticket creation and edition pages','WATS').'</td></tr><tr><td>';
	echo '<div class="wats_tip" id="ticket_edition_media_upload_tip">';
	echo __('Check this option if you want to allow media upload while creating and editing tickets. This will allow users to attach media files to tickets.','WATS').'</div></td></tr></table><br />';
	
	echo '<h3><a style="cursor:pointer;" title="'.__('Click to get some help!', 'WATS').'" onclick=javascript:wats_invert_visibility("ticket_edition_media_upload_tabs_tip");>'.__('Media upload tabs','WATS').' : </a></h3>';
	echo '<table class="form-table">';
	echo '<tr><td><input type="checkbox" name="ticket_edition_media_upload_tabs"';
	if ($wats_settings['ticket_edition_media_upload_tabs'] == 1)
		echo ' checked';
	echo '> '.__('Allow media library browsing during media upload','WATS').'</td></tr><tr><td>';
	echo '<div class="wats_tip" id="ticket_edition_media_upload_tabs_tip">';
	echo __('Check this option if you want to allow media library browsing during media upload while creating and editing tickets. This will allow users to view the library and insert files directly from it.','WATS').'</div></td></tr></table><br />';
	
	echo '<h3><a style="cursor:pointer;" title="'.__('Click to get some help!', 'WATS').'" onclick=javascript:wats_invert_visibility("guestlist_tip");>'.__('Shared guest user','WATS').' : </a></h3>';
	echo '<table class="form-table"><tr><td>'.__('User','WATS').' : <select name="guestlist" id="guestlist" class="wats_select">';
	$userlist = wats_build_user_list(1,__("None",'WATS'),0);
	foreach ($userlist AS $userlogin => $username)
	{
        echo '<option value="'.$userlogin.'" ';
        if ($userlogin == $wats_settings['wats_guest_user']) echo 'selected';
			echo '>'.$username.'</option>';
	}
	echo '</select></td></tr><tr><td>';
	echo '<div class="wats_tip" id="guestlist_tip">';
	echo __('The shared guest user is a user that must have at least contributor user level. This user will only have access to the ticket creation page on the admin side. You can share the guest user login/password with your visitors so that they can submit tickets without having to register first. This is a shared account.','WATS');
	echo '<br /><br />'.__('Warning : if you set the current user (your admin account) as the guest user, you won\'t be able to access the admin options anymore after the save. So please make sure that you understand what it is about before setting this option.','WATS').'</div></td></tr></table><br />';

	echo '<h3><a style="cursor:pointer;" title="'.__('Click to get some help!', 'WATS').'" onclick=javascript:wats_invert_visibility("frontendsubmitformaccess_tip");>'.__('Frontend submission form access','WATS').' : </a></h3>';
	echo '<table class="form-table">';
	echo '<tr><td><input type="radio" name="group5" value="0" ';
	echo ($wats_settings['frontend_submit_form_access'] == 0) ? 'checked' : '';
	echo '>'.__('Disable frontend ticket submission form','WATS').' </td></tr>';
	echo '<tr><td><input type="radio" name="group5" value="1" ';
	echo ($wats_settings['frontend_submit_form_access'] == 1) ? 'checked' : '';
	echo '>'.__('Enable frontend ticket submission form for any visitor with a valid email address','WATS').'</td></tr>';
	echo '<tr><td><input type="radio" name="group5" value="2" ';
	echo ($wats_settings['frontend_submit_form_access'] == 2) ? 'checked' : '';
	echo '>'.__('Enable frontend ticket submission form for registered users only','WATS').'</td></tr><tr><td>';
	echo '<div class="wats_tip" id="frontendsubmitformaccess_tip">';
	echo __('Set this option to allow users to use a ticket submission form in the frontend to submit new tickets.','WATS');
	echo '<br /><br />'.__('Warning : if option is selected, users will have the opportunity to submit tickets without being authenticated. This could result in large amount of SPAM.','WATS').'</div></td></tr></table><br />';

	echo '<h3><a style="cursor:pointer;" title="'.__('Click to get some help!', 'WATS').'" onclick=javascript:wats_invert_visibility("frontendsubformstatus_tip");>'.__('Frontend submission form ticket status','WATS').' : </a></h3>';
	echo '<table class="form-table">';
	echo '<tr><td><input type="radio" name="group6" value="0" ';
	echo ($wats_settings['frontend_submit_form_ticket_status'] == 0) ? 'checked' : '';
	echo '>'.__('All tickets submitted will be in \'pending\' status','WATS').' </td></tr>';
	echo '<tr><td><input type="radio" name="group6" value="1" ';
	echo ($wats_settings['frontend_submit_form_ticket_status'] == 1) ? 'checked' : '';
	echo '>'.__('Tickets from unauthenticated users will be submitted in \'pending\' status and tickets from authenticated users will be in \'publish\' status','WATS').'</td></tr>';
	echo '<tr><td><input type="radio" name="group6" value="2" ';
	echo ($wats_settings['frontend_submit_form_ticket_status'] == 2) ? 'checked' : '';
	echo '>'.__('Tickets from unauthenticated users will be submitted in \'pending\' status and tickets from authenticated users will be set according to user level capability','WATS').'</td></tr>';
	echo '<tr><td><input type="radio" name="group6" value="3" ';
	echo ($wats_settings['frontend_submit_form_ticket_status'] == 3) ? 'checked' : '';
	echo '>'.__('All tickets submitted will be in \'publish\' status','WATS').'</td></tr><tr><td>';
	echo '<div class="wats_tip" id="frontendsubformstatus_tip">';
	echo __('Set this option to define the ticket publications status upon ticket submission. It is advisable to set unauthenticated users tickets status to \'pending\' to allow admin moderation before publication and limit SPAM.','WATS');
	echo '</div></td></tr></table><br />';
	
	echo '<h3><a style="cursor:pointer;" title="'.__('Click to get some help!', 'WATS').'" onclick=javascript:wats_invert_visibility("email_ticket_submission_tip");>'.__('Email ticket submission','WATS').' : </a></h3>';
	echo '<table class="form-table">';
	echo '<tr><td><input type="checkbox" name="ms_ticket_submission"';
	if ($wats_settings['ms_ticket_submission'] == 1)
		echo ' checked';
	echo '> '.__('Allow ticket submission through email','WATS').'</td></tr><tr><td>';
	echo '<tr><td>'.__('Server : ','WATS').'<input type="text" name="ms_mail_server" value="'.esc_attr(stripslashes($wats_settings['ms_mail_server'])).'" size=30></td></tr><tr><td>';
	echo '<tr><td>'.__('Port : ','WATS').'<input type="text" name="ms_port_server" value="'.esc_attr(stripslashes($wats_settings['ms_port_server'])).'" size=30></td></tr><tr><td>';
	echo '<tr><td>'.__('Login : ','WATS').'<input type="text" name="ms_mail_address" value="'.esc_attr(stripslashes($wats_settings['ms_mail_address'])).'" size=30></td></tr><tr><td>';
	echo '<tr><td>'.__('Password : ','WATS').'<input type="text" name="ms_mail_password" value="'.esc_attr(stripslashes($wats_settings['ms_mail_password'])).'" size=30></td></tr><tr><td>';
	echo '<div class="wats_tip" id="email_ticket_submission_tip">';
	echo __('This feature allows users to submit tickets directly through email. You have to define a secret email on a POP3 server.','WATS');
	echo '<br/><br />'.__('Warning : every email received on this account will result in a ticket. Therefore, make sure that your email adress isn\'t known by SPAM robots.','WATS');
	echo '</div></td></tr></table><br />';
	
	echo '<h3><a style="cursor:pointer;" title="'.__('Click to get some help!', 'WATS').'" onclick=javascript:wats_invert_visibility("submitformdefaultauthor_tip");>'.__('Default author for unregistered visitors tickets','WATS').' : </a></h3>';
	echo '<table class="form-table"><tr><td>'.__('User','WATS').' : <select name="defaultauthorlist" id="defaultauthorlist" class="wats_select">';
	$userlist = wats_build_user_list(0,0,0);
	foreach ($userlist AS $userlogin => $username)
	{
        echo '<option value="'.$userlogin.'" ';
        if ($userlogin == $wats_settings['submit_form_default_author']) echo 'selected';
			echo '>'.$username.'</option>';
	}
	echo '</select></td></tr><tr><td>';
	echo '<div class="wats_tip" id="submitformdefaultauthor_tip">';
	echo __('This option will be used to set the author of tickets submitted through the frontend submit form or through email by unregistered users.','WATS').'</div></td></tr></table><br />';
	
	echo '<h3><a style="cursor:pointer;" title="'.__('Click to get some help!', 'WATS').'" onclick=javascript:wats_invert_visibility("call_center_ticket_creation_tip");>'.__('Call center ticket creation','WATS').' : </a></h3>';
	echo '<table class="form-table">';
	echo '<tr><td><input type="checkbox" name="call_center_ticket_creation"';
	if ($wats_settings['call_center_ticket_creation'] == 1)
		echo ' checked';
	echo '> '.__('Allow admins to create a ticket on behalf of any user','WATS').'</td></tr><tr><td>';
	echo '<div class="wats_tip" id="call_center_ticket_creation_tip">';
	echo __('Check this option if you want to allow admins to create tickets on behalf of any user. This will allow them to set the ticket originator while submitting a new ticket.','WATS').'</div></td></tr></table><br />';
	
	echo '<h3><a style="cursor:pointer;" title="'.__('Click to get some help!', 'WATS').'" onclick=javascript:wats_invert_visibility("user_selector_format_tip");>'.__('User selector format','WATS').' : </a></h3>';
	echo '<table class="form-table">';
	echo '<tr><td>'.__('Format : ','WATS').'<input type="text" name="user_selector_format" value="'.esc_attr(stripslashes($wats_settings['user_selector_format'])).'" size=30></td></tr><tr><td>';
	echo '<div class="wats_tip" id="user_selector_format_tip">';
	echo __('Using user meta keys, set the user format you would like to use for user selectors. This format will be applied to all user selectors. If it is empty, the default key "user_login" will be applied. The following user meta keys can be used : user_login, ','WATS').wats_get_list_of_user_meta_keys(0);
	echo '<br/><br />'.__('Warning : you need to make sure that the combination of keys used will make each entry unique and different from each other. Therefore, it is a good idea to use user_login as this key is unique for each user.','WATS');
	echo '</div></td></tr></table><br />';

	echo '<h3><a style="cursor:pointer;" title="'.__('Click to get some help!', 'WATS').'" onclick=javascript:wats_invert_visibility("user_selector_order_tip");>'.__('User selector order','WATS').' : </a></h3>';
	echo '<table class="form-table">';
	echo '<tr><td>'.__('Sort by','WATS').' : <select name="user_selector_order_1" id="user_selector_order_1" size="1">';
	$metakeylist = wats_get_list_of_user_meta_keys(1);
	foreach ($metakeylist AS $metakey)
	{
        echo '<option value="'.$metakey.'" ';
        if ($metakey == $wats_settings['user_selector_order_1']) echo 'selected';
			echo '>'.$metakey.'</option>';
	}
	echo '</select></td></tr><tr><td>'.__('And then','WATS').' : <select name="user_selector_order_2" id="user_selector_order_2" size="1">';
	foreach ($metakeylist AS $metakey)
	{
        echo '<option value="'.$metakey.'" ';
        if ($metakey == $wats_settings['user_selector_order_2']) echo 'selected';
			echo '>'.$metakey.'</option>';
	}
	echo '</select></td></tr><tr><td>';
	echo '<div class="wats_tip" id="user_selector_order_tip">';
	echo __('Select the meta keys used to sort the user selectors.','WATS').'</div></td></tr></table><br />';
	
	echo '<h3><a style="cursor:pointer;" title="'.__('Click to get some help!', 'WATS').'" onclick=javascript:wats_invert_visibility("filter_ticket_listing_tip");>'.__('Ticket author user meta key selector for ticket listing filtering','WATS').' : </a></h3>';
	echo '<table class="form-table">';
	echo '<tr><td><input type="checkbox" name="filter_ticket_listing"';
	if ($wats_settings['filter_ticket_listing'] == 1)
		echo ' checked';
	echo '> '.__('Allow admins to filter tickets through author user meta key selector','WATS').'</td></tr><tr><td>';
	echo __('Meta key','WATS').' : <select name="metakeylistfilter" id="metakeylistfilter" size="1">';
	$metakeylist = wats_get_list_of_user_meta_keys(1);
	foreach ($metakeylist AS $metakey)
	{
        echo '<option value="'.$metakey.'" ';
        if ($metakey == $wats_settings['filter_ticket_listing_meta_key']) echo 'selected';
			echo '>'.$metakey.'</option>';
	}
	echo '</select></td></tr><tr><td>';
	echo '<div class="wats_tip" id="filter_ticket_listing_tip">';
	echo __('Check this option if you want to allow admins to filter tickets through an additionnal selector which will be filled in with meta values attached to the selected meta key.','WATS').'</div></td></tr></table><br />';

	echo '<h3><a style="cursor:pointer;" title="'.__('Click to get some help!', 'WATS').'" onclick=javascript:wats_invert_visibility("meta_column_ticket_listing_tip");>'.__('Ticket author user meta key column for tickets listing table','WATS').' : </a></h3>';
	echo '<table class="form-table">';
	echo '<tr><td><input type="checkbox" name="meta_column_ticket_listing"';
	if ($wats_settings['meta_column_ticket_listing'] == 1)
		echo ' checked';
	echo '> '.__('Allow admins to get another column filled with author meta value in the tickets listing table','WATS').'</td></tr><tr><td>';
	echo __('Meta key','WATS').' : <select name="metakeylistcolumn" id="metakeylistcolumn" size="1">';
	foreach ($metakeylist AS $metakey)
	{
        echo '<option value="'.$metakey.'" ';
        if ($metakey == $wats_settings['meta_column_ticket_listing_meta_key']) echo 'selected';
			echo '>'.$metakey.'</option>';
	}
	echo '</select></td></tr><tr><td>';
	echo '<div class="wats_tip" id="meta_column_ticket_listing_tip">';
	echo __('Check this option if you want to allow admins to get another column in the tickets listing table that will be filled in with user meta values attached to the selected meta key.','WATS').'</div></td></tr></table><br />';
	
	echo '<p class="submit">';
	echo '<input class="button-primary" type="submit" name="save" value="'.__('Save the options','WATS').'" /></p><br />';
	
	echo '<h3>'.__('Ticket types','WATS').' :</h3><br />';
	echo '<table class="widefat" cellspacing="0" id="tabletype" style="text-align:center;"><thead><tr class="thead">';
	echo '<th scope="col" class="manage-column" width="10%" style="text-align:center;">ID</th>';
	echo '<th scope="col" class="manage-column" style="text-align:center;">'.__('Type','WATS').'</th>';
    echo '<th scope="col" class="manage-column" style="text-align:center;">'.__('Selection','WATS').'</th>';
    echo '</tr></thead><tbody class="list:user user-list">';
    wats_admin_display_options_list('wats_types','typecheck');
	wats_admin_add_table_interface('resultsuptype','resultaddtype','idsuptype','idaddtype','Type','idtype');
	
	echo '<h3>'.__('Ticket priorities','WATS').' :</h3><br />';
	echo '<table class="widefat" cellspacing="0" id="tablepriority" style="text-align:center;"><thead><tr class="thead">';
	echo '<th scope="col" class="manage-column" width="10%" style="text-align:center;">ID</th>';
	echo '<th scope="col" class="manage-column" style="text-align:center;">'.__('Priority','WATS').'</th>';
    echo '<th scope="col" class="manage-column" style="text-align:center;">'.__('Selection','WATS').'</th>';
    echo '</tr></thead><tbody class="list:user user-list">';
    wats_admin_display_options_list('wats_priorities','prioritycheck');
	wats_admin_add_table_interface('resultsuppriority','resultaddpriority','idsuppriority','idaddpriority','Priority','idpriority');
	
	echo '<h3>'.__('Ticket statuses','WATS').' :</h3><br />';
	echo '<table class="widefat" cellspacing="0" id="tablestatus" style="text-align:center;"><thead><tr class="thead">';
	echo '<th scope="col" class="manage-column" width="10%" style="text-align:center;">ID</th>';
	echo '<th scope="col" class="manage-column" style="text-align:center;">'.__('Status','WATS').'</th>';
    echo '<th scope="col" class="manage-column" style="text-align:center;">'.__('Selection','WATS').'</th>';
    echo '</tr></thead><tbody class="list:user user-list">';
    wats_admin_display_options_list('wats_statuses','statuscheck');
	wats_admin_add_table_interface('resultsupstatus','resultaddstatus','idsupstatus','idaddstatus','Status','idstatus');

	echo '<h3><a style="cursor:pointer;" title="'.__('Click to get some help!', 'WATS').'" onclick=javascript:wats_invert_visibility("closed_status_selector_tip");>'.__('Closed status','WATS').' : </a></h3>';
	echo '<table class="form-table">';
	echo '<tr><td>';
	echo __('Status','WATS').' : <select name="closedstatusselector" id="closedstatusselector" size="1">';
	$wats_status = $wats_settings['wats_statuses'];
	foreach ($wats_status AS $key => $value)
	{
        echo '<option value="'.$key.'" ';
        if ($key == $wats_settings['closed_status_id']) echo 'selected';
			echo '>'.$value.'</option>';
	}
	echo '</select></td></tr><tr><td>';
	echo '<div class="wats_tip" id="closed_status_selector_tip">';
	echo __('Select the status associated to the ticket closure.','WATS').'</div></td></tr></table><br />';
	
	echo '<h3>'.__('Categories opened to submission','WATS').' :</h3><br />';
	echo '<table class="widefat" cellspacing="0" id="tablecat" style="text-align:center;"><thead><tr class="thead">';
	echo '<th scope="col" class="manage-column" width="10%" style="text-align:center;">ID</th>';
	echo '<th scope="col" class="manage-column" style="text-align:center;">'.__('Category','WATS').'</th>';
    echo '<th scope="col" class="manage-column" style="text-align:center;">'.__('Selection','WATS').'</th>';
    echo '</tr></thead><tbody class="list:user user-list">';
    wats_admin_display_options_list('wats_categories','catcheck');
	wats_admin_add_category_interface('resultsupcat','resultaddcat','idsupcat','idaddcat','Category','idcat');
	
	echo '<p class="submit">';
	echo '<input class="button-primary" type="submit" name="save" value="'.__('Save the options','WATS').'" /></p><br />';
	
	echo '</form><br /><br />';
}
?>