<?php
?>
jQuery(document).ready(function() {
    jQuery('#filter').click(function() {
			var view = 1;
			wats_loading(document.getElementById("resultticketlist"),watsmsg[0]);
			var idtype = jQuery('#wats_select_ticket_type option:selected').val();
			var idpriority = jQuery('#wats_select_ticket_priority option:selected').val();
			var idstatus = jQuery('#wats_select_ticket_status option:selected').val();
			var idowner = jQuery('#wats_select_ticket_owner option:selected').text();
			var categoryfilter = jQuery('#categoryfilter').val();
			var categorylistfilter = jQuery('#categorylistfilter').val();

			jQuery.post(ajaxurl, {action:"wats_ticket_list_ajax_processing", _ajax_nonce:jQuery("#_wpnonce").val(), view:view, idtype:idtype, idpriority:idpriority, idstatus:idstatus, idowner:idowner, categoryfilter:categoryfilter, categorylistfilter:categorylistfilter},
			function(res)
			{
				wats_stop_loading(document.getElementById("resultticketlist"),res);
				jQuery('#tableticket').tablesorter();
			});
		
		return false;
	});

	if (jQuery('#tableticket').length > 0)
		jQuery('#tableticket').tablesorter();

	return false;
});