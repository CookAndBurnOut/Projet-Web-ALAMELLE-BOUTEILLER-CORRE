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

var lastRecettePublicRequest = new XMLHttpRequest();

if(window.addEventListener){
    window.addEventListener('load', afterLoadPage, false);
}else{
    window.attachEvent('onload', afterLoadPage);
}

function afterLoadPage(){
  lastRecettePublicRequest.open('post', '../../serveur/PHP/action.php', true);
  lastRecettePublicRequest.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	lastRecettePublicRequest.send("json="+createJsonForRequest('{"fallait":"unTruc"}', 'lastRecettePublic'));
}

lastRecettePublicRequest.onload = function(){
  json = JSON.parse(this.responseText);
  //if(isUnAlterate(json)){
    if (json.return.result == 'win') {
      afficherPreviewRecette(json.return);
    }else{
      if (json.return.code == '21'){
        document.getElementById('previewRecette').innerHTML = 'Oups il n\'y a pas de recette publique';
      }
    }
}
