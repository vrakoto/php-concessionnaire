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

    function getUtilisateur(string $idUtilisateur): array
    {
        $req = "SELECT avatar, nom, prenom, ville, dateCreation FROM client WHERE id = :id";
        $p = $this->pdo->prepare($req);
        $p->execute([
            'id' => $idUtilisateur
        ]);

        return $p->fetch();
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
        $req = "SELECT * FROM vehicule WHERE status = 'VENTE' AND type = ";
        switch ($typeVehic) {
            case 'automobile':
                $req .= "'automobile'";
            break;

            case 'deuxRoues':
                $req .= "'deuxRoues'";
            break;

            case 'edpm':
                $req .= "'edpm'";
            break;
            
            default:
                $req = "SELECT * FROM vehicule WHERE status = 'VENTE'";
            break;
        }
        $req .= " ORDER BY publication DESC";

        $p = $this->pdo->query($req);
        return $p->fetchAll();
    }

    function getLeVehicule(string $idVehic): array
    {
        $req = "SELECT * FROM vehicule WHERE id = :id";
        $p = $this->pdo->prepare($req);
        $p->execute([
            'id' => $idVehic
        ]);

        return $p->fetch();
    }

    function getMesVendus(): array
    {
        $req = "SELECT * FROM vehicule WHERE vendeur = :vendeur AND status = 'VENDU' ORDER BY publication";
        $p = $this->pdo->prepare($req);
        $p->execute([
            'vendeur' => $_SESSION['id']
        ]);

        return $p->fetchAll();
    }

    function getVehiculesUtilisateur(string $idUtilisateur): array
    {
        $req = "SELECT * FROM vehicule v
                WHERE vendeur = (SELECT id FROM client WHERE id = :idUtilisateur)
                ORDER BY publication DESC";
        $p = $this->pdo->prepare($req);
        $p->execute([
            'idUtilisateur' => $idUtilisateur
        ]);

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
        $req .= " ORDER BY publication DESC";
        
        $p = $this->pdo->prepare($req);
        $p->execute($searchNotNull);
        return $p->fetchAll();
    }

    function estMonVehicule(string $idVehicule): bool
    {
        $req = "SELECT id FROM vehicule WHERE id = :idVehicule AND vendeur = :idVendeur";
        $p = $this->pdo->prepare($req);
        $p->execute([
            'idVehicule' => $idVehicule,
            'idVendeur' => $_SESSION['id'] ?? '',
        ]);
        return !empty($p->fetch());
    }

    function supprimerVehicule(string $idVehicule): bool
    {
        $req = "DELETE FROM vehicule WHERE id = :idVehicule AND vendeur = :idVendeur";
        $p = $this->pdo->prepare($req);
        return $p->execute([
            'idVehicule' => $idVehicule,
            'idVendeur' => $_SESSION['id']
        ]);
    }

    function getMesContacts()
    {
        $req = "SELECT idVehicule, idClient, idVendeur FROM message
                WHERE :currentUser IN (idClient, idVendeur)
                GROUP BY idVehicule
                ORDER BY idClient, idVendeur";
        $p = $this->pdo->prepare($req);
        $p->execute([
            'currentUser' => $_SESSION['id'],
        ]);

        $lesContacts = $p->fetchAll();
        $contacts = []; // stock tous contacts sauf l'auth connecté

        foreach ($lesContacts as $contact) {
            if ($contact['idClient'] !== $_SESSION['id'] ) {
                $contacts[$contact['idVehicule']] = 
                [
                    'marque' => $this->getLeVehicule($contact['idVehicule'])['marque'],
                    'modele' => $this->getLeVehicule($contact['idVehicule'])['modele'],
                    'id' => $contact['idClient']
                ];
            } else if ($contact['idVendeur'] !== $_SESSION['id']) {
                $contacts[$contact['idVehicule']] = 
                [
                    'marque' => $this->getLeVehicule($contact['idVehicule'])['marque'],
                    'modele' => $this->getLeVehicule($contact['idVehicule'])['modele'],
                    'id' => $contact['idVendeur']
                ];
            }
        }
        return $contacts;
    }

    function getConversation(string $idVehicule, string $idUtilisateur): array
    {
        $req = "SELECT * FROM message
                WHERE idVehicule = :idVehicule
                AND (idClient = :idClient OR idClient = :idVendeur)
                AND (idVendeur = :idClient OR idVendeur = :idVendeur)";
        $p = $this->pdo->prepare($req);
        $p->execute([
            'idVehicule' => $idVehicule,
            'idClient' => $_SESSION['id'] ?? '',
            'idVendeur' => $idUtilisateur
        ]);

        return $p->fetchAll();
    }

    function envoyerMessage(string $idVehic, string $vendeur, string $message): bool
    {
        $req = "INSERT INTO message (idVehicule, idClient, idVendeur, message) VALUES (:idVehicule, :idClient, :idVendeur, :message)";
        $p = $this->pdo->prepare($req);
        return $p->execute([
            'idVehicule' => $idVehic,
            'idClient' => $_SESSION['id'],
            'idVendeur' => $vendeur,
            'message' => $message
        ]);
    }

}