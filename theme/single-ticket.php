<?php get_header(); ?>

<div id="content" class="narrowcolumn">
	<?php 
	if (have_posts()) : while (have_posts()) : the_post(); 
	
	if (wats_check_visibility_rights())
	{
	?>
	
		<div class="navigation">
			<div class="alignleft"><?php previous_post_link('&laquo; %link') ?></div>
			<div class="alignright"><?php next_post_link('%link &raquo;') ?></div>
		</div>
		
		<div <?php post_class() ?> id="post-<?php the_ID(); ?>">
			<h2><?php the_title(); ?></h2>
			<?php echo __("Current priority : ",'WATS'). wats_ticket_get_priority($post)."<br />"; ?>
			<?php echo __("Current status : ",'WATS'). wats_ticket_get_status($post)."<br />"; ?>
			<?php echo __("Ticket type : ",'WATS'). wats_ticket_get_type($post)."<br />"; ?>
			<div class="entry">
				<?php the_content('<p class="serif">Read the rest of the ticket &raquo;</p>'); ?>
				<?php wp_link_pages(array('before' => '<p><strong>Pages:</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>
				<?php the_tags( '<p>Mots-clefs&nbsp;: ', ', ', '</p>'); ?> 
				<p class="postmetadata alt">
					<small>
						<?php printf(__('This entry was submited %1$s on %2$s at %3$s and is filed under %4$s.', 'WATS'), $time_since, get_the_time(__('l, F jS, Y', 'WATS')), get_the_time(), get_the_category_list(', ')); ?>
						<?php printf(__("You can follow any responses to this entry through the <a href='%s'>RSS 2.0</a> feed.", "WATS"), get_post_comments_feed_link()); ?> 
						<?php if ( comments_open() && pings_open() ) {
							// Both Comments and Pings are open ?>
							<?php printf(__('You can <a href="#respond">leave an update</a>, or <a href="%s" rel="trackback">trackback</a> from your own site.', 'WATS'), trackback_url(false)); ?>

						<?php } elseif ( !comments_open() && pings_open() ) {
							// Only Pings are Open ?>
							<?php printf(__('Responses are currently closed, but you can <a href="%s" rel="trackback">trackback</a> from your own site.', 'WATS'), trackback_url(false)); ?>

						<?php } elseif ( comments_open() && !pings_open() ) {
							// Comments are open, Pings are not ?>
							<?php _e('You can skip to the end and leave an update. Pinging is currently not allowed.', 'WATS'); ?>

						<?php } elseif ( !comments_open() && !pings_open() ) {
							// Neither Comments, nor Pings are open ?>
							<?php _e('Both comments and pings are currently closed.', 'WATS'); ?>

						<?php } wats_edit_ticket_link(__('Edit this entry', 'WATS'),'','.'); ?>
					</small>				
				</p>
			</div>
		</div>
	<?php 
		comments_template(); 
	}
	else
	{
		echo '<p>'.__('Sorry, you don\'t have the rights to browse this ticket.', 'WATS').'</p>';
	}
	?>

	<?php endwhile; else: ?>
		<p><?php _e('Sorry, no tickets matched your criteria.', 'WATS'); ?></p>
	<?php endif; ?>
</div>

<?php get_sidebar(); get_footer(); ?>