/*
	    |\___/|
	    )     (
	   =\     /=
	     )===(      ___________.__
	    /     \     \__    ___/|  |__   ____  ____
	    |     |  	    |   |   |  |  \_/ __ \/  _ \
	   /       \	   |   |   |   Y  \  ___(  <_> )
	   \       /	  |____|   |___|  /\___  >____/
	    \_____/ 	                \/     \/
*/

oublieMdp = new XMLHttpRequest();
onRequest = false;

function recupMdp(){
  if(!onRequest){
    onRequest = true;
    var email = document.getElementById('email').value;
    if(email != ''){
      oublieMdp.open('post', '../../serveur/PHP/action.php', true);
      oublieMdp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
      oublieMdp.send("json="+createJsonForRequest('{"email":"' + email + '"}', 'oublieMdp'));
    }
  }
}

oublieMdp.onload = function () {
  json = JSON.parse(this.responseText);
  //if(isUnAlterate(json)){
    if (json.return.result == 'fail') {
      if (json.return.code == 404){
        document.getElementById('failNotFound').style.display = 'block';
      }else{
        document.getElementById('fail').style.display = 'block';
      }
    }else{
      document.getElementById('win').style.display = 'block';
      document.getElementById('input').style.display = 'none';
    }
    onRequest = false;
}
