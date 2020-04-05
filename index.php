<?php

header('P3P: CP="IDC DSP COR ADM DEVi TAIi PSA PSD IVAi IVDi CONi HIS OUR IND CNT"');
header('Content-Type: text/html; charset=utf-8');

header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past

error_reporting(0);
session_start();

function redirect($url)
{
	return '<script type="text/javascript">
	setTimeout(function () {
	   window.location.href = "' . $url . '";
	}, 4000);
	</script>
	<html><head><meta http-equiv="refresh" content="4;URL=' . $url . '"></head></html>
	(กรุณารอสักครู่ เรากำลังพาท่านไป ... หรือ <a href="' . $url . '"><b>คลิ๊กที่</b></a>)';
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Expires" content="-1">
<title>RAGNAROK REFILL CENTER</title>
<style type="text/css">
<!--
body,td,th {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 12px;
	color: #FFFFFF;
}
body {
	background-image: url(images/bg.jpg);
	background-repeat: repeat-x;
	background-color: #013183;
}
.style1 {
	color: #666666;
	font-weight: bold;
}
.style2 {color: #666666}
a:link {
	color: #FFFFFF;
}
a:visited {
	color: #FFFFFF;
}
a:hover {
	color: #FFFFFF;
}
a:active {
	color: #FFFFFF;
}
-->
</style>
<script language="JavaScript" type="text/JavaScript">
<!--
history.forward();

function disableback() {
	if(window.history.forward(1) != null)
		window.history.forward(1);
}
//-->
</script>
</head>

<body onLoad="disableback();">
<table width="600" border="0" align="center" cellpadding="0" cellspacing="2">
  <tr>
    <td height="45" align="center" background="images/topbaras_fill.gif"><span class="style1">RAGNAROK REFILL CENTER</span></td>
  </tr>
  <tr>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="10">
      <tr>
        <td><?php

include_once('config.php');
include_once('functions.php');

/* connect to mysql server */
$_CONFIG['mysql']['connection'] = new mysqli($_CONFIG['mysql']['host'],$_CONFIG['mysql']['username'],$_CONFIG['mysql']['password']);
if ($_CONFIG['mysql']['connection']->connect_error)
{
    die($_CONFIG['mysql']['connection']->connect_errno . ':' . $_CONFIG['mysql']['connection']->connect_error);
}
$_CONFIG['mysql']['connection']->select_db($_CONFIG['mysql']['db_name']) or die($_CONFIG['mysql']['connection']->errno . ':' . $_CONFIG['mysql']['connection']->error);
$_CONFIG['mysql']['connection']->query('SELECT 1 FROM truemoney LIMIT 1') or die($_CONFIG['mysql']['connection']->errno . ':' . $_CONFIG['mysql']['connection']->error);

if(function_exists('curl_init') == false)
{
	die('cURL extension must be enabled');
}

if(isset($_GET['logout']))
{
	session_unset();
}

if(empty($_SESSION['account_id']))
{
	if(isset($_POST['username']) && misc_parsestring($_POST['username']) == TRUE && empty($_POST['password']) == FALSE)
	{
		sleep(3);
		$_POST['username'] = strtolower($_POST['username']);
		$login_info = game_authen($_POST['username'],$_POST['password']);
		if($login_info['flag'] == true)
		{
			$_SESSION['account_id'] = $login_info['id'];
			$array_desc = str_split('ABCDEFGHIJ');
			$_SESSION['array_desc'] = $array_desc;
			echo '
			<table border="0" align="center" cellpadding="5" cellspacing="2">
			  <tr>
				<td>Login สำเร็จ<br />
				' . redirect('./?' . session_id() . '-' . mt_rand()) . '</td>
			  </tr>
			</table>';
		}
		else
		{
			session_unset();
			echo '
			<table border="0" align="center" cellpadding="5" cellspacing="2">
			  <tr>
					<td>Login ล้มเหลว เนื่องจาก ID หรือ Password ไม่ถูกต้อง<br />
					' . redirect('./?' . session_id() . '-' . mt_rand()) . '</td>
			  </tr>
			</table>';
		}
	}
	else
	{
		echo '
		<form id="form1" name="form1" method="post" action="./?' . session_id() . '-' . mt_rand() . '">
		  <table border="0" align="center" cellpadding="5" cellspacing="2">
			<tr>
			  <td colspan="2" align="center">กรุณา Login ด้วย ID ที่ใช้เข้าสู่เกม</td>
			</tr>
			<tr>
			  <td align="right">ID</td>
			  <td align="left"><input name="username" type="text" id="username" size="15" maxlength="30" /></td>
			</tr>
			<tr>
			  <td align="right">Password</td>
			  <td align="left"><input name="password" type="password" id="password" size="15" maxlength="30" /></td>
			</tr>
			<tr>
			  <td colspan="2" align="center"><input type="submit" name="button" id="button" value="Login" /></td>
			</tr>
		  </table>
		</form>';
	}
}
else
{
	if(isset($_POST['truemoney_password']) && isset($_SESSION['can_refill']) && isset($_POST['encode_hash']) && $_POST['encode_hash'] == md5($_SESSION['can_refill']))
	{
		$_SESSION['can_refill'] = unserialize($_SESSION['can_refill']);
		foreach($_SESSION['can_refill'] as $digit=>$char)
		{
			$_POST['truemoney_password'] = str_replace($char,$digit,$_POST['truemoney_password']);
		}
		unset($_SESSION['can_refill']);
		if (misc_parsestring($_POST['truemoney_password'],'0123456789') == FALSE || strlen($_POST['truemoney_password']) != 14)
		{
			echo '
			<table border="0" align="center" cellpadding="5" cellspacing="2">
			  <tr>
				<td>รหัสบัตรเงินสดที่ระบุมีรูปแบบที่ไม่ถูกต้อง<br />
				' . redirect('./?' . session_id() . '-' . mt_rand()) . '</td>
			  </tr>
			</table>';
		}
		else if (refill_countcards('WHERE password = \'' . $_POST['truemoney_password'] . '\' AND (status = 0 OR status = 1 OR status = 2)') == 1)
		{
			echo '
			<table border="0" align="center" cellpadding="5" cellspacing="2">
			  <tr>
				<td>รหัสบัตรเงินสดที่ระบุถูกใช้งานไปแล้ว<br />
				' . redirect('./?' . session_id() . '-' . mt_rand()) . '</td>
			  </tr>
			</table>';
		}
		else if (refill_countcards('WHERE user_id = ' . $_SESSION['account_id'] . ' AND status = 0') >= 3)
		{
			echo '
			<table border="0" align="center" cellpadding="5" cellspacing="2">
			  <tr>
				<td>ท่านยังมีรหัสบัตรเงินสดที่รอการตรวจสอบอยู่<br />
				' . redirect('./?' . session_id() . '-' . mt_rand()) . '</td>
			  </tr>
			</table>';
		}
		else if (refill_countcards('WHERE user_id = ' . $_SESSION['account_id'] . ' AND status > 2 AND added_time > DATE_SUB(NOW(),INTERVAL 1 DAY)') >= 3)
		{
			echo '
			<table border="0" align="center" cellpadding="5" cellspacing="2">
			  <tr>
				<td>ท่านเติมเงินผิดหลายครั้ง ระบบระงับการเติมเงินเป็นเวลา 24 ชั่วโมง<br />
				' . redirect('./?' . session_id() . '-' . mt_rand()) . '</td>
			  </tr>
			</table>';
		}
		else
		{
			if(($tmpay_ret = refill_sendcard($_SESSION['account_id'],$_POST['truemoney_password'])) !== TRUE)
			{
				echo '
				<table border="0" align="center" cellpadding="5" cellspacing="2">
				  <tr>
					<td>ขออภัย ขณะนี้ระบบ TMPAY.NET ขัดข้อง กรุณาติดต่อเจ้าหน้าที่ (' . $tmpay_ret . ')<br />
					' . redirect('./?' . session_id() . '-' . mt_rand()) . '</td>
				  </tr>
				</table>';
			}
			else
			{
				echo '
				<table border="0" align="center" cellpadding="5" cellspacing="2">
				  <tr>
					<td>ได้รับข้อมูลบัตรเงินสดเรียบร้อย กรุณารอการตรวจสอบจากระบบ<br />
					' . redirect('./?' . session_id() . '-' . mt_rand()) . '</td>
				  </tr>
				</table>';
			}
		}
	}
	else
	{
		$array_desc = $_SESSION['array_desc'];
		shuffle($array_desc);
		$_SESSION['can_refill'] = serialize($array_desc);
		echo '
		<script type="text/javascript">
		function encode_tmnc()
		{
			var temp = document.getElementById("truemoney_password_tmp").value;
		';
		foreach($array_desc as $digit=>$char)
		{
			echo '
			while(temp.indexOf(\'' . $digit . '\')!=-1) { temp = temp.replace(\'' . $digit . '\',\'' . $char . '\'); }';
		}
		echo '
			document.getElementById("truemoney_password").value = temp;
			document.getElementById("truemoney_password_tmp").value = "";
		}
		</script>';
		$cards = refill_getcards($_SESSION['account_id'],20);
		echo '
		<table border="0" align="center" cellpadding="5" cellspacing="2">
		  <tr>
			<td><a href="./?logout"><strong>คลิ๊กที่นี่</strong></a> เพื่อออกจากระบบ (Logout)</td>
		  </tr>
		</table><br />';
		if(empty($cards) == false)
		{
			echo '
			<table border="0" align="center" cellpadding="5" cellspacing="2">
			  <tr>
				<td align="center" bgcolor="#333333"><strong>รหัสบัตรเงินสด</strong></td>
				<td align="center" bgcolor="#333333"><strong>มูลค่า</strong></td>
				<td align="center" bgcolor="#333333"><strong>สถานะ</strong></td>
				<td align="center" bgcolor="#333333"><strong>เวลาที่เพิ่มเข้าระบบ</strong></td>
			  </tr>';
			 foreach($cards as $val)
			{
				echo '
				  <tr>
					<td align="center">' . $val['password'] . '</td>
					<td align="center">' . $_CONFIG['tmpay']['amount'][$val['amount']] . ' บาท</td>
					<td align="center">' . $_CONFIG['tmpay']['card_status'][$val['status']] . '</td>
					<td align="center">' . $val['added_time'] . '</td>
				  </tr>';
			}
			echo '
			 </table><br />';
		}
		echo '
		 <form id="form1" name="form1" method="post" action="./?' . session_id() . '-' . mt_rand() . '" onsubmit="encode_tmnc();">
		  <table border="0" align="center" cellpadding="5" cellspacing="2">
			<tr>
			  <td align="right">รหัสบัตรเงินสด</td>
			  <td align="left"><input name="truemoney_password_tmp" type="text" id="truemoney_password_tmp" size="20" maxlength="14" /><input name="truemoney_password" type="hidden" id="truemoney_password" />
			  <input name="encode_hash" type="hidden" id="encode_hash" value="' . md5($_SESSION['can_refill']) . '" /></td>
			</tr>
			<tr>
			  <td colspan="2" align="left">- เฉพาะบัตรทรูมันนี่เท่านั้น บัตรทรูมูฟไม่สามารถใช้ได้<br />
				- Cash จะเข้าสู่ตัวละครของท่านทันที หลังจากระบบอนุมัติ (ไม่จำเป็นต้องออกจากเกม)<br />
				- ใช้เวลาตรวจสอบประมาณ 1-10 นาที<br />
				- หากเกิดข้อผิดพลาดใดๆ กรุณาแจ้งเจ้าหน้าที่</td>
			</tr>
			<tr>
			  <td colspan="2" align="center"><input type="submit" name="button" id="button" value="Refill" /></td>
			</tr>
		  </table>
		</form>';
	}
}

$_CONFIG['mysql']['connection']->close();

?></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td height="45" align="center" background="images/topbaras_fill.gif"><span class="style2">Refill Center System (Powered by <strong><a href="http://www.tmpay.net/" target="_blank"><span class="style2">TMPAY.NET</span></a></strong>)</span></td>
  </tr>
</table>
</body>
</html>
