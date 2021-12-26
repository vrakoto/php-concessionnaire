<?php
$id = htmlentities($vehicule['id']);
$vendeur = htmlentities($vehicule['vendeur']);
$image = htmlentities($vehicule['imagePrincipale']);
$type = htmlentities($vehicule['type']);
$marque = htmlentities($vehicule['marque']);
$modele = htmlentities($vehicule['modele']);
$region = htmlentities($vehicule['region']);
$annee = (int)$vehicule['annee'];
$transmission = htmlentities($vehicule['transmission']);
$km = (int)$vehicule['km'];
$prix = (int)$vehicule['prix'];
$energie = htmlentities($vehicule['energie']);
$description = $vehicule['description'];
$publication = htmlentities($vehicule['publication']);