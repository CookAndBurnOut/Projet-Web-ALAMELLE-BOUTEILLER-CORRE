<?php
// inclusion de tous les modules nécessaires pour ce controlleur
require_once 'module/bdRecettes.php';
require_once 'module/bdIngredient.php';
require_once 'module/bdEtape.php';
require_once 'module/bdTags.php';

// controlleur qui gére l'affichage des recettes
class affichageRecette {

	// fonction permettant d'afficher les données contenues dans le Json
	function echoJsonRecette($id){
			$bdRecettes = new bdRecettes();
			if($bdRecettes->isExist($id)){
				//recuperation des info de base de la recette
				$arrayRecettes = $bdRecettes->getTupleByIdRecette($id);
				$jsonStr .= '{"proprietaire":"' . $arrayRecettes['proprietaire']
								 . '","nom":"' . $arrayRecettes['nom']
								 . '","time":' . $arrayRecettes['time']
								 . ',"type":"' . $arrayRecettes['type']
								 . '","nbrBurn":' . $arrayRecettes['nbrBurn']
								 . ',"descriptionC":"' . $arrayRecettes['descriptionC']
								 . '","descriptionL":"' . $arrayRecettes['descriptionL']
								 . '","nbrPersonne":' . $arrayRecettes['nbrPersonne']
								 . ',"url":"' . $arrayRecettes['url']
								 . '","ingredients":[';

				//recuperation des info ingredient
				$bdIngredient = new bdIngredient();
				$arrayIngredient = $bdIngredient->getTupleByIdIngredient($id);
				for ($i=0; $i < count($arrayIngredient); $i++) {
					$jsonStr .= '{"nom":"' . $arrayIngredient[$i]['nom'] . '","quantitee":' . $arrayIngredient[$i]['quantitee'] . ',"type":"' . $arrayIngredient[$i]['type'] . '"},';
				}
				$jsonStr = substr($jsonStr, 0, strlen($jsonStr) - 1);
				$jsonStr .= '],"etapes":[';

				$bdEtape = new bdEtape();
				$arrayEtape = $bdEtape->getTupleByIdEtape($id);

				for ($i=0; $i < count($arrayEtape); $i++) {
					$jsonStr .= '{"description":"' . $arrayEtape[$i]['description'] .'","ordre":' . $arrayEtape[$i]['ordre'] . '},';
				}
				$jsonStr = substr($jsonStr, 0, strlen($jsonStr) - 1);
				$jsonStr .= '],"tags":[';

				//recuperation des info tag
				$bdTags = new bdTags();
				$arrayTag = $bdTags->getTupleByIdTag($id);
				for ($i=0; $i < count($arrayTag); $i++) {
					$jsonStr .= '"' . $arrayTag[$i]['tag'] . '",';
				}
				$jsonStr = substr($jsonStr, 0, strlen($jsonStr) - 1);
				$jsonStr .= ']}';
				strJsonReturn($jsonStr);
			}else{
				failJsonReturn();
			}
	}
}

?>
