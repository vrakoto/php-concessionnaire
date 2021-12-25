<?php

class Vente extends Authentification {
    private $vendeur;
    private $image;
    private $type;
    private $marque;
    private $modele;
    private $annee;
    private $boite;
    private $km;
    private $energie;
    private $region;
    private $description;
    private $prix;

    function __construct(string $vendeur, string $image, string $type, string $marque, string $modele, string $annee, string $boite, string $km, string $energie, string $region, string $description, int $prix)
    {
        parent::__construct(); // Hériter le PDO
        $this->vendeur = $vendeur;
        $this->image = $image;
        $this->type = $type;
        $this->marque = $marque;
        $this->modele = $modele;
        $this->annee = $annee;
        $this->boite = $boite;
        $this->km = $km;
        $this->energie = $energie;
        $this->region = $region;
        $this->description = $description;
        $this->prix = $prix;
    }

    function verifierVente(): bool
    {
        return empty($this->getErreurs());
    }

    function getErreurs(): array
    {
        $erreurs = [];
        if (array_search($this->type, array_column(parent::getLesTypes(), 'id')) === FALSE) {
            $erreurs['type'] = "Type de véhicule invalide";
        }
        
        if (strlen($this->marque) < 2) {
            $erreurs['marque'] = "Marque trop courte";
        }
        
        if (strlen($this->modele) < 2) {
            $erreurs['modele'] = "Modele trop court";
        }
        

        if ((int)$this->annee <= 1920 || (int)$this->annee > date('Y')) {
            $erreurs['annee'] = "Année invalide";
        }

        if (array_search($this->boite, array_column(parent::getLesTransmissions(), 'id')) === FALSE) {
            $erreurs['transmission'] = "Boite de transmission invalide";
        }

        if ($this->km < 0 || $this->km > 5000000) {
            $erreurs['km'] = "Kilométrage inférieur à 0 ou beaucoup trop élevé";
        }

        if (array_search($this->energie, array_column(parent::getLesEnergies(), 'id')) === FALSE) {
            $erreurs['energie'] = "Energie du véhicule invalide";
        }

        if (array_search($this->region, array_column(parent::getLesRegions(), 'id')) === FALSE) {
            $erreurs['region'] = "Région invalide";
        }

        if ((int)$this->km < 0 || (int)$this->km > 10000000) {
            $erreurs['prix'] = "Prix inférieur à 0 ou beaucoup trop élevé";
        }

        return $erreurs;
    }

    function vendre(): bool
    {
        $req = "INSERT INTO vehicule (vendeur, imagePrincipale, type, marque, modele, annee, transmission, km, energie, region, description, prix) VALUES (:vendeur, :image, :type, :marque, :modele, :annee, :boite, :km, :energie, :region, :description, :prix)";
        $p = $this->pdo->prepare($req);
        return $p->execute([
            'vendeur' => $this->vendeur,
            'image' => $this->image,
            'type' => $this->type,
            'marque' => $this->marque,
            'modele' => $this->modele,
            'annee' => $this->annee,
            'boite' => $this->boite,
            'km' => $this->km,
            'energie' => $this->energie,
            'region' => $this->region,
            'description' => $this->description,
            'prix' => $this->prix,
        ]);
    }
}