<?php
function failJsonReturn() {
	$jsonStr = '{"result":"fail","code":1}';
	$crc32 = crc32($jsonStr) + 42;
	$jsonStr = '{"return":' . $jsonStr . ',"crc32":"' . $crc32 . '"}';
	header("Content-tye: application/json");
	echo $jsonStr;
}

function failWitchCodeJsonReturn($code) {
	$jsonStr = '{"result":"fail","code":' . $code . '}';
	$crc32 = crc32($jsonStr) + 42;
	$jsonStr = '{"return":' . $jsonStr . ',"crc32":"' . $crc32 . '"}';
	header("Content-tye: application/json");
	echo $jsonStr;
}

function winJsonReturn() {
	$jsonStr = '{"result":"win"}';
	$crc32 = crc32($jsonStr) + 42;
	$jsonStr = '{"return":' . $jsonStr . ',"crc32":"' . $crc32 . '"}';
	header("Content-tye: application/json");
	echo $jsonStr;
}

function strJsonReturn($str) {
	$crc32 = crc32($str) + 42;
	$jsonStr = '{"return":' . $str . ',"crc32":"' . $crc32 . '"}';
	header("Content-tye: application/json");
	echo $jsonStr;
}
?>
