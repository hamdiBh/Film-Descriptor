<?php

 function multiRequest($data,$options= array())
{
	# code...
	$curly  = array();
	$result = array();

	$mh = curl_multi_init();

	foreach ($data as $id => $d) {
		# code...
		

		$temp = curl_init();
		$url= (is_array($d) && !empty($d['url']) ? $d['url'] : $d);
		curl_setopt($temp, CURLOPT_URL,$url);
		curl_setopt($temp,CURLOPT_HEADER, 0);
		curl_setopt($temp,CURLOPT_RETURNTRANSFER,1);



		$curly[] = $temp;
		curl_multi_add_handle($mh, $temp);

	}

	$running = null;
	do{
		curl_multi_exec($mh, $running);
	}while ($running>0) ;

	foreach ($curly as $id => $c) {
		# code...
		
		$pos = json_decode(curl_multi_getcontent($c),true);

		$array1["Title"]=$pos[Title];
		$array1["Poster"]=$pos[Poster];
		$array1["Year"]=$pos[Year];
		$array1["imdbID"]=$pos[imdbID];
		$result[] = $array1;

		curl_multi_remove_handle($mh, $c);
	}

	curl_multi_close($mh);

	return $result;
}



?>  	



  	<?php
if(isset($_GET["s"])&& isset($_GET["y"])&& isset($_GET["type"])){
	
	$t=$_GET["s"];
	
	$y=$_GET["y"];
	$ty=$_GET["type"];
	$url = str_replace(" ", "%20", 'http://www.omdbapi.com/?type='.$ty.'&s='.$t.'&y='.$y.'&r=json');
	
	

}else{
	echo  " Error" ;
}
	

$json = file_get_contents($url);
$obj = json_decode($json,true);

foreach($obj[Search] as $p)
{
	$data[] = 'http://www.omdbapi.com/?i='.$p[imdbID];
/*echo '

Title: '.$p[Title] .'Poster : ';*/
//$json1 = file_get_contents('http://www.omdbapi.com/?i='.$p[imdbID]);


/*$pos = json_decode($json1,true);

$tep = $pos[Year];



$array1["Title"]=$p[Title];
$array1["Poster"]=$pos[Poster];

$array1["Year"]=$tep;
$array1["imdbID"]=$pos[imdbID];

$array[]  = ($array1);*/

}
$r = multiRequest($data);



  	header('Content-Type: application/json; charset=utf-8');
	echo json_encode(array('Search'=>$r));
	?> 	
  
