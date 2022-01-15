<?php
session_start();
$root = dirname(__DIR__) . DIRECTORY_SEPARATOR;
require_once $root . 'BDD' . DIRECTORY_SEPARATOR . 'Authentification.php';
require_once $root . 'fonctions' . DIRECTORY_SEPARATOR . 'helper.php';


$pdo = new Authentification;
$connexion = $_SESSION['id'] ?? '';

$action = htmlentities($_REQUEST['action']);
switch ($action) {
    case 'getLesMarques':
        $type = htmlentities($_GET['type']);
        $marques = $pdo->getLesMarques($type);

        echo "<option selected value='tous'>Marque</option>";
        foreach ($marques as $m) {
            $marque = htmlentities($m['marque']);
            echo "<option value='$marque'>$marque</option>";
        }
    break;

    case 'getLesModeles':
        $type = htmlentities($_GET['type']);
        $marque = htmlentities($_GET['marque']);
        $modeles = $pdo->getLesModeles($type, $marque);

        echo "<option selected value='tous'>Modèle</option>";
        foreach ($modeles as $m) {
            $modele = htmlentities($m['modele']);
            echo "<option value='$modele'>$modele</option>";
        }
    break;

    case 'rechercherVehicule':
        $type = htmlentities($_GET['type']);
        $marque = htmlentities($_GET['marque']);
        $modele = htmlentities($_GET['modele']);
        $annee = htmlentities($_GET['annee']);
        $transmission = htmlentities($_GET['transmission']);
        $prix = (int)$_GET['prix'];
        $energie = htmlentities($_GET['energie']);
        $region = htmlentities($_GET['region']);

        try {
            $resultat = $pdo->rechercherVehicule([
                'type' => $type,
                'marque' => $marque,
                'modele' => $modele,
                'annee' => $annee,
                'transmission' => $transmission,
                'prix' => $prix,
                'energie' => $energie,
                'region' => $region
            ]);

            foreach ($resultat as $vehicule) {
                require $root . 'fonctions' . DIRECTORY_SEPARATOR . 'varVehicule.php';
                require $root . 'fonctions' . DIRECTORY_SEPARATOR . 'varCardVehicule.php';
            }
        } catch (PDOException $error) {
            throw $msg = $error->getMessage();
        }
    break;

    case 'getMesVehiculesVentes':
        $mesVehiculesEnVentes = $pdo->getVehiculesUtilisateur($connexion, 'VENTE');
        foreach ($mesVehiculesEnVentes as $vehicule) {
            $idVehicule = htmlentities($vehicule['id']);
            $marque = htmlentities($vehicule['marque']);
            $modele = htmlentities($vehicule['modele']);
            $annee = (int)$vehicule['annee'];
        
            echo <<<HTML
            <div class="leContact" onclick='getLesContacts("$idVehicule", "mesContacts")'>
                <div class="d-flex bd-highlight">
                    <i class="fas fa-folder-open fa-3x"></i>
                    <div class="user_info">
                        <span class="leContact-nom">$marque $modele $annee</span>
                    </div>
                </div>
            </div>
HTML;
        }
    break;

    case 'getLesContacts':
        $idVehicule = (int)$_POST['idVehicule'];
        $typeContact = htmlentities($_POST['typeContact']);

        $lesContacts = $pdo->getLesContacts($typeContact, $idVehicule);
        $marque = $pdo->getLeVehicule($idVehicule)['marque'];
        $modele = $pdo->getLeVehicule($idVehicule)['modele'];

        echo <<<HTML
        <div>
            <span class="btn btn-primary" onclick='getMesVehiculesVentes()'><i class='fas fa-long-arrow-alt-left'></i></span>
            <span class="text-center">$marque $modele</span>
        </div>
HTML;

        foreach ($lesContacts as $client) {
            $idVehicule = htmlentities($client['idVehicule']);
            if ($typeContact === 'contactInteresse') {
                $auteur = htmlentities($client['vendeur']);
            } else {
                $auteur = htmlentities($client['client']);
            }
            $marque = htmlentities($client['marque']);
            $modele = htmlentities($client['modele']);
            $annee = (int)$client['annee'];

            echo <<<HTML
            <div class="leContact" onclick='ouvrirConversation("$idVehicule", "$auteur", this)'>
                <div class="img_cont">
                    <img src="https://static.turbosquid.com/Preview/001292/481/WV/_D.jpg" class="rounded-circle user_img">
                </div>
                <div class="d-flex bd-highlight">
                    <div class="user_info">
                        <span class="leContact-nom">$auteur</span>
                    </div>
                </div>
            </div>
HTML;
        }
    break;

    case 'ouvrirConversation':
        $erreurs = [];
        $idVehicule = (int)$_POST['idVehicule'];
        $marque = $pdo->getLeVehicule($idVehicule)['marque'];
        $modele = $pdo->getLeVehicule($idVehicule)['modele'];
        $idContact = htmlentities($_POST['leContact']);

        $laConversation = $pdo->getConversation($idVehicule, $idContact);

        ob_start();
        echo <<<HTML
        <div>
            <button type="button" class="btn btn-success" onclick="actionAchat('$idVehicule', '$idContact', 'ACCEPTE')">Valider</button>
            <button type="button" class="btn btn-danger" onclick="actionAchat('$idVehicule', '$idContact', 'REFUSE')">Refuser</button>
        </div>
HTML;
        $actionAchat = ob_get_clean();

        ob_start();
        $demandeAchat = '';
        if ($connexion === $pdo->getLeVehicule($idVehicule)['vendeur']) {
            switch ($pdo->statusDemandeAchat($idVehicule, $idContact)) {
                case 'EN COURS':
                    $demandeAchat = $actionAchat;
                break;

                case 'ACCEPTE':
                    $demandeAchat = "<span class='text-success'>Vehicule vendu</span>";
                break;

                case 'REFUSE':
                    $demandeAchat = "<span class='text-success'>Vehicule refusé</span>";
                break;
            }
        } else {
            if (empty($pdo->statusDemandeAchat($idVehicule, $connexion))) {
                $demandeAchat = "<button class='btn btn-success' onclick='demanderAchat($idVehicule)'>Demander un achat</button>";
            } else if ($pdo->statusDemandeAchat($idVehicule, $connexion) === 'EN COURS') {
                $demandeAchat = "<span class='text-warning'>Demande en cours de réponse ...</span>";
            } else {
                $decision = $pdo->statusDemandeAchat($idVehicule, $connexion);
                $demandeAchat = "<span class='text-warning'>" . $decision . "</span>";
            }
        }
        $content = ob_get_clean();

        
        echo <<<HTML
        <div class='card-header msg_head'>
            <div class='d-flex align-items-center bd-highlight'>
                <div class='img_cont'>
                    <img src='https://static.turbosquid.com/Preview/001292/481/WV/_D.jpg' class='rounded-circle user_img'>
                    <span class='online_icon'></span>
                </div>
                <div class='user_info'>
                    <span>Discussion avec <a class="text-white" href="index.php?action=profil&id=$idContact">$idContact</a> sur : <a class="text-white" href="index.php?action=vehicule&id=$idVehicule">$marque $modele</a></span>
                </div>
                <div id='infAchat' style='margin-left: auto'>{$demandeAchat}</div>
            </div>
        </div>

        <div class="card-body msg_card_body">
HTML;

        foreach ($laConversation as $conversation) {
            $auteur = htmlentities($conversation['auteur']);
            $message = $conversation['message'];
            $dateInit = htmlentities($conversation['date']);
            $date = convertDate($dateInit, TRUE);

            if ($connexion === $auteur) {
                echo "
                <div class='d-flex justify-content-end mb-4'>
                    <div class='msg_auteur'>
                        $message
                        <span class='msg_time_send'>$date</span>
                    </div>

                    <div class='img_cont_msg'>
                        <img src='https://picsum.photos/200' class='rounded-circle user_img_msg'>
                    </div>
                </div>
                ";
            } else {
                echo "
                <div class='d-flex justify-content-start mb-4'>
                    <div class='img_cont_msg'>
                        <img src='https://static.turbosquid.com/Preview/001292/481/WV/_D.jpg' class='rounded-circle user_img_msg'>
                    </div>
                    <div class='msg_receveur'>
                        $message
                        <span class='msg_time'>$date</span>
                    </div>
                </div>
                ";
            }
        }

        echo <<<HTML
        </div>
        <div class='card-footer text-center'>
            <div class='input-group'>
                <textarea class='form-control type_msg' id="message" placeholder='Insérez un message ...'></textarea>
                <div class='input-group-append'>
                    <span class='input-group-text send_btn' onclick='envoyerMessage("$idVehicule", "$idContact")'><i class='fas fa-location-arrow'></i></span>
                </div>
            </div>
        </div>
HTML;
    break;

    case 'envoyerMessage':
        $erreurs = [];
        $idVehicule = htmlentities($_POST['idVehicule']);
        $destinataire = htmlentities($_POST['leContact']);
        $message = htmlentities($_POST['message']);

        if (mb_strlen($message) <= 0) {
            $erreurs['erreur'] = "Le message est vide";
        } else {
            try {
                $pdo->envoyerMessage($idVehicule, $destinataire, nl2br($message));
            } catch (PDOException $e) {
                $erreurs['erreur'] = "L'envoi du message a échoué" . $e;
            }
        }

        echo json_encode($erreurs);
    break;

    case 'demanderAchat':
        $erreurs = [];
        $idVehicule = htmlentities($_POST['idVehicule']);
        $proprio = $pdo->getLeVehicule($idVehicule)['vendeur'];
        try {
            $pdo->demanderAchat($idVehicule, $proprio);
        } catch (\Throwable $e) {
            $erreurs['erreur'] = "Demande d'achat impossible ";
        }
        echo json_encode($erreurs);
    break;

    case 'actionAchat':
        $erreurs = [];
        $idVehicule = htmlentities($_POST['idVehicule']);
        $idClient = htmlentities($_POST['idClient']);
        $decision = htmlentities($_POST['decision']);

        try {
            $pdo->actionAchat($idVehicule, $idClient, $decision);
        } catch (\Throwable $e) {
            $erreurs['erreur'] = "Action impossible" . $e;
        }
        echo json_encode($erreurs);
    break;


    case 'supprimerVente':
        $erreurs = [];
        $idVehicule = htmlentities($_POST['idVehicule']);

        try {
            $pdo->changerStatusVehicule($idVehicule, 'VENDU');
        } catch (\Throwable $th) {
            $erreurs['erreur'] = "Suppression du véhicule de votre vente impossible" . $e;
        }
        echo json_encode($erreurs);
    break;

    case 'revendre':
        $erreurs = [];
        $idVehicule = (int)$_POST['idVehicule'];
        $prixActuel = $pdo->getLeVehicule($idVehicule)['prix'];

        $decote = ($prixActuel * (15 / 100));
        $nouveauPrix = ($prixActuel - $decote);

        try {
            $pdo->revendre($idVehicule, $nouveauPrix);
        } catch (\Throwable $th) {
            $erreurs['erreur'] = "Revente impossible" . $e;
        }
        echo json_encode($erreurs);
    break;

    case 'supprimerVehicule':
        $erreurs = [];
        $idVehicule = (int)$_POST['idVehicule'];
        
        try {
            $pdo->supprimerVehicule($idVehicule);
        } catch (\Throwable $th) {
            $erreurs['erreur'] = "Suppression du véhicule impossible" . $e;
        }
        echo json_encode($erreurs);
    break;
}