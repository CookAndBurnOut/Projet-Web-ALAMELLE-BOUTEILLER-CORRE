<?php

// Module gérant la base de données contenant tous les tags de toutes les recettes
class bdTags
{
    private $myDbLink;
    private $insertId;

    public function __construct()
    {
    	$this->insertId = $id; //on set l'id de la recette a travailler

    	//connection a la bd
        $this->myPseudo = '167560';
        $this->myMdp = '5FruitsEtLegumes';
        $this->myDbLink = mysqli_connect('mysql-cookandburnout.alwaysdata.net', '167560', '5FruitsEtLegumes')
        or die();
        mysqli_select_db($this->myDbLink , 'cookandburnout_bdcookburn')
        or die();
        //parent::__construct($myPseudo, $myMdp, $myDbLink);
    }

    // fonction permettant d'ajouter un nouveau tag dans la base de données
    public function addNewTag($id, $tag) {
			$descriptionL = mysqli_escape_string($this->myDbLink, $tag);
    	$query = 'INSERT INTO tags (idRecette, tag) VALUES (' . $id . ', "' . $tag . '")';
        if(mysqli_query($this->myDbLink, $query)){
			return true;
		}else{
			return false;
		}
    }

    // fonction permettant de définir l'id d'un tag
    public function setInsertId($id){
        $this->insertId = $id;
    }

    //fonction permettant de récupérer tous les tags d'une seule recette
    public function getTupleByIdTag($id){
        $query = 'SELECT * FROM tags WHERE idRecette = ' . $id;
        $dbResult = mysqli_query($this->myDbLink, $query);

        $temp = array();

        while ($row = mysqli_fetch_assoc($dbResult)) {
            $temp[] = array('tag' => $row['tag']);
        }
        return $temp;
    }
}

?>
