
$(document).ready(function () {
    let a = $("#datatable-buttons").DataTable({
        processing: true,
        serverSide: true,
        ordering: true,
        language: {
            processing:     "Traitement en cours...",
            search:         "Rechercher&nbsp;:",
            lengthMenu:    "Afficher _MENU_ &eacute;l&eacute;ments",
            info:           "Affichage de l'&eacute;lement _START_ &agrave; _END_ sur _TOTAL_ &eacute;l&eacute;ments",
            infoEmpty:      "Affichage de l'&eacute;lement 0 &agrave; 0 sur 0 &eacute;l&eacute;ments",
            infoFiltered:   "(filtr&eacute; de _MAX_ &eacute;l&eacute;ments au total)",
            infoPostFix:    "",
            loadingRecords: "Chargement en cours...",
            zeroRecords:    "Aucun &eacute;l&eacute;ment &agrave; afficher",
            emptyTable:     "Aucune donnée disponible dans le tableau",
            paginate: {
                previous:   "<i class='mdi mdi-chevron-left'>",
                next:       "<i class='mdi mdi-chevron-right'>",
            },
        },
        drawCallback: function () {
            $(".dataTables_paginate > .pagination").addClass("pagination-rounded");
        },
        ajax : GetProduitRoute,
        columns: [
            { data: 'id' },
            { data: 'image',"orderable": false },
            { data: 'titre' },
            { data: 'lien' },
            { data: 'date' },
            { data: 'action',"orderable": false },
        ],
        columnDefs: [
            {
                targets: 0,
                render: function(data, type, row) {
                    return "#"+data;
                }
            },
            {
                targets: 1,
                render: function(data, type, row) {
                    let url = ""
                    if(data[0])
                        url = baseImageProduit+data[0];
                    return `<img src="${url}" alt="product-img" title="product-img" class="avatar-lg  img-thumbnail">`;
                }
            },
            {
                targets: 2,
                render: function(data, type, row) {
                    return `<b class="text-danger">${data}</b>  ${devise}`;
                }
            },
            {
                targets: 3,
                render: function(data, type, row) {
                    return `<b class="text-danger">${data}</b> %`;
                }
            },
            {
                targets: 4,
                render: function(data, type, row) {
                    return `<b class="text-danger">${formatPhpDate(row.date.date,true)}</b> `;
                }
            },
            {
                targets: -1,
                render: function(data, type, row) {
                    return `
                        <div class="btn-group dropdown">
                            <a href="javascript: void(0);" class="dropdown-toggle arrow-none btn btn-light btn-sm" data-toggle="dropdown" aria-expanded="false"><i class="mdi mdi-dots-horizontal"></i></a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="${modifyProduitRoute}/${row.id}"><i class="mdi mdi-pencil mr-2 text-muted font-18 vertical-middle"></i>Modifier</a>
                                <a class="dropdown-item delete-p" href="#" data-id="${row.id}"><i class="mdi mdi-delete mr-2 text-muted font-18 vertical-middle"></i>Supprimer</a>
                            </div>
                        </div>
                    `;
                }
            }
        ]
    });
    a.on('click', '.delete-p', function (e) {
        e.preventDefault();
        ajaxDeletePopulaire($(this).data("id"));
    });

    function ajaxDeletePopulaire(idProduit) {
        $.ajax({
            type: "POST",
            url: deleteProduitRoute,
            data : {"id" : idProduit},
            dataType: "JSON",
            beforeSend : () => {
            },
            success: (data) => {
                displayMessage(data);
                a.ajax.reload();
            },
            error: () => {
                messageErrorServer();
            }
        });
    }
})
