<?php

// inclusion de tous les modules nécessaires pour ce controlleur
require_once 'module/bdRecettes.php';
require_once 'module/bdIngredient.php';
require_once 'module/bdEtape.php';
require_once 'module/bdTags.php';


// controlleur qui gère la création des recettes
class creationRecette {

	// fonction permettatn de créer une recette
	function creeUneRecette($jsonStr){
		session_start();
		//$jsonObj = json_decode('{"name":"a","duree":85,"recetteTypeDuree":"h","descriptionC":"yhte","descriptionL":"reztreztreztreztrze","nbrPersonne":3,"attribut":[{"ingredientNom":["aaaa"],"length":1},{"ingredientQuantitee":[44],"length":1},{"ingredientType":["quantitee"],"length":1},{"etapeDescription":["iutyiuty"],"length":1},{"tagNom":["tretyreyt"],"length":1}]}', true);
		$jsonObj = json_decode($jsonStr, true);

		//crée dans la table recettes la recette avec le owner nbrburn et id recette
		$bdRecettes = new bdRecettes();

	    $nomRecette = $jsonObj['name'];
	    $dureeRecette = $jsonObj['duree'];
	    $typeDureeRecette = $jsonObj['recetteTypeDuree'];
	    $proprietaireRecette = $_SESSION['profil'];
	    $descriptionC = $jsonObj['descriptionC'];
	    $descriptionL = $jsonObj['descriptionL'];
	    $nbrPersonne = $jsonObj['nbrPersonne'];
			$url = $jsonObj['recetteUrlImg'];
		if(!$bdRecettes->createNewRecette($proprietaireRecette, $nomRecette, $dureeRecette, $typeDureeRecette,
	    	$descriptionC, $descriptionL, $nbrPersonne, $url)){
				echo "fail recetteeeeee";
	    	return false;
	  }

	    $idRecetteCourant = $bdRecettes->getInsertId();

		//crée dans la table ingredient les tuples contenant le type, la quantitée, le nom et l'id de la recette
	 	$bdIngredient = new bdIngredient();

	 	for ($i=0; $i < $jsonObj['attribut'][0]['length']; $i++) {
	 		$nomIngredient = $jsonObj['attribut'][0]['ingredientNom'][$i];
	 		$quantiteeIngredient = $jsonObj['attribut'][1]['ingredientQuantitee'][$i];
	 		$typeIngredient = $jsonObj['attribut'][2]['ingredientType'][$i];

	 		if(!$bdIngredient->addNewIngredient($idRecetteCourant, $nomIngredient, $quantiteeIngredient, $typeIngredient)){
		 		return false;
		 	}
	 	}

		//crée dans la table etapes les tuples contenant la description, l'étape, le nombre d'étape (1 2 3 4 ) et l'id de la recette
		$bdEtape = new bdEtape();

		for ($i=0; $i < $jsonObj['attribut'][3]['length']; $i++) {
			$descriptionEtape = $jsonObj['attribut'][3]['etapeDescription'][$i];

			if(!$bdEtape->addNewEtape($idRecetteCourant, $descriptionEtape, $i)){
			 	return false;
			}
		}

		//crée dans la table tag les tuples contenant le nom du tag et l'id de la recette
		$bdTag = new bdTags();

		for ($i=0; $i < $jsonObj['attribut'][4]['length']; $i++) {
			$tagNom = $jsonObj['attribut'][4]['tagNom'][$i];
		 	if(!$bdTag->addNewTag($idRecetteCourant, $tagNom)){
		 		return false;
		 	}
		}
		//renvoyer que c'est bon
		return true;
	}
}

?>
