const currentType = window.location.href.split("type=")[1];
$(document).ready(() => {
    getLesMarques(currentType);
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
            url: '../ajax/index.php?action=getLesMarques',
            data: 'type=' + type,
            success: (e) => {
                $('#marque').empty();
                $('#marque').append(e);
                getLesModeles();
                rechercherVehicule();
            },
            error: (e) => {
                console.log("internal error");
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
            url: '../ajax/index.php?action=getLesModeles',
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
            url: '../ajax/index.php?action=rechercherVehicule',
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

function showCatDeuxRoues(leType)
{
    if ($(leType).val() === 'deuxRoues') {
        return $('#categorieDeuxRoues').css({display: "block"});
    } else {
        return $('#categorieDeuxRoues').css({display: "none"});
    }
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

var ccc = "";
function ouvrirConversation(idVehicule, leContact, currentCardContact)
{
    ccc = currentCardContact;
    $.ajax
    (
        {
            method: 'post',
            url: '../ajax/index.php?action=ouvrirConversation',
            data: 'idVehicule=' + idVehicule + '&leContact=' + leContact,
            success: (e) => {
                $('.leContact').not(currentCardContact).removeClass('active');
                $(currentCardContact).addClass('active');
                $('.laConversation').empty();
                $('.laConversation').append(e);
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
            url: '../ajax/index.php?action=envoyerMessage',
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