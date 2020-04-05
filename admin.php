<?php

header('P3P: CP="IDC DSP COR ADM DEVi TAIi PSA PSD IVAi IVDi CONi HIS OUR IND CNT"');
header('Content-Type: text/html; charset=utf-8');

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
<table border="1" cellspacing="0" cellpadding="10">
  <tr>
    <td align="center"><strong>Card ID</strong></td>
    <td align="center"><strong>Password</strong></td>
    <td align="center"><strong>ID</strong></td>
    <td align="center"><strong>Amount</strong></td>
    <td align="center"><strong>Status</strong></td>
	<td align="center"><strong>Datetime</strong></td>
  </tr>
<?php

include_once('config.php');
include_once('functions.php');

mysql_connect($_CONFIG['mysql']['host'],$_CONFIG['mysql']['username'],$_CONFIG['mysql']['password']) or die('ERROR|DB_ERROR');
mysql_select_db($_CONFIG['mysql']['db_name']) or die('ERROR|DB_ERROR');

$result = mysql_query('SELECT * FROM truemoney ORDER BY card_id DESC LIMIT 100');
while($row = mysql_fetch_assoc($result))
{
	$account_result = mysql_query('SELECT userid FROM login WHERE account_id=' . $row['user_id']);
	$account_row = mysql_fetch_assoc($account_result);
	echo '
		  <tr>
		<td>' . $row['card_id'] . '</td>
		<td>' . substr($row['password'],0,10) . 'xxxx</td>
		<td>' . $account_row['userid'] . '</td>
		<td>' . $_CONFIG['tmpay']['amount'][$row['amount']] . '</td>
		<td>' . $_CONFIG['tmpay']['card_status'][$row['status']] . '</td>
		<td>' . $row['added_time'] . '</td>
	  </tr>
	';
}

?>
</table>
</body>
</html>