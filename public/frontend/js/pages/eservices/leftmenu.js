let nombre_demande = 0;
let service_image = "";
let service_nom = "";

function charger_demande() {
    $.ajax({
        type: "get",
        url: window.url_demande_utilisateur,
        cache: false,
        async: false,
        success: function (response) {
            $(".demande_items.gelatine").text(response.demandes.length)
            if (response.demandes.length == 0) {
                $(".cart-total").text("Total: 0");
                if ($(".empty-cart").hasClass("is-hidden")) {
                    $(".empty-cart").removeClass("is-hidden");
                }

            } else {
                nombre_demande = response.demandes.length;
                $(".cart-total").text("Total: " + response.demandes.length);
                if (!$(".empty-cart .has-text-centered").hasClass("is-hidden"))
                    $(".empty-cart .has-text-centered").addClass("is-hidden");
                response.demandes.forEach(element => {
                    charger_service(element.id);
                    $(".shopping-cart-items").append('                <li class="clearfix">\n' +
                        '                    <img  src="' + window.url_service_image + service_image + '" data-demo-src="{{ asset(\'frontend/img/products/office5.jpg\') }}" alt="" />\n' +
                        '                    <span class="item-meta">\n' +
                        '                                <span class="item-name">' + service_nom + '</span>\n' +
                        '                        <span class="item-price">\n' +
                        '                                    <var>non répondu</var> x <span>1</span>\n' +
                        '                        </span>\n' +
                        '                        </span>\n' +
                        '                    <span class="quantity">\n' +
                        '                                <div data-trigger="spinner" class="return-spinner">\n' +
                        '                                    <input class="hidden-spinner" type="hidden" value="1" data-spin="spinner"\n' +
                        '                                           data-rule="quantity" data-min="1" data-max="99">\n' +
                        '                                    \n' +
                        '                                        \n' +
                        '                                    \n' +
                        '                                    <a href="" id="' + element.id + '" class="button feather-button primary-button raised animated preFadeInLeft fadeInLeft is-small supprimer_demande">\n' +
                        '                 annuler\n' +
                        '            </a>' +
                        '                       \n' +
                        '                            \n' +
                        '                        \n' +
                        '            </div>\n' +
                        '            </span>\n' +
                        '\n' +
                        '                </li>\n')
                })
            }

        },
        error: function () {
            toasts.service.error('', 'fas fa-dizzy', "Erreur dans l'envoie des données", 'bottomRight', 2500);
        }
    })
}

function supprimer_demande() {
    $(".button.feather-button.primary-button.raised.animated.preFadeInLeft.fadeInLeft.is-small.supprimer_demande").click(function (e) {
        e.preventDefault();
        let id = $(this).attr("id");
        let demande = $(this).closest("li.clearfix");
        launchAlert('Annuler cette demande?', 'Êtes vous sûr de vouloir annuler cette demande ? Ceci est irreversible', 'Delete', 'Cancel', function () {
                $('.cart-loader').addClass('is-active');
            $.ajax({
                type: "get",
                url: "/front/eservices/categorie/service/demandes/" + id + "/suppression",
                cache: false,
                success: function (response) {
                    setTimeout(function () {
                        $('.cart-loader').removeClass('is-active');
                    }, 1000);
                    if (response == "ok") {
                        demande.remove();
                        $(".cart-total").text("Total: " + nombre_demande - 1);
                        toasts.service.success('', 'fas fa-check', "Demande Supprimée avec succès", 'bottomRight', 2500);
                    }

                },
                error: function () {
                    toasts.service.error('', 'fas fa-dizzy', "Erreur dans la suppression", 'bottomRight', 2500);
                }
            })

        })


    })
}

function charger_service(id) {
    $.ajax({
        type: "get",
        url: "/front/eservices/categorie/service/demandes/" + id + "/service",
        cache: false,
        async: false,
        success: function (response) {
            service_image = response.service_image;
            service_nom = response.service_nom;
        },
        error: function () {
            toasts.service.error('', 'fas fa-dizzy', "Erreur dans l'affichage de l'image du service ", 'bottomRight', 2500);
        }
    })

}

$(function () {
    charger_demande();
    supprimer_demande();
    $("#open-cart").click(function () {
        if ($(".cart-loader").hasClass("is-active")) {
            setTimeout(function () {
                $('.cart-loader').removeClass('is-active');
            }, 1000);
        } else $('.cart-loader').addClass('is-active');
    })

})