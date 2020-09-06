let timeShow = 5e3;
let maxImage = 10;
let devise = "F CFA";

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

function truncateString(str, num) {
    if (str.length <= num) {
        return str;
    }
    return str.slice(0, num) + '...';
}

function isExpired(dateP) {
    let interval = (new Date()).getTime() - (new Date(dateP)).getTime();
    let days = (interval / (3600 * 24 * 1000));
    return days > 60 ;
}