function initProductPageUI() {
    //Product panel
    $('.product-action').on('click', function () {
        $('.product-action.is-active').removeClass('is-active');
        $(this).addClass('is-active');
    }); //show product

    $('#show-product').on('click', function () {
        $('#meta-view, #ratings-view').addClass('is-hidden');
        $('#product-view').removeClass('is-hidden');
    }); //show meta

    $('#show-meta').on('click', function () {
        $('#product-view, #ratings-view').addClass('is-hidden');
        $('#meta-view').removeClass('is-hidden');
    }); //show ratings

    $('#show-ratings').on('click', function () {
        $('#meta-view, #product-view').addClass('is-hidden');
        $('#ratings-view').removeClass('is-hidden');
    });
}

$(document).ready(function () {

    initProductPageUI();

    $('.fullscreen-slick').slick({
        dots: true,
        infinite: true,
        speed: 500,
        cssEase: 'cubic-bezier(0.645, 0.045, 0.355, 1.000)',
        autoplay: true,
        autoplaySpeed: 5000,
        arrows: false
    });
    $('.produit-asscocies').slick({
        dots: true,
        infinite: true,
        speed: 500,
        cssEase: 'cubic-bezier(0.645, 0.045, 0.355, 1.000)',
        autoplay: true,
        autoplaySpeed: 5000,
        arrows: false,
        slidesToShow: 3,
    });

    $('#sidebar-wishlist-button').removeClass('is-hidden');
});