<?php
require_once('../../../wp-config.php');
require_once(dirname(__FILE__) .'/wats-template.php');

global $wats_settings;

check_ajax_referer('filter-wats-tickets-list');
if ($_POST[view] == 1)
{
	$idtype = $_POST[idtype];
	$idpriority = $_POST[idpriority];
	$idstatus = $_POST[idstatus];
	$idowner = $_POST[idowner];
	$categoryfilter = $_POST[categoryfilter];
	$categorylistfilter = $_POST[categorylistfilter];
	echo wats_list_tickets($categoryfilter, $categorylistfilter, 1, $idtype, $idpriority, $idstatus, $idowner);
}

php?>