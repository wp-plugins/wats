<?php
include('../../../wp-config.php');
$site_url = get_option('siteurl');
$watsmsg[0] = __('Error : there is nothing to remove!','WATS');
$watsmsg[1] = __('Error : please select an entry to remove!','WATS');
$watsmsg[2] = __('No entry','WATS');
$watsmsg[3] = __('Please correct the errors','WATS');
$watsmsg[4] = __('Adding entry','WATS');
$watsmsg[5] = __('Error : the string contains invalid caracters!','WATS');
?>
jQuery(document).ready(function() {
	jQuery('.editable').editable(
	{
		onEdit:wats_options_editable_begin,
		onSubmit:wats_options_editable_end,
		submitBy:'click'
	});
	
	function wats_options_editable_begin()
	{    
		jQuery(this).addClass("editableaccept");
		
	}
	
	function wats_options_editable_end(content)
	{
		jQuery(this).removeClass("editableaccept");
		if (content.current != content.previous)
		{
			if (wats_js_is_string(content.current) == 1)
			{
				var id = jQuery(this);
				var idtable = jQuery(this).parent("tr").parent("tbody").parent("table").attr("id");
				var idvalue = content.current;
				var idprevvalue = content.previous;
				jQuery.post("<?php echo $site_url; ?>/wp-admin/admin-ajax.php", {action:"wats_admin_update_option_entry", _ajax_nonce:jQuery("#_wpnonce").val(), 'cookie': encodeURIComponent(document.cookie), idvalue:idvalue, idtable:idtable, idprevvalue:idprevvalue},
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
				alert('<?php echo $watsmsg[5]; ?>');
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
			var type = "wats_types";
			var idvalue = jQuery("#idtype").val();
			wats_loading(document.getElementById("resultaddtype"),"<?php echo $watsmsg[4]; ?>");
			var idcat = 0;
			jQuery.post("<?php echo $site_url; ?>/wp-admin/admin-ajax.php", {action:"wats_admin_insert_option_entry", _ajax_nonce:jQuery("#_wpnonce").val(), 'cookie': encodeURIComponent(document.cookie), idvalue:idvalue, type:type, idcat:idcat},
			function(res)
			{
				var message_result = eval('(' + res + ')');
				if (message_result.success == "TRUE")
				{
					var x = jQuery("input[name=typecheck]").length;
					var liste = [message_result.id,message_result.idvalue];
					wats_js_add_table_col(document.getElementById("tabletype"),liste,"typecheck",x,message_result.id);
					jQuery("#idtype").val("");
				}
				wats_stop_loading(document.getElementById("resultaddtype"),message_result.error);
			});
		}
		else
			wats_stop_loading(document.getElementById("resultaddtype"),"<?php echo $watsmsg[3]; ?>");
		return false;
	});

	jQuery('#idsuptype').click(function() {
		if (jQuery("input[name=typecheck]").length == 0)
			wats_stop_loading(document.getElementById("resultsuptype"),"<?php echo $watsmsg[0]; ?>");
		else if (jQuery("input[name=typecheck][checked]").length == 0)
			wats_stop_loading(document.getElementById("resultsuptype"),"<?php echo $watsmsg[1]; ?>");
		var type = "wats_types";
	    jQuery("input[name=typecheck][checked]").each(function()
		{
		    if (this.checked == true)
			{
				var idvalue = this.value;
				var nodetoremove = this.parentNode;
				jQuery.post("<?php echo $site_url; ?>/wp-admin/admin-ajax.php", {action:"wats_admin_remove_option_entry", _ajax_nonce:jQuery("#_wpnonce").val(), 'cookie': encodeURIComponent(document.cookie), idvalue:idvalue, type:type},
				function(res)
				{
					var message_result = eval('(' + res + ')');
					wats_stop_loading(document.getElementById("resultsuptype"),message_result.error);
					if (message_result.success == "TRUE")
					{
						parenttoremove = nodetoremove.parentNode;
						parenttoremove.parentNode.removeChild(parenttoremove);
					}
					if (jQuery("input[name=typecheck]").length == 0)
						wats_js_add_blank_cell("tabletype",3,"<?php echo $watsmsg[2]; ?>");
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
			var type = "wats_priorities";
			var idvalue = jQuery("#idpriority").val();
			var idcat = 0;
			wats_loading(document.getElementById("resultaddpriority"),"<?php echo $watsmsg[4]; ?>");
			jQuery.post("<?php echo $site_url; ?>/wp-admin/admin-ajax.php", {action:"wats_admin_insert_option_entry", _ajax_nonce:jQuery("#_wpnonce").val(), 'cookie': encodeURIComponent(document.cookie), idvalue:idvalue, type:type, idcat:idcat},
			function(res)
			{
				var message_result = eval('(' + res + ')');
				if (message_result.success == "TRUE")
				{
					var x = jQuery("input[name=prioritycheck]").length;
					var liste = [message_result.id,message_result.idvalue];
					wats_js_add_table_col(document.getElementById("tablepriority"),liste,"prioritycheck",x,message_result.id);
					jQuery("#idpriority").val("");
				}
				wats_stop_loading(document.getElementById("resultaddpriority"),message_result.error);
			});
		}
		else
			wats_stop_loading(document.getElementById("resultaddpriority"),"<?php echo $watsmsg[3]; ?>");
		return false;
	});

	jQuery('#idsuppriority').click(function() {
		if (jQuery("input[name=prioritycheck]").length == 0)
			wats_stop_loading(document.getElementById("resultsuppriority"),"<?php echo $watsmsg[0]; ?>");
		else if (jQuery("input[name=prioritycheck][checked]").length == 0)
			wats_stop_loading(document.getElementById("resultsuppriority"),"<?php echo $watsmsg[1]; ?>");
		var type = "wats_priorities";
	    jQuery("input[name=prioritycheck][checked]").each(function()
		{
		    if (this.checked == true)
			{
				var idvalue = this.value;
				var nodetoremove = this.parentNode;
				jQuery.post("<?php echo $site_url; ?>/wp-admin/admin-ajax.php", {action:"wats_admin_remove_option_entry", _ajax_nonce:jQuery("#_wpnonce").val(), 'cookie': encodeURIComponent(document.cookie), idvalue:idvalue, type:type},
				function(res)
				{
					var message_result = eval('(' + res + ')');
					wats_stop_loading(document.getElementById("resultsuppriority"),message_result.error);
					if (message_result.success == "TRUE")
					{
						parenttoremove = nodetoremove.parentNode;
						parenttoremove.parentNode.removeChild(parenttoremove);
					}
					if (jQuery("input[name=prioritycheck]").length == 0)
						wats_js_add_blank_cell("tablepriority",3,"<?php echo $watsmsg[2]; ?>");
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
			var type = "wats_statuses";
			var idvalue = jQuery("#idstatus").val();
			wats_loading(document.getElementById("resultaddstatus"),"<?php echo $watsmsg[4]; ?>");
			var idcat = 0;
			jQuery.post("<?php echo $site_url; ?>/wp-admin/admin-ajax.php", {action:"wats_admin_insert_option_entry", _ajax_nonce:jQuery("#_wpnonce").val(), 'cookie': encodeURIComponent(document.cookie), idvalue:idvalue, type:type, idcat:idcat},
			function(res)
			{
				var message_result = eval('(' + res + ')');
				if (message_result.success == "TRUE")
				{
					var x = jQuery("input[name=statuscheck]").length;
					var liste = [message_result.id,message_result.idvalue];
					wats_js_add_table_col(document.getElementById("tablestatus"),liste,"statuscheck",x,message_result.id);
					jQuery("#idstatus").val("");
				}
				wats_stop_loading(document.getElementById("resultaddstatus"),message_result.error);
			});
		}
		else
			wats_stop_loading(document.getElementById("resultaddstatus"),"<?php echo $watsmsg[3]; ?>");
		return false;
	});

	jQuery('#idsupstatus').click(function() {
		if (jQuery("input[name=statuscheck]").length == 0)
			wats_stop_loading(document.getElementById("resultsupstatus"),"<?php echo $watsmsg[0]; ?>");
		else if (jQuery("input[name=statuscheck][checked]").length == 0)
			wats_stop_loading(document.getElementById("resultsupstatus"),"<?php echo $watsmsg[1]; ?>");
		var type = "wats_statuses";
	    jQuery("input[name=statuscheck][checked]").each(function()
		{
		    if (this.checked == true)
			{
				var idvalue = this.value;
				var nodetoremove = this.parentNode;
				jQuery.post("<?php echo $site_url; ?>/wp-admin/admin-ajax.php", {action:"wats_admin_remove_option_entry", _ajax_nonce:jQuery("#_wpnonce").val(), 'cookie': encodeURIComponent(document.cookie), idvalue:idvalue, type:type},
				function(res)
				{
					var message_result = eval('(' + res + ')');
					wats_stop_loading(document.getElementById("resultsupstatus"),message_result.error);
					if (message_result.success == "TRUE")
					{
						parenttoremove = nodetoremove.parentNode;
						parenttoremove.parentNode.removeChild(parenttoremove);
					}
					if (jQuery("input[name=statuscheck]").length == 0)
						wats_js_add_blank_cell("tablestatus",3,"<?php echo $watsmsg[2]; ?>");
				});
			}
		});
		return false;
	});
	
	jQuery('#idaddcat').click(function() {
		var type = "wats_categories";
		var idvalue = jQuery('#catlist option:selected').text();
		var idcat = jQuery('#catlist option:selected').val();
		wats_loading(document.getElementById("resultaddcat"),"<?php echo $watsmsg[4]; ?>");
		jQuery.post("<?php echo $site_url; ?>/wp-admin/admin-ajax.php", {action:"wats_admin_insert_option_entry", _ajax_nonce:jQuery("#_wpnonce").val(), 'cookie': encodeURIComponent(document.cookie), idvalue:idvalue, type:type, idcat:idcat},
		function(res)
		{
			var message_result = eval('(' + res + ')');
			if (message_result.success == "TRUE")
			{
				var x = jQuery("input[name=catcheck]").length;
				var liste = [message_result.id,message_result.idvalue];
				wats_js_add_table_col(document.getElementById("tablecat"),liste,"catcheck",x,message_result.id);
			}
			wats_stop_loading(document.getElementById("resultaddcat"),message_result.error);
		});

		return false;
	});

	jQuery('#idsupcat').click(function() {
		if (jQuery("input[name=catcheck]").length == 0)
			wats_stop_loading(document.getElementById("resultsupcat"),"<?php echo $watsmsg[0]; ?>");
		else if (jQuery("input[name=catcheck][checked]").length == 0)
			wats_stop_loading(document.getElementById("resultsupcat"),"<?php echo $watsmsg[1]; ?>");
		var type = "wats_categories";
	    jQuery("input[name=catcheck][checked]").each(function()
		{
		    if (this.checked == true)
			{
				var idvalue = this.value;
				var nodetoremove = this.parentNode;
				jQuery.post("<?php echo $site_url; ?>/wp-admin/admin-ajax.php", {action:"wats_admin_remove_option_entry", _ajax_nonce:jQuery("#_wpnonce").val(), 'cookie': encodeURIComponent(document.cookie), idvalue:idvalue, type:type},
				function(res)
				{
					var message_result = eval('(' + res + ')');
					wats_stop_loading(document.getElementById("resultsupcat"),message_result.error);
					if (message_result.success == "TRUE")
					{
						parenttoremove = nodetoremove.parentNode;
						parenttoremove.parentNode.removeChild(parenttoremove);
					}
					if (jQuery("input[name=catcheck]").length == 0)
						wats_js_add_blank_cell("tablecat",3,"<?php echo $watsmsg[2]; ?>");
				});
			}
		});
		return false;
	});
	
	return false;
});