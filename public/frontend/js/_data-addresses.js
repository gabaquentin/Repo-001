"use strict";

// billing address

var paysPaiement = $('.js-user-billing-country').data('billingCountry');
var postePaiement = $('.js-user-billing-postal').data('billingPostal');
var villePaiement = $('.js-user-billing-address2').data('billingAddress2');
var quartierPaiement = $('.js-user-billing-city').data('billingCity');
var addressPaiement = $('.js-user-billing-state').data('billingState');

var esa = $('.js-user-esa').data('esa') === 0;
// shipping address

var paysLivraison = $('.js-user-shipping-country').data('shippingCountry');
var posteLivraison = $('.js-user-shipping-postal').data('shippingPostal');
var villeLivraison = $('.js-user-shipping-address2').data('shippingAddress2');
var quartierLivraison = $('.js-user-shipping-city').data('shippingCity');
var addressLivraison = $('.js-user-shipping-state').data('shippingState');

var myAddresses = [{
  type: 'Billing Address',
  address1: '',
  address2: villePaiement,
  city: quartierPaiement,
  postalCode: postePaiement,
  state: addressPaiement,
  country: paysPaiement
}, {
  type: 'Shipping Address',
  disabled: esa,
  address1: '',
  address2: villeLivraison,
  city: quartierLivraison,
  postalCode: posteLivraison,
  state: addressLivraison,
  country: paysLivraison
}];