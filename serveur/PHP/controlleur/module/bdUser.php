<?php

// Module gérant la base de données contenant tous les utilisateurs
class bdUser
{
    private $myDbLink;

    public function __construct()
    {
        $this->myPseudo = '167560';
        $this->myMdp = '5FruitsEtLegumes';
        $this->myDbLink = mysqli_connect('mysql-cookandburnout.alwaysdata.net', $this->myPseudo, $this->myMdp)
        or die();
        mysqli_select_db($this->myDbLink , 'cookandburnout_bdcookburn')
        or die();
    }

    // fonction permettant aux utilisateurs de se connecter
    function connexion($login, $password){
        $query = 'SELECT id, login, mdp, email, grade FROM users WHERE login =  \'' . $login . '\' AND mdp = \'' . $password . '\'';
        if (!($dbResult = mysqli_query($this->myDbLink, $query))){
            return null;
        }
        $row = mysqli_fetch_assoc($dbResult);
        if ($row == null) {
          return null;
        }else{
          $temp = array('id'=> $row['id'],'login' => $row['login'], 'nom' => $row['nom'], 'email' => $row['email'], 'grade' => $row['grade']);
          return $temp;
        }
    }

    // fonction permettant de retourner la liste de tous les utilisateurs
    function getAllUser(){
        $query = 'SELECT id, email, login, nom FROM users';
        if (!($dbResult = mysqli_query($this->myDbLink, $query))){
            return null;
        }
        $temp = array();
        while ($row = mysqli_fetch_assoc($dbResult)) {
           $temp[] = array('id' => $row['id'], 'email' => $row["email"], 'login' => $row['login'], 'nom' => $row['nom']);
        }
        return $temp;
    }

    // fonction permettant d'ajouter un utilisateur dans la base de données
    function addUser($email, $login, $nom, $password){
        $query = 'INSERT INTO `users`(`login`, `nom`, `mdp`, `email`) VALUES ("'. $login .'","'. $nom .'","'. $password .'","'. $email .'")';
        if (!($dbResult = mysqli_query($this->myDbLink, $query))){
            return false;
        }
        return true;
    }

    // fonction permettant de supprimer un utilisateur de la base de données
    function removeUser($id){
      $query = "DELETE FROM `users` WHERE id = " . $id;
      if (!($dbResult = mysqli_query($this->myDbLink, $query))){
          return false;
      }
      return true;
    }

    // fonction permettant de vérifier si un utilisateur existe en regardant les email
    function isExistByEmail($email){
        $query = 'SELECT COUNT(*) AS nbr FROM users WHERE email = \'' . $email . '\'';
        if (!($dbResult = mysqli_query($this->myDbLink, $query))){
            return false;
        }
        $row = mysqli_fetch_assoc($dbResult);
        if($row['nbr'] == 0){
            return false;
        }
        return true;
    }

    // fonction permettant de définir le mot de passe d'un utilisateur
    function setMdpById($newMdp, $id){
        $mdpCode = md5($newMdp);
        $query = 'UPDATE users set mdp="' . $mdpCode . '" WHERE id =' . $id;
        if (!($dbResult = mysqli_query($this->myDbLink, $query))){
            return false;
        }
        return true;
    }

    // fonction permettant de retourner un utilisateur
    function getIdByEmail($email){
        $query = 'SELECT id FROM users WHERE email = \'' . $email . '\'';
        $dbResult = mysqli_query($this->myDbLink, $query);

        $row = mysqli_fetch_assoc($dbResult);
        $temp = $row['id'];
        return $temp;
    }

    // fonction permettant de retourner une liste d'email
    function getEmail(){
        $query = 'SELECT email FROM users';
        $dbResult = mysqli_query($this->myDbLink, $query);
        $temp[] = array();
        $total = mysqli_num_rows($dbResult);
        if($total > 0)
        {
            while($row = mysqli_fetch_array($dbResult))
            {

                $temp[] = $row['email'];
            }
        }
        return $temp;
    }

    // fonction permettant de retourner une liste de pseudo
    function getPseudo() {
        $query = 'SELECT login FROM users';
        $dbResult = mysqli_query($this->myDbLink, $query);
        $temp[] = array();
        $total = mysqli_num_rows($dbResult);
        if($total > 0)
        {
            while($row = mysqli_fetch_array($dbResult))
            {

                $temp[] = $row['login'];
            }
        }
        return $temp;
    }
}
