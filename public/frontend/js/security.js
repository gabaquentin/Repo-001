"use strict";

var isVerified = $('.js-user-is-verified').data('isVerified');
var phoneVerified = $('.js-user-phone-verified').data('phoneVerified');
var userData = JSON.parse(localStorage.getItem('user'));


function setStatus(){

    if(isVerified)
    {
        $('#email-tile').addClass('is-done');
        $('#status-email').html("Verifié");
    }
    else
    {
        $('#email-tile').addClass('has-warning');
        $('#status-email').html("Non verifié");
    }

    if(phoneVerified)
    {
        $('#phone-tile').addClass('is-done');
        $('#status-tel').html("Verifié");
    }
    else
    {
        $('#phone-tile').addClass('has-warning');
        $('#status-tel').html("Non verifié");
    }

    if(isVerified && phoneVerified)
    {
        $('#status-tile').addClass('is-done');
        $('#status-status').html("Complet");
    }
    else
    {
        $('#status-tile').addClass('has-warning');
        $('#status-status').html("Incomplet");
    }

}

function verified(){

    $('#verified').on('click', function () {
        if(!isVerified)
        {
            Swal.fire({
                title: "Parfait",
                text: "Nous allons envoyer un E-Mail a l'adresse : "+userData.email,
                type: "warning",
                showCancelButton: !0,
                confirmButtonText: "Oui, Continuer!",
                cancelButtonText: "Non, annuler!",
                confirmButtonClass: "btn btn-success mt-2",
                cancelButtonClass: "btn btn-danger ml-2 mt-2",
                buttonsStyling: !0
            }).then(function (t) {
                if(t.value)
                {
                    fetch("/validateEmail/"+userData.email ).then(function(response) {
                        if(response.ok) {
                            Swal.fire({
                                title: "Consultez vos mails!",
                                text: "Un lien vous permettant de verifier votre adresse mail a été envoyé.",
                                type: "success"
                            })

                        } else {
                            t.dismiss === Swal.DismissReason.cancel &&Swal.fire({
                                title: "Erreur",
                                text: "Une erreur s'est produite durant l'operation",
                                type: "error"
                            });
                        }
                    })
                        .catch(function(error) {
                            console.log('Il y a eu un problème avec l\'opération fetch: ' + error.message);
                            t.dismiss === Swal.DismissReason.cancel &&Swal.fire({
                                title: "Erreur",
                                text: "Une erreur s'est produite durant l'operation",
                                type: "error"
                            });
                        });
                }
                else
                {
                    t.dismiss === Swal.DismissReason.cancel &&Swal.fire({
                        title: "OK",
                        text: "Vous pouvez reexecuter cette action a tout moment:)",
                        type: "error"
                    });
                }
            })
        }

    })
}

function changeEmail(){
    var email = "";

    $('#emailChange').on('click', function () {
        Swal.fire({
            title: "Entrez la nouvelle addresse",
            input: "text",
            inputAttributes: {autocapitalize: "off"},
            showCancelButton: !0,
            confirmButtonText: "Valider",
            cancelButtonText: "Annuler",
            showLoaderOnConfirm: !0,
            preConfirm: function (t) {
                email = t;
                if(ValidateEmail(t)) {
                    return fetch("/changeEmail/" + t).then(function (t) {
                        if (!t.ok) throw new Error(t.statusText);
                        return t.json()
                    }).catch(function (t) {
                        Swal.showValidationMessage("E-Mail déja existant")
                    })
                } else {
                    Swal.showValidationMessage("E-Mail incorrect")
                }

            },
            allowOutsideClick: function () {
                Swal.isLoading()
            }
        }).then(function (t) {

                isVerified = 0;
                userData.email = email;
                $('#verified').html('Verifier');
                setStatus();
                t.value&&Swal.fire({title:"Email changé" , text:"Votre Email a été changé et un mail a été envoyé pour verifier votre nouvel identifiant."})

        })
    });
}

function phoneverified(){

    $('#phoneVerified').on('click', function () {
        if(!phoneVerified)
        {
            Swal.fire({
                title: "Parfait",
                text: "Nous allons envoyer un code au numero : "+userData.phone,
                type: "warning",
                showCancelButton: !0,
                confirmButtonText: "Oui, Continuer!",
                cancelButtonText: "Non, annuler!",
                confirmButtonClass: "btn btn-success mt-2",
                cancelButtonClass: "btn btn-danger ml-2 mt-2",
                buttonsStyling: !0
            }).then(function (t) {
                if(t.value)
                {
                    fetch("/validatePhone/"+userData.phone ).then(function(response) {
                        if(response.ok) {
                            Swal.fire({
                                title: "Tapez le code recu",
                                input: "text",
                                inputAttributes: {autocapitalize: "off"},
                                showCancelButton: !0,
                                confirmButtonText: "Verifier",
                                cancelButtonText: "Annuler",
                                showLoaderOnConfirm: !0,
                                preConfirm: function (t) {
                                    return fetch("/markphoneIsverified/" + t).then(function (t) {
                                        if (!t.ok) throw new Error(t.statusText);
                                        return t.json()
                                    }).catch(function (t) {
                                        Swal.showValidationMessage("Code incorrect")
                                    })
                                },
                                allowOutsideClick: function () {
                                    Swal.isLoading()
                                }
                            }).then(function (t) {

                                    phoneVerified = 1;
                                    $('#phoneVerified').html(userData.phone);
                                    setStatus();

                                t.value&&Swal.fire({title:"Code correct" , text:"Votre numero de telephone est desormais marqué comme verifié"})
                            })

                        } else {
                            t.dismiss === Swal.DismissReason.cancel &&Swal.fire({
                                title: "Erreur",
                                text: "Une erreur s'est produite durant l'operation",
                                type: "error"
                            });
                        }
                    })
                        .catch(function(error) {
                            console.log('Il y a eu un problème avec l\'opération fetch: ' + error.message);
                            t.dismiss === Swal.DismissReason.cancel &&Swal.fire({
                                title: "Erreur",
                                text: "Une erreur s'est produite durant l'operation",
                                type: "error"
                            });
                        });
                }
                else
                {
                    t.dismiss === Swal.DismissReason.cancel &&Swal.fire({
                        title: "OK",
                        text: "Vous pouvez reexecuter cette action a tout moment:)",
                        type: "error"
                    });
                }
            })
        }

    })
}

function changePhone(){
    var phone = "";

    function preconfirm(t){
        return fetch("/changePhone/" + t).then(function (t) {
            if (!t.ok) throw new Error(t.statusText);
            return t.json()
        }).catch(function (t) {
            Swal.showValidationMessage("Telephone déja existant")
        })
    }

    $('#phoneChange').on('click', function () {
        Swal.fire({
            title: "Entrez le nouveau numero",
            input: "text",
            inputAttributes: {autocapitalize: "off"},
            showCancelButton: !0,
            confirmButtonText: "Valider",
            cancelButtonText: "Annuler",
            showLoaderOnConfirm: !0,
            preConfirm: function (t) {
                phone = t;
                if(ValidateTelLength('orange',t,9))
                {
                    preconfirm(t);
                }
                else if(ValidateTelLength('mtn',t,9))
                {
                    preconfirm(t);
                }
                else if(ValidateTelLength('camtel',t,9))
                {
                    preconfirm(t);
                }
                else if(ValidateTelLength('nextel',t,9))
                {
                    preconfirm(t);
                }
                else
                {
                    Swal.showValidationMessage("Telephone incorrect")
                }

            },
            allowOutsideClick: function () {
                Swal.isLoading()
            }
        }).then(function (t) {

            if(t.value)
            {
                phoneVerified = 0;
                userData.phone = phone;
                $('#phoneVerified').html('Verifier');
                setStatus();

                fetch("/validatePhone/"+phone ).then(function(response) {
                    if(response.ok) {
                        Swal.fire({
                            title: "Tapez le code recu",
                            input: "text",
                            inputAttributes: {autocapitalize: "off"},
                            showCancelButton: !0,
                            confirmButtonText: "Verifier",
                            cancelButtonText: "Plus tard",
                            showLoaderOnConfirm: !0,
                            preConfirm: function (t1) {
                                return fetch("/markphoneIsverified/" + t1).then(function (t1) {
                                    if (!t1.ok) throw new Error(t.statusText);
                                    return t1.json()
                                }).catch(function (t1) {
                                    Swal.showValidationMessage("Code incorrect")
                                })
                            },
                            allowOutsideClick: function () {
                                Swal.isLoading()
                            }
                        }).then(function (t1) {

                            phoneVerified = 1;
                            userData.phone = phone;
                            $('#phoneVerified').html(phone);
                            setStatus();

                            t1.value&&Swal.fire({title:"Code correct" , text:"Votre numero de telephone est des25ormais marqué comme verifié"})
                        })

                    } else {
                        t1.dismiss === Swal.DismissReason.cancel &&Swal.fire({
                            title: "Erreur",
                            text: "Une erreur s'est produite durant l'operation",
                            type: "error"
                        });
                    }
                })
                    .catch(function(error) {
                        console.log('Il y a eu un problème avec l\'opération fetch: ' + error.message);
                        t1.dismiss === Swal.DismissReason.cancel &&Swal.fire({
                            title: "Erreur",
                            text: "Une erreur s'est produite durant l'operation",
                            type: "error"
                        });
                    });
            }

        })
    });
}

$(document).ready(function () {
    setStatus();
    verified();
    changePhone();
    phoneverified();
    changeEmail();
});