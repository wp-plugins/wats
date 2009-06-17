<?php

/********************************************************************************/
/*                                                                              */
/* Fonction pour remplir le widget de statistiques des tickets sur le dashboard */
/*                                                                              */
/********************************************************************************/

function wats_dashboard_widget_tickets()
{
	global $current_user;

	echo __('Global stats :','WATS').'<br />';
	echo '<li>'.__('Number of tickets created : ','WATS');
	echo wats_get_number_of_tickets_by_status(0,0).'</li>';
	echo '<li>'.__('Number of tickets closed : ','WATS');
	echo wats_get_number_of_tickets_by_status(wats_get_closed_status_id(),0).'</li><br /><br />';
	
	echo __('Your stats :','WATS').'<br />';
	echo '<li>'.__('Number of tickets created : ','WATS');
	echo wats_get_number_of_tickets_by_status(0,$current_user->ID).'</li>';
	echo '<li>'.__('Number of tickets closed : ','WATS');
	echo wats_get_number_of_tickets_by_status(wats_get_closed_status_id(),$current_user->ID).'</li>';
	
	return;
}

/********************************************************************************/
/*                                                                              */
/* Fonction pour ajouter le widget de statistiques des tickets sur le dashboard */
/*                                                                              */
/********************************************************************************/

function wats_dashboard_setup()
{
	wp_add_dashboard_widget('my_wp_dashboard_wats', 'Tickets', 'wats_dashboard_widget_tickets');

	return;
}

?>