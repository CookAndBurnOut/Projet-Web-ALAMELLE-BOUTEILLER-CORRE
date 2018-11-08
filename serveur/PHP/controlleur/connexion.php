<?php

// inclusion de tous les modules nécessaires pour ce controlleur
require_once 'module/bdUser.php';

// controlleur qui gère les connexions et status des utilisateurs
class connexion {

    // fonction permettant de vérifier le pseudo et le mot de passe mis par l'utilisateur pour se connecter
    public function test($login, $password) {
      session_start();
	    //test regex du pseudo
	    // if (!mb_ereg_match('^[a-zA-Z]{3,20}$', $pseudo))
	    //     header('Location: ../test.php?action=Connexion_ErreurRegex');
	    $passwordMd5 = md5($password);
      $bdUser = new bdUser();
      $list = $bdUser->connexion($login, $passwordMd5);
      if ($list != null) {
          $_SESSION['statut'] = 'connected';
          $_SESSION['userId'] = $list['id'];
          $_SESSION['profil'] = $list['login'];
          $_SESSION['email'] = $list['email'];
          $_SESSION['nom'] = $list['nom'];
          $_SESSION['grade'] = $list['grade'];
          winJsonReturn();
      } else {
          $_SESSION['statut'] = 'disconect';
          failJsonReturn();
      }
    }

    // foction permettant de se deconnecter
    public function deconnection(){
      session_start();
      $_SESSION['statut'] = 'disconect';
      winJsonReturn();
    }

    // fonction qui retourne la valeur du statut (soit connected soit disconect)
    public function isConnected(){
      session_start();
      $jsonStr = '{"statut":"' . $_SESSION['statut'] . '"}';
      strJsonReturn($jsonStr);
    }

    // fonction qui retourne la valeur du grade de l'utilisateur
    public function isAdmin(){
      session_start();
      $jsonStr = '{"grade":"' . $_SESSION['grade'] . '"}';
      strJsonReturn($jsonStr);
    }
}
