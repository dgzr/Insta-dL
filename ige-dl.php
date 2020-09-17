<?php
/*
A simple instagram public videos & image downloader In php
Datez ft Ezz - Kun © (2020)
date : (2020-09-15 16:30:48)
*/

function SaveFile($binary,$name) {
	$dir = "./result/";
	if (is_dir($dir) === false) {
		mkdir($dir);
	}
	$were = fopen($dir.'/'.$name,'wb');
	fwrite($were,$binary);
	fclose($were);
	echo " [*] saving -> $name \n";
}

function find_media_url($page) {
	preg_match_all(
			'#"video_url"\:\"(.*?)"#',$page,$pico
			);
	preg_match_all(
		'#"display_url"\:\"(.*?)"#',$page,$piko
		);
	$all = array_merge($pico[1],$piko[1]);
	$total = count($all);
	for ($i=0; $i < $total; $i++) {
		preg_match(
			"#\/(\d.*?\_\d.+\.mp4)#",$all[$i],$name
			);
		if (count($name) == 0){
			preg_match(
				"#\/(\d.*?\_\d.+\.jpg)#",$all[$i],$name
				);
		}
		SaveFile(
			HttpPage(unescapeUTF8EscapeSeq($all[$i])),
			$name[1]
			);
	}
	echo " [*] all done, $total media saved in ``result`` \n\n";
}

function HttpPage($url) {
	$http = curl_init();
	curl_setopt($http, CURLOPT_URL, $url);
	curl_setopt($http, CURLOPT_RETURNTRANSFER, true);
	$hasil = curl_exec($http);
	curl_close($http);
	if ($hasil == ""){
		echo " [*] no internet connection or url is invalid!\n\n";
	}else{
		return $hasil;
	}
}

function unescapeUTF8EscapeSeq($str){
	return preg_replace_callback(
		"/\\\u([0-9a-f]{4})/i",
		function ($matches) {
			return html_entity_decode('&#x' . $matches[1] . ';', ENT_QUOTES, 'UTF-8');
		},
		$str
	);
}

function Main($names){
	echo "\n [*] Wait a few seconds.... \n";
	if (strpos($names,'https://') !== false) {
		find_media_url(HttpPage($names));
	}else{
		$swc = fopen($names,'r');
		$dox = fread($swc,filesize($names));
		fclose($swc);
		$navi = explode("\n",$dox);
		for ($i=0; $i < count($navi); $i++){
			if (!strlen($navi[$i]) == 0){
				find_media_url(HttpPage($navi[$i]));
			}
		}
	}
}

if ($argc < 2){
	die(" Usage : ige.php [ url ] / [ file ]\n");
}else{
	Main($argv[1]);
}
?>
