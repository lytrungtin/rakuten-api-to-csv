<?php
// Get our helper functions
require_once("inc/functions.php");


function get_http_response_code($url) {
    $headers = get_headers($url);
    return substr($headers[0], 9, 3);
}

$csv_data ='hotelNo,hotelName,hotelInformationUrl,planListUrl,dpPlanListUrl,reviewUrl,hotelKanaName,hotelSpecial,hotelMinCharge,latitude,longitude,postalCode,address1,address2,telephoneNo,faxNo,access,parkingInformation,nearestStation,hotelImageUrl,hotelThumbnailUrl,roomImageUrl,roomThumbnailUrl,hotelMapImageUrl,reviewCount,reviewAverage,userReview'."\r\n";

foreach(range('A','J') as $v){
	for ($i = 1; $i <= 100; $i++) {
		$url = 'https://app.rakuten.co.jp/services/api/Travel/SimpleHotelSearch/20170426?format=json&largeClassCode=japan&middleClassCode=tokyo&smallClassCode=tokyo&detailClassCode='.$v.'&applicationId=APP ID&page='.$i;

		if(get_http_response_code($url) != "200"){
		    break;
		}else{
			$jsonData = file_get_contents($url);
		}
		
		$products = json_decode($jsonData, true);
		$hotels = $products['hotels'];

		foreach ($hotels as $key_hotel => $hotel) {
			$hotel_detail = $hotel['hotel'][0]['hotelBasicInfo'];
			$hotel_detail = array(
			    'hotelNo' => $hotel_detail['hotelNo'],
			    'hotelName' => $hotel_detail['hotelName'],
			    'hotelInformationUrl' => $hotel_detail['hotelInformationUrl'],
			    'planListUrl' => $hotel_detail['planListUrl'],
			    'dpPlanListUrl' => $hotel_detail['dpPlanListUrl'],
			    'reviewUrl' => $hotel_detail['reviewUrl'],
			    'hotelKanaName' => $hotel_detail['hotelKanaName'],
			    'hotelSpecial' => $hotel_detail['hotelSpecial'],
			    'hotelMinCharge' => $hotel_detail['hotelMinCharge'],
			    'latitude' => $hotel_detail['latitude'],
			    'longitude' => $hotel_detail['longitude'],
			    'postalCode' => $hotel_detail['postalCode'],
			    'address1' => $hotel_detail['address1'],
			    'address2' => $hotel_detail['address2'],
			    'telephoneNo' => $hotel_detail['telephoneNo'],
			    'faxNo' => $hotel_detail['faxNo'],
			    'access' => $hotel_detail['access'],
			    'parkingInformation' => $hotel_detail['parkingInformation'],
			    'nearestStation' => $hotel_detail['nearestStation'],
			    'hotelImageUrl' => $hotel_detail['hotelImageUrl'],
			    'hotelThumbnailUrl' => $hotel_detail['hotelThumbnailUrl'],
			    'roomImageUrl' => $hotel_detail['roomImageUrl'],
			    'roomThumbnailUrl' => $hotel_detail['roomThumbnailUrl'],
			    'hotelMapImageUrl' => $hotel_detail['hotelMapImageUrl'],
			    'reviewCount' => $hotel_detail['reviewCount'],
			    'reviewAverage' => $hotel_detail['reviewAverage'],
			    'userReview' => $hotel_detail['userReview']
			);
			$csv_data .= arrayToCsv($hotel_detail)."\r\n";
		}
	}
}
$length = strlen($csv_data);
header('Content-type: text/csv;');
header('Content-Disposition: attachment; filename=rakuten_hotel_simple.csv');
header("Content-length: $length");
header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT', true, 200);
echo str_replace(" ", " ", $csv_data);exit;