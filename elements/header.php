<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha512-Fo3rlrZj/k7ujTnHg4CGR2D7kSs0v4LLanw2qksYuRlEzO+tcaEPQogQ0KaoGN26/zrn20ImR1DfuLWnOo7aBA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="elements/CSS/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="elements/CSS/style.css">

    <title><?= $title ?? 'Accueil' ?></title>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <div class="navbar-brand">ConcessionnaireV2</div>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <?= nav_item("index.php?action=accueil", "Accueil") ?>
                    <?= nav_item("index.php?action=parcourir&type=tous", "Parcourir") ?>
                    <?php if ($connexion) : ?>
                        <?= nav_item("index.php?action=vendre", "Vendre") ?>
                        <?= nav_item("index.php?action=vente&id=$connexion", "Mes ventes", "vente") ?>
                    <?php endif ?>
                </ul>
                <form class="d-flex">
                    <?php if (!$connexion) : ?>
                        <a href="index.php?action=pageConnexion" class="btn btn-outline-success me-2">Connexion</a>
                        <a href="index.php?action=pageInscription" class="btn btn-outline-secondary">Inscription</a>
                    <?php else : ?>
                        <a href="index.php?action=deconnexion" class="btn btn-outline-danger">Déconnexion</a>
                    <?php endif ?>
                </form>
            </div>
        </div>
    </nav>