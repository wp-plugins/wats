<?php
?>
jQuery(document).ready(function() {

	if (jQuery.browser.msie)
		jQuery('.wats_select').css("width","auto");

    jQuery('#filter').click(function() {
			jQuery('#filter').attr('disabled','disabled');
			var view = 1;
			wats_loading(document.getElementById("resultticketlist"),watsmsg[0]);
			var idtype = jQuery('#wats_select_ticket_type_tl option:selected').val();
			var idpriority = jQuery('#wats_select_ticket_priority_tl option:selected').val();
			var idstatus = jQuery('#wats_select_ticket_status_tl option:selected').val();
			if (jQuery('#wats_select_ticket_author_tl option:selected').val())
				var idauthor = jQuery('#wats_select_ticket_author_tl option:selected').val();
			else
				var idauthor = 0;
			if (jQuery('#wats_select_ticket_author_meta_value_tl option:selected').val())
				var idauthormetavalue = jQuery('#wats_select_ticket_author_meta_value_tl option:selected').val();
			else
				var idauthormetavalue = 0;
			if (jQuery('#wats_select_ticket_owner_tl option:selected').val())
				var idowner = jQuery('#wats_select_ticket_owner_tl option:selected').val();
			else
				var idowner = 0;
			var categoryfilter = jQuery('#categoryfilter').val();
			var categorylistfilter = jQuery('#categorylistfilter').val();
			jQuery.post(ajaxurl, {action:"wats_ticket_list_ajax_processing", _ajax_nonce:jQuery("#_wpnonce_ticket_list").val(), view:view, idtype:idtype, idpriority:idpriority, idstatus:idstatus, idauthor:idauthor, idauthormetavalue:idauthormetavalue, idowner:idowner, categoryfilter:categoryfilter, categorylistfilter:categorylistfilter},
			function(res)
			{
				jQuery('#filter').removeAttr('disabled');
				wats_stop_loading(document.getElementById("resultticketlist"),res);
				jQuery('#tableticket').tablesorter();
			});
		
		return false;
	});

	if (jQuery('#tableticket').length > 0)
		jQuery('#tableticket').tablesorter();

	return false;
});