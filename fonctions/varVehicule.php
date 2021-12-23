<?php
$type = htmlentities($vehicule['type']);
$marque = htmlentities($vehicule['marque']);
$modele = htmlentities($vehicule['modele']);
$region = htmlentities($vehicule['region']);
$prix = (int)$vehicule['prix'];

$description = $vehicule['description'];
$annee = (int)$vehicule['annee'];
$km = (int)$vehicule['km'];
$energie = htmlentities($vehicule['energie']);
$transmission = htmlentities($vehicule['transmission']);
?>