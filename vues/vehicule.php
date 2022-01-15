<?= includeCSS('vehicule') ?>

<?php if (isset($erreur)) : ?>
  <div class="container">
    <div class="alert alert-danger text-center">
      <?= $erreur ?>
    </div>
  </div>
<?php endif ?>

<div class="vehicule container mt-5">
  <div class="vehicule-header d-flex">

    <div class="imgVehic">
      <img class="img-fluid" src="<?= $image ?>" alt="Image du véhicule">
    </div>

    <div class="card">
      <div class="card-body">
        <div class="infos-vendeur">
          <img class="rounded" src="https://picsum.photos/80" width="80" height="80">
          <div class="d-inline-block align-middle m-3">
            <a href="index.php?action=profil&id=<?= $vendeur ?>"><?= $vendeur ?></a>
            <p>Avis</p>
          </div>
        </div>

        <hr>

        <h3><?= $marque ?></h3>
        <h5><?= $modele ?></h5>
        <h5><b><?= number_format($prix) ?> &euro;</b></h5>

        <?php if (!$connexion) : ?>
          <a href="index.php?action=connexion">Connectez-vous pour contacter</a>
        <?php else : ?>

          <?php if (!$monVehicule) : ?>

            <?php if (!$convExistante) : ?>
              <button type="button" class="btn btn-primary w-100 mt-2" data-bs-toggle="modal" data-bs-target="#contacter">Contacter</button>
            <?php else : ?>
              Une conversation existe déjà pour ce véhicule.
            <?php endif ?>

          <?php else : ?>

            <div class="alert alert-success text-center mb-0 mt-4"><i class="fas fa-check"></i> Votre véhicule</div>

            <?php if ($vehicEnVente) : ?>
              <button class="btn btn-warning w-100 mt-2" onclick="supprimerVente(<?= $id ?>)"><i class="fas fa-trash"></i> Retirer de mes ventes</button>
            <?php else: ?>
              <button class="btn btn-primary w-100 mt-2" onclick="revendre('<?= $id ?>')">Vendre</button>
            <?php endif ?>

            <a class="btn btn-danger w-100 mt-2" onclick="supprimerVehicule(<?= $id ?>)"><i class="fas fa-trash"></i> Supprimer le véhicule</a>
          <?php endif ?>

        <?php endif ?>
      </div>
    </div>
  </div>

  <div class="description card mt-4">
    <div class="card-body">
      <h2>Description</h2>

      <?= $description ?>
      <hr>
      <h2 class="mb-3">Informations générales</h2>
      <p>Région : <?= $region ?></p>
      <p>Année : <?= $annee ?></p>
      <p>Transmission : <?= $transmission ?></p>
      <p>Kilométrage totalisé : <?= $km ?> km</p>
      <p>Energie : <?= $energie ?></p>
      <p>Publié le : <?= convertDate($publication, TRUE) ?></p>
    </div>
  </div>
</div>

<div class="modal fade" id="contacter" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">

    <form class="modal-content" method="POST" action="index.php?action=vehicule&id=<?= $id ?>&leVendeur=<?= $vendeur ?>">
      <div class="modal-header">
        <h5 class="modal-title">Envoyez un message à <?= ucfirst($vendeur) ?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="text-center">
          <button type="button" class="btn btn-secondary" onclick="suggestionsQuestion()" id="btnSugQst">Afficher suggestions de question</button>
        </div>
        <div class="questionFrequentes" id="questionFrequentes">
          <button type="button" class="btn btn-primary" onclick="setSuggestionMessage('disponible', '<?= $marque . ' ' . $modele ?>')">Véhicule toujours disponible ?</button>
          <button type="button" class="btn btn-primary" onclick="setSuggestionMessage('prix', '<?= $marque . ' ' . $modele ?>')">Prix négociable ?</button>
          <button type="button" class="btn btn-primary" onclick="setSuggestionMessage('entretien', '<?= $marque . ' ' . $modele ?>')">Opération / Frais à prévoir ?</button>
          <button type="button" class="btn btn-primary" onclick="setSuggestionMessage('financement', '<?= $marque . ' ' . $modele ?>')">Offre de financement</button>
        </div>

        <textarea name="message" class="w-100 mt-3" id="premierContact" placeholder="Insérez votre message ..."></textarea>
      </div>

      <div class="modal-footer">
        <button type="submit" class="btn btn-primary" data-bs-dismiss="modal">Envoyer</button>
      </div>

    </form>
  </div>
</div>