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
                <div class="card-body contacts_body">
                    <ul class="contacts">
                        <?php foreach ($lesContacts as $idVehicule => $contact) : ?>
                            <li class="leContact" onclick="ouvrirConversation('<?= $idVehicule ?>', '<?= $contact ?>', this)">
                                <div class="d-flex bd-highlight">
                                    <div class="img_cont">
                                        <img src="https://static.turbosquid.com/Preview/001292/481/WV/_D.jpg" class="rounded-circle user_img">
                                        <span class="online_icon"></span>
                                    </div>
                                    <div class="user_info">
                                        <span class="leContact-nom"><?= $contact ?></span>
                                    </div>
                                </div>
                            </li>
                        <?php endforeach ?>
                    </ul>
                </div>
                <div class="card-footer"></div>
            </div>
        </div>

        <div class="col-md-8 col-xl-6 chat">
            <div class="card laConversation"></div>
        </div>
    </div>
</div>