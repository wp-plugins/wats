<?php

/***************************************************/
/*								     			   */
/* Fonction de mise à jour d'un profil utilisateur */
/*									    		   */
/***************************************************/

function wats_admin_save_user_profile()
{
	global $current_user, $wpdb, $wats_settings;

	if ($_POST['submit'])
	{	
		get_currentuserinfo();
		$old_user = $current_user;

		if ($current_user->user_level < 10)
			return;

		if ($_POST['user_id'])
			set_current_user($_POST['user_id']);

		$wats_capabilities_table = wats_init_capabilities_table();
		$wats_notifications_table = wats_init_notification_table();
		
		foreach ($wats_capabilities_table as $key => $value)
		{
			$result = $_POST[$key];
			if (($result == "yes") && (current_user_can($key) == 0))
				$current_user->add_cap($key,1);
			if (($result == "no") && (current_user_can($key) == 1))
				$current_user->remove_cap($key);
		}
		
		$notifications = get_usermeta($current_user->ID,'wats_notifications');
		foreach ($wats_notifications_table as $key => $value)
		{
			if ($wats_settings[$key] != 0)
			{
				$result = $_POST[$key];
				if ($result == "yes")
					$notifications[$key] = 1;
				else
					$notifications[$key] = 0;
			}
		}
		update_usermeta($current_user->ID,'wats_notifications',$notifications);
		
		set_current_user($old_user->ID);
	}
		
	return;
}

/**************************************************************/
/*								     						  */
/* Fonction d'édition du profil utilisateur (privilège admin) */
/*									    					  */
/**************************************************************/

function wats_admin_edit_user_profile()
{
    global $wpdb,$user_ID,$current_user,$wats_settings;

	if ($current_user->user_level < 10)
		return;

    $old_user = $current_user;
    if ($_GET['user_id'])
        set_current_user($_GET['user_id']);

	$wats_capabilities_table = wats_init_capabilities_table();
	$wats_notifications_table = wats_init_notification_table();
		
	$notifications = get_usermeta($current_user->ID,'wats_notifications');

	echo '<h3>'.__('Ticket system capabilities','WATS').'</h3><table class="form-table"><tbody>';
	foreach ($wats_capabilities_table as $key => $value)
	{
		$right = current_user_can($key) ? 1 : 0;
		echo '<tr><th><label>'.$value.'</label></th><td><select name="'.$key.'" id="'.$key.'" size=1>';
		echo '<option value="yes"';
		if ($right == 1) echo ' selected';
		echo '>'.__('Yes','WATS').'</option><option value="no"';
		if ($right == 0) echo ' selected';
		echo '>'.__('No','WATS').'</option></td></tr>';
	}
	echo '</tbody></table><br />';
	
	echo '<h3>'.__('Ticket system notifications','WATS').'</h3><table class="form-table"><tbody>';
	foreach ($wats_notifications_table as $key => $value)
	{
		echo '<tr><th><label>'.$value.'</label></th><td><select name="'.$key.'" id="'.$key.'" size=1 ';
		if ($wats_settings[$key] == 0)
			echo 'disabled=disabled ';
		echo '>';
		echo '<option value="yes"';
		if ($notifications[$key] == 1) echo ' selected';
		echo '>'.__('Yes','WATS').'</option><option value="no"';
		if ($notifications[$key] == 0) echo ' selected';
		echo '>'.__('No','WATS').'</option></td></tr>';
	}
	echo '</tbody></table><br /><br /><div class="wats_tip_visible">';
	echo __('Note : you can\'t set an option if it has been disabled globally by the admin.','WATS').'</div><br /><br />';
	
	echo '<br /><br />';
	
	set_current_user($old_user->ID);
	
	return;
}

/**************************************************************/
/*								     						  */
/* Fonction de création des metas pour un nouvel utilisateur */
/*									    					  */
/**************************************************************/

function wats_user_register($id)
{
	$wats_notifications = array();
	$wats_notifications['new_ticket_notification_admin'] = 0;
	$wats_notifications['ticket_update_notification_all_tickets'] = 0;
	$wats_notifications['ticket_update_notification_my_tickets'] = 0;
	update_usermeta($id,'wats_notifications',$wats_notifications);
	
	return;
}

?>