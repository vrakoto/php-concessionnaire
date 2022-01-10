<div class="container mt-4" id="profil">
    <div class="card">
        <div class="card-body">
            <img class="rounded-circle" src="https://picsum.photos/200" alt="Image de l'utilisateur" height="80" width="80">
            <div class="d-inline-block align-middle m-3">
                <h4 class="mb-0 text-decoration-underline"><?= $prenom . ' ' . $nom ?></h4>
                <p>Membre depuis : <?= convertDate($dateCompte, FALSE) ?></p>
                <p>Avis</p>
            </div>
            <p>Localisation : <?= ucfirst($ville) ?></p>
        </div>
    </div>

    <ul class="nav nav-tabs mt-3" role="tablist" id="myTab">
        <li class="nav-item">
            <button class="nav-link" id="vehiculesVentes-tab" data-bs-toggle="tab" data-bs-target="#vehiculesVentes" type="button" role="tab" aria-controls="vehiculesVentes" aria-selected="true">Véhicules en ventes (<?= $nbVehiculesEnVentes ?>)</button>
        </li>

        <li class="nav-item">
            <button class="nav-link" id="mesVehicules-tab" data-bs-toggle="tab" data-bs-target="#mesVehicules" type="button" role="tab" aria-controls="mesVehicules" aria-selected="false">Ses véhicules (  <?= $nbMesVehicules ?>)</button>
        </li>

        <li class="nav-item">
            <button class="nav-link" id="vehiculesVendus-tab" data-bs-toggle="tab" data-bs-target="#vehiculesVendus" type="button" role="tab" aria-controls="vehiculesVendus" aria-selected="false">Véhicules vendus (<?= $nbVehiculesVendus ?>)</button>
        </li>
    </ul>


    <div class="tab-content">

        <div class="tab-pane fade" id="vehiculesVentes" role="tabpanel" aria-labelledby="vehiculesVentes-tab">
            <?php foreach ($lesVehiculesEnVentes as $vehicule) : ?>
                <?php require $fonctions . 'varVehicule.php' ?>
                <?php require $fonctions . 'varCardVehicule.php' ?>
            <?php endforeach ?>
        </div>

        <div class="tab-pane fade" id="mesVehicules" role="tabpanel" aria-labelledby="mesVehicules-tab">
            <?php foreach ($mesVehicules as $vehicule) : ?>
                <?php require $fonctions . 'varVehicule.php' ?>
                <?php require $fonctions . 'varCardVehicule.php' ?>
            <?php endforeach ?>
        </div>

        <div class="tab-pane fade" id="vehiculesVendus" role="tabpanel" aria-labelledby="vehiculesVendus-tab">
            <?php foreach ($lesVehiculesVendus as $vehicule) : ?>
                <?php require $fonctions . 'varVehicule.php' ?>
                <?php require $fonctions . 'varCardVehicule.php' ?>
            <?php endforeach ?>
        </div>

    </div>
</div>