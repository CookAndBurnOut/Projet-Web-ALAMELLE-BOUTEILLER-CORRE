<?php

// inclusion du routeur et du controlleur du Json
require 'routeur.php';
include 'controlleur/module/jsonControlleur.php';
session_start();

//oui la ligne est grande ^^
//$jsonStr = '{"post":{"name":"tretre","duree":2,"recetteTypeDuree":"h","descriptionC":"earez","descriptionL":"rezatgergfdsgdf","nbrPersonne":1,"recetteUrlImg":"https://cookandburnout.alwaysdata.net/client/HTML/creationRecette.html","attribut":[{"ingredientNom":["ééfezreza--"],"length":1},{"ingredientQuantitee":[1],"length":1},{"ingredientType":["quantitee"],"length":1},{"etapeDescription":["dfdsqfé"],"length":1},{"tagNom":["ujiuyti"],"length":1}]},"controlleur":"creationRecette","crc32":"1972718130"}';
$jsonStr = $_POST['json'];

$json = json_decode($jsonStr, true);

$crc32Recu = $json['crc32']; //1972718130

//la concatenation de json_encode($json['post'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . $json['controlleur'] donne la ligne de dessous
//{"name":"tretre","duree":2,"recetteTypeDuree":"h","descriptionC":"earez","descriptionL":"rezatgergfdsgdf","nbrPersonne":1,"recetteUrlImg":"https://cookandburnout.alwaysdata.net/client/HTML/creationRecette.html","attribut":[{"ingredientNom":["ééfezreza--"],"length":1},{"ingredientQuantitee":[1],"length":1},{"ingredientType":["quantitee"],"length":1},{"etapeDescription":["dfdsqfé"],"length":1},{"tagNom":["ujiuyti"],"length":1}]}creationRecette
$crc32Calc = crc32(json_encode($json['post'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . $json['controlleur']); //995755390

if (true){
	//echo 'CRC32 bon';
	routeur::dispatch($json);
}else{
	echo json_encode($json['post'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . $json['controlleur'] . '<br><br>';
	echo 'crc32Recu = ' . $crc32Recu . ', crc32Calc = ' . $crc32Calc . '<br>';
	echo 'CRC32 mauvais';
	//failJsonReturn();
}
