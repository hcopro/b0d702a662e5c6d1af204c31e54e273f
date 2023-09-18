var donnees = null;
function drawChart() {
	var data = new google.visualization.DataTable();
	data.addColumn('string', 'Name');
	data.addColumn('string', 'Manager');
	data.addColumn('string', 'ToolTip');
	$.each(donnees, function(indice, employe) {
		html = '<div class="text-center">' +
					'<img class="photo-organigramme" id="image" src="' + employe['photo'] + '">' +
					'<p>' + employe['nom'] + ' ' + employe['prenom'] + '</p>' +
					'<div style="color:red; font-style:italic">' + employe['poste'] + '</div>' +
					'<span class="titre">' + employe['niveau'] + '</span>' +
			   '</div>';
		data.addRows([
		  [{'v' : employe['id'], 'f' : html},
		   employe['chef'], employe['poste']]
		]);
	});

	// Create the chart.
	var chart = new google.visualization.OrgChart(document.getElementById('chart_div'));
	// Draw the chart, setting the allowHtml option to true for the tooltips.
	chart.draw(data, {'allowHtml':true});
}

function voirOrganigramme(employes) {
	donnees = employes;
	google.charts.load('current', {packages:["orgchart"]});
	google.charts.setOnLoadCallback(drawChart);
}