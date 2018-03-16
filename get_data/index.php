<?php

error_reporting(E_ERROR);
	//Khi nào thực hiện xong cái code này mới thôi
set_time_limit(0);

$url = "https://mp3.zing.vn/";

	$proxy = trim(file_get_contents('proxy.txt'));

	echo get_data($url,$proxy);

function get_data($link, $proxy = null , $proxy_type = null){

	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL, $link);
	
		//1 là true, 0 là false ảnh hưởng tới $result, với 1 true thì echo $result hiển thị web dưới dạng....còn 0 thì dạng html
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		//Đối với 1 số trang web cần khai báo user agent, nếu không khai báo thì không crawl được
	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/64.0.3282.186 Safari/537.36');

		//Fake mình là 1 trang web khác crawl tới không phải là localhost
	curl_setopt($ch, CURLOPT_REFERER, 'https://www.google.com.vn/');

		//để trống value của ENCODING để khi web quét thì tất cả các ENCODING chấp nhận hết
	curl_setopt($ch, CURLOPT_ENCODING, '');

		//TIMEOUT là thời gian xử lí nếu quá 10s không được thì out
	curl_setopt($ch, CURLOPT_TIMEOUT, 10);

		//CONNECTTIMEOUT là thời gian kết nối
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);

		//Khi vào website thì data sẽ chuyển qua nhiều website rồi mới tới mình, nên để true là chấp nhận
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		//Số website tối đa cho chuyển qua, nếu quá 5 website chưa trả về data -> hủy
	curl_setopt($ch, CURLOPT_MAXREDIRS, 5);

	//Tạo 1 mảng cho header để duyệt header cho 1 số trang web khó 
	$headers = array();
	$headers[] = 'Accept:text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8';
	$headers[] = 'Accept-Encoding:gzip, deflate, br';
	$headers[] = 'Accept-Language:vi-VN,vi;q=0.9,fr-FR;q=0.8,fr;q=0.7,en-US;q=0.6,en;q=0.5';
	$headers[] = 'Connection:keep-alive';
	$headers[] = 'Cookie:_ga=GA1.2.1254996681.1519872756; __gads=ID=4cae7f2b641780ea:T=1519872767:S=ALNI_MZZlk2c1Psz-YpUlDvz-858xy1ZAg; _znu=1; __zi=2000.59f4ed0178089356ca19.1519872765778.7f0d664d; crtg_vng_rta=; fuid=a142251a190023945dc031d3e70503f4; adtimaUserId=2000.59f4ed0178089356ca19.1519872765778.7f0d664d; __mp3sessid=1FAE3277847D; _gid=GA1.2.1617787797.1521117912; __zi_local=2000.59f4ed0178089356ca19.1519872765778.7f0d664d; __acid=68a854ca990a2a8c616609188c5d30d8.449351519203788884.1522649041486.1497b64dc2c622987bd7.2653217678; _zmp3=0.6557059573277122; atmpv=4';
	$header[] = 'Host:mp3.zing.vn';
	$header[] = 'Upgrade-Insecure-Requests:1';
	$header[] = 'User-Agent:Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/64.0.3282.186 Safari/537.36';

	// curl_setopt($ch, CURLOPT_HEADER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

	if (isset($proxy) && check_proxy_live($proxy)) {
		//proxy
		curl_setopt($ch, CURLOPT_PROXY, $proxy);

		if (isset($proxy_type))
			curl_setopt($ch, CURLOPT_PROXYTYPE, $proxy_type);
	}

	$data = curl_exec($ch);

	curl_close($ch);

	return $data;
}

//Hàm check proxy còn sống không
function check_proxy_live($proxy){
	$waitTimeoutInSeconds = 10;

	$proxy_split = explode(':', $proxy);

	$ip = $proxy_split[0];
	$port = $proxy_split[1];

	$result = false;

	if ($fp = fsockopen($ip,$port,$erCode,$errStr,$waitTimeoutInSeconds)){
		$result = true;
		fclose($fp);
	}

	return $result;
}
