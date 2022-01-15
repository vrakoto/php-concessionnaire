<?php

class Inscription extends Authentification {
    private $id;
    private $nom;
    private $prenom;
    private $ville;
    private $mdp;
    private $mdp_confirm;

    function __construct(string $id, string $nom, string $prenom, string $ville, string $mdp, string $mdp_confirm)
    {
        parent::__construct(); // Hériter le PDO
        $this->id = $id;
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->ville = $ville;
        $this->mdp = $mdp;
        $this->mdp_confirm = $mdp_confirm;
    }

    function identifiantExistant(string $id): bool
    {
        $req = "SELECT id FROM client WHERE id = :id";
        $p = $this->pdo->prepare($req);
        $p->execute([
            'id' => $id
        ]);

        return !empty($p->fetch());
    }

    function verifierInscription(): bool
    {
        return empty($this->getErreurs());
    }

    function getErreurs(): array
    {
        $erreurs = [];
        if (strlen($this->id) < 2) {
            $erreurs['id'] = "Identifiant trop court";
        }

        if ($this->identifiantExistant($this->id)) {
            $erreurs['id'] = "Identifiant déjà prit";
        }
        
        if (strlen($this->nom) < 2) {
            $erreurs['nom'] = "Nom trop court";
        }
        
        if (strlen($this->prenom) < 2) {
            $erreurs['prenom'] = "Prenom trop court";
        }
        

        if (strlen($this->ville) < 2) {
            $erreurs['ville'] = "Ville trop courte";
        }

        if (strlen($this->mdp) < 2) {
            $erreurs['mdp'] = "Mot de passe trop court";
        }

        if ($this->mdp !== $this->mdp_confirm) {
            $erreurs['mdp'] = "Les mots de passe ne correspondent pas";
        }

        return $erreurs;
    }

    function inscrire(): bool
    {
        $req = "INSERT INTO client (id, nom, prenom, ville, mdp) VALUES (:id, :nom, :prenom, :ville, :mdp)";
        $p = $this->pdo->prepare($req);
        return $p->execute([
            'id' => $this->id,
            'nom' => $this->nom,
            'prenom' => $this->prenom,
            'ville' => $this->ville,
            'mdp' => password_hash($this->mdp,  PASSWORD_DEFAULT, ['cost' => 12])
        ]);
    }
}