<?php

/****************************************************/
/*                                                  */
/* Fonction pour ajouter des colonnes personnalisée */
/*                                                  */
/****************************************************/

function wats_edit_post_column($defaults)
{
	if ($defaults)
	{
		$defaults['type'] = __('Type','WATS');
		$defaults['priority'] = __('Priority','WATS');
		$defaults['status'] = __('Status','WATS');
		$defaults['title'] = __('Ticket','WATS');
		unset($defaults['tags']);
		return $defaults;
	}
	
	return;
}

/*****************************************************/
/*                                                   */
/* Fonction pour remplir les colonnes personnalisées */
/*                                                   */
/*****************************************************/

function wats_edit_post_custom_column($column_name, $post_id)
{
	global $wats_settings;
	
	$wats_ticket_priority = $wats_settings['wats_priorities'];
	$wats_ticket_type = $wats_settings['wats_types'];
	$wats_ticket_status = $wats_settings['wats_statuses'];
	
	if ($column_name == 'priority')
	{
		$ticket_priority = get_post_meta($post_id,'wats_ticket_priority',true);
		echo $wats_ticket_priority[$ticket_priority];
	}
	else if ($column_name == 'status')
	{
		$ticket_status = get_post_meta($post_id,'wats_ticket_status',true);
		echo $wats_ticket_status[$ticket_status];
	}
	else if ($column_name == 'type')
	{
		$ticket_type = get_post_meta($post_id,'wats_ticket_type',true);
		echo $wats_ticket_type[$ticket_type];
	}
	
	return;
}

/***********************************************************************/
/*                                                                     */
/* Fonction pour préparer le remplissage des colonnes pour les tickets */
/*                                                                     */
/***********************************************************************/

function wats_post_rows($posts = array()) 
{
	global $wp_query, $post, $mode;

	add_filter('the_title','wp_specialchars');

	// Create array of post IDs.
	$post_ids = array();

	if ( empty($posts) )
		$posts = &$wp_query->posts;

	foreach ( $posts as $a_post )
		$post_ids[] = $a_post->ID;

	$comment_pending_count = get_pending_comments_num($post_ids);
	if ( empty($comment_pending_count) )
		$comment_pending_count = array();

	foreach ( $posts as $post ) {
		if ( empty($comment_pending_count[$post->ID]) )
			$comment_pending_count[$post->ID] = 0;

		wats_post_row($post, $comment_pending_count[$post->ID], $mode);
	}
}

/***********************************************************/
/*                                                         */
/* Fonction pour remplir les colonnes pour un ticket donné */
/*                                                         */
/***********************************************************/

function wats_post_row($a_post, $pending_comments, $mode)
{
	global $post;
	static $rowclass;

	$global_post = $post;
	$post = $a_post;
	setup_postdata($post);

	$rowclass = 'alternate' == $rowclass ? '' : 'alternate';
	global $current_user;
	$post_owner = ( $current_user->ID == $post->post_author ? 'self' : 'other' );
	$edit_link = wats_get_edit_ticket_link($post->ID);
	$title = _draft_or_post_title();
?>
	<tr id='post-<?php echo $post->ID; ?>' class='<?php echo trim( $rowclass . ' author-' . $post_owner . ' status-' . $post->post_status ); ?> iedit' valign="top">
<?php
	$posts_columns = get_column_headers('edit');
	$hidden = get_hidden_columns('edit');
	foreach ( $posts_columns as $column_name=>$column_display_name ) {
		$class = "class=\"$column_name column-$column_name\"";

		$style = '';
		if ( in_array($column_name, $hidden) )
			$style = ' style="display:none;"';

		$attributes = "$class$style";

		switch ($column_name) {

		case 'cb':
		?>
		<th scope="row" class="check-column"><?php if ( current_user_can( 'edit_post', $post->ID ) ) { ?><input type="checkbox" name="post[]" value="<?php the_ID(); ?>" /><?php } ?></th>
		<?php
		break;

		case 'date':
			if ( '0000-00-00 00:00:00' == $post->post_date && 'date' == $column_name ) {
				$t_time = $h_time = __('Unpublished');
			} else {
				$t_time = get_the_time(__('Y/m/d g:i:s A'));
				$m_time = $post->post_date;
				$time = get_post_time('G', true, $post);

				$time_diff = time() - $time;

				if ( ( 'future' == $post->post_status) ) {
					if ( $time_diff <= 0 ) {
						$h_time = sprintf( __('%s from now'), human_time_diff( $time ) );
					} else {
						$h_time = $t_time;
						$missed = true;
					}
				} else {

					if ( $time_diff > 0 && $time_diff < 24*60*60 )
						$h_time = sprintf( __('%s ago'), human_time_diff( $time ) );
					else
						$h_time = mysql2date(__('Y/m/d'), $m_time);
				}
			}

			echo '<td ' . $attributes . '>';
			if ( 'excerpt' == $mode )
				echo apply_filters('post_date_column_time', $t_time, $post, $column_name, $mode);
			else
				echo '<abbr title="' . $t_time . '">' . apply_filters('post_date_column_time', $h_time, $post, $column_name, $mode) . '</abbr>';
			echo '<br />';
			if ( 'publish' == $post->post_status ) {
				_e('Published');
			} elseif ( 'future' == $post->post_status ) {
				if ( isset($missed) )
					echo '<strong class="attention">' . __('Missed schedule') . '</strong>';
				else
					_e('Scheduled');
			} else {
				_e('Last Modified');
			}
			echo '</td>';
		break;

		case 'title':
			$attributes = 'class="post-title column-title"' . $style;
		?>
		<td <?php echo $attributes ?>><strong><?php if ( current_user_can( 'edit_post', $post->ID ) ) { ?><a class="row-title" href="<?php echo $edit_link; ?>" title="<?php echo attribute_escape(sprintf(__('Edit "%s"'), $title)); ?>"><?php echo $title ?></a><?php } else { echo $title; }; _post_states($post); ?></strong>
		<?php
			if ( 'excerpt' == $mode )
				the_excerpt();

			$actions = array();
			if ( current_user_can('edit_post', $post->ID) ) {
				$actions['edit'] = '<a href="' . wats_get_edit_ticket_link($post->ID, true) . '" title="' . attribute_escape(__('Edit this post')) . '">' . __('Edit') . '</a>';
				$actions['inline hide-if-no-js'] = '<a href="#" class="editinline" title="' . attribute_escape(__('Edit this post inline')) . '">' . __('Quick&nbsp;Edit') . '</a>';
				$actions['delete'] = "<a class='submitdelete' title='" . attribute_escape(__('Delete this post')) . "' href='" . wp_nonce_url("post.php?action=delete&amp;post=$post->ID", 'delete-post_' . $post->ID) . "' onclick=\"if ( confirm('" . js_escape(sprintf( ('draft' == $post->post_status) ? __("You are about to delete this draft '%s'\n 'Cancel' to stop, 'OK' to delete.") : __("You are about to delete this post '%s'\n 'Cancel' to stop, 'OK' to delete."), $post->post_title )) . "') ) { return true;}return false;\">" . __('Delete') . "</a>";
			}
			if ( in_array($post->post_status, array('pending', 'draft')) ) {
				if ( current_user_can('edit_post', $post->ID) )
					$actions['view'] = '<a href="' . get_permalink($post->ID) . '" title="' . attribute_escape(sprintf(__('Preview "%s"'), $title)) . '" rel="permalink">' . __('Preview') . '</a>';
			} else {
				$actions['view'] = '<a href="' . get_permalink($post->ID) . '" title="' . attribute_escape(sprintf(__('View "%s"'), $title)) . '" rel="permalink">' . __('View') . '</a>';
			}
			$action_count = count($actions);
			$i = 0;
			echo '<div class="row-actions">';
			foreach ( $actions as $action => $link ) {
				++$i;
				( $i == $action_count ) ? $sep = '' : $sep = ' | ';
				echo "<span class='$action'>$link$sep</span>";
			}
			echo '</div>';

			get_inline_data($post);
		?>
		</td>
		<?php
		break;

		case 'categories':
		?>
		<td <?php echo $attributes ?>><?php
			$categories = get_the_category();
			if ( !empty( $categories ) ) {
				$out = array();
				foreach ( $categories as $c )
					$out[] = "<a href='admin.php?page=wats/wats-edit.php&category_name=$c->slug'> " . wp_specialchars(sanitize_term_field('name', $c->name, $c->term_id, 'category', 'display')) . "</a>";
					echo join( ', ', $out );
			} else {
				_e('Uncategorized');
			}
		?></td>
		<?php
		break;

		case 'tags':
		?>
		<td <?php echo $attributes ?>><?php
			$tags = get_the_tags($post->ID);
			if ( !empty( $tags ) ) {
				$out = array();
				foreach ( $tags as $c )
					$out[] = "<a href='admin.php?page=wats/wats-edit.php&tag=$c->slug'> " . wp_specialchars(sanitize_term_field('name', $c->name, $c->term_id, 'post_tag', 'display')) . "</a>";
				echo join( ', ', $out );
			} else {
				_e('No Tags');
			}
		?></td>
		<?php
		break;

		case 'comments':
		?>
		<td <?php echo $attributes ?>><div class="post-com-count-wrapper">
		<?php
			$pending_phrase = sprintf( __('%s pending'), number_format( $pending_comments ) );
			if ( $pending_comments )
				echo '<strong>';
				comments_number("<a href='edit-comments.php?p=$post->ID' title='$pending_phrase' class='post-com-count'><span class='comment-count'>" . __('0') . '</span></a>', "<a href='edit-comments.php?p=$post->ID' title='$pending_phrase' class='post-com-count'><span class='comment-count'>" . __('1') . '</span></a>', "<a href='edit-comments.php?p=$post->ID' title='$pending_phrase' class='post-com-count'><span class='comment-count'>" . __('%') . '</span></a>');
				if ( $pending_comments )
				echo '</strong>';
		?>
		</div></td>
		<?php
		break;

		case 'author':
		?>
		<td <?php echo $attributes ?>><a href="admin.php?page=wats/wats-edit.php&author=<?php the_author_ID(); ?>"><?php the_author() ?></a></td>
		<?php
		break;

		case 'control_view':
		?>
		<td><a href="<?php the_permalink(); ?>" rel="permalink" class="view"><?php _e('View'); ?></a></td>
		<?php
		break;

		case 'control_edit':
		?>
		<td><?php if ( current_user_can('edit_post', $post->ID) ) { echo "<a href='$edit_link' class='edit'>" . __('Edit') . "</a>"; } ?></td>
		<?php
		break;

		case 'control_delete':
		?>
		<td><?php if ( current_user_can('delete_post', $post->ID) ) { echo "<a href='" . wp_nonce_url("post.php?action=delete&amp;post=$id", 'delete-post_' . $post->ID) . "' class='delete'>" . __('Delete') . "</a>"; } ?></td>
		<?php
		break;

		default:
		?>
		<td <?php echo $attributes ?>><?php do_action('manage_posts_custom_column', $column_name, $post->ID); ?></td>
		<?php
		break;
	}
}
?>
	</tr>
<?php
	$post = $global_post;
}

/**
 * Edit posts rows table for inclusion in administration panels.
 *
 * @package WordPress
 * @subpackage Administration
 */

if ( ! defined('ABSPATH') ) die();
?>
<table class="widefat post fixed" cellspacing="0">
	<thead>
	<tr>
<?php print_column_headers('edit'); ?>
	</tr>
	</thead>

	<tfoot>
	<tr>
<?php print_column_headers('edit', false); ?>
	</tr>
	</tfoot>

	<tbody>
<?php wats_post_rows(); ?>
	</tbody>
</table>