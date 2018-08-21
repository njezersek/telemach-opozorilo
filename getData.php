<?php

require "userInfo.php";

/* userInfo.php
$username = "**********";
$password = "**********";
*/

function curlGet($method, $destination, $headers, $parameters){
	$curl = curl_init();
	curl_setopt_array($curl, array(
		CURLOPT_URL => $destination,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => "",
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 30,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => $method,
		CURLOPT_POSTFIELDS => $parameters,      # PODATKI O UPORABNIKU
		CURLOPT_COOKIE => "",
		CURLOPT_HTTPHEADER => explode("\n", $headers),
		CURLOPT_HEADER => 1,
		CURLOPT_SSL_VERIFYHOST => 0,
		CURLOPT_SSL_VERIFYPEER => 0
	));

	$response = curl_exec($curl);
	$err = curl_error($curl);

	$header = "";
	$body = "";

	if ($err) {
		die("cURL Error #:" . $err);
	} else {
		$header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
		$header = substr($response, 0, $header_size);
		$body = substr($response, $header_size);
	}
	curl_close($curl);
	return array('header' => $header, 'body' => $body);
}



$header = curlGet('POST', 'https://moj.telemach.si/prijava/preverba', '', 'username='.$username.'&password='.$password)['header'];

echo "<pre>";
echo $header;
echo "<hr>";

preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $header, $matches);
$cookies = array();
foreach($matches[1] as $item) {
		parse_str($item, $cookie);
		$cookies = array_merge($cookies, $cookie);
}
var_dump($cookies);
echo "</pre>";
?>
