let service_questions = [];
let service_question = 0;
let service_question_prec = 0;
let reponses = [];
let bool_suivant = false;

function charger_question() {
    $.ajax({
        type: "get",
        url: window.url_service_questions,
        cache: false,
        async: false,
        success: function (response) {
            response.service_questions.forEach(element => {
                service_questions.push(element);
            })
        },
        error: function () {
            console.log("cela n'a pas fonctionné")
        }
    })
}

function afficher_question(number) {
    $("#question").html(service_questions[number].question)
    $("#options").html("");
    let rep=service_questions[number].reponses.split(',');
    let i=1;
    rep.forEach(element=>{
        $("#options").append(' <div class="custom-control custom-radio mt-2 mb-3">\n' +
            '                                                                    <input type="radio" name="option"\n' +
            '                                                                           class="custom-control-input"\n' +
            '                                                                           id="customRadioInline'+i+'" number=0>\n' +
            '                                                                    <label class="custom-control-label"\n' +
            '                                                                           for="customRadioInline'+i+'">' + element+
            '                                                                        </label>\n' +
            '                                                                </div>')
        i++;
    })

}

function position() {
    $("#prec").click(function () {
        afficher_reponse();
        recuperer_reponse(service_questions[service_question].question)
        service_question_prec = service_question;
        service_question = service_question == 0 ? 0 : service_question - 1;
        afficher_question(service_question);
        ProgressBar();

    })
    $("#suiv").click(function () {
        if (service_question == 3) {
            setTimeout(function () {
                $("#questions").css("display", "none");
                $("#informations").css("display", "block");
            }, 2000);
        }
        afficher_reponse();
        recuperer_reponse(service_questions[service_question].question);
        service_question_prec = service_question;
        service_question = service_question == 3 ? 3 : service_question + 1;
        afficher_question(service_question);
        ProgressBar();


    })
}

function init() {
    $("body").css("background-color", "#ededed");
    charger_question();
    afficher_question(0);
    $("#suiv").prop("disabled", true);
    position();
    $("#informations").css("display", "none");
    $('.dropify').dropify();

}

function ProgressBar() {
    let number = service_question == 0 ? service_question : service_question == 3 && service_question - service_question_prec == 0 ? service_question + 1 : service_question;
    progressBar = (number) * 100 / service_questions.length;
    $(".progress-bar").animate({
        width: "" + progressBar - 0 + "%",
    }, 300, "linear")
        .queue(function () {
            $(".lead").text("Complété à " + Math.floor(progressBar) + "%");
            $(this).dequeue();
        });
}

function recuperer_reponse(question) {
    console.log(service_question);
    $(".custom-control-input").each(function (index, element) {
        if ($(this).prop("checked")) {
            if(reponses[service_question]==null){
                reponses[service_question] = {
                    "question": question,
                    "reponse": $(this).next("label").text()
                };
            }
            else {
                reponses[service_question].reponse=$(this).next("label").text();
            }

            $(this).prop("checked", false);
            bool_suivant = true;
            $("#suiv").prop("disabled", false);

            // return false;
        } else $("#suiv").prop("disabled", true);

    })
    console.log(reponses)

}

function afficher_reponse() {
    $(".custom-control-input").each(function (index) {
        // alert(reponses);
        reponses.forEach(element => {
                if (element.reponse == $(this).next("label").text()) {
                    $(this).prop("checked", true)
                }
            }
        )
    })
}

function validation() {
    $.validator.setDefaults({
        errorClass: 'invalid-feedback',
        highlight: function (element) {
            $(element).parent().addClass('has-error');
        },
        unhighlight: function (element) {
            $(element).parent().removeClass('has-error');
            $(element).parent().addClass('has-success');

        },
        errorPlacement: function (error, element) {
            if (element.prop('type') === 'text' || element.prop('type') === 'mail' || element.prop('type') === 'password') {
                element.after(error);
            } else if (element.prop('type') === 'radio') {
                element.parent().parent().after(error);
            }
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

    $("#informations_2").validate({
        onkeyup: (element) => {
            $(element).valid();
        },
        rules: {
            'demande[localisation]': {
                required: true,
                codepostal:true
            },
            'demande[description]': {
                required: true,
            },
            'demande[date]': {
                required: true,
            },
            "demande[photo]": {
                required: true,
            },
        },
        messages: {
            'demande[localisation]': {
                required: ' champ est requis.',
                codepostal: 'entrez un code postal valide',
            },
            'demande[description]': {
                required: 'ce champ est requis',
            },
            'demande[date]': {
                required: 'ce champ est requis',
            },
            "demande[photo]": {
                required: "vous devez ajouter une photo"
            },
        },
        submitHandler: function (form) {
            alert("validation correcte");
            // form.submit();
        }
    })
}

$(function () {
    init()
    validation();
})