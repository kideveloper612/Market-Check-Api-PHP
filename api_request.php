<?php
	
	require('./vendor/autoload.php');

	$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
	$dotenv->load();

	$api_key = $_ENV['API_KEY'];

	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL, "http://api.marketcheck.com/v2/search/car/recents?api_key=&dealer_id=1000979&sold=true");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");


	$headers = array();
	$headers[] = "Accept: application/json";
	$headers[] = "Authorization: Bearer APIKEY";
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

	$result = curl_exec($ch);
	if (curl_errno($ch)) {
	    echo 'Error:' . curl_error($ch);
	}
	curl_close ($ch);
	echo $result;

?>