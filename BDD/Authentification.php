<?php

use function PHPSTORM_META\type;

class Authentification {
    protected $pdo;

    function __construct()
    {
        $this->pdo = new PDO('mysql:host=localhost;dbname=concessionnairev2', 'root', 'root', [
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
            $req .= " marque FROM vehicule WHERE status = 'VENTE'"; // Affiche TOUTES les marques peu importe le type
            $p = $this->pdo->query($req);
        } else {
            $req .= " marque FROM vehicule WHERE type = :type AND status = 'VENTE'";
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
        $req = "SELECT DISTINCT modele FROM vehicule WHERE status = 'VENTE'";

        if ($type !== 'tous' && $marque !== 'tous') {
            $req .= " AND type = :type AND marque = :marque";
            $elements['type'] = $type;
            $elements['marque'] = $marque;

        } else if ($type !== 'tous') {
            $req .= " AND type = :type";
            $elements['type'] = $type;

        } else if ($marque !== 'tous') {
            $req .= " AND marque = :marque";
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

    function getVehiculesUtilisateur(string $idUtilisateur, string $status): array
    {
        $req = "SELECT * FROM vehicule
                WHERE vendeur = :idUtilisateur
                AND status = :status
                ORDER BY publication";
        $p = $this->pdo->prepare($req);
        $p->execute([
            'idUtilisateur' => $idUtilisateur,
            'status' => $status
        ]);

        return $p->fetchAll();
    }

    function getVehiculesVendusUtilisateur(string $idUtilisateur): array
    {
        $req = "SELECT idVehicule FROM demande_achat
                WHERE vendeur = :idUtilisateur
                AND status = 'ACCEPTE'
                ORDER BY dateDemande";
        $p = $this->pdo->prepare($req);
        $p->execute([
            'idUtilisateur' => $idUtilisateur
        ]);

        $vehicules = [];
        foreach ($p->fetchAll() as $vehicule) {
            $vehicules[] = $this->getLeVehicule($vehicule['idVehicule']);
        }

        return $vehicules;
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
        $req .= " AND status = 'VENTE'
                ORDER BY publication DESC";
        
        $p = $this->pdo->prepare($req);
        $p->execute($searchNotNull);
        return $p->fetchAll();
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

    
    function listeVentesInteresses(): array
    {
        $req = "SELECT c.idVehicule, c.auteur,
                v.vendeur, v.marque, v.modele, v.annee
                FROM conversation c
                JOIN vehicule v on c.idVehicule = v.id
                WHERE c.idVehicule NOT IN (SELECT id FROM vehicule WHERE vendeur = :currentUser)
                AND c.auteur = :currentUser
                GROUP BY c.idVehicule, c.auteur
                ORDER BY c.date";

        $p = $this->pdo->prepare($req);
        $p->execute([
            'currentUser' => $_SESSION['id']
        ]);

        return $p->fetchAll();
    }
    
    /**
     * Soit contactInteresses OU mesContact(ou autre chose)
     *
     */
    function getLesContacts(string $typeContact, int $idVehicule): array
    {
        if ($typeContact === 'contactInteresse') {
            $req = "SELECT c.idVehicule,
                    v.vendeur, v.marque, v.modele, v.annee
                    FROM conversation c
                    JOIN vehicule v on c.idVehicule = v.id
                    WHERE c.idVehicule NOT IN (SELECT id FROM vehicule WHERE vendeur = :currentUser AND id = :idVehicule)
                    AND c.auteur = :currentUser
                    GROUP BY c.auteur
                    ORDER BY c.date";
        } else {
            $req = "SELECT c.idVehicule, c.auteur as client,
                    v.marque, v.modele, v.annee
                    FROM conversation c
                    JOIN vehicule v on c.idVehicule = v.id
                    WHERE c.idVehicule IN (SELECT id FROM vehicule WHERE vendeur = :currentUser AND id = :idVehicule)
                    AND NOT c.auteur = :currentUser
                    GROUP BY c.auteur
                    ORDER BY c.date";
        }

        $p = $this->pdo->prepare($req);
        $p->execute([
            'currentUser' => $_SESSION['id'],
            'idVehicule' => $idVehicule
        ]);

        return $p->fetchAll();
    }

    function getConversation(string $idVehicule, string $idContact): array
    {
        $req = "SELECT c.auteur, c.destinataire, c.message, c.date,
                v.vendeur
                FROM conversation c
                JOIN vehicule v on c.idVehicule = v.id
                WHERE (c.auteur = :currentUser AND c.destinataire = :idContact AND c.idVehicule = :idVehicule)
                OR (c.auteur = :idContact AND c.destinataire = :currentUser AND c.idVehicule = :idVehicule)";

        $p = $this->pdo->prepare($req);
        $p->execute([
            'idVehicule' => $idVehicule,
            'idContact' => $idContact,
            'currentUser' => $_SESSION['id'] ?? ''
        ]);

        return $p->fetchAll();
    }

    function envoyerMessage(string $idVehicule, string $destinataire, string $message): bool
    {
        $req = "INSERT INTO conversation (idVehicule, auteur, destinataire, message) VALUES (:idVehicule, :auteur, :destinataire, :message)";
        $p = $this->pdo->prepare($req);
        return $p->execute([
            'idVehicule' => $idVehicule,
            'auteur' => $_SESSION['id'],
            'destinataire' => $destinataire,
            'message' => $message
        ]);
    }

    function demanderAchat(string $idVehicule, string $proprio): bool
    {
        $req = "INSERT INTO demande_achat (idVehicule, vendeur, idClient) VALUES (:idVehicule, :proprio, :idClient)";
        $p = $this->pdo->prepare($req);
        return $p->execute([
            'idVehicule' => $idVehicule,
            'proprio' => $proprio,
            'idClient' => $_SESSION['id']
        ]);
    }

    function statusDemandeAchat(string $idVehicule, string $idClient): string
    {
        $req = "SELECT status FROM demande_achat WHERE idVehicule = :idVehicule AND idClient = :idClient";
        $p = $this->pdo->prepare($req);
        $p->execute([
            'idVehicule' => $idVehicule,
            'idClient' => $idClient
        ]);

        return $p->fetch()['status'] ?? '';
    }

    function actionAchat(string $idVehicule, string $idClient, string $decision): bool
    {
        if ($decision === 'ACCEPTE') {
            $this->changerProprio($idVehicule, $idClient);
            $this->changerStatusVehicule($idVehicule, 'VENDU');
        }

        $req = "UPDATE demande_achat SET status = :status WHERE idVehicule = :idVehicule AND idClient = :idClient";
        $p = $this->pdo->prepare($req);
        return $p->execute([
            'idVehicule' => $idVehicule,
            'idClient' => $idClient,
            'status' => $decision
        ]);
    }

    function changerProprio(string $idVehicule, string $idClient): bool
    {
        $req = "UPDATE vehicule SET vendeur = :idClient WHERE id = :idVehicule";
        $p = $this->pdo->prepare($req);
        return $p->execute([
            'idClient' => $idClient,
            'idVehicule' => $idVehicule
        ]);
    }

    function changerStatusVehicule(string $idVehicule, string $status): bool
    {
        $req = "UPDATE vehicule SET status = :status WHERE id = :idVehicule";
        $p = $this->pdo->prepare($req);
        return $p->execute([
            'idVehicule' => $idVehicule,
            'status' => $status,
        ]);
    }
}