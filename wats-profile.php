<?php

/***************************************************/
/*								     			   */
/* Fonction de mise à jour d'un profil utilisateur */
/*									    		   */
/***************************************************/

function wats_admin_save_user_profile()
{
	global $current_user, $wpdb;

	if ($_POST['submit'])
	{	
		get_currentuserinfo();
		$old_user = $current_user;

		if ($current_user->user_level < 10)
			return;

		if ($_GET['user_id'])
			set_current_user($_GET['user_id']);

		$wats_capabilities_table['wats_ticket_ownership'] = __('Tickets can be assigned to this user','WATS');
		foreach ($wats_capabilities_table as $key => $value)
		{
			$result = $_POST[$key];
			if (($result == "yes") && (current_user_can($key) == 0))
				$current_user->add_cap($key,1);
			if (($result == "no") && (current_user_can($key) == 1))
				$current_user->remove_cap($key);
		}
		
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
    global $wpdb,$user_ID,$current_user;

	if ($current_user->user_level < 10)
		return;

	$wats_capabilities_table['wats_ticket_ownership'] = __('Tickets can be assigned to this user','WATS');
    $old_user = $current_user;
    if ($_GET['user_id'])
        set_current_user($_GET['user_id']);

	echo '<h3>'.__('Ticket system capabilities','WATS').'</h3><table class="form-table"><tbody>';
	foreach ($wats_capabilities_table as $key => $value)
	{
		$right = current_user_can($key) ? 1 : 0;
		echo '<tr><th><label>'.$value.'</label></th><td><select name="'.$key.'" id="'.$key.'" size=1>';
		echo '<option value="yes"';
		if ($right == 1) echo ' selected';
		echo ' >'.__('Yes','WATS').'</option><option value="no"';
		if ($right == 0) echo ' selected';
		echo ' >'.__('No','WATS').'</option></td></tr>';
	}

	echo "</tbody></table><br /><br /><br />";
	set_current_user($old_user->ID);
	
	return;
}
?>