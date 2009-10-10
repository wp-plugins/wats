<?php

/********************************************/
/*                                          */
/* Fonction de r�cup�ration du ticket owner */
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
/* Fonction de r�cup�ration de la priorit� d'un ticket */
/*                                                     */
/*******************************************************/

function wats_ticket_get_priority($post)
{
	global $wats_settings;
	
	$wats_ticket_priority = $wats_settings['wats_priorities'];
	
	return($wats_ticket_priority[get_post_meta($post->ID,'wats_ticket_priority',true)]);
}

/************************************************/
/*                                              */
/* Fonction de r�cup�ration du type d'un ticket */
/*                                              */
/************************************************/

function wats_ticket_get_type($post)
{
	global $wats_settings;
	
	$wats_ticket_type = $wats_settings['wats_types'];

	return($wats_ticket_type[get_post_meta($post->ID,'wats_ticket_type',true)]);
}

/**************************************************/
/*                                                */
/* Fonction de r�cup�ration du status d'un ticket */
/*                                                */
/**************************************************/

function wats_ticket_get_status($post)
{
	global $wats_settings;
	
	$wats_ticket_status = $wats_settings['wats_statuses'];
	
	return($wats_ticket_status[get_post_meta($post->ID,'wats_ticket_status',true)]);
}

/*************************************************/
/*                                               */
/* Fonction de mise � jour des metas d'un ticket */
/*                                               */
/*************************************************/

function wats_comment_update_meta($comment_id)
{
	$comment = get_comment($comment_id); 
	$status = $comment->comment_approved; 
	if($status !== "spam" ) // approved 
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
	
	return;
}

/************************************************************************/
/*                                                                      */
/* Fonction de filtrage du mail de l'exp�diteur du mail de notification */
/*                                                                      */
/************************************************************************/

function wats_mail_from()
{
	return get_option('admin_email');
}

/***********************************************************************/
/*                                                                     */
/* Fonction de filtrage du nom de l'exp�diteur du mail de notification */
/*                                                                     */
/***********************************************************************/

function wats_mail_from_name()
{
	return get_option('blogname');
}

/****************************************************/
/*                                                  */
/* Fonction de notification de cr�ation d'un ticket */
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
				$subject = __('New ticket submitted','WATS');
				$output = __('Hello ','WATS').get_usermeta($user->ID, 'first_name').",\r\n\r\n";
				$output .= __('A new ticket has been submitted into the system.','WATS');
				$output .= __('You can view it there :','WATS')."\r\n";
				$output .= __('+ Frontend side : ','WATS').get_permalink($postID)."\r\n\r\n";
				$output .= __('+ Admin side : ','WATS').wats_get_edit_ticket_link($postID)."\r\n\r\n";
				$output .= __('Regards','WATS').",\r\n\r\n";
				$output .= __('WATS Notification engine','WATS');
				wp_mail($user->user_email,$subject,$output);
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
	global $wp_meta_boxes;

	remove_meta_box('trackbacksdiv', 'post', 'normal');
	remove_meta_box('postexcerpt', 'post', 'normal');
	remove_meta_box('tagsdiv-post_tag', 'post', 'normal');
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
/* Fonction d'affichage des d�tails d'un ticket (meta box) */
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
		echo '>'.__($value,'WATS').'</option>';
	}
	echo '</select><br /><br />';
	
	echo __('Ticket priority','WATS').' : ';
	echo '<select name="wats_select_ticket_priority" id="wats_select_ticket_priority">';
	foreach ($wats_ticket_priority as $key => $value)
	{
		echo '<option value='.$key;
		if ($key == $ticket_priority)
			echo ' selected';
		echo '>'.__($value,'WATS').'</option>';
	}
	echo '</select><br /><br />';
	
	echo __('Ticket status','WATS').' : ';
	echo '<select name="wats_select_ticket_status" id="wats_select_ticket_status">';
	foreach ($wats_ticket_status as $key => $value)
	{
		echo '<option value='.$key;
		if ($key == $ticket_status)
			echo ' selected';
		echo '>'.__($value,'WATS').'</option>';
	}
	echo '</select><br /><br />';
	
	if ($wats_ticket_assign == 1 || ($wats_ticket_assign == 2 && $wats_ticket_assign_level <= $current_user->user_level))
	{
		echo __('Ticket owner','WATS').' : ';
		$userlist = wats_build_user_list(0,__("None",'WATS'));
		echo '<select name="wats_select_ticket_owner" id="wats_select_ticket_owner">';
		for ($i = 0; $userlist[$i] != false; $i++)
		{
			echo '<option value="'.$userlist[$i].'" ';
			if ($userlist[$i] == $ticket_owner) echo 'selected';
				echo '>'.$userlist[$i].'</option>';
		}
		echo '</select>';
	}

	if (is_admin())
	{
		setup_postdata($post);
		if ($post->ID)
		{
			echo '<br /><br />'.__('Ticket originator : ','WATS');
			the_author();
		}
	}
	
	return;
}

?>
