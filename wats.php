<?php
/*
Plugin Name: Wats
Plugin URI: http://www.lautre-monde.fr/wats-going-on/
Description: Wats is a ticket system. Wats stands for Wordpress Advanced Ticket System.
Author: Olivier
Version: 1.0.29
Author URI: http://www.lautre-monde.fr
*/

/*
1/ Release history :
- V1.0.29 (18/11/2009) :
+ modified all ajax calls to match WP guidelines (removed wp-config inclusion, used admin-ajax in the frontend)
+ modified statistics dashboard widget visibility option to apply only to global stats (all users can now view their own stats)
- V1.0.28 (09/11/2009) :
+ added an option for statistics dashboard widget visibility
- V1.0.27 (04/11/2009) :
+ added inline help to items in the options page
- V1.0.26 (29/10/2009) :
+ fixed a bug preventing single-ticket.php template from being overriden
+ translated comment-tickets.php items that were in french by default
- V1.0.25 (27/10/2009) :
+ added Czech characters support
+ added an option to allow tickets tagging during ticket creation and edition
+ added an option to allow custom fields association to tickets during ticket creation and edition
- V1.0.24 (10/10/2009) :
+ improved robustness to fix a conflict between WATS and others plugins using jQuery
+ modified recent comments dashboard widget so that non admin users can only view their own tickets comments (as per ticket visibility option)
+ added an option to block access to comments edition menuitem and comments edition page for users without moderate_comments capability (so that they can't view updates on others users tickets)
- V1.0.23 (26/09/2009) :
+ added frontend widget for ticket statistics (code provided by Tobias Kalleder from http://indivisualists.com/)
+ modified ticket listing filtering in the admin tickets table so that non admin users can only view their own tickets (as per ticket visibility option)
+ added german translation (provided by Tobias Kalleder from http://indivisualists.com/)
+ fixed few translations items
- V1.0.22 (25/09/2009) :
+ modified single ticket display template to include ticket originator details
+ modified ticket edit template in the admin to include ticket originator details
+ modified creation date format to allow proper sorting in the ticket listing table
- V1.0.21 (10/09/2009) :
+ added tickets listing table sort feature
- V1.0.20 (09/09/2009) :
+ fixed a bug preventing contributor level users from submitting/editing tickets
- V1.0.19 (08/09/2009) :
+ fixed another redirection loop encountered on some sites (using IIS) while guest user is trying to log into the admin
- V1.0.18 (04/09/2009) :
+ added admin email notification option upon new ticket submission
- V1.0.17 (03/09/2009) :
+ fixed a redirection loop encountered on some sites while guest user is trying to log into the admin
- V1.0.16 (30/08/2009) :
+ added ticket filtering feature to the ticket list (works through Ajax, JS support required).
- V1.0.15 (29/08/2009) :
+ added ticket ownership feature. Tickets can now be assigned to users in the frontend and the backend.
- V1.0.14 (26/08/2009) :
+ fixed a bug where ticket metas would be generated for all post types (post, page and ticket) instead of only ticket
- V1.0.13 (24/08/2009) :
+ modified single ticket template to include sidebar
- V1.0.12 (18/08/2009) :
+ added an option to filter ticket visibility on frontend based on user privileges
- V1.0.11 (12/08/2009) :
+ fixed few translations items
- V1.0.10 (29/07/2009) :
+ fixed jQuery checked selector issue on Firefox
+ improved editable support on the options page
- V1.0.9 (26/07/2009) :
+ modified readme file structure to support WP repository changelog feature
+ fixed duplicate admin footer on edit ticket page
+ added screenshots to the plugin archive
+ fixed few translations items
- V1.0.8 (12/07/2009) :
+ fixed a bug with new category not showing up on wats options page dropdown list of categories
+ fixed admin page registration code to be compatible with WP 2.8.1 security enforcements
+ removed unused code (wats-edit-form.php)
- V1.0.7 (06/07/2009) :
+ fixed a bug preventing single post/ticket display
+ fixed a bug preventing comment template display
+ optimized template redirect
+ improved css table display when there is no entry
- V1.0.6 (06/07/2009) :
+ fixed a bug with non working guest user access (redirection loop) when WP isn't installed in a subdirectory
- V1.0.5 (26/06/2009) :
+ modified the code to allow WP 2.7.1 compatibility
+ fixed query filtering logic for display of tickets together with posts in home, categories and archives
+ added robustness to prevent subscriber level user from being set as the guest user as it doesn't have the minimum capabilities
- V1.0.4 (23/06/2009) :
+ added ticket listing functionnality
+ removed broken inline ticket edit under the admin bulk ticket edit page
+ fixed html table tag issue problem under the options panel in the admin
- V1.0.3 (21/06/2009) :
+ fixed admin footer showing twice on edit ticket and new ticket pages
- V1.0.2 (19/06/2009) :
+ fixed css for bullets in the dashboard meta box under FF
+ added robustness to prevent php error when no category filter has been set under the options
+ fixed handling of the previous/next links of the ticket to prevent page link display
- V1.0.1 (17/06/2009) :
+ added an option to display (or not) tickets together with posts on home
+ fixed a bug with archives not displaying tickets when only tickets are present
- V1.0 (15/06/2009) : Initial release

2/ Plugin description :
This plugin  adds to wordpress the functionnalities of a ticket system. This allows users to submit tickets to
report problems or get support on whatever you want. You can customize the status, priority and type of
the ticket.

3/ Credits :
WATS uses the following scripts :
- Editable from Arash Karimzadeh (arashkarimzadeh.com)
- Table sorter from Christian Bach (tablesorter.com)
- jQuery
- Wordpress
=> Without them, WATS wouldn't be so thanks to them!

4/ License terms :
WATS is licensed under GPL v3.
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
add_action('wp_print_styles', 'wats_add_my_stylesheet');
add_action('wp_dashboard_setup', 'wats_dashboard_setup');
add_action('wp_enqueue_scripts','wats_enqueue_script_frontend');

/*************************************/
/*                                   */
/* Définition des variables globales */
/*                                   */
/*************************************/

define('WATS_DEBUG', false);
define('WATS_URL',get_option('siteurl').'/wp-content/plugins/'.basename(dirname(__FILE__)).'/');
define('WATS_PATH',ABSPATH.'wp-content/plugins/'.basename(dirname(__FILE__)).'/');
define('WATS_SHORT_PATH','/wp-content/plugins/'.basename(dirname(__FILE__)).'/');
define('WATS_THEME_PATH',WATS_PATH.'theme');
define('WATS_BACKLINK','http://www.lautre-monde.fr/wats-going-on/');
define('WATS_BACKLINK2','http://www.lautre-monde.fr');
define('WATS_ANCHOR','ticket system');
define('WATS_ANCHOR2',"l'autre monde");
define("WATS_TICKET_LIST_REGEXP", "/\[WATS_TICKET_LIST ([[:print:]]+)\]/");

$wats_settings = array();
$wats_version = '1.0.29';

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
add_action('pre_get_posts','wats_parse_query');
add_action('template_redirect','wats_template_redirect');
add_action('comment_post','wats_comment_update_meta');
add_action('wp_footer','wats_wp_footer');

add_filter('taxonomy_template', 'wats_taxomony_template');
add_filter('comments_template', 'wats_comments_template');
add_filter('the_title','wats_title_insert_ticket_number');
add_filter('get_previous_post_where','wats_ticket_get_previous_next_post_where');
add_filter('get_next_post_where','wats_ticket_get_previous_next_post_where');
add_filter('getarchives_where','wats_get_archives');
add_filter('posts_where','wats_posts_where');
add_filter('the_content', 'wats_list_tickets_filter');
add_filter('the_content_rss', 'wats_list_tickets_filter');

/* Ajax Actions Hooks */
add_action('wp_ajax_wats_admin_insert_option_entry','wats_admin_insert_option_entry',10);
add_action('wp_ajax_wats_admin_remove_option_entry','wats_admin_remove_option_entry',10);
add_action('wp_ajax_wats_admin_update_option_entry','wats_admin_update_option_entry',10);

add_action('wp_ajax_wats_ticket_list_ajax_processing','wats_ticket_list_ajax_processing',10);
add_action('wp_ajax_nopriv_wats_ticket_list_ajax_processing','wats_ticket_list_ajax_processing',10);
?>