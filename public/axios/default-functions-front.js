

function displayMessage(data) {
    if(data['errors'] && data['errors'].length!==0)
    {
        let i = 5;
        for(let titre in data['errors'])
        {
            setTimeout(()=>{
                displayMessageNotify(data['errors'][titre]);
                }, (500 + (i*100)));
            i++;
        }

    }
    if(data['success'] && data['success'].length!==0)
    {
        let j = 5;
        for(let i in data['success'])
        {
            setTimeout(()=>{
                displayMessageNotify(data['success'][i],"success");
            }, (500 + (j*100)));
            j++;
        }
    }
}


function disableButton(button)
{
    button.addClass("is-loading");
}

function ableButton(button)
{
    button.removeClass("is-loading")
}

function messageErrorServer(message = "Probléme de connexion avec le serveur") {
    displayMessageNotify( message);
}

function displayMessageNotify( message,type= "error",entete = "") {
    if (type==="error" || type==="warning")
        toasts.service.error(entete, 'fas fa-meh', message, 'bottomRight', timeShow);
    else
        toasts.service.success(entete, 'fas fa-check', message, 'bottomRight', timeShow);
}
