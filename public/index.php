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

// Gére les différents accès
$restrictions = ["vendre", "supprimerVehicule", "messagerie", "deconnexion"];
if ($connexion) {
    $restrictions = ["connexion", "inscription"];
}

if (in_array($action, $restrictions)) {
    header('Location:index.php?action=unknown');
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
                header('Location:index.php?action=accueil');
                exit();
            } else {
                $erreur = "Authentification incorrecte";
            }
        }
        require_once $vues . 'connexion.php';
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
        $nbVehics = (int)count($pdo->getLesVehicules());
        $nbAutomobile = (int)count($pdo->getLesVehicules('automobile'));
        $nbDeuxRoues = (int)count($pdo->getLesVehicules('deuxRoues'));
        $nbEDPM = (int)count($pdo->getLesVehicules('edpm'));

        $type = htmlentities($_GET['type']);
        
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
                require_once $vues . 'vendre.php';
            }
        }
        require_once $vues . 'vendre.php';
    break;

    // Consulter un véhicule
    case 'vehicule':
        $erreur = NULL;
        $idVehic = htmlentities($_GET['id']);
        $vehicule = $pdo->getLeVehicule($idVehic);
        require_once $fonctions . 'varVehicule.php';
        $avatar = $pdo->getUtilisateur($vendeur)['avatar'];

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
                    $pdo->envoyerMessage($idVehic, $vendeur, $message);
                    header('Location:index.php?action=vehicule&id=' . $idVehic);
                    exit();
                } catch (PDOException $error) {
                    echo "Erreur Internal";
                }
            }
        }

        $convExistante = !empty($pdo->getConversation($idVehic, $vendeur));
        $monVehicule = $pdo->estMonVehicule($idVehic);
        require_once $vues . 'vehicule.php';
    break;

    case 'vente':
        $idUtilisateur = htmlentities($_GET['id']);

        try {
            $pdo->getUtilisateur($idUtilisateur);
        } catch (\Throwable $th) {
            echo "<div class='container alert alert-danger text-center'>Utilisateur inexistant <br/><a href='index.php?action=accueil'>Revenir à la page d'accueil</a></div>";
            exit();
        }

        $lesVentes = $pdo->getVehiculesUtilisateur($idUtilisateur);
        if ($connexion === $idUtilisateur) {
            $lesVentes = $pdo->getVehiculesUtilisateur($connexion);
        }
        require_once $vues . 'ventes.php';
    break;

    case 'supprimerVehicule':
        $idVehic = htmlentities($_GET['id']);
        try {
            $pdo->supprimerVehicule($idVehic);
            header('Location:index.php?action=vente&id=' . $connexion);
            exit();
        } catch (\Throwable $th) {
            echo "<div class='container alert alert-danger text-center'>Erreur 404 <br/></div>";
            echo $th->getMessage();
        }
    break;

    case 'messagerie':
        $lesContacts = $pdo->getMesContacts();
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

            $nbVehicsVendus = (int)count($pdo->getMesVendus()); 
            $phraseVehicsVendus = ($nbVehicsVendus > 1) ? "Véhicules vendus : $nbVehicsVendus" : "Véhicule vendu : $nbVehicsVendus";
            
            $lesVehicules = $pdo->getVehiculesUtilisateur($idUtilisateur);
            require_once $vues . 'profil.php';
        } catch (\Throwable $e) {
            echo "<div class='alert alert-danger text-center p-4'>Utilisateur introuvable</div>";
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