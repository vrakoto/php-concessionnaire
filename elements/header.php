<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha512-Fo3rlrZj/k7ujTnHg4CGR2D7kSs0v4LLanw2qksYuRlEzO+tcaEPQogQ0KaoGN26/zrn20ImR1DfuLWnOo7aBA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="elements/css/style.css">
    <script src="elements/main.js"></script>
    <title><?= $title ?? 'Accueil' ?></title>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light text-primary">
        <div class="container-fluid">
            <div class="navbar-brand">ConcessionnaireV2</div>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <?= nav_item("index.php?action=accueil", "Accueil") ?>
                    <?= nav_item("index.php?action=parcourir&type=tous", "Parcourir") ?>
                </ul>
            </div>

            <div class="d-flex">
                <div>
                    <?php if (!$connexion): ?>
                        <a href="index.php?action=pageConnexion" class="btn btn-outline-success mr-2">Connexion</a>
                        <a href="index.php?action=pageInscription" class="btn btn-outline-secondary">Inscription</a>
                    <?php else: ?>
                        <a href="index.php?action=deconnexion" class="btn btn-outline-danger">Déconnexion</a>
                    <?php endif ?>
                </div>
            </div>
        </div>
    </nav>