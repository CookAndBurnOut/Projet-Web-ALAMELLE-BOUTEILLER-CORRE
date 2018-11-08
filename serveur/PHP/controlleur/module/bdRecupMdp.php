<?php

// Module gérant la base de données contenant toutes les informations de la récupération de mot de passe
class bdRecupMdp 
{
    private $myDbLink;

    public function __construct() {
      $this->myDbLink = mysqli_connect('mysql-cookandburnout.alwaysdata.net', '167560', '5FruitsEtLegumes')
      or die();
      mysqli_select_db($this->myDbLink , 'cookandburnout_bdcookburn')
      or die();
    }

    // fonction permettant d'ajouter dans la base le lien pour rénitialiser son mot de passe et le nom du compte
    public function insertUid($uid, $idUser) {
        $query = 'INSERT INTO recupMdp (uid, idUser) VALUES (\'' . $uid . '\',\'' . $idUser . '\')';
        if (!($dbResult = mysqli_query($this->myDbLink, $query))) {
            return false;
        }
        return true;
    }

    // fonction permettant de récupérer le nom du compte en fonction du lien de rénitialisation
    public function getIdUserByUid($uid){
      $query = 'SELECT idUser FROM recupMdp WHERE uid = \'' . $uid . '\'';
      $dbResult = mysqli_query($this->myDbLink, $query);

      $row = mysqli_fetch_assoc($dbResult);
      $temp = $row['idUser'];
      return $temp;
    }

    // fonction permettant de vérifier si le lien mis en paramètre existe dans la base de données
    public function isExistByUid($uid){
      $query = 'SELECT COUNT(*) AS nbr FROM recupMdp WHERE uid = "' . $uid .'"';
      if (!($dbResult = mysqli_query($this->myDbLink, $query))){
          return false;
      }
      $row = mysqli_fetch_assoc($dbResult);
      if($row['nbr'] == 0){
          return false;
      }
      return true;
    }

    // fonction permettant de supprimer un tuple par le lien mis en paramètre
    public function deleteByUid($uid){
      $query = "DELETE FROM `recupMdp` WHERE uid = '" . $uid . "'";
      if (!($dbResult = mysqli_query($this->myDbLink, $query))){
          return false;
      }
      return true;
    }
}
