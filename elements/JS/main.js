let url = window.location.href;
const currentType = url.split("type=")[1];

$(document).ready(() => {
    if (url.indexOf("action=parcourir") != -1 ) {
        getLesMarques(currentType);
    }

    if (url.indexOf("messagerie") != -1) {
        getMesVehiculesVentes();
    }

    // profil de l'utilisateur
    // conserve onglet sélectionné dans les cookies
    if (url.indexOf("action=profil") != -1) {
        $('button[data-bs-toggle="tab"]').on('show.bs.tab', function(e) {
            localStorage.setItem('active', $(e.target).attr('data-bs-target'));
        });
        const active = localStorage.getItem('active');
        if (active) {
            $(active + '-tab').addClass("active");
            $(active).addClass("active show");
        } else {
            $('#vehiculesVentes-tab').addClass("active");
            $('#vehiculesVentes').addClass("active show");
        }
    }
});

$('textarea').on("input", function() {
    const maxlength = 1000;
    const currentLength = $(this).val().length;
    return $('#countChrTxtArea').text(maxlength - currentLength + " mot(s) restant(s)");
});

function getLesMarques(type)
{   
    $.ajax
    (
        {
            method: 'get',
            url: 'ajax/index.php?action=getLesMarques',
            data: 'type=' + type,
            success: (e) => {
                $('#marque').empty();
                $('#marque').append(e);
                getLesModeles();
                rechercherVehicule();
            },
            error: (e) => {
                console.log("internal error" + e);
            }
        }
    )
}

function getLesModeles()
{
    $.ajax
    (
        {
            method: 'get',
            url: 'ajax/index.php?action=getLesModeles',
            data: 'type=' + currentType + '&marque=' + $('#marque').val(),
            success: (e) => {
                $('#modele').empty();
                $('#modele').append(e);
            },
            error: (e) => {
                console.log("internal error");
            }
        }
    )
}

function rechercherVehicule()
{
    $.ajax
    (
        {
            method: 'get',
            url: 'ajax/index.php?action=rechercherVehicule',
            data: 'type=' + currentType + '&marque=' + $('#marque').val() + '&modele=' + $('#modele').val() + '&annee=' + $('#annee').val() + '&transmission=' + $('#transmission').val() + '&prix=' + $('#prix').val() + '&energie=' + $('#energie').val() + '&region=' + $('#region').val(),
            success: (e) => {
                $('.lesVehicules').empty();
                $('.lesVehicules').append(e);
            },
            error: (e) => {
                console.log("internal error");
            }
        }
    )
}

function suggestionsQuestion()
{
    if ($('#questionFrequentes').css('display') === "none") {
        $('#questionFrequentes').css({display: "block"});
        $('#btnSugQst').text('Masquer les suggestions');
    } else {
        $('#btnSugQst').text('Afficher suggestions de question');
        $('#questionFrequentes').css({display: "none"});
    }

}

function setSuggestionMessage(leMessage, leVehicule)
{
    let message = "";
    switch (leMessage) {
        case 'disponible':
            message = "Bonjour Je suis intéressé par votre " + leVehicule + ". Le véhicule est-il toujours disponible ? Cordialement";
        break;

        case 'prix':
            message = "Bonjour Je suis intéressé par votre " + leVehicule + ". Votre prix est-il négociable ? Cordialement";
        break;

        case 'entretien':
            message = "Bonjour Je suis intéressé par votre " + leVehicule + ". Des frais sont-ils à prévoir sur votre véhicule ? Cordialement";
        break;

        case 'financement':
            message = "Bonjour Je suis intéressé par votre " + leVehicule + ". Faites-vous des offres de financement pour l'achat de ce véhicule ? Cordialement";
        break;
    }
    
    $('#premierContact').empty();
    $('#premierContact').append(message);
}



function getMesVehiculesVentes()
{
    $.ajax
    (
        {
            method: 'get',
            url: 'ajax/index.php?action=getMesVehiculesVentes',
            success: (data) => {
                $('#mesContacts').empty();
                $('#mesContacts').append(data);
            },
            error: (e) => {
                console.log("internal error" + e);
            }
        }
    )
}

function rechercherContact()
{
    const input = $('#rechercheAmiConv').val();
    const inputFilter = input.toLowerCase();
    $('.leContact').each(function() {
        const leAmi = $(this).find('.leContact-nom').text();

        if (!leAmi.includes(inputFilter)) {
            $(this).addClass("filtrerContact");
        } else {
            $(this).removeClass("filtrerContact");
        }
    });
}

function getLesContacts(idVehicule, idContactBody)
{
    $.ajax
    (
        {
            method: 'post',
            url: 'ajax/index.php?action=getLesContacts',
            data: 'idVehicule=' + idVehicule + '&typeContact=' + idContactBody,
            success: (data) => {
                $('#' + idContactBody).empty();
                $('#' + idContactBody).append(data);
            },
            error: (e) => {
                console.log("internal error");
            }
        }
    )
}

function ouvrirConversation(idVehicule, leContact, currentCardContact)
{
    ccc = currentCardContact;
    $.ajax
    (
        {
            method: 'post',
            url: 'ajax/index.php?action=ouvrirConversation',
            data: 'idVehicule=' + idVehicule + '&leContact=' + leContact,
            success: (data) => {
                $('.leContact').not(currentCardContact).removeClass('activeContact');
                $(currentCardContact).addClass('activeContact');
                $('.laConversation').empty();
                $('.laConversation').append(data);
                $('.msg_card_body').scrollTop($('.msg_card_body').get(0).scrollHeight);
            },
            error: (e) => {
                console.log("internal error");
            }
        }
    )
}

function envoyerMessage(idVehicule, leContact)
{
    const message = $('#message').val();
    $.ajax
    (
        {
            method: 'post',
            url: 'ajax/index.php?action=envoyerMessage',
            data: 'idVehicule=' + idVehicule + '&leContact=' + leContact + '&message=' + message,
            success: (data) => {
                ouvrirConversation(idVehicule, leContact, ccc);
                const datas = JSON.parse(data);
                if (datas.erreur) {
                    $('#messageStatus').modal('show');
                    $('#messageContentStatus').text(datas.erreur);
                }
            },
            error: (e) => {
                console.log("internal error");
            }
        }
    )
}

function demanderAchat(idVehicule)
{
    $.ajax
    (
        {
            method: 'post',
            url: 'ajax/index.php?action=demanderAchat',
            data: 'idVehicule=' + idVehicule,
            success: (data) => {
                const datas = JSON.parse(data);
                if (datas.erreur) {
                    $('#messageStatus').modal('show');
                    $('#messageContentStatus').text(datas.erreur);
                } else {
                    $('#infAchat').empty();
                    $('#infAchat').append("<span class='text-warning ms-auto'>Demande en cours de réponse ...</span>");
                }
            },
            error: (e) => {
                console.log("internal error");
            }
        }
    )
}

function actionAchat(idVehicule, idClient, decision)
{
    const ask = window.confirm('Voulez vous confirmez cette décision ? Votre conversation avec ce client sera supprimée.');
    if (ask === true) {
        $.ajax
        (
            {
                method: 'post',
                url: 'ajax/index.php?action=actionAchat',
                data: 'idVehicule=' + idVehicule + '&idClient=' + idClient + '&decision=' + decision,
                success: (data) => {
                    const datas = JSON.parse(data);
                    if (datas.erreur) {
                        $('#messageStatus').modal('show');
                        $('#messageContentStatus').text(datas.erreur);
                    } else {
                        location.reload();
                    }
                },
                error: (e) => {
                    console.log("internal error");
                }
            }
        )
    }
}




function supprimerVente(idVehicule)
{
    $.ajax
    (
        {
            method: 'post',
            url: 'ajax/index.php?action=supprimerVente',
            data: 'idVehicule=' + idVehicule,
            success: (data) => {
                const datas = JSON.parse(data);
                if (datas.erreur) {
                    $('#messageStatus').modal('show');
                    $('#messageContentStatus').text(datas.erreur);
                } else {
                    location.reload();
                }
            },
            error: (e) => {
                console.log("internal error");
            }
        }
    )
}

function revendre(idVehicule)
{
    const ask = window.confirm("Une décote de 15% sera appliquée au véhicule, voulez-vous confirmer cette vente ?");
    if (ask) {
        $.ajax
        (
            {
                method: 'post',
                url: 'ajax/index.php?action=revendre',
                data: 'idVehicule=' + idVehicule,
                success: (data) => {
                    const datas = JSON.parse(data);
                    if (datas.erreur) {
                        $('#messageStatus').modal('show');
                        $('#messageContentStatus').text(datas.erreur);
                    } else {
                        location.reload();
                    }
                },
                error: (e) => {
                    console.log("internal error");
                }
            }
        )
    }
}

function supprimerVehicule(idVehicule)
{
    const ask = window.confirm("Voulez-vous supprimer ce véhicule définitivement ?");
    if (ask) {
        $.ajax
        (
            {
                method: 'post',
                url: 'ajax/index.php?action=supprimerVehicule',
                data: 'idVehicule=' + idVehicule,
                success: (data) => {
                    const datas = JSON.parse(data);
                    if (datas.erreur) {
                        $('#messageStatus').modal('show');
                        $('#messageContentStatus').text(datas.erreur);
                    } else {
                        location.reload();
                    }
                },
                error: (e) => {
                    console.log("internal error");
                }
            }
        )
    }
}