
$(document).ready(function () {
    let  btnSauv = $("#btn-submit");
    let  btnReset = $("#btn-reset")
    let formulaire = $("#form-category")
    let btnModif = $(".btn-modif")
    let btnSupp = $(".btn-supp")
    let contentForm = $("#content-form")
    let btnSaveCategory = $("#btn-save-category")
    $("#nestable_list").nestable({ group: 1,maxDepth:2 })

    btnModif.on("click",function (e) {
        e.preventDefault()
        ajaxGetCategory($(this).attr("data-id"),contentForm,$(this));
    })
    $(document).on("click","#btn-reset",function (e) {
        e.preventDefault()
        ajaxGetCategory(0,contentForm,$("#btn-reset"));
    })
    $(document).on("click","#btn-submit",function (e) {
        e.preventDefault()
        ajaxSaveCategory($("#form-category"),$("#btn-submit"))
    })
    btnSaveCategory.on("click",function (e) {
        e.preventDefault()
        ajaxSaveCategoryDispo($("#nestable_list").nestable("serialize"),$(this),contentForm);
    })
    btnSupp.on("click",function (e) {
        e.preventDefault()

        Swal.fire({ title: "Etes vous sûre?", text: "Cette action est irreversible", type: "warning", showCancelButton: !0, confirmButtonColor: "#3085d6", cancelButtonColor: "#dd3333", confirmButtonText: "Oui supprimer",cancelButtonText: "Annuler" }).then(
            (t)=> {
                t.value && ajaxSuppressionCat($(this).attr("data-id"),$(this))
            }
        )
    })

})

function ajaxSaveCategory(form,button)
{
    $.ajax({
        type: form.attr("method"),
        url: form.attr("action"),
        data : form.serialize(),
        dataType: "JSON",
        beforeSend : () => {
            disableButton(button)
        },
        success: (data) => {
            displayMessage(data);
            if(data["route"])
                Swal.fire("Succès", "La catégorie a été sauvegardée", "success").then((t)=>{
                    document.location.href = data["route"];
                });

            ableButton(button)
        },
        error: () => {
            messageErrorServer()
            ableButton(button)
        }
    });
}

function ajaxSaveCategoryDispo(data,button,formContent)
{
    $.ajax({
        type: "POST",
        url: saveCategoriesRoute,
        data : {"categories":data},
        dataType: "JSON",
        beforeSend : () => {
            disableButton(button)
        },
        success: (data) => {
            displayMessage(data)
            if(data["form"] && data["form"].length!==0)
            {
                formContent.fadeOut()
                formContent.html(data["form"]["content"])
                formContent.fadeIn()
            }
            ableButton(button)
        },
        error: () => {
            messageErrorServer()
            ableButton(button)
        }
    });
}

function ajaxGetCategory(idCategory,formulaire,button) {
    $.ajax({
        type: "GET",
        url: GetCategorieRoute,
        data : {"idCategory" : idCategory},
        dataType: "JSON",
        beforeSend : () => {
            disableButton(button)
        },
        success: (data) => {
            displayMessage(data);
            formulaire.fadeOut()
            formulaire.html(data)
            formulaire.fadeIn()
            ableButton(button)
        },
        error: () => {
            messageErrorServer()
            ableButton(button)
        }
    });
}

function ajaxSuppressionCat(idCategorie,button) {
    $.ajax({
        type: "POST",
        url: DeteleteCategorieRoute,
        data : {"idCategory" : idCategorie},
        dataType: "JSON",
        beforeSend : () => {
            disableButton(button)
        },
        success: (data) => {
            displayMessage(data);
            if(data["errors"].length === 0)
                Swal.fire("Suppression!", "La catégorie a été supprimée", "success").then((t)=>{
                    document.location.reload();
                });
            ableButton(button)
        },
        error: () => {
            messageErrorServer()
            ableButton(button)
        }
    });
}
