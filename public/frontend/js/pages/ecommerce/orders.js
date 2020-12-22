function Set_Shipping_Orders(){
    let checkout = JSON.parse(localStorage.getItem('checkout'));
    checkout.shippingAddress = {
        'country': $('#country').val(),
        'street': $('#street').val(),
        'town': $('#town').val(),
        'address': $('#address').val(),
        'cp': $('#cp').val(),
    };
    localStorage.setItem('checkout', JSON.stringify(checkout));
}
/**
 * @return {boolean}
 */
function Valider_CP(chaine) {
    let regex=  /^[0-9]{4}$/;
    if(regex.test(chaine)){
        return true;
    }
    return false;
}

/**
 * @return {boolean}
 */
function Valider_Text(chaine){
    let regex=  /^[a-zA-ZÀ-ÿ0-9 \._-]{3,}$/;
    if(regex.test(chaine)){
        return true;
    }
    return false;
}
$(document).ready(function () {
    $('.form-shipping').attr('hidden',true);
    $('.fa-edit').on('click',function () {
        $('.form-shipping').attr('hidden',false);
        $('.info-shipping').attr('hidden',true);
        $('#country').val($('#shipping-country').text());
        $('#town').val($('#shipping-state').text());
        $('#street').val($('#shipping-city').text());
        $('#cp').val($('#shipping-postalCode').text());
        $('#address').val($('#shipping-address1').text());
    });

    $('.valider-adresse').on('click',function () {
        $('.valider-adresse').addClass('is-loading');
        if(Valider_Text($('#country').val()) && Valider_Text($('#town').val())
            && Valider_Text($('#address').val()) && Valider_Text($('#street').val()) && Valider_CP($('#cp').val())){
            Set_Shipping_Orders();
            $('.info-shipping').attr('hidden',false);
            $('.valider-adresse').removeClass('is-loading');
            $('#shipping-address1').html($('#address').val());
            $('#shipping-address2').html("");
            $('#shipping-city').html($('#street').val());
            $('#shipping-state').html($('#town').val());
            $('#shipping-postalCode').html($('#cp').val());
            $('#shipping-country').html($('#country').val());
            $('.form-shipping').attr('hidden',true);
            $('.address').attr('hidden',false);
        }
        else{
            setTimeout(function () {
                $('.cart-loader').removeClass('is-active');
                toasts.service.success('', 'fas fa-check', "Les données de l'adresse de livraison ne sont pas valides", 'bottomRight', 2500);
            }, 800);
            $('.valider-adresse').removeClass('is-loading');
        }
    });

});