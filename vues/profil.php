<div class="container mt-4" id="profil">
    <div class="card">
        <div class="card-body">
            <img class="rounded-circle" src="https://picsum.photos/200" alt="Image de l'utilisateur" height="80" width="80">
            <div class="d-inline-block align-middle m-3">
                <h4 class="mb-0 text-decoration-underline"><?= $prenom . ' ' . $nom ?></h4>
                <p>Membre depuis : <?= convertDate($dateCompte, FALSE) ?></p>
                <p><?= $phraseVehicsVendus ?></p>
                <p>Avis</p>
            </div>
            <p>Localisation : <?= ucfirst($ville) ?></p>
        </div>
    </div>

    <?php foreach ($lesVehicules as $vehicule): ?>
        <?php require $fonctions . 'varVehicule.php' ?>
        <a href="index.php?action=vehicule&id=<?= $id ?>" class="mt-5 leVehicule d-flex flex-row card w-100 mb-5">
            <img class="img-fluid" src="<?= $image ?>" width="250" alt="Image du véhicule">
            <div class="card-body">
                <h4 class="card-title"><?= $type ?></h4>
                <h6 class="mt-3 mb-0"><?= $marque . ' ' . $modele ?></h6>
                <i><?= $region ?></i>
                <h6 class="mt-3"><b><?= $prix ?> &euro;</b></h6>

                <p><?= $description ?></p>
                <span><?= $annee ?> | </span>
                <span><?= $km ?> km</span>
                <span><?= $energie ?> | </span>
                <span><?= $transmission ?></span>
            </div>
        </a>
    <?php endforeach ?>
</div>