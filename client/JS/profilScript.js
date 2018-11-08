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

changementMdpRequest = new XMLHttpRequest();
getRecetteRequest = new XMLHttpRequest();
removeRecetteRequest = new XMLHttpRequest();
isConnectedRequest = new XMLHttpRequest();

var isConnect = false;
var onRequestMdp = false;
var onRequestGetRecette = false;
var onRequestRemoveRecette = false;

//------------------------------------------------After Load Html------------------------------------------------------------

isConnectedRequest.onload = function () {
  var json = JSON.parse(this.responseText);
  if(isUnAlterate(json)){
    if (json.return.statut == "connected"){
      isConnect = true;
      document.getElementById("isConnect").style.display = 'block';
      document.getElementById("notConnect").style.display = 'none';
      afficherConnection();
      isAdmin();
      getRecette()
    }else{
      isConnect = false;
      document.getElementById("notConnect").style.display = 'block';
      document.getElementById("isConnect").style.display = 'none';
      afficherDeconnection();
    }
  }
}

/**----------------------------------------List des recette---------------------------------------**/

function getRecette() {
  if(!onRequestGetRecette && isConnect){
    onRequestGetRecette = true;
    document.getElementById('listRecette').style.display = 'none';
    document.getElementById('listRecette').innerHTML = '';
    getRecetteRequest.open('post', '../../serveur/PHP/action.php', true);
    getRecetteRequest.setRequestHeader("Content-type","application/x-www-form-urlencoded");
    getRecetteRequest.send("json="+createJsonForRequest('{"fallait":"untruc"}', 'getRecetteUser'));
  }
}

getRecetteRequest.onload = function () {
  var json = JSON.parse(this.responseText);
  if(isUnAlterate(json)){
    if(json.return.result == 'fail'){
      if(json.return.code == '404'){
        document.getElementById('listRecette').innerHTML = "Oups visiblement vous ne possedez aucune recette";
        document.getElementById('listRecette').style.display = 'block';
      }
    }else{
      for (var i = 0; i < json.return.length; i++) {
        affichageRecette(json.return[i].id, json.return[i].nom, json.return[i].nbrBurn);
      }
      document.getElementById('listRecette').style.display = 'block';
    }
  }
  onRequestGetRecette = false;
}

/**-------------------------------------supresion recette----------------------------------------------**/

function removeRecette(id) {
  if(!onRequestRemoveRecette && isConnect){
    onRequestRemoveRecette = true;
    removeRecetteRequest.open('post', '../../serveur/PHP/action.php', true);
    removeRecetteRequest.setRequestHeader("Content-type","application/x-www-form-urlencoded");
    removeRecetteRequest.send("json="+createJsonForRequest('{"id":' + id + '}', 'removeRecetteUser'));
  }
}

removeRecetteRequest.onload = function (){
  var json = JSON.parse(this.responseText);
  var div = document.getElementById("listRecetteResult")
  if(isUnAlterate(json)){
    if(json.return.result == "win"){
      location.reload();
    }else{
      div.innerHTML = "Il y a eu une erreur dans la suppression de l'utilisateur";
    }
  }else{
    div.innerHTML = "Oups il y a eu un problème avec la page";
  }
  div.style.display = 'block';
  onRequestRemoveRecette = false;
}


//-----------------------------------ChangementMdp onClick----------------------------------------

function changementMdp(){
  if(!onRequestMdp && isConnect){
    onRequestMdp = true;
    newMdp = document.getElementById('newMdp').value;
    confirmMdp = document.getElementById('confirmMdp').value;

    if(newMdp == confirmMdp && newMdp != ''){
      changementMdpRequest.open('post', '../../serveur/PHP/action.php', true);
      changementMdpRequest.setRequestHeader("Content-type","application/x-www-form-urlencoded");
      changementMdpRequest.send("json="+createJsonForRequest('{"mdp":"' + newMdp + '"}', 'changementMdp'));
    }
  }
}

changementMdpRequest.onload = function () {
  var json = JSON.parse(this.responseText);
  if(isUnAlterate(json)){
    if (json.return.statut == "connected"){
      document.getElementById('newMdp').value = '';
      document.getElementById('confirmMdp').value = '';
      alert("Mot de passe changé !");
    }else{
      alert("Echec de changement de mot de passe T-T");
    }
  }
  onRequestMdp = false;
}

/**-------------------------------------affichage recette----------------------------------------**/

function affichageRecette(id, nom, burn) {
  var divUtilisateur = document.getElementById('listRecette');
  str = '<div>';
  str += 'Nom : ' + nom;
  str += ' Burn : ' + burn;
  str += '<button onclick="removeRecette(' + id + ')">Supprimer</button>';
  str.innerHTML += '</div>';
  divUtilisateur.innerHTML += str;
}
