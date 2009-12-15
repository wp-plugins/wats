<?php

/********************************************************/
/*                                                      */
/* Fonction de génération du lien d'édition d'un ticket */
/*                                                      */
/********************************************************/

function wats_get_edit_ticket_link( $id = 0, $context = 'display' ) {
	if ( !$post = &get_post( $id ) )
		return;

	if ( 'display' == $context )
		$action = 'action=edit&amp;';
	else
		$action = 'action=edit&';

	switch ( $post->post_type ) :
	case 'page' :
		if ( !current_user_can( 'edit_page', $post->ID ) )
			return;
		$file = 'page';
		$var  = 'post';
		break;
	case 'attachment' :
		if ( !current_user_can( 'edit_post', $post->ID ) )
			return;
		$file = 'media';
		$var  = 'attachment_id';
		break;
	case 'revision' :
		if ( !current_user_can( 'edit_post', $post->ID ) )
			return;
		$file = 'revision';
		$var  = 'revision';
		$action = '';
		break;
	default :
		if ( !current_user_can( 'edit_post', $post->ID ) )
			return;
		$file = 'admin.php?page=wats/wats-ticket';
		$var  = 'post';
		break;
	endswitch;

	return apply_filters( 'get_edit_post_link', admin_url("$file.php&{$action}$var=$post->ID"), $post->ID, $context );
}

/**********************************************************/
/*                                                        */
/* Fonction de récupération du lien d'édition d'un ticket */
/*                                                        */
/**********************************************************/

function wats_edit_ticket_link( $link = 'Edit This', $before = '', $after = '' ) {
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

/*******************************************/
/*                                                                                  */
/* Fonction de filtrage de l'url d'édition du ticket */
/*                                                                                 */
/******************************************/

function wats_filter_edit_ticket_link($link)
{
	preg_match("/post=([0-9])+/",$link,$matches);
	$matches = explode("=",$matches[0]);
	$postid = $matches[1];
	
	if (get_post_type($postid) == "ticket")
		$link = str_replace("post.php?","admin.php?page=wats/wats-ticket.php&amp;",$link);

	return($link);
}

?>
