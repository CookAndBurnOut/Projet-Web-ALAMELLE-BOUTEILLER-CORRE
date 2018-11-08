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

var request = new XMLHttpRequest()
var isPublicRecetteRequest = new XMLHttpRequest();
var id;

isConnectedRequest.onload = function () {
  var json = JSON.parse(this.responseText);
  if(isUnAlterate(json)){
    getIdOnLink();
    if (json.return.statut == "connected"){
      document.getElementById("isConnect").style.display = 'block';
      document.getElementById("notConnect").style.display = 'none';
      afficherConnection();
      isAdmin();
      getRecette(id);
    }else{
      afficherDeconnection();
      isPublic(id);
    }
  }
}

/**--------------------------------- Recuperation de lid dans lien -----------------------------**/

function getIdOnLink(){
  var url_string = window.location.href;
  var url = new URL(url_string);
  id = url.searchParams.get("id");
}

/**--------------------------------- si pas connecter on verifie que la recette est public -----------------------------**/

function isPublic(idRecette){
  isPublicRecetteRequest.open('post', '../../serveur/PHP/action.php',true);
  isPublicRecetteRequest.setRequestHeader("Content-type","application/x-www-form-urlencoded");
  isPublicRecetteRequest.send("json="+createJsonForRequest('{"id":' + idRecette + '}', 'isPublicRecette'));
}

isPublicRecetteRequest.onload = function (){
  console.log(this.responseText);
  var json = JSON.parse(this.responseText);
//if(isUnAlterate(json)){
		if(json.return.result == "win"){
      getRecette(id);
      document.getElementById("isConnect").style.display = 'block';
        document.getElementById("wait").style.display = 'none';
    }else if (json.return.result == "fail"){
      if(json.return.code == '404'){
        document.title = "Erreur";
        document.getElementById("wait").style.display = 'none';
        document.getElementById("notConnect").style.display = 'block';
        document.getElementById("isConnect").style.display = 'none';
      }else{
        document.title = "Erreur";
        document.getElementById("wait").style.display = 'none';
        document.getElementById("fail").style.display = 'block';
      }
    }

}

/**--------------------- ------------ recuperation de la recette -----------------------------**/

function getRecette(id){
	request.open('post', '../../serveur/PHP/action.php',true);
	request.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	request.send("json="+createJsonForRequest('{"id":' + id + '}', 'affichageRecette'));
}

request.onload = function () {
	var json = JSON.parse(this.responseText);
		if(json.return.result == "fail"){
			document.title = "Erreur";
			document.getElementById("wait").style.display = 'none'; 
			document.getElementById("fail").style.display = 'block';
		}else{
			document.title = json.return.nom;
			document.getElementById("wait").style.display = 'none';
			document.getElementById("win").style.display = 'block';

      	document.getElementById("imgRecette").src = json.return.url //Récupération de l'élément url

			document.getElementById('nomRecette').innerHTML += json.return.nom; //Récupération de l'élément nom de la recette
			document.getElementById('temps').innerHTML += json.return.time += json.return.type; //Récupération de l'élément durée suivie du type de durée

			document.getElementById('nbrPersonne').innerHTML += "Pour " + json.return.nbrPersonne + " personne(s)"; //Récupération de l'élément nombre de personne
			
			document.getElementById('descriptionLongue').innerHTML += json.return.descriptionL; //Récupération de l'élément description longue de la recette

			document.getElementById('nbrBurn').innerHTML += '<div class="burn">' + json.return.nbrBurn + '</div>'; //Récupération de l'élément nombre de burn suivie de l'image d'un burn

			for (i=0; i<json.return.ingredients.length; ++i) // boucle qui récupère tout les ingrédients, leur quantité ainsi que leur type.
			{
			document.getElementById('ingredients').innerHTML +=  '<div class="truc"> ' + "-"+ json.return.ingredients[i].nom + " " + json.return.ingredients[i].quantitee + " " + json.return.ingredients[i].type;
			}

			for (i=0; i<json.return.ingredients.length; ++i) // boucle qui récupère toutes les étapes
			{
			document.getElementById('etapes').innerHTML += '<div class="truc"> ' + "-"+ json.return.etapes[i].description;
			}

			document.getElementById('win').innerHTML += "<p>Recette crée par " + json.return.proprietaire + "</p>"; //Récupération du nom du créateur de la recette
			
		}
	
}
