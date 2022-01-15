<a href="index.php?action=vehicule&id=<?= $id ?>" class="mt-5 leVehicule d-flex flex-row card w-100 mb-5">
    <img class="img-fluid" src="<?= $image ?>" width="250" alt="Image du véhicule">
    <div class="card-body">
        <p class="text-success"><?= $monVehicule ?></p>
        <h4 class="card-title"><?= $type ?></h4>
        <h6 class="mt-3 mb-0"><?= $marque . ' ' . $modele ?></h6>
        <i><?= $region ?></i>
        <h6 class="mt-3"><b><?= $prix ?> &euro;</b></h6>

        <p><?= $description ?></p>
        <span><?= $annee ?> | </span>
        <span><?= $km ?> km</span>
        <span><?= $energie ?> | </span>
        <span><?= $transmission ?></span>

        <div class="mt-3">
            <?php if (isset($provenance) && $provenance === 'mesVehiculesEnVentes' && $connexion === $vendeur): ?>
                <button class="btn btn-warning" onclick="supprimerVente(<?= $id ?>); return false;"><i class="fas fa-trash"></i> Retirer de mes ventes</button>
                <button class="btn btn-danger" onclick="supprimerVehicule('<?= $id ?>'); return false;">Supprimer</button>
            <?php endif ?>

            <?php if (isset($provenance) && $provenance === 'mesVehicules' && $connexion === $vendeur): ?>
                <button class="btn btn-primary" onclick="revendre('<?= $id ?>'); return false;">Vendre</button>
                <button class="btn btn-danger" onclick="supprimerVehicule('<?= $id ?>'); return false;">Supprimer</button>
            <?php endif ?>
        </div>
    </div>
</a>