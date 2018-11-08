<?php

// Module gérant la base de données contenant les recettes
class bdRecettes {
    private $myDbLink;
    private $insertId;

    public function __construct() 
    {
        $this->myDbLink = mysqli_connect('mysql-cookandburnout.alwaysdata.net', '167560', '5FruitsEtLegumes')
        or die();
        mysqli_select_db($this->myDbLink , 'cookandburnout_bdcookburn')
        or die();
    }

    // fonction permettant de créer une nouvelle recette
    public function createNewRecette($proprietaire, $nom, $time, $type, $descriptionC, $descriptionL, $nbrPersonne, $url) {
      $nom = mysqli_escape_string($this->myDbLink, $nom);
      $descriptionC = mysqli_escape_string($this->myDbLink, $descriptionC);
      $descriptionL = mysqli_escape_string($this->myDbLink, $descriptionL);
      $url = mysqli_escape_string($this->myDbLink, $url);

      $query = 'INSERT INTO recettes (proprietaire, nom, nbrPersonne, descriptionC, descriptionL, time, type, url)  VALUES ("' . $proprietaire . '"," ' . $nom . '", ' . $nbrPersonne . ', "' . $descriptionC . '", "'
                        . $descriptionL . '", ' . $time . ',"' . $type . '","' . $url . '")';

      if(mysqli_query($this->myDbLink, $query)){
          $this->insertId = mysqli_insert_id($this->myDbLink);
			    return true;
		  }else{
			    return false;
		  }
    }

    // fonction permettant de retourner la valeur de la variable privée insertId
    public function getInsertId(){
        return $this->insertId;
    }

    // fonction permettant de supprimer une recette par son id
    function removeRecetteById($id){
      $query = "DELETE FROM `recettes` WHERE id = " . $id;
      if (!($dbResult = mysqli_query($this->myDbLink, $query))){
          return false;
      }
      return true;
    }

    // fonction permettant de récupérer toutes les données de la recette correspondante à l'id mis en paramètre et de les retourner
    public function getTupleByIdRecette($id){
        $query = 'SELECT * FROM recettes WHERE id = ' . $id;
        if (!($dbResult = mysqli_query($this->myDbLink, $query))){
            return null;
        }
        $row = mysqli_fetch_assoc($dbResult);
        $temp = array('proprietaire' => $row['proprietaire'], 'nom' => $row['nom'], 'time' => $row['time'],
                              'type' => $row['type'], 'descriptionC' => $row['descriptionC'], 'descriptionL' => $row['descriptionL'], 'nbrPersonne' => $row['nbrPersonne'], 'nbrBurn' => $row['nbrBurn']
                              , 'url' => $row['url']);
        return $temp;
    }

    // fonction permettant de récupérer toutes les données des recettes créées par le propriétaire mis en paramètre et de les retourner
    public function getTupleByProprietaire($proprietaire){
        $query = 'SELECT * FROM recettes WHERE proprietaire = "' . $proprietaire . '"';
        $dbResult = mysqli_query($this->myDbLink, $query);

        while ($row = mysqli_fetch_assoc($dbResult)) {
        $temp[] = array('id' => $row['id'], 'proprietaire' => $row["proprietaire"], 'nom' => $row['nom'], 'time' => $row['time'],
                            'type' => $row['type'], 'descriptionC' => $row['descriptionC'], 'descriptionL' => $row['descriptionL'], 'nbrPersonne' => $row['nbrPersonne'], 'nbrBurn' => $row['nbrBurn']
                            ,'url' => $row['url']);
        }
        return $temp;
    }

    // fonction permettant de rechercher les valeurs rentrées dans la barre de recherche dans la base de données et retourne les recettes qui correspondent à la recherche
    public function searchIdByValue($value){
      $query = 'select distinct recettes.id  from recettes, tags where recettes.id = tags.idRecette and (proprietaire = "'
            . $value . '" or nom = "'
            . $value . '" or tag = "'
            . $value . '")';
      $dbResult = mysqli_query($this->myDbLink, $query);
      while ($row = mysqli_fetch_assoc($dbResult)) {
          $temp[] = $row['id'];
      }
      return $temp;
    }

    // fonction permettant de retourner le nombre de like (burn) pour la recette qui l'id qui correspond a celui passé en paramètre
    public function getBurnByIdRecette($id){
        $query = 'SELECT nbrBurn FROM recettes WHERE id = ' . $id;
        $dbResult = mysqli_query($this->myDbLink, $query);

        $row = mysqli_fetch_assoc($dbResult);
        $temp = $row['nbrBurn'];
        return $temp;
    }

    // fonction permettant d'ajouter un like (burn) à une recette
    public function addBurn($id){
      $query = 'UPDATE recettes SET nbrBurn = nbrBurn + 1 WHERE id = ' . $id;
      if (!($dbResult = mysqli_query($this->myDbLink, $query))){
          return false;
      }
      return true;
    }

    // fonction permettant de retirer un like (burn) à une recette
    public function removeBurn($id){
      $query = 'UPDATE recettes SET nbrBurn = nbrBurn - 1 WHERE id = ' . $id;
      if (!($dbResult = mysqli_query($this->myDbLink, $query))){
          return false;
      }
      return true;
    }

    // fonction permettant de savoir si une recette existe
    public function isExist($id){
        $query = 'SELECT COUNT(*) AS nbr FROM recettes WHERE id = ' . $id;
        if (!($dbResult = mysqli_query($this->myDbLink, $query))){
            return false;
        }
        $row = mysqli_fetch_assoc($dbResult);
        if($row['nbr'] == 0){
            return false;
        }
        return true;
    }
}

?>
