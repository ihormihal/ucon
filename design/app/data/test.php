<?php
header('Content-Type: application/json');

$names = json_decode(file_get_contents('http://country.io/names.json'),true);
$continent = json_decode(file_get_contents('http://country.io/continent.json'),true);
$iso3 = json_decode(file_get_contents('http://country.io/iso3.json'),true);
$capital = json_decode(file_get_contents('http://country.io/capital.json'),true);
$phone = json_decode(file_get_contents('http://country.io/phone.json'),true);
$currency = json_decode(file_get_contents('http://country.io/currency.json'),true);

$all = array();
$index = 0;
foreach ($names as $code => $name) {
	$all[] = array(
		'id' => $index,
		'name' => $names[$code],
		'code' => $code,
		'iso_code' => $iso3[$code],
		'continent' => $continent[$code],
		'capital' => $capital[$code],
		'phone' => $phone[$code],
		'currency' => $currency[$code],
		'active' => true
	);
	$index++;
}
echo json_encode($all);
?>