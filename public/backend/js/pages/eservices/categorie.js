$(document).ready(function () {
    const btnSubmit =$('#valider')
    const form = $('#form-categorie')
    const imgfile = $('#imgfile')

    /**
     * button de soumission du formulaires
     */
    btnSubmit.on("click",(e)=>{
        disableButton(btnSubmit);
        e.preventDefault();
        /*if(myDropzone.files.length!==0)
            myDropzone.processQueue();
        else*/
        console.log(imgfile.val())
        ajaxSaveService(form,btnSubmit,form.serialize());
    });

    function ajaxSaveService(form,button,data) {
        let formData = new FormData(form[0]);
        console.log(formData)
        $.ajax({
            type: form.attr("method"),
            url: form.attr("action"),
            data: formData,
            contentType: false,
            dataType: "JSON",
            processData: false,
            cache: false,
            beforeSend : () => {
                disableButton(button)
            },
            success: (data) => {
                displayMessage(data);
                ableButton(button);
                //window.location.href = "";
                /*if(data["success"]&&data["success"].length!==0)
                {
                    if(getOrdreImg().length===0)
                        document.location.reload();
                }*/
            },
            error: () => {
                messageErrorServer()
                ableButton(button)
            }
        });
    }
})