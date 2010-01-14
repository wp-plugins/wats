<?php

/********************************************/
/*                                          */
/* Fonction de récupération du ticket owner */
/*                                          */
/********************************************/

function wats_ticket_get_owner($post)
{
	global $wats_settings;
	
	if ($wats_settings['ticket_assign'] != 0)
	{
		$ticket_owner = get_post_meta($post->ID,'wats_ticket_owner',true);
		if ($ticket_owner)
			echo __("Current ticket owner : ",'WATS').$ticket_owner."<br />";
	}

	return;
}


/*******************************************************/
/*                                                     */
/* Fonction de récupération de la priorité d'un ticket */
/*                                                     */
/*******************************************************/

function wats_ticket_get_priority($post)
{
	global $wats_settings;
	
	$wats_ticket_priority = $wats_settings['wats_priorities'];
	
	return(esc_html__($wats_ticket_priority[get_post_meta($post->ID,'wats_ticket_priority',true)],'WATS'));
}

/************************************************/
/*                                              */
/* Fonction de récupération du type d'un ticket */
/*                                              */
/************************************************/

function wats_ticket_get_type($post)
{
	global $wats_settings;
	
	$wats_ticket_type = $wats_settings['wats_types'];

	return(esc_html__($wats_ticket_type[get_post_meta($post->ID,'wats_ticket_type',true)],'WATS'));
}

/**************************************************/
/*                                                */
/* Fonction de récupération du status d'un ticket */
/*                                                */
/**************************************************/

function wats_ticket_get_status($post)
{
	global $wats_settings;
	
	$wats_ticket_status = $wats_settings['wats_statuses'];
	
	return(esc_html__($wats_ticket_status[get_post_meta($post->ID,'wats_ticket_status',true)],'WATS'));
}

/*************************************************/
/*                                               */
/* Fonction de mise à jour des metas d'un ticket */
/*                                               */
/*************************************************/

function wats_comment_update_meta($comment_id)
{
	$comment = get_comment($comment_id); 
	$status = $comment->comment_approved; 
	if ($status !== "spam") // approved 
	{ 
		$post_id =  $comment->comment_post_ID; 
		wats_ticket_save_meta($post_id);
	}

	return;
}

/************************************************/
/*                                              */
/* Fonction de sauvegarde des metas d'un ticket */
/*                                              */
/************************************************/

function wats_ticket_save_meta($postID)
{
	$newticket = 0;

	$newstatus = -1;
	$status = get_post_meta($postID,'wats_ticket_status',true);
	if ($status != $_POST['wats_select_ticket_status'])
		$newstatus = $_POST['wats_select_ticket_status'];
		
	$newtype = -1;
	$type = get_post_meta($postID,'wats_ticket_type',true);
	if ($type != $_POST['wats_select_ticket_type'])
		$newtype = $_POST['wats_select_ticket_type'];
		
	$newpriority = -1;
	$priority = get_post_meta($postID,'wats_ticket_priority',true);
	if ($priority != $_POST['wats_select_ticket_priority'])
		$newpriority = $_POST['wats_select_ticket_priority'];

	$newowner = -1;
	$owner = get_post_meta($postID,'wats_ticket_owner',true);
	if ($owner != $_POST['wats_select_ticket_owner'])
		$newowner = $_POST['wats_select_ticket_owner'];
	
	if (!update_post_meta($postID,'wats_ticket_status',$_POST['wats_select_ticket_status']))
		add_post_meta($postID,'wats_ticket_status',$_POST['wats_select_ticket_status']);

	if (!update_post_meta($postID,'wats_ticket_type',$_POST['wats_select_ticket_type']))
		add_post_meta($postID,'wats_ticket_type',$_POST['wats_select_ticket_type']);
		
	if (!update_post_meta($postID,'wats_ticket_priority',$_POST['wats_select_ticket_priority']))
		add_post_meta($postID,'wats_ticket_priority',$_POST['wats_select_ticket_priority']);
	
	if (!get_post_meta($postID,'wats_ticket_number',true))
	{
		add_post_meta($postID,'wats_ticket_number',wats_get_latest_ticket_number()+1);
		$newticket = 1;
	}
	
	if (!update_post_meta($postID,'wats_ticket_owner',$_POST['wats_select_ticket_owner']))
		add_post_meta($postID,'wats_ticket_owner',$_POST['wats_select_ticket_owner']);

	if ($newticket == 1)
		wats_fire_admin_notification($postID);
	else
		wats_fire_ticket_update_notification($postID,$newstatus,$newtype,$newpriority,$newowner);
	
	return;
}

/*****************************************************/
/*                                                   */
/* Fonction de hook durant la sauvegarde d'un ticket */
/*                                                   */
/*****************************************************/

function wats_insert_post_data($data)
{
	global $wats_settings, $current_user;
	
	if ($current_user->user_level == 10 && $wats_settings['call_center_ticket_creation'] == 1 && isset($_POST['wats_select_ticket_originator']) && $data['post_type'] == "ticket")
		$data['post_author'] = wats_get_user_ID_from_user_login($_POST['wats_select_ticket_originator']);

	return $data;
}

/************************************************************************/
/*                                                                      */
/* Fonction de filtrage du mail de l'expéditeur du mail de notification */
/*                                                                      */
/************************************************************************/

function wats_mail_from()
{
	return get_option('admin_email');
}

/***********************************************************************/
/*                                                                     */
/* Fonction de filtrage du nom de l'expéditeur du mail de notification */
/*                                                                     */
/***********************************************************************/

function wats_mail_from_name()
{
	return get_option('blogname');
}

/*******************************************************/
/*                                                     */
/* Fonction de notification de mise à jour d'un ticket */
/*                                                     */
/*******************************************************/

function wats_fire_ticket_update_notification($postID,$newstatus,$newtype,$newpriority,$newowner)
{
	global $wats_settings, $wpdb;

	wats_load_settings();

	$updates = '';
	if ($newstatus != -1)
	{
		$wats_ticket_status = $wats_settings['wats_statuses'];
		$updates .= __('Ticket status has been changed to : ','WATS').esc_html__($wats_ticket_status[$newstatus],'WATS').".\r\n";
	}
	if ($newtype != -1)
	{
		$wats_ticket_types = $wats_settings['wats_types'];
		$updates .= __('Ticket type has been changed to : ','WATS').esc_html__($wats_ticket_types[$newtype],'WATS').".\r\n";
	}
	if ($newpriority != -1)
	{
		$wats_ticket_priority = $wats_settings['wats_priorities'];
		$updates .= __('Ticket priority has been changed to : ','WATS').esc_html__($wats_ticket_priority[$newpriority],'WATS').".\r\n";
	}
	if ($newowner != -1)
	{
		if ($newowner == "0")
			$updates .= __('Ticket owner has been removed','WATS').".\r\n";
		else
			$updates .= __('Ticket owner has been assigned to : ','WATS').esc_html__($newowner,'WATS').".\r\n";
	}
	
	$ticket_author_id = 0;
	if ($wats_settings['ticket_update_notification_my_tickets'] == 1)
	{
		$post = get_post($postID);
		$userid = $post->post_author;
		add_filter('wp_mail_from', 'wats_mail_from');
		add_filter('wp_mail_from_name', 'wats_mail_from_name');
		$ticketnumber = get_post_meta($postID,'wats_ticket_number',true);
		$user = new WP_user($userid);
		$notifications = get_usermeta($user->ID,'wats_notifications');
		if (!isset($notifications['ticket_update_notification_my_tickets']) || $notifications['ticket_update_notification_my_tickets'] != 0)
		{
			$ticket_author_id = $userid;
			$subject = __('Ticket ','WATS').$ticketnumber.__(' has been updated','WATS');
			$output = __('Hello ','WATS').get_usermeta($user->ID, 'first_name').",\r\n\r\n";
			$output .= __('Ticket ','WATS').$ticketnumber.__(' has been updated.','WATS');
			$output .= __('You can view it there :','WATS')."\r\n";
			$output .= __('+ Frontend side : ','WATS').get_permalink($postID)."\r\n\r\n";
			$output .= __('+ Admin side : ','WATS').wats_get_edit_ticket_link($postID, 'mail')."\r\n\r\n";
			$output .= $updates."\r\n";
			$output .= wats_get_mail_notification_signature();
			wp_mail($user->user_email,$subject,$output);
		}
	}
	
	if ($wats_settings['ticket_update_notification_all_tickets'] == 1)
	{
		add_filter('wp_mail_from', 'wats_mail_from');
		add_filter('wp_mail_from_name', 'wats_mail_from_name');
		$users = $wpdb->get_results("SELECT ID FROM `{$wpdb->prefix}users`");
		$ticketnumber = get_post_meta($postID,'wats_ticket_number',true);
		foreach ($users AS $user)
		{
			if ($user->ID != $ticket_author_id)
			{
				$user = new WP_user($user->ID);
				$notifications = get_usermeta($user->ID,'wats_notifications');
				if (!isset($notifications['ticket_update_notification_all_tickets']) || $notifications['ticket_update_notification_all_tickets'] != 0)
				{
					$subject = __('Ticket ','WATS').$ticketnumber.__(' has been updated','WATS');
					$output = __('Hello ','WATS').get_usermeta($user->ID, 'first_name').",\r\n\r\n";
					$output .= __('Ticket ','WATS').$ticketnumber.__(' has been updated.','WATS');
					$output .= __('You can view it there :','WATS')."\r\n";
					$output .= __('+ Frontend side : ','WATS').get_permalink($postID)."\r\n\r\n";
					$output .= __('+ Admin side : ','WATS').wats_get_edit_ticket_link($postID, 'mail')."\r\n\r\n";
					$output .= $updates."\r\n";
					$output .= wats_get_mail_notification_signature();
					wp_mail($user->user_email,$subject,$output);
				}
			}
		}
	}

	return;
}

/****************************************************/
/*                                                  */
/* Fonction de notification de création d'un ticket */
/*                                                  */
/****************************************************/

function wats_fire_admin_notification($postID)
{
	global $wats_settings, $wpdb;

	wats_load_settings();

	if ($wats_settings['new_ticket_notification_admin'] == 1)
	{
		add_filter('wp_mail_from', 'wats_mail_from');
		add_filter('wp_mail_from_name', 'wats_mail_from_name');
		$users = $wpdb->get_results("SELECT ID FROM `{$wpdb->prefix}users`");
		foreach ($users AS $user)
		{
			$user = new WP_user($user->ID);
			if ($user->user_level == 10)
			{
				$notifications = get_usermeta($user->ID,'wats_notifications');
				if (!isset($notifications['new_ticket_notification_admin']) || $notifications['new_ticket_notification_admin'] != 0)
				{
					$subject = __('New ticket submitted','WATS');
					$output = __('Hello ','WATS').get_usermeta($user->ID, 'first_name').",\r\n\r\n";
					$output .= __('A new ticket has been submitted into the system.','WATS');
					$output .= __('You can view it there :','WATS')."\r\n";
					$output .= __('+ Frontend side : ','WATS').get_permalink($postID)."\r\n\r\n";
					$output .= __('+ Admin side : ','WATS').wats_get_edit_ticket_link($postID, 'mail')."\r\n\r\n";
					$output .= wats_get_mail_notification_signature();
					wp_mail($user->user_email,$subject,$output);
				}
			}
		}
	}
	
	return;
}

/************************************************/
/*                                              */
/* Fonction d'ajout des meta boxes dans l'admin */
/*                                              */
/************************************************/

function wats_ticket_meta_boxes()
{
	global $wp_meta_boxes, $wats_settings;

	remove_meta_box('trackbacksdiv', 'post', 'normal');
	remove_meta_box('postexcerpt', 'post', 'normal');
	if ($wats_settings['tickets_tagging'] == 0)
		remove_meta_box('tagsdiv-post_tag', 'post', 'normal');
	if ($wats_settings['tickets_custom_fields'] == 0)
		remove_meta_box('postcustom', 'post', 'normal');
	remove_meta_box('commentsdiv', 'post', 'normal');
	remove_meta_box('commentstatusdiv', 'post', 'normal');
	remove_meta_box('authordiv', 'post', 'normal');
	remove_meta_box('revisionsdiv', 'post', 'normal');
	// add_meta_box('tickethistorydiv',__('Ticket history','WATS'),'wats_ticket_history_meta_box','post','normal');
	add_meta_box('ticketdetailsdiv',__('Ticket details','WATS'),'wats_ticket_details_meta_box','post','side');
	
	foreach (array_keys($wp_meta_boxes['post']) as $context)
	{
		foreach (array_keys($wp_meta_boxes['post'][$context]) as $priority)
		{
			foreach (array_keys($wp_meta_boxes['post'][$context][$priority]) as $id)
			{
				if ($wp_meta_boxes['post'][$context][$priority][$id] != false)
				{
					if (($id != 'tickethistorydiv') && ($id != 'ticketdetailsdiv'))
					{
						$wp_meta_boxes['post'][$context][$priority][$id] = false;
					}
				}
			}
		}
	}
	
	return;
}

/***************************************************************/
/*                                                             */
/* Fonction d'affichage de l'historique d'un ticket (meta box) */
/*                                                             */
/***************************************************************/

function wats_ticket_history_meta_box($post)
{

	echo __('Here is the ticket history','WATS');

	return;
}

/***********************************************************/
/*                                                         */
/* Fonction d'affichage des détails d'un ticket (meta box) */
/*                                                         */
/***********************************************************/

function wats_ticket_details_meta_box($post)
{
	global $wats_settings, $current_user;
	
	$wats_ticket_priority = $wats_settings['wats_priorities'];
	$wats_ticket_type = $wats_settings['wats_types'];
	$wats_ticket_status = $wats_settings['wats_statuses'];
	$wats_ticket_assign = $wats_settings['ticket_assign'];
	$wats_ticket_assign_level = $wats_settings['ticket_assign_level'];
	
	$ticket_priority = get_post_meta($post->ID,'wats_ticket_priority',true);
	$ticket_status = get_post_meta($post->ID,'wats_ticket_status',true);
	$ticket_type = get_post_meta($post->ID,'wats_ticket_type',true);
	$ticket_owner = get_post_meta($post->ID,'wats_ticket_owner',true);
	
	echo __('Ticket type','WATS').' : ';
	echo '<select name="wats_select_ticket_type" id="wats_select_ticket_type">';
	foreach ($wats_ticket_type as $key => $value)
	{
		echo '<option value='.$key;
		if ($key == $ticket_type)
			echo ' selected';
		echo '>'.esc_html__($value,'WATS').'</option>';
	}
	echo '</select><br /><br />';
	
	echo __('Ticket priority','WATS').' : ';
	echo '<select name="wats_select_ticket_priority" id="wats_select_ticket_priority">';
	foreach ($wats_ticket_priority as $key => $value)
	{
		echo '<option value='.$key;
		if ($key == $ticket_priority)
			echo ' selected';
		echo '>'.esc_html__($value,'WATS').'</option>';
	}
	echo '</select><br /><br />';
	
	echo __('Ticket status','WATS').' : ';
	echo '<select name="wats_select_ticket_status" id="wats_select_ticket_status">';
	foreach ($wats_ticket_status as $key => $value)
	{
		echo '<option value='.$key;
		if ($key == $ticket_status)
			echo ' selected';
		echo '>'.esc_html__($value,'WATS').'</option>';
	}
	echo '</select><br /><br />';
	
	setup_postdata($post);
	
	if ($wats_ticket_assign == 1 || ($wats_ticket_assign == 2 && $wats_ticket_assign_level <= $current_user->user_level))
	{
		echo __('Ticket owner','WATS').' : ';
		if ($wats_settings['ticket_assign_user_list'] == 0)
		{
			$userlist = wats_build_user_list(0,__("None",'WATS'),0);
		}
		else if ($wats_settings['ticket_assign_user_list'] == 1)
		{
			$userlist = wats_build_user_list(10,__("None",'WATS'),0);
			if ($post->ID && !in_array(get_the_author(),$userlist))
			{
				$namelist = wats_build_formatted_name(get_the_author_meta('ID'));
				foreach ($namelist AS $login => $name)
					$userlist[$login] = $name;
			}
		}
		else if ($wats_settings['ticket_assign_user_list'] == 2)
		{
			$userlist = wats_build_user_list(0,__("None",'WATS'),'wats_ticket_ownership');
			if ($post->ID && !in_array(get_the_author(),$userlist))
			{
				$namelist = wats_build_formatted_name(get_the_author_meta('ID'));
				foreach ($namelist AS $login => $name)
					$userlist[$login] = $name;
			}
		}
		
		echo '<select name="wats_select_ticket_owner" id="wats_select_ticket_owner">';
		foreach ($userlist AS $userlogin => $username)
		{
			echo '<option value="'.$userlogin.'" ';
			if ($userlogin == $ticket_owner) echo 'selected';
				echo '>'.$username.'</option>';
		}
		echo '</select>';
	}

	if (is_admin())
	{
		if ($post->ID)
		{
			echo '<br /><br />'.__('Ticket originator : ','WATS');
			if ($current_user->user_level == 10 && $wats_settings['call_center_ticket_creation'] == 1)
			{
				$userlist = wats_build_user_list(0,0,0);
				echo '<select name="wats_select_ticket_originator" id="wats_select_ticket_originator">';
				foreach ($userlist AS $userlogin => $username)
				{
					echo '<option value="'.$userlogin.'" ';
					if ($userlogin == get_the_author_meta('user_login')) echo 'selected';
						echo '>'.$username.'</option>';
				}
				echo '</select>';
			}
			else
				echo get_the_author();
		}
		else if ($wats_settings['call_center_ticket_creation'] == 1)
		{
			if ($current_user->user_level == 10)
			{
				echo '<br /><br />'.__('Ticket originator : ','WATS');
				$userlist = wats_build_user_list(0,0,0);
				echo '<select name="wats_select_ticket_originator" id="wats_select_ticket_originator">';
				foreach ($userlist AS $userlogin => $username)
				{
					echo '<option value="'.$userlogin.'" ';
					if ($current_user->user_login == $userlogin) echo 'selected';
					echo '>'.$username.'</option>';
				}
				echo '</select>';
			}
		}
	}
	
	return;
}

?>
