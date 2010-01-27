<?php
?>
jQuery(document).ready(function() {
    jQuery('#submit_ticket').click(function() {
			var view = 1;
			wats_loading(document.getElementById("resultticketsubmitform"),watsmsg[1]);
			var name = jQuery('#name').val();
			var email = jQuery('#email').val();
			var url = jQuery('#url').val();
			var ticket_title = jQuery('#ticket_title').val();
			var ticket_content = jQuery('#ticket_content').val();
			var idtype = jQuery('#wats_select_ticket_type option:selected').val();
			var idpriority = jQuery('#wats_select_ticket_priority option:selected').val();
			var idstatus = jQuery('#wats_select_ticket_status option:selected').val();
			var categoryfilter = jQuery('#categoryfilter').val();
			var categorylistfilter = jQuery('#categorylistfilter').val();
			jQuery.post(ajaxurl, {action:"wats_ticket_submit_form_ajax_processing", _ajax_nonce:jQuery("#_wpnonce_ticket_submit_form").val(), view:view, name:name, email:email, url:url, ticket_title:ticket_title, ticket_content:ticket_content, idtype:idtype, idpriority:idpriority, idstatus:idstatus, categoryfilter:categoryfilter, categorylistfilter:categorylistfilter},
			function(res)
			{
				wats_stop_loading(document.getElementById("resultticketsubmitform"),res);
			});
		
		return false;
	});

	return false;
});