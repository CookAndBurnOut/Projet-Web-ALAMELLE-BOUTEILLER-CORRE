var request = new XMLHttpRequest();
function test(){
	request.open('POST', 'test.php', true);
	request.send();
}

request.onload = function (){
}