<?php

/*******************************************************/
/*                                                     */
/* Fonction de récupération de la priorité d'un ticket */
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
/* Fonction de récupération du type d'un ticket */
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
/* Fonction de récupération du status d'un ticket */
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
/* Fonction de mise à jour des metas d'un ticket */
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
	
	if (!update_post_meta($postID, 'wats_ticket_status', $_POST['wats_select_ticket_status']))
		add_post_meta($postID, 'wats_ticket_status', $_POST['wats_select_ticket_status']);
		
	if (!update_post_meta($postID, 'wats_ticket_type', $_POST['wats_select_ticket_type']))
		add_post_meta($postID, 'wats_ticket_type', $_POST['wats_select_ticket_type']);
		
	if (!update_post_meta($postID, 'wats_ticket_priority', $_POST['wats_select_ticket_priority']))
		add_post_meta($postID, 'wats_ticket_priority', $_POST['wats_select_ticket_priority']);
	
	if (!get_post_meta($postID,'wats_ticket_number',true))
	{
		add_post_meta($postID,'wats_ticket_number',wats_get_latest_ticket_number()+1);
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
/* Fonction d'affichage des détails d'un ticket (meta box) */
/*                                                         */
/***********************************************************/

function wats_ticket_details_meta_box($post)
{
	global $wats_settings;
	
	$wats_ticket_priority = $wats_settings['wats_priorities'];
	$wats_ticket_type = $wats_settings['wats_types'];
	$wats_ticket_status = $wats_settings['wats_statuses'];
	
	$ticket_priority = get_post_meta($post->ID,'wats_ticket_priority',true);
	$ticket_status = get_post_meta($post->ID,'wats_ticket_status',true);
	$ticket_type = get_post_meta($post->ID,'wats_ticket_type',true);
	
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
	echo '</select>';
	
	return;
}

?>
