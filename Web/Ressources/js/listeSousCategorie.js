function recuperer(idSousCategorie, idCategorie, libSousCat) {
	$('#libelle').val(libSousCat);
	$('#select-idParent').val(idCategorie);
	$('#idCategorie').val(idSousCategorie);
	pathname = new URL(window.location.href).pathname;		
	if (pathname.split('_')[1] == 'detail') {
		$('#modif-idCategorie').val(idSousCategorie);
	}
}