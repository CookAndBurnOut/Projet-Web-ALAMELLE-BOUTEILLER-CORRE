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

var searchRecetteRequest = new XMLHttpRequest();
var parametreRequest = new XMLHttpRequest();
var jsonResult;
var page = 0;
var nbrRecetteParPage;
var pageMax;
var onRequest = false;

//------------------------------------------------After Load Html---------------------------------

if(window.addEventListener){
    window.addEventListener('load', afterLoadPage, false);
}else{
    window.attachEvent('onload', afterLoadPage);
}

function afterLoadPage(){
  onRequest = true;
  parametreRequest.open('post', '../../serveur/PHP/action.php', true);
  parametreRequest.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	parametreRequest.send("json="+createJsonForRequest('{"name":"nbrRecetteParPage"}', 'getParametre'));
}

parametreRequest.onload = function () {
  var json = JSON.parse(this.responseText);
  if(isUnAlterate(json)){
    nbrRecetteParPage = json.return;
  }
  onRequest = false;
}

function rechercheButton(){
  if(!onRequest){
    search = document.getElementById('recherche').value;
    searchRecetteRequest.open('post', '../../serveur/PHP/action.php', true);
    searchRecetteRequest.setRequestHeader("Content-type","application/x-www-form-urlencoded");
  	searchRecetteRequest.send("json="+createJsonForRequest('{"value":"' + search + '"}', 'searchRecette'));
    page = 0;
  }
}

searchRecetteRequest.onload = function(){
  json = JSON.parse(this.responseText);
  //if(isUnAlterate(json)){
    if (json.return.result == 'fail') {
      document.getElementById('failRecherche').style.display = 'block';
      document.getElementById('rechercheResult').style.display = 'none';
    }else{
      jsonResult = json.return;
      clearAffichage()
      calculePageMax();
      affichageNbrPage();
      affichagePage();
      document.getElementById('failRecherche').style.display = 'none';
      document.getElementById('rechercheResult').style.display = 'block';
    }
}

function calculePageMax(){
  pageMax = jsonResult.length / nbrRecetteParPage;
  pageMax = Math.ceil(pageMax);
}

//--------------------------GESTION PAGE--------------------------------------
function affichageNbrPage(){
  document.getElementById('page').innerHTML = (page + 1) + '/' + pageMax;
}

function affichagePage(){
  for (var i = 0; i + (nbrRecetteParPage * page) < jsonResult.length && i < nbrRecetteParPage; i++) {
    afficherPreviewRecette(jsonResult[i + (nbrRecetteParPage * page)]);
  }
}

function clearAffichage(){
  document.getElementById('previewRecette').innerHTML = "";
}

function pagePrev(){
  if(page > 0){
    page = page - 1
    clearAffichage();
    affichageNbrPage();
    affichagePage();
  }
}

function pageSuiv(){
  if(page < pageMax - 1){
    page = page + 1
    clearAffichage();
    affichageNbrPage();
    affichagePage();
  }
}
