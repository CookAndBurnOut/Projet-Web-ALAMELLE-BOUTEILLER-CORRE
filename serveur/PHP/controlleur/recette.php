<?php

// inclusion de tous les modules nécessaires pour ce controlleur
require_once 'module/bdRecettes.php';
require_once 'module/bdRecettesPublic.php';

// controlleur qui gère les recettes
class recettes {

  // fonction permettant de rechercher une recette
  public function searchRecette($value){
    $bdRecettes = new bdRecettes();
    $find = false;
    $result = $bdRecettes->searchIdByValue($value);
    if ($result == null) {
      failWitchCodeJsonReturn('404');
    }else{
      $jsonStrFinal = '[';
      session_start();
      if ($_SESSION['statut'] == 'connected'){
        //recherche toute les recettes
        $find = true;
        foreach ($result as $key => $value) {
          $jsonStrFinal .= self::recttePreview($value);
          $jsonStrFinal .= ',';
        }
      }else{
        //recherche que les recettes public
        $bdRecettesPublic = new bdRecettesPublic();
        foreach ($result as $key => $value) {
          if($bdRecettesPublic->isExist($value)){
            $find = true;
            $jsonStrFinal .= self::recttePreview($value);
            $jsonStrFinal .= ',';
          }
        }
      }
      if($find){
        $jsonStrFinal = substr($jsonStrFinal, 0, -1);
        $jsonStrFinal .= ']';
        strJsonReturn($jsonStrFinal);
      }else{
        failWitchCodeJsonReturn('404');
      }
    }
  }

  // fonction permettant de supprimer un recette
  public function removeRecetteUser($id) {
    $bdRecettes = new bdRecettes();
    if ($bdRecettes->removeRecetteById($id)){
      winJsonReturn();
    }else{
      failJsonReturn();
    }
  }

  // fonction permettant de retourner la dernière recette publique
  public function getLastPublicRecette () {
    $bdRecettesPublic = new bdRecettesPublic();
    $id = $bdRecettesPublic->getIdLastPublicRecette();
    if($id == -1){
      failWitchCodeJsonReturn(21); //21 pas de recette public
    }elseif ($id == -2) {
      failWitchCodeJsonReturn(20); //20 erreur sql
    }else{
      $idRecette = $bdRecettesPublic->getIdRecette($id);
      if ($idRecette == -2) {
        failWitchCodeJsonReturn(20); //20 erreur sql
      }else{
       $jsonStr = self::recttePreview($idRecette);
       strJsonReturn($jsonStr);
      }
    }
  }

  // fonction permettant de retourner toutes les recettes d'un utilisateur
  public function getRecetteUser(){
    session_start();
    $bdRecettes = new bdRecettes();
    $result = $bdRecettes->getTupleByProprietaire($_SESSION['profil']);
    if($result == null){
      failWitchCodeJsonReturn('404');
    }else{
      $jsonStr = '[';
      for ($i=0; $i < count($result); $i++) {
        $jsonStr .= '{"id":' . $result[$i]['id']
                 . ',"nom":"' . $result[$i]['nom']
                 . '","nbrBurn":"' . $result[$i]['nbrBurn']
                 . '"},';
      }
      $jsonStr = substr($jsonStr, 0, strlen($jsonStr) - 1);
      $jsonStr .= ']';
      strJsonReturn($jsonStr);
    }
  }

  // fonction permettant de mettre dans le Json toutes les données de la recette
  public function recttePreview($idRecette){
    $bdRecettes = new bdRecettes();
    $arrayRecettes = $bdRecettes->getTupleByIdRecette($idRecette);
    $jsonStr .= '{"proprietaire":"' . $arrayRecettes['proprietaire']
             . '","nom":"' . $arrayRecettes['nom']
             . '","time":' . $arrayRecettes['time']
             . ',"type":"' . $arrayRecettes['type']
             . '","nbrBurn":' . $arrayRecettes['nbrBurn']
             . ',"descriptionC":"' . $arrayRecettes['descriptionC']
             . '","nbrPersonne":' . $arrayRecettes['nbrPersonne']
             . ',"url":"' . $arrayRecettes['url']
             . '","idRecette":' . $idRecette
             . ',"result":"win"}';
    return $jsonStr;
  }

  // fonction permettant de savoir si la recette est publique ou non
  public function isPublicRecette($id){
    $bdRecettes = new bdRecettes();
    if($bdRecettes->isExist($id)){
      $bdRecettesPublic = new bdRecettesPublic();
      if($bdRecettesPublic->isExist($id)){
        winJsonReturn();
      }else{
        failWitchCodeJsonReturn('404');
      }
    }else{
      failJsonReturn();
    }
  }


}


?>
