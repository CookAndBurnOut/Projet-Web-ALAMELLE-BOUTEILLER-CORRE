<?php

// inclusion de tous les modules nécessaires pour ce controlleur
require_once 'module/bdRecettes.php';
require_once 'module/bdLike.php';
require_once 'module/bdRecettesPublic.php';

// controlleur qui gère les like (burn)
class burn {

  // fonction permettant de vérifier si l'utilisateur à déjà like (burn) la recette
  public function allReadyBurn($id) {
    session_start();
    if ($_SESSION['statut'] == 'connected'){
      $bdLike = new bdLike();
      if($bdLike->isAlreadyLike($id, $_SESSION['profil'])){
        failWitchCodeJsonReturn(14); //code 14 veux dire deja like
      }else{
        winJsonReturn();
      }
    }else{
      failWitchCodeJsonReturn(15); //code 15 veux dire pas connected
    }
  }

  // fonction permettant d'ajouter un like (burn) à la recette (faisable uniquement en étant connecté)
  public function addBurn($id) {
    session_start();
    if ($_SESSION['statut'] == 'connected'){
      $bdRecettes = new bdRecettes();
      $bdLike = new bdLike();
      $bdRecettesPublic = new bdRecettesPublic();
      if(!($bdLike->isAlreadyLike($id, $_SESSION['profil']))){
        if($bdRecettes->addBurn($id)){
          if($bdLike->addLikePeople($id, $_SESSION['profil'])){
            if($bdRecettes->getBurnByIdRecette($id) > 14){ //si > 14 burn alors la recette doit etre public
              if(!($bdRecettesPublic->isExist($id))){
                $bdRecettesPublic->addRecettePublic($id);
              }
            }
            winJsonReturn();
          }
        }
      }else{
        failWitchCodeJsonReturn(14); //code 14 veux dire deja like
      }
    }else{
      failWitchCodeJsonReturn(15); //code 15 veux dire pas connected
    }
  }

  // fonction permettant de retirer un like (burn) à la recette (faisable uniquement en étant connecté)
  public function removeBurn($id) {
    session_start();
    if ($_SESSION['statut'] == 'connected'){
      $bdRecettes = new bdRecettes();
      $bdLike = new bdLike();
      $bdRecettesPublic = new bdRecettesPublic();
      if($bdLike->isAlreadyLike($id, $_SESSION['profil'])){
        if($bdRecettes->removeBurn($id)){
          if($bdLike->removeLikePeople($id, $_SESSION['profil'])){
            if($bdRecettes->getBurnByIdRecette($id) < 15){ //si > 14 burn alors la recette doit etre public
              if($bdRecettesPublic->isExist($id)){
                $bdRecettesPublic->removeRecettePublic($id);
              }
            }
            winJsonReturn();
          }
        }
      }else{
        failWitchCodeJsonReturn(13); //code 13 veux dire qui n'a jamais like
      }
    }else{
      failWitchCodeJsonReturn(15); //code 15 veux dire pas connected
    }
  }
}

?>
