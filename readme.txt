=== Wordpress Advanced Ticket System ===
Contributors: Olivier
Donate link: http://www.ticket-system.net/
Tags: ticket,support,taxonomy,help,cms,crm,customer
Requires at least: 2.7.1
Tested up to: 3.0.1
Stable tag: 1.0.53

== Description ==

This plugin adds to wordpress the functionnalities of a ticket system. This allows users to submit tickets to report problems or get support on whatever you want. You can customize the status, priority and type of the ticket.

= Plugin's Official website =
Wordpress Advanced Ticket System : http://www.ticket-system.net/


WATS offers multiple possibilities for your customers to submit tickets :

* through the frontend form (doesn't require any authentication nor registration) (Premium)
* through the admin site via the shared guest user feature
* through the admin side for all users with a minimum level of contributor
* directly by email (Premium)

WATS key features :

* ticket submission through the admin
* ticket submission through the frontend (Premium)
* ticket assignation
* ticket submission on behalf of users
* mail notification upon new ticket submission (Premium)
* mail notification upon ticket update (Premium)
* ticket list filtering and sorting (Premium)

Examples of use of WATS :

* technical support system
* customer relationship management system
* software release lifecycle management
* service request system
* helpdesk system

Translations :

* English (Minimum release : 1.0, Originator : Olivier from http://www.ticket-system.net )
* French (Minimum release : 1.0, Originator : Olivier from http://www.ticket-system.net )
* German (Minimum release : 1.0.23, Originator : Tobias Kalleder from http://indivisualists.com/ )
* Spanish (Minimum release : 1.0.49, Originator : Esteban from http://www.netmdp.com/ )
* Lithuanian (Minimum release : 1.0.50, Originator : Arturas from http://www.taisyklajums.lt/ )
* Russian (Minimum release : 1.0.53, Originator : Alexey from http://reservation.isaev.asia/ )
* Indonesian (Minimum release : 1.0.53, Originator : Rizal Fauzie from http://fauzievolute.com/ )


== Installation ==

This plugin is almost plug and play! Just activate it and then go to the options menuitem to set the guest user and the allowed categories for ticket submission.

== Frequently Asked Questions ==

1/ License terms :
WATS is licensed under GPL v3.

2/ Warranty (Excerpt of GPL v3 - Disclaimer of Warranty ) :
THERE IS NO WARRANTY FOR THE PROGRAM, TO THE EXTENT PERMITTED BY APPLICABLE LAW. EXCEPT WHEN OTHERWISE STATED IN WRITING THE COPYRIGHT HOLDERS AND/OR OTHER PARTIES PROVIDE THE PROGRAM "AS IS" WITHOUT WARRANTY OF ANY KIND, EITHER EXPRESSED OR IMPLIED, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE. THE ENTIRE RISK AS TO THE QUALITY AND PERFORMANCE OF THE PROGRAM IS WITH YOU. SHOULD THE PROGRAM PROVE DEFECTIVE, YOU ASSUME THE COST OF ALL NECESSARY SERVICING, REPAIR OR CORRECTION.


== Screenshots ==

1. Sample ticket display in the frontend
2. Ticket update form in the frontend
3. Tickets listing in the frontend
4. Ticket creation in the admin backend

== Changelog ==

= V1.0.53 (07/10/2010) =
* fixed a bug with ticket listing table formatting when ticket numbering isn't active
* added compatibility for WP 3.0, 3.0.1
* fixed a bug inducing a php warning when no category is associated to ticket listing post
* enhanced title formatting on single ticket page in the frontend
* fixed a race condition with jQuery Editable plugin
* fixed a bug inducing javascript error with jQuery Editable plugin
* added category selector to frontend ticket submission form
* added Russian translation (prodived by Alexey from http://reservation.isaev.asia/ )
* added Indonesian translation (provided by Rizal Fauzie from http://fauzievolute.com )
* added an option to select the ticket default type, priority and status that will then be selected by default in the ticket creation page (admin and frontend submission form)
* added an option to define specific notification list upon new ticket opening based on ticket priority, type and status
* enhanced details provided in new ticket notification mail
* modified ticket update notification to not include admin link if the user level is subscriber
* fixed a bug enforcing comments for all post type

= V1.0.52 (13/06/2010) =
* added an option to select the ticket closed status (to allow bypassing of autodetection)
* modified checks for ticket status, priority, type and category edition in the options page
* modified checks for ticket title and content on the frontend submission form
* added a filter operator for statuses on the ticket listing page
* modified notifications for "my tickets" updates, now notifications are sent out to ticket originator and users who updated the ticket (through comments)
* added an option to make local user profile notifications settings optionnal and leave precedence to global notifications settings only

= V1.0.51 (12/05/2010) =
* added CSS class to ticket details items on frontend ticket submission page
* fixed a bug where a notification would be fired for all post type comments instead of only being fired for tickets
* modified meta_value selector in the ticket listing page to be sorted by alphabetical order
* modified thickbox loading to fix an interworking problem with gravity forms plugin
* fixed a bug preventing admin edit ticket link from being inserted into the new ticket admin notification when an unauthenticated user submits a ticket through the frontend form
* added login form to the ticket submission form if user isn't authenticated and authentication is mandatory to access it
* enhanced handling of update forms selectors when no value is provided so that existing values aren't removed

= V1.0.50 (24/03/2010) =
* fixed a bug preventing ticket number from being displayed in ticket title (wptexturize issue)
* enhanced menuitem icon rendering
* added lithuanian translation (provided by Arturas from http://www.taisyklajums.lt/ )

= V1.0.49 (09/03/2010) =
* added support for WP 2.9.2
* added support for username not formatted as an email address for the ticket submission through email options
* added spanish translation (provided by Esteban from http://www.netmdp.com/ )

= V1.0.48 (14/02/2010) =
* fixed a bug with RSS comment feeds showing ticket comments while they shouldn't

= V1.0.47 (12/02/2010) =
* fixed a bug with previous and next links on single ticket page in WP 2.9
* added "/" as valid character for ticket description text area in the frontend submit form

= V1.0.46 (08/02/2010) =
* modified default value for notification signature
* fixed frontend submit form error when description contains carriage return
* enhanced success message on frontend submit form based on ticket numbering setting

= V1.0.45 (04/02/2010) =
* enhanced options page design to improve admin experience
* fixed a bug with number of comments for tickets in ticket edit page (admin)
* added "?", "!" and ":" as valid charaters for inputs

= V1.0.44 (29/01/2010) =
* enhanced Ajax submission handling by disabling buttons on submit to prevent double submission errors
* added feature to allow ticket submission by email

= V1.0.43 (27/01/2010) =
* added ticket submission form feature in the frontend
* removed unused code (wats-ticket-list-ajax-processing.php)

= V1.0.42 (24/01/2010) =
* added a check to prevent notifications from being delivered to pending users (register plus compatibility)
* added persian characters support
* added an option to sort user selectors by meta keys values
* modified user selector format check to allow ":" char to be inserted
* modified selectors width to match max width of elements (IE)
* moved ticket details meta box from sidebar to main content on the ticket creation and edition pages

= V1.0.41 (14/01/2010) =
* fixed a bug preventing right ticket author from being selected in the ticket edition page
* fixed a bug with invalid ticket counters on ticket listing page in the admin
* added value "None" to ticket owner selector filter on ticket listing (frontend)

= V1.0.40 (12/01/2010) =
* sorted all users selectors by user last_name
* enhanced details related to guest user setting to minimize error risks
* modified style of ticket title column in ticket edit page to get a fixed width of 200px (admin side)
* added default notifications flags for newly registered users

= V1.0.39 (05/01/2010) =
* fixed an issue with user profile options save in WP 2.9
* fixed a bug with upload_files capability granting (can now only be modified for roles without upload_files capability by default)
* added support for WP 2.9.1

= V1.0.38 (30/12/2009) =
* added arabic characters support
* added support for WP 2.9

= V1.0.37 (17/12/2009) =
* fixed a bug with post author save

= V1.0.36 (15/12/2009) =
* fixed a bug with post edit link under archives pages if post type is "ticket"
* removed empty ticket author label on ticket creation page for non admin users
* added an option under the user profile to grant or remove upload files capability for any user
* added an option to set email signature of notifications sent by the system to users
* modified default ticket creator (call center feature) to current user
* enhanced ticket update notification emails to include status, priority, type and owner changes

= V1.0.35 (09/12/2009) =
* added an option to allow admins to filter tickets on ticket listing table page through an additionnal selector based on user meta value
* added an option to allow admins to get an additionnal column in the ticket listing table filled in with selected meta key meta values
* fixed a bug with user format in formatted user list when a user meta is used at the beginning of the format string

= V1.0.34 (04/12/2009) =
* fixed a bug with ticket listing filtering when ticket owner is "any"
* added ticket author selector filter for ticket listing table
* enhanced ticket visibility option scope to impact owner and author selectors filters for ticket listing table

= V1.0.33 (03/12/2009) =
* fixed an issue with admin side edit ticket url encoding on mail notifications
* added an option to allow admins to create tickets on behalf of any user
* added an option to set the format of user selector
* modified all user selectors to work with the format option (owner selector on single ticket page, ticket list owner selector, create/edit ticket owner selector, guest user selector)
* added originator selector on ticket creation/edition page for admins

= V1.0.32 (02/12/2009) =
* added a global option for notification of updates on all tickets (wats options page)
* added a global option for notification of updates on user own tickets (wats options page)
* added a profile option for notification of new ticket submissions (user profile page, can only be set by admins)
* added a profile option for notification of updates on all tickets (user profile page, can only be set by admins)
* added a profile option for notification of updates on user own tickets (user profile page, can only be set by admins)

= V1.0.31 (28/11/2009) =
* added wats_ticket_ownership capability which can be granted individually under user profile by admins
* added an option to restrict user list for ticket assignation

= V1.0.30 (26/11/2009) =
* added an option to allow media upload on ticket creation and edition pages
* added an option to allow media library browsing and selection on ticket creation and edition pages

= V1.0.29 (18/11/2009) =
* modified all ajax calls to match WP guidelines (removed wp-config inclusion, used admin-ajax in the frontend)
* modified statistics dashboard widget visibility option to apply only to global stats (all users can now view their own stats)

= V1.0.28 (09/11/2009) =
* added an option for statistics dashboard widget visibility

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
* added frontend widget for ticket statistics (code provided by Tobias Kalleder from http://indivisualists.com/ )
* modified ticket listing filtering in the admin tickets table so that non admin users can only view their own tickets (as per ticket visibility option)
* added german translation (provided by Tobias Kalleder from http://indivisualists.com/ )
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