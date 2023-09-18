function getNext(indice)
{
	indice++;
	if (indice < 0) {
		indice += MODULO;
	}
	return (indice % MODULO);
}
function getPrevious(indice)
{
	indice--;
	if (indice < 0) {
		indice += MODULO;
	}
	return (indice % MODULO);
}
function setTippy(classTippy, contentTippy)
{
	var attente = setTimeout(function() {
		tippy('.' + classTippy, {
			content: contentTippy
  		});	
	}, 2000);
}
function setHtmlTippy(classTippy, contentTippy)
{
	var attente = setTimeout(function() {
		tippy('.' + classTippy, {
			content: contentTippy,
			allowHtml : true
  		});	
	}, 2000);
}
function loadLoading()
{
	$('#container-contenus').html('<div class="text-center" style="width: 100%; height:300px;">' +
						          	'<i class="fa fa-spinner fa-pulse fa-5x fa-fw" style="margin-top: 100px; color: #647994;"></i>' +
						            '<span class="sr-only">Loading...</span>' +
					             '</div>');
}
function lineJump(numero)
{
	$('.ligne-' + numero).removeClass('card-down');
	$('.ligne-' + numero).addClass('card-jump');
}
function lineDown(numero)
{
	$('.ligne-' + numero).addClass('card-down');
	$('.ligne-' + numero).removeClass('card-jump');
}