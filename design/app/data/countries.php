<?php
header('Content-Type: application/json');
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

$countries = json_decode(file_get_contents('countries.json'),true);

$sortdir = 'ASC';
$sortby = 'name';
$search = array();
$onpage = 10;
$total = 0;
$page = 1;

if(!empty($_GET['sortby'])){
	$sortby = $_GET['sortby'];
}
if(!empty($_GET['sortdir'])){
	$sortdir = $_GET['sortdir'];
}

//not used
if(!empty($_GET['s'])){
	$s = json_decode($_GET['s'],true);
}
//json array
if(!empty($_GET['search'])){
	$search = json_decode($_GET['search'],true);
}

if(!empty($_GET['onpage'])){
	$onpage = (int)$_GET['onpage'];
}
if(!empty($_GET['page'])){
	$page = (int)$_GET['page'];
}

// Получение столбца для сортировки
$collumn = array();
foreach ($countries as $key => $row) {
	$collumn[$key] = $row[$sortby];
}

if($sortdir == 'DESC'){
	array_multisort($collumn, SORT_DESC, $countries);
}else{
	array_multisort($collumn, SORT_ASC, $countries);
}

if(count($search) > 0){
	$tmp_array = array();
	foreach ($countries as $country) {
		$find = true;
		foreach ($search as $property => $value) {
			if($value && stripos($country[$property], $value) === false){
				$find = false;
			}
		}
		if($find){
			$tmp_array[] = $country;
		}
	}
	$countries = $tmp_array;
}


echo json_encode(array(
	'total' => count($countries),
	'pages' => ceil(count($countries)/$onpage),
	'page' => $page,
	'rows' => array_slice($countries, ($page-1)*$onpage, $onpage),
));
?>