
$(document).ready(function () {
    let quill ;
    const extendForm = $("#extend-form");
    let produitAssocies = $("#produits-associes");
    const btnSubmit = $("#btn-submit");
    const btnReset = $("#btn-reset");
    const form = $("#form-produit");
    const btnRemoveImg = $(".uploaded-image [data-dz-remove]");

    if($("#produit_categorieProd").length) {
        let selectCatgorie = $("#produit_categorie .options li");
        getProdFormEvent(selectCatgorie);
    }

    /**
     * initialisation des données
     */
    initProductPage();

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
        {
            if(getOrdreImg().length===0)
                document.location.reload();
        }
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
            ajaxSaveProduit(form,btnSubmit,form.serialize()+"&produitAssocies="+produitAssocies.val() +"&description="+ quill.root.innerHTML+"&ordre="+getOrdreImg());
    });

    btnReset.on("click",(e)=>{
        e.preventDefault();
        document.location.reload();
    });

    /**
     * recuperation du formulaire du produit en fonction de la catagorie du produit
     */
    function getProdFormEvent(selectCatgorie) {
        selectCatgorie.on("click",function () {
            ajaxGetExtendFormCat($(this).attr("rel"),extendForm);
        })
    }

    /**
     * initialition des input number en cas d'ajout de l'extention du formulaire
     */
    function initProductFormImmobilier() {
        initSpinner(true);
    }

    function initProductPage()
    {
        console.log($("#produit_categorieProd").length)
        if($("#produit_categorieProd").length)
            ajaxGetExtendFormCat($("#produit_categorieProd").val(),extendForm);

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
            data : {
                "idCategorie" : idCategorie,
                "forFront" : true
            },
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
                {
                    if(getOrdreImg().length===0)
                        setTimeout(function (){
                            document.location.reload();
                        },1000);
                }
            },
            error: () => {
                messageErrorServer()
                ableButton(button)
            }
        });
    }

})

