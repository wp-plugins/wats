<?php
/*
Plugin Name: Wats
Plugin URI: http://www.lautre-monde.fr/wats/
Description: Wats is a ticket system. Wats stands for Wordpress Advanced Ticket System.
Author: Olivier
Version: 1.0
Author URI: http://www.lautre-monde.fr
*/

/*
1/ License terms :
- This release is provided for free. In exchange, you must keep the backlinks added by the plugin 
in the footer of the ticket pages on the frontend.
- You cannot redistribute this software without my written agreement.
- You cannot modify it for your own needs or third party needs without my written agreement.
- You cannot reuse parts of the code without my written agreement.
- This is not an open source software.
- This software is provided "As Is". I don't provide any warranty of any kind whether express or implied.
I give no warranty regarding the quality, reliability, timeliness or security of the service and software.
I will not endorse any responsibility for any damage or problem that could happen as part of the usage of
this software. This software has been developped by a human so it is not error free.
=> By installing and using this software, you fully accept all the terms of the license without any restriction.

2/ Release history :
- V1.0 (15/06/2009) : Initial release

3/ Plugin description :
This plugin  adds to wordpress the functionnalities of a ticket system. This allows users to submit tickets to
report problems or get support on whatever you want. You can customize the status, priority and type of
the ticket.

4/ Credits :
WATS uses the following scripts :
- Editable from Arash Karimzadeh (arashkarimzadeh.com)
- jQuery
- Wordpress
=> Without them, WATS wouldn't be so thanks to them!
*/

require_once(dirname(__FILE__) .'/wats-lib.php');
require_once(dirname(__FILE__) .'/wats-options.php');
require_once(dirname(__FILE__) .'/wats-head.php');
require_once(dirname(__FILE__) .'/wats-ticket-metas.php');
require_once(dirname(__FILE__) .'/wats-dashboard.php');
require_once(dirname(__FILE__) .'/wats-link-template.php');
require_once(dirname(__FILE__) .'/wats-template.php');

add_action('admin_head', 'wats_admin_head');
add_action('admin_print_styles', 'wats_add_my_stylesheet');
add_action('wp_dashboard_setup', 'wats_dashboard_setup');

/*************************************/
/*                                   */
/* Définition des variables globales */
/*                                   */
/*************************************/

define('WATS_DEBUG', false);
define('WATS_URL',get_option('siteurl').'/wp-content/plugins/'.basename(dirname(__FILE__)).'/');
define('WATS_PATH',ABSPATH.'wp-content/plugins/'.basename(dirname(__FILE__)).'/');
define('WATS_SHORT_PATH','/wp-content/plugins/'.basename(dirname(__FILE__)).'/');
define('WATS_THEME_PATH',WATS_PATH.'theme/');
define('WATS_BACKLINK','http://www.lautre-monde.fr');
define('WATS_ANCHOR','Wordpress Advanced Ticket System');

$wats_settings = array();
$wats_version = '1.0';

$wats_default_ticket_priority = array(1 => "Emergency", 2 => "Critical", 3 => "Major", 4 => "Minor");
$wats_default_ticket_status = array(1 => "Newly open", 2 => "Under investigation", 3 => "Waiting for reoccurence", 4 => "Waiting for details", 5 => "Solution delivered", 6 => "Closed");
$wats_default_ticket_type = array(1 => "Question", 2 => "SW Bug", 3 => "Installation request", 4 => "Feature request");

/******************************************/
/*                                        */
/* Function d'accroche à l'initialisation */
/*                                        */
/******************************************/

function wats_init()
{    
	wats_register_taxonomy();

	return;
}

/************************************/
/*                                  */
/* Fonction de chargement du plugin */
/*                                  */
/************************************/

function wats_plugins_loaded()
{
	if (function_exists('load_plugin_textdomain'))
	{
		$plugin_dir = basename(dirname(__FILE__));
		load_plugin_textdomain('WATS','wp-content/plugins/'.$plugin_dir.'/languages',$plugin_dir.'/languages');
	}
	wats_load_settings();
	add_action('admin_menu','wats_add_admin_page');

	return;
}

/***************************************************/
/*                                                 */
/* Fonction appelée lors de l'activation du plugin */
/*                                                 */
/***************************************************/

function wats_activation()
{
	wats_debug("Wats $wats_version activated.");
	
	return;
}

/*******************************************************/
/*                                                     */
/* Fonction appelée lors de la désactivation du plugin */
/*                                                     */
/*******************************************************/

function wats_deactivation()
{
	wats_debug("Wats $wats_version deactivated.");
	
	return;
}

register_activation_hook(__FILE__, 'wats_activation');
register_deactivation_hook(__FILE__, 'wats_deactivation');

add_action('init','wats_init',0);
add_action('plugins_loaded','wats_plugins_loaded');
add_action('save_post','wats_ticket_save_meta');
add_action('pre_get_posts','wats_parse_query');
add_action('template_redirect','wats_template_redirect');
add_action('comment_post','wats_comment_update_meta');
add_filter('taxonomy_template', 'wats_taxomony_template');
add_filter('comments_template', 'wats_comments_template');
add_filter('the_title','wats_title_insert_ticket_number');
add_filter('get_previous_post_where','wats_ticket_get_previous_next_post_where');
add_filter('get_next_post_where','wats_ticket_get_previous_next_post_where');

/* Ajax Actions Hooks */
add_action('wp_ajax_wats_admin_insert_option_entry','wats_admin_insert_option_entry',10);
add_action('wp_ajax_wats_admin_remove_option_entry','wats_admin_remove_option_entry',10);
add_action('wp_ajax_wats_admin_update_option_entry','wats_admin_update_option_entry',10);

php?>