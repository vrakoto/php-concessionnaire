<?php
session_start();
$connexion = $_SESSION['id'] ?? '';
$root = __DIR__ . DIRECTORY_SEPARATOR;
$element = $root . 'elements' . DIRECTORY_SEPARATOR;
$vues = $root . 'vues' . DIRECTORY_SEPARATOR;
$bdd = $root . 'bdd' . DIRECTORY_SEPARATOR;
$fonctions = $root . 'fonctions' . DIRECTORY_SEPARATOR;
require_once $bdd . 'Authentification.php';
require_once $fonctions . 'helper.php';

$pdo = new Authentification;
require_once $element . 'header.php';

if (!isset($_REQUEST['action'])) {
    header('Location:index.php?action=accueil');
    exit();
}
$action = $_REQUEST['action'];
switch ($action) {
    case 'accueil':
        require_once $vues . 'accueil.php';
    break;

    case 'pageConnexion':
        if (empty($connexion)) {
            require_once $vues . 'connexion.php';
        } else {
            header('Location:index.php?action=accueil');
            exit();
        }
    break;

    case 'verifierConnexion':
        if (isset($_POST['id'], $_POST['mdp'])) {
            $id = htmlentities($_POST['id']);
            $mdp = htmlentities($_POST['mdp']);

            if ($pdo->verifierAuth($id, $mdp)) {
                $_SESSION['id'] = $id;
                header('Location:index.php?action=accueil');
                exit();
            } else {
                $erreur = "Authentification incorrecte";
                require_once $vues . 'connexion.php';
            }
        }
    break;

    case 'pageInscription':
        if (empty($connexion)) {
            require_once $vues . 'inscription.php';
        } else {
            header('Location:index.php?action=accueil');
            exit();
        }
    break;

    case 'verifierInscription':
        if (isset($_POST['id'], $_POST['nom'], $_POST['prenom'], $_POST['ville'], $_POST['mdp'], $_POST['mdp_confirm'])) {
            require_once $bdd . 'Inscription.php';
            $id = htmlentities($_POST['id']);
            $nom = htmlentities($_POST['nom']);
            $prenom = htmlentities($_POST['prenom']);
            $ville = htmlentities($_POST['ville']);
            $mdp = htmlentities($_POST['mdp']);
            $mdp_confirm = htmlentities($_POST['mdp_confirm']);

            $inscription = new Inscription($id, $nom, $prenom, $ville, $mdp, $mdp_confirm);
            if ($inscription->verifierInscription()) {
                $inscription->inscrire();
                header('Location:index.php?action=pageConnexion');
                exit();
            } else {
                $erreur = "Formulaire d'inscription incorrecte";
                $erreurs = $inscription->getErreurs();
                require_once $vues . 'inscription.php';
            }
        }
    break;

    case 'deconnexion':
        unset($_SESSION['id']);
        header('Location:index.php?action=accueil');
        exit();
    break;
    
    default:
        echo 404;
    break;
}

require_once $element . 'footer.php';