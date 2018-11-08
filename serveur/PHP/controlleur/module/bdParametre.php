<?php

// Module gérant la base de données contenant des paramètres
class bdParametre 
{
    private $myDbLink;

    public function __construct()
    {
        $this->myDbLink = mysqli_connect('mysql-cookandburnout.alwaysdata.net', '167560', '5FruitsEtLegumes')
        or die();
        mysqli_select_db($this->myDbLink , 'cookandburnout_bdcookburn')
        or die();
    }

    // foction permettant de récupérer un paramètre par son nom
    public function getParametreValueByName($name){
        $query = 'SELECT value FROM parametre WHERE nom = "' . $name . '"';
        $dbResult = mysqli_query($this->myDbLink, $query);
        $row = mysqli_fetch_assoc($dbResult);
        $temp = $row['value'];
        return $temp;
    }

    // fonction permettant de définir le nom d'un paramètre
    public function setParametreValueByName($name, $value){
        $query = 'UPDATE `parametre` SET `value`= ' . $value . ' WHERE nom = "' . $name . '"';
        if(!($dbResult = mysqli_query($this->myDbLink, $query))){
            return false;
        }
        return true;
    }
}

?>
