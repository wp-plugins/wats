<?php

/************************************************************/
/*                                                          */
/* Fonction de vérification de l'ouverture des commentaires */
/*                                                          */
/************************************************************/

function wats_check_ticket_update_rights()
{
	global $wats_settings, $current_user, $post;
	
	wats_load_settings();
	
	if (get_post_meta($post->ID,'wats_ticket_status',true) == wats_get_closed_status_id() && !current_user_can('administrator'))
	{
		echo '<div id="ticket_is_closed">'.__('The ticket is closed. Only administrators could reopen it.','WATS').'</div>';
		return false;
	}
	else if ($wats_settings['visibility'] == 2 && is_user_logged_in() && !current_user_can('administrator') && $current_user->ID != $post->post_author && $wats_settings['ticket_visibility_read_only_capability'] == 1 && current_user_can('wats_ticket_read_only'))
	{
		echo '<div id="ticket_is_read_only">'.__('Only admins and ticket author can update this ticket.','WATS').'</div>';
		return false;
	}
		
	return true;
}

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
	else if ($wats_settings['visibility'] == 2 && is_user_logged_in() && (current_user_can('administrator') || $current_user->ID == $post->post_author || (!is_admin() && $wats_settings['ticket_visibility_read_only_capability'] == 1 && current_user_can('wats_ticket_read_only'))))
		return true;
		
	return false;
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

/******************************/
/*                            */
/* Fonction d'ajout du footer */
/*                            */
/******************************/

function wats_wp_footer()
{
	if (is_front_page() && (!is_paged()))
		echo '<div style="text-align:center;">Wordpress advanced <a href="'.WATS_BACKLINK.'">'.WATS_ANCHOR.'</a></div>';
		
	return;
}

/*********************************************************/
/*                                                       */
/* Fonction de redirection de la template pour un ticket */
/*                                                       */
/*********************************************************/

function wats_ticket_template_loader($template)
{
	global $wp_query;

	if (is_singular() && wats_is_ticket($wp_query->post) == true)
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
	
	if ((!is_home() || $wats_settings['wats_home_display'] == 1) && (!is_admin()) && (!is_page()) && (!is_search()))
	{
		if ($wats_settings['visibility'] == 0)
			$where = str_replace($wpdb->posts.".post_type = 'post' AND","(".$wpdb->posts.".post_type = 'post' OR ".$wpdb->posts.".post_type = 'ticket') AND", $where);
		else if ($wats_settings['visibility'] == 1 && is_user_logged_in())
			$where = str_replace($wpdb->posts.".post_type = 'post' AND","(".$wpdb->posts.".post_type = 'post' OR ".$wpdb->posts.".post_type = 'ticket') AND", $where);
		else if ($wats_settings['visibility'] == 2 && is_user_logged_in() && (current_user_can('administrator') || ($wats_settings['ticket_visibility_read_only_capability'] == 1 && current_user_can('wats_ticket_read_only'))))
			$where = str_replace($wpdb->posts.".post_type = 'post' AND","(".$wpdb->posts.".post_type = 'post' OR ".$wpdb->posts.".post_type = 'ticket') AND", $where);
		else if ($wats_settings['visibility'] == 2 && is_user_logged_in())
			$where = str_replace($wpdb->posts.".post_type = 'post' AND","(".$wpdb->posts.".post_type = 'post' OR (".$wpdb->posts.".post_type = 'ticket' AND ".$wpdb->posts.".post_author = ".$current_user->ID.")) AND", $where);
	}
	
	if (is_admin() && isset($_GET['post_type']) && $_GET['post_type'] == 'ticket')
	{
		if ($wats_settings['visibility'] == 2 && !current_user_can('administrator'))
			$where = str_replace($wpdb->posts.".post_type = 'ticket' AND",$wpdb->posts.".post_type = 'ticket' AND ".$wpdb->posts.".post_author = ".$current_user->ID." AND", $where);
	}

	if (is_search())
	{
		if ($wats_settings['visibility'] == 1 && !is_user_logged_in())
			$where = str_replace(", 'ticket'","", $where);
		else if ($wats_settings['visibility'] == 2 && is_user_logged_in() && !current_user_can('administrator') && ($wats_settings['ticket_visibility_read_only_capability'] == 0 || !current_user_can('wats_ticket_read_only')))
		{
			$where = str_replace(", 'ticket'","", $where);
			$where .= " OR (".$wpdb->posts.".post_type = 'ticket' AND ".$wpdb->posts.".post_author = ".$current_user->ID.")";
		}
		else if ($wats_settings['visibility'] == 2 && !is_user_logged_in())
		{
			$where = str_replace(", 'ticket'","", $where);
		}
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

	if (wats_is_ticket($wp_query->post) == true)
	{
		if (file_exists(TEMPLATEPATH.'/comments-ticket.php')) 
			$template = TEMPLATEPATH.'/comments-ticket.php';
		else 
			$template = WATS_THEME_PATH.'/comments-ticket.php';
	}

	return($template);
}

/************************************/
/*                                  */
/* Fonction d'ajout d'une taxonomie */
/*                                  */
/************************************/

function wats_register_taxonomy()
{
	global $wats_settings;

	$taxonomies[] =  'category';
	if ($wats_settings['tickets_tagging'] == 1)
		$taxonomies[] = 'post_tag';

	$plugin_url = trailingslashit(get_option('siteurl')) . 'wp-content/plugins/' . basename(dirname(__FILE__)) .'/';
	$labels = array('name' => __('Tickets','WATS'),
					'singular_name' => __('ticket','WATS'),
					'add_new' => __('Add New','WATS'),
					'add_new_item' => __('Add New Ticket','WATS'),
					'edit_item' => __('Edit Ticket','WATS'),
					'new_item' => __('New Ticket','WATS'),
					'view_item' => __('View Ticket','WATS'),
					'search_items' => __('Search Ticket','WATS'),
					'not_found' =>  __('No tickets found','WATS'),
					'not_found_in_trash' => __('No tickets found in Trash','WATS'), 
					'parent_item_colon' => '');
	$args = array('labels' => $labels,
				  'public' => true,
				  'publicly_queryable' => true,
				  'show_ui' => true, 
				  'query_var' => true,
				  'rewrite' => true,
				  'capability_type' => 'post',
				  'hierarchical' => false,
				  'menu_position' => null,
				  'menu_icon' => $plugin_url.'img/support.png',
				  'supports' => array('title','editor','comments'),
				  'register_meta_box_cb' => 'wats_ticket_meta_boxes',
				  'taxonomies' => $taxonomies);
	register_post_type('ticket',$args);
	
	return;
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

function wats_title_insert_ticket_number($title, $postID)
{
	global $wats_printing_inline_data;

	if (get_post_type($postID) == "ticket" && $wats_printing_inline_data == false)
	{
		$value = wats_get_ticket_number($postID);
		
		if ($value)
			return($value." ".$title);
	}
	
	if (is_admin() && $wats_printing_inline_data == false)
		$wats_printing_inline_data = true;

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
	else if ($wats_settings['visibility'] == 2 && is_user_logged_in() && (current_user_can('administrator') || ($wats_settings['ticket_visibility_read_only_capability'] == 1 && current_user_can('wats_ticket_read_only'))))
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

/****************************************************/
/*                                                  */
/* Fonction de filtrage du flux RSS des commentaire */
/*                                                  */
/****************************************************/

function wats_filter_comments_rss($cwhere)
{

	$cwhere = $cwhere." AND post_type != 'ticket'";
	
	return $cwhere;
}

/****************************************************/
/*                                                  */
/* Fonction pour ajouter des colonnes personnalisée */
/*                                                  */
/****************************************************/

function wats_edit_post_column($defaults)
{
	global $wats_settings;

	if ($defaults)
	{
		if ($wats_settings['ticket_type_key_enabled'] == 1)
			$defaults['type'] = __('Type','WATS');
		if ($wats_settings['ticket_priority_key_enabled'] == 1)
			$defaults['priority'] = __('Priority','WATS');
		if ($wats_settings['ticket_status_key_enabled'] == 1)
			$defaults['status'] = __('Status','WATS');
		$defaults['title'] = __('Ticket','WATS');
		if ($wats_settings['ticket_product_key_enabled'] == 1)
			$defaults['product'] = __('Product','WATS');
		unset($defaults['tags']);
		return $defaults;
	}
	
	return;
}

/*****************************************************/
/*                                                   */
/* Fonction pour remplir les colonnes personnalisées */
/*                                                   */
/*****************************************************/

function wats_edit_post_custom_column($column_name, $post_id)
{
	global $wats_settings;
	
	$wats_ticket_priority = $wats_settings['wats_priorities'];
	$wats_ticket_type = $wats_settings['wats_types'];
	$wats_ticket_status = $wats_settings['wats_statuses'];
	$wats_ticket_product = $wats_settings['wats_products'];
	
	if ($column_name == 'priority')
	{
		$ticket_priority = get_post_meta($post_id,'wats_ticket_priority',true);
		if (wats_is_numeric($ticket_priority) && isset($wats_ticket_priority[$ticket_priority]))
			echo $wats_ticket_priority[$ticket_priority];
	}
	else if ($column_name == 'status')
	{
		$ticket_status = get_post_meta($post_id,'wats_ticket_status',true);
		if (wats_is_numeric($ticket_status) && isset($wats_ticket_status[$ticket_status]))
			echo $wats_ticket_status[$ticket_status];
	}
	else if ($column_name == 'type')
	{
		$ticket_type = get_post_meta($post_id,'wats_ticket_type',true);
		if (wats_is_numeric($ticket_type) && isset($wats_ticket_type[$ticket_type]))
			echo $wats_ticket_type[$ticket_type];
	}
	else if ($column_name == 'product')
	{
		$ticket_product = get_post_meta($post_id,'wats_ticket_product',true);
		if (wats_is_numeric($ticket_product) && isset($wats_ticket_product[$ticket_product]))
			echo $wats_ticket_product[$ticket_product];
	}
	
	return;
}

/*************************************************/
/*                                               */
/* Fonction de rewrite du title dans le frontend */
/*                                               */
/*************************************************/

function wats_wp_title($title)
{
	global $post;

	if (is_single() && $post->post_type == 'ticket')
	{
		$title = $post->post_title." | ";
	}
	
	return $title;
}

/*************************************************/
/*                                               */
/* Fonction de filtrage des posts rows actions */
/*                                               */
/*************************************************/

function wats_post_row_actions($actions, $post)
{
	global $wats_printing_inline_data;
	
	$wats_printing_inline_data = false;

	return $actions;
}

?>