<div class="container d-flex justify-content-evenly mt-4">
    <?= typeParcourir('tous', 'star-of-life', 'Tous', $nbVehics) ?>
    <?= typeParcourir('automobile', 'car', 'Automobile', $nbAutomobile) ?>
    <?= typeParcourir('deuxRoues', 'motorcycle', 'Deux roues', $nbDeuxRoues) ?>
    <?= typeParcourir('edpm', 'biking', 'EDP(M)', $nbEDPM) ?>
</div>

<hr class="container">

<div class="laRecherche container d-flex mt-4">
    <select class="form-select">
        <option selected>Marque</option>
        <option value="1">One</option>
        <option value="2">Two</option>
        <option value="3">Three</option>
    </select>

    <select class="form-select">
        <option selected>Modele</option>
        <option value="1">One</option>
        <option value="2">Two</option>
        <option value="3">Three</option>
    </select>

    <select class="form-select">
        <option selected>Année</option>
        <option value="1">One</option>
        <option value="2">Two</option>
        <option value="3">Three</option>
    </select>

    <select class="form-select">
        <option selected>Boîte</option>
        <option value="1">Automatique</option>
        <option value="3">Manuelle</option>
        <option value="2">Séquentielle</option>
    </select>

    <select class="form-select">
        <option selected>Etat</option>
        <option value="1">One</option>
        <option value="2">Two</option>
        <option value="3">Three</option>
    </select>

    <select class="form-select">
        <option selected>Prix</option>
        <option value="1">One</option>
        <option value="2">Two</option>
        <option value="3">Three</option>
    </select>

    <select class="form-select">
        <option selected>Energie</option>
        <option value="1">One</option>
        <option value="2">Two</option>
        <option value="3">Three</option>
    </select>

    <select class="form-select">
        <option selected>Région</option>
        <option value="1">One</option>
        <option value="2">Two</option>
        <option value="3">Three</option>
    </select>

    <button class="btn btn-primary">Rechercher</button>
</div>

<div class="container d-flex justify-content-evenly mt-5">
    <?php foreach ($lesVehicules as $vehicule) : ?>
        <?php require_once $fonctions . 'varVehicule.php'; ?>
        <div class="d-flex flex-row card w-100">
            <img class="img-fluid" src="https://picsum.photos/250" width="250" alt="Image du véhicule">
            <div class="card-body">
                <h4 class="card-title"><?= $type ?></h4>
                <h6 class="mt-3 mb-0"><?= $marque ?> <?= $modele ?></h6>
                <i><?= $region ?></i>
                <h6 class="mt-3"><?= $prix ?></h6>

                <p><?= $description ?></p>
                <span><?= $annee ?></span>
                <span><?= $km ?></span>
                <span><?= $energie ?></span>
                <span><?= $transmission ?></span>
            </div>
        </div>
    <?php endforeach ?>
</div>