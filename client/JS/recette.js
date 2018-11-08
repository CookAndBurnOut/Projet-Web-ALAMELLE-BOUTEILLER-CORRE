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

function afficherPreviewRecette(json){
  divPreviewRecette = document.getElementById('previewRecette');
  str  = '<div class="container">';
  str += '<h1>' + json.nom + '</h1>';
  str += '<div class="burn">' + json.nbrBurn + '</div>'
  str += '<img class="imgRecette" src="' + json.url + '" alt="Image de la recette"/>';
  str += '<div class="desc">' + "Durée : " + json.time + " Nombre de Personne : " + json.nbrPersonne + " Description : " + json.descriptionC + ' fait par ' + json.proprietaire + '</div>';
  str += '<a href="https://cookandburnout.alwaysdata.net/client/HTML/affichageRecette.html?id=' + json.idRecette + '">';
  str += '<img src="https://upload.wikimedia.org/wikipedia/commons/thumb/6/67/OOjs_UI_icon_external-link-ltr.svg/1024px-OOjs_UI_icon_external-link-ltr.svg.png" /></a></div>';
  divPreviewRecette.innerHTML += str;
}
