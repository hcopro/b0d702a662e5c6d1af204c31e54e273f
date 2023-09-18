function setUpdate(self,id) {
	$('#idCategorieUpdate').val(id);
	$('.code').val($($(self).parent().parent().find('td')[1]).html().trim());
	$('.description').val($($(self).parent().parent().find('td')[2]).html().trim());
	$('#input-hiddenLibelle').val($(self).parent().parent().find('.sorting_1').html().trim());
}
function setDelete(self,id) {
	$('#action-delete').attr('href','delete-categorie?id_categorie='+id);
	$('#text-confirmation').html('Voulez-vous vraiment supprimer <b><em>"'+$(self).parent().parent().find('.sorting_1').html().trim()+'"</b></em> ?');
}