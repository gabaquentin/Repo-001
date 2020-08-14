function getSelectedCat() {
    let val = [];
    $('.category-block').each(function(i){
        let $this = $(this);
        if($this.find('input[type="checkbox"]').prop("checked"))
            val.push($(this).data("id"))
    })
    return val;
}

function ajaxShowProducts() {
    let container = $("#products-list .products");
    const searchValue = $(".nephos-search-filter").val();
    const order = $(".chosen-single span").text();
    const showMore = $(".show-more");
    const ville = $("#ville-filtre").val() ;
    const typeTransaction = $("#type-tansaction-filtre").val() ;
    const prix = $("#prix-filtre").val() ;
    const meuble = ($("#meuble-filtre").prop("checked"))?1:0 ;
    const nbreChambres = $("#chambre-filtre").val() ;
    const nbresallleBain = $("#salle-bain-filtre").val() ;
    const longueur = $("#longueur-filtre").val() ;
    const largeur = $("#largeur-filtre").val() ;


    $.ajax({
        type: "GET",
        url: showProductsRoute,
        data: {
            "max": container.attr("data-max"),
            "start":container.attr("data-show"),
            "searchValue":searchValue,
            "order":order,
            "idCategories": getSelectedCat(),
            "ville": ville,
            "typeTransaction": typeTransaction,
            "prix" : prix,
            "meuble" : meuble,
            "nbreChambres" : nbreChambres,
            "nbresalleBain" : nbresallleBain,
            "longueur" : longueur,
            "largeur" : largeur,
        },
        async: true,
        dataType: "JSON",
        beforeSend: () => {
            showMore.find("a").addClass("is-loading")
        },
        success: (data) => {
            showMore.find("a").removeClass("is-loading")
            //console.log(data);
            const l = $(".products .product-container:last-child");
            if (!data["data"].length)
                showMore.hide();

            data["data"].forEach(product=>{
                container.append(addSingleProduct(product));
            });

            container.attr("data-show",data["itemsShow"]);

            $('.search-count').html(data["itemsMax"]+" produits");

            if(data["showMore"])showMore.show();else showMore.hide();

            if(l.position())$(window).scrollTop(l.position().top);

            if(container.attr("data-show")==="0")
                toasts.service.error('', 'fas fa-plus', 'Aucun produit trouvé', 'bottomRight', 2500);
        },
        error: () => {
            showMore.find("a").removeClass("is-loading")
            toasts.service.error('', 'fas fa-plus', 'Problèmes de connexion avec le serveur', 'bottomRight', 2500);
        }
    });
}

function addSingleProduct(p)
{
    const prix = p.prix + " " + devise;
    const nom = p.nom.toUpperCase()
    let image = "http://via.placeholder.com/500x500/ffffff/999999";
    if(p.images.length)image = p.images[0];
    return `
        <div class="column is-3 product-container" data-product-id="${p.id}">
            <div class="flat-card">
                <div class="image" >
                    <img src="${imageProdPath+image}" style="width: 100px;height: 100px" data-action="zoom" alt="" class=""  style="">
                </div>
                <div class="product-info has-text-centered">
                    <a href="${detailProductRoute}/${p.id}"><h3 class="product-name">${nom}</h3></a>
                    <p class="product-price">
                        ${prix}
                    </p>
                </div>
                <div class="actions">
                    <div class="add"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-shopping-cart has-simple-popover" data-content="Ajouter au panier" data-placement="top"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg></div>
                    <div class="like"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-heart has-simple-popover" data-content="Ajouter à la Wishlist" data-placement="top"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg></div>
                </div>
            </div>
        </div>
    `;
}
function initSelect()
{
    $('select.native').each(function () {

        // Cache the number of options
        var $this = $(this),
            numberOfOptions = $(this).children('option').length;

        // Hides the select element
        $this.addClass('s-hidden');

        // Wrap the select element in a div
        $this.wrap('<div class="select"></div>');

        // Insert a styled div to sit over the top of the hidden select element
        $this.after('<div class="styledSelect"></div>');

        // Cache the styled div
        var $styledSelect = $this.next('div.styledSelect');

        // Show the first select option in the styled div
        $styledSelect.text($this.children('option').eq(0).text());

        // Insert an unordered list after the styled div and also cache the list
        var $list = $('<ul />', {
            'class': 'options'
        }).insertAfter($styledSelect);

        // Insert a list item into the unordered list for each select option
        for (var i = 0; i < numberOfOptions; i++) {
            $('<li />', {
                text: $this.children('option').eq(i).text(),
                rel: $this.children('option').eq(i).val()
            }).appendTo($list);
        }

        // Cache the list items
        var $listItems = $list.children('li');

        // Show the unordered list when the styled div is clicked (also hides it if the div is clicked again)
        $styledSelect.on('click', function (e) {
            e.stopPropagation();
            $('div.styledSelect.active').each(function () {
                $(this).removeClass('active').next('ul.options').hide();
            });
            $(this).toggleClass('active').next('ul.options').toggle();
        });

        // Hides the unordered list when a list item is clicked and updates the styled div to show the selected list item
        // Updates the select element to have the value of the equivalent option
        $listItems.on('click', function (e) {
            e.stopPropagation();
            $styledSelect.text($(this).text()).removeClass('active');
            $this.val($(this).attr('rel'));
            $list.hide();
            /* alert($this.val()); Uncomment this for demonstration! */
        });

        // Hides the unordered list when clicking outside of it
        $(document).on('click', function () {
            $styledSelect.removeClass('active');
            $list.hide();
        });

    });
}

function InitSpinner()
{
    $(".basic-spinner").InputSpinner({

        // button text/iconsclass="fa fa-plus"
        decrementButton: `<i class=" fa fa-minus "></i>`,
        incrementButton: `<i class=" fa fa-plus "></i>`,

        // class of input group
        groupClass: "spinner-control",

        // button class
        buttonsClass: "spinner-button",

        // text alignment
        textAlign: "center",

        // delay in milliseconds
        autoDelay: 500,

        // interval in milliseconds
        autoInterval: 100,

        // boost after these steps
        boostThreshold: 15,

        // boost multiplier
        boostMultiplier: 2,

        // detects the local from `navigator.language`, if null
        locale: null

    });

}

$(document).ready(function () {
    let container = $("#products-list .products")
    ajaxShowProducts();
    $(".chosen-select-no-single").change(function () {
        container.attr("data-show",0);
        container.html("");
        ajaxShowProducts();
    })
    $("#search-prod,.category-block input").on("click",function (e) {
        //e.preventDefault();
        container.attr("data-show",0);
        container.html("");
        ajaxShowProducts();
    })
    $("#filtrer-btn").on("click",function (e) {
        e.preventDefault();
        container.attr("data-show",0);
        container.html("");
        ajaxShowProducts();
    })
    $(".show-more").on("click",function (e) {
        e.preventDefault();
        ajaxShowProducts();
    })


    InitSpinner();
    initSelect();
});

