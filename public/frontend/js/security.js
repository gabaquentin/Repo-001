"use strict";

var isVerified = $('.js-user-is-verified').data('isVerified');
var phoneVerified = $('.js-user-phone-verified').data('phoneVerified');


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

$(document).ready(function () {
    setStatus();
});