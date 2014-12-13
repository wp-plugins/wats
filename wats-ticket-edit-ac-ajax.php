<?php
header("Content-Type: application/javascript");
?>
jQuery(document).ready(function() {

	var selected_wats_select_ticket_originator_ac = 0;
	jQuery('#wats_select_ticket_originator_ac').autocomplete({
						source: function(request,response)  {
						jQuery.ajax({
							url: ajaxurl+"?action=wats_ajax_frontend_get_user_list",
							dataType: "json",
							data: {
								value:jQuery('#wats_select_ticket_originator_ac').val(),
								_ajax_nonce:jQuery("#_wpnonce_wats_edit_ticket").val(),
								watsid:watsid,
								type:'adminticketeditauthorlist',
								'cookie': encodeURIComponent(document.cookie)
							},
							success: function(data) {
								if (jQuery.isEmptyObject(data) == true)
									jQuery('#wats_select_ticket_originator').val("0");
								response(
								jQuery.map(data, function(item)
								{ return{value:item.label,label:item.label,hidden:item.value} }));
							}
							});
						},
						select: function(event,ui) {
							selected_wats_select_ticket_originator_ac = 1;
							jQuery('#wats_select_ticket_originator').val(ui.item.hidden);
						},
						close : function(event,ui) {
							if (selected_wats_select_ticket_originator_ac == 0)	
								jQuery('#wats_select_ticket_originator').val("0");
							selected_wats_select_ticket_originator_ac = 0;
						},
						minLength:3,
						delay:300
	});
	
	return false;
});