<form action="index.php?action=vendre" method="POST" class="container mt-5 vendre">

    <?php if (isset($erreur)) : ?>
        <div class="alert alert-danger text-center">
            <?= $erreur ?>
            <ul class="mt-2">
                <?php foreach ($erreurs as $err): ?>
                    <li><?= $err ?></li>
                <?php endforeach ?>
            </ul>
        </div>
    <?php endif ?>

    <?= form_item('image', 'Lien de l\'image', 'text', 'image', 'Insérez le lien de l\'image ( http(s)://www.... )', TRUE) ?>

    <select class="form-select" name="type" id="type">
        <option selected value="none">Sélectionnez le type du véhicule</option>
        <option value="automobile" <?= valOptionSub('type', 'automobile') ?>>Automobile</option>
        <option value="deuxRoues" <?= valOptionSub('type', 'deuxRoues') ?>>Deux Roues</option>
    </select>

    <?= form_item('marque', 'Marque', 'text', 'marque', 'Insérez la marque', TRUE) ?>
    <?= form_item('modele', 'Modèle', 'text', 'modele', 'Insérez le modèle', TRUE) ?>

    <select class="form-select" name="annee">
        <option selected value="none">Sélectionnez l'année du véhicule</option>
        <?php for ($i = date('Y'); $i >= 1920; $i--): ?>
            <option value="<?= $i ?>" <?= valOptionSub('annee', $i) ?>><?= $i ?></option>
        <?php endfor ?>
    </select>

    <select class="form-select" name="boite">
        <option selected value="none">Sélectionnez la boite de transmission du véhicule</option>
        <option value="automatique" <?= valOptionSub('boite', 'automatique') ?>>Automatique</option>
        <option value="manuelle" <?= valOptionSub('boite', 'manuelle') ?>>Manuelle</option>
        <option value="sequentielle" <?= valOptionSub('boite', 'sequentielle') ?>>Séquentielle</option>
    </select>

    <?= form_item('km', 'Kilométrage total', 'number', 'km', 'Insérez le KM total du véhicule', TRUE) ?>

    <select class="form-select" name="energie">
        <option selected value="none">Sélectionnez l'énergie du véhicule</option>
        <option value="diesel" <?= valOptionSub('energie', 'diesel') ?>>Diesel</option>
        <option value="electrique" <?= valOptionSub('energie', 'electrique') ?>>Electrique</option>
        <option value="essence" <?= valOptionSub('energie', 'essence') ?>>Essence</option>
        <option value="hybride" <?= valOptionSub('energie', 'hybride') ?>>Hybride</option>
    </select>

    <select class="form-select" name="region">
        <option selected value="none">Sélectionnez une région de vente</option>
        <?php foreach ($lesRegions as $region): ?>
            <option value="<?= $region['id'] ?>"><?= $region['id'] ?></option>
        <?php endforeach ?>
    </select>

    <textarea class="form-control" name="description" placeholder="Décrire votre véhicule" maxlength="1000"></textarea>
    <p class="text-muted" id="countChrTxtArea"></p>

    <?= form_item('prix', 'Prix à définir', 'number', 'prix', 'Insérez votre prix de vente', TRUE) ?>

    <button type="submit" class="btn btn-primary mt-2" id="vendre">Continuer</button>
</form>