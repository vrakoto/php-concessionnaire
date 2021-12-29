<?php
// exit() à la fin des cases servent à empêcher la récupération du header
switch ($action) {
    case 'getLesMarques':
        $type = htmlentities($_GET['type']);
        $marques = $pdo->getLesMarques($type);

        echo "<option selected value='tous'>Marque</option>";
        foreach ($marques as $m) {
            $marque = htmlentities($m['marque']);
            echo "<option value='$marque'>$marque</option>";
        }
        exit();
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
        exit();
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
                require $fonctions . 'varVehicule.php';
                if ($type === 'deuxRoues') {
                    $type = 'Deux Roues';
                }
                $monVehicule = "";
                if ($vendeur === $connexion) {
                    $monVehicule = "Votre véhicule";
                }
                echo <<<HTML
                <a href="index.php?action=vehicule&id=$id" class="leVehicule d-flex flex-row card w-100 mb-5">
                    <img class="img-fluid" src="$image" width="250" alt="Image du véhicule">
                    <div class="card-body">
                        <p class="text-success">$monVehicule</p>
                        <h4 class="card-title">$type</h4>
                        <h6 class="mt-3 mb-0">$marque $modele</h6>
                        <i>$region</i>
                        <h6 class="mt-3"><b>$prix &euro;</b></h6>

                        <p>$description</p>
                        <span>$annee | </span>
                        <span>$km km | </span>
                        <span>$energie | </span>
                        <span>$transmission</span>
                    </div>
                </a>
HTML;
            }
        } catch (PDOException $error) {
            throw $msg = $error->getMessage();
        }
        exit();
    break;

    case 'ouvrirConversation':
        $idVehicule = htmlentities($_POST['idVehicule']);
        $idContact = htmlentities($_POST['leContact']);
        $laConversation = $pdo->getConversation($idVehicule, $idContact);

        echo <<<HTML
        <div class='card-header msg_head'>
            <div class='d-flex bd-highlight'>
                <div class='img_cont'>
                    <img src='https://static.turbosquid.com/Preview/001292/481/WV/_D.jpg' class='rounded-circle user_img'>
                    <span class='online_icon'></span>
                </div>
                <div class='user_info'>
                    <span>Discutez avec <a href="index.php?action=profil&id=" class="text-white" . $idContact>$idContact</a></span>
                </div>
            </div>
        </div>

        <div class="card-body msg_card_body">
HTML;

        foreach ($laConversation as $conversation) {
            $idClient = htmlentities($conversation['idClient']);
            $idVendeur = htmlentities($conversation['idVendeur']);
            $idVehicule = htmlentities($conversation['idVehicule']);
            $message = $conversation['message'];
            $date = htmlentities($conversation['date']);

            if ($connexion === $idClient) {
                echo "
                <div class='d-flex justify-content-end mb-4'>
                    <div class='msg_auteur'>
                        $message
                        <span class='msg_time_send'></span>
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
                        <span class='msg_time'></span>
                    </div>
                </div>
                ";
            }
        }

        echo <<<HTML
        </div>
        <div class='card-footer'>
            <div class='input-group'>
                <textarea class='form-control type_msg' id="message" placeholder='Insérez un message ...'></textarea>
                <div class='input-group-append'>
                    <span class='input-group-text send_btn' onclick='envoyerMessage("$idVehicule", "$idContact")'><i class='fas fa-location-arrow'></i></span>
                </div>
            </div>
        </div>
HTML;
        exit();
    break;

    case 'envoyerMessage':
        $erreurs = [];
        $idVehicule = htmlentities($_POST['idVehicule']);
        $idContact = htmlentities($_POST['leContact']);
        $message = htmlentities($_POST['message']);

        if (mb_strlen($message) <= 0) {
            $erreurs['erreur'] = "Le message est vide";
        } else {
            try {
                $pdo->envoyerMessage($idVehicule, $idContact, nl2br($message));
            } catch (PDOException $e) {
                $erreurs['erreur'] = "L'envoi du message a échoué";
            }
        }

        echo json_encode($erreurs);
        exit();
    break;
}