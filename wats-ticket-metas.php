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
	$post_id =  $comment->comment_post_ID;
	if ($status !== "spam" && wats_is_ticket($post_id))
	{
		wats_ticket_save_meta($post_id,get_post($post_id));
	}

	return;
}

/************************************************/
/*                                              */
/* Fonction de sauvegarde des metas d'un ticket */
/*                                              */
/************************************************/

function wats_ticket_save_meta($postID,$post)
{

	if ($post->post_type == 'ticket')
	{

		$newticket = 0;
		
		if ((defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) || $post->post_status == 'auto-draft')
		{
			return $postID;
		}
		

		
		$newstatus = -1;
		if (isset($_POST['wats_select_ticket_status']))
		{
			$status = get_post_meta($postID,'wats_ticket_status',true);
			if ($status != $_POST['wats_select_ticket_status'])
				$newstatus = $_POST['wats_select_ticket_status'];
				
			if (!update_post_meta($postID,'wats_ticket_status',$_POST['wats_select_ticket_status']))
				add_post_meta($postID,'wats_ticket_status',$_POST['wats_select_ticket_status']);
		}

		$newtype = -1;
		if (isset($_POST['wats_select_ticket_type']))
		{
			$type = get_post_meta($postID,'wats_ticket_type',true);
			if ($type != $_POST['wats_select_ticket_type'])
				$newtype = $_POST['wats_select_ticket_type'];
				
			if (!update_post_meta($postID,'wats_ticket_type',$_POST['wats_select_ticket_type']))
				add_post_meta($postID,'wats_ticket_type',$_POST['wats_select_ticket_type']);
		}
			
		$newpriority = -1;
		if (isset($_POST['wats_select_ticket_type']))
		{
			$priority = get_post_meta($postID,'wats_ticket_priority',true);
			if ($priority != $_POST['wats_select_ticket_priority'])
				$newpriority = $_POST['wats_select_ticket_priority'];
			
			if (!update_post_meta($postID,'wats_ticket_priority',$_POST['wats_select_ticket_priority']))
				add_post_meta($postID,'wats_ticket_priority',$_POST['wats_select_ticket_priority']);
		}
		
		$newowner = -1;
		if (isset($_POST['wats_select_ticket_owner']))
		{
			$owner = get_post_meta($postID,'wats_ticket_owner',true);
			if ($owner != $_POST['wats_select_ticket_owner'])
				$newowner = $_POST['wats_select_ticket_owner'];
			
			if (!update_post_meta($postID,'wats_ticket_owner',$_POST['wats_select_ticket_owner']))
				add_post_meta($postID,'wats_ticket_owner',$_POST['wats_select_ticket_owner']);
		}
		
		if (!get_post_meta($postID,'wats_ticket_number',true))
		{
			add_post_meta($postID,'wats_ticket_number',wats_get_latest_ticket_number()+1);
			$newticket = 1;
		}
	}
	
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
	
	if ($data['post_type'] == "ticket")
		$data['comment_status'] = 'open';
	
	return $data;
}

/************************************************/
/*                                              */
/* Fonction d'ajout des meta boxes dans l'admin */
/*                                              */
/************************************************/

function wats_ticket_meta_boxes()
{
	global $wp_meta_boxes, $wats_settings, $wp_version;

	if ($wp_version < '3.0')
	{
		remove_meta_box('trackbacksdiv', 'post', 'normal');
		remove_meta_box('postexcerpt', 'post', 'normal');
		if ($wats_settings['tickets_tagging'] == 0)
			remove_meta_box('tagsdiv-post_tag', 'post', 'normal');
		if ($wats_settings['tickets_custom_fields'] == 0)
			remove_meta_box('postcustom', 'post', 'normal');
		remove_meta_box('authordiv', 'post', 'normal');
		remove_meta_box('revisionsdiv', 'post', 'normal');
		remove_meta_box('commentsdiv', 'post', 'normal');
		remove_meta_box('commentstatusdiv', 'post', 'normal');
		// add_meta_box('tickethistorydiv',__('Ticket history','WATS'),'wats_ticket_history_meta_box','post','normal');
		add_meta_box('ticketdetailsdiv',__('Ticket details','WATS'),'wats_ticket_details_meta_box','post','normal');
		
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
	}
	else
	{
		remove_meta_box('commentsdiv', 'ticket', 'normal');
		remove_meta_box('commentstatusdiv', 'ticket', 'normal');
		add_meta_box('ticketdetailsdiv',__('Ticket details','WATS'),'wats_ticket_details_meta_box','ticket','normal');
		add_meta_box('categorydiv', __('Categories'), 'post_categories_meta_box', 'ticket', 'side', 'core');
		if ($wats_settings['tickets_custom_fields'] == 1)
			add_meta_box('postcustom', __('Custom Fields'), 'post_custom_meta_box', 'ticket', 'normal', 'core');
		if ($wats_settings['tickets_tagging'] == 1)
			add_meta_box('tagsdiv-post_tag', __('Tags'), 'post_tags_meta_box', 'ticket', 'side', 'core');
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
/* view : 0 (comment form et ticket edit/creation admin    */
/* view : 1 (ticket creation frontend) 					   */
/*                                                         */
/***********************************************************/

function wats_ticket_details_meta_box($post,$view=0)
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
	
	$output = '';
	
	if ($view == 1)
		$output .= '<div class="wats_select_ticket_type_frontend">';
	$output .= __('Ticket type','WATS').' : ';
	$output .= '<select name="wats_select_ticket_type" id="wats_select_ticket_type" class="wats_select">';
	foreach ($wats_ticket_type as $key => $value)
	{
		$output .= '<option value='.$key;
		if ($key == $ticket_type || (!$ticket_type && $key == $wats_settings['default_ticket_type']))
			$output .= ' selected';
		$output .= '>'.esc_html__($value,'WATS').'</option>';
	}
	$output .= '</select><br /><br />';
	
	if ($view == 1)
		$output .= '</div><div class="wats_select_ticket_priority_frontend">';
	$output .= __('Ticket priority','WATS').' : ';
	$output .= '<select name="wats_select_ticket_priority" id="wats_select_ticket_priority" class="wats_select">';
	foreach ($wats_ticket_priority as $key => $value)
	{
		$output .= '<option value='.$key;
		if ($key == $ticket_priority || (!$ticket_priority && $key == $wats_settings['default_ticket_priority']))
			$output .= ' selected';
		$output .= '>'.esc_html__($value,'WATS').'</option>';
	}
	$output .= '</select><br /><br />';
		
	if ($view == 1)
		$output .= '</div><div class="wats_select_ticket_status_frontend">';
	$output .= __('Ticket status','WATS').' : ';
	$output .= '<select name="wats_select_ticket_status" id="wats_select_ticket_status" class="wats_select">';
	foreach ($wats_ticket_status as $key => $value)
	{
		$output .= '<option value='.$key;
		if ($key == $ticket_status || (!$ticket_status && $key == $wats_settings['default_ticket_status']))
			$output .= ' selected';
		$output .= '>'.esc_html__($value,'WATS').'</option>';
	}
	$output .= '</select><br /><br />';
	if ($view == 1)
		$output .= '</div>';
		
	if ($wats_ticket_assign == 1 || ($wats_ticket_assign == 2 && $wats_ticket_assign_level <= $current_user->user_level))
	{
		$output .= __('Ticket owner','WATS').' : ';
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
		
		$output .= '<select name="wats_select_ticket_owner" id="wats_select_ticket_owner" class="wats_select">';
		foreach ($userlist AS $userlogin => $username)
		{
			$output .= '<option value="'.$userlogin.'" ';
			if ($userlogin == $ticket_owner) $output .= 'selected';
				$output .= '>'.$username.'</option>';
		}
		$output .= '</select><br /><br />';
	}
	
	setup_postdata($post);

	if (is_admin())
	{
		if ($post->ID)
		{
			$output .= __('Ticket originator : ','WATS');
			if ($current_user->user_level == 10 && $wats_settings['call_center_ticket_creation'] == 1)
			{
				$userlist = wats_build_user_list(0,0,0);
				$output .= '<select name="wats_select_ticket_originator" id="wats_select_ticket_originator" class="wats_select">';
				foreach ($userlist AS $userlogin => $username)
				{
					$output .= '<option value="'.$userlogin.'" ';
					if ($userlogin == get_the_author_meta('user_login')) $output .= 'selected';
						$output .= '>'.$username.'</option>';
				}
				$output .= '</select>';
			}
			else
				$output .= get_the_author();
		}
		else if ($wats_settings['call_center_ticket_creation'] == 1)
		{
			if ($current_user->user_level == 10)
			{
				$output .= __('Ticket originator : ','WATS');
				$userlist = wats_build_user_list(0,0,0);
				$output .= '<select name="wats_select_ticket_originator" id="wats_select_ticket_originator" class="wats_select">';
				foreach ($userlist AS $userlogin => $username)
				{
					$output .= '<option value="'.$userlogin.'" ';
					if ($current_user->user_login == $userlogin) $output .= 'selected';
					$output .= '>'.$username.'</option>';
				}
				$output .=  '</select>';
			}
		}
		
		$ticket_author_name = get_post_meta($post->ID,'wats_ticket_author_name',true);
		if ($ticket_author_name)
			$output .= '<br /><br />'.__('Ticket author name : ','WATS').$ticket_author_name;
		
		$ticket_author_email = get_post_meta($post->ID,'wats_ticket_author_email',true);
		if ($ticket_author_email)
			$output .= '<br /><br />'.__('Ticket author email : ','WATS').'<a href="mailto:'.$ticket_author_email.'">'.$ticket_author_email.'</a>';
		
		$ticket_author_url = get_post_meta($post->ID,'wats_ticket_author_url',true);
		if ($ticket_author_url)
			$output .= '<br /><br />'.__('Ticket author url : ','WATS').'<a href="'.$ticket_author_url.'">'.$ticket_author_url.'</a>';
	}

	if ($view == 1)
		return ($output);
	else
		echo $output;
}

?>