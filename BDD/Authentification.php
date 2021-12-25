<?php

use function PHPSTORM_META\type;

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

    function getLesTypes(): array
    {
        $req = "SELECT id FROM type";
        $p = $this->pdo->query($req);
        return $p->fetchAll();
    }

    function getLesMarques(string $type): array
    {
        $req = "SELECT DISTINCT";
        if (array_search($type, array_column($this->getLesTypes(), 'id')) === FALSE) {
            $req .= " marque FROM vehicule"; // Affiche TOUTES les marques peu importe le type
            $p = $this->pdo->query($req);
        } else {
            $req .= " marque FROM vehicule WHERE type = :type";
            $p = $this->pdo->prepare($req);
            $p->execute([
                'type' => $type
            ]);
        }

        return $p->fetchAll();
    }

    function getLesModeles(string $type, string $marque): array
    {
        $elements = [];
        $req = "SELECT DISTINCT modele FROM vehicule";

        if ($type !== 'tous' && $marque !== 'tous') {
            $req .= " WHERE type = :type AND marque = :marque";
            $elements['type'] = $type;
            $elements['marque'] = $marque;

        } else if ($type !== 'tous') {
            $req .= " WHERE type = :type";
            $elements['type'] = $type;

        } else if ($marque !== 'tous') {
            $req .= " WHERE marque = :marque";
            $elements['marque'] = $marque;
        }

        $p = $this->pdo->prepare($req);

        $p->execute($elements);
        return $p->fetchAll();
    }


    function getLesTransmissions(): array
    {
        $req = "SELECT id FROM transmission";
        $p = $this->pdo->query($req);
        return $p->fetchAll();
    }

    function getLesEnergies(): array
    {
        $req = "SELECT id FROM energie";
        $p = $this->pdo->query($req);
        return $p->fetchAll();
    }

    function getLesRegions(): array
    {
        $req = "SELECT id FROM region";
        $p = $this->pdo->query($req);
        return $p->fetchAll();
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
                $req = "SELECT * FROM vehicule WHERE type = 'deuxRoues'";
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

    function rechercherVehicule(array $champs): array
    {
        $searchNotNull = [];
        $req = "SELECT * FROM vehicule WHERE type is NOT NULL";
        foreach ($champs as $champ => $valeur) {
            if ($valeur !== 'tous' && $valeur !== 'null' && $valeur !== 0) {
                $searchNotNull[$champ] = $valeur;
                $req .= " AND $champ = :$champ";
            }
        }
        
        $p = $this->pdo->prepare($req);
        $p->execute($searchNotNull);
        return $p->fetchAll();
    }
}