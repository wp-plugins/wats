<?php

/*********************************************************/
/*                                                       */
/* Fonction de vérification de la visibilité des tickets */
/*                                                       */
/*********************************************************/

function wats_check_visibility_rights()
{
	global $wats_settings, $current_user, $post;
	
	if ($wats_settings['visibility'] == 0)
		return true;
	else if ($wats_settings['visibility'] == 1 && is_user_logged_in())
		return true;
	else if ($wats_settings['visibility'] == 2 && is_user_logged_in() && ($current_user->user_level == 10 || $current_user->ID == $post->post_author))
		return true;
		
	return false;
}

/*******************************************************/
/*                                                     */
/* Fonction de processing Ajax de la liste des tickets */
/*                                                     */
/*******************************************************/

function wats_ticket_list_ajax_processing()
{
	global $wats_settings, $current_user;

	wats_load_settings();
	check_ajax_referer('filter-wats-tickets-list');
	if ($_POST[view] == 1)
	{
		$idtype = $_POST[idtype];
		$idpriority = $_POST[idpriority];
		$idstatus = $_POST[idstatus];
		if (($wats_settings['visibility'] == 0) || ($wats_settings['visibility'] == 1 && is_user_logged_in()) || ($wats_settings['visibility'] == 2 && is_user_logged_in() && $current_user->user_level == 10))
		{
			$idauthor = $_POST[idauthor];
			$idowner = $_POST[idowner];
		}
		else
		{
			$idauthor = "0";
			$idowner = "0";
		}
		if ($current_user->user_level == 10 && $wats_settings['filter_ticket_listing'] == 1)
			$idauthormetavalue = wats_fix_single_quotes(esc_html(stripslashes($_POST[idauthormetavalue])));
		else
			$idauthormetavalue = "0";
		$categoryfilter = $_POST[categoryfilter];
		$categorylistfilter = $_POST[categorylistfilter];
		echo wats_list_tickets($categoryfilter, $categorylistfilter, 1, $idtype, $idpriority, $idstatus, $idowner, $idauthor, $idauthormetavalue);
	}
	
	exit;
}

/********************************************************************************/
/*                                                                              */
/* Fonction de filtrage sur le contenu pour l'affichage de la table des tickets */
/*                                                                              */
/********************************************************************************/

function wats_list_tickets_filter($content)
{
    return (preg_replace_callback(WATS_TICKET_LIST_REGEXP, 'wats_list_tickets_args', $content));
}

/********************************************************************************/
/*                                                                              */
/* Fonction de filtrage des paramètres pour l'affichage de la table des tickets */
/*                                                                              */
/********************************************************************************/

function wats_list_tickets_args($args)
{
	global $wpdb;

	$args = explode(" ", rtrim($args[0], "]"));
	
	$cats = get_the_category();
	foreach ($cats as $cat)
	{
		$catlist[] = $cat->cat_ID;
	}
	$catlist = implode(',',$catlist);
	
	return (wats_list_tickets($args[1],$catlist,0,0,0,0,0,0,0));
}

/**************************************************************/
/*                                                            */
/* Fonction d'affichage des filtres pour la liste des tickets */
/*                                                            */
/**************************************************************/

function wats_list_tickets_filters()
{
	global $wats_settings, $current_user;
	
	$wats_ticket_priority = $wats_settings['wats_priorities'];
	$wats_ticket_type = $wats_settings['wats_types'];
	$wats_ticket_status = $wats_settings['wats_statuses'];
	
	$output = '<form action="" method="post">';
	wp_nonce_field('filter-wats-tickets-list','_wpnonce_ticket_list');
	
	$output .= '<p align="left">'.__('Ticket type','WATS').' : ';
	$output .= '<select name="wats_select_ticket_type_tl" id="wats_select_ticket_type_tl" class="wats_select">';
	$output .= '<option value=0>'.esc_html__('Any','WATS').'</option>';
	foreach ($wats_ticket_type as $key => $value)
		$output .= '<option value='.$key.'>'.esc_html__($value,'WATS').'</option>';
	$output .= '</select><br /><br />';
	
	$output .= __('Ticket priority','WATS').' : ';
	$output .= '<select name="wats_select_ticket_priority_tl" id="wats_select_ticket_priority_tl" class="wats_select">';
	$output .= '<option value=0>'.esc_html__('Any','WATS').'</option>';
	foreach ($wats_ticket_priority as $key => $value)
		$output .= '<option value='.$key.'>'.esc_html__($value,'WATS').'</option>';
	$output .= '</select><br /><br />';
	
	$output .=  __('Ticket status','WATS').' : ';
	$output .= '<select name="wats_select_ticket_status_tl" id="wats_select_ticket_status_tl" class="wats_select">';
	$output .= '<option value=0>'.esc_html__('Any','WATS').'</option>';
	foreach ($wats_ticket_status as $key => $value)
		$output .= '<option value='.$key.'>'.esc_html__($value,'WATS').'</option>';
	$output .= '</select><br /><br />';
	
	if (($wats_settings['visibility'] == 0) || ($wats_settings['visibility'] == 1 && is_user_logged_in()) || ($wats_settings['visibility'] == 2 && is_user_logged_in() && $current_user->user_level == 10))
	{
		$output .= __('Ticket author','WATS').' : ';
		$userlist = wats_build_user_list(0,__('Any','WATS'),0);
		$output .= '<select name="wats_select_ticket_author_tl" id="wats_select_ticket_author_tl" class="wats_select">';
		foreach ($userlist AS $userlogin => $username)
		{
			$output .= '<option value="'.$userlogin.'" >'.$username.'</option>';
		}
		$output .= '</select><br /><br />';
	
		if ($current_user->user_level == 10 && $wats_settings['filter_ticket_listing'] == 1)
		{
			$output .= __('Ticket author','WATS').' ('.$wats_settings['filter_ticket_listing_meta_key'].') : ';
			$metakeyvalues = wats_build_list_meta_values($wats_settings['filter_ticket_listing_meta_key']);
			$output .= '<select name="wats_select_ticket_author_meta_value_tl" id="wats_select_ticket_author_meta_value_tl" class="wats_select">';
			$output .= '<option value="0">'.__('Any','WATS').'</option>';
			foreach ($metakeyvalues AS $value)
			{
				$output .= '<option value="'.esc_attr($value).'">'.esc_html($value).'</option>';
			}
			$output .= '</select><br /><br />';
		}
	
		$output .= __('Ticket owner','WATS').' : ';
		$userlist = wats_build_user_list(0,0,0);
		$output .= '<select name="wats_select_ticket_owner_tl" id="wats_select_ticket_owner_tl" class="wats_select">';
		$output .= '<option value="0">'.__('Any','WATS').'</option>';
		$output .= '<option value="1">'.__('None','WATS').'</option>';
		foreach ($userlist AS $userlogin => $username)
		{
			$output .= '<option value="'.$userlogin.'">'.$username.'</option>';
		}
		$output .= '</select><br /></p>';
	}
	
	$output .= '<p class="submit">';
	$output .= '<input class="button-primary" type="submit" id="filter" name="filter" value="'.__('Filter','WATS').'" /></p></form>';
	
	return($output);
}

/********************************************************/
/*                                                      */
/* Fonction d'affichage du listing des tickets          */
/* Argument 1 : filtre catégorie (0 : all, 1 : current) */
/*                                                      */
/********************************************************/

function wats_list_tickets($filtercategory, $catlist, $view, $idtype, $idpriority, $idstatus, $idowner, $idauthor, $idauthormetavalue)
{
	global $wpdb, $wats_settings, $current_user;

	wats_load_settings();

	$joinoptions = 0;
	$leftjoin = "";
	$where = "";
	if ($view == 1)
	{
		if ($idtype > 0)
		{
			$leftjoin = " LEFT JOIN $wpdb->postmeta AS wp1 ON $wpdb->posts.ID = wp1.post_id ";
			$where = " AND (wp1.meta_key = 'wats_ticket_type' AND wp1.meta_value = '$idtype')";
			$joinoptions = 1;
		}
		if ($idpriority > 0)
		{
			$leftjoin .= " LEFT JOIN $wpdb->postmeta AS wp2 ON $wpdb->posts.ID = wp2.post_id ";
			$where .= " AND (wp2.meta_key = 'wats_ticket_priority' AND wp2.meta_value = '$idpriority')";
			$joinoptions = 1;
		}
		if ($idstatus > 0)
		{
			$leftjoin .= " LEFT JOIN $wpdb->postmeta AS wp3 ON $wpdb->posts.ID = wp3.post_id ";
			$where .= " AND (wp3.meta_key = 'wats_ticket_status' AND wp3.meta_value = '$idstatus')";
			$joinoptions = 1;
		}
		if ($idauthor != "0")
		{
			$idauthor = wats_get_user_ID_from_user_login($idauthor);
			$where .= " AND $wpdb->posts.post_author = '$idauthor'";
			$joinoptions = 1;
		}
		if ($idowner != "0" && $idowner != "1")
		{
			$leftjoin .= " LEFT JOIN $wpdb->postmeta AS wp4 ON $wpdb->posts.ID = wp4.post_id ";
			$where .= " AND (wp4.meta_key = 'wats_ticket_owner' AND wp4.meta_value = '$idowner')";
			$joinoptions = 1;
		}
		else if ($idowner == "1")
		{
			$leftjoin .= " LEFT JOIN $wpdb->postmeta AS wp4 ON $wpdb->posts.ID = wp4.post_id ";
			$where .= " AND (wp4.meta_key = 'wats_ticket_owner' AND wp4.meta_value = 0)";
			$joinoptions = 1;
		}
		if ($idauthormetavalue != "0")
		{
			$key = $wats_settings['filter_ticket_listing_meta_key'];
			$leftjoin .= " LEFT JOIN $wpdb->usermeta AS wp5 ON $wpdb->posts.post_author = wp5.user_id ";
			$where .= " AND (wp5.meta_key = '$key' AND wp5.meta_value = \"$idauthormetavalue\")";
			$joinoptions = 1;
		}
	}

	if ($filtercategory == 0)
	{
		if ($wats_settings['visibility'] == 0)
			$tickets = $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->posts".$leftjoin." WHERE $wpdb->posts.post_type = 'ticket' AND $wpdb->posts.post_status = 'publish'".$where));
		else if ($wats_settings['visibility'] == 1 && is_user_logged_in())
			$tickets = $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->posts".$leftjoin." WHERE $wpdb->posts.post_type = 'ticket' AND $wpdb->posts.post_status = 'publish'".$where));
		else if ($wats_settings['visibility'] == 2 && is_user_logged_in() && $current_user->user_level == 10)
			$tickets = $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->posts".$leftjoin." WHERE $wpdb->posts.post_type = 'ticket' AND $wpdb->posts.post_status = 'publish'".$where));
		else if ($wats_settings['visibility'] == 2 && is_user_logged_in())
			$tickets = $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->posts".$leftjoin." WHERE $wpdb->posts.post_type = 'ticket' AND $wpdb->posts.post_author = $current_user->ID AND $wpdb->posts.post_status = 'publish'".$where));
	}
	else if ($filtercategory == 1)
	{
		if ($wats_settings['visibility'] == 0)
			$tickets = $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->posts LEFT JOIN $wpdb->term_relationships ON $wpdb->posts.ID = $wpdb->term_relationships.object_id ".$leftjoin." WHERE  $wpdb->posts.post_type = 'ticket' AND $wpdb->posts.post_status = 'publish' AND $wpdb->term_relationships.term_taxonomy_id IN($catlist)".$where));
		else if ($wats_settings['visibility'] == 1 && is_user_logged_in())
			$tickets = $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->posts LEFT JOIN $wpdb->term_relationships ON $wpdb->posts.ID = $wpdb->term_relationships.object_id ".$leftjoin." WHERE  $wpdb->posts.post_type = 'ticket' AND $wpdb->posts.post_status = 'publish' AND $wpdb->term_relationships.term_taxonomy_id IN($catlist)".$where));
		else if ($wats_settings['visibility'] == 2 && is_user_logged_in() && $current_user->user_level == 10)
			$tickets = $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->posts LEFT JOIN $wpdb->term_relationships ON $wpdb->posts.ID = $wpdb->term_relationships.object_id ".$leftjoin." WHERE  $wpdb->posts.post_type = 'ticket' AND $wpdb->posts.post_status = 'publish' AND $wpdb->term_relationships.term_taxonomy_id IN($catlist)".$where));
		else if ($wats_settings['visibility'] == 2 && is_user_logged_in())
			$tickets = $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->posts LEFT JOIN $wpdb->term_relationships ON $wpdb->posts.ID = $wpdb->term_relationships.object_id ".$leftjoin." WHERE  $wpdb->posts.post_type = 'ticket' AND $wpdb->posts.post_author = $current_user->ID AND $wpdb->posts.post_status = 'publish' AND $wpdb->term_relationships.term_taxonomy_id IN($catlist)".$where));
	}
	$output = "";
	if ($view == 0)
	{
		$output .= wats_list_tickets_filters();
		$output .= '<input class="button-primary" type="hidden" id="categoryfilter" name="categoryfilter" value="'.$filtercategory.'" />';
		$output .= '<input class="button-primary" type="hidden" id="categorylistfilter" name="categorylistfilter" value="'.$catlist.'" />';
		$output .= '<div id="resultticketlist">';
	}

	$output .= '<table class="wats_table" cellspacing="0" id="tableticket" style="text-align:center;"><thead><tr class="thead">';
	if (($wats_settings['numerotation'] == 1) || ($wats_settings['numerotation'] == 2))
		$output .= '<th scope="col" style="text-align:center;">ID</th>';
	$output .= '<th scope="col" style="text-align:center;">'.__('Title','WATS').'</th>';
	$output .= '<th scope="col" style="text-align:center;">'.__('Category','WATS').'</th>';
	$output .= '<th scope="col" style="text-align:center;">'.__('Author','WATS').'</th>';
	if ($wats_settings['meta_column_ticket_listing'] == 1 && $current_user->user_level == 10)
		$output .= '<th scope="col" style="text-align:center;">'.$wats_settings['meta_column_ticket_listing_meta_key'].'</th>';
	$output .= '<th scope="col" style="text-align:center;">'.__('Owner','WATS').'</th>';
	$output .= '<th scope="col" style="text-align:center;">'.__('Creation date','WATS').'</th>';
	$output .= '<th scope="col" style="text-align:center;">'.__('Type','WATS').'</th>';
	$output .= '<th scope="col" style="text-align:center;">'.__('Priority','WATS').'</th>';
	$output .= '<th scope="col" style="text-align:center;">'.__('Status','WATS').'</th>';
	$output .= '</tr></thead><tbody>';

    $alt = false;
	if ($wats_settings['meta_column_ticket_listing'] == 1 && $current_user->user_level == 10)
		$colspan = 10;
	else
		$colspan = 9;
	if ($tickets)
	foreach ($tickets as $ticket)
	{
		$x = 1;
	
		$output .= '<tr';
		$output .= ($alt == true) ? ' class="alternate"' : '';
		if (($wats_settings['numerotation'] == 1) || ($wats_settings['numerotation'] == 2))
			$output .= '><td>'.wats_get_ticket_number($ticket->ID).'</td>';
		$output .= '<td><a href="'.get_permalink($ticket).'">'.htmlspecialchars(stripslashes($ticket->post_title)).'</a></td>';
		$categories = get_the_category($ticket->ID);
		if (!empty($categories))
		{
			$out = array();
			foreach ($categories as $c)
			{
				$out[] = wp_specialchars(sanitize_term_field('name', $c->name, $c->term_id, 'category', 'display'));
	
			}
			$output .= '<td>'.join(', ',$out).'</td>';
		} 
		else
		{
			$output .= '<td>'.__('Uncategorized','WATS').'</td>';
		}
		
		if (function_exists('get_the_author_meta'))
			$output .= '<td>'.get_the_author_meta('nickname',$ticket->post_author).'</td>';
		else
			$output .= '<td>'.get_the_author($ticket->post_author).'</td>';
			
		if ($wats_settings['meta_column_ticket_listing'] == 1 && $current_user->user_level == 10)
			$output .= '<td>'.esc_html(get_usermeta($ticket->post_author,$wats_settings['meta_column_ticket_listing_meta_key'])).'</td>';

		$ticket_owner = get_post_meta($ticket->ID,'wats_ticket_owner',true);
		if ($ticket_owner)
			$output .= '<td>'.get_post_meta($ticket->ID,'wats_ticket_owner',true).'</td>';
		else
			$output .= '<td>'.__('None','WATS').'</td>';
		$output .= '<td>'.get_post_time('M d, Y',false,$ticket,true).'</td>';
		$output .= '<td>'.wats_ticket_get_type($ticket).'</td>';
		$output .= '<td>'.wats_ticket_get_priority($ticket).'</td>';
		$output .= '<td>'.wats_ticket_get_status($ticket).'</td>';
		$output .= '</tr>';
		$alt = !$alt;
	}
	
	if ($x == 0)
		$output .= '<tr valign="middle"><td colspan="'.$colspan.'" style="text-align:center">'.__('No entry','WATS').'</td></tr>';
	
	$output .= '</tbody></table><br />';

	if ($view == 0)
		$output .= '</div>';
	
	return ($output);
}

/************************************************************************************************/
/*                                                                                              */
/* Fonction de filtrage sur le contenu pour l'affichage du formulaire de soumission des tickets */
/*                                                                                              */
/************************************************************************************************/

function wats_ticket_submit_form_filter($content)
{
	return (preg_replace_callback(WATS_TICKET_SUBMIT_FORM, 'wats_ticket_submit_form', $content));
}

/****************************************************************/
/*                                                              */
/* Fonction d'affichage du formulaire de soumission des tickets */
/*                                                              */
/****************************************************************/

function wats_ticket_submit_form()
{
	global $current_user, $wats_settings;

	if ($wats_settings['frontend_submit_form_access'] == 1 || ($wats_settings['frontend_submit_form_access'] == 2 && is_user_logged_in()))
	{
		$output = '<form action="" method="post" id="">';
		wp_nonce_field('filter-wats-submit-form','_wpnonce_ticket_submit_form');
		$output .= '<p align="left" class="wats_ticket_form_title">'.__('User details','WATS').'</p>';
		if (is_user_logged_in())
			$output .= '<p align="left">'.__('Logged in as ', 'WATS').$current_user->user_login.'</p>';
		else
		{
			$output .= '<p align="left">'.__('Name', 'WATS').' '.__('(required)','WATS').' <input type="text" name="name" id="name" value="" size="22" /></p>';
			$output .= '<p align="left">'.__('Mail', 'WATS').' '.__('(required)', 'WATS').' <input type="text" name="email" id="email" value="" size="22" /></p>';
			$output .= '<p align="left">'.__('Website', 'WATS').' <input type="text" name="url" id="url" value="" size="22" /></p><br />';
		}
		$output .= '<p align="left" class="wats_ticket_form_title">'.__('Ticket details','WATS').'</p>';
		$output .= '<p align="left">'.wats_ticket_details_meta_box($post,1).'</p>';
		
		$output .= '<p align="left" class="wats_ticket_form_title">'.__('Ticket title', 'WATS').'</p>';
		$output .= '<p align="left"><input type="text" name="ticket_title" id="ticket_title" value="" size="60" /></p><br />';
		$output .= '<p align="left" class="wats_ticket_form_title">'.__('Ticket description', 'WATS').'</p>';
		$output .= '<p align="left"><textarea name="ticket_content" id="ticket_content" cols="60" rows="10"></textarea></p>';

		$output .= '<p align="right"><input name="submit_ticket" type="submit" id="submit_ticket" value="'.__('Submit ticket', 'WATS').'" /></p>';
		$output .= '<div id="resultticketsubmitform"></div>';
		$output .= '</form>';
	}
	else if ($wats_settings['frontend_submit_form_access'] == 2)
		$output = __('Please authenticate yourself to be able to access the form.','WATS');
	else if ($wats_settings['frontend_submit_form_access'] == 0)
		$output = __('Sorry, the ticket submission form access has been disabled by the admin.','WATS');

	return ($output);
}

/*********************************************************/
/*                                                       */
/* Fonction de processing Ajax de soumission d'un ticket */
/*                                                       */
/*********************************************************/

function wats_ticket_submit_form_ajax_processing()
{
	global $wats_settings, $current_user;

	wats_load_settings();
	check_ajax_referer('filter-wats-submit-form');
	$create_metas = 0;
	if ($_POST[view] == 1 && ($wats_settings['frontend_submit_form_access'] == 1 || ($wats_settings['frontend_submit_form_access'] == 2 && is_user_logged_in())))
	{
		$categoryfilter = $_POST[categoryfilter];
		$categorylistfilter = $_POST[categorylistfilter];
		$post_title = stripslashes(strip_tags($_POST[ticket_title]));
		$post_content = stripslashes(strip_tags($_POST[ticket_content], '<br><i><b><u><strong>'));
		$error = '';
		
		if (!wats_is_string($post_title))
			$error .= __('The ticket title is empty or contains invalid characters. ','WATS');
		
		if (!wats_is_paragraph($post_content))
			$error .= __('The ticket description is empty or contains invalid characters. ','WATS');
		
		$post_category = array(get_option('default_email_category'));
		$post_type = 'ticket';
		
		if (is_user_logged_in())
		{
			$post_author = $current_user->ID;
		}
		else
		{
			$name = esc_html($_POST[name]);
			$email = esc_html($_POST[email]);
			$url = esc_html($_POST[url]);
			if (!wats_is_string(stripslashes($name)))
				$error .= __('The name is empty or contains invalid characters. ','WATS');
			if (is_email($email))
			{
				$userdata = get_user_by_email($email);
				if ($userdata)
					$post_author = $userdata->ID;
				else
				{
					$post_author = wats_get_user_ID_from_user_login($wats_settings['submit_form_default_author']);
					$create_metas = 1;
				}
			}
			else
				$error .= __('The email address is empty or contains invalid characters. ','WATS');
		}
		
		if ($wats_settings['frontend_submit_form_ticket_status'] == 0)
			$post_status = 'pending';
		else if ($wats_settings['frontend_submit_form_ticket_status'] == 1)
		{
			if (is_user_logged_in())
				$post_status = 'publish';
			else
				$post_status = 'pending';
		}
		else if ($wats_settings['frontend_submit_form_ticket_status'] == 2)
		{
			if (is_user_logged_in())
			{
				$user = new WP_User($post_author);
				$post_status = ($user->has_cap('publish_posts')) ? 'publish' : 'pending';
			}
			else
				$post_status = 'pending';
		}
		else if ($wats_settings['frontend_submit_form_ticket_status'] == 3)
			$post_status = 'publish';
		else
			$post_status = 'pending';
			
		$post_data = compact('post_content','post_title','post_author','post_category', 'post_status', 'post_type');
		$post_data = add_magic_quotes($post_data);

		if (strlen($error))
			echo __('Error : ','WATS').$error;
		else
		{
			$post_ID = wp_insert_post($post_data);
			if (is_wp_error($post_ID))
				echo "\n" . $post_ID->get_error_message();
			else
			{
				add_post_meta($post_ID,'wats_ticket_status',$_POST[idstatus]);
				add_post_meta($post_ID,'wats_ticket_type',$_POST[idtype]);
				add_post_meta($post_ID,'wats_ticket_priority',$_POST[idpriority]);
				add_post_meta($post_ID,'wats_ticket_number',wats_get_latest_ticket_number()+1);
				if ($create_metas == 1)
				{
					add_post_meta($post_ID,'wats_ticket_author_name',$name);
					add_post_meta($post_ID,'wats_ticket_author_email',$email);
					add_post_meta($post_ID,'wats_ticket_author_url',$url);
				}
				
				//add_post_meta($post_ID,'wats_ticket_owner',$_POST['wats_select_ticket_owner']);
				$ticketnumber = wats_get_ticket_number($post_ID);
				if ($ticketnumber != 0)
					$output = __('Ticket ','WATS').$ticketnumber.__(' has been successfully created!','WATS').'<br />';
				else
					$output = __('Ticket has been successfully created!','WATS').'<br />';
				if ($post_status == 'publish')
					$output .= __('You can access it ','WATS').'<a href="'.get_permalink($post_ID).'">'.__('here','WATS').'</a>.';
				else
					$output .= __('You will be able to access it ','WATS').'<a href="'.get_permalink($post_ID).'">'.__('here','WATS').'</a> '.__('when an admin would have approved it','WATS').'.';
				echo $output;
				wats_fire_admin_notification($post_ID);
			}
		}
	}
	else
		echo __('Error : can\'t submit form','WATS');
	
	exit;
}

/****************************************/
/*                                      */
/* Fonction de vérification d'un ticket */
/*                                      */
/****************************************/

function wats_is_ticket($post)
{
	if (get_post_type($post) == "ticket")
		return true;
	
	return false;
}

/*******************************************************/
/*                                                     */
/* Fonction de modification de la query dans la boucle */
/*                                                     */
/*******************************************************/

function wats_parse_query()
{
	global $wp_query, $wats_settings, $wp_version;

	if (((!is_home()) || ($wats_settings['wats_home_display'] == 1)) && (!is_admin()) && ($wp_query->is_page == false))
	{
		if (is_single())
			$wp_query->query_vars['post_type'] = 'any';
		if ($wp_query->is_single == true)
		{
			$wp_query->is_single = false;
			$wp_query->was_single = true;
			if ($wp_version >= '2.8')
				$wp_query->is_page = true;
		}
	}
	
	return;
}

/******************************/
/*                            */
/* Fonction d'ajout du footer */
/*                            */
/******************************/

function wats_wp_footer()
{

	if (is_front_page() && (!is_paged()))
		echo '<div style="text-align:center;">Wordpress advanced <a href="'.WATS_BACKLINK.'">'.WATS_ANCHOR.'</a> from <a href="'.WATS_BACKLINK2.'">'.WATS_ANCHOR2.'</a></div>';
	
	return;
}

/*********************************************************/
/*                                                       */
/* Fonction de redirection de la template pour un ticket */
/*                                                       */
/*********************************************************/

function wats_taxomony_template($template)
{
	global $wp_query;

	if ($wp_query->is_ticket == true)
	{
		if (file_exists(TEMPLATEPATH.'/single-ticket.php')) $template = TEMPLATEPATH.'/single-ticket.php';
		else $template = WATS_THEME_PATH.'/single-ticket.php';
	}
	
	return($template);
}

/*************************************************/
/*                                               */
/* Fonction de filtrage pour inclure les tickets */
/*                                               */
/*************************************************/

function wats_posts_where($where)
{
	global $wpdb, $wats_settings, $current_user;
	
	if (((!is_home()) || ($wats_settings['wats_home_display'] == 1)) && (!is_admin()) && ($wp_query->is_page == false))
	{
		if ($wats_settings['visibility'] == 0)
			$where = str_replace($wpdb->posts.".post_type = 'post' AND","(".$wpdb->posts.".post_type = 'post' OR ".$wpdb->posts.".post_type = 'ticket') AND", $where);
		else if ($wats_settings['visibility'] == 1 && is_user_logged_in())
			$where = str_replace($wpdb->posts.".post_type = 'post' AND","(".$wpdb->posts.".post_type = 'post' OR ".$wpdb->posts.".post_type = 'ticket') AND", $where);
		else if ($wats_settings['visibility'] == 2 && is_user_logged_in() && $current_user->user_level == 10)
			$where = str_replace($wpdb->posts.".post_type = 'post' AND","(".$wpdb->posts.".post_type = 'post' OR ".$wpdb->posts.".post_type = 'ticket') AND", $where);
		else if ($wats_settings['visibility'] == 2 && is_user_logged_in())
			$where = str_replace($wpdb->posts.".post_type = 'post' AND","(".$wpdb->posts.".post_type = 'post' OR (".$wpdb->posts.".post_type = 'ticket' AND ".$wpdb->posts.".post_author = ".$current_user->ID.")) AND", $where);
	}

	return($where);
}

/*******************************************************************/
/*                                                                 */
/* Fonction de filtrage pour inclure les tickets dans les archives */
/*                                                                 */
/*******************************************************************/

function wats_get_archives($where)
{
	$where = str_replace( " post_type = 'post' AND", " (post_type = 'post' OR post_type = 'ticket') AND", $where);

	return($where);
}

/****************************************************************************/
/*                                                                          */
/* Fonction de redirection de la template pour les commentaires d'un ticket */
/*                                                                          */
/****************************************************************************/

function wats_comments_template($template)
{
	global $wp_query;

	if ($wp_query->is_ticket == true)
	{
		if (file_exists(TEMPLATEPATH.'/comments-ticket.php')) 
			$template = TEMPLATEPATH.'/comments-ticket.php';
		else 
			$template = WATS_THEME_PATH.'/comments-ticket.php';
	}

	return($template);
}

/*********************************************************/
/*                                                       */
/* Fonction de modification de la query dans le frontend */
/*                                                       */
/*********************************************************/

function wats_template_redirect()
{
	global $wp_query, $wp_version;

	if (!is_admin() && isset($wp_query->was_single) && $wp_query->was_single == true)
	{
		$wp_query->is_single = true;
		if (wats_is_ticket($wp_query->post) == true)
		{
			$wp_query->is_ticket = true;
			$wp_query->is_tax = true;
		}
	}

	return;
}

/************************************/
/*                                  */
/* Fonction d'ajout d'une taxonomie */
/*                                  */
/************************************/

function wats_register_taxonomy()
{
	register_taxonomy( 'category', 'ticket', array('hierarchical' => true, 'update_count_callback' => 'wats_update_ticket_term_count', 'label' => __('Categories'), 'query_var' => false, 'rewrite' => false) ) ;
	register_taxonomy( 'post_tag', 'post', array('hierarchical' => false, 'update_count_callback' => 'wats_update_ticket_term_count', 'label' => __('Post Tags'), 'query_var' => false, 'rewrite' => false) ) ;
}

/*********************************************************/
/*                                                       */
/* Fonction de calcul du nombre d'éléments par catégorie */
/*                                                       */
/*********************************************************/

function wats_update_ticket_term_count($terms)
{
	global $wpdb;
 
    foreach ((array) $terms as $term)
	{
        $count = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $wpdb->term_relationships, $wpdb->posts WHERE $wpdb->posts.ID = $wpdb->term_relationships.object_id AND post_status = 'publish' AND (post_type = 'ticket' OR post_type = 'post') AND term_taxonomy_id = %d", $term));
        $wpdb->update($wpdb->term_taxonomy, compact('count'), array('term_taxonomy_id' => $term));
    }
}

/******************************************************/
/*                                                    */
/* Fonction d'ajout du numéro de ticket dans le titre */
/*                                                    */
/******************************************************/

function wats_title_insert_ticket_number($title)
{
	global $post;

	if (wats_is_ticket($post) && ($title == $post->post_title))
	{
		$value = wats_get_ticket_number($post->ID);
		if ($value)
			return($value." ".$title);
	}

	return ($title);
}

/*******************************************************/
/*                                                     */
/* Fonction de modification des liens previous et next */
/*                                                     */
/*******************************************************/

function wats_ticket_get_previous_next_post_where($where)
{
	global $wats_settings, $current_user;

	$searched_pattern = array(" AND p.post_type = 'ticket'"," AND p.post_type = 'post'");
	if ($wats_settings['visibility'] == 0)
		$where = str_replace($searched_pattern, " AND (p.post_type = 'post' OR p.post_type = 'ticket')", $where);
	else if ($wats_settings['visibility'] == 1 && is_user_logged_in())
		$where = str_replace($searched_pattern, " AND (p.post_type = 'post' OR p.post_type = 'ticket')", $where);
	else if ($wats_settings['visibility'] == 2 && is_user_logged_in() && $current_user->user_level == 10)
		$where = str_replace($searched_pattern, " AND (p.post_type = 'post' OR p.post_type = 'ticket')", $where);
	else if ($wats_settings['visibility'] == 2 && is_user_logged_in())
		$where = str_replace($searched_pattern, " AND (p.post_type = 'post' OR (p.post_type = 'ticket' AND p.post_author = ".$current_user->ID."))", $where);

	return ($where);
}

/***************************************/
/*                                     */
/* Fonction de filtrage des catégories */
/*                                     */
/***************************************/

function wats_list_terms_exclusions($args)
{
	global $wats_settings;
	
	$where = "";
	if ($wats_settings['wats_categories'])
	{
		$list = $wats_settings['wats_categories'];
		$catlist = array();
		foreach ($list as $key => $value)
		{
			$catlist[] = $key;
		}
		$catlist = implode(',',$catlist);
		$where = " AND t.term_id IN ($catlist)";
	}
	
	return $where;
}

php?>