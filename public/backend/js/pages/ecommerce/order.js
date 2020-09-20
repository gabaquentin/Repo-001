function messageErrorServer2(message = "La Date saisit est incorrecte") {
    displayMessageNotify2( message);
}

function displayMessageNotify2( message,type= "error",entete = "Erreur") {
    setTimeout(()=>{
        $.NotificationApp.send(entete, message, "top-right", "#bf441d", type, timeShow, 1, "slide");
    }, (500));
}

function messageSucces(message = "La date de livraison a bien été modifié") {
    displayMessageSuccess( message);
}

function displayMessageSuccess( message,type= "Success",entete = "Succès") {
    setTimeout(()=>{
        $.NotificationApp.send("Succèss", message, "top-right", "#5ba035", "success", timeShow, 1, "slide");
    }, (500));
}

function Ajouter_date_livraison(date){
    let id =$('#order_id').text();
    let weekday = new Array(7);
    let tab_month = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
    weekday[0] = "Sunday";
    weekday[1] = "Monday";
    weekday[2] = "Tuesday";
    weekday[3] = "Wednesday";
    weekday[4] = "Thursday";
    weekday[5] = "Friday";
    weekday[6] = "Saturday";
    $.ajax({
        type: "GET",
        url: "/back/ecommerce/commande/add_date_liv",
        data: {
            "date_liv": date,
            'id' : id
        },
        async :true,
        dataType : "JSON",
            beforeSend: () => {
            $('.valider').attr("disabled", true);
            $('.envoie').addClass('spinner-border spinner-border-sm mr-1');
        },
        success: (data) => {
            let date_liv = new Date(data);
            let str_date = ""+weekday[date_liv.getDay()]+" "+date_liv.getDate()+"- "+tab_month[date_liv.getMonth()]+" - "+date_liv.getFullYear();
            messageSucces();
            $('.show_calendar').attr('hidden',false);
            $('.show_calendar2').attr('hidden',false);
            $('.form-horizontal').attr('hidden',true);
            $('.dat_liv').html(str_date);
            $('.valider').attr("disabled", false);
            $('.envoie').removeClass('spinner-border spinner-border-sm mr-1');
        },
        error: () => {

        }
    });
}

function  Ajouter_Livreur(id,nom) {
    let id_liv=parseInt(id);
    let id_order =$('#order_id').text();
    $.ajax({
        type: "GET",
        url: "/back/ecommerce/commande/livreur/add",
        data: {
            "livreur": id_liv,
            "id": id_order
        },
        async: true,
        dataType: "JSON",
        beforeSend: () => {
            $('.affecter_liv').attr("disabled", true);
            $('.envoie_liv').addClass('spinner-border spinner-border-sm mr-1');
        },
        success: (data) => {
            $('.form-horizontal2').attr('hidden',true);
            $('.affecter_liv').attr("disabled", false);
            $('.envoie_liv').removeClass('spinner-border spinner-border-sm mr-1');
            $('.Livreur').attr('hidden',false);
            $('.set_liv').attr('hidden',false);
            $('.liv').html(nom);
            console.log(data);
        },
        error: () => {

        }
    });

}

function Chercher_Livreur (livreur) {
    $('.affecter_liv').attr('disabled',true);
    $.ajax({
        type: "GET",
        url: "/back/ecommerce/commande/livreur/search",
        data: {
            "livreur": livreur
        },
        async: true,
        dataType: "JSON",
        beforeSend: () => {

        },
        success: (data) => {
            $('.list_liv').attr('hidden',true);
            console.log(data);
           if(data.length !== 0){
               $('#select_livreur').attr('hidden',false);
               for(let i =0 ; i<data.length;i++){
                   let template =` <option   class="list_liv" value="${data[i]['id']}">${data[i]['nom']}   ${data[i]['prenom']} </option>`;
                   $('#select_livreur').append(template);
               }
               $('.list_liv').on('click',function () {
                   let sel = document.getElementById('select_livreur');

                   //alert(sel.value);
                   if(sel.value !== ""){
                       $('.affecter_liv').attr('disabled',false);
                        $('.affecter_liv').on('click',function () {
                            Ajouter_Livreur(sel.value,sel.options[sel.selectedIndex].text);
                        });
                   }
                   else{
                       $('.affecter_liv').attr('disabled',true);
                   }
                   $(".nom_liv").val(sel.options[sel.selectedIndex].text);
                   $('#select_livreur').attr('hidden',true);
               });
           }
           else{
           }
        },
        error: () => {

        }
    });
}

$(document).ready(function () {
    $('.affecter_liv').attr('disabled',true);
    $('.valider').on('click' , function () {
        if($('.date_liv'). val() !== ""){
            Ajouter_date_livraison($('.date_liv').val());
        }
        else{
            messageErrorServer2();
        }
    });

    $('.show_calendar').on('click',function () {
        $('.show_calendar').attr('hidden',true);
        $('.show_calendar2').attr('hidden',true);
        $('.form-horizontal').attr('hidden',false);
    });

    $('.set_liv').on('click',function () {
        $('.set_liv').attr('hidden',true);
        $('.Livreur').attr('hidden',true);
        $('.form-horizontal2').attr('hidden',false);
        $('#select_livreur').attr('hidden',true);
    });
    $('.nom_liv').on('keyup',function () {
        if($('.nom_liv').val() !== ""){
            $('.affecter_liv').attr('disabled',false);
            Chercher_Livreur($('.nom_liv').val());
        }
        else{
            $('#select_livreur').attr('hidden',true);
            $('.affecter_liv').attr('disabled',true);
        }
    })
});