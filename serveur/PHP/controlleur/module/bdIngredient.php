<?php

// Module gérant la base de données contenant toutes les informations consernant les ingrédients
class bdIngredient
{

    private $myDbLink;

    public function __construct()
    {
    	//connection a la bd
        $this->myDbLink = mysqli_connect('mysql-cookandburnout.alwaysdata.net', '167560', '5FruitsEtLegumes')
        or die();
        mysqli_select_db($this->myDbLink , 'cookandburnout_bdcookburn')
        or die();
        //parent::__construct($myPseudo, $myMdp, $myDbLink);
    }

    // fonction permettant d'ajouter un ingrédient à la base de données
    public function addNewIngredient($id, $nom, $quantitee, $type) {
			$nom = mysqli_escape_string($this->myDbLink, $nom);
    	$query = 'INSERT INTO ingredients (idRecette, nom, quantitee, type) VALUES (' . $id . ', "' . $nom . '", ' . $quantitee . ', "' . $type . '")';
      if(mysqli_query($this->myDbLink, $query)){
    		return true;
    	}else{
    		return false;
    	}
    }

    // fonction permettant de définir l'id de l'ingrédient
    public function setInsertId($id){
        $this->insertId = $id;
    }

    // fonction permettant de récupérer les ingrédients dans le bon ordre avec leur contenu
    public function getTupleByIdIngredient($id){
        $query = 'SELECT * FROM ingredients WHERE idRecette = ' . $id;
        $dbResult = mysqli_query($this->myDbLink, $query);

        $temp = array();

        while ($row = mysqli_fetch_assoc($dbResult)) {
           $temp[] = array('nom' => $row["nom"], 'quantitee' => $row['quantitee'], 'type' => $row['type']);
        }
        return $temp;
    }

}

?>
