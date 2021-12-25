<?php
// Optionnel
/* session_start();
$connexion = $_SESSION['id'] ?? '';
$root = dirname(__DIR__) . DIRECTORY_SEPARATOR;
$element = $root . 'elements' . DIRECTORY_SEPARATOR;
$vues = $root . 'vues' . DIRECTORY_SEPARATOR;
$bdd = $root . 'bdd' . DIRECTORY_SEPARATOR;
$fonctions = $root . 'fonctions' . DIRECTORY_SEPARATOR;
$role = $root . 'role' . DIRECTORY_SEPARATOR;

require_once $bdd . 'Authentification.php';
require_once $fonctions . 'helper.php';
$pdo = new Authentification;

if (!isset($_REQUEST['action'])) {
    header('Location:index.php?action=accueil');
    exit();
}
$action = $_REQUEST['action']; */