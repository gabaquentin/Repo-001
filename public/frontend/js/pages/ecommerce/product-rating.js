    $('#publier_avis').click(function () {
    let notes = 0;
    if($('#starhalf').is(':checked')){
    notes = 0.5
    console.log("+0.5")
}
    if($('#star1').is(':checked')){
    notes = 1
    console.log("+1")
}
    if($('#star1half').is(':checked')){
    notes = 1.5
    console.log("+1.5")
}
    if($('#star2').is(':checked')){
    notes = 2
    console.log("+2")
}
    if($('#star2half').is(':checked')){
    notes = 2.5
    console.log("+2.5")
}
    if($('#star3').is(':checked')){
    notes = 3
    console.log("+3")
}
    if($('#star3half').is(':checked')){
    notes = 3.5
    console.log("+3.5")
}
    if($('#star4').is(':checked')){
    notes = 4
    console.log("+4")
}
    if($('#star4half').is(':checked')){
    notes = 4.5
    console.log("+4.5")
}
    if($('#star5').is(':checked')){
    notes = 5
    console.log("+5")
}
    let comment = $('#comment').val()
    let url = "{{ path('noter_produit') }}"
    $.ajax({
    url:url,
    type: "POST",
    dataType: "json",
    data: {
    "idproduit": {{ produit.id }},
    "notes": notes,
    "comment": comment,
},
    async: true,
    success: function (data) {
    let content  = "";
    content += "<div class=\"customer-ratings\">\n" +
    "                                        <!-- Product review -->\n" +
    "                                        <div class=\"media\">\n" +
    "                                                <div class=\"media-left\">\n" +
    "                                                        <figure class=\"image is-32x32\">\n" +
    "                                                                <img src=\"http://via.placeholder.com/250x250\" data-demo-src=\"assets/img/avatars/elie.jpg\"\n" +
    "                                                                     alt=\"\">\n" +
    "                                                        </figure>\n" +
    "                                                </div>\n" +
    "                                                <div class=\"media-content\">\n" +
    "                                                        <p>\n" +
    "                                                                <span>Ngassa</span>\n" +
    "                                                                <small>\n";
    if(notes == 0){
    content += "<i class=\"fa fa-star is-empty\"></i>\n" +
    "                                                                                <i class=\"fa fa-star is-empty\"></i>\n" +
    "                                                                                <i class=\"fa fa-star is-empty\"></i>\n" +
    "                                                                                <i class=\"fa fa-star is-empty\"></i>\n" +
    "                                                                                <i class=\"fa fa-star is-empty\"></i>"
}
    if(notes == 0.5){
    content += "<i class=\"fa fa-star-half\"></i>\n" +
    "                                                                                <i class=\"fa fa-star is-empty\"></i>\n" +
    "                                                                                <i class=\"fa fa-star is-empty\"></i>\n" +
    "                                                                                <i class=\"fa fa-star is-empty\"></i>\n" +
    "                                                                                <i class=\"fa fa-star is-empty\"></i>"
}
    if(notes == 1){
    content += "<i class=\"fa fa-star\"></i>\n" +
    "                                                                                <i class=\"fa fa-star is-empty\"></i>\n" +
    "                                                                                <i class=\"fa fa-star is-empty\"></i>\n" +
    "                                                                                <i class=\"fa fa-star is-empty\"></i>\n" +
    "                                                                                <i class=\"fa fa-star is-empty\"></i>"
}
    if(notes == 1.5){
    content += "<i class=\"fa fa-star\"></i>\n" +
    "                                                                                <i class=\"fa fa-star-half\"></i>\n" +
    "                                                                                <i class=\"fa fa-star is-empty\"></i>\n" +
    "                                                                                <i class=\"fa fa-star is-empty\"></i>\n" +
    "                                                                                <i class=\"fa fa-star is-empty\"></i>"
}
    if(notes == 2){
    content += "<i class=\"fa fa-star\"></i>\n" +
    "                                                                                <i class=\"fa fa-star\"></i>\n" +
    "                                                                                <i class=\"fa fa-star is-empty\"></i>\n" +
    "                                                                                <i class=\"fa fa-star is-empty\"></i>\n" +
    "                                                                                <i class=\"fa fa-star is-empty\"></i>"
}
    if(notes == 2.5){
    content += "<i class=\"fa fa-star\"></i>\n" +
    "                                                                                <i class=\"fa fa-star\"></i>\n" +
    "                                                                                <i class=\"fa fa-star-half\"></i>\n" +
    "                                                                                <i class=\"fa fa-star is-empty\"></i>\n" +
    "                                                                                <i class=\"fa fa-star is-empty\"></i>"
}
    if(notes == 3){
    content += "<i class=\"fa fa-star\"></i>\n" +
    "                                                                                <i class=\"fa fa-star\"></i>\n" +
    "                                                                                <i class=\"fa fa-star\"></i>\n" +
    "                                                                                <i class=\"fa fa-star is-empty\"></i>\n" +
    "                                                                                <i class=\"fa fa-star is-empty\"></i>"
}
    if(notes == 3.5){
    content += "<i class=\"fa fa-star\"></i>\n" +
    "                                                                                <i class=\"fa fa-star\"></i>\n" +
    "                                                                                <i class=\"fa fa-star\"></i>\n" +
    "                                                                                <i class=\"fa fa-star-half\"></i>\n" +
    "                                                                                <i class=\"fa fa-star is-empty\"></i>"
}
    if(notes == 4){
    content += "<i class=\"fa fa-star\"></i>\n" +
    "                                                                                <i class=\"fa fa-star\"></i>\n" +
    "                                                                                <i class=\"fa fa-star\"></i>\n" +
    "                                                                                <i class=\"fa fa-star\"></i>\n" +
    "                                                                                <i class=\"fa fa-star is-empty\"></i>"
}
    if(notes == 4.5){
    content += "<i class=\"fa fa-star\"></i>\n" +
    "                                                                                <i class=\"fa fa-star\"></i>\n" +
    "                                                                                <i class=\"fa fa-star\"></i>\n" +
    "                                                                                <i class=\"fa fa-star\"></i>\n" +
    "                                                                                <i class=\"fa fa-star-half\"></i>"
}
    if(notes == 5){
    content += "<i class=\"fa fa-star\"></i>\n" +
    "                                                                                <i class=\"fa fa-star\"></i>\n" +
    "                                                                                <i class=\"fa fa-star\"></i>\n" +
    "                                                                                <i class=\"fa fa-star\"></i>\n" +
    "                                                                                <i class=\"fa fa-star\"></i>"
}
    content += "</small>\n" +
    "                                                                <br>\n" +
    "                                                                <span class=\"rating-content\">"+comment+"</span>\n" +
    "                                                        </p>\n" +
    "                                                </div>\n" +
    "                                        </div>\n" +
    "                                </div>"
    $('#rating-list').prepend(content)
    console.log("Avis publié");
}
})
    console.log("Avis non publié");
})