<?php
header("Content-Type: application/javascript");
?>
jQuery(document).ready(function() {
	
	var wats_select_ticket_updater_ac = 0;
	if (jQuery("#wats_select_ticket_updater_ac").is("input"))
	{
		jQuery('#wats_select_ticket_updater_ac').autocomplete({
							source: function(request,response)  {
							jQuery.ajax({
								url: ajaxurl+"?action=wats_ajax_frontend_get_user_list",
								dataType: "json",
								data: {
									value:jQuery('#wats_select_ticket_updater_ac').val(),
									_ajax_nonce:jQuery("#_wpnonce_wats_single_ticket").val(),
									type:'frontendupdaterlist',
									'cookie': encodeURIComponent(document.cookie),
									watsid:watsid
								},
								success: function(data) {
									if (jQuery.isEmptyObject(data) == true)
										jQuery('#wats_select_ticket_updater').val("0");
									response(
									jQuery.map(data, function(item)
									{ return{value:item.label,label:item.label,hidden:item.value} }));
								}
								});
							},
							select: function(event,ui) {
								wats_select_ticket_updater_ac = 1;
								jQuery('#wats_select_ticket_updater').val(ui.item.hidden);
							},
							close : function(event,ui) {
								if (wats_select_ticket_updater_ac == 0)	
									jQuery('#wats_select_ticket_updater').val("0");
								wats_select_ticket_updater_ac = 0;
							},
							minLength:3,
							delay:300
		});
	}
	
	return false;
});