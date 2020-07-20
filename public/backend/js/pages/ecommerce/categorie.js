
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
            console.log(data);
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
            formContent.fadeOut()
            formContent.html(data["form"]["content"])
            formContent.fadeIn()
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

function f() {
    $.ajax({
        type: "GET",
        url: GetCategorieRoute,
        data : {"idCategory" : 1},
        dataType: "JSON",
        beforeSend : () => {
            disableButton(button)
        },
        success: (data) => {
            displayMessage(data);
            console.log(data);
            ableButton(button)
        },
        error: () => {
            messageErrorServer()
            ableButton(button)
        }
    });
}
