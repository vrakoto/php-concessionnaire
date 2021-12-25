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

function categorieDeuxRoues()
{

    console.log($('#categorieDeuxRoues'));
}