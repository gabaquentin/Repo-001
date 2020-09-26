 //Empty cart object initialization

let cart = {}; //Populate default cart object

cart.items = 0;
cart.total = 0.00;
cart.products = []; //If user cart is null

if (JSON.parse(localStorage.getItem('cart')) === null) {
  localStorage.setItem('cart', JSON.stringify(cart));
} //Init cart sidebar spinners


function initSpinners() {
  $('.sidebar-spinner').spinner('changing', function (e, newVal, oldVal) {
    let $this = $(this);
    $this.closest('.quantity').find('.spinner-value').html(newVal);
    $this.closest('li').find('.item-price span').html(newVal);
    $('.cart-quickview .view-cart-button').addClass('is-hidden');
    $('.cart-quickview .update-cart-button').removeClass('is-hidden');
  });
} //Init cart page spinners


function initCartSpinners() {
  $('.main-cart-spinner').spinner('changing', function (e, newVal, oldVal) {
    let $this = $(this);
    $this.closest('li').find('.spinner-value').html(newVal);
    $('#init-checkout').addClass('is-hidden');
    $('#update-cart-page').removeClass('is-hidden');
  });
} //Update cart sidebar (when products are added, updated or removed)

//Fonction qui permet de mettre à jour le panier
function updateCartSidebar() {
  let cartObject = {};
  let productsCount = $('.cart-quickview .product-container').length;
  cartObject.products = [];
  cartObject.items = productsCount;
  $('.cart-quickview .product-container').each(function () {
    let $this = $(this);
    let productId = parseInt($this.attr('data-product-id'));
    let productName = $this.find('.item-name').text();
    let productPrice = parseFloat($this.find('.item-price').text());
    let productQuantity = parseInt($this.find('.quantity input').val());
    let productImage = $this.find('img').attr('src');
    cartObject.products.push({
      id: productId,
      name: productName,
      quantity: productQuantity,
      price: productPrice,
      images: [{
        url: productImage
      }]
    });
  });
  localStorage.setItem('cart', JSON.stringify(cartObject));
  console.log(cartObject);
} //Reusable add to cart function

 //Fonction qui permet d'afficher un produit du panier dans le panier de la gauche
function getProductFromCart(p) {
  let plusIcon = feather.icons.plus.toSvg();
  let minusIcon = feather.icons.minus.toSvg();
  let closeIcon = feather.icons.x.toSvg();
  let price = p.price.toFixed(2);
  return `
        <li class="clearfix product-container" data-product-id="${p.id}">
            <img src="${p.images[0].url}" data-demo-src="" alt="" >
            <span class="item-meta">
                <span class="item-name">${p.name}</span>
                <span class="item-price"><let>${price}</let> x <span>${p.quantity}</span></span>
            </span>
            <span class="quantity">
                <div id="spinner-${p.id}"   data-trigger="spinner" class="sidebar-spinner">
                    <input class="hidden-spinner" type="hidden" value="${p.quantity}" data-spin="spinner" data-rule="quantity" data-min="1" data-max="99">
                    <a class="spinner-button is-remove" href="javascript:;" data-spin="down">${minusIcon}</a>
                    <span class="spinner-value">${p.quantity}</span>
                    <a class="spinner-button is-add" href="javascript:;" data-spin="up">${plusIcon}</a>
                </div>
            </span>
            <span class="remove-item remove-from-cart-action has-simple-popover" data-content="Remove from Cart" data-placement="top" onclick="return false">${closeIcon}</span>
        </li>
    `;
}

//Fonction qui permet d'afficher un produit du panier dans la page Panier
function getProductToCart(p) {
  let plusIcon = feather.icons.plus.toSvg();
  let minusIcon = feather.icons.minus.toSvg();
  let removeIcon = feather.icons['trash-2'].toSvg();
  let closeIcon = feather.icons.x.toSvg();
  let price = p.price.toFixed(2);
  return `
        <div class="flat-card is-auto cart-card product-container" data-product-id="${p.id}" >
            <ul class="cart-content">
                <li><img src="${p.images[0].url}" data-demo-src="" alt="">
                    <span class="product-info"><span>${p.name}</span><span>${p.category}</span></span>
                    <span class="product-price"><span>Prix</span><span>${price}</span></span>
                    <div data-trigger="spinner" class="main-cart-spinner">
                        <input class="hidden-spinner" type="hidden" value="${p.quantity}" data-spin="spinner" data-rule="quantity" data-min="1" data-max="99">
                        <a class="spinner-button is-remove" href="javascript:;" data-spin="down">${minusIcon}</a>
                        <span class="spinner-value">${p.quantity}</span>
                        <a class="spinner-button is-add" href="javascript:;" data-spin="up">${plusIcon}</a>
                    </div>
                    <span class="action"><span class="action-link is-remove remove-from-cartpage-action has-simple-popover" data-content="Remove from Cart" data-placement="top" onclick="return false">
                        <a href="#">${removeIcon}</a></span>
                    </span>
                </li>
            </ul>
        </div>
    `;
}

//Fonction qui permet d'afficher tout les produit du panier dans le cart de la gauche
function getCart() {
  let plusIcon = feather.icons.plus.toSvg();
  let minusIcon = feather.icons.minus.toSvg();
  let closeIcon = feather.icons.x.toSvg();
  let data = JSON.parse(localStorage.getItem('cart'));
  let cartTotal = 0.00; //Populate cart sidebar

  $('.cart-loader').addClass('is-active');
  $('.cart-quickview .cart-body ul').empty();
  if (data.products.length > 0) {
    $('.cart-quickview .empty-cart').addClass('is-hidden');
    for (let i = 0; i < data.products.length; i++) {
      cartTotal = parseFloat(cartTotal) + parseFloat(data.products[i].price) * parseInt(data.products[i].quantity);
      let template = getProductFromCart(data.products[i]);
      $('.cart-quickview .cart-body ul').append(template);
      if (i == data.products.length - 1) {
        data.total = cartTotal;
        $('#quickview-cart-count let').html(data.items);
        localStorage.setItem('cart', JSON.stringify(data));
        $('.cart-quickview .cart-total').html(parseFloat(cartTotal).toFixed(2));
        initSpinners(); //Check viewport size

        if (!mobileTrue) {
          initPopovers();
        }

        removeFromCart();
      }
    }

    $('#cart-dot').removeClass('is-hidden');
    $('#mobile-cart-count').html(data.items);
    setTimeout(function () {
      $('.cart-loader').removeClass('is-active');
    }, 800);
  } else {
    $('#cart-dot').addClass('is-hidden');
    $('.cart-quickview .empty-cart').removeClass('is-hidden');
    cartTotal = 0.00;
    data.total = cartTotal;
    $('#mobile-cart-count').html('0');
    localStorage.setItem('cart', JSON.stringify(data));
    $('.cart-quickview .cart-total').html(parseFloat(cartTotal).toFixed(2));
  }
} //Reusable remove from cart function


function removeFromCart() {
  $('.remove-from-cart-action').on('click', function () {
    let $this = $(this);
    let productId = parseInt($this.closest('.product-container').attr('data-product-id'));
    let data = JSON.parse(localStorage.getItem('cart'));
    $('.cart-loader').addClass('is-active'); //Update cart Data

    setTimeout(function () {
      data.products = $.grep(data.products, function (e) {
        return e.id != productId;
      });
      data.items = data.products.length;
      localStorage.setItem('cart', JSON.stringify(data));
      $('.webui-popover').removeClass('in').addClass('pop-out');
      getCart();
    }, 300); //Simulate loading

    setTimeout(function () {
      $('.cart-loader').removeClass('is-active');
      toasts.service.success('', 'fas fa-check', 'Produit retiré du panier avec succèss', 'bottomRight', 2500);
    }, 800);
  });
} //Disable cart sidebar (when on cart page or checkout)


function disableCartSidebar() {
  $('#open-cart').off();
  $('#open-cart').on('click', function () {
    if ($('.checkout-wrapper').length) {
      toasts.service.error('', 'fas fa-comment-alt', 'Cart sidebar is disabled during checkout', 'bottomRight', 2500);
    } else {
      toasts.service.error('', 'fas fa-comment-alt', 'Cart sidebar is disabled on cart page', 'bottomRight', 2500);
    }
  });
} //Get cart data for cart page


function getCartPage() {
  let plusIcon = feather.icons.plus.toSvg();
  let minusIcon = feather.icons.minus.toSvg();
  let removeIcon = feather.icons['trash-2'].toSvg();
  let data = JSON.parse(localStorage.getItem('cart'));
  let cartSubtotal = 0.00;
  let taxRate = 0.00; // 6% tax rate
  //Populate cart page

  $('.account-loader').addClass('is-active');
  $('#cart-page-products').empty();

  if (data.products.length > 0) {
    $('#cart-main-placeholder').addClass('is-hidden');
    $('.is-account-grid').removeClass('is-hidden');

    for (let i = 0; i < data.products.length; i++) {
      cartSubtotal = parseFloat(cartSubtotal) + parseFloat(data.products[i].price) * parseInt(data.products[i].quantity);
      let template = getProductToCart(data.products[i]);
      $('#cart-page-products').append(template);

      if (i == data.products.length - 1) {
        data.total = cartSubtotal;
        $('#cart-page-count').html(data.items);
        localStorage.setItem('cart', JSON.stringify(data));
        $('#cart-summary-subtotal').html(parseFloat(cartSubtotal).toFixed(2));
        $('#cart-summary-taxes').html(parseFloat(cartSubtotal * taxRate).toFixed(2));
        $('#cart-summary-total').html(parseFloat(cartSubtotal * taxRate + cartSubtotal).toFixed(2));
        initCartSpinners(); //Check viewport size

        if (!mobileTrue) {
          initPopovers();
        }

        removeFromCartPage();
      }
    }

    $('#cart-dot').removeClass('is-hidden');
    setTimeout(function () {
      $('.cart-loader').removeClass('is-active');
    }, 800);
  } else {
    $('.is-account-grid').addClass('is-hidden');
    $('#cart-main-placeholder').removeClass('is-hidden');
    cartSubtotal = 0.00;
    data.total = cartSubtotal;
    localStorage.setItem('cart', JSON.stringify(data));
    $('#cart-page-count').html('0');
    $('#cart-summary-subtotal').html(parseFloat(cartSubtotal).toFixed(2));
    $('#cart-summary-taxes').html(parseFloat(cartSubtotal * taxRate).toFixed(2));
    $('#cart-summary-total').html(parseFloat(cartSubtotal * taxRate + cartSubtotal).toFixed(2));
  }
} //Update cart page when update button is clicked


function updateCartPage() {
  let cartObject = {};
  let productsCount = $('#cart-page-products .product-container').length;
  cartObject.products = [];
  cartObject.items = productsCount;
  $('#cart-page-products .product-container').each(function () {
    let $this = $(this);
    let productId = parseInt($this.attr('data-product-id'));
    let productName = $this.find('.product-info span:first-child').text();
    let productCategory = $this.find('.product-info span:nth-child(2)').text();
    let productPrice = parseFloat($this.find('.product-price span:nth-child(2)').text());
    let productQuantity = parseInt($this.find('.main-cart-spinner input').val());
    let productImage = $this.find('img').attr('src');
    cartObject.products.push({
      id: productId,
      name: productName,
      category: productCategory,
      quantity: productQuantity,
      price: productPrice,
      images: [{
        url: productImage
      }]
    });
  });
  localStorage.setItem('cart', JSON.stringify(cartObject));
  console.log(cartObject);
} //Remove product from cart page


function removeFromCartPage() {
  $('.remove-from-cartpage-action').on('click', function () {
    let $this = $(this);
    let productId = parseInt($this.closest('.product-container').attr('data-product-id'));
    let data = JSON.parse(localStorage.getItem('cart'));
    $('.account-loader').addClass('is-active'); //Update cart Data

    setTimeout(function () {
      data.products = $.grep(data.products, function (e) {
        return e.id != productId;
      });
      data.items = data.products.length;
      localStorage.setItem('cart', JSON.stringify(data));
      $('.webui-popover').removeClass('in').addClass('pop-out');
      getCartPage();
      getCart();
    }, 300); //Simulate loading

    setTimeout(function () {
      $('.account-loader').removeClass('is-active');
      toasts.service.success('', 'fas fa-check', 'Le produit a été rétiré du panier avec succès ', 'bottomRight', 2500);
    }, 800);
  });
} //Build proto checkout object and pass it to checkout


  function initCheckout() {
  $('#init-checkout').on('click', function () {
    if (JSON.parse(localStorage.getItem('cart')) !== null) {
      localStorage.removeItem('checkout');
    }

    let cartData = JSON.parse(localStorage.getItem('cart'));
    let userData = JSON.parse(localStorage.getItem('user'));
    let $this = $(this);
    if(userData.isLoggedIn){
      let checkoutObject = {};
      checkoutObject.items = cartData.products;
      checkoutObject.shippingAddress = [];
      checkoutObject.count = cartData.items;
      checkoutObject.subtotal = parseFloat($('#cart-summary-subtotal').text());
      checkoutObject.coupon = 0;
      checkoutObject.remise = 0;
      checkoutObject.taxes = 0;
      checkoutObject.shipping = 0.00;
      checkoutObject.total = parseFloat($('#cart-summary-total').text());
      checkoutObject.step = 1;
      checkoutObject.username = userData.firstName + ' ' + userData.lastName;
      checkoutObject.avatar = userData.photoUrl;
      checkoutObject.orderNotes = '';
      localStorage.setItem('checkout', JSON.stringify(checkoutObject));
      setTimeout(function () {
        window.location.href = "/front/ecommerce/checkout_step1" ;
      }, 1200);

    }
    else{
      $this.addClass('is-loading');
      setTimeout(function () {
        window.location.href = "/login";
      }, 1200);
    }
  });
}

$(document).ready(function () {
  //Update cart button
  $('.update-cart-button').on('click', function () {
    let $this = $(this);
    $this.addClass('is-loading');
    $('.cart-loader').addClass('is-active');
    setTimeout(function () {
      updateCartSidebar();
      getCart();
    }, 300);
    setTimeout(function () {
      $this.removeClass('is-loading').addClass('is-hidden');
      $('.cart-quickview .view-cart-button').removeClass('is-hidden');
      $('.cart-loader').removeClass('is-active');
      toasts.service.success('', 'fas fa-check', 'Le panier a été mise à jour avec succès', 'bottomRight', 2500);
    }, 800);
  }); //Products Grid implementation

  if ($('#shop-grid').length) {
    //Add to cart
    $('.product-container .actions .add').off().on('click', function () {
      let $this = $(this);

      if ($('.cart-quickview').hasClass('is-active')) {
        $('.cart-loader').addClass('is-active');
      }

      setTimeout(function () {
        $.when(addToCart($this)).done(function () {
          getCart();
        });
      }, 300);
      setTimeout(function () {
        if ($('.cart-quickview').hasClass('is-active')) {
          $('.cart-loader').removeClass('is-active');
        }

        toasts.service.success('', 'fas fa-plus', 'Product successfully added to cart', 'bottomRight', 2500);
      }, 800);
    });
  } //Products List implementation


  if ($('#shop-list').length) {
    //Add to cart
    $('.product-container .actions .add').on('click', function () {
      let $this = $(this);

      if ($('.cart-quickview').hasClass('is-active')) {
        $('.cart-loader').addClass('is-active');
      }

      setTimeout(function () {
        $.when(AddEventToCart()).done(function () {
          getCart();
        });
      }, 300);
      setTimeout(function () {
        if ($('.cart-quickview').hasClass('is-active')) {
          $('.cart-loader').removeClass('is-active');
        }

        toasts.service.success('', 'fas fa-plus', 'Product successfully added to cart', 'bottomRight', 2500);
      }, 800);
    });
  } //If cart page


  if ($('#cart-page').length) {
    disableCartSidebar();
    getCartPage();
    setTimeout(function () {
      $('.account-loader').removeClass('is-active');
    }, 1200); //Init checkout button

    initCheckout(); //Update cart page

    $('#update-cart-page').on('click', function () {
      let $this = $(this);
      $this.addClass('is-loading');
      $('.account-loader').addClass('is-active');
      setTimeout(function () {
        updateCartPage();
        getCartPage();
      }, 300);
      setTimeout(function () {
        $this.removeClass('is-loading').addClass('is-hidden');
        $('#init-checkout').removeClass('is-hidden');
        $('.account-loader').removeClass('is-active');
        toasts.service.success('', 'fas fa-check', 'Le Panier a été ajouté avec succès', 'bottomRight', 2500);
      }, 800);
    }); //Add to cart from recently viewed

    $('.product-container .actions .add').on('click', function () {
      let $this = $(this);
      $('.account-loader').addClass('is-active');
      setTimeout(function () {
        $.when(addToCart($this)).done(function () {
          getCartPage();
          getCart();
        });
      }, 300);
      setTimeout(function () {
        $('.account-loader').removeClass('is-active');
        toasts.service.success('', 'fas fa-plus', 'Product successfully added to cart', 'bottomRight', 2500);
      }, 800);
    });
  }
});