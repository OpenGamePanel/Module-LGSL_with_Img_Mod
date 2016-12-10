<?php
function get_true_boolean($bool)
{
	if ( (int) $bool > 0 )
		$ret = true;
	else
	{
		$lowered_bool = strtolower($bool); // that could be 'True' or 'true' or 'TRUE', etc...
		if( $lowered_bool === "true" || $lowered_bool === "on" || $lowered_bool === "yes" )
			$ret = true;
		else
			$ret = false;
	}
	return $ret;
}

$request_string = "";

foreach($_GET as $key => $val)
{
	if($key == "type") $key = "lgsl_type";
	$request_string .= "&$key=$val";
}

$scheme = ( isset($_SERVER['HTTPS']) and get_true_boolean($_SERVER['HTTPS']) ) ? "https://" : "http://";
$index_link = $scheme . implode('/', (explode('/', $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'], -4))) . "/index.php";
$lgsl_feed_error = 0;
if (function_exists('curl_init') && function_exists('curl_setopt') && function_exists('curl_exec'))
{
	$lgsl_curl = curl_init();

	curl_setopt($lgsl_curl, CURLOPT_HEADER, 0);
	curl_setopt($lgsl_curl, CURLOPT_HTTPGET, 1);
	curl_setopt($lgsl_curl, CURLOPT_TIMEOUT, 6);
	curl_setopt($lgsl_curl, CURLOPT_ENCODING, "");
	curl_setopt($lgsl_curl, CURLOPT_FORBID_REUSE, 1);
	curl_setopt($lgsl_curl, CURLOPT_FRESH_CONNECT, 1);
	curl_setopt($lgsl_curl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($lgsl_curl, CURLOPT_CONNECTTIMEOUT, 6);
	if($scheme == "https://")
	{
		curl_setopt($lgsl_curl, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($lgsl_curl, CURLOPT_SSL_VERIFYHOST, FALSE);
	}
	curl_setopt($lgsl_curl, CURLOPT_URL, "$index_link?m=lgsl&p=feed&type=cleared$request_string");
	
	$http_reply = curl_exec($lgsl_curl);

	if (curl_error($lgsl_curl))
	{
		$lgsl_feed_error = 1;
	}

	curl_close($lgsl_curl);
}
elseif (function_exists('fsockopen'))
{
	$ssl = $scheme == "https://" ? "ssl://" : "";
	$lgsl_fp = @fsockopen($ssl.$_SERVER['SERVER_ADDR'], $_SERVER['SERVER_PORT'], $errno, $errstr, 6);

	if (!$lgsl_fp)
	{
		$lgsl_feed_error = 1;
	}
	else
	{
		stream_set_timeout($lgsl_fp, 6, 0);
		stream_set_blocking($lgsl_fp, TRUE);
		$host = parse_url("$index_link?m=lgsl&p=feed&type=cleared$request_string");
		$http_send  = "GET {$host['path']}?{$host['query']} HTTP/1.0\r\n";
		$http_send .= "Host: {$host['host']}\r\n";
		$http_send .= "Port: {$host['port']}\r\n";
		$http_send .= "Pragma: no-cache\r\n";
		$http_send .= "Cache-Control: max-age=0\r\n";
		$http_send .= "Accept-Encoding: \r\n";
		$http_send .= "Accept-Language: en-us,en;q=0.5\r\n";
		$http_send .= "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7\r\n";
		$http_send .= "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8\r\n";
		$http_send .= "User-Agent: Mozilla/5.0 (X11; U; Linux i686; en-GB; rv:1.9.1.4) Gecko/20091028\r\n";
		$http_send .= "Connection: Close\r\n\r\n";

		fwrite($lgsl_fp, $http_send);

		$http_reply = "";

		while (!feof($lgsl_fp))
		{
			$http_chunk = fread($lgsl_fp, 4096);
			if ($http_chunk === "") { break; }
			$http_reply .= $http_chunk;
		}

		@fclose($lgsl_fp);
		$http_reply = substr($http_reply, strpos($http_reply,"\r\n\r\n")+4);
	}
}
else
{
	echo "LGSL FEED PROBLEM: NO CURL OR FSOCKOPEN SUPPORT"; return;
}
if (!$lgsl_feed_error)
{
	echo trim($http_reply);
}
?>