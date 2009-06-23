<?php

/********************************************************************************/
/*                                                                              */
/* Fonction de filtrage sur le contenu pour l'affichage de la table des tickets */
/*                                                                              */
/********************************************************************************/

function wats_list_tickets_filter($content)
{
    return (preg_replace_callback(WATS_TICKET_LIST_REGEXP, 'wats_list_tickets', $content));
}

/************************************************/
/*                                              */
/* Fonction d'affichage du listing des tickets  */
/* Argument 1 : catégorie (0 : all, 1 : current */
/*                                              */
/************************************************/

function wats_list_tickets($args)
{
	global $wpdb, $wats_settings;
	
	$args = explode(" ", rtrim($args[0], "]"));
	$filtercategory = $args[1];
	if ($filtercategory == 0)
	{
		$tickets = $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->posts WHERE  $wpdb->posts.post_type = 'ticket' AND $wpdb->posts.post_status = 'publish'"));
	}
	else if ($filtercategory == 1)
	{
		$catlist = array();
		$cats = get_the_category();
		foreach ($cats as $cat)
		{
			$catlist[] = $cat->cat_ID;
		}
		$catlist = implode(',',$catlist);
		wats_debug($catlist);
		$tickets = $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->posts LEFT JOIN $wpdb->term_relationships ON $wpdb->posts.ID = $wpdb->term_relationships.object_id WHERE $wpdb->posts.post_type = 'ticket' AND $wpdb->posts.post_status = 'publish' AND $wpdb->term_relationships.term_taxonomy_id IN($catlist)"));
	}

	$output = '<table class="wats_table" cellspacing="0" id="tableticket" style="text-align:center;"><thead><tr class="thead">';
	if (($wats_settings['numerotation'] == 1) || ($wats_settings['numerotation'] == 2))
		$output .= '<th scope="col" style="text-align:center;">ID</th>';
	$output .= '<th scope="col" style="text-align:center;">'.__('Title','WATS').'</th>';
	$output .= '<th scope="col" style="text-align:center;">'.__('Category','WATS').'</th>';
	$output .= '<th scope="col" style="text-align:center;">'.__('Author','WATS').'</th>';
	$output .= '<th scope="col" style="text-align:center;">'.__('Creation date','WATS').'</th>';
	$output .= '<th scope="col" style="text-align:center;">'.__('Type','WATS').'</th>';
	$output .= '<th scope="col" style="text-align:center;">'.__('Priority','WATS').'</th>';
	$output .= '<th scope="col" style="text-align:center;">'.__('Status','WATS').'</th>';
	$output .= '</tr></thead><tbody>';
   
    $alt = false;
	foreach ($tickets as $ticket)
	{
		$x = 1;
	
		$output .= '<tr';
		$output .= ($alt == true) ? ' class="alternate"' : '';
		if (($wats_settings['numerotation'] == 1) || ($wats_settings['numerotation'] == 2))
			$output .= '><td>'.wats_get_ticket_number($ticket->ID).'</td>';
		$output .= '<td><a href="'.get_permalink($ticket).'">'.htmlspecialchars(stripcslashes($ticket->post_title)).'</a></td>';
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
		
		$output .= '<td>'.get_the_author_meta('nickname',$ticket->post_author).'</td>';
		$output .= '<td>'.get_post_time(get_option('date_format'),false,$ticket,true).'</td>';
		$output .= '<td>'.wats_ticket_get_type($ticket).'</td>';
		$output .= '<td>'.wats_ticket_get_priority($ticket).'</td>';
		$output .= '<td>'.wats_ticket_get_status($ticket).'</td>';
		$output .= '</tr>';
		$alt = !$alt;
	}
	if ($x == 0)
	{
		$output .= '<tr valign="middle"><td colspan="3" style="text-align:center">'.__('No entry','WATS').'</td></tr>';
	}
	$output .= '</tbody></table><br />';

	return($output);
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
	global $wp_query, $wats_settings;

	if (((!is_home()) || ($wats_settings['wats_home_display'] == 1)) && (!is_admin() && ($wp_query->is_page == false)))
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
	$where = str_replace( " AND p.post_type = 'post'", " AND (p.post_type = 'post' OR p.post_type = 'ticket')", $where);
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