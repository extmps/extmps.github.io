<?php

if(!function_exists('str_split'))
{
	function str_split($string,$string_length=1)
	{
		if(strlen($string)>$string_length || !$string_length)
		{
			do
			{
				$c = strlen($string);
				$parts[] = substr($string,0,$string_length);
				$string = substr($string,$string_length);
			} while($string !== false);
		}
		else
		{
			$parts = array($string);
		}
		return $parts;
	}
}

function misc_parsestring($text,$allowchr='1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ')
{
	if(empty($allowchr))
		$allowchr = '1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	if(empty($text)) return FALSE;
	$size = strlen($text);
	for($i=0; $i < $size; $i++) {
		$tmpchr = substr($text, $i , 1);
		if(strpos($allowchr,$tmpchr) === FALSE) 
			return FALSE;
	}
	return TRUE;
}

function game_authen($username,$password)
{
	global $_CONFIG;

	$login_flag = false;
	$user_id = 0;
	$result = $_CONFIG['mysql']['connection']->query('SELECT account_id,user_pass FROM login WHERE userid = \'' .  $_CONFIG['mysql']['connection']->real_escape_string($username) . '\' LIMIT 1');
	if($result->num_rows == 1)
	{
		$row = $result->fetch_assoc();
		if ($_CONFIG['tmpay']['use_md5'] == true)
		{
			$password = md5($password);
		}
		if(strcmp($password,$row['user_pass']) == 0)
		{
			$login_flag = true;
			$user_id = $row['account_id'];
		}
	}
	return array('flag'=>$login_flag,'id'=>$user_id);
}

function refill_countcards($query)
{
	global $_CONFIG;

	$result = $_CONFIG['mysql']['connection']->query('SELECT COUNT(*) FROM truemoney ' . $query);
	$row = $result->fetch_row();
	return $row[0];
}

function refill_getcards($userid,$count=20)
{
	global $_CONFIG;

	$cards = array();
	//card status : 0=waiting , 1=succeed , 2=succeed(cash was added) , 3=already used , 4=incorrect password , 5=truemovecard
	$result = $_CONFIG['mysql']['connection']->query('SELECT * FROM truemoney WHERE user_id = ' . $userid . ' ORDER BY card_id DESC LIMIT ' . $count);
	while($row = $result->fetch_assoc())
	{
		$cards[] = $row;
	}
	return $cards;
}

function refill_sendcard($user_id,$password)
{
	global $_CONFIG;
	
	$curl = curl_init('https://203.146.127.112/tmpay.net/TPG/backend.php?merchant_id=' .$_CONFIG['tmpay']['merchant_id'] . '&password=' . $password . '&resp_url=' . $_CONFIG['tmpay']['resp_url']);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($curl, CURLOPT_TIMEOUT, 10);
	curl_setopt($curl, CURLOPT_HEADER, FALSE);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE);
	$curl_content = curl_exec($curl);
	if($curl_content === false)
	{
		die(curl_errno($curl) . ':' . curl_error($curl));
	}
	curl_close($curl);
	if(strpos($curl_content,'SUCCEED') !== FALSE)
	{
		$_CONFIG['mysql']['connection']->query('INSERT INTO truemoney (card_id,password,user_id,amount,status,added_time) VALUES (NULL,\'' . $password . '\',' . $user_id . ',0,0,NOW())');
		return TRUE;
	}
	else return $curl_content;
}

?>