$(document).ready(function() {
    /**@changelog [EVOL] (Lansky) 08/12/2022 Ajouter l'offset dans la forme */
    setTimeout(function () {
        if (typeof offSet === "undefined"){
            var offSet = 1;
        }
    } ,300);
    var displayStart = (offSet * 10) - 10;
    $('#table-back').DataTable({
        "pagingType" : "full_numbers",
        "language" : {
	        "search" : "_INPUT_",
	        "searchPlaceholder" : "rechercher",
	        "zeroRecords": "Aucun enregistrement n'a été trouvé",
            "oPaginate": {
                "sFirst":    "Premier",
                "sLast":    "Dernier",
                "sNext":    "Suivant",
                "sPrevious": "Précédent"
            }
	    },
        "searchPlaceholder" : "rechercher",
        "lengthMenu" : [[10, 25, 50, -1], [5, 25, 50, "All"]],
        "displayStart": displayStart
    } );
    $('#table-back_length').addClass("invisible");
    $('#table-back_info').parent().removeClass();
    $('#table-back_info').parent().addClass("col-md-3");
    $('#table-back_info').remove();
    $('#table-back_wrapper').css("padding","0px");
    $.each($('.pagination').children('li'), function() {
        if ($(this).children().text() == 2) {
            $(this).addClass('active');
        }
    });
});


 /**@changelog [EVOL] (Lansky) 08/12/2022 Ajouter l'offset dans la forme */
// $(window).on('load', function(){
//     if (typeof offSet === "undefined"){
//         var offSet = 1;
//     console.log("ddddddddddd")
//     }
//     var displayStart = (offSet * 10) - 10;
//     console.log("eeeeeeeeeeeeeee")
//     console.log("eeeeeeeeeeeeeee")
//     // $('#table-back').DataTable({
//     //     "displayStart": displayStart
//     // })
// });