<?php

function wats_ticket_history_meta_box($post)
{

	echo _e('Here are the logs of event for ticket');

	return;
}

function wats_ticket_details_meta_box($post)
{
	global $wats_ticket_priority, $wats_ticket_status, $wats_ticket_type;

	echo _e('Ticket type').' : ';
	echo '<select name="wats_select_ticket_type" id="wats_select_ticket_type">';
	foreach ($wats_ticket_type as $key => $value)
	{
		echo '<option value='.$key.'>'.$value.'</option>';
	}
	echo '</select><br /><br />';
	
	echo _e('Ticket priority').' : ';
	echo '<select name="wats_select_ticket_priority" id="wats_select_ticket_priority">';
	foreach ($wats_ticket_priority as $key => $value)
	{
		echo '<option value='.$key.'>'.$value.'</option>';
	}
	echo '</select><br /><br />';
	
	echo _e('Ticket status').' : ';
	echo '<select name="wats_select_ticket_status" id="wats_select_ticket_status">';
	foreach ($wats_ticket_status as $key => $value)
	{
		echo '<option value='.$key.'>'.$value.'</option>';
	}
	echo '</select>';
	
	return;
}

function wats_ticket_submit_meta_box($post)
{
	global $action;

	$can_publish = current_user_can('publish_posts');
?>
<div class="submitbox" id="submitpost">

<div id="minor-publishing">

<?php // Hidden submit button early on so that the browser chooses the right button when form is submitted with Return key ?>
<div style="display:none;">
<input type="submit" name="save" value="<?php echo attribute_escape( __('Save') ); ?>" />
</div>

<div id="minor-publishing-actions">
<div id="save-action">
<?php if ( 'publish' != $post->post_status && 'future' != $post->post_status && 'pending' != $post->post_status )  { ?>
<input <?php if ( 'private' == $post->post_status ) { ?>style="display:none"<?php } ?> type="submit" name="save" id="save-post" value="<?php echo attribute_escape( __('Save Draft') ); ?>" tabindex="4" class="button button-highlighted" />
<?php } elseif ( 'pending' == $post->post_status && $can_publish ) { ?>
<input type="submit" name="save" id="save-post" value="<?php echo attribute_escape( __('Save as Pending') ); ?>" tabindex="4" class="button button-highlighted" />
<?php } ?>
</div>

<div id="preview-action">
<?php $preview_link = 'publish' == $post->post_status ? clean_url(get_permalink($post->ID)) : clean_url(apply_filters('preview_post_link', add_query_arg('preview', 'true', get_permalink($post->ID)))); ?>

<a class="preview button" href="<?php echo $preview_link; ?>" target="wp-preview" id="post-preview" tabindex="4"><?php _e('Preview'); ?></a>
<input type="hidden" name="wp-preview" id="wp-preview" value="" />
</div>

<div class="clear"></div>
</div><?php // /minor-publishing-actions ?>

<div id="misc-publishing-actions">

<div class="misc-pub-section<?php if ( !$can_publish ) { echo '  misc-pub-section-last'; } ?>"><label for="post_status"><?php _e('Status:') ?></label>
<b><span id="post-status-display">
<?php
switch ( $post->post_status ) {
	case 'private':
		_e('Privately Published');
		break;
	case 'publish':
		_e('Published');
		break;
	case 'future':
		_e('Scheduled');
		break;
	case 'pending':
		_e('Pending Review');
		break;
	case 'draft':
		_e('Draft');
		break;
}
?>
</span></b>
<?php if ( 'publish' == $post->post_status || 'private' == $post->post_status || $can_publish ) { ?>
<a href="#post_status" <?php if ( 'private' == $post->post_status ) { ?>style="display:none;" <?php } ?>class="edit-post-status hide-if-no-js" tabindex='4'><?php _e('Edit') ?></a>

<div id="post-status-select" class="hide-if-js">
<input type="hidden" name="hidden_post_status" id="hidden_post_status" value="<?php echo $post->post_status; ?>" />
<select name='post_status' id='post_status' tabindex='4'>
<?php if ( 'publish' == $post->post_status ) : ?>
<option<?php selected( $post->post_status, 'publish' ); ?> value='publish'><?php _e('Published') ?></option>
<?php elseif ( 'private' == $post->post_status ) : ?>
<option<?php selected( $post->post_status, 'private' ); ?> value='publish'><?php _e('Privately Published') ?></option>
<?php elseif ( 'future' == $post->post_status ) : ?>
<option<?php selected( $post->post_status, 'future' ); ?> value='future'><?php _e('Scheduled') ?></option>
<?php endif; ?>
<option<?php selected( $post->post_status, 'pending' ); ?> value='pending'><?php _e('Pending Review') ?></option>
<option<?php selected( $post->post_status, 'draft' ); ?> value='draft'><?php _e('Draft') ?></option>
</select>
 <a href="#post_status" class="save-post-status hide-if-no-js button"><?php _e('OK'); ?></a>
 <a href="#post_status" class="cancel-post-status hide-if-no-js"><?php _e('Cancel'); ?></a>
</div>

<?php } ?>
</div><?php // /misc-pub-section ?>

</div>
<div class="clear"></div>
</div>

<div id="major-publishing-actions">
<?php do_action('post_submitbox_start'); ?>
<div id="delete-action">
<?php
if ( ( 'edit' == $action ) && current_user_can('delete_post', $post->ID) ) { ?>
<a class="submitdelete deletion" href="<?php echo wp_nonce_url("post.php?action=delete&amp;post=$post->ID", 'delete-post_' . $post->ID); ?>" onclick="if ( confirm('<?php echo js_escape(sprintf( ('draft' == $post->post_status) ? __("You are about to delete this draft '%s'\n  'Cancel' to stop, 'OK' to delete.") : __("You are about to delete this post '%s'\n  'Cancel' to stop, 'OK' to delete."), $post->post_title )); ?>') ) {return true;}return false;"><?php _e('Delete'); ?></a>
<?php } ?>
</div>

<div id="publishing-action">
<?php
if ( !in_array( $post->post_status, array('publish', 'future', 'private') ) || 0 == $post->ID ) { ?>
<?php if ( current_user_can('publish_posts') ) : ?>
	<?php if ( !empty($post->post_date_gmt) && time() < strtotime( $post->post_date_gmt . ' +0000' ) ) : ?>
		<input name="original_publish" type="hidden" id="original_publish" value="<?php _e('Schedule') ?>" />
		<input name="publish" type="submit" class="button-primary" id="publish" tabindex="5" accesskey="p" value="<?php _e('Schedule') ?>" />
	<?php else : ?>
		<input name="original_publish" type="hidden" id="original_publish" value="<?php _e('Publish') ?>" />
		<input name="publish" type="submit" class="button-primary" id="publish" tabindex="5" accesskey="p" value="<?php _e('Publish') ?>" />
	<?php endif; ?>
<?php else : ?>
	<input name="original_publish" type="hidden" id="original_publish" value="<?php _e('Submit for Review') ?>" />
	<input name="publish" type="submit" class="button-primary" id="publish" tabindex="5" accesskey="p" value="<?php _e('Submit for Review') ?>" />
<?php endif; ?>
<?php } else { ?>
	<input name="original_publish" type="hidden" id="original_publish" value="<?php _e('Update Post') ?>" />
	<input name="save" type="submit" class="button-primary" id="publish" tabindex="5" accesskey="p" value="<?php _e('Update Post') ?>" />
<?php } ?>
</div>
<div class="clear"></div>
</div>
</div>

<?php
	return;
}

/****************************************/
/*                                      */
/* Function to display ticket edit form */
/* view 0 : new ticket                  */
/* view 1 : existing ticket             */
/*                                      */
/****************************************/

function wats_ticket_edit_form($view, $title)
{

	echo '<div class="wrap"><h2>'.wp_specialchars($title).'</h2></div>';
	if ( $notice ) : ?>
<div id="notice" class="error"><p><?php echo $notice ?></p></div>
<?php endif; ?>
<?php if (isset($_GET['message'])) : ?>
<div id="message" class="updated fade"><p><?php echo $messages[$_GET['message']]; ?></p></div>
<?php endif; ?>
<form name="post" action="admin.php?page=wats-ticket-new" method="post" id="post">
<?php

if ( 0 == $post_ID)
	wp_nonce_field('add-post');
else
	wp_nonce_field('update-post_' .  $post_ID);
?>

<div id="poststuff" class="metabox-holder">
<div id="side-info-column" class="inner-sidebar">
<?php 
add_meta_box('ticketsdetaildiv', __('Ticket details'), 'wats_ticket_details_meta_box', 'ticket', 'side', 'core');
add_meta_box('submitdiv', __('Publish'), 'wats_ticket_submit_meta_box', 'ticket', 'side', 'core');
add_meta_box('ticketshistorydiv', __('Ticket history'), 'wats_ticket_history_meta_box', 'ticket', 'normal', 'core');
do_action('submitpost_box');
$side_meta_boxes = do_meta_boxes('ticket', 'side', $post);
?>
</div>
<div id="post-body" class="<?php echo $side_meta_boxes ? 'has-sidebar' : ''; ?>">
<div id="post-body-content" class="has-sidebar-content">
<div id="titlediv">
<div id="titlewrap">
	<label class="invisible" for="title"><?php _e('Ticket title') ?></label>
	<input type="text" name="post_title" size="30" tabindex="1" value="<?php echo htmlspecialchars( $post->post_title ); ?>" id="title" autocomplete="off" />
</div>
<div class="inside">
<?php
$sample_permalink_html = get_sample_permalink_html($post->ID);
echo '<div id="edit-slug-box">';
	if ( ! empty($post->ID) && ! empty($sample_permalink_html) ) :
		echo $sample_permalink_html;
endif; ?>
	</div>

</div></div>

<div id="<?php echo user_can_richedit() ? 'postdivrich' : 'postdiv'; ?>">
<legend><?php _e('Ticket details') ?></legend>
<?php the_editor($content,'content');
echo '</div><br />';
do_meta_boxes('ticket', 'normal', $post);
do_action('edit_form_advanced');
do_meta_boxes('ticket', 'advanced', $post);
do_action('dbx_post_sidebar');
echo '</div></div></div></form>';


	return;
}

?>
