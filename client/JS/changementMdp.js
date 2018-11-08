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

//------------------------------------------------After Load Html------------------------------------------------------------

var request = new XMLHttpRequest();
var changerMdprequest = new XMLHttpRequest();
var uid;
var isGoodLink = false;
var onRequest = false;

if(window.addEventListener){
    window.addEventListener('load', afterLoadPage, false);
}else{
    window.attachEvent('onload', afterLoadPage);
}

function afterLoadPage(){
  isGoodLink = false;
	var url_string = window.location.href;
	var url = new URL(url_string);
	uid = url.searchParams.get("uid");
	request.open('post', '../../serveur/PHP/action.php',true);
	request.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	request.send("json="+createJsonForRequest('{"uid":"' + uid + '"}', 'verifUid'));
}

request.onload = function () {
	var json = JSON.parse(this.responseText);
	//if(isUnAlterate(json)){
		if(json.return.result == "fail"){
			document.getElementById("failLien").style.display = 'block';
		}else{
      isGoodLink = true;
      document.getElementById("failLien").style.display = 'none';
      document.getElementById("winLien").style.display = 'block';
    }
	//}else {
		//alert("probleme de chargement de la page");
	//}
}


function changerMdp(){
  if(isGoodLink && !onRequest){
    onRequest = true;
    var mdp1 = document.getElementById('mdpNew').value;
    var mdp2 = document.getElementById('mdpConf').value;
    if(mdp1 == mdp2 && mdp1 != ""){
      changerMdprequest.open('post', '../../serveur/PHP/action.php',true);
      changerMdprequest.setRequestHeader("Content-type","application/x-www-form-urlencoded");
      changerMdprequest.send("json="+createJsonForRequest('{"uid":"' + uid + '","mdp":"' + mdp1 + '"}', 'changeMdpParLien'));
    }else{
      alert('Les deux mots de passe ne correspondent pas ou l\'un des deux est vide');
    }
  }
}

changerMdprequest.onload = function() {
  var json = JSON.parse(this.responseText);
  //if(isUnAlterate(json)){
    if(json.return.result == "fail"){
      document.getElementById("failMdp").style.display = 'block';
    }else{
      document.getElementById("winLien").style.display = 'none';
      document.getElementById("failMdp").style.display = 'none';
      document.getElementById("winMdp").style.display = 'block';
    }
  //}else {
    //alert("probleme de chargement de la page");
  //}
  onRequest = false;
}
