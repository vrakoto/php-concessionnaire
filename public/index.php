<?php
session_start();
$connexion = $_SESSION['id'] ?? '';
$root = dirname(__DIR__) . DIRECTORY_SEPARATOR;

// Les dossiers
$element = $root . 'elements' . DIRECTORY_SEPARATOR;
$vues = $root . 'vues' . DIRECTORY_SEPARATOR;
$bdd = $root . 'bdd' . DIRECTORY_SEPARATOR;
$fonctions = $root . 'fonctions' . DIRECTORY_SEPARATOR;
$role = $root . 'role' . DIRECTORY_SEPARATOR;

require_once $bdd . 'Authentification.php';
require_once $fonctions . 'helper.php';
$pdo = new Authentification;

require_once $element . 'header.php';

if (!isset($_REQUEST['action'])) {
    header('Location:index.php?action=accueil');
    exit();
}
$action = htmlentities($_REQUEST['action']);

// Conserve la page lors connexion / deconnexion
$dontKeepURL = ["deconnexion", "connexion", "inscription"];
if (!in_array($action, $dontKeepURL)) {
    $_SESSION['url'] = $_SERVER['QUERY_STRING'];
}
$currentPage = explode('action=', $_SESSION['url'])[1];


// Gére les différents accès
$restrictions = ["vendre", "supprimerVehicule", "messagerie", "deconnexion"];
if ($connexion) {
    $restrictions = ["connexion", "inscription"];
}

if (in_array($action, $restrictions)) {
    header('Location:index.php?action=accueil');
    exit();
}

switch ($action) {
    case 'accueil':
        require_once $vues . 'accueil.php';
    break;

    case 'connexion':
        if (isset($_POST['id'], $_POST['mdp'])) {
            $id = htmlentities($_POST['id']);
            $mdp = htmlentities($_POST['mdp']);

            if ($pdo->verifierAuth($id, $mdp)) {
                $_SESSION['id'] = $id;
                header("Location:index.php?action=" . $currentPage);
                exit();
            } else {
                $erreur = "Authentification incorrecte";
            }
        }
        require_once $vues . 'connexion.php';
    break;

    case 'deconnexion':
        unset($_SESSION['id']);
        header("Location:index.php?action=" . $currentPage);
        exit();
    break;

    case 'inscription':
        if (isset($_POST['id'], $_POST['nom'], $_POST['prenom'], $_POST['ville'], $_POST['mdp'], $_POST['mdp_confirm'])) {
            require_once $bdd . 'Inscription.php';
            $id = htmlentities($_POST['id']);
            $nom = htmlentities($_POST['nom']);
            $prenom = htmlentities($_POST['prenom']);
            $ville = htmlentities($_POST['ville']);
            $mdp = htmlentities($_POST['mdp']);
            $mdp_confirm = htmlentities($_POST['mdp_confirm']);

            $inscription = new Inscription($id, $nom, $prenom, $ville, $mdp, $mdp_confirm);
            if (!$inscription->verifierInscription()) {
                $erreur = "Formulaire d'inscription incorrecte";
                $erreurs = $inscription->getErreurs();
            } else {
                $inscription->inscrire();
                header('Location:index.php?action=connexion');
                exit();
            }
        }
        require_once $vues . 'inscription.php';
    break;

    case 'parcourir':
        if (!isset($_GET['type'])) { // Si type URL non spécifié alors affiche tous par défaut
            header("Location:index.php?action=parcourir&type=tous");
            exit();
        }
        $type = htmlentities($_GET['type']);

        $nbVehics = (int)count($pdo->getLesVehicules());
        $nbAutomobile = (int)count($pdo->getLesVehicules('automobile'));
        $nbDeuxRoues = (int)count($pdo->getLesVehicules('deuxRoues'));
        $nbEDPM = (int)count($pdo->getLesVehicules('edpm'));
        
        $lesVehicules = $pdo->getLesVehicules($type);
        
        $lesMarques = $pdo->getLesMarques($type);
        $lesEnergies = $pdo->getLesEnergies();
        $lesRegions = $pdo->getLesRegions();
        require_once $vues . 'parcourir.php';
    break;


    case 'vendre':
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
            }
        }
        require_once $vues . 'vendre.php';
    break;

    // Consulter un véhicule
    case 'vehicule':
        $erreur = NULL;
        $idVehicule = htmlentities($_GET['id']);
        $vehicule = $pdo->getLeVehicule($idVehicule);
        require_once $fonctions . 'varVehicule.php';

        if (isset($_POST['message'])) {
            $vendeur = htmlentities($_GET['leVendeur']);
            $message = htmlentities($_POST['message']);
    
            if (empty($pdo->getUtilisateur($vendeur))) {
                $erreur = "Vendeur introuvable";
            }
    
            if (empty(trim($message))) {
                $erreur = "Le message est vide";
            }

            if (empty($erreur)) {
                try {
                    // Envoie le premier message au vendeur
                    $pdo->envoyerMessage($idVehicule, $vendeur, $message);
                    header('Location:index.php?action=vehicule&id=' . $idVehicule);
                    exit();
                } catch (PDOException $error) {
                    echo "Erreur Internal";
                }
            }
        }

        $convExistante = !empty($pdo->getConversation($idVehicule, $vendeur));
        $monVehicule = $pdo->getLeVehicule($idVehicule)['vendeur'] === $connexion;
        $vehicEnVente = $pdo->getLeVehicule($idVehicule)['status'] === 'VENTE';
        require_once $vues . 'vehicule.php';
    break;

    case 'supprimerVehicule':   
        $idVehicule = htmlentities($_GET['id']);
        try {
            $pdo->supprimerVehicule($idVehicule);
            header('Location:index.php?action=profil&id=' . $connexion);
            exit();
        } catch (\Throwable $th) {
            echo "<div class='container alert alert-danger text-center'>Erreur 404 <br/></div>";
        }
    break;

    case 'messagerie':
        $lesClients = $pdo->getContactClients();
        $lesVendeurs = $pdo->getContactVendeurs();
        require_once $vues . 'messagerie.php';
    break;

    case 'profil':
        $idUtilisateur = htmlentities($_GET['id']);
        try {
            $utilisateur = $pdo->getUtilisateur($idUtilisateur);
            $avatar = htmlentities($utilisateur['avatar']);
            $nom = htmlentities($utilisateur['nom']);
            $prenom = htmlentities($utilisateur['prenom']);
            $ville = htmlentities($utilisateur['ville']);
            $dateCompte = htmlentities($utilisateur['dateCreation']);

            $lesVehiculesVendus = $pdo->getVehiculesVendusUtilisateur($idUtilisateur);
            $nbVehiculesVendus = (int)count($lesVehiculesVendus); 
            $phraseVehicsVendus = ($nbVehiculesVendus > 1) ? "Véhicules vendus : $nbVehiculesVendus" : "Véhicule vendu : $nbVehiculesVendus";
            
            $lesVehiculesEnVentes = $pdo->getVehiculesUtilisateur($idUtilisateur, 'VENTE');
            $nbVehiculesEnVentes = (int)count($lesVehiculesEnVentes);

            $mesVehicules = $pdo->getVehiculesUtilisateur($idUtilisateur, 'VENDU');
            $nbMesVehicules = (int)count($mesVehicules);
            require_once $vues . 'profil.php';
            
        } catch (\Throwable $e) {
            echo "<div class='alert alert-danger text-center p-4'>Utilisateur introuvable</div>";
        }
    break;

    

    default:
        require_once $vues . '404.php';
    break;
}

require_once $element . 'footer.php';