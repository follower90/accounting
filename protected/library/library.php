<?php
function __autoload($class_name)
{
	$filename = $class_name . '.php';
	if (!include($filename)) {
		return false;
	}
}

function Select_masiv($mas, $name_select, $KEY = 0, $style, $option_text = 'Выберете')
{
	$text = "<select $style class=form_option name=\"$name_select\">";

	if ($option_text <> '') $text .= "<option value='0'>$option_text</option>";

	foreach ($mas as $key_t => $value_t) {
		if ($KEY == $key_t) $sel = 'selected="selected"';
		else $sel = '';
		$text = $text . '<option value="' . $key_t . '" ' . $sel . '>' . $value_t . '</option>';
	}
	$text = $text . '</select>';

	return $text;
}

function Select_masiv_multi($mas, $name_select, $ArKEY = 0, $style, $option_text = 'Выберете')
{

	$text = 'Чтобы выбрать несколько позиций зажмите клавишу CTRL и кликайте машкой ан разделы<br />
			<select name="' . $name_select . '"  ' . $style . ' size="10" multiple="multiple">';
	if ($option_text <> '') $text .= "<option value=0 >$option_text</option>";
	foreach ($mas as $key_t => $value_t) {
		$sel = '';
		if (isset($ArKEY) and is_array($ArKEY) === true and count($ArKEY) > 0) {
			if (in_array($key_t, array_keys($ArKEY))) {
				$sel = 'selected="selected"';
			}
		} else if ($key_t == $ArKEY) {
			$sel = 'selected="selected"';
		}

		$text = $text . '<option value="' . $key_t . '" ' . $sel . ' >' . $value_t . '</option>';
	}

	$text = $text . '</select>';
	return $text;
}

function createTree_cat($array, $currentParent, $KEY, $currLevel = 0, $prevLevel = -1)
{

	$text = '';
	foreach ($array as $categoryId => $category) {
		if ($currentParent == $category['sub']) {
			$sub = $category['sub'];
			$level = $currLevel;

			if ($level == 0) $bull = '&nbsp;';
			elseif ($level == 1) $bull = '&nbsp;&bull;&nbsp;';
			elseif ($level == 2) $bull = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&ordm;&nbsp;';
			elseif ($level == 3) $bull = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-';
			elseif ($level > 3) $bull = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';

			if ($sub == 0) $class = " class = form_ac ";
			else $class = "";

			$select = "";

			if ($categoryId == $KEY) {
				$select = 'selected = "selected"';
			}

			$text .= "<option value=\"$categoryId\" $select $class > $bull" . htmlspecialchars(stripslashes($category['name'])) . "</option>";

			if ($currLevel > $prevLevel) {
				$prevLevel = $currLevel;
			}

			$currLevel++;


			$text .= createTree_cat($array, $categoryId, $KEY, $currLevel, $prevLevel);
			$currLevel--;
		}
	}
	return $text;
}


function arrayKeys($array, $key = 'id')
{
	$array_new = array();
	foreach ($array as $val) {
		$array_new[$val[$key]] = $val;
	}

	return $array_new;
}

function viewPrice($price, $discount = 0)
{
	$return = array();
	$return['old_price'] = '';////Старая цена(str)
	$return['price'] = ''; ////Цена с форматированием(str)
	$return['cur_price'] = 0; ////Цена без форматирования(float)
	$return['base_price'] = 0; ////Цена в базовой валюте(float)

	if ($_SESSION['currency'][1]['base'] == 1) {
		if ($discount != 0) {
			$return['old_price'] = formatPrice($price);
			$price = discount($discount, $price);
		}
		$return['price'] = formatPrice($price);
		$return['cur_price'] = round($price, 2);
		$return['base_price'] = $price;
	} else {
		$return['base_price'] = $price;
		$price = $price * (1 / $_SESSION['currency'][1]['rate']);
		if ($discount != 0) {
			$return['old_price'] = formatPrice($price);
			$price = discount($discount, $price);
			$return['base_price'] = discount($discount, $return['base_price']);
		}

		$return['cur_price'] = round($price, 2);
		$return['price'] = formatPrice($price);
	}

	return $return;
}

function formatPrice($price)
{
	if ($_SESSION['lang'] === "ru") {
		if ($_SESSION['currency'][1]['position'] == 1) $price = number_format($price, 0, ',', ' ') . ' ' . ' <font>' . $_SESSION['currency'][1]['icon'] . '</font>';
		else $price = '<font>' . $_SESSION['currency'][1]['icon'] . '</font> ' . number_format($price, 2, ',', ' ');
		return $price;
	} else {
		if ($_SESSION['currency'][1]['position'] == 1) $price = number_format($price, 0, ',', ' ') . ' ' . ' <font>' . $_SESSION['currency'][1]['icon2'] . '</font>';
		else $price = '<font>' . $_SESSION['currency'][1]['icon2'] . '</font> ' . number_format($price, 2, ',', ' ');
		return $price;
	}
}

function getUri($languages)
{
	$url = sanitize($_SERVER['REQUEST_URI']);
	$value_lang = explode("/", $url);
	if ((isset($value_lang[1]) && ($value_lang[1] != 'ajaxadmin' && $value_lang[1] != 'ajax' && $value_lang[1] != 'admin' && $value_lang[1] != 'js' && $value_lang[1] != 'server' && $value_lang[1] != 'captcha')) || !isset($_SESSION['key_lang'])) {
		$_SESSION['key_lang'] = 'ru';
	}

	if (!isset($value_lang[2]) || (isset($value_lang[2]) && $value_lang[2] != "admin")) {
		foreach ($languages as $row) {
			if (isset($value_lang[1]) && $value_lang[1] == $row['language']) {
				$_SESSION['key_lang'] = $row['language'];
				$_SERVER['REQUEST_URI'] = mb_substr($_SERVER['REQUEST_URI'], 3);
			}
		}
	}

	return $_SESSION['key_lang'];
}

function errorMail($text)
{
	$contact_mail = email_error;
	$url = $_SERVER['REQUEST_URI'];
	$refer = '';
	if (isset($_SERVER['HTTP_REFERER'])) $refer = $_SERVER['HTTP_REFERER'];
	$ip_user = $_SERVER['REMOTE_ADDR'];
	$br_user = $_SERVER['HTTP_USER_AGENT'];

	$header = "From: $contact_mail" . "\r\n" .
		"Reply-To: $contact_mail" . "\r\n" .
		"Return-Path: $contact_mail" . "\r\n" .
		"Content-type: text/plain; charset=UTF-8";

	$subject = 'Отладка ошибок в системе SkyCms:' . $_SERVER['SERVER_NAME'];
	$body = "SERVER_NAME:" . $_SERVER['SERVER_NAME'] . "
				 страница: $url \n
				 REFER страница: $refer \n
				 IP пользователя: $ip_user \n
				 браузер пользователя: $br_user \n
				 -----------------------------------------
				 $text";
	mail($contact_mail, $subject, $body, $header);
}

function translit($string, $flag = false)
{
	$replace = array(
		"'" => "",
		"`" => "",
		"а" => "a", "А" => "a",
		"б" => "b", "Б" => "b",
		"в" => "v", "В" => "v",
		"г" => "g", "Г" => "g",
		"д" => "d", "Д" => "d",
		"е" => "e", "Е" => "e",
		"ж" => "zh", "Ж" => "zh",
		"з" => "z", "З" => "z",
		"и" => "i", "И" => "i",
		"й" => "y", "Й" => "y",
		"к" => "k", "К" => "k",
		"л" => "l", "Л" => "l",
		"м" => "m", "М" => "m",
		"н" => "n", "Н" => "n",
		"о" => "o", "О" => "o",
		"п" => "p", "П" => "p",
		"р" => "r", "Р" => "r",
		"с" => "s", "С" => "s",
		"т" => "t", "Т" => "t",
		"у" => "u", "У" => "u",
		"ф" => "f", "Ф" => "f",
		"х" => "h", "Х" => "h",
		"ц" => "c", "Ц" => "c",
		"ч" => "ch", "Ч" => "ch",
		"ш" => "sh", "Ш" => "sh",
		"щ" => "sch", "Щ" => "sch",
		"ъ" => "", "Ъ" => "",
		"ы" => "y", "Ы" => "y",
		"ь" => "", "Ь" => "",
		"э" => "e", "Э" => "e",
		"ю" => "yu", "Ю" => "yu",
		"я" => "ya", "Я" => "ya",
		"і" => "i", "І" => "i",
		"ї" => "yi", "Ї" => "yi",
		"є" => "e", "Є" => "e",
		"	" => "-", " " => "-"
	);
	$string = iconv("UTF-8", "UTF-8//IGNORE", strtr($string, $replace));
	if (!$flag) $string = preg_replace("/[^a-zA-ZА-Яа-я0-9\s]/", "-", $string);
	else $string = preg_replace("/[^a-zA-ZА-Яа-я0-9\s\/\:]/", "-", $string);
	return mb_strtolower($string);
}

function sanitize($var, $reverse = false)
{
	$sanMethod = array(
		array('&', '&#038;'),
		array('"', '&#034;'),
		array("'", '&#039;'),
		array('%', '&#037;'),
		array('(', '&#040;'),
		array(')', '&#041;'),
		array('+', '&#043;'),
		array('<', '&lt;'),
		array('>', '&gt;')
	);

	if (!is_array($var)) {
		$charsCount = count($sanMethod);
		if ($reverse) for ($j = $charsCount; $j > 0; $j--) $var = str_replace($sanMethod[$j][1], $sanMethod[$j][0], $var);
		else for ($j = 0; $j < $charsCount; $j++) $var = str_replace($sanMethod[$j][0], $sanMethod[$j][1], $var);
		return $var;
	}
	$varCount = count($var);
	$keys = array_keys($var);
	$i = 0;

	while ($i < $varCount) {
		if (is_array($var[$keys[$i]])) return sanitize($var[$keys[$i]]);
		else {
			$charsCount = count($sanMethod);
			if ($reverse) for ($j = $charsCount; $j > 0; $j--) $var = str_replace($sanMethod[$j][1], $sanMethod[$j][0], $var);
			else for ($j = 0; $j < $charsCount; $j++) $var[$keys[$i]] = str_replace($sanMethod[$j][0], $sanMethod[$j][1], $var[$keys[$i]]);
		}
		$i++;
	}

	return $var;
}

function post_write($err, $header = false)
{
	$_POST['err'] = $err;
	$_SESSION['_POST'] = $_POST;
	$_POST = array();
	if ($header) {
		header("location:$header");
		exit();
	}
}

function send_mime_mail($name_from, $email_from,  $name_to, $email_to, $data_charset, $send_charset, $subject, $body)
{
	$email_to = str_replace("&#044;", ",", $email_to);
	$email_cnt = explode(",", $email_to);
	$email_to = "";
	for ($i = 0; $i <= count($email_cnt) - 1; $i++) {
		if ($i != 0) $email_to .= ", ";
		$email_to .= "< {$email_cnt[$i]} >";//echo $email_cnt[$i]."<br />";
	}

	$to = mime_header_encode($name_to, $data_charset, $send_charset) . $email_to;
	$subject = mime_header_encode($subject, $data_charset, $send_charset);
	$from = mime_header_encode($name_from, $data_charset, $send_charset)
		. ' <' . $email_from . '>';
	if ($data_charset != $send_charset) {
		$body = iconv($data_charset, $send_charset, $body);
	}

	$headers = "From: $from \r\n";
	$headers .= "Reply-To: $from \r\n";
	$headers .= "Content-type: text/html; charset=$send_charset \r\n";

	return mail($to, $subject, $body, $headers, "-f info@" . $_SERVER['HTTP_HOST']);
}

function mime_header_encode($str, $data_charset, $send_charset)
{
	if ($data_charset != $send_charset) {
		$str = iconv($data_charset, $send_charset, $str);
	}
	return '=?' . $send_charset . '?B?' . base64_encode($str) . '?=';
}

function genPassword($size = 8)
{
	$a = array('e', 'y', 'u', 'i', 'o', 'a');
	$b = array('q', 'w', 'r', 't', 'p', 's', 'd', 'f', 'g', 'h', 'j', 'k', 'l', 'z', 'x', 'c', 'v', 'b', 'n', 'm');
	$c = array('1', '2', '3', '4', '5', '6', '7', '8', '9', '0');
	$e = array('-');
	$password = $b[array_rand($b)];

	do {
		$lastChar = $password[strlen($password) - 1];
		@$predLastChar = $password[strlen($password) - 2];
		if (in_array($lastChar, $b)) {
			if (in_array($predLastChar, $a)) {
				$r = rand(0, 2);
				if ($r) $password .= $a[array_rand($a)];
				else $password .= $b[array_rand($b)];
			} else $password .= $a[array_rand($a)];

		} elseif (!in_array($lastChar, $c) AND !in_array($lastChar, $e)) {
			$r = rand(0, 2);
			if ($r == 2) $password .= $b[array_rand($b)];
			elseif (($r == 1)) $password .= $e[array_rand($e)];
			else $password .= $c[array_rand($c)];
		} else {
			$password .= $b[array_rand($b)];
		}

	} while (($len = strlen($password)) < $size);

	return $password;
}

function removeDir($dir)
{
	if ($objs = glob($dir . "/*")) {
		foreach ($objs as $obj) {
			is_dir($obj) ? removeDir($obj) : unlink($obj);
		}
	}
	if (is_dir($dir)) rmdir($dir);
}
