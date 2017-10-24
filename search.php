<?php  
header('Content-Type: text/javascript; charset=utf8');
header('Pragma: no-cache');
header('Cache-Control: no-cache,must-revalidate');

$dataFinder = file('json/finder.dat');
$term = $_GET['term'];
$matches = array();
$i = 0;
$cantidad = 10;
foreach($dataFinder as $data) {
	$segments = preg_split('/\t/', trim($data));
	if(preg_match('/' . preg_quote($term) . '/i', str_replace(array("á","é","í","ó","ú","ñ"),array("a","e","i","o","u","n"),$segments[1])) || preg_match('/' . preg_quote($term) . '/i', $segments[1])) {
	{
		$matches[] = array('label' => $segments[1]);
		$i++;
	}
	if ($i==$cantidad)
		break;
	
  }
}

print json_encode($matches);


?>