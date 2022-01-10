<form method="POST" class="container mt-5">

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

    <?= form_item('id', 'Identifiant', 'text', 'id', 'Insérez un identifiant', TRUE) ?>
    <?= form_item('nom', 'Nom', 'text', 'nom', 'Insérez votre nom', TRUE) ?>
    <?= form_item('prenom', 'Prénom', 'text', 'prenom', 'Insérez votre prénom', TRUE) ?>
    <?= form_item('ville', 'Ville', 'text', 'ville', 'Insérez votre ville de résidence', TRUE) ?>
    <?= form_item('mdp', 'Mot de passe', 'password', 'mdp', 'Insérez un mot de passe') ?>
    <?= form_item('mdp2', 'Mot de passe à confirmer', 'password', 'mdp_confirm', 'Confirmez le mot de passe') ?>
    <button type="submit" class="btn btn-primary">Se connecter</button>
</form>