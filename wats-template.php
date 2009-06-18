<?php

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
	global $wp_query;
	
	if (!is_admin() && ($wp_query->is_page == false))
		$wp_query->query_vars['post_type'] = 'any';

	return;
}

/******************************/
/*                            */
/* Fonction d'ajout du footer */
/*                            */
/******************************/

function wats_wp_footer()
{
	echo __('Page generated by ').'<a href="'.WATS_BACKLINK.'">'.WATS_ANCHOR.'</a>';
	
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
		
		add_action('wp_footer','wats_wp_footer');
	}

	return($template);
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
		if (file_exists(TEMPLATEPATH.'/comments-ticket.php')) $template = TEMPLATEPATH.'/comments-ticket.php';
		else $template = WATS_THEME_PATH.'/comments-ticket.php';
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
	global $wp_query;

	if (!is_admin() && isset($wp_query->is_single) && $wp_query->is_single == true)
	{
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
	$where = str_replace( " AND p.post_type = 'post'", '', $where);
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
	
	$list = $wats_settings['wats_categories'];
	$catlist = array();
	foreach ($list as $key => $value)
	{
		$catlist[] = $key;
	}
	$catlist = implode(',',$catlist);
	$where = " AND t.term_id IN ($catlist)";
	return $where;
}

php?>