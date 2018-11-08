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

//---------------------------------------------------Variable----------------------------------------------------------------

var requeteCreationRecette = new XMLHttpRequest();
var enCourDeCreation = false;
var isConnect = false;

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
    }else{
      isConnect = false;
      document.getElementById("notConnect").style.display = 'block';
      document.getElementById("isConnect").style.display = 'none';
      afficherDeconnection();
    }
  }
}


//----------------------------------------------OnCLick Ingredient-----------------------------------------------------------
function ajouterIngredient(){
	var ingredientNom = document.getElementById('ingredientNom');
	var ingredientQuantitee = document.getElementById('ingredientQuantitee');
	var ingredientType = document.getElementById('ingredientType');
	if (ingredientNom.value != "" && ingredientQuantitee.value != "" && ingredientQuantitee.value.isNumber()){
		if (ingredientType.value == 'quantitee' || ingredientType.value == 'mg' || ingredientType.value == 'g' || ingredientType.value == 'kg' || ingredientType.value == 'ml' || ingredientType.value == 'cl' || ingredientType.value == 'l'){
			insertRowIngredient(ingredientNom.value, ingredientQuantitee.value, ingredientType.value);
			ingredientNom.value = "";
			ingredientQuantitee.value = "";
			ingredientType.value = "quantitee";
		}else{
			alert("pas bien de modifier l'html");
		}
	}else{
		alert("Vous ne pouvez pas ajouter d'ingredients, verifiez que tous les champs sont remplis et possèdent une quantitée");
	}
}

function supresionIngredient(ligneTab){
	deleteRow(ligneTab, 'tableIngredient', 'supresionIngredient', 3);
}

//------------------------------------------------OnCLick Etapes------------------------------------------------------------

function ajouterEtape(){
 	var etapeDescription = document.getElementById('etapeDescription').value;
  if(etapeDescription != ""){
    insertRowEtape(etapeDescription);
  }else{
    alert("L'etape ne peux pas être vide");
  }
}

function supresionEtape(ligneTab){
	deleteRow(ligneTab, 'tableEtape', 'supresionEtape', 1);
}

//--------------------------------------------------OnCLick Tag-------------------------------------------------------------

function ajouterTag(){
 	var tagNom = document.getElementById('tagNom').value;
  if(tagNom != ""){
    insertRowTag(tagNom);
  }else{
    alert("Le tag ne peux pas être vide");
  }
}

function supresionTag(ligneTab){
	deleteRow(ligneTab, 'tableTag', 'supresionTag', 1);
}

//-------------------------------------------------OnClick Creation---------------------------------------------------------

function creationRecette(){
  if (!enCourDeCreation && isConnect) {
    enCourDeCreation = true;
    jsonStr = creationDuJson();
    if(jsonStr != 'fail'){
      requeteCreationRecette.open("POST", "../../serveur/PHP/action.php", true);
      requeteCreationRecette.setRequestHeader("Content-type","application/x-www-form-urlencoded");
      requeteCreationRecette.send("json="+jsonStr);
    }
  }
}

requeteCreationRecette.onload = function(){
	//si reponse fail alors message erreur*
	json = JSON.parse(this.responseText);
	if(isUnAlterate(json)){
		if (json.return.result == 'fail'){
			alert("Oups il y'a eu une erreur pendant la création de la recette veuillez réessayer");
		}
    if (json.return.result == 'win') {
			alert("boooouuummmmm recette créée");
		}
	}else{
  	alert("Oups il y'a eu une erreur pendant la création de la recette veuillez réessayer ultérieurement");
  }
  enCourDeCreation = false;
};

//-------------------------------------------------Creation d'array---------------------------------------------------------

function creationArrayStringJsonForAjax(tableid, posCol, arrayName){
	var cpt = 0;
	var str = "{\"" + arrayName + "\":[";
	var rows = document.getElementById(tableid).rows
	for (var i = 1; i < rows.length - 1; ++i) {
		var row = rows[i].cells;
		str += "\"" + row[posCol].textContent + "\",";
		++cpt;
	}
	str = str.substring(0, str.length-1);
	str += "],\"length\":" + cpt + "}";
	return str;
}

function creationArrayIntJsonForAjax(tableid, posCol, arrayName){
	var cpt = 0;
	var str = "{\"" + arrayName + "\":[";
	var rows = document.getElementById(tableid).rows
	for (var i = 1; i < rows.length - 1; ++i) {
		var row = rows[i].cells;
		str += row[posCol].textContent + ",";
		++cpt;
	}
	str = str.substring(0, str.length-1);
	str += "],\"length\":" + cpt + "}";
	return str;
}


//-------------------------------------------------Creation du Json---------------------------------------------------------

function creationDuJson (){
	//creation des array ingredient
	var ingredientNomArray = creationArrayStringJsonForAjax('tableIngredient', 0, "ingredientNom");
	var ingredientQuantiteeArray = creationArrayIntJsonForAjax('tableIngredient', 1, "ingredientQuantitee");
	var ingredientTypeArray = creationArrayStringJsonForAjax('tableIngredient', 2, "ingredientType");
	var etapeDescriptionArray = creationArrayStringJsonForAjax('tableEtape', 0, "etapeDescription");
	var tagNomArray = creationArrayStringJsonForAjax('tableTag', 0, "tagNom");

	var recetteNom = document.getElementById('recetteNom').value;
	var recetteDuree = document.getElementById('recetteDuree').value;
	var recetteTypeDuree = document.getElementById('recetteTypeDuree').value;
	var recetteDescriptionC = document.getElementById('descriptionC').value;
	var recetteDescriptionL = document.getElementById('descriptionL').value;
	var recetteNbrPersonne = document.getElementById('nbrPersonne').value;
  var recetteUrlImg = document.getElementById('ulrImg').value;

  if (recetteNom != "" && recetteDescriptionC != ""  && recetteDescriptionL != ""  && recetteUrlImg != "" && recetteDuree.isNumber() && recetteNbrPersonne.isNumber()){
		if (recetteTypeDuree == 'h' || recetteTypeDuree == 'm' || recetteTypeDuree == 'j'){
      jsonStr = "";
    	jsonStr += "{\"name\":\"" + recetteNom + "\",\"duree\":"
    			+ recetteDuree + ",\"recetteTypeDuree\":\"" + recetteTypeDuree + "\",\"descriptionC\":\""
    			+ recetteDescriptionC + "\",\"descriptionL\":\"" + recetteDescriptionL + "\",\"nbrPersonne\":"
    			+ recetteNbrPersonne + ",\"recetteUrlImg\":\"" + recetteUrlImg
          + "\",\"attribut\":[" + ingredientNomArray + "," + ingredientQuantiteeArray
          + "," + ingredientTypeArray + "," + etapeDescriptionArray + "," + tagNomArray + "]}";

    	//calculer le crc32 de chaque json
    	jsonStr = createJsonForRequest(jsonStr, 'creationRecette');
    	return jsonStr;
		}else{
			alert("pas bien de modifier l'html");
		}
	}else{
		alert("Zut, un élément de votre recette n'a pas été correctement rempli :/");
	}
  return 'fail';
}

//-------------------------------------------------Gestion Tableau----------------------------------------------------------

function insertRowIngredient(nom, quantitee, type) {
	var table = document.getElementById('tableIngredient');
	var row = table.insertRow(table.rows.length-1);
	row.insertCell(0).innerHTML = nom;
	row.insertCell(1).innerHTML = quantitee;
	row.insertCell(2).innerHTML = type;
	row.insertCell(3).innerHTML = "<button class=\"buttonDelete\" onclick=\"supresionIngredient(" + (table.rows.length - 2) + ")\">-</button>";
}

function insertRowEtape(description) {
	var table = document.getElementById('tableEtape');
	var row = table.insertRow(table.rows.length-1);
	row.insertCell(0).innerHTML = description;
	row.insertCell(1).innerHTML = "<button class=\"buttonDelete\" onclick=\"supresionEtape(" + (table.rows.length - 2) + ")\">-</button>";
}

function insertRowTag(tag) {
	var table = document.getElementById('tableTag');
	var row = table.insertRow(table.rows.length-1);
	row.insertCell(0).innerHTML = tag;
	row.insertCell(1).innerHTML = "<button class=\"buttonDelete\" onclick=\"supresionTag(" + (table.rows.length - 2) + ")\">-</button>";
}

function deleteRow(ligneTab, id, methodeSupr, colPos) {
	var table = document.getElementById(id);
	table.deleteRow(ligneTab);
	for (var i = 1; i < table.rows.length - 1; ++i) {
		var row = table.rows[i].cells;
		row[colPos].innerHTML = "<button class=\"buttonDelete\" onclick=\"" + methodeSupr + "(" + i + ")\">-</button>";
	}
}
