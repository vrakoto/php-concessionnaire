<?php

class Authentification {
    protected $pdo;

    function __construct()
    {
        $this->pdo = new PDO('mysql:host=localhost;dbname=concessionnaireV2', 'root', 'root', [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
    }
    
    /**
     * Vérifie l'authentification
     *
     */
    function verifierAuth(string $id, string $mdp): bool
    {
        $req = "SELECT * FROM client WHERE id = :id AND mdp = :mdp";
        $p = $this->pdo->prepare($req);
        $p->execute([
            'id' => $id,
            'mdp' => $mdp,
        ]);

        return !empty($p->fetchAll());
    }
    
    /**
     * Retourne tous les véhicules (ou seulement par type si spécifié)
     *
     */
    function getLesVehicules(string $typeVehic = NULL): array
    {
        switch ($typeVehic) {
            case 'automobile':
                $req = "SELECT * FROM vehicule WHERE type = 'automobile'";
            break;

            case 'deuxRoues':
                $req = "SELECT * FROM vehicule WHERE type = 'deux roues'";
            break;

            case 'edpm':
                $req = "SELECT * FROM vehicule WHERE type = 'edpm'";
            break;
            
            default:
                $req = "SELECT * FROM vehicule";
            break;
        }

        $req .= " ORDER BY publication DESC";

        $p = $this->pdo->query($req);
        return $p->fetchAll();
    }
}