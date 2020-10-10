let categorie = "La Totalité";
let key_word = "vide";
let bool = false;
let service_questions_2 = [];
let service_question_2 = 0;
let demande_reponses = [];
let numero_question = 0;

function recuperer_key_word() {
    $("#demande_recherche").keydown(function () {
        key_word = $.trim($(this).val());
        if (key_word == "") key_word = "vide";
        charger_demandes();
    })
}

function recuperer_categorie() {
    $("select.chosen-select-no-single").change(function () {
        categorie = $(this).children("option:selected").val();
        charger_demandes()
    });
}

function charger_demandes() {
    $.when($(".columns.is-account-grid.is-multiline").html("    <div class=\"account-loader is-active\">\n" +
        "                                    <div class=\"loader is-loading\"></div>\n" +
        "                                </div>")).done(function () {
        $('.account-loader').addClass('is-active');
        $.ajax({
            type: "get",
            url: '/front/eservices/demandes_client/' + categorie + '/' + key_word,
            cache: false,
            success: function (response) {
                // console.log("categorie: " + categorie);
                // console.log("keyword: " + key_word);
                let template = "";
                console.log("demandes",response.demandes);
                console.log("taille",response.demandes.length);
                response.demandes.forEach(element => {
                    template += '<div class="column is-6">\n' +
                        '                                    <div class="flat-card product-container is-long" data-product-id="7"\n' +
                        '                                         data-product-category="House">\n' +
                        '                                        <div class="left-image is-md">\n' +
                        '                                            <img src="' + window.url_service_image + element.img + '"\n' +
                        '                                                 data-demo-src="assets/img/products/house1.png" alt="">\n' +
                        '                                        </div>\n' +
                        '                                        <div class="product-info">\n' +
                        '                                            <a class="product-details-link" href="product.html"><h3\n' +
                        '                                                        class="product-name featured-md">' + element.nom + '</h3></a>\n' +
                        '                                            <p class="product-description">' + element.description + '</p>\n' +
                        '                                            <p class="product-description">non répondu</p>\n' +
                        '                                            <p class="product-price">\n' +
                        '                                                <span>' + element.date + ' </span>\n' +
                        '                                                <span>' + element.heure.date.split(" ")[1].split(".")[0] + ' </span>\n' +
                        '                                            </p>\n' +
                        '                                        </div>\n' +
                        '\n' +
                        '                                        <div class="actions">\n' +
                        '                                         <div class="demande_id is-hidden" data-id="' + element.id + '"></div>\n' +
                        '                                         <div class="service_id is-hidden" data-id="' + element.service + '"></div>\n' +
                        '                                         <div class="categorie_img is-hidden" data-img="' + element.categorie_image + '"></div>' +

                        '                                            <div class="add modal-trigger" data-modal="add-to-wishlist-modal">\n' +
                        '                                                                                              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-settings feather-icons"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>\n' +
                        '                                            </div>\n' +
                        '                                            <div class="demande_delete">\n' +
                        '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>\n' +

                        '                                            </div>\n' +
                        '                                        </div>\n' +
                        '                                    </div>\n' +
                        '                                    </div>\n' +
                        '                                </div>'
                })
                $.when($(".columns.is-account-grid.is-multiline").append(template)).done(function () {
                    $('.account-loader').removeClass('is-active');
                })
            },
            error: function () {
                toasts.service.error('', 'fas fa-dizzy', "le chargement des demandes n'a pas fonctionné ", 'bottomRight', 2500);
            }
        })

    })
}

function supprimer_demande_2() {
    $(document).on("click", ".demande_delete", function () {
        let id = $(this).siblings(".demande_id.is-hidden").data("id");
        let demande = $(this).closest(".column.is-6");
        launchAlert('Annuler cette demande?', 'Êtes vous sûr de vouloir annuler cette demande ? Ceci est irreversible', 'Delete', 'Cancel', function () {
            $.ajax({
                type: "get",
                url: "/front/eservices/categorie/service/demandes/" + id + "/suppression",
                cache: false,
                success: function (response) {
                    if (response == "ok") {
                        $('.account-loader').addClass('is-active');
                        console.log("demande_template",demande);
                        demande.remove();//TODO problème de suppression
                        charger_demandes();
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

function init() {
    setTimeout(function () {
        $(".account-loader").removeClass("is-active")
    }, 3000)
}

function afficher_question_2() {
    $(".tabs ul li").remove();
    $(".navtab-content").remove();
    let i = 1;
    let tmp = "";
    let tmp2 = "";
    service_questions_2.forEach(element => {
        tmp = '<li class="reponse ';
        tmp2 += '<div id="t' + i + '" class="navtab-content ';
        if (i == 1) {
            tmp += 'is-active';
            tmp2 += 'is-active'
        }
        tmp += '" data-tab="t' + i + '"><a>Question ' + i + '</a></li>\n';
        tmp2 += '">\n' + '                                        <div class="inner-content">\n' +
            '                                            <div id="existing-product-message" class="message is-link has-close-icon is-hidden">\n' +
            '                                                <div class="message-body">\n' +
            '                                                    <a class="close-icon">\n' +
            '                                                        <i data-feather="x"></i>\n' +
            '                                                    </a>\n' +
            '                                                    This product already exists in the selected wishlist. Please try another one.\n' +
            '                                                </div>\n' +
            '                                            </div>\n' +
            '                                            <div id="wishlist-modal-list-placeholder" class="modal-placeholder is-hidden">\n' +
            '                                                <div class="placeholder-content">\n' +
            '                                                    <img src="assets/img/illustrations/bed.svg" alt="">\n' +
            '                                                    <h3>No Wishlists</h3>\n' +
            '                                                    <p>You currently don\'t have any wishlist saved. Start by creating one.</p>\n' +
            '                                                    <div class="button-wrap">\n' +
            '                                                        <a href="wishlist.html"\n' +
            '                                                           class="button big-button primary-button rounded raised modal-delete">Add\n' +
            '                                                            Wishlist</a>\n' +
            '                                                    </div>\n' +
            '                                                </div>\n' +
            '                                            </div>\n' +
            '                                            <div id="wishlist-modal-list" class="wishlist-modal-list">\n' +
            '                                                <p>' + element.question + '\n' +
            '                                                    .</p>\n' +
            '                                                <ul data-id="' + i + '">\n' +
            '                                                </ul>\n' +
            ' <div class="button-wrap">\n' +
            '                                    <button type="submit" class="button add-to-wishlist-action is-fullwidth feather-button is-bold primary-button raised suivant">\n' +
            '                                        Suivant\n' +
            '                                    </button>\n' +
            '                                </div>\n' +
            '                                            </div>\n' +
            '                                        </div>\n'

        $.when($(".tabs ul").append(tmp)).done(function () {
            $(".tabs-wrapper ").append(tmp2);
        })
        tmp = "";
        tmp2 = "";
        let j = 1;
        let template = '';
        let rep = element.reponses.split(',');
        rep.forEach(element2 => {
            template += ' <li class="list-item">\n' +
                '                                                                <div class="meta">\n' +
                '                                                                    <span class="name">' + element2 + '</span>\n' +
                '                                                                </div>\n' +
                '                                                                <div class="selected-indicator">\n' +
                '                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check"><polyline points="20 6 9 17 4 12"></polyline></svg>' +
                '                                                                </div>\n' +
                '                                                            </li>'
            j++;
        })
        $(".wishlist-modal-list").find("ul[data-id='" + i + "']").append(template);
        template = "";

        i++;
    })
}

function charger_question_2(id) {
    $.ajax({
        type: "get",
        url: '/front/eservices/categorie/service/demande/nouvelle/' + id + '/questions',
        cache: false,
        async: false,
        success: function (response) {
            JSON.parse(response).forEach(element => {
                service_questions_2.push(element);
            })
        },
        error: function () {
            toasts.service.error('', 'fas fa-comment-alt', "le chargement des questions n'a pas fonctionné", 'bottomRight', 2500);
        }
    })
}

function update_template() {
    $(document).on("click", ".add", function () {
        $(".modal.add-to-wishlist-modal").addClass("is-active");
        $(".tabs-wrapper.underline-tabs.animated-tabs").css("display", "block");
        $("#demande_update").css("display", "none");
        let id = $(this).siblings(".service_id.is-hidden").data("id");
        let demande_id = $(this).siblings(".demande_id.is-hidden").data("id")
        image_categorie = $(this).siblings(".categorie_img.is-hidden").data("img");
        service_questions_2 = [];
        charger_question_2(id)
        afficher_question_2();
        charger_reponses(demande_id);

        $(".reponse").click(function () {
            $(".reponse").each(function () {
                if ($(this).hasClass("is-active"))
                    $(this).removeClass("is-active")
            })
            $(this).addClass("is-active")
            let data_tab = $(this).attr("data-tab");
            if ($.trim(data_tab) == "t1") numero_question = 0;
            else if ($.trim(data_tab) == "t2") numero_question = 1;
            if ($.trim(data_tab) == "t3") numero_question = 2;
            if ($.trim(data_tab) == "t4") numero_question = 3;

            $(".navtab-content").each(function () {
                if ($(this).hasClass("is-active"))
                    $(this).removeClass("is-active");
                if ($(this).attr("id") == data_tab)
                    $(this).addClass("is-active");
            })
        })

        $(".list-item").each(function () {
            let reponse = $(this).find(".name").text();
            demande_reponses.forEach(element => {
                if ($.trim(reponse) == $.trim(element.reponses)) {
                    $(this).addClass("is-active");
                    return;
                }
            })

        });
        $(".list-item").click(function () {
            $(this).siblings("li").each(function () {
                if ($(this).hasClass("is-active"))
                    $(this).removeClass("is-active")
            })
            $(this).addClass("is-active")
            demande_reponses[numero_question].reponses = $(this).find(".name").text();
        })
        $(".suivant").click(function () {
            $(".tabs-wrapper.underline-tabs.animated-tabs").css("display", "none");
            modifier_reponses(demande_reponses);
            $.when($("#demande_update").css("display", "block")).done(function () {
                charger_demande_modification(demande_id);
            })
            $("#demande_update").children("img").attr("src", window.url_service_image + image_categorie);
            $("#modifier").click(function () {
                validation_modification(demande_id);
            })
        })
    })
}

function charger_reponses(demande_id) {
    demande_reponses = [];
    $.ajax({
        type: "get",
        url: '/front/eservices/categorie/service/demandes/' + demande_id + '/demande_reponses',
        cache: false,
        async: false,
        success: function (response) {
            JSON.parse(response).forEach(element => {
                demande_reponses.push(element);
            })
        },
        error: function () {
            toasts.service.error('', 'fas fa-comment-alt', "le chargement des demandes n'a pas fonctionné ", 'bottomRight', 2500);

        }
    })
}

function modifier_reponses(demande_reponses) {
    $.ajax({
        type: "get",
        url: '/front/eservices/categorie/service/demandes/reponse_modification',
        data: "&reponses=" + JSON.stringify(demande_reponses),
        cache: false,
        success: function () {
            toasts.service.success('', 'fas fa-check', 'réponses modifiées avec succèss', 'bottomRight', 2500);

        },
        error: function () {
            toasts.service.error('', 'fas fa-comment-alt', "les réponses n'ont pas pu être modifiées", 'bottomRight', 2500);
        }
    })
}

function charger_demande_modification(demande_id) {
    $.ajax({
        type: "get",
        url: '/front/eservices/categorie/service/demandes/' + demande_id,
        cache: false,
        async: false,
        success: function (response) {
            demande = JSON.parse(response);
            $("#description").val(demande.description);
            $("#localisation").val(demande.localisation);
            $("#date").val(demande.date);
            $("#heure").val(demande.heure.split("T")[1].split("+")[0]);
        },
        error: function () {
            toasts.service.error('', 'fas fa-dizzy', "le chargement des demandes pour la modification n'a pas fonctionné", 'bottomRight', 2500);

        }
    })
}

function modifier_demande(demande_id) {
    $.ajax({
        type: "get",
        url: '/front/eservices/categorie/service/demandes/' + demande_id + "/modification",
        cache: false,
        async: false,
        beforeSend: function () {
            $("#modifier").addClass("is-loading");
        },
        data: {
            "localisation": $("#localisation").val(),
            "description": $("#description").val(),
            "date": $("#date").val(),
            "heure": $("#heure").val(),
        },
        success: function () {
            setTimeout(function () {
                toasts.service.success('', 'fas fa-check', 'demande modifiée avec succèss', 'bottomRight', 2500);
            }, 1500);
            setTimeout(function () {
                $(".modal.add-to-wishlist-modal").removeClass("is-active");
            }, 4000);
            $("#modifier").removeClass("is-loading");
        },
        error: function () {
            toasts.service.error('', 'fas fa-dizzy', "la modification de la demande n'a pas fonctionné\"n n'a pas fonctionné", 'bottomRight', 2500);

        }
    })
}

function validation_modification(demande_id) {
    $.validator.setDefaults({
        errorClass: 'errorMessage',
        highlight: function (element) {
            $(element).parent().addClass('has-error');
        },
        unhighlight: function (element) {
            $(element).parent().removeClass('has-error');
            $(element).parent().addClass('has-success');

        },
        errorPlacement: function (error, element) {
            if (element.prop('type') === 'text' || element.prop('type') === 'mail' || element.prop('type') === 'password' || element.prop("type") == "file" || element.prop("type") == "textarea" || element.prop("type") == "date" || element.prop("type") == "time") {
                element.after(error);
            } else if (element.prop('type') === 'radio') {
                element.parent().after(error);
            }
            toasts.service.error('', 'fas fa-dizzy', error.text(), 'bottomRight', 2500);
        }
    });

    $.validator.addMethod("codepostal", function (value, element) {
        var regex = new RegExp("^[1-9]{1}[0-9]{2}\\s{0,1}[0-9]{3}$");
        var key = value;

        if (!regex.test(key)) {
            return false;
        }
        return true;
    }, "écriver un code postal  conforme svp");
    var isOneFieldEmpty = false;
    var submit = false;

    $("#modification").validate({
        onkeyup: (element) => {
            $(element).valid();
        },
        rules: {
            localisation: {
                required: true,
                codepostal: true
            },
            description: {
                required: true,
            },
            date: {
                required: true,
            },
            heure: {
                required: true,
            },
        },
        messages: {
            localisation: {
                required: ' champ est requis.',
                codepostal: 'entrez un code postal valide',
            },
            description: {
                required: 'ce champ est requis',
            },
            date: {
                required: 'ce champ est requis',
            },
            heure: {
                required: "vous devez préciser l'heure du rendez vous",
            },
        },
        submitHandler: function (form, e) {
            // form.submit();
            e.preventDefault();
            modifier_demande(demande_id);


        }
    })
}

$(function () {
    init();
    recuperer_categorie();
    recuperer_key_word();
    supprimer_demande_2();
    let image_categorie = "";
    update_template();
    $('[data-toggle="datepicker"]').click(function () {
        $('[data-toggle="datepicker"]').focus();
    });


})