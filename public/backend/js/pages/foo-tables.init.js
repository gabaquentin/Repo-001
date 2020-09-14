$(window).on("load", function () {
    $("#demo-foo-row-toggler").footable(), $("#demo-foo-accordion").footable().on("footable_row_expanded", function (o) {
        $("#demo-foo-accordion tbody tr.footable-detail-show").not(o.row).each(function () {
            $("#demo-foo-accordion").data("footable").toggleDetail(this)
        })
    }),
        $("#demo-foo-pagination").footable(),
        $("#demo-show-entries").change(function (o) {
        o.preventDefault();
        var t = $(this).val();
        $("#demo-foo-pagination").data("page-size", t), $("#demo-foo-pagination").trigger("footable_initialized")
    });
    var t = $("#demo-foo-filtering");
    t.footable().on("footable_filtering", function (o) {
        var t = $("#demo-foo-filter-status").find(":selected").val();
        o.filter += o.filter && 0 < o.filter.length ? " " + t : t, o.clear = !o.filter
    }),
        $("#demo-foo-filter-status").change(function (o) {
        o.preventDefault(), t.trigger("footable_filter", {filter: $(this).val()})
    }),
        $("#demo-foo-search").on("input", function (o) {
        o.preventDefault(), t.trigger("footable_filter", {filter: $(this).val()})
    });
    var e = $("#demo-foo-addrow");
    e.footable().on("click", ".remove-admin", function (i) {
        var o = e.data("footable"), t = $(this).parents("tr:first");
        var id = $(i.target).closest('td').find("#id_user").html();
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
            if(t1.value)
            {
                fetch("/back/security/retroUser/" + id ).then(function(response) {
                    if(response.ok) {
                        Swal.fire({
                            title: "Retrogradé!",
                            text: "Votre administrateur a ete retrogradé.",
                            type: "success"
                        });

                        o.removeRow(t);

                    } else {
                        t1.dismiss === Swal.DismissReason.cancel &&Swal.fire({
                            title: "Erreur",
                            text: "Une erreur s'est produite durant l'operation",
                            type: "error"
                        });
                    }
                })
                    .catch(function(error) {
                        console.log('Il y a eu un problème avec l\'opération fetch: ' + error.message);
                        t.dismiss === Swal.DismissReason.cancel &&Swal.fire({
                            title: "Erreur",
                            text: "Une erreur s'est produite durant l'operation",
                            type: "error"
                        });
                    });
            }
            else
            {
                t1.dismiss === Swal.DismissReason.cancel &&Swal.fire({
                    title: "Annulé",
                    text: "Aucune promotion n'as été effectué:)",
                    type: "error"
                });
            }
        })

    }),
    e.footable().on("click", ".enable-partner", function (i) {
        var o = e.data("footable"), t = $(this).parents("tr:first");
        var id = $(i.target).closest('td').find("[id=id_user]").html();
        Swal.fire({
            title: "Êtes-vous sûr?",
            text: "L'utilisateur seras desormais partenaire approuvé!",
            type: "warning",
            showCancelButton: !0,
            confirmButtonText: "Oui, Valider!",
            cancelButtonText: "Non, annuler!",
            confirmButtonClass: "btn btn-success mt-2",
            cancelButtonClass: "btn btn-danger ml-2 mt-2",
            buttonsStyling: !1
        }).then(function (t1) {
            if(t1.value)
            {
                fetch("/back/security/validatePartner/1/" + id ).then(function(response) {
                    if(response.ok) {
                        Swal.fire({
                            title: "Reussi!",
                            text: "L'utilisateur est desormais partenaire approuvé.",
                            type: "success"
                        });

                        $("[id=row_etat"+id+"]").html("<button class=\"disable-partner btn btn-danger btn-xs btn-icon\"><i class=\"fa fa-times\"></i></button>");

                    } else {
                        t.dismiss === Swal.DismissReason.cancel &&Swal.fire({
                            title: "Erreur",
                            text: "Une erreur s'est produite durant l'operation",
                            type: "error"
                        });
                    }
                })
                    .catch(function(error) {
                        console.log('Il y a eu un problème avec l\'opération fetch: ' + error.message);
                        t.dismiss === Swal.DismissReason.cancel &&Swal.fire({
                            title: "Erreur",
                            text: "Une erreur s'est produite durant l'operation",
                            type: "error"
                        });
                    });

            }
            else
            {
                t1.dismiss === Swal.DismissReason.cancel &&Swal.fire({
                    title: "Annulé",
                    text: "Votre approbation n'as pas été validé:)",
                    type: "error"
                });
            }
        })

    }),
    e.footable().on("click", ".disable-partner", function (i) {
        var o = e.data("footable"), t = $(this).parents("tr:first");
        var id = $(i.target).closest('td').find("[id=id_user]").html();
        console.log(id);
        Swal.fire({
            title: "Êtes-vous sûr?",
            text: "Vous etes sur le point de resilier votre approbation sur votre partenariat!",
            type: "warning",
            showCancelButton: !0,
            confirmButtonText: "Oui, Valider!",
            cancelButtonText: "Non, annuler!",
            confirmButtonClass: "btn btn-success mt-2",
            cancelButtonClass: "btn btn-danger ml-2 mt-2",
            buttonsStyling: !1
        }).then(function (t1) {
            if(t1.value)
            {
                fetch("/back/security/validatePartner/0/" + id ).then(function(response) {
                    if(response.ok) {
                        Swal.fire({
                            title: "Reussi!",
                            text: "L'utilisateur n'est plus partenaire approuvé.",
                            type: "success"
                        });

                        $("[id=row_etat"+id+"]").html("<button class=\"enable-partner btn btn-success btn-xs btn-icon\"><i class=\"fa fa-check\"></i></button>");

                    } else {
                        t.dismiss === Swal.DismissReason.cancel &&Swal.fire({
                            title: "Erreur",
                            text: "Une erreur s'est produite durant l'operation",
                            type: "error"
                        });
                    }
                })
                    .catch(function(error) {
                        console.log('Il y a eu un problème avec l\'opération fetch: ' + error.message);
                        t.dismiss === Swal.DismissReason.cancel &&Swal.fire({
                            title: "Erreur",
                            text: "Une erreur s'est produite durant l'operation",
                            type: "error"
                        });
                    });

            }
            else
            {
                t1.dismiss === Swal.DismissReason.cancel &&Swal.fire({
                    title: "Annulé",
                    text: "Votre requete n'as pas été effectué:)",
                    type: "error"
                });
            }
        })

    }),
        $("#demo-input-search2").on("input", function (o) {
        o.preventDefault(), e.trigger("footable_filter", {filter: $(this).val()})
    }),
        $("#demo-btn-addrow").click(function () {
        e.data("footable").appendRow('<tr><td style="text-align: center;"><button class="demo-delete-row btn btn-danger btn-xs btn-icon"><i class="fa fa-times"></i></button></td><td>Adam</td><td>Doe</td><td>Traffic Court Referee</td><td>22 Jun 1972</td><td><span class="badge label-table badge-success   ">Active</span></td></tr>')
    })
});