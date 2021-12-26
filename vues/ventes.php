<div class="container d-flex flex-column justify-content-evenly mt-5">
    <?php foreach ($lesVentes as $vehicule) : ?>
        <?php require $fonctions . 'varVehicule.php' ?>
        <a href="index.php?action=vehicule&id=<?= $id ?>" class="leVehicule d-flex flex-row card w-100 mb-5">
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