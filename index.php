<?php
	

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>Dealer Api</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="shortcut icon" href="https://apidocs.marketcheck.com//favicon.ico">
	<link rel="stylesheet" type="text/css" href="./main.css">
</head>
<body>
<div class="container">
	<span class="validation">please input valid source</span>
	<section>
		<form class="form-inline">
			<div class="form-group">
				<label for="source_site">Source Site:</label>
				<input type="text" class="form-control" placeholder="Input Source Site" id="source_site">
			</div>
			<button type="submit" class="btn btn-default">Request</button>
		</form>
	</section>
	<span class="waiting">Please wait while response will arrive...</span>
	<span class="no_result">Nothing for the dealer id. Please enter another dealer again.</span>
	<span class="count_result"></span>
	<section>
		<div class="card_group">
	</section>
</div>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			var d = new Date();
			var n = (d.getTime() - (10 * 24 * 60 * 60 *1000)) / 1000;

			const form = $('form.form-inline');
			form.on('submit', function(e){
				e.preventDefault();
				var source_site = $('#source_site').val();
				$("span.no_result").css('display', 'none');
				$("span.count_result").empty();
				$(".card_group").empty();
				if (!isDomain(source_site)) {
					$("span.validation").css('display', 'flex');
					return;
				} else {
					$("span.validation").css('display', 'none');
					$("span.waiting").css('display', 'flex');
					$.ajax({
						url: "api_request.php?source_site=" + source_site,
						success: function(response) {
							$("span.waiting").css('display', 'none');
							const json_data = JSON.parse(response);
							const total_count = json_data.length;
							if (total_count > 0) {
								$("span.count_result").css('display', 'flex');
								$("span.count_result").append("Total Count: " + total_count);
								json_data.forEach((item, index) => {
									const time_count = item['last_seen_at'];
									let stock = inventory_type = last_seen_at_date = media = photo = price = build = year = make = model = heading = link = color = '';
									if (time_count > n || 1=1) {
										if (item.hasOwnProperty('stock_no')) {
											stock = item['stock_no'];
										} else {
											stock = ''
										}
										if (item.hasOwnProperty('inventory_type')) {
											inventory_type = item['inventory_type'];
										} else {
											inventory_type = ''
										}
										if (item.hasOwnProperty('last_seen_at_date')) {
											if (item['last_seen_at_date'].length > 10) {
												last_seen_date = item['last_seen_at_date'].substring(0, 10);
											} else {
												last_seen_date = item['last_seen_at_date'];
											}
										} else {
											last_seen_date = ''
										}
										if (item.hasOwnProperty('price')) {
											price = '$' + item['price'];
										} else {
											price = 'Not Sure';
										}
										if (item.hasOwnProperty('vdp_url')) {
											link = item['vdp_url'];
										} else {
											link = ''
										}
										if (item.hasOwnProperty('exterior_color')) {
											color = item['exterior_color'];
										} else {
											color = '';
										}
										if (item.hasOwnProperty('media')) {
											media = item['media'];
											if (media.hasOwnProperty('photo_links')) {
												photo = media['photo_links'][0];
											} else {
												photo = '';
											}
										} else {
											photo = ''
										}
										if (item.hasOwnProperty('build')) {
											build = item['build'];
											if (build.hasOwnProperty('year')) {
												year = build['year'];
											} else {
												year = ''
											}
											if (build.hasOwnProperty('make')) {
												make = build['make'];
											} else {
												make = ''
											}
											if (build.hasOwnProperty('model')) {
												model = build['model'];
											} else {
												model = '';
											}
											heading = year + ' ' + make + ' ' + model;
										} else {
											heading = 'NOT SURE';
										}
										$(".card_group").append(
										"<div class='one_card'><div class='OEM'>" + last_seen_date + "</div><table><tr><td>Stock Number:</td><td>" + stock + "</td></tr><tr><td>OEM:</td><td>" + heading + "</td></tr><tr><td>Inventory Type:</td><td>" + inventory_type + "</td></tr><tr><td>Color:</td><td>" + color + "</td></tr><tr><td>Price:</td><td>" + price + "</td></tr></table></div>"
										);
									}
								});
							} else {
								$("span.no_result").css('display', 'flex');
							}
						},
						error: function(error) {
							alert('Occured error in api request. Please retry!')
						}
					});
				}
			})
		});

		function isDomain(value) {
			return /^(?:[-A-Za-z0-9]+\.)+[A-Za-z]{2,6}$/.test(value);
		}
	</script>
</body>
</html>