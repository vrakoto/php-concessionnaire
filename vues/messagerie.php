<?= includeCSS('messagerie') ?>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-4 col-xl-3 chat">
            <div class="card mb-sm-3 mb-md-0 contacts_card">
                <div class="card-header">
                    <div class="input-group">
                        <input type="text" id="rechercheAmiConv" placeholder="Rechercher un contact ..." class="form-control search" oninput="rechercherContact()">
                    </div>
                </div>

                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <button class="ongletContact nav-link active" id="mesVehicules-tab" data-bs-toggle="tab" data-bs-target="#mesVehicules" type="button" role="tab" aria-controls="mesVehicules">Mes véhicules en ventes</button>
                    </li>

                    <li class="nav-item">
                        <button class="ongletContact nav-link" id="lesVendeurs-tab" data-bs-toggle="tab" data-bs-target="#lesVendeurs" type="button" role="tab" aria-controls="lesVendeurs">Les véhicules intéressés</button>
                    </li>

                </ul>

                <div class="tab-content">

                    <div class="tab-pane fade show active" id="mesVehicules" role="tabpanel" aria-labelledby="mesVehicules-tab">
                        <div class="card-body contacts_body" id="mesContacts"></div>
                    </div>

                    <div class="tab-pane fade show" id="lesVendeurs" role="tabpanel" aria-labelledby="lesVendeurs-tab">
                        <div class="card-body contacts_body" id="contactInteresse">
                            <?php foreach ($lesVehiculesInteresses as $vendeur) :
                                $idVehicule = htmlentities($vendeur['idVehicule']);
                                $auteur = htmlentities($vendeur['vendeur']);
                                $marque = htmlentities($vendeur['marque']);
                                $modele = htmlentities($vendeur['modele']);
                                $annee = (int)$vendeur['annee'];
                            ?>
                            <div class="leContact" onclick="ouvrirConversation('<?= $idVehicule ?>', '<?= $auteur ?>', this)">
                                <div class="d-flex bd-highlight">
                                    <div class="img_cont">
                                        <img src="https://static.turbosquid.com/Preview/001292/481/WV/_D.jpg" class="rounded-circle user_img">
                                        <span class="online_icon"></span>
                                    </div>
                                    <div class="user_info">
                                        <span class="leContact-nom"><?= $auteur . ' : ' . $marque . ' ' . $modele . ' ' . $annee ?></span>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach ?>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="col-md-8 col-xl-6 chat">
            <div class="card laConversation"></div>
        </div>
    </div>
</div>