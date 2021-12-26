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
            url: 'index.php?action=getLesMarques',
            data: 'type=' + type,
            success: (e) => {
                $('#marque').empty();
                $('#marque').append(e);
                getLesModeles();
                rechercherVehicule();
            },
            error: (e) => {
                console.log(e);
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
            url: 'index.php?action=getLesModeles',
            data: 'type=' + currentType + '&marque=' + $('#marque').val(),
            success: (e) => {
                $('#modele').empty();
                $('#modele').append(e);
            },
            error: (e) => {
                console.log(e);
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
            url: 'index.php?action=rechercherVehicule',
            data: 'type=' + currentType + '&marque=' + $('#marque').val() + '&modele=' + $('#modele').val() + '&annee=' + $('#annee').val() + '&transmission=' + $('#transmission').val() + '&prix=' + $('#prix').val() + '&energie=' + $('#energie').val() + '&region=' + $('#region').val(),
            success: (e) => {
                $('.lesVehicules').empty();
                $('.lesVehicules').append(e);
            },
            error: (e) => {
                console.log(e);
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

function contacter()
{
    
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

        case 'photos':
            message = "Bonjour Je suis intéressé par votre " + leVehicule + ". Pourriez-vous me transmettre davantage de photos de votre véhicule ? Cordialement";
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