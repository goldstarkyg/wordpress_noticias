<?php
	error_reporting(E_ERROR | E_PARSE);
	


	function getSslPage($url) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_REFERER, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		$result = curl_exec($ch);
		curl_close($ch);
		return $result;
	}



	if (!isset($_GET['youtubeID']) || !isset($_GET['cur_i'])) {
				//nothing
				die();
	} else {
	
		$url='https://www.googleapis.com/youtube/v3/videos?key=AIzaSyAyYEonPlSe24OpkiwM6WsZhwpQSAC3vCM&part=snippet&id='.$_GET['youtubeID'];
		$rs= getSslPage($url);
		if (!$rs) {
			echo "NO DATA!!!!";	
		} else {
			$data = json_decode($rs, TRUE);
			//var_dump($json->items[0]->snippet->thumbnails);
			//echo $json->items[0]->snippet->thumbnails;
			//print_r ($data);
			//echo strip_tags($data['items'][0]['snippet']['title']);
			
			
			echo $_GET['cur_i'].'#----#'.strip_tags($data['items'][0]['snippet']['thumbnails']['medium']['url']).'#----#'.strip_tags($data['items'][0]['snippet']['title']).'#----#'.strip_tags($data['items'][0]['snippet']['description']);
		}
	}
?>
