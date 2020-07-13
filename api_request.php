<?php
	
	require('./vendor/autoload.php');

	$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
	$dotenv->load();

	$api_key = $_ENV['API_KEY'];

	function curl_request($source_site, $start) {
		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => "http://api.marketcheck.com/v2/search/car/active?api_key=".$GLOBALS['api_key']."&sold=true&rows=50&source=".$source_site."&start=".$start,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "GET",
			CURLOPT_HTTPHEADER => array(
				"Host: marketcheck-prod.apigee.net", "Content-Type: application/json"
			),
		));

		$response = curl_exec($curl);

		curl_close($curl);
		return $response;
	}

	if (isset($_GET['source_site'])) {

		$source_site = $_GET['source_site'];
		$start = -50;

		$flag = true;
		$result = array();

		while ($flag) {
			$start += 50;
			$curl_res = curl_request($source_site, $start);
			$json_data = json_decode($curl_res);

			if (isset($json_data -> listings)) {
				if (empty($json_data -> listings)) {
					$flag = false;
				}
				foreach ($json_data -> listings as $value) {
					array_push($result, $value);
				}
			} else {
				$flag = false;
			}
		}

		echo json_encode($result);
	}

?>