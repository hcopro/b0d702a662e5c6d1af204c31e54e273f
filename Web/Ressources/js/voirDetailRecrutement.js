$(document).on("click", ".delete", function () {
    var id   = $(this).data('id');
    var name = $(this).data('name');
    var url  = $(this).data('url');
    $('.modal-body #text-confirmation').text('Voulez-vous vraiment supprimer "' + name + '" ?');
    document.getElementById('action-delete').href = url + id;
     
});

$('#idInterlocuteur').change(function(){
	if ($('#idInterlocuteur').val() == "autre") {		
		window.location.href = "create-interlocuteur";
	} else {
		window.location.href = "save-interlocuteur_niveau_entretien?idInterlocuteur=" + $('#idInterlocuteur').val();
	}
});

$(document).ready(function(){
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
	$label.replaceWith(document.getElementsByTagName("INPUT")[0]);
	var $labelLangue = document.getElementsByTagName("SELECT")[0].closest("label");
	$labelLangue.replaceWith(document.getElementsByTagName("SELECT")[0]);
	$("#DataTables_Table_0_filter" ).prepend( $( "<i class='fa fa-search icon-input'></i>" ) );
	$("#DataTables_Table_0_filter").parent().addClass("form-group");
	$("#DataTables_Table_0_length").parent().addClass("form-group");
});