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
        console.log(updateDataPack($(this),dataUnit,false))
    })

});

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