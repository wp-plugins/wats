<?php

/********************************************************/
/*                                                      */
/* Fonction de génération du lien d'édition d'un ticket */
/*                                                      */
/********************************************************/

function wats_get_edit_ticket_link($id = 0, $context = 'display')
{
	global $wp_version;
	
	if (!$post = &get_post($id))
		return;

	if ('display' == $context)
		$action = '&action=edit&amp;';
	else
		$action = '&action=edit';

	if ($wp_version < '3.0')
		$file = 'admin.php?page=wats/wats-ticket.php'.$action.'&post='.$post->ID;
	else
	{
		$file = get_post_type_object($post->post_type);
		$file = sprintf($file->_edit_link.$action, $post->ID);
	}

	return apply_filters('get_edit_post_link', admin_url($file), $post->ID, $context);
}

/**********************************************************/
/*                                                        */
/* Fonction de récupération du lien d'édition d'un ticket */
/*                                                        */
/**********************************************************/

function wats_edit_ticket_link( $link = 'Edit This', $before = '', $after = '' ) 
{
	global $post;

	if ( $post->post_type == 'page' ) {
		if ( !current_user_can( 'edit_page', $post->ID ) )
			return;
	} else {
		if ( !current_user_can( 'edit_post', $post->ID ) )
			return;
	}

	$link = '<a class="post-edit-link" href="' . wats_get_edit_ticket_link( $post->ID ) . '" title="' . attribute_escape( __( 'Edit post' ) ) . '">' . $link . '</a>';
	echo $before . apply_filters( 'edit_post_link', $link, $post->ID ) . $after;
}

/*****************************************************/
/*                                                   */
/* Fonction de filtrage de l'url d'édition du ticket */
/*                                                   */
/*****************************************************/

function wats_filter_edit_ticket_link($link)
{
	global $wp_version;

	preg_match("/post=([0-9])+/",$link,$matches);
	$matches = explode("=",$matches[0]);
	$postid = $matches[1];
	
	if (get_post_type($postid) == "ticket" && $wp_version < '3.0')
		$link = str_replace("post.php?","admin.php?page=wats/wats-ticket.php&amp;",$link);

	return($link);
}

?>
