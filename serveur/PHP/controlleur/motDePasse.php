<?php

// inclusion de tous les modules nécessaires pour ce controlleur
include_once 'module/bdUser.php';
include_once 'module/bdRecupMdp.php';

// controlleur qui gère la récupération des mots de passe
class mdp{

    // fonction permettant d'envoyer le mail de réinitialisation du mot de passe oublié
    public function recuperation($email){
      $bdUser = new bdUser();
      if($bdUser->isExistByEmail($email)){
        $idUser = $bdUser->getIdByEmail($email);
        $uid = uniqid();
        $bdRecupMdp = new bdRecupMdp();
        if($bdRecupMdp->insertUid($uid, $idUser)){
          $link = 'https://cookandburnout.alwaysdata.net/client/HTML/changementMotDePasse.html?uid=' . $uid;
          if(mail ($email, 'Recuperation mot de passe', 'Cliquez sur ce lien pour changer votre mot de passe : ' . $link)){
            winJsonReturn();
          }else{
            failWitchCodeJsonReturn('52'); //52 echec envoi mail
          }
        }else{
          failJsonReturn();
        }
      }else{
        failWitchCodeJsonReturn('404'); //404 not found
      }
    }

    // fonction permettant de vérifier le lien de réinitialisation du mot de passe oublié
    public function verifUid($uid){
      $bdRecupMdp = new bdRecupMdp();
      if($bdRecupMdp->isExistByUid($uid)){
        winJsonReturn();
      }else{
        failWitchCodeJsonReturn('404'); //404 not found
      }
    }

    // fonction permettant de remplacer le mot de passe actuel de l'utilisateur à qui appartient l'adresse mail avec le mot de passe qui vient d'être changer
    public function changerMdpParLien($uid, $mdp){
      $bdRecupMdp = new bdRecupMdp();
      $idUser = $bdRecupMdp->getIdUserByUid($uid);
      $bdUser = new bdUser();
      if($bdUser->setMdpById($mdp ,$idUser)){
        $bdRecupMdp->deleteByUid($uid);
        winJsonReturn();
      }else{
        failJsonReturn();
      }
    }
}

?>
