"use strict"; //Get checkout summary for checkout sidebar

function getCheckoutSidebar() {
  let userData = JSON.parse(localStorage.getItem('user'));
  let checkout = JSON.parse(localStorage.getItem('checkout')); //Populate data

  $('#checkout-avatar').attr('src', 'http://via.placeholder.com/250x250');
  $('#checkout-avatar').attr('data-demo-src', checkout.avatar);
  $('#checkout-username').html(checkout.username); //Shipping address
  if (!userData.addresses[1].disabled) {
    $('#shipping-address1').html(userData.addresses[1].address1);
    $('#shipping-address2').html(userData.addresses[1].address2);
    $('#shipping-city').html(userData.addresses[1].city);
    $('#shipping-state').html(userData.addresses[1].state);
    $('#shipping-postalCode').html(userData.addresses[1].postalCode);
    $('#shipping-country').html(userData.addresses[1].country);
  } else {
    $('#shipping-address1').html(userData.addresses[0].address1);
    $('#shipping-address2').html(userData.addresses[0].address2);
    $('#shipping-city').html(userData.addresses[0].city);
    $('#shipping-state').html(userData.addresses[0].state);
    $('#shipping-postalCode').html(userData.addresses[0].postalCode);
    $('#shipping-country').html(userData.addresses[0].country);
  } //Totals


  $('#checkout-subtotal-value').html(parseFloat(checkout.subtotal).toFixed(2));
  $('#checkout-shipping-value').html(parseFloat(checkout.shipping).toFixed(2));
  $('#checkout-tax-value').html(parseFloat(checkout.taxes).toFixed(2));
  $('#checkout-grandtotal-value').html(parseFloat(checkout.total).toFixed(2));
} //Get step 1 info

function getAllProducts(p) {
  let price = p.price.toFixed(2);
  return `<div class="flex-table-item product-container" data-product-id="${p.id}">
            <div class="product">
                <img src="${p.images[0].url}" data-demo-src="" alt="">
                <a class="product-name">${p.name}</a>
            </div>
            <div class="quantity"><span>${p.quantity}</span></div>
            <div class="price"><span class="has-price">${price}</span></div>
            <div class="discount"><span class="has-price">0</span></div>
            <div class="total"><span class="has-price">${p.quantity*price}</span></div>
         </div>
    `;
}


function getCheckoutStep1() {
  let checkout = JSON.parse(localStorage.getItem('checkout')); //Remove existing

  $('.flex-table .flex-table-item').remove(); //Loop products

  for (let p = 0; p < checkout.items.length; p++) {
    let template = getAllProducts(checkout.items[p]);
    $.when($('.flex-table').append(template)).done(function () {});
  }
} //Get step 2 info


function getCheckoutStep2() {
  let userData = JSON.parse(localStorage.getItem('user')); //Disable address toggle if no shipping address is configured

  if (userData.addresses[1].disabled === true) {
    $('#shipping-switch').closest('label').addClass('is-hidden');
  }
} //Get step 4 info


function getCheckoutStep4() {
  let checkout = JSON.parse(localStorage.getItem('checkout')); //Shipping

  $('#summary-shipping-icon').attr('src', checkout.shippingMethod.icon);
  $('#summary-shipping-name').html(checkout.shippingMethod.name);
  $('#summary-shipping-description').html(checkout.shippingMethod.description); //Payment

  $('#summary-payment-icon').attr('src', checkout.paymentMethod.icon);
  $('#summary-payment-name').html(checkout.paymentMethod.name);
  $('#summary-payment-description').html(checkout.paymentMethod.description);
} //Finalize checkout and convert to order

function convertCheckoutToOrder() {
  let userData = JSON.parse(localStorage.getItem('user'));
  let checkout = JSON.parse(localStorage.getItem('checkout'));
  let orderProducts = [];

  function formatDate(date) {
    let monthsList = ["Jan", "Fev", "Mar", "Avr", "Mai", "Juin", "Juil", "Aout", "Sept", "Oct", "Nov", "Dec"];
    return monthsList[date.getMonth()] + ' ' + date.getDate() + ' ' + date.getFullYear();
  }

  ;
  let orderDate = formatDate(new Date());

  for (let p = 0; p < checkout.items.length; p++) {
    let orderItem = {
      id: checkout.items[p].id,
      name: checkout.items[p].name,
      price: checkout.items[p].price,
      quantity: checkout.items[p].quantity,
      photoUrl: checkout.items[p].images[0].url
    };
    orderProducts.push(orderItem);
  }

  let newOrder = {
    id: parseInt(userData.orders[0].id) + 1,
    total: checkout.total,
    date: orderDate,
    status: 'New',
    completed: 12,
    shippingMethod: checkout.shippingMethod.name,
    orderModel: {
      subtotal: parseFloat(checkout.subtotal).toFixed(2),
      taxes: parseFloat(checkout.taxes).toFixed(2),
      shipping: parseFloat(checkout.shipping).toFixed(2),
      total: parseFloat(checkout.total).toFixed(2)
    },
    //products: checkout.items,
    products: orderProducts,
    contact: {
      name: 'Janet Smith',
      photoUrl: 'assets/img/avatars/janet.jpg'
    }
  };
  userData.orders.unshift(newOrder);
  localStorage.setItem('user', JSON.stringify(userData));
  //localStorage.removeItem('checkout');
}

//Fonction qui permet de vérifer le code coupon saisie
function checkCoupon() {
  let checkout = JSON.parse(localStorage.getItem('checkout'));
  let code= $(".input").val();
  if(code !==""){
      $.ajax({
        type: "GET",
        url: check_couponRoute,
        data:{
          "coupon":code
        },
        async: true,
        dataType: "JSON",
        beforeSend: () => {
          $('.coupon-button').addClass('is-loading')
        },
        success: (data) =>{
          let Reduc=data["remise"];
          checkout.coupon = data["id"];
          checkout.taxes= (checkout.subtotal*Reduc ) /100 ;
          checkout.remise=Reduc;
          checkout.total = checkout.subtotal-checkout.taxes;
          checkout.taxes =parseFloat(checkout.taxes).toFixed(2);
          checkout.total =parseFloat(checkout.total).toFixed(2);
          localStorage.setItem('checkout', JSON.stringify(checkout));
          $('#checkout-tax-value').html(checkout.taxes);
          $('#checkout-grandtotal-value').html(checkout.total);
          $('.coupon-button').removeClass('is-loading');
          toasts.service.error('', 'fa fa-close', 'Votre code coupon a été pris en compte  ', 'bottomRight', 2500);

        },
        error: () => {
          $('.coupon-button').removeClass('is-loading');
          toasts.service.error('', 'fa fa-close', 'Vous avez saisi un mauvais code ', 'bottomRight', 2500);
        }
     })
  }
  else {
    toasts.service.error('', 'fa fa-close', "Vous avez oublié d'entrer un code coupon", 'bottomRight', 2500);
  }

}

function getOrderInfo() {
  let checkout = JSON.parse(localStorage.getItem('checkout'));
  $("#payment-mode").html(checkout.paymentMethod.name);
  $("#numero_order").html(checkout.id);
  $("#shipping-mode").html(checkout.shippingMethod.name);
  $("#statut").html("En cours de Livraison");
  $('#contact').html(checkout.username);
  $('.flex-table .flex-table-item').remove();
  for (let p = 0; p < checkout.items.length; p++) {
    let priceProduct = checkout.items[p].price * checkout.items[p].quantity;
    let remise = (priceProduct * checkout.remise / 100).toFixed(2);
    let template = "\n            <div class=\"flex-table-item product-container\" data-product-id=\"" + checkout.items[p].id + "\">\n    " +
        " <div class=\"product\">\n                  " +
        " <img src=\"http://via.placeholder.com/250x250\" data-demo-src=\"" + checkout.items[p].images[0].url + "\" alt=\"\">\n       " +
        "<a class=\"product-name\">" + checkout.items[p].name + "</a>\n              " +
        "</div>\n   " +
        " <div class=\"quantity\">\n  <span>" + checkout.items[p].quantity + "</span>\n     </div>\n  " +
        " <div class=\"price\">\n       <span class=\"has-price\">" + checkout.items[p].price.toFixed(2) + "</span>\n   </div>\n     " +
        " <div class=\"discount\">\n    <span class=\"has-price\"> "+ remise +"</span>\n     </div>\n           " +
        " <div class=\"total\">\n       <span class=\"has-price\">" + (priceProduct - remise).toFixed(2) + "</span>\n   </div>\n  " +
        " </div>\n        ";
    $.when($('.flex-table').append(template)).done(function () {});
  }
  $("#subtotal-value").html(checkout.subtotal);
  $("#shipping-value").html(checkout.shipping);
  $("#discount-value").html(checkout.taxes);
  $("#total-value").html(checkout.total);
  localStorage.setItem('checkout', JSON.stringify(checkout));
}

function SendOrderInfo() {
  let checkout = JSON.parse(localStorage.getItem('checkout'));
  let lettre = "0123456789";
  let code = ""+checkout.username.substr(0,3);
  for (let i = 0; i < 5; i++){
    code += lettre.charAt(Math.floor(Math.random() * lettre.length));
  }
  checkout.id = code;
  let cart = JSON.parse(localStorage.getItem('cart'));
  let PaymentMode = checkout.paymentMethod.name;
  let ShippingMode = checkout.shippingMethod.name;
  let numero=checkout.id;
  let userData = JSON.parse(localStorage.getItem('user'));
  let userId = userData.email;
  localStorage.setItem('cart', JSON.stringify(cart));
  $.ajax({
    type: "GET",
    url: add_commande,
    data:{
      "numero": numero,
      "cart": cart,
      "payment":PaymentMode,
      "shipping":ShippingMode,
      "user":userId
    },
    async: true,
    dataType: "JSON",
    success: (data) => {
      alert(data['payment']);
    },
    error: () => {
      alert("Faute");
    }
  });
  localStorage.setItem('checkout', JSON.stringify(checkout));
}

$(document).ready(function () {
  //Shipping methods
  $('.shipping-methods-grid input').on('change', function () {
    let $this = $(this);
    let checkout = JSON.parse(localStorage.getItem('checkout'));
    let rate = parseFloat($this.closest('.method-card').attr('data-shipping-rate')).toFixed(2);
    let items = parseInt(checkout.count);
    let shippingRate = (rate * items).toFixed(2);
    let newTotal = (parseFloat(checkout.total) + parseFloat(shippingRate)).toFixed(2);
    $('#checkout-shipping-value').html(shippingRate);
    $('#checkout-grandtotal-value').html(newTotal);
    $('.shipping-methods-grid .method-card').removeClass('is-selected');
    $this.closest('.method-card').addClass('is-selected');
  }); //Payment methods

  $('#payment-methods-main input').on('change', function () {
    let targetMethod = $(this).attr('data-value-id');
    $(this).closest('.method-card').addClass('is-selected');
    setTimeout(function () {
      $('.checkout-payment-methods').addClass('is-hidden');
      $('#payment-methods-' + targetMethod).removeClass('is-hidden');
    }, 300);
  });

  $('.checkout-payment-methods .payment-back').on('click', function () {
    $('.checkout-payment-methods').addClass('is-hidden');
    $('.checkout-payment-methods .method-card').removeClass('is-selected');
    $('#payment-methods-main').removeClass('is-hidden').find('input').prop('checked', false);
    $('.payment-disclaimer .animated-checkbox.is-checked input').prop('checked', false);
    $('.payment-disclaimer .animated-checkbox.is-checked .shadow-circle').removeClass('is-opaque');
    $('.payment-disclaimer .animated-checkbox.is-checked').removeClass('is-checked is-unchecked');
    $('#checkout-next').addClass('no-click');
  });

  $('.payment-disclaimer input').on('change', function () {
    console.log('changed');

    if (!$(this).prop('checked')) {
      $('#checkout-next').addClass('no-click');
    } else {
      $('#checkout-next').removeClass('no-click');
    }
  }); //If checkout

  if ($('.checkout-wrapper').length) {
    let currentStep = parseInt($('.checkout-wrapper').attr('data-checkout-step'));
    let checkout = JSON.parse(localStorage.getItem('checkout'));
    let userData = JSON.parse(localStorage.getItem('user'));
    disableCartSidebar(); //If a user is logged

    if (userData.isLoggedIn) {
      //If checkout data is null, show a modal
      if (checkout === null || checkout === undefined) {
        setTimeout(function () {
          $('.checkout-blocked-modal').addClass('is-active');
          setTimeout(function () {
            $('.checkout-blocked-modal .box').addClass('scaled');
          }, 600);
        }, 1500);
      } //User is logged and some checkout data is available
      else {
        //Redirect to the correct checkout object step
        if (checkout.step !== currentStep) {
          window.location.href = current;
        } //If checkout step1


        if ($('#checkout-1').length) {
          getCheckoutSidebar();
          getCheckoutStep1();
        }
        else if ($('#checkout-2').length) {
          getCheckoutSidebar();
          getCheckoutStep2();
        }
        else if ($('#checkout-3').length) {
          getCheckoutSidebar();
        }
        else if ($('#checkout-4').length) {
          getCheckoutSidebar();
          getCheckoutStep1(); //Can be reused here to get products

          getCheckoutStep4();
        }
        else if ($('#checkout-5').length) {
          getCheckoutSidebar();

          setTimeout(function () {
            $('.success-card').removeClass('is-hidden');
            getCart();
          }, 2000);
        }
      }
    } //If not
    else {
      setTimeout(function () {
        $('.checkout-unauth-modal').addClass('is-active');
        setTimeout(function () {
          $('.checkout-unauth-modal .box').addClass('scaled');
        }, 600);
      }, 1500);
    }
  } //Checkout button


  $('#checkout-next').on('click', function () {
    let checkout = JSON.parse(localStorage.getItem('checkout'));
    let $this = $(this);
    $this.addClass('is-loading'); //Handle step 1

    if ($('#checkout-1').length) {
      checkout.step = parseInt(checkout.step) + 1;
      localStorage.setItem('checkout', JSON.stringify(checkout));
      setTimeout(function () {
        window.location.href = next;
        $this.removeClass('is-loading');
      }, 1000);
    } //Handle step 2
    else if ($('#checkout-2').length) {
      let shippingMethod = {};

      if ($('.method-card.is-selected').length === 0) {
        toasts.service.error('', 'fa fa-close', 'Veillez choisir un moyen de livraison', 'bottomRight', 2500);
        setTimeout(function () {
          $this.removeClass('is-loading');
        }, 1000);
      }
      else {
        checkout.step = parseInt(checkout.step) + 1;
        localStorage.setItem('checkout', JSON.stringify(checkout));
        shippingMethod.icon = $('.method-card.is-selected').find('img').attr('src');
        shippingMethod.name = $('.method-card.is-selected').find('h3').text();
        shippingMethod.description = $('.method-card.is-selected').find('p').text();
        checkout.shippingMethod = shippingMethod;
        checkout.shipping = parseFloat($('#checkout-shipping-value').text()).toFixed(2);
        checkout.total = (parseFloat(checkout.total) + parseFloat(checkout.shipping)).toFixed(2);
        localStorage.setItem('checkout', JSON.stringify(checkout));
        setTimeout(function () {
          window.location.href = next;
          $this.removeClass('is-loading');
        }, 1000);
      }
    } //Handle step 3
    else if ($('#checkout-3').length) {
      let paymentMethod = {};

      if ($('.method-card.is-selected').length === 0) {
        toasts.service.error('', 'fa fa-close', 'Veillez saisir le mode de Payement', 'bottomRight', 2500);
        setTimeout(function () {
          $this.removeClass('is-loading');
        }, 1000);
      } else {
        checkout.step = parseInt(checkout.step) + 1;
        localStorage.setItem('checkout', JSON.stringify(checkout));
        paymentMethod.icon = $('.method-card.is-selected').find('img').attr('src');
        paymentMethod.name = $('.method-card.is-selected').find('h3').text();
        paymentMethod.description = $('.method-card.is-selected').find('p').text();
        checkout.paymentMethod = paymentMethod;
        localStorage.setItem('checkout', JSON.stringify(checkout));
        setTimeout(function () {
          window.location.href = next;
          $this.removeClass('is-loading');
        }, 1000);
      }
    } //Handle step 4
    else if ($('#checkout-4').length) {
      let checkout = JSON.parse(localStorage.getItem('checkout'));
      checkout.step = parseInt(checkout.step);
      localStorage.setItem('checkout', JSON.stringify(checkout));
      checkout.orderNotes = $('#checkout-notes').val();
      localStorage.setItem('checkout', JSON.stringify(checkout));
      SendOrderInfo();
      setTimeout(function () {
        window.location.href = prev;
        $this.removeClass('is-loading');
      }, 1000);
    }
  }); //Checkout back button

  $('.checkout-back').on('click', function (e) {
    let checkout = JSON.parse(localStorage.getItem('checkout'));
    let $this = $(this);
    $this.addClass('is-loading');

    if ($this.attr('data-checkout-step') !== undefined) {
      e.preventDefault();
      checkout.step = parseInt(checkout.step) - 1;
      localStorage.setItem('checkout', JSON.stringify(checkout)); //Back from step 2 to step 1

      if ($('#checkout-2').length) {
        setTimeout(function () {
          window.location.href = prev;
          $this.removeClass('is-loading');
        }, 1000);
      } //Back from step 3 to step 2
      else if ($('#checkout-3').length) {
        checkout.total = (parseFloat(checkout.total) - parseFloat(checkout.shipping)).toFixed(2);
        checkout.shipping = 0.00.toFixed(2);
        localStorage.setItem('checkout', JSON.stringify(checkout));
        setTimeout(function () {
          window.location.href = prev;
          $this.removeClass('is-loading');
        }, 1000);
      } //Back from step 4 to step 3
      else if ($('#checkout-4').length) {
        setTimeout(function () {
          window.location.href = prev;
          $this.removeClass('is-loading');
        }, 1000);
      }
    }
  }); //End Checkout

  $('#end-checkout-button').on('click', function () {
    let $this = $(this);
    $this.addClass('is-loading');
    convertCheckoutToOrder();
    setTimeout(function () {
      window.location.href = '/orders.html';
    }, 1200);
  }); //Credit card

  if ($('#credit-card').length) {
    let card = new Card({
      form: '.active form',
      container: '.card-wrapper'
    });
  } //Checkout mobile mode


  if ($('.action-bar').length) {
    //Js Media Query
    if (window.matchMedia('(max-width: 768px)').matches) {
      $('.action-bar').addClass('is-mobile');
      $('.shop-wrapper').addClass('is-mobile-mode');
      $('.main-sidebar, .shop-quickview, .cart-quickview, .filters-quickview').addClass('is-pushed-mobile');
      $('.pageloader, .infraloader').addClass('is-full');
    } else {
      $('.shop-wrapper').removeClass('is-mobile-mode');
      $('.main-sidebar, .shop-quickview, .cart-quickview, .filters-quickview').removeClass('is-pushed-mobile');
      $('.pageloader, .infraloader').removeClass('is-full');
    } //resize handler


    $(window).on('resize', function () {
      if (window.matchMedia('(max-width: 768px)').matches) {
        $('.action-bar').addClass('is-mobile');
        $('.shop-wrapper').addClass('is-mobile-mode');
        $('.main-sidebar, .shop-quickview, .cart-quickview, .filters-quickview').addClass('is-pushed-mobile');
        $('.pageloader, .infraloader').addClass('is-full');
      } else {
        $('.shop-wrapper').removeClass('is-mobile-mode');
        $('.main-sidebar, .shop-quickview, .cart-quickview, .filters-quickview').removeClass('is-pushed-mobile');
        $('.pageloader, .infraloader').removeClass('is-full');
      }
    });
  }

  $('.coupon-button').on('click',function () {
      checkCoupon();
  });
  if($("#order").length){
    getOrderInfo();
  }
});