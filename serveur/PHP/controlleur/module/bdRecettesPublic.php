<?php

// Module gérant la base de données contenant les recettes publiques
class bdRecettesPublic 
{
    private $myDbLink;
    private $insertId;

    public function __construct() 
    {
        $this->myDbLink = mysqli_connect('mysql-cookandburnout.alwaysdata.net', '167560', '5FruitsEtLegumes')
        or die();
        mysqli_select_db($this->myDbLink , 'cookandburnout_bdcookburn')
        or die();
    }

    // fonction permettant d'insérer une recette dans la base de données
    public function addRecettePublic($id) {
      $query = 'INSERT INTO `recettePublic`(`idRecette`) VALUES (' . $id . ')';
      if(!(mysqli_query($this->myDbLink, $query))){
        return false;
      }
      return true;
    }

    // fonction permettant de retirer une recette de la base de données
    public function removeRecettePublic($id) {
      $query = 'DELETE FROM `recettePublic` WHERE idRecette = ' . $id;
      if(!(mysqli_query($this->myDbLink, $query))){
        return false;
      }
      return true;
    }

    // fonction permettant de savoir si une recette existe dans la base de données
    public function isExist($id){
        $query = 'SELECT COUNT(*) AS nbr FROM recettePublic WHERE idRecette = ' . $id;
        if (!($dbResult = mysqli_query($this->myDbLink, $query))){
            return false;
        }
        $row = mysqli_fetch_assoc($dbResult);
        if($row['nbr'] == 0){
            return false;
        }
        return true;
    }

    // fonction permettant de récupérer la dernière recette rendue publique c'est-à-dire rajouter à la base de données et de la retourner
    public function getIdLastPublicRecette(){
      $query = 'SELECT MAX(id) AS nbr FROM recettePublic';
      if (!($dbResult = mysqli_query($this->myDbLink, $query))){
          return -2;
      }
      $row = mysqli_fetch_assoc($dbResult);
      if($row['nbr'] == 0){
          return -1;
      }
      return $row['nbr'];
    }

    // fonction permettant de retourner une recette trouvée par son id
    public function getIdRecette($id){
      $query = 'SELECT idRecette FROM recettePublic WHERE id = ' . $id;
      if (!($dbResult = mysqli_query($this->myDbLink, $query))){
          return -2;
      }
      $row = mysqli_fetch_assoc($dbResult);
      if($row['idRecette'] == 0){
          return -1;
      }
      return $row['idRecette'];
    }


}


?>
