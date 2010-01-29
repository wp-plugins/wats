<?php
?>
jQuery(document).ready(function() {
	function wats_options_editable_init()
	{
		jQuery('.wats_editable').editable(
		{
			onEdit:wats_options_editable_begin,
			onSubmit:wats_options_editable_end,
			submitBy:'click'
		});
		
		return;
	}
	
	wats_options_editable_init();
	function wats_options_editable_begin()
	{    
		jQuery(this).addClass("wats_editableaccept");
		
	}
	
	function wats_options_editable_end(content)
	{
		jQuery(this).removeClass("wats_editableaccept");
		if (content.current != content.previous)
		{
			if (wats_js_is_string(content.current) == 1)
			{
				var id = jQuery(this);
				var idtable = jQuery(this).parent("tr").parent("tbody").parent("table").attr("id");
				var idvalue = content.current;
				var idprevvalue = content.previous;
				jQuery.post(ajaxurl, {action:"wats_admin_update_option_entry", _ajax_nonce:jQuery("#_wpnonce").val(), 'cookie': encodeURIComponent(document.cookie), idvalue:idvalue, idtable:idtable, idprevvalue:idprevvalue},
				function(res)
				{
					var message_result = eval('(' + res + ')');
					alert(message_result.error);
					if (message_result.success == "FALSE")
					{
						jQuery(id).html(content.previous);
					}
				});
			}
			else
			{
				alert(watsmsg[5]);
				jQuery(this).html(content.previous);
			}
		}
	}
	
    jQuery('#idaddtype').click(function() {
		resultat = 0;
		if (wats_js_check_form("idtype","string") == 1)
			resultat = 1;
		if (resultat == 0)
		{
			jQuery('#idaddtype').attr('disabled','disabled');
			var type = "wats_types";
			var idvalue = jQuery("#idtype").val();
			wats_loading(document.getElementById("resultaddtype"),watsmsg[4]);
			var idcat = 0;
			jQuery.post(ajaxurl, {action:"wats_admin_insert_option_entry", _ajax_nonce:jQuery("#_wpnonce").val(), 'cookie': encodeURIComponent(document.cookie), idvalue:idvalue, type:type, idcat:idcat},
			function(res)
			{
				jQuery('#idaddtype').removeAttr('disabled');
				var message_result = eval('(' + res + ')');
				if (message_result.success == "TRUE")
				{
					var x = jQuery("input[name=typecheck]").length;
					var liste = [message_result.id,message_result.idvalue];
					var editable = [0,1];
					wats_js_add_table_col(document.getElementById("tabletype"),liste,"typecheck",x,message_result.id,editable);
					jQuery("#idtype").val("");
					wats_options_editable_init();
				}
				wats_stop_loading(document.getElementById("resultaddtype"),message_result.error);
			});
		}
		else
			wats_stop_loading(document.getElementById("resultaddtype"),watsmsg[3]);
		return false;
	});

	jQuery('#idsuptype').click(function() {
		if (jQuery("input[name=typecheck]").length == 0)
			wats_stop_loading(document.getElementById("resultsuptype"),watsmsg[0]);
		else if (jQuery("input[name=typecheck]:checked").length == 0)
			wats_stop_loading(document.getElementById("resultsuptype"),watsmsg[1]);
		var type = "wats_types";
	    jQuery("input[name=typecheck]:checked").each(function()
		{
		    if (this.checked == true)
			{
				jQuery('#idsuptype').attr('disabled','disabled');
				var idvalue = this.value;
				var nodetoremove = this.parentNode;
				jQuery.post(ajaxurl, {action:"wats_admin_remove_option_entry", _ajax_nonce:jQuery("#_wpnonce").val(), 'cookie': encodeURIComponent(document.cookie), idvalue:idvalue, type:type},
				function(res)
				{
					jQuery('#idsuptype').removeAttr('disabled');
					var message_result = eval('(' + res + ')');
					wats_stop_loading(document.getElementById("resultsuptype"),message_result.error);
					if (message_result.success == "TRUE")
					{
						parenttoremove = nodetoremove.parentNode;
						parenttoremove.parentNode.removeChild(parenttoremove);
					}
					if (jQuery("input[name=typecheck]").length == 0)
						wats_js_add_blank_cell("tabletype",3,watsmsg[2]);
				});
			}
		});
		return false;
	});
	
	jQuery('#idaddpriority').click(function() {
		resultat = 0;
		if (wats_js_check_form("idpriority","string") == 1)
			resultat = 1;
		if (resultat == 0)
		{
			jQuery('#idaddpriority').attr('disabled','disabled');
			var type = "wats_priorities";
			var idvalue = jQuery("#idpriority").val();
			var idcat = 0;
			wats_loading(document.getElementById("resultaddpriority"),watsmsg[4]);
			jQuery.post(ajaxurl, {action:"wats_admin_insert_option_entry", _ajax_nonce:jQuery("#_wpnonce").val(), 'cookie': encodeURIComponent(document.cookie), idvalue:idvalue, type:type, idcat:idcat},
			function(res)
			{
				jQuery('#idaddpriority').removeAttr('disabled');
				var message_result = eval('(' + res + ')');
				if (message_result.success == "TRUE")
				{
					var x = jQuery("input[name=prioritycheck]").length;
					var liste = [message_result.id,message_result.idvalue];
					var editable = [0,1];
					wats_js_add_table_col(document.getElementById("tablepriority"),liste,"prioritycheck",x,message_result.id,editable);
					jQuery("#idpriority").val("");
					wats_options_editable_init();
				}
				wats_stop_loading(document.getElementById("resultaddpriority"),message_result.error);
			});
		}
		else
			wats_stop_loading(document.getElementById("resultaddpriority"),watsmsg[3]);
		return false;
	});

	jQuery('#idsuppriority').click(function() {
		if (jQuery("input[name=prioritycheck]").length == 0)
			wats_stop_loading(document.getElementById("resultsuppriority"),watsmsg[0]);
		else if (jQuery("input[name=prioritycheck]:checked").length == 0)
			wats_stop_loading(document.getElementById("resultsuppriority"),watsmsg[1]);
		var type = "wats_priorities";
	    jQuery("input[name=prioritycheck]:checked").each(function()
		{
		    if (this.checked == true)
			{
				jQuery('#idsuppriority').attr('disabled','disabled');
				var idvalue = this.value;
				var nodetoremove = this.parentNode;
				jQuery.post(ajaxurl, {action:"wats_admin_remove_option_entry", _ajax_nonce:jQuery("#_wpnonce").val(), 'cookie': encodeURIComponent(document.cookie), idvalue:idvalue, type:type},
				function(res)
				{
					jQuery('#idsuppriority').removeAttr('disabled');
					var message_result = eval('(' + res + ')');
					wats_stop_loading(document.getElementById("resultsuppriority"),message_result.error);
					if (message_result.success == "TRUE")
					{
						parenttoremove = nodetoremove.parentNode;
						parenttoremove.parentNode.removeChild(parenttoremove);
					}
					if (jQuery("input[name=prioritycheck]").length == 0)
						wats_js_add_blank_cell("tablepriority",3,watsmsg[2]);
				});
			}
		});
		return false;
	});
	
	jQuery('#idaddstatus').click(function() {
		resultat = 0;
		if (wats_js_check_form("idstatus","string") == 1)
			resultat = 1;
		if (resultat == 0)
		{
			jQuery('#idaddstatus').attr('disabled','disabled');
			var type = "wats_statuses";
			var idvalue = jQuery("#idstatus").val();
			wats_loading(document.getElementById("resultaddstatus"),watsmsg[4]);
			var idcat = 0;
			jQuery.post(ajaxurl, {action:"wats_admin_insert_option_entry", _ajax_nonce:jQuery("#_wpnonce").val(), 'cookie': encodeURIComponent(document.cookie), idvalue:idvalue, type:type, idcat:idcat},
			function(res)
			{
				jQuery('#idaddstatus').removeAttr('disabled');
				var message_result = eval('(' + res + ')');
				if (message_result.success == "TRUE")
				{
					var x = jQuery("input[name=statuscheck]").length;
					var liste = [message_result.id,message_result.idvalue];
					var editable = [0,1];
					wats_js_add_table_col(document.getElementById("tablestatus"),liste,"statuscheck",x,message_result.id,editable);
					jQuery("#idstatus").val("");
					wats_options_editable_init();
				}
				wats_stop_loading(document.getElementById("resultaddstatus"),message_result.error);
			});
		}
		else
			wats_stop_loading(document.getElementById("resultaddstatus"),watsmsg[3]);
		return false;
	});

	jQuery('#idsupstatus').click(function() {
		if (jQuery("input[name=statuscheck]").length == 0)
			wats_stop_loading(document.getElementById("resultsupstatus"),watsmsg[0]);
		else if (jQuery("input[name=statuscheck]:checked").length == 0)
			wats_stop_loading(document.getElementById("resultsupstatus"),watsmsg[1]);
		var type = "wats_statuses";
	    jQuery("input[name=statuscheck]:checked").each(function()
		{
		    if (this.checked == true)
			{
				jQuery('#idsupstatus').attr('disabled','disabled');
				var idvalue = this.value;
				var nodetoremove = this.parentNode;
				jQuery.post(ajaxurl, {action:"wats_admin_remove_option_entry", _ajax_nonce:jQuery("#_wpnonce").val(), 'cookie': encodeURIComponent(document.cookie), idvalue:idvalue, type:type},
				function(res)
				{
					jQuery('#idsupstatus').removeAttr('disabled');
					var message_result = eval('(' + res + ')');
					wats_stop_loading(document.getElementById("resultsupstatus"),message_result.error);
					if (message_result.success == "TRUE")
					{
						parenttoremove = nodetoremove.parentNode;
						parenttoremove.parentNode.removeChild(parenttoremove);
					}
					if (jQuery("input[name=statuscheck]").length == 0)
						wats_js_add_blank_cell("tablestatus",3,watsmsg[2]);
				});
			}
		});
		return false;
	});
	
	jQuery('#idaddcat').click(function() {
		jQuery('#idaddcat').attr('disabled','disabled');
		var type = "wats_categories";
		var idvalue = jQuery('#catlist option:selected').text();
		var idcat = jQuery('#catlist option:selected').val();
		wats_loading(document.getElementById("resultaddcat"),watsmsg[4]);
		jQuery.post(ajaxurl, {action:"wats_admin_insert_option_entry", _ajax_nonce:jQuery("#_wpnonce").val(), 'cookie': encodeURIComponent(document.cookie), idvalue:idvalue, type:type, idcat:idcat},
		function(res)
		{
			jQuery('#idaddcat').removeAttr('disabled');
			var message_result = eval('(' + res + ')');
			if (message_result.success == "TRUE")
			{
				var x = jQuery("input[name=catcheck]").length;
				var liste = [message_result.id,message_result.idvalue];
				var editable = [0,1];
				wats_js_add_table_col(document.getElementById("tablecat"),liste,"catcheck",x,message_result.id,editable);
			}
			wats_stop_loading(document.getElementById("resultaddcat"),message_result.error);
		});

		return false;
	});

	jQuery('#idsupcat').click(function() {
		if (jQuery("input[name=catcheck]").length == 0)
			wats_stop_loading(document.getElementById("resultsupcat"),watsmsg[0]);
		else if (jQuery("input[name=catcheck]:checked").length == 0)
			wats_stop_loading(document.getElementById("resultsupcat"),watsmsg[1]);
		var type = "wats_categories";
	    jQuery("input[name=catcheck]:checked").each(function()
		{
		    if (this.checked == true)
			{
				jQuery('#idsupcat').attr('disabled','disabled');
				var idvalue = this.value;
				var nodetoremove = this.parentNode;
				jQuery.post(ajaxurl, {action:"wats_admin_remove_option_entry", _ajax_nonce:jQuery("#_wpnonce").val(), 'cookie': encodeURIComponent(document.cookie), idvalue:idvalue, type:type},
				function(res)
				{
					jQuery('#idsupcat').removeAttr('disabled');
					var message_result = eval('(' + res + ')');
					wats_stop_loading(document.getElementById("resultsupcat"),message_result.error);
					if (message_result.success == "TRUE")
					{
						parenttoremove = nodetoremove.parentNode;
						parenttoremove.parentNode.removeChild(parenttoremove);
					}
					if (jQuery("input[name=catcheck]").length == 0)
						wats_js_add_blank_cell("tablecat",3,watsmsg[2]);
				});
			}
		});
		return false;
	});
	
	return false;
});