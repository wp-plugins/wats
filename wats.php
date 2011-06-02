<?php
/*
Plugin Name: Wats
Plugin URI: http://www.ticket-system.net/
Description: Wats is a ticket system. Wats stands for Wordpress Advanced Ticket System.
Author: Olivier
Version: 1.0.54
Author URI: http://www.ticket-system.net/
*/

/*
1/ Release history :
- V1.0.54 (19/03/2011) :
+ removed use of deprecated user_level attribute, get_usermeta function (minimum release is now WP 3.0)
+ added an option to set roles allowed to assign tickets
+ added an option to set roles allowed to view global stats on the dashboard
+ fixed a bug causing duplicate meta values to be inserted for tickets metas
+ fixed a bug preventing category filtering on ticket edition page
+ fixed a bug preventing owner assignation on frontend ticket submission page
+ fixed a bug causing invalid template usage for page on WP 3.0
+ added an option to allow notification source address to be set to current user email address
+ added a div with a class for the category selector on the frontend submission form
+ added Italian translation (prodived by Alessandro Pagano from http://www.alessandropagano.net/ )
+ modified ticket update (comment) template to prevent new update when ticket is closed (unless the user is an administrator)
+ fixed a bug preventing preview of draft posts
+ added filters hooks to allow notifications content modification
+ fixed IE rendering problem in login form (frontend submission page)
+ added an option to prevent regular users (non admins) from modifying their profile email
+ added a profile option to set user account expiration date (account is locked after expiration date)
+ added tickets in pending status to frontend ticket listing for administrators
+ added an optionnal ticket key for products (admin can define products and tickets can then be associated to a product)
+ added a country selector in the user profile page (only admin can set it)
+ modified ticket update notification option for all tickets to apply only to administrators instead of all users to avoid unwanted email flood
+ added non registered users to notification cycle for updates on tickets originated by them
+ removed ticket update notification for trashed tickets
+ added an option to define which keys are active (status, priority, type and product)
+ modified frontend script loading to be performed only on pages having a WATS shortcode from the wp_footer (rather than wp_head on all pages)
+ modified frontend css loading to be performed only on pages having a WATS shortcode
+ fixed creation date sorting problem in the ticket listing table
+ modified ticket submission by mail server name parser format to allow specific prefix
+ modified permalinks to take into consideration pending tickets
+ fixed a bug with submission by email new ticket admin notification permalink format
+ added a company option to allow admins to define a company meta_key and then associate a meta_value for each user
+ added a company management page (to define companies and associate these with users)
+ added a profile company option (filled with companies entered in the company management page)
+ fixed a bug with notification rule addition index
+ added product, company and country selectors to notification rules
+ added a company selector for ticket author on ticket creation/edition page in the admin to allow user filtering by company
+ added an optionnal SLA profile option (SLA can be defined in the options page and assigned in the profile and company pages)
+ fixed sidebar on single ticket page in Twenty Ten (default theme)
+ added an optionnal capability "wats_ticket_read_only" that can be assigned to users to allow them to browse other users tickets when ticket visibility is set to admin and author only
+ added an XML (excel) export feature for ticket listing in the frontend
+ fixed a bug with inline ticket edit that would add the ticket number in front of title thus resulting in duplicate number display after edition end
+ fixed a bug with invalid category filtering on frontend ticket listing page
+ modified frontend ticket listing to hide owner column when ticket assignation isn't enabled
+ added WP 3.1 compatibility
- V1.0.53 (07/10/2010) :
+ fixed a bug with ticket listing table formatting when ticket numbering isn't active
+ added compatibility for WP 3.0, 3.0.1
+ fixed a bug inducing a php warning when no category is associated to ticket listing post
+ enhanced title formatting on single ticket page in the frontend
+ fixed a race condition with jQuery Editable plugin
+ fixed a bug inducing javascript error with jQuery Editable plugin
+ added category selector to frontend ticket submission form
+ added Russian translation (prodived by Alexey from http://reservation.isaev.asia/ )
+ added Indonesian translation (provided by Rizal Fauzie from http://fauzievolute.com )
+ added an option to select the ticket default type, priority and status that will then be selected by default in the ticket creation page (admin and frontend submission form)
+ added an option to define specific notification list upon new ticket opening based on ticket priority, type and status
+ enhanced details provided in new ticket notification mail
+ modified ticket update notification to not include admin link if the user level is subscriber
+ fixed a bug enforcing comments to be opened for all post type
- V1.0.52 (13/06/2010) :
+ added an option to select the ticket closed status (to allow bypassing of autodetection)
+ modified checks for ticket status, priority, type and category edition in the options page
+ modified checks for ticket title and content on the frontend submission form
+ added a filter operator for statuses on the ticket listing page
+ modified notifications for "my tickets" updates, now notifications are sent out to ticket originator and users who updated the ticket (through comments)
+ added an option to make local user profile notifications settings optionnal and leave precedence to global notifications settings only
- V1.0.51 (12/05/2010) :
+ added CSS class to ticket details items on frontend ticket submission page
+ fixed a bug where a notification would be fired for all post type comments instead of only being fired for tickets
+ modified meta_value selector in the ticket listing page to be sorted by alphabetical order
+ modified thickbox loading to fix an interworking problem with gravity forms plugin
+ fixed a bug preventing admin edit ticket link from being inserted into the new ticket admin notification when an unauthenticated user submits a ticket through the frontend form
+ added login form to the ticket submission form if user isn't authenticated and authentication is mandatory to access it
+ enhanced handling of update forms selectors when no value is provided so that existing values aren't removed
- V1.0.50 (24/03/2010) :
+ fixed a bug preventing ticket number from being displayed in ticket title (wptexturize issue)
+ enhanced menuitem icon rendering
+ added lithuanian translation (provided by Arturas from http://www.taisyklajums.lt/)
- V1.0.49 (09/03/2010) :
+ added support for WP 2.9.2
+ added support for username not formatted as an email address for the ticket submission through email options
+ added spanish translation (provided by Esteban from http://www.netmdp.com/)
- V1.0.48 (14/02/2010) :
+ fixed a bug with RSS comment feeds showing ticket comments while they shouldn't
- V1.0.47 (12/02/2010) :
+ fixed a bug with previous and next links on single ticket page in WP 2.9
+ added "/" as valid character for ticket description text area in the frontend submit form
- V1.0.46 (08/02/2010) :
+ modified default value for notification signature
+ fixed frontend submit form error when description contains carriage return
+ enhanced success message on frontend submit form based on ticket numbering setting
- V1.0.45 (04/02/2010) :
+ enhanced options page design to improve admin experience
+ fixed a bug with number of comments for tickets in ticket edit page (admin)
+ added "?", "!" and ":" as valid charaters for inputs
- V1.0.44 (29/01/2010) :
+ enhanced Ajax submission handling by disabling buttons on submit to prevent double submission errors
+ added feature to allow ticket submission by email
- V1.0.43 (27/01/2010) :
+ added ticket submission form feature in the frontend
+ removed unused code (wats-ticket-list-ajax-processing.php)
- V1.0.42 (24/01/2010) :
+ added a check to prevent notifications from being delivered to pending users (register plus compatibility)
+ added persian characters support
+ added an option to sort user selectors by meta keys values
+ modified user selector format check to allow ":" char to be inserted
+ modified selectors width to match max width of elements (IE)
+ moved ticket details meta box from sidebar to main content on the ticket creation and edition pages
- V1.0.41 (14/01/2010) :
+ fixed a bug preventing right ticket author from being selected in the ticket edition page
+ fixed a bug with invalid ticket counters on ticket listing page in the admin
+ added value "None" to ticket owner selector filter on ticket listing (frontend)
- V1.0.40 (12/01/2010) :
+ sorted all users selectors by user last_name
+ enhanced details related to guest user setting to minimize error risks
+ modified style of ticket title column in ticket edit page to get a fixed width of 200px (admin side)
+ added default notifications flags for newly registered users
- V1.0.39 (05/01/2010) :
+ fixed an issue with user profile options save in WP 2.9
+ fixed a bug with upload_files capability granting (can now only be modified for roles without upload_files capability by default)
+ added support for WP 2.9.1
- V1.0.38 (30/12/2009) :
+ added arabic characters support
+ added support for WP 2.9
- V1.0.37 (17/12/2009) :
+ fixed a bug with post author save
- V1.0.36 (15/12/2009) :
+ fixed a bug with post edit link under archives pages if post type is "ticket"
+ removed empty ticket author label on ticket creation page for non admin users
+ added an option under the user profile to grant or remove upload files capability for any user
+ added an option to set email signature of notifications sent by the system to users
+ modified default ticket creator (call center feature) to current user
+ enhanced ticket update notification emails to include status, priority, type and owner changes
- V1.0.35 (09/12/2009) :
+ added an option to allow admins to filter tickets on ticket listing table page through an additionnal selector based on user meta value
+ added an option to allow admins to get an additionnal column in the ticket listing table filled in with selected meta key meta values
+ fixed a bug with user format in formatted user list when a user meta is used at the beginning of the format string
- V1.0.34 (04/12/2009) :
+ fixed a bug with ticket listing filtering when ticket owner is "any"
+ added ticket author selector filter for ticket listing table
+ enhanced ticket visibility option scope to impact owner and author selectors filters for ticket listing table
- V1.0.33 (03/12/2009) :
+ fixed an issue with admin side edit ticket url encoding on mail notifications
+ added an option to allow admins to create tickets on behalf of any user
+ added an option to set the format of user selector
+ modified all user selectors to work with the format option (owner selector on single ticket page, ticket list owner selector, create/edit ticket owner selector, guest user selector)
+ added originator selector on ticket creation/edition page for admins
- V1.0.32 (02/12/2009) :
+ added a global option for notification of updates on all tickets (wats options page)
+ added a global option for notification of updates on user own tickets (wats options page)
+ added a profile option for notification of new ticket submissions (user profile page, can only be set by admins)
+ added a profile option for notification of updates on all tickets (user profile page, can only be set by admins)
+ added a profile option for notification of updates on user own tickets (user profile page, can only be set by admins)
- V1.0.31 (28/11/2009) :
+ added wats_ticket_ownership capability which can be granted individually under user profile by admins
+ added an option to restrict user list for ticket assignation
- V1.0.30 (26/11/2009) :
+ added an option to allow media upload on ticket creation and edition pages
+ added an option to allow media library browsing and selection on ticket creation and edition pages
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
- WATS is licensed under GPL v3.
- Excerpt of GPL v3 : Disclaimer of Warranty
THERE IS NO WARRANTY FOR THE PROGRAM, TO THE EXTENT PERMITTED BY APPLICABLE LAW. 
EXCEPT WHEN OTHERWISE STATED IN WRITING THE COPYRIGHT HOLDERS AND/OR OTHER 
PARTIES PROVIDE THE PROGRAM �AS IS� WITHOUT WARRANTY OF ANY KIND, EITHER EXPRESSED 
OR IMPLIED, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY 
AND FITNESS FOR A PARTICULAR PURPOSE. THE ENTIRE RISK AS TO THE QUALITY AND PERFORMANCE 
OF THE PROGRAM IS WITH YOU. SHOULD THE PROGRAM PROVE DEFECTIVE, YOU ASSUME THE 
COST OF ALL NECESSARY SERVICING, REPAIR OR CORRECTION.

*/

require_once(dirname(__FILE__) .'/wats-lib.php');
require_once(dirname(__FILE__) .'/wats-options.php');
require_once(dirname(__FILE__) .'/wats-head.php');
require_once(dirname(__FILE__) .'/wats-ticket-metas.php');
require_once(dirname(__FILE__) .'/wats-dashboard.php');
require_once(dirname(__FILE__) .'/wats-link-template.php');
require_once(dirname(__FILE__) .'/wats-template.php');
require_once(dirname(__FILE__) .'/wats-profile.php');

add_action('admin_head', 'wats_admin_head');
add_action('wp_print_styles', 'wats_add_my_stylesheet');
add_action('admin_print_styles', 'wats_add_my_stylesheet');
add_action('wp_dashboard_setup', 'wats_dashboard_setup');

/*************************************/
/*                                   */
/* D�finition des variables globales */
/*                                   */
/*************************************/

define('WATS_DEBUG', false);
define('WATS_URL',get_option('siteurl').'/wp-content/plugins/'.basename(dirname(__FILE__)).'/');
define('WATS_PATH',ABSPATH.'wp-content/plugins/'.basename(dirname(__FILE__)).'/');
define('WATS_SHORT_PATH','/wp-content/plugins/'.basename(dirname(__FILE__)).'/');
define('WATS_THEME_PATH',WATS_PATH.'theme');
define('WATS_BACKLINK','http://www.ticket-system.net/');
define('WATS_ANCHOR','ticket system');

$wats_settings = array();
$wats_version = '1.0.54';
$wats_printing_inline_data = false;

$wats_default_ticket_priority = array(1 => "Emergency", 2 => "Critical", 3 => "Major", 4 => "Minor");
$wats_default_ticket_status = array(1 => "Newly open", 2 => "Under investigation", 3 => "Waiting for reoccurence", 4 => "Waiting for details", 5 => "Solution delivered", 6 => "Closed");
$wats_default_ticket_type = array(1 => "Question", 2 => "SW Bug", 3 => "Installation request", 4 => "Feature request");
$wats_default_sla = array(1 => "Gold", 2 => "Silver", 3 => "Bronze");

/******************************************/
/*                                        */
/* Function d'accroche � l'initialisation */
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
/* Fonction appel�e lors de l'activation du plugin */
/*                                                 */
/***************************************************/

function wats_activation()
{
	global $wats_version;
	
	wats_debug("Wats ".$wats_version." activated.");
	wats_register_taxonomy();
	flush_rewrite_rules();
	
	return;
}

/*******************************************************/
/*                                                     */
/* Fonction appel�e lors de la d�sactivation du plugin */
/*                                                     */
/*******************************************************/

function wats_deactivation()
{
	global $wats_version;

	wats_debug("Wats ".$wats_version." deactivated.");
	flush_rewrite_rules();
	
	return;
}


register_activation_hook(__FILE__, 'wats_activation');
register_deactivation_hook(__FILE__, 'wats_deactivation');

add_action('init','wats_init',0);
add_action('plugins_loaded','wats_plugins_loaded');
add_action('comment_post','wats_comment_update_meta');
add_action('wp_footer','wats_wp_footer');
add_action('user_register','wats_user_register');

add_filter('template_include', 'wats_ticket_template_loader');
add_filter('comments_template', 'wats_comments_template');
add_filter('the_title','wats_title_insert_ticket_number',10,2);
add_filter('get_previous_post_where','wats_ticket_get_previous_next_post_where');
add_filter('get_next_post_where','wats_ticket_get_previous_next_post_where');
add_filter('getarchives_where','wats_get_archives');
add_filter('posts_where','wats_posts_where');
add_filter('wp_insert_post_data', 'wats_insert_post_data');
add_filter('edit_post_link','wats_filter_edit_ticket_link');
add_filter('comment_feed_where','wats_filter_comments_rss');
add_filter('wp_title', 'wats_wp_title');
add_filter('post_row_actions','wats_post_row_actions',10,2);
add_action('save_post', 'wats_ticket_save_meta', 10, 2);
add_action('pre_comment_on_post','wats_pre_comment_on_post',10,1);
add_filter('post_type_link','wats_post_type_link',10,4);

/* Ajax Actions Hooks */
add_action('wp_ajax_wats_admin_insert_option_entry','wats_admin_insert_option_entry',10);
add_action('wp_ajax_wats_admin_remove_option_entry','wats_admin_remove_option_entry',10);
add_action('wp_ajax_wats_admin_update_option_entry','wats_admin_update_option_entry',10);
add_action('wp_ajax_wats_admin_insert_notification_rule_entry','wats_admin_insert_notification_rule_entry',10);
add_action('wp_ajax_wats_admin_remove_notification_rule_entry','wats_admin_remove_notification_rule_entry',10);

?>