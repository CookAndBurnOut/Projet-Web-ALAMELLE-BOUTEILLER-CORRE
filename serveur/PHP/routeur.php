<?php
// inclusion de tous les controlleurs nécessaires pour le site
include_once 'controlleur/connexion.php';
include_once 'controlleur/creationRecette.php';
include_once 'controlleur/affichageRecette.php';
include_once 'controlleur/burn.php';
include_once 'controlleur/recette.php';
include_once 'controlleur/motDePasse.php';
include_once 'controlleur/parametre.php';
include_once 'controlleur/utilisateur.php';

class Routeur
{
    public static function dispatch($json)
    {
        // switch case qui appelle les fonctions en fonctions de la valeur de $json['controlleur']
        switch ($json['controlleur']) {
            case 'creationRecette':
                call_user_func_array(array('creationRecette', 'creeUneRecette'), array(json_encode($json['post'])));
                break;
            case 'affichageRecette':
                call_user_func_array(array('affichageRecette', 'echoJsonRecette'), array($json['post']['id']));
                break;
            case 'connection':
                call_user_func_array(array('connexion', 'test'), array($json['post']['login'], $json['post']['mdp']));
                break;
            case 'isConnect':
                call_user_func(array('connexion', 'isConnected'));
                break;
            case 'isAdmin':
                call_user_func(array('connexion', 'isAdmin'));
                break;
            case 'deconnection':
                call_user_func(array('connexion', 'deconnection'));
                break;
            case 'addBurn':
                call_user_func_array(array('burn', 'addBurn'), array($json['post']['idRecette']));
                break;
            case 'removeBurn':
                call_user_func_array(array('burn', 'removeBurn'), array($json['post']['idRecette']));
                break;
            case 'allReadyBurn':
                call_user_func_array(array('burn', 'allReadyBurn'), array($json['post']['idRecette']));
                break;
            case 'lastRecettePublic':
                call_user_func(array('recettes', 'getLastPublicRecette'));
                break;
            case 'searchRecette':
                call_user_func_array(array('recettes', 'searchRecette'), array($json['post']['value']));
                break;
            case 'getRecetteUser':
                call_user_func(array('recettes', 'getRecetteUser'));
                break;
           case 'removeRecetteUser':
                call_user_func_array(array('recettes', 'removeRecetteUser'), array($json['post']['id']));
                break;
           case 'isPublicRecette':
                call_user_func_array(array('recettes', 'isPublicRecette'), array($json['post']['id']));
                break;
            case 'oublieMdp':
                call_user_func_array(array('mdp', 'recuperation'), array($json['post']['email']));
                break;
            case 'verifUid':
                call_user_func_array(array('mdp', 'verifUid'), array($json['post']['uid']));
                break;
            case 'changeMdpParLien':
                call_user_func_array(array('mdp', 'changerMdpParLien'), array($json['post']['uid'], $json['post']['mdp']));
                break;
            case 'getParametre':
                call_user_func_array(array('parametre', 'getParametreByName'), array($json['post']['name']));
                break;
            case 'setParametre':
                call_user_func_array(array('parametre', 'setParametreByName'), array($json['post']['name'], $json['post']['value']));
                break;
            case 'addUser':
                call_user_func_array(array('utilisateur', 'addUtilisateur'), array($json['post']['email'], $json['post']['login'], $json['post']['nom'], $json['post']['mdp']));
                break;
            case 'getAllUser':
                call_user_func(array('utilisateur', 'getAllUtilisateur'));
                break;
            case 'removeUser':
                call_user_func_array(array('utilisateur', 'removeUtilisateur'), array($json['post']['id']));
                break;
            case 'changementMdp':
                call_user_func_array(array('utilisateur', 'changerMdp'), array($json['post']['mdp']));
                break;
            default:
                failJsonReturn();
                break;
        }
    }
}
