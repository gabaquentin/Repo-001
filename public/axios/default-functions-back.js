const timeShow = 5e3;
const maxImage = 4;
const devise = "F CFA"

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
    displayMessageNotify( message);
}

function displayMessageNotify( message,type= "error",entete = "Alerte") {
    setTimeout(()=>{
        $.NotificationApp.send(entete, message, "top-right", "#bf441d", type, timeShow, 1, "slide");
    }, (500));
}

function formatPhpDate(date,withTime = false) {
    let today = new Date(date);
    let dd = today.getDate();
    let mm = today.getMonth() + 1;
    let yyyy = today.getFullYear();
    let minutes = today.getMinutes();
    let heures = today.getHours();
    if (dd < 10) {
        dd = '0' + dd;
    }
    if (mm < 10) {
        mm = '0' + mm;
    }
    if (minutes < 10) {
        minutes = '0' + minutes;
    }
    if (heures < 10) {
        heures = '0' + heures;
    }
    let toDay = dd + '/' + mm + '/' + yyyy;
    if (withTime)
        toDay += (" " + heures +":"+minutes);
    return toDay;
}