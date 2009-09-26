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
				if (wats_is_string(stripcslashes($idvalue)))
				{
					$wats_options[$res] = $idvalue;
					$wats_settings[$type] = $wats_options;
					update_option('wats', $wats_settings);
					$message_result = array('id' => "", 'idvalue' => stripcslashes($idvalue),'success' => "TRUE", 'error' => __("Entry successfully updated!",'WATS'));
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
			if (wats_is_string(stripcslashes($idvalue)))
			{
				if ($idcat > 0)
					$length = $idcat;
				else
					$length++;
				$wats_options[$length] = $idvalue;
				$wats_settings[$type] = $wats_options;
				update_option('wats', $wats_settings);
				$message_result = array('id' => $length, 'idvalue' => stripcslashes($idvalue),'success' => "TRUE", 'error' => __("Entry successfully added!",'WATS'));
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
			echo htmlspecialchars(stripcslashes($value)).'</td>';
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

/*********************************************************/
/*                                                       */
/* Fonction de construction de la liste des utilisateurs */
/*                                                       */
/*********************************************************/

function wats_build_user_list($min_level,$firstitem)
{
    global $wpdb;

    $users = $wpdb->get_results("SELECT ID FROM `{$wpdb->prefix}users`");
    $userlist = array();
	$userlist[] = $firstitem;

    foreach ($users AS $user)
    {
		$user = new WP_user($user->ID);
		if ($user->user_level >= $min_level)
			$userlist[] = $user->user_login;
	}
        
    return ($userlist);
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
		$wats_settings['ticket_assign_level'] = $_POST['ticket_assign_level'];
		$wats_settings['wats_guest_user'] = $_POST['guestlist'];
		$wats_settings['wats_home_display'] = isset($_POST['homedisplay']) ? 1 : 0;
		$wats_settings['new_ticket_notification_admin'] = isset($_POST['new_ticket_notification_admin']) ? 1 : 0;
		update_option('wats', $wats_settings);
	}
	
	wats_load_settings();

	echo '<H2><div style="text-align:center">WATS '.$wats_settings['wats_version'].'</div></H2>';
	echo '<form action="" method="post">';
	wp_nonce_field('update-wats-options');
	
	echo '<h3>'.__('Ticket numerotation','WATS').' :</h3>';
	echo '<table class="form-table">';
	echo '<tr><td><input type="radio" name="group1" value="0" ';
	echo ($wats_settings['numerotation'] == 0) ? 'checked' : '';
	echo '>'.__('None','WATS').' </td></tr>';
	echo '<tr><td><input type="radio" name="group1" value="1" ';
	echo ($wats_settings['numerotation'] == 1) ? 'checked' : '';
	echo '>'.__('Dated','WATS').' (ex : 090601-00001)</td></tr>';
	echo '<tr><td><input type="radio" name="group1" value="2" ';
	echo ($wats_settings['numerotation'] == 2) ? 'checked' : '';
	echo '>'.__('Numbered','WATS').' (ex : 1)</td></tr></table>';
	
	if ($wats_settings['numerotation'] > 0)
	{
		echo '<h3>'.__('Latest ticket ID','WATS').' : '.wats_get_latest_ticket_number().'</h3><br />';
	}
	
	echo '<h3>'.__('Tickets display','WATS').' :</h3>';
	echo '<table class="form-table">';
	echo '<tr><td><input type="checkbox" name="homedisplay"';
	if ($wats_settings['wats_home_display'] == 1)
		echo ' checked';
	echo '> '.__('Include tickets on homepage together with posts','WATS').'</td></tr></table><br />';

	echo '<h3>'.__('Notification','WATS').' :</h3>';
	echo '<table class="form-table">';
	echo '<tr><td><input type="checkbox" name="new_ticket_notification_admin"';
	if ($wats_settings['new_ticket_notification_admin'] == 1)
		echo ' checked';
	echo '> '.__('Notify admin by email upon new ticket submission','WATS').'</td></tr></table><br />';

	
	echo '<h3>'.__('Tickets visibility','WATS').' :</h3>';
	echo '<table class="form-table">';
	echo '<tr><td><input type="radio" name="group2" value="0" ';
	echo ($wats_settings['visibility'] == 0) ? 'checked' : '';
	echo '>'.__('Everybody can see all tickets','WATS').' </td></tr>';
	echo '<tr><td><input type="radio" name="group2" value="1" ';
	echo ($wats_settings['visibility'] == 1) ? 'checked' : '';
	echo '>'.__('Only registered users can see tickets','WATS').'</td></tr>';
	echo '<tr><td><input type="radio" name="group2" value="2" ';
	echo ($wats_settings['visibility'] == 2) ? 'checked' : '';
	echo '>'.__('Only ticket creator and admins can see tickets','WATS').'</td></tr></table>';
	
	echo '<h3>'.__('Tickets assignment','WATS').' :</h3>';
	echo '<table class="form-table">';
	echo '<tr><td><input type="radio" name="group3" value="0" ';
	echo ($wats_settings['ticket_assign'] == 0) ? 'checked' : '';
	echo '>'.__('No assignment possible','WATS').' </td></tr>';
	echo '<tr><td><input type="radio" name="group3" value="1" ';
	echo ($wats_settings['ticket_assign'] == 1) ? 'checked' : '';
	echo '>'.__('Everybody can assign a ticket','WATS').'</td></tr>';
	echo '<tr><td><input type="radio" name="group3" value="2" ';
	echo ($wats_settings['ticket_assign'] == 2) ? 'checked' : '';
	echo '>'.__('Only registered users can assign a ticket','WATS').'</td></tr></table>';

	echo '<h3>'.__('Ticket assignment capability minimum level requirement','WATS').' : ';
	echo '<td><select name="ticket_assign_level" id="ticket_assign_level" size="1">';
	for ($i = 0; $i != 11; $i++)
	{
        echo '<option value="'.$i.'" ';
        if ($i == $wats_settings['ticket_assign_level']) echo 'selected';
			echo '>'.$i.'</option>';
	}
	echo '</select></td></tr>';
	echo '</h3><br />';

	
	echo '<h3>'.__('Guest user','WATS').' : ';
	echo '<td><select name="guestlist" id="guestlist" size="1">';
	$userlist = wats_build_user_list(1,__("None",'WATS'));
	for ($i = 0; $userlist[$i] != false; $i++)
	{
        echo '<option value="'.$userlist[$i].'" ';
        if ($userlist[$i] == $wats_settings['wats_guest_user']) echo 'selected';
			echo '>'.$userlist[$i].'</option>';
	}
	echo '</select></td></tr>';
	echo '</h3><br />';

	
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
	
	echo '<p class="submit">';
	echo '<input class="button-primary" type="submit" name="save" value="'.__('Save','WATS').'" /></p></form>';
}

?>