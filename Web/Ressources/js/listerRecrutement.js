$(document).on("click", ".delete", function () {
    var id   = $(this).data('id');
    var name = $(this).data('name');
    var url  = $(this).data('url');
    $('.modal-body #text-confirmation').text('Voulez-vous vraiment supprimer "' + name + '" ?');
    document.getElementById('action-delete').href = url + id;
});

$(document).on("click", ".modal-confirmation", function () {
    var id   = $(this).data('id');
    var name = $(this).data('name');
    var url  = $(this).data('url');
    $('.modal-body #text-confirmation').text('Voulez-vous vraiment ' + name + ' ?');
    document.getElementById('action').href = url + id;
});

$(document).ready(function(){
  // tab Super admin
  var a = "/manage/activeEntreprises";
  var b = "/manage/inactiveEntreprises";
  var pathname = new URL(window.location.href).pathname;
  
  if (pathname == a){
    $("li#a").addClass('is-active');
    $("li#b").removeClass('is-active');
  }
  else if (pathname == b){
  	console.log(b)
     $("li#b").addClass('is-active');
     $("li#a").removeClass('is-active');
  }
// end tab
  $.fn.dataTable.ext.errMode = 'throw';
  $('table').DataTable({
    "lengthMenu": [ [5, 10, 15, 20, -1], [5, 10, 15, 20, "Voire tous"] ], 
      "pageLength": 5,
      "language": {
        "sProcessing":    "Traitement...",
        "sLengthMenu":    "Afficher _MENU_ ",
        "sZeroRecords":   "Aucun résultat trouvé",
        "sEmptyTable":    "Aucune donnée disponible dans ce tableau",
        "sInfoFiltered":  "(fuite de un total de _MAX_ enregistrements)",
        "sInfoPostFix":   "",
        "searchPlaceholder": "Ici votre recherche ...",
        "search": '<i class="fa fa-search icon-input"></i>',
        "sUrl":           "",
        "sInfoThousands":  ",",
        "sLoadingRecords": "Chargement...",
        "oPaginate": {
            "sFirst":    "Premier",
            "sLast":    "Dernier",
            "sNext":    ">>",
            "sPrevious": "<<"
        }
    },
    "ordering": false,
    'bJQueryUI': true
  });
  $('#DataTables_Table_0_wrapper').removeClass("dataTables_wrapper form-inline dt-bootstrap no-footer");
  var $label = document.getElementsByTagName("INPUT")[0].closest("label");
  if ($label !== null) {
    $label.replaceWith(document.getElementsByTagName("INPUT")[0]);
  }
  var $labelLangue = document.getElementsByTagName("SELECT")[0].closest("label");
  if ($labelLangue !== null) {
    $labelLangue.replaceWith(document.getElementsByTagName("SELECT")[0]);
  }
  $("#DataTables_Table_0_filter" ).prepend( $( "<i class='fa fa-search icon-input'></i>" ) );
  $("#DataTables_Table_0_filter").parent().addClass("form-group");
  $("#DataTables_Table_0_length").parent().addClass("form-group");
});

