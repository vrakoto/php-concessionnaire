<form method="POST" class="container mt-5">
    <?php if (isset($erreur)): ?>
        <div class="alert alert-danger text-center">
            <?= $erreur ?? '' ?>
        </div>
    <?php endif ?>

    <div class="mb-4">
        <label for="id" class="form-label">Identifiant</label>
        <input type="text" name="id" class="form-control" id="id" placeholder="Insérez votre identifiant" autofocus>
    </div>

    <div class="mb-3">
        <label for="mdp" class="form-label">Mot de passe</label>
        <input type="password" name="mdp" class="form-control" id="mdp" placeholder="Insérez votre mot de passe">
    </div>
    <button type="submit" class="btn btn-primary">Se connecter</button>
</form>