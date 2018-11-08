<?php
include_once 'bdRecupMdp.php';

// Module permettant de créer un lien quand l'utilisateur a perdu son mot de passe et qu'il veut le rénitialiser
class RecupMdp
{
    private $uid;
    private $finalUrl;
    public function __construct()
    {
        $this->uid = uniqid();
        $this->finalUrl = 'cookandburnout.alwaysdata.net/test.php?action=RecupMdp'.$this->uid;
        $bd = new bdRecupMdp('167560', '5FruitsEtLegumes', 'mysql-cookandburnout.alwaysdata.net');
        $bd->insertUid($this->uid);
    }
}
