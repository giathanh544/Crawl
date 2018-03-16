<?php

error_reporting(E_ERROR);
set_time_limit(0);	

$r = download_file('http://phukiensinhnhatchobe.com/uploads/images/vi/sanpham/bo-phu-kien-tuoi-hoi.jpg','data/heocon.jpg');

if(empty($r)){
	echo "download success";
}else{
	echo "download false";
}

function download_file($url , $path){
	$f = fopen($path, 'w');

	$ch = curl_init($url);

	curl_setopt($ch, CURLOPT_FILE, $f);
	curl_setopt($ch, CURLOPT_TIMEOUT, 3600);

	curl_exec($ch);
	//curl_error có 2 trạng thái nếu success trả về rỗng, còn false thì trả về ....
	$e = curl_error($ch);
	curl_close($ch);

	fclose($f);

	return $e;
}