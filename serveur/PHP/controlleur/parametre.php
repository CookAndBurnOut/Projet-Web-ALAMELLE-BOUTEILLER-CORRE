<?php

// inclusion de tous les modules nécessaires pour ce controlleur
include_once 'module/bdParametre.php';

// controlleur qui gère les paramètres
class parametre{

    // fonction permettant de retourner un paramètre
    public function getParametreByName($name){
      $bdParametre = new bdParametre();
      $result = $bdParametre->getParametreValueByName($name);
      strJsonReturn($result);
    }

    // fonction permettant de définir un paramètre
    public function setParametreByName($name, $value){
      $bdParametre = new bdParametre();
      if($bdParametre->setParametreValueByName($name, $value)){
        winJsonReturn();
      }else {
        failJsonReturn();
      }
    }
}

?>
