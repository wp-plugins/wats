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
		$default['notification_signature'] = 'Regads,\r\n\r\nWATS Notification engine';
				
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
			$wats_settings['notification_signature'] = 'Regads,\r\n\r\nWATS Notification engine';
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
	$idvalue = $_POST[idvalue];
	$idprevvalue = $_POST[idprevvalue];
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
				if (esc_html(stripslashes($value)) == esc_html(stripslashes($idprevvalue)))
					$res = $key;
			}
			
			foreach ($wats_options as $key => $value)
			{
				if (esc_html(stripslashes($value)) == esc_html(stripslashes($idvalue)))
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
				if (wats_is_string(stripslashes($idvalue)))
				{
					$wats_options[$res] = esc_html(stripslashes($idvalue));
					$wats_settings[$type] = $wats_options;
					update_option('wats', $wats_settings);
					$message_result = array('id' => "", 'idvalue' => stripslashes($idvalue),'success' => "TRUE", 'error' => __("Entry successfully updated!",'WATS'));
				}
				else
				{
					$message_result = array('id' => "", 'idvalue' => "",'success' => "FALSE", 'error' => __("Error : the entry contains invalid characters!",'WATS'));
				}
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

	$idvalue = $_POST[idvalue];
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
	$idvalue = $_POST[idvalue];
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
			if (wats_is_string(stripslashes($idvalue)))
			{
				if ($idcat > 0)
					$length = $idcat;
				else
					$length++;
				$wats_options[$length] = esc_html(stripslashes($idvalue));
				$wats_settings[$type] = $wats_options;
				update_option('wats', $wats_settings);
				$message_result = array('id' => $length, 'idvalue' => stripslashes($idvalue),'success' => "TRUE", 'error' => __("Entry successfully added!",'WATS'));
			}
			else
			{
				$message_result = array('id' => "", 'idvalue' => "",'success' => "FALSE", 'error' => __("Error : the entry contains invalid characters!",'WATS'));
			}
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

	echo '<input type="submit" class="button-primary" id="'.$idsup.'" value="'.__('Remove','WATS').'" /><div id="'.$resultsup.'"></div><br /><br />';

	echo '<table class="form-table" cellspacing="1" cellpadding="1">';
	echo '<tr><th><label>'.__($value,'WATS').'</label></th><td>';
	echo '<select name="catlist" id="catlist" size="1">';
	$categories = get_categories('type=post&hide_empty=0');
	foreach ($categories as $category)
	{
        echo '<option value="'.$category->cat_ID.'" >'.$category->cat_name.'</option>';
	}
	echo '</select></td><td></td></tr>';
	echo '</table><br />';
	echo '<input type="submit" id="'.$idadd.'" value="'.__('Add','WATS').'" class="button-primary" /><div id="'.$resultadd.'"></div>';

	return;
}

/************************************************************/
/*                                                          */
/* Fonction d'affichage des tables dans la page des options */
/*                                                          */
/************************************************************/

function wats_admin_add_table_interface($resultsup,$resultadd,$idsup,$idadd,$value,$input)
{

	echo '<input type="submit" class="button-primary" id="'.$idsup.'" value="'.__('Remove','WATS').'" /><div id="'.$resultsup.'"></div><br /><br />';

	echo '<table class="form-table" cellspacing="1" cellpadding="1">';
	echo '<tr><th><label>'.__($value,'WATS').'</label></th><td><input type="text" name="'.$input.'" id="'.$input.'" size="30" class="regular-text" /></td><td></td></tr>';
	echo '</table><br />';
	echo '<input type="submit" id="'.$idadd.'" value="'.__('Add','WATS').'" class="button-primary" /><div id="'.$resultadd.'"></div><br /><br />';

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
			echo esc_html(stripslashes($value)).'</td>';
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
		$wats_settings['notification_signature'] = esc_html(preg_replace("/(\r\n|\n|\r)/", "",nl2br($_POST['notification_signature'])));
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
	echo '> '.__('Notify user by email when ticket is updated. Applies to all tickets.','WATS').'</td></tr><tr><td>';
	echo '<tr><td><input type="checkbox" name="ticket_update_notification_my_tickets"';
	if ($wats_settings['ticket_update_notification_my_tickets'] == 1)
		echo ' checked';
	echo '> '.__('Notify user by email when ticket is updated. Applies only to tickets originated by the user.','WATS').'</td></tr><tr><td>';
	echo '<div class="wats_tip" id="notification_admin_tip">';
	echo __('Check the options according to the notifications you want the system to send to users after specific events happened. ','WATS');
	echo __('These are global options which can be enabled or disabled individually under user profile. ','WATS');
	echo __('If the option is enabled here, then by default, it will be enabled for the user but he can disable it under his profile. ','WATS');
	echo __('If the option is disabled here, then it will be disabled for everybody and it couldn\'t be enabled individually. ','WATS');
	echo __('The update notification is fired upon the following events : new comment added to a ticket, ownership, priority, status or type change in the ticket edition admin page.','WATS').'<br /><br />';
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
	echo '<table class="form-table"><tr><td>'.__('User','WATS').' : <select name="guestlist" id="guestlist" size="1">';
	$userlist = wats_build_user_list(1,__("None",'WATS'),0);
	foreach ($userlist AS $userlogin => $username)
	{
        echo '<option value="'.$userlogin.'" ';
        if ($userlogin == $wats_settings['wats_guest_user']) echo 'selected';
			echo '>'.$username.'</option>';
	}
	echo '</select></td></tr><tr><td>';
	echo '<div class="wats_tip" id="guestlist_tip">';
	echo __('The shared guest user is a user that must have at least contributor user level. This user will only have access to the ticket creation page on the admin side. You can share the guest user login/password with your visitors so that they can submit tickets without having to register first. This is a shared account.','WATS').'</div></td></tr></table><br />';
	
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
	echo '<input class="button-primary" type="submit" name="save" value="'.__('Save','WATS').'" /></p><br />';
	
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
	
	echo '<h3>'.__('Categories opened to submission','WATS').' :</h3><br />';
	echo '<table class="widefat" cellspacing="0" id="tablecat" style="text-align:center;"><thead><tr class="thead">';
	echo '<th scope="col" class="manage-column" width="10%" style="text-align:center;">ID</th>';
	echo '<th scope="col" class="manage-column" style="text-align:center;">'.__('Category','WATS').'</th>';
    echo '<th scope="col" class="manage-column" style="text-align:center;">'.__('Selection','WATS').'</th>';
    echo '</tr></thead><tbody class="list:user user-list">';
    wats_admin_display_options_list('wats_categories','catcheck');
	wats_admin_add_category_interface('resultsupcat','resultaddcat','idsupcat','idaddcat','Category','idcat');
	
	echo '</form><br /><br />';
}

?>