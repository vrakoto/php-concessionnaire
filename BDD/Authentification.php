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
}