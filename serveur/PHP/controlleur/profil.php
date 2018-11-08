<?php

// inclusion de tous les modules nécessaires pour ce controlleur
include_once 'module/bdUser.php';

// controlleur qui gère le profil de l'utilisateur
class Profil
{
	// fonction permettant de changer de mot de passe
	function changementMdp($newMdp,$confirmMdp)
	{
		if ($newMdp == $confirmMdp) 
		{
			$bdUser = new bdUser();
			$bdUser->setMdp($newMdp, $_SESSION['profil']);
		}
		else
		{
			// message d'erreur
		}
	}

	function affiche()
	{
		echo '<label>mail : ' . $_SESSION['email'] . '</label>';
		echo '<label>pseudo : ' . $_SESSION['profil'] . '</label>';
	}
}