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
        <tr data-order-id="${order['id']}">
            <td class="text-center">
                <button class=" btn btn-primary btn-xs btn-icon"><i class="fa fas fa-list-alt"></i></button>
                <a href="/back/ecommerce/commande/detail/${order['id']}"><button class="affecter-livreur btn btn-primary btn-xs btn-icon"><i class="fa fas fa-edit"></i></button></a>
            </td>
            <td class="order" id="order_id" data-order="${order['id']}">${order['id']}</td>
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
    $('.affecter-livreur').on('click',function () {
        alert($('.order').text());
    });
});
