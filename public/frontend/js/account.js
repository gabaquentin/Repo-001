"use strict"; //Shipping address state global variable

var enableShippingAddress = false;

//Get account information

function getAccountInfo() {
  var userData = JSON.parse(localStorage.getItem('user')); //If not logged in, hide account
  var url = window.location.href;

  if (!userData.isLoggedIn) {
    $('#account-main, #account-main-placeholder').toggleClass('is-hidden');
    $('#elements-selection, #account-main-placeholder').toggleClass('is-hidden');
  } //Load account info
  else {
      //Photo
    if(url.toString() === "http://127.0.0.1:8000/partenariat" && userData.partenariat ==="boutique")
    {
      $('.profile-image').empty();
      var avatar = "\n        <img src=\"" + userData.logo + "\" data-demo-src=\"" + userData.logo + "\" alt=\"\">\n    ";
      $('.profile-image').append(avatar);
    }
    else
    {
      $('.profile-image').empty();
      var avatar = "\n        <img src=\"" + userData.photoUrl + "\" data-demo-src=\"" + userData.photoUrl + "\" alt=\"\">\n    ";
      $('.profile-image').append(avatar);
    }

      $('#account-first-name').html(userData.firstName);
      $('#account-last-name').html(userData.lastName);
      $('#full-name').html(userData.firstName + ' ' + userData.lastName);
      $('#account-email, #full-email').html(userData.email);

      if (userData.phone !== null) {
        $('#account-phone-number').html(userData.phone);
      } else {
        $('#account-phone-number').html('N/A');
      } //Billing address


      $('#account-billing-address .address1').html(userData.addresses[0].address1);
      $('#account-billing-address .address2').html(userData.addresses[0].address2);
      $('#account-billing-address .city').html(userData.addresses[0].city);
      $('#account-billing-address .postal-code').html(userData.addresses[0].postalCode);
      $('#account-billing-address .state').html(userData.addresses[0].state);
      $('#account-billing-address .country').html(userData.addresses[0].country); //Shipping address (if enabled)

      if (userData.addresses[1].disabled === false) {
        $('#account-shipping-address .address1').html(userData.addresses[1].address1);
        $('#account-shipping-address .address2').html(userData.addresses[1].address2);
        $('#account-shipping-address .city').html(userData.addresses[1].city);
        $('#account-shipping-address .postal-code').html(userData.addresses[1].postalCode);
        $('#account-shipping-address .state').html(userData.addresses[1].state);
        $('#account-shipping-address .country').html(userData.addresses[1].country);
        $('#account-shipping-address').removeClass('is-hidden');
      } //Hide Loader


      $('.account-loader').addClass('is-hidden');
    }
} //Get account edit data


function getEditAccountInfo() {
  var userData = JSON.parse(localStorage.getItem('user')); //If not logged in, hide account

  if (!userData.isLoggedIn && !userData.ableToEdit) {
    $('#account-edit-main, #account-edit-main-placeholder').toggleClass('is-hidden');
  } //Load account edit info
  else {
      //Photo
      $('.avatar-wrapper .profile-pic').remove();
      var avatar = "\n        <img class=\"profile-pic\" src=\"" + userData.photoUrl + "\" data-demo-src=\"" + userData.photoUrl + "\" alt=\"\">\n    ";
      $('.avatar-wrapper').prepend(avatar); //User Info

      $('#edit-first-name').val(userData.firstName);
      $('#edit-last-name').val(userData.lastName);
      $('#edit-email').val(userData.email);
      $('#full-name').html(userData.firstName + ' ' + userData.lastName);
      $('#full-email').html(userData.email);

      if (userData.phone !== null) {
        $('#edit-phone-number').val(userData.phone);
      } else {
        $('#edit-phone-number').val('');
      } //Billing address


      $('#billing-edit-address1').val(userData.addresses[0].address1);
      $('#billing-edit-address2').val(userData.addresses[0].address2);
      $('#billing-edit-city').val(userData.addresses[0].city);
      $('#billing-edit-postal-code').val(userData.addresses[0].postalCode);
      $('#billing-edit-state').val(userData.addresses[0].state);
      $('#billing-edit-country').val(userData.addresses[0].country); //Shipping address

      $('#shipping-edit-address1').val(userData.addresses[1].address1);
      $('#shipping-edit-address2').val(userData.addresses[1].address2);
      $('#shipping-edit-city').val(userData.addresses[1].city);
      $('#shipping-edit-postal-code').val(userData.addresses[1].postalCode);
      $('#shipping-edit-state').val(userData.addresses[1].state);
      $('#shipping-edit-country').val(userData.addresses[1].country);

      if (userData.addresses[1].disabled === false) {
        $('#shipping-switch').trigger('click');
        enableShippingAddress = true;
        $('.profile-info-card .card-body').removeClass('is-disabled');
      } //Hide Loader


      $('.account-loader').addClass('is-hidden');
    }
} //Save user info


function saveAccountInfo() {

  $('#save-account-button').on('click', function (event) {
    var $this = $(this);
    event.preventDefault();
    $this.addClass('is-loading');

    var state ;
    if(enableShippingAddress === false)
      state = 0;
    else
      state = 1;
    var form = $('#account-edit-form');

    $.ajax({
      type: "POST",
      url: form.attr("/editaccount"),
      data: {
        "nom": $('#edit-first-name').val(),
        "prenom": $('#edit-last-name').val(),
        "paysPaiement": $('#billing-edit-country').val(),
        "postePaiement": $('#billing-edit-postal-code').val(),
        "villePaiement": $('#billing-edit-address2').val(),
        "quartierPaiement": $('#billing-edit-city').val(),
        "addressPaiement": $('#billing-edit-state').val(),
        "paysLivraison": $('#shipping-edit-country').val(),
        "posteLivraison": $('#shipping-edit-postal-code').val(),
        "villeLivraison": $('#shipping-edit-address2').val(),
        "quartierLivraison": $('#shipping-edit-city').val(),
        "addressLivraison": $('#shipping-edit-state').val(),
        "image": $('.profile-pic').attr('src'),
        "esa": state,
        "local": $('#edit-local').val(),
      },
      dataType: 'json',
      success: function (data) {
        setTimeout(function () {
          $this.removeClass('is-loading');
          toasts.service.success('', 'fas fa-check', 'Sauvegarde effectué avec succés', 'bottomRight', 2500);
        }, 1500);
        setTimeout(function () {
          getUser();
        }, 4000);
      },
      error: function (data) {
        setTimeout(function () {
          $this.removeClass('is-loading');
          toasts.service.error('', 'fas fa-meh', " Erreur interne du server... Veuillez reesayer ", 'bottomRight', 2800);
        }, 800);

      },
    });
  });
} //Fake field validation


function fakeValidation() {
  $('.fake-validation').on('change', function () {
    var $this = $(this);

    if ($this.val().length < 2) {
      $this.closest('.field').addClass('has-error');
      $('#save-account-button').addClass('no-click');
    } else {
      $this.closest('.field').removeClass('has-error');
      $('#save-account-button').removeClass('no-click');
    }
  });
  $('.fake-email-validation').on('change', function () {
    var $this = $(this);

    if (!ValidateEmail($this.val())) {
      $this.closest('.field').addClass('has-error');
      $('#save-account-button').addClass('no-click');
    } else {
      $this.closest('.field').removeClass('has-error');
      $('#save-account-button').removeClass('no-click');
    }
  });
} //Upload profile picture


function uploadProfilePicture() {
  var imgSrc = '';

  function readFile(input) {
    if (input.files && input.files[0]) {
      var reader = new FileReader();

      reader.onload = function (e) {
        $uploadCrop.croppie('bind', {
          url: e.target.result
        }).then(function () {
          imgSrc = e.target.result;
          console.log('jQuery bind complete');
        });
      };

      reader.readAsDataURL(input.files[0]);
    } else {
      swal("Sorry - you're browser doesn't support the FileReader API");
    }
  } //Use croppie plugin


  var $uploadCrop = $('#upload-profile').croppie({
    enableExif: true,
    url: 'frontend/img/avatars/altvatar.png',
    viewport: {
      width: 130,
      height: 130,
      type: 'circle'
    },
    boundary: {
      width: '100%',
      height: 300
    }
  }); //Show preview

  function popupResult(result) {
    var html;

    if (result.html) {
      html = result.html;
    }

    if (result.src) {
      html = '<img src="' + result.src + '" />';
      $('.profile-pic').attr('src', result.src);
      $('#submit-profile-picture').removeClass('is-loading');
      $('#upload-crop-modal').removeClass('is-active');
    }
  }

  $('#upload-profile-picture').on('change', function () {
    readFile(this);
    $(this).closest('.modal').find('.profile-uploader-box, .upload-demo-wrap, .profile-reset').toggleClass('is-hidden');
    $('#submit-profile-picture').removeClass('is-disabled');
  }); //Submit

  $('#submit-profile-picture').on('click', function (ev) {
    var $this = $(this);
    $this.addClass('is-loading');
    $uploadCrop.croppie('result', {
      type: 'canvas',
      size: 'viewport'
    }).then(function (resp) {
      popupResult({
        src: resp
      });
    });
  }); //Reset

  $('#profile-upload-reset').on('click', function () {
    $(this).addClass('is-hidden');
    $('.profile-uploader-box, .upload-demo-wrap').toggleClass('is-hidden');
    $('#submit-profile-picture').addClass('is-disabled');
    $('#upload-profile-picture').val('');
  });
} //Countries autocomplete


function initCountryAutocomplete() {
  var accountCountriesOptions = {
    url: "https://restcountries.eu/rest/v2/all",
    getValue: "name",
    template: {
      type: "custom",
      method: function method(value, item) {
        return "<div class=" + 'template-wrapper' + "><img class=" + 'autocpl-country' + " src='" + item.flag + "' /><div class=" + 'entry-text' + ">" + value + "<br><span>" + item.region + "</span></div></div> ";
      }
    },
    highlightPhrase: false,
    list: {
      maxNumberOfElements: 3,
      showAnimation: {
        type: "fade",
        //normal|slide|fade
        time: 400,
        callback: function callback() {}
      },
      match: {
        enabled: true
      }
    }
  };
  $(".country-autocpl").easyAutocomplete(accountCountriesOptions);
}

$(document).ready(function () {
  //If account page
  if ($('#account-page').length) {
    getAccountInfo();
  } //If account edit page

  if ($('#edit-account-page').length) {
    getEditAccountInfo();
    initPopButtons();
    fakeValidation();
    initCountryAutocomplete();
    saveAccountInfo(); //Address switch
    $('#shipping-switch').on('change', function () {
      var userData = JSON.parse(localStorage.getItem('user'));
      $(this).closest('.flat-card').find('.card-body').toggleClass('is-disabled');
      enableShippingAddress = !enableShippingAddress;
      console.log(enableShippingAddress);
      if (enableShippingAddress) {
        $('#shipping-edit-address1').val(userData.addresses[0].address1);
        $('#shipping-edit-address2').val(userData.addresses[0].address2);
        $('#shipping-edit-city').val(userData.addresses[0].city);
        $('#shipping-edit-postal-code').val(userData.addresses[0].postalCode);
        $('#shipping-edit-state').val(userData.addresses[0].state);
        $('#shipping-edit-country').val(userData.addresses[0].country);
      } else {
        $('#shipping-edit-address1').val('');
        $('#shipping-edit-address2').val('');
        $('#shipping-edit-city').val('');
        $('#shipping-edit-postal-code').val('');
        $('#shipping-edit-state').val('');
        $('#shipping-edit-country').val('');
      }

      /*
      var state ;
      if(enableShippingAddress === false)
        state = 0;
      else
        state = 1;

      fetch("/esa/" + state).then(function(response) {
        if(response.ok) {
          if(state === 1)
          {
            setTimeout(function () {
              toasts.service.success('', 'fas fa-check', 'Adresse de livraison activé ', 'bottomRight', 2000);
            }, 600);
          }
          else if (state === 0)
          {
            setTimeout(function () {
              toasts.service.success('', 'fas fa-check', 'Adresse de livraison desactivé', 'bottomRight', 2000);
            }, 1200);
          }
        } else {
          console.log('Mauvaise réponse du réseau');
        }
      })
          .catch(function(error) {
            console.log('Il y a eu un problème avec l\'opération fetch: ' + error.message);
          });

       */
    });

  }
});