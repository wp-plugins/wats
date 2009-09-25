<?php

/*****************************************/
/*                                       */
/* Fonction d'accroche dans l'admin head */
/*                                       */
/*****************************************/

function wats_admin_head()
{

	return;
}

/*********************************************/
/*                                           */
/* Fonction d'accroche dans le frontend head */
/*                                           */
/*********************************************/

function wats_enqueue_script_frontend()
{
	wp_enqueue_script('jquery');
	$ajaxfileloc = trailingslashit(get_option('siteurl')) . 'wp-content/plugins/' . basename(dirname(__FILE__)) .'/js/jquery.tablesorter.min.js';
	wp_enqueue_script('tablesorter',$ajaxfileloc,array('jquery'));
	$ajaxfileloc = trailingslashit(get_option('siteurl')) . 'wp-content/plugins/' . basename(dirname(__FILE__)) . '/wats-js-commons.php';
    wp_enqueue_script('wats-js-commons', $ajaxfileloc);
	$ajaxfileloc = trailingslashit(get_option('siteurl')) . 'wp-content/plugins/' . basename(dirname(__FILE__)) .'/wats-ticket-list-ajax.php';
	wp_enqueue_script('wats-ticket-list',$ajaxfileloc);
		
	return;
}

/*********************************/
/*                               */
/* Fonction de chargement du css */
/*                               */
/*********************************/

function wats_add_my_stylesheet()
{
    $plugin_url = trailingslashit(get_option('siteurl')) . 'wp-content/plugins/' . basename(dirname(__FILE__)) .'/';
	$myStyleFile = $plugin_url."css/wats.css";
    wp_register_style('wats_css', $myStyleFile); 
    wp_enqueue_style('wats_css');
	
	return;
}

/*********************************************/
/*                                           */
/* Fonction de chargement des scripts jquery */
/*                                           */
/*********************************************/

function wats_admin_scripts()
{
	$plugin_url = trailingslashit(get_option('siteurl')) . 'wp-content/plugins/' . basename(dirname(__FILE__)) .'/';

	wp_enqueue_script('jquery');

	$editableurl = $plugin_url.'js/jquery.editable.js';
	wp_enqueue_script('editable',$editableurl,array('jquery'));
	
	return;
}

/************************************************************/
/*                                                          */
/* Fonction de gestion de l'utilisateur invité dans l'admin */
/*                                                          */
/************************************************************/

function wats_customize_guest_admin()
{
	global $menu;
	
	foreach ($menu as $key => $value)
	{
		unset($menu[$key]);
	}
	
    if (!empty($_SERVER["REQUEST_URI"]))
		$requesteduri = $_SERVER["REQUEST_URI"];
    else
		$requesteduri = getenv('REQUEST_URI');

	$targeturi = admin_url().'admin.php?page=wats/wats-ticket-new.php';
	$subtargeturi = substr_replace($targeturi,'',0,strlen(get_option('siteurl')));
	$result = strpos($requesteduri,$subtargeturi);

	if ($result === false)
		wp_safe_redirect($targeturi);
	
	return;
}

/************************************************/
/*                                              */
/* Fonction pour ajouter les menus dans l'admin */
/*                                              */
/************************************************/

function wats_add_admin_page()
{
	global $wats_settings, $menu, $current_user, $_registered_pages;

	wats_load_settings();
	$plugin_url = trailingslashit(get_option('siteurl')) . 'wp-content/plugins/' . basename(dirname(__FILE__)) .'/';
	
	if ($current_user->user_login == $wats_settings['wats_guest_user'])
	{
		wats_customize_guest_admin();
	}
	
	if (function_exists('add_options_page'))
	{
		$page = add_options_page('Wats Options', 'Wats Options',10, basename(__FILE__), 'wats_options_admin_menu');
		add_action('admin_print_scripts-'.$page,'wats_options_admin_head');
	}

	if (function_exists('add_menu_page') && function_exists('add_submenu_page'))
	{
		if ($current_user->user_login == $wats_settings['wats_guest_user'])
		{
			add_menu_page(__('New Ticket','WATS'),__('Tickets','WATS'),0,WATS_PATH.'wats-ticket-new.php',0,$plugin_url.'img/support.png');
			add_submenu_page(WATS_PATH.'wats-ticket-new.php',__('New ticket','WATS'),__('New ticket','WATS'),0,WATS_PATH.'wats-ticket-new.php');
			add_action('admin_head-wats/wats-ticket-new.php','wats_ticket_creation_admin_head');
		}
		else if (current_user_can('edit_posts') == 1)
		{
			add_menu_page(__('Modify','WATS'),__('Tickets','WATS'),0,WATS_PATH.'wats-edit.php',0,$plugin_url.'img/support.png');
			add_submenu_page(WATS_PATH.'wats-edit.php',__('Edit Tickets','WATS'),__('Edit Tickets','WATS'),0,WATS_PATH.'wats-edit.php');
			add_action('admin_head-wats/wats-edit.php','wats_ticket_edit_admin_head');

			add_submenu_page(WATS_PATH.'wats-edit.php',__('New ticket','WATS'),__('New ticket','WATS'),0,WATS_PATH.'wats-ticket-new.php');
			add_action('admin_head-wats/wats-ticket-new.php','wats_ticket_creation_admin_head');
		}
	}
	
	$_registered_pages[get_plugin_page_hookname('wats/wats-ticket.php','')] = true;
	
	return;
}

/***********************************************************/
/*                                                         */
/* Fonction de chargement des scripts de la page d'options */
/*                                                         */
/***********************************************************/

function wats_options_admin_head()
{
	wats_admin_scripts();

	$ajaxfileloc = trailingslashit(get_option('siteurl')) . 'wp-content/plugins/' . basename(dirname(__FILE__)) . '/wats-options-ajax.php';
    wp_enqueue_script('wats-options-ajax', $ajaxfileloc);

	$ajaxfileloc = trailingslashit(get_option('siteurl')) . 'wp-content/plugins/' . basename(dirname(__FILE__)) . '/wats-js-commons.php';
    wp_enqueue_script('wats-js-commons', $ajaxfileloc);
	
	return;
}

/**********************************************************/
/*                                                        */
/* Fonction de chargement des scripts de la page d'édtion */
/*                                                        */
/**********************************************************/

function wats_ticket_edit_admin_head()
{
	wats_admin_scripts();
	/*wp_print_scripts('inline-edit-post');*/
	add_filter('list_terms_exclusions','wats_list_terms_exclusions');
	
	return;
}

/*************************************************************/
/*                                                           */
/* Fonction de chargement des scripts de la page de création */
/*                                                           */
/*************************************************************/

function wats_ticket_creation_admin_head()
{
	add_filter('list_terms_exclusions','wats_list_terms_exclusions');
    //wats_admin_scripts();
	
	remove_action('media_buttons','media_buttons');
	wats_ticket_meta_boxes();
	
	wp_admin_css('thickbox');
	add_thickbox();
	wp_enqueue_script('jquery-schedule');
	wp_print_scripts('autosave');
	wp_print_scripts('suggest');
	wp_enqueue_script('jquery-ui-core');
	wp_enqueue_script('jquery-ui-tabs');
	wp_enqueue_script('jquery-ui-sortable');
	wp_print_scripts('postbox');
	wp_print_scripts('slug');
	wp_print_scripts('post');
	wp_print_scripts('editor');
	wp_print_scripts('thickbox');
	wp_print_scripts('media-upload');
	wp_print_scripts('word-count');
		
	if (function_exists('wp_tiny_mce'))
		wp_tiny_mce();

	return;
}

?>