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

addBurnRequest = new XMLHttpRequest();
removeBurnRequest = new XMLHttpRequest();
allReadyLikeRequest = new XMLHttpRequest();


var id = 0;
var onRequest = false;

if(window.addEventListener){
    window.addEventListener('load', afterLoadPageBurn, false);
}else{
    window.attachEvent('onload', afterLoadPageBurn);
}

function afterLoadPageBurn(){
  onRequest = true;
	var url_string = window.location.href;
	var url = new URL(url_string);
	id = url.searchParams.get("id");
  allReadyLikeRequest.open('post', '../../serveur/PHP/action.php', true);
  allReadyLikeRequest.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	allReadyLikeRequest.send("json="+createJsonForRequest('{"idRecette":' + id + '}', 'allReadyBurn'));
}

allReadyLikeRequest.onload = function () {
	json = JSON.parse(this.responseText);
	if(isUnAlterate(json)){
		if (json.return.result == 'fail'){
      if(json.return.code == 14){
        afficherRemoveBurn();
      }else if(json.return.code == 15){
        masquerBurn();
      }
		}else if (json.return.result == 'win') {
      afficherAddBurn();
		}
  }
  onRequest = false;
}

function addBurn() {
  if (!onRequest){
    addBurnRequest.open('post', '../../serveur/PHP/action.php', true);
    addBurnRequest.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	  addBurnRequest.send("json="+createJsonForRequest('{"idRecette":' + id + '}', 'addBurn'));
    onRequest = true;
  }
}

addBurnRequest.onload = function () {
  json = JSON.parse(this.responseText);
	if(isUnAlterate(json)){
    if (json.return.result == 'win') {
      changerNbrBurn("+");
      afficherRemoveBurn();
		}
  }
  onRequest = false;
}

function removeBurn() {
  if (!onRequest){
    removeBurnRequest.open('post', '../../serveur/PHP/action.php', true);
    removeBurnRequest.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	  removeBurnRequest.send("json="+createJsonForRequest('{"idRecette":' + id + '}', 'removeBurn'));
    onRequest = true;
  }
}

removeBurnRequest.onload = function () {
  json = JSON.parse(this.responseText);
  if(isUnAlterate(json)){
    if (json.return.result == 'win') {
      changerNbrBurn("-");
      afficherAddBurn();
    }
  }
  onRequest = false;
}

function afficherAddBurn (){
  document.getElementById("removeBurn").style.display = 'none';
  document.getElementById("addBurn").style.display = 'block';
}

function afficherRemoveBurn (){
  document.getElementById("addBurn").style.display = 'none';
  document.getElementById("removeBurn").style.display = 'block';
}

function masquerBurn (){
  document.getElementById("addBurn").style.display = 'none';
  document.getElementById("removeBurn").style.display = 'none';
}

function changerNbrBurn(methode){
  var obj = document.getElementById('nbrBurn');
  if(methode == '+'){
    obj.innerHTML = '<div class="burn">' + (parseInt(obj.textContent, 10) + 1) + '</div>';
  }else{
    obj.innerHTML = '<div class="burn">' + (parseInt(obj.textContent, 10) - 1) + '</div>';
  }
}
