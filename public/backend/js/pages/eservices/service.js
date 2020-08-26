$(document).ready(function () {
    const btnSubmit =$('#valider')
    const form = $('#form-service')
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
        ajaxSaveService(form,btnSubmit,form.serialize()+"$imgfile1="+imgfile.val());
    });

    function ajaxSaveService(form,button,data) {
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
                window.location.href = "";
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