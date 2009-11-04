=== Wordpress Advanced Ticket System ===
Contributors: Olivier
Donate link: http://www.lautre-monde.fr/wats-going-on/
Tags: ticket,support,taxonomy,help,cms,crm,customer
Requires at least: 2.7.1
Tested up to: 2.8.5
Stable tag: 1.0.27

== Description ==

This plugin adds to wordpress the functionnalities of a ticket system. This allows users to submit tickets to report problems or get support on whatever you want. You can customize the status, priority and type of the ticket.

Any registered user (with a minimum level access of contributor) can submit tickets. You can also setup a "guest" user that will be shared between visitors so that they don't have to register before submitting tickets.


Examples of use of WATS :

* technical support system
* customer relationship management system
* software release lifecycle management
* service request system
* helpdesk system


Translations :

* English (Minimum release : 1.0, Originator : Olivier from http://www.lautre-monde.fr)
* French (Minimum release : 1.0, Originator : Olivier from http://www.lautre-monde.fr)
* German (Minimum release : 1.0.23, Originator : Tobias Kalleder from http://indivisualists.com/)


== Installation ==

This plugin is almost plug and play! Just activate it and then go to the options menuitem to set the guest user and the allowed categories for ticket submission.

== Frequently Asked Questions ==

1/ License terms :
WATS is licensed under GPL v3.

2/ Price :
WATS is free but you are more than welcome to make a donation to thank me if you'd like to.

== Screenshots ==
1. Sample ticket display in the frontend
2. Ticket update form in the frontend
3. Tickets listing in the frontend
4. Ticket creation in the admin backend

== Changelog ==

= V1.0.27 (04/11/2009) =
* added inline help to items in the options page

= V1.0.26 (29/10/2009) =
* fixed a bug preventing single-ticket.php template from being overriden
* translated comment-tickets.php items that were in french by default

= V1.0.25 (27/10/2009) =
* added Czech characters support
* added an option to allow tickets tagging during ticket creation and edition
* added an option to allow custom fields association to tickets during ticket creation and edition

= V1.0.24 (10/10/2009) =
* improved robustness to fix a conflict between WATS and others plugins using jQuery
* modified recent comments dashboard widget so that non admin users can only view their own tickets comments (as per ticket visibility option)
* added an option to block access to comments edition menuitem and comments edition page for users without moderate_comments capability (so that they can't view updates on others users tickets)

= V1.0.23 (26/09/2009) =
* added frontend widget for ticket statistics (code provided by Tobias Kalleder from http://indivisualists.com/)
* modified ticket listing filtering in the admin tickets table so that non admin users can only view their own tickets (as per ticket visibility option)
* added german translation (provided by Tobias Kalleder from http://indivisualists.com/)
* fixed few translations items

= V1.0.22 (25/09/2009) =
* modified single ticket display template to include ticket originator details
* modified ticket edit template in the admin to include ticket originator details
* modified creation date format to allow proper sorting in the ticket listing table

= V1.0.21 (10/09/2009) =
* added tickets listing table sort feature

= V1.0.20 (09/09/2009) =
* fixed a bug preventing contributor level users from submitting/editing tickets

= V1.0.19 (08/09/2009) =
* fixed another redirection loop encountered on some sites (using IIS) while guest user is trying to log into the admin

= V1.0.18 (04/09/2009) =
* added admin email notification option upon new ticket submission

= V1.0.17 (03/09/2009) =
* fixed a redirection loop encountered on some sites while guest user is trying to log into the admin

= V1.0.16 (30/08/2009) =
* added ticket filtering feature to the ticket list (works through Ajax, JS support required).

= V1.0.15 (29/08/2009) =
* added ticket ownership feature. Tickets can now be assigned to users in the frontend and the backend.

= V1.0.14 (26/08/2009) =
* fixed a bug where ticket metas would be generated for all post types (post, page and ticket) instead of only ticket

= V1.0.13 (24/08/2009) =
* modified single ticket template to include sidebar

= V1.0.12 (18/08/2009) =
* added an option to filter ticket visibility on frontend based on user privileges

= V1.0.11 (12/08/2009) =
* fixed few translations items

= V1.0.10 (29/07/2009) =
* fixed jQuery checked selector issue on Firefox
* improved editable support on the options page

= V1.0.9 (23/07/2009) =
* modified readme file structure to support WP repository changelog feature
* fixed duplicate admin footer on edit ticket page
* added screenshots to the plugin archive
* fixed few translations items

= V1.0.8 (12/07/2009) =
* fixed a bug with new category not showing up on wats options page dropdown list of categories
* fixed admin page registration code to be compatible with WP 2.8.1 security enforcements
* removed unused code (wats-edit-form.php)

= V1.0.7 (06/07/2009) =
* fixed a bug preventing single post/ticket display
* fixed a bug preventing comment template display
* optimized template redirect
* improved css table display when there is no entry

= V1.0.6 (06/07/2009) =
* fixed a bug with non working guest user access (redirection loop) when WP isn't installed in a subdirectory

= V1.0.5 (26/06/2009) =
* modified the code to allow WP 2.7.1 compatibility
* fixed query filtering logic for display of tickets together with posts in home, categories and archives
* added robustness to prevent subscriber level user from being set as the guest user as it doesn't have the minimum capabilities

= V1.0.4 (23/06/2009) =
* added ticket listing functionnality
* removed broken inline ticket edit under the admin bulk ticket edit page
* fixed html table tag issue problem under the options panel in the admin

= V1.0.3 (21/06/2009) =
* fixed admin footer showing twice on edit ticket and new ticket pages

= V1.0.2 (19/06/2009) =
* fixed css for bullets in the dashboard meta box under FF
* added robustness to prevent php error when no category filter has been set under the options
* fixed handling of the previous/next links of the ticket to prevent page link display

= V1.0.1 (17/06/2009) =
* added an option to display (or not) tickets together with posts on home
* fixed a bug with archives not displaying tickets when only tickets are present

= V1.0 (15/06/2009) =
* Initial release