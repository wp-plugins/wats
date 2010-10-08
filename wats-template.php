<?php

/*********************************************************/
/*                                                       */
/* Fonction de v�rification de la visibilit� des tickets */
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

/****************************************/
/*                                      */
/* Fonction de v�rification d'un ticket */
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
		echo '<div style="text-align:center;">Wordpress advanced <a href="'.WATS_BACKLINK.'">'.WATS_ANCHOR.'</a></div>';
	
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
	global $wpdb, $wats_settings, $current_user, $wp_version;
	
	if ((!is_home() || $wats_settings['wats_home_display'] == 1) && (!is_admin()) && ($wp_query->is_page == false))
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
	
	if ($wp_version >= '3.0' && is_admin() && $_GET['post_type'] == 'ticket')
	{
		if ($wats_settings['visibility'] == 2 && $current_user->user_level != 10)
			$where = str_replace($wpdb->posts.".post_type = 'ticket' AND",$wpdb->posts.".post_type = 'ticket' AND ".$wpdb->posts.".post_author = ".$current_user->ID." AND", $where);
	}
	
	if (is_search())
	{
		if ($wats_settings['visibility'] == 1 && !is_user_logged_in())
			$where = str_replace(", 'ticket'","", $where);
		else if ($wats_settings['visibility'] == 2 && is_user_logged_in() && $current_user->user_level < 10)
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
	global $wp_version,$wats_settings;
	
	if ($wp_version < '3.0')
	{
		register_taxonomy( 'category', 'ticket', array('hierarchical' => true, 'update_count_callback' => 'wats_update_ticket_term_count', 'label' => __('Categories'), 'query_var' => false, 'rewrite' => false) ) ;
		register_taxonomy( 'post_tag', 'post', array('hierarchical' => false, 'update_count_callback' => 'wats_update_ticket_term_count', 'label' => __('Post Tags'), 'query_var' => false, 'rewrite' => false) ) ;
	}
	else
	{
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
                      'rewrite' => array('slug'=>''),
                      'capability_type' => 'post',
                      'hierarchical' => false,
                      'menu_position' => null,
					  'menu_icon' => $plugin_url.'img/support.png',
                      'supports' => array('title','editor','comments'),
					  'register_meta_box_cb' => 'wats_ticket_meta_boxes',
					  'taxonomies' => $taxonomies);
		register_post_type('ticket',$args);

		//register_taxonomy( 'category', 'ticket', array('hierarchical' => true, 'update_count_callback' => 'wats_update_ticket_term_count', 'label' => __('Categories'), 'show_ui' => true, 'query_var' => false, 'rewrite' => false) ) ;
		//register_taxonomy( 'post_tag', 'ticket', array('hierarchical' => false, 'update_count_callback' => 'wats_update_ticket_term_count', 'label' => __('Post Tags'), 'show_ui' => true, 'query_var' => false, 'rewrite' => false) ) ;
	}
}

/*********************************************************/
/*                                                       */
/* Fonction de calcul du nombre d'�l�ments par cat�gorie */
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
/* Fonction d'ajout du num�ro de ticket dans le titre */
/*                                                    */
/******************************************************/

function wats_title_insert_ticket_number($title)
{
	global $post;

	if (wats_is_ticket($post) && ($title == wptexturize($post->post_title)))
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
/* Fonction de filtrage des cat�gories */
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
/* Fonction pour ajouter des colonnes personnalis�e */
/*                                                  */
/****************************************************/

function wats_edit_post_column($defaults)
{
	if ($defaults)
	{
		$defaults['type'] = __('Type','WATS');
		$defaults['priority'] = __('Priority','WATS');
		$defaults['status'] = __('Status','WATS');
		$defaults['title'] = __('Ticket','WATS');
		unset($defaults['tags']);
		return $defaults;
	}
	
	return;
}

/*****************************************************/
/*                                                   */
/* Fonction pour remplir les colonnes personnalis�es */
/*                                                   */
/*****************************************************/

function wats_edit_post_custom_column($column_name, $post_id)
{
	global $wats_settings;
	
	$wats_ticket_priority = $wats_settings['wats_priorities'];
	$wats_ticket_type = $wats_settings['wats_types'];
	$wats_ticket_status = $wats_settings['wats_statuses'];
	
	if ($column_name == 'priority')
	{
		$ticket_priority = get_post_meta($post_id,'wats_ticket_priority',true);
		echo $wats_ticket_priority[$ticket_priority];
	}
	else if ($column_name == 'status')
	{
		$ticket_status = get_post_meta($post_id,'wats_ticket_status',true);
		echo $wats_ticket_status[$ticket_status];
	}
	else if ($column_name == 'type')
	{
		$ticket_type = get_post_meta($post_id,'wats_ticket_type',true);
		echo $wats_ticket_type[$ticket_type];
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

?>