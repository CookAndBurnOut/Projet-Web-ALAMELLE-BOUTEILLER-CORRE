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

isAdminRequest = new XMLHttpRequest();
parametreChangeRequest = new XMLHttpRequest();
ajouterUnUtilisateurRequest = new XMLHttpRequest();
getAllUtilisateurRequest = new XMLHttpRequest();
suprimerUnUtilisateurRequest = new XMLHttpRequest();

var isAdminBool = false;
var onRequestParam = false;
var onRequestAddUti = false;
var onRequestAllUti = false;
var onRequestRemoveUti = false;

isConnectedRequest.onload = function () {
  var json = JSON.parse(this.responseText);
  if(isUnAlterate(json)){
    if (json.return.statut == "connected"){
      afficherConnection();
      isAdmin();
    }else{
      document.getElementById("isAdmin").style.display = 'none';
      afficherDeconnection();
    }
  }
}

isAdminRequest.onload = function () {
  var json = JSON.parse(this.responseText);
  if(isUnAlterate(json)){
    if(json.return.grade == "admin"){
        isAdminBool = true;
        document.getElementById("onlyAdmin").style.display = 'inline-block';
        document.getElementById("isAdmin").style.display = 'block';
        document.getElementById("notAdmin").style.display = 'none';
        getAllUtilisateur();
    }else{
        isAdminBool = false;
        document.getElementById("notAdmin").style.display = 'block';
        document.getElementById("isAdmin").style.display = 'none';
    }
  }
}

/**----------------------------------------List des utilisateur---------------------------------------**/

function getAllUtilisateur() {
  if(!onRequestAllUti){
    onRequestAllUti = true;
    document.getElementById('listUtilisateur').style.display = 'none';
    document.getElementById('listUtilisateur').innerHTML = '';
    getAllUtilisateurRequest.open('post', '../../serveur/PHP/action.php', true);
    getAllUtilisateurRequest.setRequestHeader("Content-type","application/x-www-form-urlencoded");
    getAllUtilisateurRequest.send("json="+createJsonForRequest('{"fallait":"untruc"}', 'getAllUser'));
  }
}

getAllUtilisateurRequest.onload = function () {
  if(isUnAlterate(json)){
    if(json.return.result == 'fail'){
      if(json.return.code == '404'){
        document.getElementById('listUtilisateur').innerHTML = "Oups il n'y a pas d'utilisateur";
        document.getElementById('listUtilisateur').style.display = 'block';
      }
    }else{
      for (var i = 0; i < json.return.length; i++) {
        affichageUtilisateur(json.return[i].id, json.return[i].email, json.return[i].nom, json.return[i].login);
      }
      document.getElementById('listUtilisateur').style.display = 'block';
    }
  }
  onRequestAllUti = false;
}

/**---------------------------------------Ajouter un utilisateur--------------------------------------**/
function addUtilisateur(){
  if(!onRequestAddUti && isAdminBool){
    onRequestAddUti = true;
    var email = document.getElementById("email").value;
    var login = document.getElementById("login").value;
    var nom = document.getElementById("nom").value;
    var mdp = document.getElementById("mdp").value;

    if(email != "" && login != "" && nom != "" && mdp != ""){
      jsonStr = '{"email":"' + email + '","login":"' + login + '","nom":"' + nom + '","mdp":"' + mdp + '"}'
      ajouterUnUtilisateurRequest.open('post', '../../serveur/PHP/action.php', true);
      ajouterUnUtilisateurRequest.setRequestHeader("Content-type","application/x-www-form-urlencoded");
      ajouterUnUtilisateurRequest.send("json="+createJsonForRequest(jsonStr, 'addUser'));
    }else {
      alert("L'email, le login, le nom ou le mot de passe ne peuvent pas être vide");
    }
  }
}

ajouterUnUtilisateurRequest.onload = function () {
  var json = JSON.parse(this.responseText);
  var div = document.getElementById("resultUser")
  if(isUnAlterate(json)){
    if(json.return.result == "win"){
      location.reload();
    }else{
      div.innerHTML = "Il y a eu une erreur dans la création de l'utilisateur";
    }
  }else{
    div.innerHTML = "Oups il y a eu un problème avec la page";
  }
  div.style.display = 'block';
  onRequestAddUti = false;
}

/**-------------------------------------supresion utilisateur----------------------------------------------**/

function removeUtilisateur(id) {
  if(!onRequestRemoveUti && isAdminBool){
    onRequestRemoveUti = true;
    suprimerUnUtilisateurRequest.open('post', '../../serveur/PHP/action.php', true);
    suprimerUnUtilisateurRequest.setRequestHeader("Content-type","application/x-www-form-urlencoded");
    suprimerUnUtilisateurRequest.send("json="+createJsonForRequest('{"id":' + id + '}', 'removeUser'));
  }
}

suprimerUnUtilisateurRequest.onload = function (){
  var json = JSON.parse(this.responseText);
  var div = document.getElementById("listUtilisateurResult")
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
  onRequestRemoveUti = false;
}

/**-----------------------------------------Appliquer onCLick-----------------------------------------**/

function appliquerButton(){
  if(!onRequestParam && isAdminBool){
    onRequestParamonRequestParam = true;
    var nbrRecetteParPage = document.getElementById('generaleNbrRecettePage').value;
    if(nbrRecetteParPage.isNumber()){
      if(parseInt(nbrRecetteParPage) >= 1){
        parametreChangeRequest.open('post', '../../serveur/PHP/action.php', true);
        parametreChangeRequest.setRequestHeader("Content-type","application/x-www-form-urlencoded");
      	parametreChangeRequest.send("json="+createJsonForRequest('{"name":"nbrRecetteParPage","value":"' + nbrRecetteParPage + '"}', 'setParametre'));
      }else {
        alert("Le nombre de recette par page doit etre supérieur ou egal a 1");
      }
    }else {
      alert("Le nombre de recette par page doit être un nombre");
    }
  }
}

parametreChangeRequest.onload = function() {
  var json = JSON.parse(this.responseText);
  var div = document.getElementById("resultParam")
  if(isUnAlterate(json)){
    if(json.return.result == "win"){
      div.innerHTML = "Le paramètre a bien été changé";
    }else{
      div.innerHTML = "Il y a eu une erreur dans le changement du paramètre";
    }
  }else{
    div.innerHTML = "Oups il y a eu un problème avec la page";
  }
  div.style.display = 'block';
  onRequestParam = false;
}

/**-------------------------------------affichage utilisateur----------------------------------------**/

function affichageUtilisateur(id, email, nom, login) {
  var divUtilisateur = document.getElementById('listUtilisateur');
  str = '<div>';
  str += 'Email : ' + email;
  str += ' Nom : ' + nom;
  str += ' Login : ' + login;
  str += '<button onclick="removeUtilisateur(' + id + ')">Supprimer</button>';
  str.innerHTML += '</div>';
  divUtilisateur.innerHTML += str;
}
