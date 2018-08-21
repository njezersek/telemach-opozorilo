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

//
// login request
$header = curlGet('POST', 'https://moj.telemach.si/prijava/preverba', '', 'username='.$username.'&password='.$password)['header'];

// header od login requesta
/*echo "<pre>";
echo $header;
echo "<hr>";*/

// iz headerja sparasaj piškoteke
preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $header, $matches);
$cookies = array();
foreach($matches[1] as $item) {
		parse_str($item, $cookie);
		$cookies = array_merge($cookies, $cookie);
}

// prikaz pridobljenih piškotkov
/*var_dump($cookies);
echo "</pre><hr>";*/

// dobi html od nadzorne plošče potrala
$body = curlGet('GET', 'https://moj.telemach.si/', 'Cookie: JSESSIONID='.$cookies['JSESSIONID'], '')['body'];

// prikaz pridobljenga bodya
/*echo $body;*/

$doc = new DOMDocument();
libxml_use_internal_errors(true);
$doc->loadHTML($body);
$xpath = new DOMXPath($doc);
$dataList = $xpath->query("//*[contains(@class, 'data-list')]");
$preostaleMinute = $xpath->query("descendant::*[contains(@class, 'u-pull-left')]", $dataList->item(0))->item(0)->textContent;

// prikaz preostalih minut
echo $preostaleMinute;
?>
