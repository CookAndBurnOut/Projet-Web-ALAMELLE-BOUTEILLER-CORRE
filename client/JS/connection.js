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

connectionRequest = new XMLHttpRequest();
isConnectedRequest = new XMLHttpRequest();
deconnectionRequest = new XMLHttpRequest();
isAdminRequest = new XMLHttpRequest();

//------------------------------------------------After Load Html---------------------------------

if(window.addEventListener){
    window.addEventListener('load', afterLoadPage, false);
}else{
    window.attachEvent('onload', afterLoadPage);
}

function afterLoadPage(){
  isConnectedRequest.open('post', '../../serveur/PHP/action.php', true);
  isConnectedRequest.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	isConnectedRequest.send("json="+createJsonForRequest('{"fallait":"unTruc"}', 'isConnect'));
}

isConnectedRequest.onload = function () {
  var json = JSON.parse(this.responseText);
  if(isUnAlterate(json)){
    if (json.return.statut == "connected"){
      afficherConnection();
      isAdmin();
    }else{
      afficherDeconnection();
    }
  }
}

//------------------------------Gestion du botton pour la page Admin-----------------------------
function isAdmin() {
 isAdminRequest.open('post', '../../serveur/PHP/action.php', true);
 isAdminRequest.setRequestHeader("Content-type","application/x-www-form-urlencoded");
 isAdminRequest.send("json="+createJsonForRequest('{"fallait":"unTruc"}', 'isAdmin'));
}

isAdminRequest.onload = function () {
  var json = JSON.parse(this.responseText);
  if(isUnAlterate(json)){
    if(json.return.grade == "admin"){
        document.getElementById("onlyAdmin").style.display = 'inline-block';
    }
  }
}

//-----------------------------------Deconnection onClick----------------------------------------

function deconnection() {
  deconnectionRequest.open('post', '../../serveur/PHP/action.php', true);
  deconnectionRequest.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	deconnectionRequest.send("json="+createJsonForRequest('{"fallait":"unTruc"}', 'deconnection'));
}

deconnectionRequest.onload = function () {
  var json = JSON.parse(this.responseText);
  if(isUnAlterate(json)){
    if(json.return.result == "win"){
      location.reload();
    }
  }
}

//------------------------------------Connection onClick-----------------------------------------

function connection(){
  login = document.getElementById('connectionLogin').value;
  mdp = document.getElementById('connectionMdp').value;
  connectionRequest.open('post', '../../serveur/PHP/action.php', true);
  connectionRequest.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	connectionRequest.send("json="+createJsonForRequest('{"login":"' + login + '","mdp":"' + mdp + '"}', 'connection'));
}

connectionRequest.onload = function () {
  var json = JSON.parse(this.responseText);
  if(isUnAlterate(json)){
    if(json.return.result == "win"){
      location.reload();
    }else{
      alert("Mot de passe ou Nom d'utilisateur incorrect");
    }
  }
}

//---------------------------------------Gestion Div--------------------------------------------

function afficherConnection (){
  document.getElementById("deconnectionDiv").style.display = 'none';
  document.getElementById("connectionDiv").style.display = 'inline-block';
}

function afficherDeconnection (){
  document.getElementById("connectionDiv").style.display = 'none';
  document.getElementById("onlyAdmin").style.display = 'none';
  document.getElementById("deconnectionDiv").style.display = 'inline-block';
}
