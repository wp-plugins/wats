<?php

/********************************************************************************/
/*                                                                              */
/* Fonction de remplissage du widget de statistiques des tickets dans le frontend */
/*                                                                              */
/********************************************************************************/

function wats_frontend_widget_stats_content()
{
	global $current_user;

	echo '<strong>'.__('Global stats :','WATS').'</strong><br /><ul>';
	echo '<li>'.__('Tickets created :','WATS');
	echo ' '.wats_get_number_of_tickets_by_status(0,0).'</li>';
	echo '<li>'.__('Tickets closed :','WATS');
	echo ' '.wats_get_number_of_tickets_by_status(wats_get_closed_status_id(),0).'</li></ul>';

	if (is_user_logged_in())
	{
		echo '<strong>'.__('Your stats :','WATS').'</strong><br /><ul>';
		echo '<li>'.__('Tickets created :','WATS');
		echo ' '.wats_get_number_of_tickets_by_status(0,$current_user->ID).'</li>';
		echo '<li>'.__('Tickets closed :','WATS');
		echo ' '.wats_get_number_of_tickets_by_status(wats_get_closed_status_id(),$current_user->ID).'</li></ul>';
	}

	return;
}

/********************************************************************************/
/*                                                                              */
/* Fonction d'initialisation du widget de statistiques des tickets dans le frontend */
/*                                                                              */
/********************************************************************************/

function wats_frontend_widget_stats($args)
{
	
	extract($args);
  
	echo $before_widget;
	echo $before_title.__('Tickets','WATS').$after_title;
	wats_frontend_widget_stats_content();
	echo $after_widget;
	
	return;
}

/********************************************************************************/
/*                                                                              */
/* Fonction d'enregistrement du widget de statistiques des tickets dans le frontend */
/*                                                                              */
/********************************************************************************/

function wats_frontend_widget_stats_init()
{
   register_sidebar_widget(__('Tickets','WATS'), 'wats_frontend_widget_stats');
}

add_action('plugins_loaded','wats_frontend_widget_stats_init');

/********************************************************************************/
/*                                                                              */
/* Fonction pour remplir le widget de statistiques des tickets sur le dashboard */
/*                                                                              */
/********************************************************************************/

function wats_dashboard_widget_tickets()
{
	global $current_user;

	echo __('Global stats :','WATS').'<br />';
	echo '<li class="wats">'.__('Number of tickets created : ','WATS');
	echo ' '.wats_get_number_of_tickets_by_status(0,0).'</li>';
	echo '<li class="wats">'.__('Number of tickets closed : ','WATS');
	echo ' '.wats_get_number_of_tickets_by_status(wats_get_closed_status_id(),0).'</li><br /><br />';
	
	echo __('Your stats :','WATS').'<br />';
	echo '<li class="wats">'.__('Number of tickets created : ','WATS');
	echo ' '.wats_get_number_of_tickets_by_status(0,$current_user->ID).'</li>';
	echo '<li class="wats">'.__('Number of tickets closed : ','WATS');
	echo ' '.wats_get_number_of_tickets_by_status(wats_get_closed_status_id(),$current_user->ID).'</li>';
	
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