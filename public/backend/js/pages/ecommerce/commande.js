function show_order(order) {
    let span = '';
    if(order['statut'] === "Livré"){
        span ='<span class="badge label-table badge-primary">';
    }
    else if(order['statut'] === "En cours de Livraison"){
        span ='<span class="badge label-table badge-success">';
    }
    else if(order['statut'] === "En Attente"){
        span ='<span class="badge label-table badge-danger">';
    }
    return `
        <tr class="orders" data-order-id="${order['id']}">
            <td class="text-center">
                <a href="/back/ecommerce/commande/detail/${order['id']}"><button class=" btn btn-primary btn-xs btn-icon"><i class="fa fas fa-list-alt"></i></button></a>
                <button class="affecter-livreur btn btn-primary btn-xs btn-icon"><i class="fa fas fa-edit"></i></button>
            </td>
            <td class="order"><span id="order_id" hidden>${order['id']}</span>${order['id']}</td>
            <td>${order['numero']}</td>
            <td>${order['client']}</td>
            <td>${order['livreur']}</td>
            <td><span class="badge label-table badge-light-dark">${order['payement']}</span></td>
            <td><span class="badge label-table badge-light-dark">${order['mode_liv']}</td>
            
            <td>${span}${order['statut']}</span></td>
        </tr>
    `;
}

function Show_All_Orders(orders){
    if($('#demo-foo-filtering').length){
        for (let i=0;i<orders.length;i++){
            console.log(orders[i]);
            let template = show_order(orders[i]);
            $.when($('.order-list').append(template)).done(function () {});
        }
    }
}

$(document).ready(function () {
    Show_All_Orders(JSON.parse($('#demo-foo-filtering').attr('data-orders')));
    let e = $("#demo-foo-filtering");
    e.footable().on("click", ".affecter-livreur", function (i) {
        let o = e.data("footable"), t = $(this).parents("tr:first");
        let id = $(i.target).closest('td').find("#order_id").html();
        console.log(id);
        Swal.fire({
            title: "Êtes-vous sûr?",
            text: "L'administrateur seras retrogradé au rang d'utilisateur simple!",
            type: "warning",
            showCancelButton: !0,
            confirmButtonText: "Oui, retrograder l'administrateur!",
            cancelButtonText: "Non, annuler!",
            confirmButtonClass: "btn btn-success mt-2",
            cancelButtonClass: "btn btn-danger ml-2 mt-2",
            buttonsStyling: !1
        }).then(function (t1) {

        })

    });
});
