let container = $("#my-products-container .products");

function ajaxShowProducts() {
    const showMore = $(".show-more");

    $.ajax({
        type: "GET",
        url: showProductsRoute,
        data: {
            "max": container.attr("data-max"),
            "start":container.attr("data-show"),
        },
        async: true,
        dataType: "JSON",
        beforeSend: () => {
            // spinner sur le button showmore
            showMore.find("a").addClass("is-loading")
        },
        success: (data) => {
            // suppime le spinner sur le button showmore
            showMore.find("a").removeClass("is-loading")
            //console.log(data);
            const l = $(".products .product-container:last-child");
            // cache le button showMore s'il n'y a pas de nouvaux produits
            if (!data["data"].length)
                showMore.hide();
            // ajout des produit dans la div reservée à cette effet
            data["data"].forEach(product=>{
                container.append(addSingleProduct(product));
            });
            // mise à jour du nouveau nombre d'elements affichés
            container.attr("data-show",data["itemsShow"]);
            // cache le button showMore s'il n'y a pas de produits à afficher après
            if(data["showMore"])showMore.show();else showMore.hide();
            // maintient de du scroll de la souris dans la zone du dernier produit vu
            if(l.position())$(window).scrollTop(l.position().top);
            // message d'alert s'il n'y aucun produit trouvé
            if(container.attr("data-show")==="0")
                toasts.service.error('', 'fas fa-plus', 'Aucun produit trouvé', 'bottomRight', 2500);

            $('.account-loader').removeClass('is-active');
            if(container.attr("data-show")==="0")
                $("#my-products-empty-placeholder").removeClass("is-hidden")
            else
                $("#my-products-empty-placeholder").addClass("is-hidden")
        },
        error: () => {
            showMore.find("a").removeClass("is-loading")
            toasts.service.error('', 'fas fa-plus', 'Problèmes de connexion avec le serveur', 'bottomRight', 2500);
        }
    });
}

function ajaxDeleteProduct(idProduct,productContainer) {
    $.ajax({
        type: "POST",
        url: deleteProductRoute,
        data: {
            "idProduit": idProduct,
        },
        async: true,
        dataType: "JSON",
        beforeSend: () => {
            $('.account-loader').addClass('is-active');
        },
        success: (data) => {
            let show = parseInt(container.attr("data-show"));

            toasts.service.success('', 'fas fa-plus', data["success"], 'bottomRight', 2500);

            productContainer.remove();
            container.attr("data-show",(show>1)?show-1:show)

            $('.account-loader').removeClass('is-active');
        },
        error: () => {
            $('.account-loader').removeClass('is-active');
            toasts.service.error('', 'fas fa-plus', 'Problèmes de connexion avec le serveur', 'bottomRight', 2500);
        }
    });
}

function addEvent() {
    $(document).on("click",".add",function (){
        let id = $(this).parents(".product-container").first().attr("data-product-id");

        window.location.href = modifyProductRoute+"/"+id;
    })
    $(document).on("click",".like",function (){
        let id = $(this).parents(".product-container").first().attr("data-product-id");
        ajaxDeleteProduct(id,$(this).parents(".product-container").first());
    })
}

function addSingleProduct(p)
{
    let prix = (p.prix*(1-(p.prixPromo/100))).toString();
    if(prix.indexOf('.')!==-1)
        prix = prix.slice(0,prix.indexOf('.')+2);
    prix += (" " + "F CFA");
    const nom = truncateString(p.nom.toUpperCase(),30);
    let image = "http://via.placeholder.com/500x500/ffffff/999999";
    if(p.images.length)image = p.images[0];
    return `
        <div class="column is-3 product-container" data-product-id="${p.id}">
            <div class="flat-card">
                <div class="image" >
                    <img src="${imageProdPath+image}" style="width: 100px;height: 100px" data-action="zoom" alt="" class="" >
                </div>
                <div class="product-info has-text-centered">
                    <p class="product-price">
                        ${prix}
                    </p>
                    <a href="${modifyProductRoute}/${p.id}"><h3 class="product-name">${nom}</h3></a>
                </div>
                <div class="actions">
                    <div class="like">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                    </div>
                    <div class="add">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg>                    </div>
                    </div>
            </div>
        </div>
    `;
}

$(document).ready(function () {

    ajaxShowProducts();

    addEvent();

    $(".show-more").on("click",function (e) {
        e.preventDefault();
        ajaxShowProducts();
    })
});
