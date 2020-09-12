"use strict"; //Guest user object

var user = {
  isLoggedIn: false,
  ableToEdit: false,
  firstName: null,
  lastName: null,
  email: null,
  photoUrl: '/frontend/img/avatars/altvatar.png'
}; // set user local

var isAuthenticated = $('.js-user-rating').data('isAuthenticated');
var nom = $('.js-user-first-name').data('nom');
var prenom = $('.js-user-last-name').data('prenom');
var email = $('.js-user-email').data('email');
var phone = $('.js-user-phone').data('phone');
var image = $('.js-user-image').data('image');
var ableToEdit = $('.js-user-able-to-edit').data('ableToEdit');

if(isAuthenticated)
{
  var login = {
    isLoggedIn: true,
    ableToEdit: ableToEdit,
    firstName: nom,
    lastName: prenom,
    email: email,
    phone: phone,
    photoUrl: image,
    wishlists: myWishlists,
    orders: myOrders,
    addresses: myAddresses
};

  localStorage.setItem('user', JSON.stringify(login));

}
else
{

  localStorage.setItem('user', JSON.stringify(user));

}

//If no logged in user is found, set the default guest user object

if (JSON.parse(localStorage.getItem('user')) === null) {
  localStorage.setItem('user', JSON.stringify(user));
} //Get logged user info


function getUser() {
  var data = JSON.parse(localStorage.getItem('user')); //Populate user areas

  $('#quickview-avatar').attr('src', data.photoUrl);
  $('#quickview-avatar').attr('data-demo-src', data.photoUrl);
  $('#review-modal .box-header img, #mobile-avatar').attr('src', data.photoUrl);
  $('#review-modal .box-header img, #mobile-avatar').attr('data-demo-src', data.photoUrl);

  if (data.firstName !== null) {
    $('#quickview-username, #mobile-username').html(data.firstName + ' ' + data.lastName);
  } else {
    $('#quickview-username').html('Guest');
    $('#mobile-username').html('Welcome, Guest');
  }

  if (!data.isLoggedIn) {
    $('#logout-link, #mobile-logout-link').addClass('is-hidden');
    $('#login-link, #mobile-login-link, #mobile-register-link').removeClass('is-hidden');
  } else {
    $('#login-link, #mobile-login-link, #mobile-register-link').addClass('is-hidden');
    $('#logout-link, #mobile-logout-link').removeClass('is-hidden');
  }
} //Initialize login / registration forms


function initAuthenticationForms() {
  //Toggle login and registration wrappers
  $('.auth-toggler input').on('change', function () {
    if ($(this).prop('checked')) {
      $('.login-form-wrapper, .registration-form-wrapper').toggleClass('is-hidden');
      $('.reset-form').addClass('is-hidden');
      $('.login-form').removeClass('is-hidden');
      $('#auth-main-title').html('REGISTER');
    } else {
      $('.login-form-wrapper, .registration-form-wrapper').toggleClass('is-hidden');
      $('.reset-form').addClass('is-hidden');
      $('.login-form').removeClass('is-hidden');
      $('#auth-main-title').html('LOGIN');
    }
  }); //Toggle login and reset form

  $('.login-form .forgot-link a').on('click', function () {
    $('.login-form, .reset-form').toggleClass('is-hidden');
    $('#auth-main-title').html('FORGOT PASSWORD');
  });
  $('.reset-form .back-link a').on('click', function () {
    $('.login-form, .reset-form').toggleClass('is-hidden');
    $('#auth-main-title').html('LOGIN');
  });
} //Password regex

function ValidatePassword(password) {
  if (/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])[0-9a-zA-Z]{8,}$/.test(password)) {
    return true;
  }
  else{
    return false;
  }

} //Email regex

function ValidateEmail(mail) {
  if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(mail)) {
    return true;
  }
else{
    return false;
  }

} //Input value length check

function ValidateLength(value, length) {
  if (value.length >= length) {
    return true;
  } else {
    return false;
  }
} // Phone value validation

function ValidateTelLength(om, value, length) {
  if(om === "orange")
  {
    // 55 /56 /57 /58 /590 / 591/ 592/ 593/ 594/ 9/
    if(value.substr(0, 1) === "6")
    {
      if(value.substr(1, 2) === "55" || value.substr(1, 2) === "56" || value.substr(1, 2) === "57" || value.substr(1, 2) === "58" || value.substr(1, 3) === "590" || value.substr(1, 3) === "591" || value.substr(1, 3) === "592" || value.substr(1, 3) === "593" || value.substr(1, 3) === "594" || value.substr(1, 1) === "9")
      {
        if (value.length === length) {
          return true;
        } else {
          return false;
        }
      }
      else
      {
        return false;
      }
    }
    else
    {
      return false;
    }


  }
  else if(om === "mtn")
  {
    // 7/ 80/ 50/ 51/ 52/ 53/ 54/
    if(value.substr(0, 1) === "6")
    {
      if(value.substr(1, 1) === "7" || value.substr(1, 2) === "80" || value.substr(1, 2) === "50" || value.substr(1, 2) === "51" || value.substr(1, 2) === "52" || value.substr(1, 2) === "53" || value.substr(1, 2) === "54")
      {
        if (value.length === length) {
          return true;
        } else {
          return false;
        }
      }
      else
      {
        return false;
      }
    }
    else
    {
      return false;
    }
  }
  else if(om === "camtel")
  {
    // 22/ 33/ 42/ 43/
    if(value.substr(0, 1) === "2")
    {
      if(value.substr(1, 2) === "22" || value.substr(1, 2) === "33" || value.substr(1, 2) === "42" || value.substr(1, 2) === "43")
      {
        if (value.length === length) {
          return true;
        } else {
          return false;
        }
      }
      else
      {
        return false;
      }
    }
    else
    {
      return false;
    }
  }
  else if(om === "nextel")
  {
    // 6/
    if(value.substr(0, 1) === "6")
    {
      if(value.substr(1, 1) === "6")
      {
        if (value.length === length) {
          return true;
        } else {
          return false;
        }
      }
      else
      {
        return false;
      }
    }
    else
    {
      return false;
    }
  }
} // login function


function Login() {
 //Password Length validation

  $('#login-password').on('change', function () {
    var $this = $(this);
    var password = $this.val().trim();

    if (!ValidateLength(password, 8)) {
      $('#login-submit').addClass('is-disabled');
      $this.closest('.field').addClass('has-error');
    } else {
      $('#login-submit').removeClass('is-disabled');
      $this.closest('.field').removeClass('has-error');
    }
  });

  $('#login-email').on('change', function () {
    var $this = $(this);
    var email = $this.val();
    if (email === "") {
      $('#login-submit').addClass('is-disabled');
      $this.closest('.field').addClass('has-error');
    } else {
      $('#login-submit').removeClass('is-disabled');
      $this.closest('.field').removeClass('has-error');
    }

  });

  $('#login-submit').on('click', function (event) {

    // variable pour le calcul des erreurs de saisie
    var erreur = -1;

    var password = $('#login-password').val().trim();
    var email = $('#login-email').val().trim();

    if (!ValidateLength(password, 8)) {
      event.preventDefault();
      $('#login-submit').addClass('is-disabled');
      $('#login-password').closest('.field').addClass('has-error');
    } else {
      erreur = -1;
      $('#login-submit').removeClass('is-disabled');
      $('#login-password').closest('.field').removeClass('has-error');
    }

    if (email === "") {
      event.preventDefault();
      $('#login-submit').addClass('is-disabled');
      $('#login-email').closest('.field').addClass('has-error');
    } else {
      erreur -= -1;
      $('#login-submit').removeClass('is-disabled');
      $('#login-email').closest('.field').removeClass('has-error');
    }
    // si aucune erreur

    if(erreur === 0) {
      var $this = $(this);
      $this.addClass('is-loading');
      $('.small-auth-loader').addClass('is-active');
    }

    });// set user informations to local storage

} //register function

function Register() {
  //Email validation
  $('#register-email').on('change', function () {
    var $this = $(this);
    var email = $this.val().trim();

    if (!ValidateEmail(email)) {
      $('#register-submit').addClass('is-disabled');
      $this.closest('.field').addClass('has-error');
    } else {
      $('#register-submit').removeClass('is-disabled');
      $this.closest('.field').removeClass('has-error');
    }
  }); //Phone Length validation

  $('#register-tel').on('change', function () {
    var $this = $(this);
    var tel = $this.val().trim();
    var om = $('#register-om').val().trim();

    if (!ValidateTelLength(om, tel, 9)) {
      $('#register-submit').addClass('is-disabled');
      $this.closest('.field').addClass('has-error');
    } else {
      $('#register-submit').removeClass('is-disabled');
      $this.closest('.field').removeClass('has-error');
    }
  }); //Password validation

  $('#register-password').on('change', function () {
    var $this = $(this);
    var password = $this.val().trim();

    if (!ValidatePassword(password)) {
      setTimeout(function () {
        toasts.service.error('', 'fas fa-dizzy', '' +
            'Le Mot de passe doit contenir :' +
            ' </br> ' +
            'Au moins un chiffre 0-9 </br> ' +
            'Au moins une minuscule a-z </br> ' +
            'Au moins une majuscule A-Z  </br> ' +
            'Minimum 8 caractéres  </br> ' +
            '', 'bottomRight', 11200);
      }, 11200);
      $('#register-submit').addClass('is-disabled');
      $this.closest('.field').addClass('has-error');
    } else {
      $('#register-submit').removeClass('is-disabled');
      $this.closest('.field').removeClass('has-error');
    }
  }); //Verifing matching of passwords

  $('#register-confirm-password').on('change', function () {
    var $this = $(this);
    var confirm_passwordValue = $('#register-confirm-password').val();
    var passwordValue = $('#register-password').val();

    if (passwordValue !== confirm_passwordValue)
    {
      $('#register-submit').addClass('is-disabled');
      $this.closest('.field').addClass('has-error');
    } else {
      $('#register-submit').removeClass('is-disabled');
      $this.closest('.field').removeClass('has-error');
    }
  }); // message for language select

  $('#register-local').on('change', function () {
    var $this = $(this);
    var local = $this.val();

    if(local === "fr")
    {
      $('#register-submit').removeClass('is-disabled');
      $this.closest('.field').removeClass('has-error');
      setTimeout(function () {
        toasts.service.info('', 'fas fa-grin-wink', 'Vous pouvez changer votre preference langue a tout moment dans vos parametres', 'bottomRight', 11200);
      }, 1200);
    }
    else if(local === "en")
    {
      $('#register-submit').removeClass('is-disabled');
      $this.closest('.field').removeClass('has-error');
      setTimeout(function () {
        toasts.service.info('', 'fas fa-grin-wink', 'You can change your preferred language at any time in your settings', 'bottomRight', 11200);
      }, 1200);
    }
    else
    {
      $('#register-submit').addClass('is-disabled');
      $this.closest('.field').addClass('has-error');
    }

  }); //

  $('#register-om').on('change', function () {
    var $this = $(this);
    var om = $this.val();

    $('#register-tel').val("");

    if(om === "orange")
    {
      $('#register-tel').removeClass('is-hidden');
      setTimeout(function () {
        toasts.service.info('', 'fas fa-grin-wink', 'Les numeros de telephones chez Orange commencent par  =>  +237 6 55 /56 /57 /58 /590 / 591/ 592/ 593/ 594/ 9/', 'bottomRight', 11200);
      }, 1200);
    }
    else if(om === "mtn")
    {
      $('#register-tel').removeClass('is-hidden');
      setTimeout(function () {
        toasts.service.info('', 'fas fa-grin-wink', 'Les numeros de telephones chez MTN commencent par  =>   +237 6 7/ 80/ 50/ 51/ 52/ 53/ 54/', 'bottomRight', 11200);
      }, 1200);
    }
    else if(om === "camtel")
    {
      $('#register-tel').removeClass('is-hidden');
      setTimeout(function () {
        toasts.service.info('', 'fas fa-grin-wink', 'Les numeros de telephones chez Camtel commencent par  =>   +237 2 22/ 33/ 42/ 43/', 'bottomRight', 11200);
      }, 1200);
    }
    else if(om === "nextel")
    {
      $('#register-tel').removeClass('is-hidden');
      setTimeout(function () {
        toasts.service.info('', 'fas fa-grin-wink', 'Les numeros de telephones chez Nextel commencent par  =>   +237 6 6/', 'bottomRight', 11200);
      }, 1200);
    }

  });

  $('#register-nom').on('change', function () {
    var $this = $(this);
    var nom = $this.val();
    if (nom === "") {
      $('#register-submit').addClass('is-disabled');
      $this.closest('.field').addClass('has-error');
    } else {
      $('#register-submit').removeClass('is-disabled');
      $this.closest('.field').removeClass('has-error');
    }

  });

  $('#register-prenom').on('change', function () {
    var $this = $(this);
    var prenom = $this.val();
    if (prenom === "") {
      $('#register-submit').addClass('is-disabled');
      $this.closest('.field').addClass('has-error');
    } else {
      $('#register-submit').removeClass('is-disabled');
      $this.closest('.field').removeClass('has-error');
    }

  });




  $('#register-submit').on('click', function (event) {
    event.preventDefault();
    // variable pour le calcul des erreurs de saisie
    var erreur = 2;

    var email =  $('#register-email').val().trim();
    var password = $('#register-password').val().trim();
    var tel = $('#register-tel').val().trim();
    var om = $('#register-om').val().trim();

    var nom = $('#register-nom').val();
    var prenom = $('#register-prenom').val();
    var local = $('#register-local').val();

    var confirm_passwordValue = $('#register-confirm-password').val();
    var passwordValue = $('#register-password').val();

    if (!ValidateEmail(email)) {
      event.preventDefault();
      $('#register-submit').addClass('is-disabled');
      $('#register-email').closest('.field').addClass('has-error');
    } else {
      erreur = -6;
      $('#register-submit').removeClass('is-disabled');
      $('#register-email').closest('.field').removeClass('has-error');
    }

    if (!ValidatePassword(password)) {
      setTimeout(function () {
        toasts.service.error('', 'fas fa-dizzy', '' +
            'Le Mot de passe doit contenir :' +
            ' </br> ' +
            'Au moins un chiffre 0-9 </br> ' +
            'Au moins une minuscule a-z </br> ' +
            'Au moins une majuscule A-Z  </br> ' +
            'Minimum 8 caractéres  </br> ' +
            '', 'bottomRight', 11200);
      }, 11200);
      event.preventDefault();
      $('#register-submit').addClass('is-disabled');
      $('#register-password').closest('.field').addClass('has-error');
    } else {
      erreur -= -1;
      $('#register-submit').removeClass('is-disabled');
      $('#register-password').closest('.field').removeClass('has-error');
    }

    if (!ValidateTelLength(om, tel, 9)) {
      event.preventDefault();
      $('#register-submit').addClass('is-disabled');
      $('#register-tel').closest('.field').addClass('has-error');
    } else {
      erreur -= -1;
      $('#register-submit').removeClass('is-disabled');
      $('#register-tel').closest('.field').removeClass('has-error');
    }

    if (passwordValue !== confirm_passwordValue) {
      event.preventDefault();
      $('#register-submit').addClass('is-disabled');
      $('#register-confirm-password').closest('.field').addClass('has-error');
    } else {
      erreur -= -1;
      $('#register-submit').removeClass('is-disabled');
      $('#register-confirm-password').closest('.field').removeClass('has-error');
    }

    if (nom === "") {
      event.preventDefault();
      $('#register-submit').addClass('is-disabled');
      $('#register-nom').closest('.field').addClass('has-error');
    } else {
      erreur -= -1;
      $('#register-submit').removeClass('is-disabled');
      $('#register-nom').closest('.field').removeClass('has-error');
    }

    if (prenom === "") {
      event.preventDefault();
      $('#register-submit').addClass('is-disabled');
      $('#register-prenom').closest('.field').addClass('has-error');
    } else {
      erreur -= -1;
      $('#register-submit').removeClass('is-disabled');
      $('#register-prenom').closest('.field').removeClass('has-error');
    }

    if(local === "fr") {
      erreur -= -1;
      $('#register-submit').removeClass('is-disabled');
      $('#register-local').closest('.field').removeClass('has-error');
    } else if(local === "en") {
      erreur -= -1;
      $('#register-submit').removeClass('is-disabled');
      $('#register-local').closest('.field').removeClass('has-error');
    } else {
      event.preventDefault();
      $('#register-submit').addClass('is-disabled');
      $('#register-local').closest('.field').addClass('has-error');
    }

    // si aucune erreur

    if(erreur === 0) {
      var $this = $(this);
      $this.addClass('is-loading');
      var form = $('#registration-form');

      $.ajax({
        type: "POST",
        url: form.attr("/register/new_user"),
        data: {
          "nom": $('#register-nom').val(),
          "prenom": $('#register-prenom').val(),
          "email": $('#register-email').val(),
          "telephone": $('#register-tel').val(),
          "password": $('#register-password').val(),
          "local": $('#register-local').val(),
          "image": document.getElementById("imagesrc").src,
        },
        dataType: 'json',
        success: function (data) {
          /*
* code 0 : if no prblems with registration
* code 100 : if email already exist in data base
* code 200 : if phone number already exist in data base
* code 300 : if phone number and email already exist in data base
* code 400 : if phone number and/or email is empty
          */
          if(data['code'] === 0) {
            $('#user-email-label').html(data['email']);
            $('#registration-form').addClass('is-hidden');
            $('#check-email').removeClass('is-hidden');

            var register = {
              isLoggedIn: true,
              firstName: data['nom'],
              lastName: data['prenom'],
              email: data['email'],
              phone: data['tel'],
              photoUrl: data['image'],
              wishlists: myWishlists,
              orders: myOrders,
              addresses: myAddresses
            };

            localStorage.setItem('user', JSON.stringify(register));

            setTimeout(function () {
              $this.removeClass('is-loading');
              toasts.service.success('', 'fas fa-check', data['infos'], 'bottomRight', 11200);
            }, 1200);
          } else {
            setTimeout(function () {
              $this.removeClass('is-loading');
              toasts.service.info('', 'fas fa-meh', data['infos'], 'bottomRight', 11200);
            }, 1200);
            if(data['code'] === 100) {
              $('#register-email').closest('.field').addClass('has-error');
            } else if(data['code'] === 200) {
              $('#register-tel').closest('.field').addClass('has-error');
            } else if(data['code'] === 300) {
              $('#register-email').closest('.field').addClass('has-error');
              $('#register-tel').closest('.field').addClass('has-error');
            }

          }

        },
        error: function (data) {
          setTimeout(function () {
            $this.removeClass('is-loading');
            toasts.service.error('', 'fas fa-meh', " Erreur interne du server... Veuillez reesayer ", 'bottomRight', 2800);
          }, 800);
        },
      });
    } else {
      setTimeout(function () {
        toasts.service.error('', 'fas fa-dizzy', 'Le formulaire présente des problémes veuillez vérifier vos entrées', 'bottomRight', 11200);
      }, 1200);
      $('#register-submit').addClass('is-disabled');
    }

  })

}//Reset Password function

function Reset() {

  // email validation
  $('#reset-email').on('change', function () {
    var $this = $(this);
    var email = $this.val().trim();

    if (!ValidateEmail(email)) {
      $('#reset-submit').addClass('is-disabled');
      $this.closest('.field').addClass('has-error');
    } else {
      $('#reset-submit').removeClass('is-disabled');
      $this.closest('.field').removeClass('has-error');
    }
  });// validate password
  $('#reset-password').on('change', function () {
    var $this = $(this);
    var password = $this.val().trim();

    if (!ValidateLength(password, 8)) {
      $('#reset-process-submit').addClass('is-disabled');
      $this.closest('.field').addClass('has-error');
    } else {
      $('#reset-process-submit').removeClass('is-disabled');
      $this.closest('.field').removeClass('has-error');
    }
  });

  $('#reset-submit').on('click', function (event) {

    event.preventDefault();
    var erreur = 1;

    var email =  $('#reset-email').val().trim();

    if (!ValidateEmail(email)) {
      event.preventDefault();
      $('#reset-submit').addClass('is-disabled');
      $('#reset-email').closest('.field').addClass('has-error');
    } else {
      erreur = 0;
      $('#reset-submit').removeClass('is-disabled');
      $('#reset-email').closest('.field').removeClass('has-error');
    }

    // si auccune erreur
    if (erreur === 0) {
      var $this = $(this);
      $this.addClass('is-loading');

      var form = $('#reset-form');

      $.ajax({
        type: "POST",
        url: form.attr("/reset-password"),
        data: {
          "email": $('#reset-email').val(),
        },
        dataType: 'json',
        success: function (data) {
          if(data['status'] === "succes") {
            setTimeout(function () {
              $this.removeClass('is-loading');
              toasts.service.success('', 'fas fa-check', "Verifiez vos E-Mail", 'bottomRight', 11200);
            }, 1200);

            $('#token-life-time').html(data['tokenlifetime']);
            $('#reset-form').addClass('is-hidden');
            $('#check-email-reset').removeClass('is-hidden');
          } else {
            setTimeout(function () {
              $this.removeClass('is-loading');
              toasts.service.error('', 'fas fa-meh', "Le compte n'existe pas dans notre base de donnees", 'bottomRight', 2800);
            }, 800);
          }
        },
        error: function (data) {
          setTimeout(function () {
            $this.removeClass('is-loading');
            toasts.service.error('', 'fas fa-meh', " Erreur interne du server... Veuillez reesayer ", 'bottomRight', 2800);
          }, 800);

        },
      });
    } else {
      setTimeout(function () {
        toasts.service.error('', 'fas fa-dizzy', 'Le formulaire présente des problémes veuillez vérifier vos entrées', 'bottomRight', 11200);
      }, 1200);
      $('#reset-submit').addClass('is-disabled');
    }
  });

  $('#reset-process-submit').on('click', function (event) {

    event.preventDefault();
    var erreur = 1;

    var password =  $('#reset-password').val().trim();
    var passwordConfirm =  $('#reset-confirm-password').val().trim();

    if (!ValidatePassword(password)) {
      setTimeout(function () {
        toasts.service.error('', 'fas fa-dizzy', '' +
            'Le Mot de passe doit contenir :' +
            ' </br> ' +
            'Au moins un chiffre 0-9 </br> ' +
            'Au moins une minuscule a-z </br> ' +
            'Au moins une majuscule A-Z  </br> ' +
            'Minimum 8 caractéres  </br> ' +
            '', 'bottomRight', 11200);
      }, 11200);
      event.preventDefault();
      $('#reset-submit').addClass('is-disabled');
      $('#reset-password').closest('.field').addClass('has-error');
    } else {
      erreur = -1;
      $('#reset-submit').removeClass('is-disabled');
      $('#reset-password').closest('.field').removeClass('has-error');
    }

    if (password !== passwordConfirm) {
      event.preventDefault();
      $('#reset-submit').addClass('is-disabled');
      $('#reset-confirm-password').closest('.field').addClass('has-error');
    } else {
      erreur -= -1;
      $('#register-submit').removeClass('is-disabled');
      $('#register-confirm-password').closest('.field').removeClass('has-error');
    }

    // si auccune erreur
    if (erreur === 0)
    {
      var $this = $(this);
      $this.addClass('is-loading');

      var form = $('#reset-process-form');

      $.ajax({
        type: "POST",
        url: form.attr("{{ path('app_reset_password') }}"),
        data: {
          "password": $('#reset-password').val(),
        },
        dataType: 'json',
        success: function (data) {

          setTimeout(function () {
            $this.removeClass('is-loading');
            toasts.service.success('', 'fas fa-check', "Recuperation effectue avec succes", 'bottomRight', 11200);
          }, 1200);

          $('#reset-process-form').addClass('is-hidden');
          $('#succes-email-reset').removeClass('is-hidden');



        },
        error: function (data) {
          setTimeout(function () {
            $this.removeClass('is-loading');
            toasts.service.error('', 'fas fa-meh', " Erreur interne du server... Veuillez reesayer ", 'bottomRight', 2800);
          }, 800);

        },
      });
    }
    else
    {
      setTimeout(function () {
        toasts.service.error('', 'fas fa-dizzy', 'Le formulaire présente des problémes veuillez vérifier vos entrées', 'bottomRight', 11200);
      }, 1200);
      $('#reset-submit').addClass('is-disabled');
    }
  })

}//partenariat function

function Partenariat() {

  $('#partenariatB-nom').on('change', function () {
    var $this = $(this);
    var nom = $this.val();
    if (nom === "") {
      $('#partenariat-boutique-submit').addClass('is-disabled');
      $this.closest('.field').addClass('has-error');
    } else {
      $('#partenariat-boutique-submit').removeClass('is-disabled');
      $this.closest('.field').removeClass('has-error');
    }
  });
  $('#partenariatS-cin').on('change', function () {
    var $this = $(this);
    var cin = $this.val();
    if (cin === "") {
      $('#partenariat-services-submit').addClass('is-disabled');
      $this.closest('.field').addClass('has-error');
    } else {
      $('#partenariat-services-submit').removeClass('is-disabled');
      $this.closest('.field').removeClass('has-error');
    }
  });
  $('#partenariatB-desc').on('change', function () {
    var $this = $(this);
    var desc = $this.val().trim();

    if (!ValidateLength(desc, 10)) {
      $('#partenariat-boutique-submit').addClass('is-disabled');
      $this.closest('.field').addClass('has-error');
    } else {
      $('#partenariat-boutique-submit').removeClass('is-disabled');
      $this.closest('.field').removeClass('has-error');
    }
  });
  $('#partenariatS-desc').on('change', function () {
    var $this = $(this);
    var desc = $this.val().trim();

    if (!ValidateLength(desc, 10)) {
      $('#partenariat-services-submit').addClass('is-disabled');
      $this.closest('.field').addClass('has-error');
    } else {
      $('#partenariat-services-submit').removeClass('is-disabled');
      $this.closest('.field').removeClass('has-error');
    }
  });
  $('#partenariatB-domaine').on('change', function () {
    var $this = $(this);
    var domaine = document.querySelector("#partenariatB-domaine").selectedOptions.length;

    if(!domaine)
    {
      $('#partenariat-boutique-submit').removeClass('is-disabled');
      $this.closest('.field').addClass('has-error');
    }
    else
    {
      $('#partenariat-boutique-submit').addClass('is-disabled');
      $this.closest('.field').removeClass('has-error');
    }
  });
  $('#partenariatS-domaine').on('change', function () {
    var $this = $(this);
    var domaine = document.querySelector("#partenariatS-domaine").selectedOptions.length;

    if(!domaine)
    {
      $('#partenariat-services-submit').removeClass('is-disabled');
      $this.closest('.field').addClass('has-error');
    }
    else
    {
      $('#partenariat-services-submit').addClass('is-disabled');
      $this.closest('.field').removeClass('has-error');
    }
  });

  $('#partenariat-boutique-submit').on('click', function (event) {

    event.preventDefault();
    var erreur = 1;

    var nom =  $('#partenariatB-nom').val();
    var desc =  $('#partenariatB-desc').val();
    var domaine =  document.querySelector("#partenariatB-domaine").selectedOptions.length;

    if (nom === "") {
      event.preventDefault();
      $('#partenariat-boutique-submit').addClass('is-disabled');
      $('#partenariatB-nom').closest('.field').addClass('has-error');
    } else {
      erreur = -2;
      $('#partenariat-boutique-submit').removeClass('is-disabled');
      $('#partenariatB-nom').closest('.field').removeClass('has-error');
    }

    if (!ValidateLength(desc, 10)) {
      event.preventDefault();
      $('#partenariat-boutique-submit').addClass('is-disabled');
      $('#partenariatB-desc').closest('.field').addClass('has-error');
    } else {
      erreur -= -1;
      $('#partenariat-boutique-submit').removeClass('is-disabled');
      $('#partenariatB-desc').closest('.field').removeClass('has-error');
    }

    if(!domaine) {
      event.preventDefault();
      $('#partenariat-boutique-submit').removeClass('is-disabled');
      $('#partenariatB-domaine').closest('.field').addClass('has-error');
    } else {
      erreur -= -1;
      $('#partenariat-boutique-submit').addClass('is-disabled');
      $('#partenariatB-domaine').closest('.field').removeClass('has-error');
    }

    // si auccune erreur
    if (erreur === 0)
    {
      var $this = $(this);
      $this.addClass('is-loading');

      var form = $('#partenariat-boutique-form');

      $.ajax({
        type: "POST",
        url: form.attr("/partenariat"),
        data: {
          "nom": $('#partenariatB-nom').val(),
          "domaine": $('#partenariatB-domaine').val().toString(),
          "desc": $('#partenariatB-desc').val(),
          "partenariat": "boutique",
          "logo": document.getElementById("imagesrc").src,
        },
        dataType: 'json',
        success: function (data) {

          $('#partenariat-boutique-form').addClass('is-hidden');
          $('#boutique-submit-ok').removeClass('is-hidden');
          setTimeout(function () {
            $this.removeClass('is-loading');
            toasts.service.success('', 'fas fa-check', data['infos'], 'bottomRight', 11200);
          }, 1200);
        },
        error: function (data) {
          setTimeout(function () {
            $this.removeClass('is-loading');
            toasts.service.error('', 'fas fa-meh', " Erreur interne du server... Veuillez reesayer ", 'bottomRight', 2800);
          }, 800);

        },
      });
    }
    else
    {
      setTimeout(function () {
        toasts.service.error('', 'fas fa-dizzy', 'Le formulaire présente des problémes veuillez vérifier vos entrées', 'bottomRight', 11200);
      }, 1200);
      $('#partenariat-boutique-submit').addClass('is-disabled');
    }
  });
  $('#partenariat-services-submit').on('click', function (event) {

    event.preventDefault();
    var erreur = 1;

    var cin =  $('#partenariatS-cin').val();
    var desc =  $('#partenariatS-desc').val();
    var domaine =  document.querySelector("#partenariatS-domaine").selectedOptions.length;

    if (cin === "") {
      event.preventDefault();
      $('#partenariat-services-submit').addClass('is-disabled');
      $('#partenariatS-cin').closest('.field').addClass('has-error');
    } else {
      erreur = -2;
      $('#partenariat-services-submit').removeClass('is-disabled');
      $('#partenariatS-nom').closest('.field').removeClass('has-error');
    }

    if (!ValidateLength(desc, 10)) {
      event.preventDefault();
      $('#partenariat-services-submit').addClass('is-disabled');
      $('#partenariatS-desc').closest('.field').addClass('has-error');
    } else {
      erreur -= -1;
      $('#partenariat-services-submit').removeClass('is-disabled');
      $('#partenariatS-desc').closest('.field').removeClass('has-error');
    }

    if(!domaine) {
      event.preventDefault();
      $('#partenariat-services-submit').removeClass('is-disabled');
      $('#partenariatS-domaine').closest('.field').addClass('has-error');
    } else {
      erreur -= -1;
      $('#partenariat-services-submit').addClass('is-disabled');
      $('#partenariatS-domaine').closest('.field').removeClass('has-error');
    }

    // si auccune erreur
    if (erreur === 0)
    {
      var $this = $(this);
      $this.addClass('is-loading');
      var form = $('#partenariat-services-form');

      $.ajax({
        type: "POST",
        url: form.attr("/partenariat"),
        data: {
          "cin": $('#partenariatS-cin').val(),
          "domaine": $('#partenariatS-domaine').val().toString(),
          "desc": $('#partenariatS-desc').val(),
          "partenariat": "services",
        },
        dataType: 'json',
        success: function (data) {

          $('#partenariat-boutique-form').addClass('is-hidden');
          $('#boutique-submit-ok').removeClass('is-hidden');
          setTimeout(function () {
            $this.removeClass('is-loading');
            toasts.service.success('', 'fas fa-check', data['infos'], 'bottomRight', 11200);
          }, 1200);
        },
        error: function (data) {
          setTimeout(function () {
            $this.removeClass('is-loading');
            toasts.service.error('', 'fas fa-meh', " Erreur interne du server... Veuillez reesayer ", 'bottomRight', 2800);
          }, 800);

        },
      });

    }
    else
    {
      setTimeout(function () {
        toasts.service.error('', 'fas fa-dizzy', 'Le formulaire présente des problémes veuillez vérifier vos entrées', 'bottomRight', 11200);
      }, 1200);
      $('#partenariat-services-submit').addClass('is-disabled');
    }
  });

}//Logout function

function Logout() {
  $('#logout-link, #mobile-logout-link').on('click', function () {
    $('.small-auth-loader').addClass('is-active');
    localStorage.removeItem('user');
    setTimeout(function () {
      toasts.service.success('', 'fas fa-check', 'Vous etes maintenant deconnecté', 'bottomRight', 2000);
    }, 600);
  });
} //Accounts panel (Demo: do not use in production)


$(window).on('load', function () {
  var url = window.location.href;
  var userData = JSON.parse(localStorage.getItem('user'));

  if (url.indexOf("/login") > -1) {
    //If logged in, redirect
    if (userData.isLoggedIn) {
      window.location.href = '/';
    }
  }else if (url.indexOf("/editaccount") > -1) {
    //If not able to edit, redirect
    if (!userData.ableToEdit) {
      if(userData.isLoggedIn)
      {
        Swal.fire({
          title: "Entrez votre mot de passe",
          input: "password",
          inputAttributes: {autocapitalize: "off"},
          showCancelButton: !0,
          confirmButtonText: "Verifier",
          showLoaderOnConfirm: !0,
          preConfirm: function (t) {
            return fetch("/validatePassword/" + t).then(function (t) {
              if (!t.ok) throw new Error(t.statusText);
              return t.json()
            }).catch(function (t) {
              Swal.showValidationMessage("Mot de passe incorrect")
            })
          },
          allowOutsideClick: function () {
            Swal.isLoading()
          }
        }).then(function (t) {
          if(t.value)
          {
            var login = {
              isLoggedIn: true,
              ableToEdit: true,
              firstName: userData.firstName,
              lastName: userData.lastName,
              email: userData.email,
              phone: userData.phone,
              photoUrl: userData.photoUrl,
              wishlists: myWishlists,
              orders: myOrders,
              addresses: myAddresses
            };

            localStorage.setItem('user', JSON.stringify(login));
          }
          else
          {
            window.location.href = '/account';
          }
          t.value&&Swal.fire({title:"Mot de passe correct" , text:"Vous pouvez modifier vos informations personnelles"})
        })
      }

    }
  } else {
    console.log(url.toString());
  }
});

$(document).ready(function () {
  initAuthenticationForms();
  getUser();
  Login();
  Register();
  Reset();
  Partenariat();
  uploadProfilePicture();
  Logout();
});