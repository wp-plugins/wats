<?php

/**********************************************/
/*                                            */
/* Fonction pour compter le nombre de tickets */
/*                                            */
/**********************************************/

function wats_wp_count_posts($type = 'post', $perm = '')
{
	global $wpdb, $wats_settings;

	$user = wp_get_current_user();

	$cache_key = $type;

	$query = "SELECT post_status, COUNT( * ) AS num_posts FROM {$wpdb->posts} WHERE post_type = %s";
	if ( 'readable' == $perm && is_user_logged_in() ) {
		if ( !current_user_can("read_private_{$type}s") ) {
			$cache_key .= '_' . $perm . '_' . $user->ID;
			$query .= " AND (post_status != 'private' OR ( post_author = '$user->ID' AND post_status = 'private' ))";
		}
		if ($wats_settings['visibility'] == 2 && $user->user_level < 10)
			$query .= " AND post_author = '$user->ID'";
	}
	$query .= ' GROUP BY post_status';

	$count = wp_cache_get($cache_key, 'counts');
	if ( false !== $count )
		return $count;

	$count = $wpdb->get_results( $wpdb->prepare( $query, $type ), ARRAY_A );

	$stats = array( 'publish' => 0, 'private' => 0, 'draft' => 0, 'pending' => 0, 'future' => 0, 'trash' => 0 );
	foreach( (array) $count as $row_num => $row ) {
		$stats[$row['post_status']] = $row['num_posts'];
	}

	$stats = (object) $stats;
	wp_cache_set($cache_key, $stats, 'counts');

	return $stats;
}

/**********************************************/
/*                                            */
/* Fonction pour adapter la query aux tickets */
/*                                            */
/**********************************************/

function wats_wp_edit_posts_query( $q = false )
{
	if ( false === $q )
		$q = $_GET;
		
	$q['m']   = isset($q['m']) ? (int) $q['m'] : 0;
	$q['cat'] = isset($q['cat']) ? (int) $q['cat'] : 0;
	$post_stati  = array(	//	array( adj, noun )
				'publish' => array(__('Published'), __('Published posts'), __ngettext_noop('Published <span class="count">(%s)</span>', 'Published <span class="count">(%s)</span>')),
				'future' => array(__('Scheduled'), __('Scheduled posts'), __ngettext_noop('Scheduled <span class="count">(%s)</span>', 'Scheduled <span class="count">(%s)</span>')),
				'pending' => array(__('Pending Review'), __('Pending posts'), __ngettext_noop('Pending Review <span class="count">(%s)</span>', 'Pending Review <span class="count">(%s)</span>')),
				'draft' => array(__('Draft'), _c('Drafts|manage posts header'), __ngettext_noop('Draft <span class="count">(%s)</span>', 'Drafts <span class="count">(%s)</span>')),
				'private' => array(__('Private'), __('Private posts'), __ngettext_noop('Private <span class="count">(%s)</span>', 'Private <span class="count">(%s)</span>')),
			);

	$post_stati = apply_filters('post_stati', $post_stati);

	$avail_post_stati = get_available_post_statuses('post');

	$post_status_q = '';
	if ( isset($q['post_status']) && in_array( $q['post_status'], array_keys($post_stati) ) ) {
		$post_status_q = '&post_status=' . $q['post_status'];
		$post_status_q .= '&perm=readable';
	}

	if ( isset($q['post_status']) && 'pending' === $q['post_status'] ) {
		$order = 'ASC';
		$orderby = 'modified';
	} elseif ( isset($q['post_status']) && 'draft' === $q['post_status'] ) {
		$order = 'DESC';
		$orderby = 'modified';
	} else {
		$order = 'DESC';
		$orderby = 'date';
	}

	wp("post_type=ticket&what_to_show=posts$post_status_q&posts_per_page=15&order=$order&orderby=$orderby");

	return array($post_stati, $avail_post_stati);
}


/**
 * Edit Posts Administration Panel.
 *
 * @package WordPress
 * @subpackage Administration
 */

/** WordPress Administration Bootstrap */
require_once('admin.php');

// Back-compat for viewing comments of an entry
if ( $_redirect = intval( max( @$_GET['p'], @$_GET['attachment_id'], @$_GET['page_id'] ) ) ) {
	wp_redirect( admin_url('edit-comments.php?p=' . $_redirect ) );
	exit;
} else {
	unset( $_redirect );
}

// Handle bulk actions
if ( isset($_GET['action']) && ( -1 != $_GET['action'] || -1 != $_GET['action2'] ) ) {
	$doaction = ( -1 != $_GET['action'] ) ? $_GET['action'] : $_GET['action2'];

	switch ( $doaction ) {
		case 'delete':
			if ( isset($_GET['post']) && ! isset($_GET['bulk_edit']) && (isset($_GET['doaction']) || isset($_GET['doaction2'])) ) {
				check_admin_referer('bulk-posts');
				$deleted = 0;
				foreach( (array) $_GET['post'] as $post_id_del ) {
					$post_del = & get_post($post_id_del);

					if ( !current_user_can('delete_post', $post_id_del) )
						wp_die( __('You are not allowed to delete this post.') );

					if ( $post_del->post_type == 'attachment' ) {
						if ( ! wp_delete_attachment($post_id_del) )
							wp_die( __('Error in deleting...') );
					} else {
						if ( !wp_delete_post($post_id_del) )
							wp_die( __('Error in deleting...') );
					}
					$deleted++;
				}
			}
			break;
		case 'edit':
			if ( isset($_GET['post']) && isset($_GET['bulk_edit']) ) {
				check_admin_referer('bulk-posts');

				if ( -1 == $_GET['_status'] ) {
					$_GET['post_status'] = null;
					unset($_GET['_status'], $_GET['post_status']);
				} else {
					$_GET['post_status'] = $_GET['_status'];
				}

				$done = bulk_edit_posts($_GET);
			}
			break;
	}

	$sendback = wp_get_referer();

	if ( strpos($sendback, 'wats-ticket.php') !== false ) $sendback = admin_url('admin.php?page=wats/wats-ticket-new.php');
	elseif ( strpos($sendback, 'attachments.php') !== false ) $sendback = admin_url('attachments.php');
	if ( isset($done) ) {
		$done['updated'] = count( $done['updated'] );
		$done['skipped'] = count( $done['skipped'] );
		$done['locked'] = count( $done['locked'] );
		$sendback = add_query_arg( $done, $sendback );
	}
	if ( isset($deleted) )
		$sendback = add_query_arg('deleted', $deleted, $sendback);
	wp_redirect($sendback);
	exit();
} elseif ( isset($_GET['_wp_http_referer']) && ! empty($_GET['_wp_http_referer']) ) {
	 wp_redirect( remove_query_arg( array('_wp_http_referer', '_wpnonce'), stripslashes($_SERVER['REQUEST_URI']) ) );
	 exit;
}

if (empty($title))
	$title = __('Edit Tickets','WATS');
$parent_file = 'wats-edit.php';
/*wp_print_scripts('inline-edit-post');*/

list($post_stati, $avail_post_stati) = wats_wp_edit_posts_query();

/* global $wp_query;
$wp_query = null;
$wp_query = new WP_Query();
$wp_query->query('post_type=ticket');*/

require_once('admin-header.php');

if ( !isset( $_GET['paged'] ) )
	$_GET['paged'] = 1;

if ( empty($_GET['mode']) )
	$mode = 'list';
else
	$mode = attribute_escape($_GET['mode']); ?>

<div class="wrap">
<?php screen_icon(); ?>
<h2><?php echo wp_specialchars($title);
if ( isset($_GET['s']) && $_GET['s'] )
	printf( '<span class="subtitle">' . __('Search results for &#8220;%s&#8221;') . '</span>', wp_specialchars(get_search_query())); ?>
</h2>

<?php
if ( isset($_GET['posted']) && $_GET['posted'] ) : $_GET['posted'] = (int) $_GET['posted']; ?>
<div id="message" class="updated fade"><p><strong><?php _e('Your ticket has been saved.','WATS'); ?></strong> <a href="<?php echo get_permalink( $_GET['posted'] ); ?>"><?php _e('View post'); ?></a> | <a href="<?php echo wats_get_edit_ticket_link( $_GET['posted'] ); ?>"><?php _e('Edit ticket','WATS'); ?></a></p></div>
<?php $_SERVER['REQUEST_URI'] = remove_query_arg(array('posted'), $_SERVER['REQUEST_URI']);
endif; ?>

<?php if ( isset($_GET['locked']) || isset($_GET['skipped']) || isset($_GET['updated']) || isset($_GET['deleted']) ) { ?>
<div id="message" class="updated fade"><p>
<?php if ( isset($_GET['updated']) && (int) $_GET['updated'] ) {
	printf( __ngettext( '%s ticket updated.', '%s tickets updated.', $_GET['updated'] ), number_format_i18n( $_GET['updated'] ) );
	unset($_GET['updated']);
}

if ( isset($_GET['skipped']) && (int) $_GET['skipped'] )
	unset($_GET['skipped']);

if ( isset($_GET['locked']) && (int) $_GET['locked'] ) {
	printf( __ngettext( '%s ticket not updated, somebody is editing it.', '%s tickets not updated, somebody is editing them.', $_GET['locked'] ), number_format_i18n( $_GET['locked'] ) );
	unset($_GET['locked']);
}

if ( isset($_GET['deleted']) && (int) $_GET['deleted'] ) {
	printf( __ngettext( 'Ticket deleted.', '%s tickets deleted.', $_GET['deleted'] ), number_format_i18n( $_GET['deleted'] ) );
	unset($_GET['deleted']);
}

$_SERVER['REQUEST_URI'] = remove_query_arg( array('locked', 'skipped', 'updated', 'deleted'), $_SERVER['REQUEST_URI'] );
?>
</p></div>
<?php } ?>

<form id="posts-filter" action="" method="get">

<ul class="subsubsub">
<?php
if ( empty($locked_post_status) ) :
$status_links = array();
$num_posts = wats_wp_count_posts('ticket','readable');
$total_posts = array_sum( (array) $num_posts );
$class = empty( $_GET['post_status'] ) ? ' class="current"' : '';
$status_links[] = "<li><a href='admin.php?page=wats/wats-edit.php' $class>".sprintf(__ngettext('All <span class="count">(%s)</span>', 'All <span class="count">(%s)</span>', $total_posts), number_format_i18n($total_posts)).'</a>';

foreach ($post_stati as $status => $label)
{
	$class = '';

	if ( !in_array( $status, $avail_post_stati ) )
		continue;

	if ( empty( $num_posts->$status ) )
		continue;
	if ( isset($_GET['post_status']) && $status == $_GET['post_status'] )
		$class = ' class="current"';

	$status_links[] = "<li><a href='admin.php?page=wats/wats-edit.php&amp;post_status=$status' $class>".sprintf(__ngettext($label[2][0],$label[2][1],$num_posts->$status ),number_format_i18n($num_posts->$status)).'</a>';
}
echo implode(" |</li>\n",$status_links).'</li>';
unset($status_links);
endif;
?>
</ul>

<p class="search-box">
	<input type="hidden" id="page" name="page" value="wats/wats-edit.php" />
	<input type="hidden" id="noheader" name="noheader" value="1" />
	<label class="hidden" for="post-search-input"><?php _e('Search Tickets','WATS'); ?>:</label>
	<input type="text" class="search-input" id="post-search-input" name="s" value="<?php the_search_query(); ?>" />
	<input type="submit" value="<?php _e('Search Tickets','WATS'); ?>" class="button" />
</p>

<?php if ( isset($_GET['post_status'] ) ) : ?>
<input type="hidden" name="post_status" value="<?php echo attribute_escape($_GET['post_status']) ?>" />
<?php endif; ?>
<input type="hidden" name="mode" value="<?php echo $mode; ?>" />

<?php if ( have_posts() ) {
add_action('manage_posts_custom_column','wats_edit_post_custom_column', 10, 2);
add_action('manage_posts_columns','wats_edit_post_column');
?>
<div class="tablenav">
<?php
$page_links = paginate_links( array(
	'base' => add_query_arg( 'paged', '%#%' ),
	'format' => '',
	'prev_text' => __('&laquo;'),
	'next_text' => __('&raquo;'),
	'total' => $wp_query->max_num_pages,
	'current' => $_GET['paged']
));

?>

<div class="alignleft actions">
<select name="action">
<option value="-1" selected="selected"><?php _e('Bulk Actions'); ?></option>
<option value="edit"><?php _e('Edit'); ?></option>
<option value="delete"><?php _e('Delete'); ?></option>
</select>
<input type="submit" value="<?php _e('Apply'); ?>" name="doaction" id="doaction" class="button-secondary action" />
<?php wp_nonce_field('bulk-posts'); ?>

<?php // view filters
if ( !is_singular() ) {
$arc_query = "SELECT DISTINCT YEAR(post_date) AS yyear, MONTH(post_date) AS mmonth FROM $wpdb->posts WHERE post_type = 'ticket' ORDER BY post_date DESC";

$arc_result = $wpdb->get_results( $arc_query );

$month_count = count($arc_result);

if ( $month_count && !( 1 == $month_count && 0 == $arc_result[0]->mmonth ) ) {
$m = isset($_GET['m']) ? (int)$_GET['m'] : 0;
?>
<select name='m'>
<option<?php selected( $m, 0 ); ?> value='0'><?php _e('Show all dates'); ?></option>
<?php
foreach ($arc_result as $arc_row) {
	if ( $arc_row->yyear == 0 )
		continue;
	$arc_row->mmonth = zeroise( $arc_row->mmonth, 2 );

	if ( $arc_row->yyear . $arc_row->mmonth == $m )
		$default = ' selected="selected"';
	else
		$default = '';

	echo "<option$default value='$arc_row->yyear$arc_row->mmonth'>";
	echo $wp_locale->get_month($arc_row->mmonth) . " $arc_row->yyear";
	echo "</option>\n";
}
?>
</select>
<?php } ?>

<?php
$dropdown_options = array('show_option_all' => __('View all categories'), 'hide_empty' => 0, 'hierarchical' => 1,
	'show_count' => 0, 'orderby' => 'name', 'selected' => $cat);
wp_dropdown_categories($dropdown_options);
do_action('restrict_manage_posts');
?>
<input type="submit" id="post-query-submit" value="<?php _e('Filter'); ?>" class="button-secondary" />

<?php } ?>
</div>

<?php if ( $page_links ) { ?>
<div class="tablenav-pages"><?php $page_links_text = sprintf( '<span class="displaying-num">' . __( 'Displaying %s&#8211;%s of %s' ) . '</span>%s',
	number_format_i18n( ( $_GET['paged'] - 1 ) * $wp_query->query_vars['posts_per_page'] + 1 ),
	number_format_i18n( min( $_GET['paged'] * $wp_query->query_vars['posts_per_page'], $wp_query->found_posts ) ),
	number_format_i18n( $wp_query->found_posts ),
	$page_links
); echo $page_links_text; ?></div>
<?php } ?>

<div class="view-switch">
	<a href="<?php echo esc_url(add_query_arg('mode', 'list', $_SERVER['REQUEST_URI'])) ?>"><img <?php if ( 'list' == $mode ) echo 'class="current"'; ?> id="view-switch-list" src="../wp-includes/images/blank.gif" width="20" height="20" title="<?php _e('List View') ?>" alt="<?php _e('List View') ?>" /></a>
	<a href="<?php echo esc_url(add_query_arg('mode', 'excerpt', $_SERVER['REQUEST_URI'])) ?>"><img <?php if ( 'excerpt' == $mode ) echo 'class="current"'; ?> id="view-switch-excerpt" src="../wp-includes/images/blank.gif" width="20" height="20" title="<?php _e('Excerpt View') ?>" alt="<?php _e('Excerpt View') ?>" /></a>
</div>

<div class="clear"></div>
</div>

<div class="clear"></div>

<?php include( 'wats-edit-post-rows.php' ); ?>

<div class="tablenav">

<?php
if ( $page_links )
	echo "<div class='tablenav-pages'>$page_links_text</div>";
?>

<div class="alignleft actions">
<select name="action2">
<option value="-1" selected="selected"><?php _e('Bulk Actions'); ?></option>
<option value="edit"><?php _e('Edit'); ?></option>
<option value="delete"><?php _e('Delete'); ?></option>
</select>
<input type="submit" value="<?php _e('Apply'); ?>" name="doaction2" id="doaction2" class="button-secondary action" />
<br class="clear" />
</div>
<br class="clear" />
</div>

<?php } else { // have_posts() ?>
<div class="clear"></div>
<p><?php _e('No ticket found') ?></p>
<?php } ?>

</form>

<?php inline_edit_row('post'); ?>

<div id="ajax-response"></div>

<br class="clear" />

</div>