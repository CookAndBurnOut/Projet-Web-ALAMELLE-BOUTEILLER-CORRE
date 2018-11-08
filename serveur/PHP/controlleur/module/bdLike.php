<?php

// Module gérant la base de données contenant les like (burn)
class bdLike
{

    private $myDbLink;

    public function __construct(){
        $this->myDbLink = mysqli_connect('mysql-cookandburnout.alwaysdata.net', '167560', '5FruitsEtLegumes')
        or die();
        mysqli_select_db($this->myDbLink , 'cookandburnout_bdcookburn')
        or die();
    }

    // fonction permettant d'ajouter des like du compte actuel sur la recette en paramètre
    public function addLikePeople($id, $profil){
      $query = "INSERT INTO `like` (`idRecette`, `personne`) VALUES ('" . $id . "', '" . $profil . "')";
      if (!($dbResult = mysqli_query($this->myDbLink, $query))){
          return false;
      }
      return true;
    }

    // fonction permettant de vérifier si le compte actuel à déjà liker la recette en paramètre
    public function isAlreadyLike($id, $profil){
      $query = "SELECT COUNT(*) AS nbr FROM `like` where personne = '" . $profil . "' and idRecette = '" . $id ."'";
      if (!($dbResult = mysqli_query($this->myDbLink, $query))){
        return false;
      }
      $row = mysqli_fetch_assoc($dbResult);
      if($row['nbr'] == 0){
          return false;
      }
      return true;
    }

    // fonction permettant d'enlever le like du compte actuel sur la recette en paramètre
    public function removeLikePeople($id, $profil){
      $query = "DELETE FROM `like` WHERE idRecette = '" . $id . "' AND personne = '" . $profil . "'";
      if (!($dbResult = mysqli_query($this->myDbLink, $query))){
          return false;
      }
      return true;
    }

}

?>
