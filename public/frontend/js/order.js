"use strict"; //Get order Id parameter from query string


let orderId = parseInt($.urlParam('orderId')); //Get order json details, passing the Id as parameter
function getOrder(orderId) {
  let userData = JSON.parse(localStorage.getItem('user'));
  $.ajax({
    url: 'frontend/data/orders.json',
    async: true,
    dataType: 'json',
    success: function success(data) {
      for (let i = 0; i < data.length; i++) {
        if (data[i].id === orderId) {
          console.log('DATA', data[i]); //Populate basic data

          $('#order-details-id let').html(data[i].id);
          $('#order-details-date let').html(data[i].date);
          $('#order-details-avatar').attr('src', 'http://via.placeholder.com/250x250');
          $('#order-details-avatar').attr('data-demo-src', data[i].contact.photoUrl);
          $('#order-details-contact').html(data[i].contact.name); //Payment tile

          if (data[i].paymentStatus === 'Paid') {
            $('#payment-tile').addClass('is-done');
          } else if (data[i].paymentStatus === 'Pending') {
            $('#payment-tile').addClass('has-warning');
          }

          $('#payment-tile span:nth-child(3)').html(data[i].paymentMethod);
          $('#payment-tile span:nth-child(2)').html(data[i].paymentStatus); //Shipping tile

          if (data[i].shippingTrackingId === null) {
            $('#shipping-tile span:nth-child(3)').html('Not shipped yet');
          } else {
            $('#shipping-tile').addClass('is-done');
            $('#shipping-tile span:nth-child(3) a').html(data[i].shippingTrackingId);
          }

          $('#shipping-tile span:nth-child(2)').html(data[i].shippingMethod); //Status tile

          if (data[i].status === 'Complete') {
            $('#status-tile').addClass('is-done');
          }

          $('#status-tile span:nth-child(2)').html(data[i].status); //Shipping Address

          let shippingAddressCode;

          if (userData.addresses[1].disabled === true) {
            shippingAddressCode = 0;
          } else {
            shippingAddressCode = data[i].shippingAddressId;
          }

          $('#shipping-address1').html(userData.addresses[shippingAddressCode].address1);
          $('#shipping-address2').html(userData.addresses[shippingAddressCode].address2);
          $('#shipping-city').html(userData.addresses[shippingAddressCode].city);
          $('#shipping-state').html(userData.addresses[shippingAddressCode].state);
          $('#shipping-postalCode').html(userData.addresses[shippingAddressCode].postalCode);
          $('#shipping-country').html(userData.addresses[shippingAddressCode].country); //Billing Address

          let billingAddressCode = data[i].billingAddressId;
          $('#billing-address1').html(userData.addresses[billingAddressCode].address1);
          $('#billing-address2').html(userData.addresses[billingAddressCode].address2);
          $('#billing-city').html(userData.addresses[billingAddressCode].city);
          $('#billing-state').html(userData.addresses[billingAddressCode].state);
          $('#billing-postalCode').html(userData.addresses[billingAddressCode].postalCode);
          $('#billing-country').html(userData.addresses[billingAddressCode].country); //Totals

          $('#order-subtotal-value').html(data[i].orderModel.subtotal.toFixed(2));
          $('#order-shipping-value').html(data[i].orderModel.shipping.toFixed(2));
          $('#order-tax-value').html(data[i].orderModel.taxes.toFixed(2));
          $('#order-grandtotal-value').html(data[i].orderModel.total.toFixed(2)); //Products

          $('.flex-table .flex-table-item').remove();

          for (let p = 0; p < data[i].products.length; p++) {
            let template = "\n                            <div class=\"flex-table-item product-container\" data-product-id=\"" + data[i].products[p].id + "\">\n                                <div class=\"product\">\n                                   <img src=\""+ data[i].products[p].photoUrl+"\"  alt=\"\">\n                                    <a class=\"product-details-link product-name\">" + data[i].products[p].name + "</a>\n                                </div>\n                                <div class=\"quantity\">\n                                    <span>" + data[i].products[p].quantity + "</span>\n                                </div>\n                                <div class=\"price\">\n                                    <span class=\"has-price\">" + data[i].products[p].price.toFixed(2) + "</span>\n                                </div>\n                                <div class=\"discount\">\n                                    <span class=\"has-price\">0</span>\n                                </div>\n                                <div class=\"total\">\n                                    <span class=\"has-price\">" + (data[i].products[p].price * data[i].products[p].quantity).toFixed(2) + "</span>\n                                </div>\n                            </div>\n                        ";
            $.when($('.flex-table').append(template)).done(function () {
              //Make product links clickable
              initOrderDetailsLinks();
            });
          }
        }
      }
    }
  });
}

function getDate(datecom){
  let tabD=['Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi','Dimanche'];
  let tabM=['Janvier','Fevrier','Mars','Avril','Mai','Juin','Juillet','Août','Septembre','Octobre','Nomvembre','Decembre'];
  let FirstCut =datecom.split('-');
  let SecondCut = FirstCut[2].split(' ');
  let FinalCut = SecondCut[1].split('.');
  let TimeCut = FinalCut[0].split(':');
  let chaine=""+SecondCut[0]+"-"+tabM[parseInt(FirstCut[1])]+"-"+FirstCut[0]+" à "+TimeCut[0]+"h :"+TimeCut[1]+"min";
  return chaine;
}

function ConvertToJs(){
  let orders=JSON.parse($('#order-details').attr('data-orders'));
  let userData = JSON.parse(localStorage.getItem('user'));
  let orderProducts = [];
  let orderItem=[];
  let newOrder = [];
  let produits =[];
  for(let i=0 ; i<orders.length;i++) {
    for (let j = 0; j < orders[i]['cart'].products.length; j++) {
      orderItem = {
        id: orders[i]['cart'].products[j].id,
        name: orders[i]['cart'].products[j].name,
        price: orders[i]['cart'].products[j].price,
        quantity: orders[i]['cart'].products[j].quantity,
        photoUrl: orders[i]['cart'].products[j].images[0].url
      };
      orderProducts.push(orderItem);
    }

    let datC = getDate(orders[i]['datecom'].date);
    newOrder = {
      id: orders[i].id,
      numero: orders[i]['numero'],
      total: orders[i]["cart"].total,
      date: datC,
      status: orders[i]['statut'],
      completed: 12,
      shippingMethod: orders[i]['mode_liv'],
      shippingAddress : orders[i]['info_liv'],
      orderModel: {
        subtotal: orders[i]["cart"].total,
        taxes: 0,
        shipping: 0,
        total: orders[i]["cart"].total
      },
      //products: checkout.items,
      products: orderProducts,
      contact: {
        name: userData.username,
        photoUrl: ""
      }
    };
    userData.orders.push(newOrder);
    orderProducts = [];
  }
  localStorage.setItem('user', JSON.stringify(userData));
}

function getOrder2(orderId){
  ConvertToJs();
  let userData = JSON.parse(localStorage.getItem('user'));
  $('.flex-table-item').hide();
  for (let i = 0; i < userData.orders.length; i++) {
      if (userData.orders[i].id === orderId){
        $('#order-details-id').html(userData.orders[i].numero);
        $('#order-details-date').html(userData.orders[i].date);
        $('#shipping-address1').html(userData.orders[i].shippingAddress['address']);
        $('#shipping-city').html(userData.orders[i].shippingAddress['street']);
        $('#shipping-postalCode').html(userData.orders[i].shippingAddress['cp']);
        $('#shipping-country').html(userData.orders[i].shippingAddress['country']);
        $('#shipping-state').html(userData.orders[i].shippingAddress['town']);
        $('#order-details-avatar').attr('src', userData.photoUrl);
        $('#order-details-contact').html(userData.firstName+ ' ' + userData.lastName);

        if (userData.orders[i].paymentMethod !== "En Espèce" ) {
          $('#payment-tile').addClass('is-done');
          $('.is-pay').html("Payé");
        }
        else if(userData.orders[i].status === 'Livré') {
          $('#payment-tile').addClass('is-done');
          $('.is-pay').html("Payé");
        }
        else{
          $('#payment-tile').addClass('has-warning');
          $('.is-pay').html("Non Payé");
        }
        $('.payement').html(userData.orders[i].paymentMethod);
        $('.shipping').html(userData.orders[i].shippingMethod);
        $('.statut').html(userData.orders[i].status);
        for(let j=0 ; j < userData.orders[i].products.length; j++){
          let template = "\n                            <div class=\"flex-table-item product-container\" data-product-id=\""
              + userData.orders[i].id + "\">\n                                <div class=\"product\">\n                                    <img src=\""
              + userData.orders[i].products[j].photoUrl + "\" >\n                                    <a class=\"product-details-link product-name\">"
              + userData.orders[i].products[j].name + "</a>\n                                </div>\n                                <div class=\"quantity\">\n                                    <span>"
              + userData.orders[i].products[j].quantity + "</span>\n                                </div>\n                                <div class=\"price\">\n                                    <span class=\"has-price\">"
              + userData.orders[i].products[j].price + "</span>\n                                </div>\n                                <div class=\"discount\">\n                                    <span class=\"has-price\">0</span>\n                                </div>\n                                <div class=\"total\">\n                                    <span class=\"has-price\">"
              + (userData.orders[i].products[j].price * userData.orders[i].products[j].quantity).toFixed(2) + "</span>\n                                </div>\n                            </div>\n                        ";
          $.when($('.flex-table').append(template)).done(function () {
            //Make product links clickable
            initOrderDetailsLinks();
          });
        }
        $('#order-subtotal-value').html(userData.orders[i].total);
        $('#checkout-shipping-value').html(0);
        $('#order-total-value').html(userData.orders[i].total);
      }
  }

  }

$(document).ready(function () {
  if ($('#order-details').length) {
    //Get product details
    getOrder2(orderId);
  }
});