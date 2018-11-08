<?php

// Module gérant la base de données contenant toutes les informations sur les étapes d'une recette
class bdEtape
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

    // fonction permettant d'ajouter une étape dans la base de données
    public function addNewEtape($id, $description, $ordre) 
    {
      $description = mysqli_escape_string($this->myDbLink, $description);
    	$query = 'INSERT INTO etapes (idRecette, description, ordre) VALUES (' . $id . ', "' . $description . '", ' . $ordre . ')';
      if(mysqli_query($this->myDbLink, $query))
      {
			     return true;
		  }else{
			     return false;
		  }
    }

    // fonction permettant de définir l'id d'une étape d'une recette
    public function setInsertId($id){
        $this->insertId = $id;
    }

    // fonction permettant de récupérer les étapes dans le bon ordre avec leur contenu
    public function getTupleByIdEtape($id){
        $query = 'SELECT * FROM etapes WHERE idRecette = ' . $id;
        $dbResult = mysqli_query($this->myDbLink, $query);

        $temp = array();

        while ($row = mysqli_fetch_assoc($dbResult)) {
            $temp[] = array('description' => $row["description"], 'ordre' => $row['ordre']);
        }
        return $temp;
    }

}

?>
