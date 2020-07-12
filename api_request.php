<?php
	
	require('./vendor/autoload.php');

	$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
	$dotenv->load();

	$api_key = $_ENV['API_KEY'];

	echo "string";
	echo $api_key;
	return;

	function curl_request($dealer_id, $start) {
		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => "http://api.marketcheck.com/v2/search/car/active?api_key=".$GLOBALS['api_key']."&dealer_id=".$dealer_id."&start=".$start."&rows=50&sold=true",
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

	if (isset($_GET['dealer_id'])) {

		$dealer_id = $_GET['dealer_id'];
		$start = -50;

		$flag = true;
		$result = array();

		while ($flag) {
			$start += 50;
			$curl_res = curl_request($dealer_id, $start);
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