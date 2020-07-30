
$(document).ready(function () {
    let quill ;
    const extendForm = $("#extend-form");
    const selectCatgorie = $("#produit_categorieProd");
    let produitAssocies = $("#produits-associes");
    let attributs = $("#attributs");
    const btnSubmit = $("#btn-submit");
    const btnReset = $("#btn-reset");
    const btnAddVille = $("#btn-add-ville");
    const contentModale = $("#modal-body");
    const btnSaveModal = $("#save-modal");
    const selectVille = $("#produit_ville");
    const form = $("#form-produit");
    const btnRemoveImg = $(".uploaded-image [data-dz-remove]");
    const paramSelect2 = {
        tags: true,
        createTag: ()=> {return null;}
    }
    const paramCarateristiques = { min: 0, max: 100, step: 1, decimals: 0, boostat: 5, maxboostedstep: 10, buttondown_class: "btn btn-primary", buttonup_class: "btn btn-primary"};
    const paramDimensions = { min: 0, max: 999999999, step: 0.1, decimals: 2, boostat: 5, maxboostedstep: 10, buttondown_class: "btn btn-primary", buttonup_class: "btn btn-primary", postfix: "Mètre" };
    /**
     * initialisation des données
     */
    initProductPage();

    /**
     * button pour ajouter une ville
     */
    btnAddVille.on("click", ()=> {
        ajaxGetFormVille(contentModale);
    })

    /**
     * initialiastion de l imput pour les images
     */
    form.data("previewsContainer");
    const temp = form.data("uploadPreviewTemplate");
    let myDropzone = new Dropzone('#form-produit', {
        paramName: "files",
        autoDiscover : false,
        previewsContainer : form.data("previewsContainer"),
        previewTemplate : $(temp).html(),
        url : form.attr("action"),
        sending: function(file, xhr, formData) {
            formData.append("produitAssocies", produitAssocies.val());
            formData.append("attributs", attributs.val());
            formData.append("description", quill.root.innerHTML);
            formData.append("ordre", getOrdreImg());
        },
        dictMaxFilesExceeded : "maximun "+maxImage+" photos",
        dictResponseError : "échec de la connexion avec le serveur",
        dictInvalidFileType : "extension acceptées .jpg,.jpeg,.png",
        dictFileSizeUnits : "image trop lourde",
        acceptedFiles : ".jpg,.jpeg,.png",
        maxFiles: maxImage,
        parallelUploads: maxImage,
        uploadMultiple: true,
        autoProcessQueue: false,
        clickable : ".click",
    });
    /**
     * En cas de succès on recharge les images
     */
    myDropzone.on("success",(file,data)=>{
       myDropzone.removeFile(file);
       file.status = undefined;
       file.accepted = undefined
       myDropzone.addFile(file);
       ableButton(btnSubmit);
       displayMessage(data);
       if(data["success"]&&data["success"].length!==0)
           document.location.reload();
    });
    /**
     * En cas d'ajout d'une image sur un produit éxistant on vérifie les images déjà upload
     */
    myDropzone.on("addedfile",(file)=>{
        const ordre = getOrdreImg();
        if (myDropzone.files.length>(maxImage - ordre.length)) {
            displayMessageNotify(myDropzone.options.dictMaxFilesExceeded,"warning");
            myDropzone.removeFile(file);
        }
    });

    /**
     * affichage des erreurs
     */
    myDropzone.on("error", (file, message)=> {
        displayMessageNotify(message,"warning");
        myDropzone.removeFile(file);
    });

    btnRemoveImg.on("click",function(e){
        e.preventDefault();
        $(this).parents(".uploaded-image").remove();
    })

    /**
     * button de soumission du formulaire
     */
    btnSubmit.on("click",(e)=>{
        disableButton(btnSubmit);
        e.preventDefault();
        if(myDropzone.files.length!==0)
            myDropzone.processQueue();
        else
            ajaxSaveProduit(form,btnSubmit,form.serialize()+"&produitAssocies="+produitAssocies.val() +"&=attributs"+ attributs.val()+"&description="+ quill.root.innerHTML+"&ordre="+getOrdreImg());
    });

    btnReset.on("click",(e)=>{
        e.preventDefault();
        document.location.reload();
    });

    /**
     * button de souvegarde d'une ville
     */
    btnSaveModal.on("click",()=>{
        const form = contentModale.find("form:first-child");
        ajaxSaveVille(form,selectVille,btnSaveModal);
    })

    /**
     * recuperation du formulaire du produit en fonction de la catagorie du produit
     */
    selectCatgorie.on("change",function () {
        ajaxGetExtendFormCat($(this).val(),extendForm);
    })

    /**
     * initialition des input number en cas d'ajout de l'extention du formulaire
     */
    function initProductFormImmobilier() {
        $("#produit_dureeSejour").TouchSpin({ min: 0, max: 999999999, step: 1, decimals: 0, boostat: 5, maxboostedstep: 10, buttondown_class: "btn btn-primary", buttonup_class: "btn btn-primary", postfix: "Jours" });

        $("#produit_caracteristique_nbreChambres").TouchSpin(paramCarateristiques);
        $("#produit_caracteristique_nbreSalleBain").TouchSpin(paramCarateristiques);
        $("#produit_caracteristique_nbreParking").TouchSpin(paramCarateristiques);
    }

    function initProductPage()
    {
        $("#produit_prixPromo").TouchSpin({ min: 0, max: 100, step: 0.1, decimals: 2, boostat: 5, maxboostedstep: 10, buttondown_class: "btn btn-primary", buttonup_class: "btn btn-primary", postfix: "%" });
        $("#produit_prix").TouchSpin({ min: 0, max: 999999999, step: 0.1, decimals: 2, boostat: 5, maxboostedstep: 10, buttondown_class: "btn btn-primary", buttonup_class: "btn btn-primary", postfix: "F CFA" });

        $("#produit_dimension_longueur").TouchSpin(paramDimensions);
        $("#produit_dimension_largeur").TouchSpin(paramDimensions);
        $("#produit_dimension_hauteur").TouchSpin(paramDimensions);

        ajaxGetExtendFormCat(selectCatgorie.val(),extendForm);

        /**
         * initialisation des produit assciés
         */
        produitAssocies.select2(paramSelect2);
        produitAssocies.val(produitAssocies.attr('data-value').split(",")).trigger('change');

        /**
         * initialisation des attributs
         */
        attributs.select2(paramSelect2);
        attributs.val(attributs.attr('data-value').split(",")).trigger('change');

        /**
         * input pour la description
         * @type {Quill}
         */
        quill = new Quill("#snow-editor", {
            theme: "snow",
            modules: {
                toolbar: [
                    [{ font: [] }, { size: [] }],
                    ["bold", "italic", "underline", "strike"],
                    [{ color: [] }, { background: [] }],
                    [{ script: "super" }, { script: "sub" }],
                    [{ header: [!1, 1, 2, 3, 4, 5, 6] }, "blockquote", "code-block"],
                    [{ list: "ordered" }, { list: "bullet" }, { indent: "-1" }, { indent: "+1" }],
                    ["direction", { align: [] }],
                    ["link"],
                    ["clean"],
                ],
            },
        });

    }

    function getOrdreImg()
    {
        let ordre = [];
        $(".uploaded-image img").each(function(i){
            ordre.push($(this).data("order"))
        })
        return ordre;
    }
    /**
     *  recupere le formualaire en donction de la categorie du produit
     */
    function ajaxGetExtendFormCat(idCategorie,extendForm) {
        $.ajax({
            type: "GET",
            url: GetCategorieFormRoute,
            data : {"idCategorie" : idCategorie},
            dataType: "JSON",
            beforeSend : () => {
            },
            success: (data) => {
                displayMessage(data);
                extendForm.html(data);
                initProductFormImmobilier();
            },
            error: () => {
                messageErrorServer();
            }
        });
    }

    /**
     * recupere le formualaire pour l'ajout d'une ville
     */
    function ajaxGetFormVille(contentModal) {
        contentModale.html("");
        $.ajax({
            type: "GET",
            url: GetVilleFormRoute,
            dataType: "JSON",
            beforeSend : () => {
                contentModale.addClass("spinner-border");
            },
            success: (data) => {
                displayMessage(data);
                contentModale.removeClass("spinner-border");
                contentModal.html(data);
            },
            error: () => {
                messageErrorServer();
                contentModale.removeClass("spinner-border");
            }
        });
    }

    /**
     * sauvegarde une ville et l'ajoute au formulaire du produit
     */
    function ajaxSaveVille(form,inputSelect,button) {
        $.ajax({
            type: form.attr("method"),
            url: form.attr("action"),
            data: form.serialize(),
            dataType: "JSON",
            beforeSend: () => {
                disableButton(button);
            },
            success: (data) => {
                displayMessage(data);
                if(data["ville"])
                {
                    const ville = data["ville"];
                    inputSelect.append(`<option value="${ville.id}"> ${ville.villes} </option>`);
                }
                ableButton(button);
            },
            error: () => {
                messageErrorServer();
                ableButton(button);
            }
        });
    }


    function ajaxSaveProduit(form,button,data) {
        $.ajax({
            type: form.attr("method"),
            url: form.attr("action"),
            data: data,
            dataType: "JSON",
            beforeSend : () => {
                disableButton(button)
            },
            success: (data) => {
                displayMessage(data);
                ableButton(button);
                if(data["success"]&&data["success"].length!==0)
                    document.location.reload();
            },
            error: () => {
                messageErrorServer()
                ableButton(button)
            }
        });
    }
})


function f() {
    $.ajax({
        type: "GET",
        url: GetCategorieRoute,
        data : {"idCategory" : 1},
        dataType: "JSON",
        beforeSend : () => {
            disableButton(button);
        },
        success: (data) => {
            displayMessage(data);
            console.log(data);
            ableButton(button);
        },
        error: () => {
            messageErrorServer();
            ableButton(button);
        }
    });
}
