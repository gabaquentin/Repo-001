let selectCategories = $(".select-categories");
let selectDuration = $(".select-duration .options li");
let selectPost = $(".select-post .options li");
let dataUnit = JSON.parse($("#shop-pack").attr("data-unit"));


function updateDataPack(trigger,dataUnit,update = true)
{
    let data = [];
    let container = trigger.parents(".pack-container").first();
    let selectDuration = container.find('.select-duration select');
    let selectPost = container.find('.select-post select');
    let selectCategories = container.find('.select-categories');
    let totaldiv = container.find(".v-indicator");
    let total = 0;

    data["id"] = parseInt(container.attr("data-id"));
    data["duration"] = selectDuration.val();
    data["post"] = selectPost.val();
    data["total"] = totaldiv.text().split(" ")[0];
    data["categories"] = selectCategories.val();

    if(data["post"] == undefined)data["post"]=0;
    if(data["duration"] == undefined)data["duration"]=0;

    data["categories"].forEach(function (val){
        dataUnit.forEach(function (du){
            if(val==du["idCat"])
            {
                total += (data["duration"] * du["durationUnit"] + data["post"] * du["postUnit"]);
            }
        })
    })

    data["total"] = total;
    if(update)
        totaldiv.text(total+" "+ devise);

    return data;
}

function ajaxSavePackUser(button,packInfo) {
    $.ajax({
        type: "POST",
        url: setPackRoute,
        data: {
            id : packInfo["id"],
            duration : packInfo["duration"],
            post : packInfo["post"],
            total : packInfo["total"],
            cats : packInfo["categories"],
        },
        dataType: "JSON",
        beforeSend : () => {
            disableButton(button)
        },
        success: (data) => {
            displayMessage(data);
            ableButton(button);
        },
        error: () => {
            messageErrorServer()
            ableButton(button)
        }
    });
}

$(document).ready(function (){
    initSelect();

    selectCategories.on("change",function () {
        updateDataPack($(this),dataUnit)
    })
    $(".select-duration .options li,.select-post .options li").on("click",function () {
        updateDataPack($(this),dataUnit)
    })

    $('.buy-pack').on("click",function (e) {
        e.preventDefault();
        let data = updateDataPack($(this),dataUnit,false);
        if(data["categories"].length===0)
            displayMessageNotify("vous devez sélèctionner une catégorie");
        else
            ajaxSavePackUser($(this),data);
    })

});

