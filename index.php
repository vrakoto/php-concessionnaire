<?php
session_start();
$connexion = $_SESSION['id'] ?? '';
$root = __DIR__ . DIRECTORY_SEPARATOR;
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
$action = $_REQUEST['action'];

// AJAX
require_once $root . 'ajax' . DIRECTORY_SEPARATOR . 'index.php';

require_once $element . 'header.php';

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

    case 'parcourir':
        if (!isset($_REQUEST['type'])) { // Si type URL non spécifié alors affiche tous par défaut
            header("Location:index.php?action=parcourir&type=tous");
            exit();
        }
        $nbVehics = (int)count($pdo->getLesVehicules());
        $nbAutomobile = (int)count($pdo->getLesVehicules('automobile'));
        $nbDeuxRoues = (int)count($pdo->getLesVehicules('deuxRoues'));
        $nbEDPM = (int)count($pdo->getLesVehicules('edpm'));

        $type = htmlentities($_REQUEST['type']);
        
        $lesVehicules = $pdo->getLesVehicules($type);
        
        $lesMarques = $pdo->getLesMarques($type);
        $lesEnergies = $pdo->getLesEnergies();
        $lesRegions = $pdo->getLesRegions();
        require_once $vues . 'parcourir.php';
    break;



    case 'vendre':
        if ($connexion) {
            $lesTypes = $pdo->getLesTypes();
            $lesTransmissions = $pdo->getLesTransmissions();
            $lesEnergies = $pdo->getLesEnergies();
            $lesRegions = $pdo->getLesRegions();

            if (isset($_POST['image'], $_POST['type'], $_POST['marque'], $_POST['modele'], $_POST['annee'], $_POST['boite'], $_POST['km'], $_POST['energie'], $_POST['region'], $_POST['description'], $_POST['prix'])) {
                require_once $bdd . 'Vente.php';
                $image = htmlentities($_POST['image']);
                $type = htmlentities($_POST['type']);
                $marque = htmlentities($_POST['marque']);
                $modele = htmlentities($_POST['modele']);
                $annee = htmlentities($_POST['annee']);
                $boite = htmlentities($_POST['boite']);
                $km = htmlentities($_POST['km']);
                $energie = htmlentities($_POST['energie']);
                $region = htmlentities($_POST['region']);
                $description = htmlentities($_POST['description']);
                $prix = (int)$_POST['prix'];
    
                $inscription = new Vente($connexion, $image, $type, $marque, $modele, $annee, $boite, $km, $energie, $region, $description, $prix);
                if ($inscription->verifierVente()) {
                    $inscription->vendre();
                    header('Location:index.php?action=parcourir&type=tous');
                    exit();
                } else {
                    $lesRegions = $pdo->getLesRegions();
                    $erreur = "Formulaire de vente incorrecte";
                    $erreurs = $inscription->getErreurs();
                    require_once $vues . 'vendre.php';
                }
            }
            require_once $vues . 'vendre.php';
        } else {
            require_once $vues . '404.php';
        }
    break;


    case 'deconnexion':
        unset($_SESSION['id']);
        header('Location:index.php?action=accueil');
        exit();
    break;

    default:
        require_once $vues . '404.php';
    break;
}

require_once $element . 'footer.php';