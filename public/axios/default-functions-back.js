const timeShow = 5e3;

function displayMessage(data) {
    if(data['errors'] && data['errors'].length!==0)
    {
        let errors = [];
        for(let titre in data['errors'])
        {
            errors.push(data['errors'][titre]) ;
        }
        setTimeout(()=>{
            $.NotificationApp.send("Erreur", errors, "top-right", "#bf441d", "error", timeShow, 1, "slide");
        }, (500));
    }
    if(data['success'] && data['success'].length!==0)
    {
        let succes = []
        for(let i in data['success'])
        {
            succes.push(data['success'][i]);
        }
        setTimeout(()=>{
            $.NotificationApp.send("Succès", succes, "top-right", "#5ba035", "success", timeShow, 1, "slide");
        }, (500));
    }
}


function disableButton(button)
{
    button.find("span").first().addClass("spinner-border");
    button.attr("disabled", "disabled")
}

function ableButton(button)
{
    button.find("span").first().removeClass("spinner-border")
    button.removeAttr("disabled")
}

function messageErrorServer(message = "Probléme de connexion avec le serveur") {
    setTimeout(()=>{
        $.NotificationApp.send("Alerte", message, "top-right", "#bf441d", "error", timeShow, 1, "slide");
    }, (500));
}