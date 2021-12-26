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
}
