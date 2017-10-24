function GraficoPorcentajes()
{
	var chart;
	
	var argv = GraficoPorcentajes.arguments;
	var categories = argv[0];
	var seriescarga = argv[1];
		
	chart = new Highcharts.Chart({
		chart: {
			renderTo: 'container',
			type: "column"
		},
		yAxis: {
			title: {
				text: 'Grafico'
			},
			labels: {
				overflow: 'justify'
			}
		},
		title: {
			text: 'Respuestas'
		},
		xAxis: {
			categories: categories
		},
		tooltip: {
			formatter: function() {
				return ''+
					this.point.name +': '+ this.y + ' Votos';
			}
		},
		plotOptions: {
			column: {
				stacking: 'normal',
				dataLabels: {
					enabled: true,
					color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white',
					formatter: function() {
						return ''+
							'<b>'+this.y + ' Votos</b>';
					}					
				}
			}
		},
		legend: {
			enabled :false,
		},

		credits: {
			enabled: false
		},
		series: seriescarga
	});
}	
