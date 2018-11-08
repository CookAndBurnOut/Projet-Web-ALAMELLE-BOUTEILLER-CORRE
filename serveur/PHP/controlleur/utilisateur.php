<?php

// inclusion de tous les modules nécessaires pour ce controlleur
require_once 'module/bdUser.php';

// controlleur qui gère les utilisateurs
class utilisateur {

    // fonction permettant d'ajouter un utilisateur
    public function addUtilisateur($email, $login, $nom, $mdp) {
	    $passwordMd5 = md5($mdp);
      $bdUser = new bdUser();
      if ($bdUser->addUser($email, $login, $nom, $passwordMd5)){
        winJsonReturn();
      }else{
        failJsonReturn();
      }
    }

    // fonction permettatn de retirer un utilisateur
    public function removeUtilisateur($id) {
      $bdUser = new bdUser();
      if ($bdUser->removeUser($id)){
        winJsonReturn();
      }else{
        failJsonReturn();
      }
    }

    // fonction permettant de mettre dans le Json tous les utilisateurs
    public function getAllUtilisateur() {
      $bdUser = new bdUser();
      $result = $bdUser->getAllUser();
      if($result == null){
        failWitchCodeJsonReturn('404');
      }else{
        $jsonStr = '[';
        for ($i=0; $i < count($result); $i++) {
          $jsonStr .= '{"id":"' . $result[$i]['id']
                   . '","email":"' . $result[$i]['email']
                   . '","login":"' . $result[$i]['email']
                   . '","nom":"' . $result[$i]['nom']
                   . '"},';
        }
        $jsonStr = substr($jsonStr, 0, strlen($jsonStr) - 1);
        $jsonStr .= ']';
        strJsonReturn($jsonStr);
      }
    }

    // onction permettant de changer de mot de passe
    public function changerMdp($mdp){
      session_start();
      $bdUser = new bdUser();
      if($bdUser->setMdpById($mdp, $_SESSION['userId'])){
        winJsonReturn();
      }else{
        failJsonReturn();
      }
    }


}
