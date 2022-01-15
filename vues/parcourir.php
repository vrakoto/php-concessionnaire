<?= includeCSS('parcourir') ?>

<div class="type container d-flex justify-content-evenly flex-wrap mt-4">
    <?= typeParcourir('tous', 'star-of-life', 'Tous', $nbVehics) ?>
    <?= typeParcourir('automobile', 'car', 'Automobile', $nbAutomobile) ?>
    <?= typeParcourir('deuxRoues', 'motorcycle', 'Deux roues', $nbDeuxRoues) ?>
</div>

<hr class="container">

<div class="laRecherche container d-flex mt-4">
    <select class="form-select" onchange="getLesModeles()" name="marque" id="marque"></select>
    <select class="form-select" name="modele" id="modele"></select>

    <select class="form-select" id="annee">
        <option selected value="tous">Année</option>
        <?php for ($i = date('Y'); $i >= 1920; $i--): ?>
            <option value="<?= $i ?>" <?= valOptionSub('annee', $i) ?>><?= $i ?></option>
        <?php endfor ?>
    </select>

    <select class="form-select" id="transmission">
        <option selected value="tous">Boîte</option>
        <option value="automatique">Automatique</option>
        <option value="manuelle">Manuelle</option>
        <option value="sequentielle">Séquentielle</option>
    </select>

    <input type="number" class="form-control" id="prix" placeholder="Prix">

    <select class="form-select" id="energie">
        <option selected value="tous">Energie</option>
        <?php foreach ($lesEnergies as $e) { $energie = htmlentities($e['id'])  ?>
            <option value="<?= $energie ?>"><?= $energie ?></option>
        <?php } ?>
    </select>

    <select class="form-select" id="region">
        <option selected value="tous">Région</option>
        <?php foreach ($lesRegions as $r) { $region = htmlentities($r['id']) ?>
            <option value="<?= $region ?>"><?= $region ?></option>
        <?php } ?>
    </select>

    <button class="btn btn-primary" onclick="rechercherVehicule()">Rechercher</button>
</div>

<div class="lesVehicules container d-flex flex-column justify-content-evenly mt-5"></div>