<?php

include_once('config.php');
include_once('functions.php');

$_CONFIG['mysql']['connection'] = new mysqli($_CONFIG['mysql']['host'],$_CONFIG['mysql']['username'],$_CONFIG['mysql']['password']);
if ($_CONFIG['mysql']['connection']->connect_error)
{
    die('ERROR|DB_ERROR|' . $_CONFIG['mysql']['connection']->connect_errno . ':' . $_CONFIG['mysql']['connection']->connect_error);
}
$_CONFIG['mysql']['connection']->select_db($_CONFIG['mysql']['db_name']) or die('ERROR|DB_ERROR|' . $_CONFIG['mysql']['connection']->errno . ':' . $_CONFIG['mysql']['connection']->error);

if($_SERVER['REMOTE_ADDR'] != $_CONFIG['tmpay']['access_ip']) die('ERROR|ACCESS_DENIED|' . $_SERVER['REMOTE_ADDR']);
else
{
	$_CONFIG['mysql']['connection']->query('UPDATE truemoney SET amount=' . intval($_GET['amount']) . ',status=' . intval($_GET['status']) . ' WHERE password=\'' . $_CONFIG['mysql']['connection']->real_escape_string($_GET['password']) . '\' AND status=0 LIMIT 1');
	echo 'SUCCEED|AFFECTED=' . $_CONFIG['mysql']['connection']->affected_rows;
}

?>