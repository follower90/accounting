<?php

class Log
{
	public static function echoLog ($logText)
	{
		//echo '<pre><font color="red">' . $logText . '</font></pre>';
		echo $logText;
		errorMail($logText);
	}

	public static function writeLog ($logText,$logType) {
		$body = $logType.' \n '.$logText;
		errorMail($body);
		echo $body;
		//echo '<font color="red">' . $logType . '<br>';		echo $logText . '</font>';
	}
}
