//Get and populate orders grid

function getOrdersGrid() {
  let userData = JSON.parse(localStorage.getItem('user'));
  let primaryColor;
  let modifierClass;
  let icon;
  let truckIcon = feather.icons.truck.toSvg();
  let clockIcon = feather.icons.clock.toSvg();
  let checkIcon = feather.icons.check.toSvg();
  let packageIcon = feather.icons.package.toSvg();
  let ccIcon = feather.icons['credit-card'].toSvg();
  let blockedIcon = feather.icons['alert-octagon'].toSvg(); //If not logged in, hide account

  if (!userData.isLoggedIn) {
    $('#orders-main, #orders-main-placeholder').toggleClass('is-hidden');
  } else if (userData.orders.length === 0) {
    $('#orders-main, #orders-empty-placeholder').toggleClass('is-hidden');
  } //Load orders
  else {
      //Remove orders
      $('#orders-main .column').remove(); //Loop orders

      for (let i = 0; i < userData.orders.length; i++) {
        //Apply status color
        if (userData.orders[i].status === 'new') {
          primaryColor = '#00b289';
          modifierClass = 'is-success';
          icon = packageIcon;
        } else if (userData.orders[i].status === 'Shipping') {
          primaryColor = '#0023ff';
          modifierClass = 'is-primary';
          icon = truckIcon;
        } else if (userData.orders[i].status === 'Livré') {
          primaryColor = '#0023ff';
          modifierClass = 'is-primary';
          icon = checkIcon;
        } else if (userData.orders[i].status === 'En cours de Livraison') {
          primaryColor = '#00b289';
          modifierClass = 'is-success';
          icon = packageIcon;
        } else if (userData.orders[i].status === 'Processing') {
          primaryColor = '#eda514';
          modifierClass = 'is-warning';
          icon = ccIcon;
        } else if (userData.orders[i].status === 'Blocked') {
          primaryColor = '#FF7273';
          modifierClass = 'is-danger';
          icon = blockedIcon;
        }

        let template = "\n                <div class=\"column is-4\">\n                    <div class=\"flat-card order-card has-popover-top\" data-order-id=\"" + userData.orders[i].id + "\">\n                        <div class=\"order-info\">\n                            <span><a class=\"order-details-link\" onclick=\"return true\">COM-" + userData.orders[i].numero + "</a></span>\n                            <span class=\"tag " + modifierClass + "\">" + userData.orders[i].status + "</span>\n                        </div>\n                        <!-- Progress Circle -->\n                        <div class=\"circle-chart-wrapper\">\n                            <svg class=\"circle-chart\" viewbox=\"0 0 33.83098862 33.83098862\" width=\"140\" height=\"140\" xmlns=\"http://www.w3.org/2000/svg\">\n                                <circle class=\"circle-chart-background\" stroke=\"#efefef\" stroke-width=\"2\" fill=\"none\" cx=\"16.91549431\" cy=\"16.91549431\" r=\"15.91549431\" />\n                                <circle class=\"circle-chart-circle\" stroke=\"" + primaryColor + "\" stroke-width=\"2\" stroke-dasharray=\"" + userData.orders[i].completed + ",100\" stroke-linecap=\"round\" fill=\"none\" cx=\"16.91549431\" cy=\"16.91549431\" r=\"15.91549431\" />\n                            </svg>\n                            <!-- Icon -->\n                            <div class=\"chart-icon\">\n                                " + icon + "\n                            </div>\n                            <!-- Label -->\n                            <div class=\"ring-title has-text-centered\">\n                                <span>" + userData.orders[i].completed + "% Complete</span>\n                            </div>\n                        </div>\n                    </div>\n\n                    <div class=\"webui-popover-content\">\n                        <!-- Popover Block -->\n                        <div class=\"popover-flex-block\">\n                            <img class=\"staff-avatar\" src=\"" + userData.photoUrl+ "\" alt=\"\">\n                            <div class=\"content-block\">\n                                <label>Effectué par </label>\n                                <span>" + userData.firstName+" "+userData.lastName + "</span>\n                            </div>\n                        </div>\n                        <!-- Popover Block -->\n                        <div class=\"popover-flex-block\">\n                            <div class=\"icon-block\">\n                                " + clockIcon + "\n                            </div>\n                            <div class=\"content-block\">\n                                <label>Commandé le </label>\n                                <span>" + userData.orders[i].date + "</span>\n                            </div>\n                        </div>\n                        <!-- Popover Block -->\n                        <div class=\"popover-flex-block\">\n                            <div class=\"icon-block\">\n                                " + ccIcon + "\n                            </div>\n                            <div class=\"content-block\">\n                                <label>Prix Total</label>\n                                <span>" + userData.orders[i].total + "</span>\n                            </div>\n                        </div>\n                        <!-- Popover Block -->\n                        <div class=\"popover-flex-block\">\n                            <div class=\"icon-block\">\n                                " + truckIcon + "\n                            </div>\n                            <div class=\"content-block\">\n                                <label>Méthode de Livraison</label>\n                                <span>" + userData.orders[i].shippingMethod + "</span>\n                            </div>\n                        </div>\n                    </div>\n                </div>\n            ";
        $.when($('#orders-main').append(template)).done(function () {
          initPopovers(); //Hide Loader

          $('.account-loader').addClass('is-hidden'); //Init Order details

          initOrderDetailsLinks(orderRoute);
        });
      }
    }
} //Get and populate orders List

function getOrders_list_sorted() {
  let userData = JSON.parse(localStorage.getItem('user'));
  let primaryColor;
  let modifierClass;
  let icon;
  let truckIcon = feather.icons.truck.toSvg();
  let clockIcon = feather.icons.clock.toSvg();
  let checkIcon = feather.icons.check.toSvg();
  let packageIcon = feather.icons.package.toSvg();
  let ccIcon = feather.icons['credit-card'].toSvg();
  let blockedIcon = feather.icons['alert-octagon'].toSvg();
  let supportIcon = feather.icons['life-buoy'].toSvg();
  $('#orders-main .order-long-card').remove(); //Loop orders

  for (let i = 0; i < userData.orders.length; i++) {
    //Apply status color
    if (userData.orders[i].status === 'Shipping') {
      primaryColor = '#0023ff';
      modifierClass = 'is-primary';
      icon = truckIcon;
    } else if (userData.orders[i].status === 'Livré') {
      primaryColor = '#0023ff';
      modifierClass = 'is-primary';
      icon = checkIcon;
    } else if (userData.orders[i].status === 'En cours de Livraison') {
      primaryColor = '#00b289';
      modifierClass = 'is-success';
      icon = packageIcon;
    } else if (userData.orders[i].status === 'Processing') {
      primaryColor = '#eda514';
      modifierClass = 'is-warning';
      icon = ccIcon;
    } else if (userData.orders[i].status === 'En Attente') {
      primaryColor = '#FF7273';
      modifierClass = 'is-danger';
      icon = blockedIcon;
    }

    let template = "\n                <div class=\"order-long-card\" data-order-id=\"" + userData.orders[i].id + "\">\n                    <div class=\"left-side\">\n                        <div class=\"order-header\">\n                            <h3>COMMANDE" + userData.orders[i].numero + "</h3>\n                            <span class=\"date\">" + userData.orders[i].date + "</span>\n                            <span class=\"tag is-primary\">" + userData.orders[i].status + "</span>\n                            <span class=\"order-total\">" + userData.orders[i].total + "</span>\n                        </div>\n                        <div class=\"ordered-products has-slimscroll\">\n                            <!--Loader-->\n                            <div class=\"products-loader is-active\">\n                                <div class=\"loader is-loading\"></div>\n                            </div>\n                        </div>\n                    </div>\n                    <div class=\"right-side\">\n                        <img class=\"side-bg\" src=\"img/logo/nephos-greyscale.svg\" alt=\"\">\n                        <div class=\"meta-header\">\n                            <img class=\"staff-avatar\" src=\"" + userData.photoUrl+ "\" alt=\"\">\n                            <div class=\"inner-meta\">\n                                <span>Effectué par </span>\n                                <span>" + userData.firstName+" "+userData.lastName + "</span>\n                            </div>\n                            <a class=\"support\">\n                                " + supportIcon + "\n                            </a>\n                        </div>\n\n                        <div class=\"meta-actions\">\n                            <a class=\"button is-rounded is-fullwidth primary-button raised order-details-link\">Détail de la commande</a>\n                            <a class=\"button is-rounded is-fullwidth grey-button rounded\">Invoice</a>\n                        </div>\n                    </div>\n                </div>\n            ";
    $.when($('#orders-main .column.is-12').append(template)).done(function () {
      //Hide Loader
      $('.account-loader').addClass('is-hidden');
    });
  } //Load products for each order


  loadOrdersListProducts(); //Init Order details

  initOrderDetailsLinks("/ecommerce/single-order");

}

function getOrders_grid_sorted() {
  let userData = JSON.parse(localStorage.getItem('user'));
  let primaryColor;
  let modifierClass;
  let icon;
  let truckIcon = feather.icons.truck.toSvg();
  let clockIcon = feather.icons.clock.toSvg();
  let checkIcon = feather.icons.check.toSvg();
  let packageIcon = feather.icons.package.toSvg();
  let ccIcon = feather.icons['credit-card'].toSvg();
  let blockedIcon = feather.icons['alert-octagon'].toSvg();
  let supportIcon = feather.icons['life-buoy'].toSvg();
  $('#orders-main .column').remove(); //Loop orders

  for (let i = 0; i < userData.orders.length; i++) {
    //Apply status color
    if (userData.orders[i].status === 'new') {
      primaryColor = '#00b289';
      modifierClass = 'is-success';
      icon = packageIcon;
    } else if (userData.orders[i].status === 'Shipping') {
      primaryColor = '#0023ff';
      modifierClass = 'is-primary';
      icon = truckIcon;
    } else if (userData.orders[i].status === 'Livré') {
      primaryColor = '#0023ff';
      modifierClass = 'is-primary';
      icon = checkIcon;
    } else if (userData.orders[i].status === 'En cours de Livraison') {
      primaryColor = '#00b289';
      modifierClass = 'is-success';
      icon = packageIcon;
    } else if (userData.orders[i].status === 'Processing') {
      primaryColor = '#eda514';
      modifierClass = 'is-warning';
      icon = ccIcon;
    } else if (userData.orders[i].status === 'Blocked') {
      primaryColor = '#FF7273';
      modifierClass = 'is-danger';
      icon = blockedIcon;
    }

    let template = "\n                <div class=\"column is-4\">\n                    <div class=\"flat-card order-card has-popover-top\" data-order-id=\"" + userData.orders[i].id + "\">\n                        <div class=\"order-info\">\n                            <span><a class=\"order-details-link\" onclick=\"return true\">COM-" + userData.orders[i].numero + "</a></span>\n                            <span class=\"tag " + modifierClass + "\">" + userData.orders[i].status + "</span>\n                        </div>\n                        <!-- Progress Circle -->\n                        <div class=\"circle-chart-wrapper\">\n                            <svg class=\"circle-chart\" viewbox=\"0 0 33.83098862 33.83098862\" width=\"140\" height=\"140\" xmlns=\"http://www.w3.org/2000/svg\">\n                                <circle class=\"circle-chart-background\" stroke=\"#efefef\" stroke-width=\"2\" fill=\"none\" cx=\"16.91549431\" cy=\"16.91549431\" r=\"15.91549431\" />\n                                <circle class=\"circle-chart-circle\" stroke=\"" + primaryColor + "\" stroke-width=\"2\" stroke-dasharray=\"" + userData.orders[i].completed + ",100\" stroke-linecap=\"round\" fill=\"none\" cx=\"16.91549431\" cy=\"16.91549431\" r=\"15.91549431\" />\n                            </svg>\n                            <!-- Icon -->\n                            <div class=\"chart-icon\">\n                                " + icon + "\n                            </div>\n                            <!-- Label -->\n                            <div class=\"ring-title has-text-centered\">\n                                <span>" + userData.orders[i].completed + "% Complete</span>\n                            </div>\n                        </div>\n                    </div>\n\n                    <div class=\"webui-popover-content\">\n                        <!-- Popover Block -->\n                        <div class=\"popover-flex-block\">\n                            <img class=\"staff-avatar\" src=\"" + userData.photoUrl + "\" alt=\"\">\n                            <div class=\"content-block\">\n                                <label>Effectué par</label>\n                                <span>" + userData.firstName+" "+userData.lastName + "</span>\n                            </div>\n                        </div>\n                        <!-- Popover Block -->\n                        <div class=\"popover-flex-block\">\n                            <div class=\"icon-block\">\n                                " + clockIcon + "\n                            </div>\n                            <div class=\"content-block\">\n                                <label>Ordered on</label>\n                                <span>" + userData.orders[i].date + "</span>\n                            </div>\n                        </div>\n                        <!-- Popover Block -->\n                        <div class=\"popover-flex-block\">\n                            <div class=\"icon-block\">\n                                " + ccIcon + "\n                            </div>\n                            <div class=\"content-block\">\n                                <label>Order Total</label>\n                                <span>" + userData.orders[i].total + "</span>\n                            </div>\n                        </div>\n                        <!-- Popover Block -->\n                        <div class=\"popover-flex-block\">\n                            <div class=\"icon-block\">\n                                " + truckIcon + "\n                            </div>\n                            <div class=\"content-block\">\n                                <label>Shipping Method</label>\n                                <span>" + userData.orders[i].shippingMethod + "</span>\n                            </div>\n                        </div>\n                    </div>\n                </div>\n            ";
    $.when($('#orders-main').append(template)).done(function () {
      initPopovers(); //Hide Loader

      $('.account-loader').addClass('is-hidden'); //Init Order details

      initOrderDetailsLinks(orderRoute);
    });
  }

}


function TrierCommande(chaine) {
  $.ajax({
    type: "GET",
    url: "/ecommerce/trie_commande",
    data:{
      "critere":chaine
    },
    async: true,
    dataType: "JSON",
    beforeSend: () => {

    },
    success: (data) => {
      ConvertSQL3(data);
      $('.order-long-card').addClass('is-hidden');
      if ($('#orders-list').length) {
        getOrders_list_sorted();
      }
      if ($('#orders-grid').length) {
        getOrders_grid_sorted();
      }
      $('.order-long-card').removeClass('is-hidden');
    },
    error: () => {

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

function getDate2(datecom){
  let tabD=['Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi','Dimanche'];
  let tabM=['Janvier','Fevrier','Mars','Avril','Mai','Juin','Juillet','Août','Septembre','Octobre','Nomvembre','Decembre'];
  let FirstCut =datecom.split('-');
  let SecondCut = FirstCut[2].split('T');
  let FinalCut = SecondCut[1].split(':');
  let chaine=""+SecondCut[0]+"-"+tabM[parseInt(FirstCut[1])]+"-"+FirstCut[0]+" à "+FinalCut[0]+"h :"+FinalCut[1]+"min";
  return chaine;
}

function ConvertSQL3(commande){
  let userData = JSON.parse(localStorage.getItem('user'));
  userData.orders = [];
  let orders=commande;
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

    let datC = getDate2(orders[i]['datecom']);
    let com = 10;
    if(orders[i]['statut'] === "Livré"){
      com=100;
    }else if(orders[i]['statut'] === "En cours de Livraison"){
      com=30;
    }
    newOrder = {
      id: orders[i].id,
      numero : orders[i].numero,
      total: orders[i]["cart"].total,
      date: datC,
      status: orders[i]['statut'],
      completed: com,
      shippingMethod: orders[i]['mode_liv'],
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
  console.log(userData.orders);
  localStorage.setItem('user', JSON.stringify(userData));
}

function ConvertSQL(){
  let orders=JSON.parse($('#orders-list').attr('data-orders'));
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
    let com = 10;
    if(orders[i]['statut'] === "Livré"){
      com=100;
    }else if(orders[i]['statut'] === "En cours de Livraison"){
      com=30;
    }
    let datC = getDate(orders[i]['datecom'].date);
    newOrder = {
      id: orders[i].id,
      numero : orders[i].numero,
      total: orders[i]["cart"].total,
      date: datC,
      status: orders[i]['statut'],
      completed: com,
      shippingMethod: orders[i]['mode_liv'],
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

function ConvertSQL2(){
  let orders=JSON.parse($('#orders-grid').attr('data-orders'));
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
    console.log(orderProducts);

    let datC = getDate(orders[i]['datecom'].date);
    let com = 10;
    if(orders[i]['statut'] === "Livré"){
      com=100;
    }else if(orders[i]['statut'] === "En cours de Livraison"){
      com=30;
    }
    newOrder = {
      id: orders[i].id,
      numero : orders[i].numero,
      total: orders[i]["cart"].total,
      date: datC,
      status: orders[i]['statut'],
      completed: com,
      shippingMethod: orders[i]['mode_liv'],
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

function getOrdersList() {
  let userData = JSON.parse(localStorage.getItem('user'));
  let supportIcon = feather.icons['life-buoy'].toSvg(); //If not logged in, hide account

  if (!userData.isLoggedIn) {
    $('#orders-main, #orders-main-placeholder').toggleClass('is-hidden');
  } else if (userData.orders.length === 0) {
    $('#orders-main, #orders-empty-placeholder').toggleClass('is-hidden');
  } //Load orders
  else {
    //Remove orders
    $('#orders-main .order-long-card').remove(); //Loop orders

    for (let i = 0; i < userData.orders.length; i++) {
      //Apply status color

      let template = "\n                <div class=\"order-long-card\" data-order-id=\"" + userData.orders[i].id + "\">\n                    <div class=\"left-side\">\n                        <div class=\"order-header\">\n                            <h3>COMMANDE  " + userData.orders[i].numero + "</h3>\n                            <span class=\"date\">" + userData.orders[i].date + "</span>\n                            <span class=\"tag is-primary statut\">" + userData.orders[i].status + "</span>\n                            <span class=\"order-total\">" + userData.orders[i].total + "</span>\n                        </div>\n                        <div class=\"ordered-products has-slimscroll\">\n                            <!--Loader-->\n                            <div class=\"products-loader is-active\">\n                                <div class=\"loader is-loading\"></div>\n                            </div>\n                        </div>\n                    </div>\n                    <div class=\"right-side\">\n                        <img class=\"side-bg\" src=\"img/logo/nephos-greyscale.svg\" alt=\"\">\n                        <div class=\"meta-header\">\n                            <img src=\"" + userData.photoUrl + "\" alt=\"\">\n                            <div class=\"inner-meta\">\n                                <span>Effectué par </span>\n                                <span>" + userData.firstName+" "+userData.lastName + "</span>\n                            </div>\n                            <a class=\"support\">\n                                " + supportIcon + "\n                            </a>\n                        </div>\n\n                        <div class=\"meta-actions\">\n                            <a class=\"button is-rounded is-fullwidth primary-button raised order-details-link\">Détail de la commande</a>\n                            <a class=\"button is-rounded is-fullwidth grey-button rounded\">Invoice</a>\n                        </div>\n                    </div>\n                </div>\n            ";

      $.when($('#orders-main .column.is-12').append(template)).done(function () {
        //Hide Loader
        $('.account-loader').addClass('is-hidden');
      });
    } //Load products for each order


    loadOrdersListProducts(); //Init Order details

    initOrderDetailsLinks("/ecommerce/single-order");
  }
} //Populate inner product lists in order lists


function loadOrdersListProducts() {
  let userData = JSON.parse(localStorage.getItem('user'));
  $('.order-long-card').each(function () {
    let $this = $(this);
    let orderId = parseInt($this.attr('data-order-id'));
    let $container = $this.find('.ordered-products');
    let products;

    for (let i = 0; i < userData.orders.length; i++) {
      if (userData.orders[i].id == orderId) {
        products = userData.orders[i].products;
      }
    }

    for (let p = 0; p < products.length; p++) {
      let template = "\n                        <div class=\"ordered-product\">\n                            <img src=\""+ products[p].photoUrl+"\"  alt=\"\">\n                            <div class=\"product-meta\">\n                                <span class=\"name\">" + products[p].name + "</span>\n                                <span class=\"price\">\n                                    <span>" + parseFloat(products[p].price).toFixed(2) + "</span>\n                                    <span>x <let>" + products[p].quantity + "</let></span>\n                                </span>\n                            </div>\n                            <div class=\"product-subtotal\">\n                                <span>Total</span>\n                                <span>" + (parseFloat(products[p].price) * parseFloat(products[p].quantity)).toFixed(2) + "</span>\n                            </div>\n                        </div>\n                    ";
      $.when($container.append(template)).done(function () {
        $this.find('.products-loader').removeClass('is-active');
      });
    }
  });
}

$(document).ready(function () {
  //If orders grid page
  if ($('#orders-grid').length) {
    ConvertSQL2();
    getOrdersGrid();
  } //If orders list page

  if ($('#orders-list').length) {
    ConvertSQL();
    getOrdersList();
  }

  $('.triOrders').on('change' , function () {
      let chaine = $('#TriCom').val();
    TrierCommande(chaine);

  })
});