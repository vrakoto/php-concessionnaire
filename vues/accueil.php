<!-- <h1><?= $_SESSION['id']  ?? 'Accueil' ?></h1> -->

<div class="container mt-5">
    <div class="card">
        <div class="card-header">
            Vos ventes
        </div>
        <div class="card-body">
            <?php foreach ($actuVentes as $vente) : ?>
                <?php if ($vente['vendeur'] === $connexion) : ?>
                    <p class="card-text"><?= $vente['idClient'] ?> a acheté votre <a href="index.php?action=vehicule&id=<?= $vente['idVehicule'] ?>"><?= $pdo->getLeVehicule($vente['idVehicule'])['marque'] ?> <?= $pdo->getLeVehicule($vente['idVehicule'])['modele'] ?></a> le <?= $vente['dateDemande'] ?>.</p>
                <?php endif ?>
            <?php endforeach ?>
        </div>
    </div>

    <div class="card mt-5">
        <div class="card-header">
            Vos demandes de véhicule
        </div>
        <div class="card-body">
            <?php date_default_timezone_set('Europe/Paris') ?>
            <?php foreach ($actuVentes as $vente) :
                $idVehicule = (int)$vente['idVehicule'];
                $idClient = htmlentities($vente['idClient']);
                $status = htmlentities($vente['status']);
                $marque = $pdo->getLeVehicule($idVehicule)['marque'];
                $modele = $pdo->getLeVehicule($idVehicule)['modele'];
                $dateDemande = htmlentities($vente['dateDemande']);
                ?>

                <?php if ($idClient === $connexion) : ?>
                    
                    <?php if (date('d/m/Y') === convertDate($dateDemande)): ?>
                        <h5 class="card-title">Aujourd'hui</h5>
                    <?php endif ?>

                    <?php switch ($status):
                        case 'EN COURS': ?>
                            <p class="card-text">Une demande a été faite pour : <a href="index.php?action=vehicule&id=<?= $idVehicule ?>"><?= $marque ?> <?= $modele ?></a> le <?= convertDate($dateDemande, TRUE) ?>.</p>
                            <?php break ?>

                        <?php
                        case 'ACCEPTE': ?>
                            <p class="card-text text-success">Votre demande pour : <a href="index.php?action=vehicule&id=<?= $idVehicule ?>"><?= $marque ?> <?= $modele ?></a> le <?= convertDate($dateDemande, TRUE) ?> a été acceptée.</p>
                            <?php break ?>

                        <?php
                        case 'REFUSE': ?>
                            <p class="card-text text-danger">Votre demande pour : <a href="index.php?action=vehicule&id=<?= $idVehicule ?>"><?= $marque ?> <?= $modele ?></a> le <?= convertDate($dateDemande, TRUE) ?> a été refusée.</p>
                            <?php break ?>

                    <?php endswitch ?>
                <?php endif ?>
            <?php endforeach ?>
        </div>
    </div>
</div>