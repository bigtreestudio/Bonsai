	
function GraficoBarras()
{
	chart && chart.destroy();
	chart = null;
	
	var argv = GraficoBarras.arguments;
	var Tipo = argv[0];
	var Codigo = argv[1];
	var Titulo = datosgrafico.graficotitulo;
	var SubTitulo = datosgrafico.graficodesc;
	var TituloX = datosgrafico.graficotitulocolumnas;
	var TituloY = datosgrafico.graficotitulofilas;

	if (TituloX=="")
		TituloX = null;
	if (TituloY=="")
		TituloY = null;
	if (Titulo=="")
		Titulo = null;
	if (SubTitulo=="")
		SubTitulo = null;
	
	var TituloAlign = datosgrafico.graficotituloalign;
	var SubTituloAlign = datosgrafico.graficodescalign;
	
	var invertir = false;
	if (datosgrafico.graficoinvertir==1)
		invertir = true;

	var mostrarleyenda = false;
	if (datosgrafico.graficoleyendamostrar==1)
		mostrarleyenda = true;
		
	var TituloXAlign = datosgrafico.graficotitulocolumnasalign;
	var TituloYAlign = datosgrafico.graficotitulofilasalign;

	var mostrarvaloreseje = false;
	if (datosgrafico.graficomuestravaloreseje==1)
		mostrarvaloreseje = true;
	var mostrarvaloresseries = false;
	if (datosgrafico.graficomuestravaloresseries==1)
		mostrarvaloresseries = true;
	
	var Floating = false;
	if (datosgrafico.graficofilaflota==1)
		Floating = true;
	var ValX = null;
	if (datosgrafico.valorx!="")
		ValX = datosgrafico.valorx;
	var ValY = null;
	if (datosgrafico.valory!="")
		ValY = datosgrafico.valory;

	var LeyendaVerticalAlign = datosgrafico.graficoleyendaalinearvertical;
	var LeyendaAlign = datosgrafico.graficoleyendaalinear;


	var Medida = "";
	if (datosgrafico.graficomedida!=null)
		Medida = ' '+datosgrafico.graficomedida;
		
	var zoom = ""
	if (datosgrafico.graficozoom==1)
		zoom = "xy";
		
	
	chart = new Highcharts.Chart({
		chart: {
			renderTo: 'container_'+Codigo,
			type: Tipo,
			height: datosgrafico.graficoalto,
			zoomType: zoom,
			inverted: invertir
		},
		title: {
			text: Titulo,
			align: TituloAlign
		},
		subtitle: {
			text: SubTitulo,
			align: SubTituloAlign
		},
		xAxis: {
			categories: categories,
			title: {
				text: TituloX,
				align: TituloXAlign
			},
			labels: {
				enabled:mostrarvaloreseje
			}
			
		},
		yAxis: {
			
			title: {
				text: TituloY,
				align: TituloYAlign
			},
			labels: {
				enabled:mostrarvaloresseries,
				overflow: 'justify'
				
			}
		},
		tooltip: {
			formatter: function() {
				return ''+
					this.series.name +': '+ this.y +' '+Medida;
			}
		},
		plotOptions: {
			column: {
				dataLabels: {
					enabled: false
				}
			}
		},
		
		legend: {
			enabled :mostrarleyenda,
			layout: 'horizontal',
			floating :Floating,
			x: ValX,
			y: ValY,
			align: LeyendaAlign,
			verticalAlign: LeyendaVerticalAlign,
			borderWidth: 1,
			backgroundColor: '#FFFFFF',
			shadow: true
		},

		credits: {
			enabled: true
		},
		series: seriescarga
	});
}	





function GraficoPorcentajes()
{
	chart && chart.destroy();
	chart = null;
	
	var argv = GraficoPorcentajes.arguments;
	var Tipo = argv[0];
	var Codigo = argv[1];
	var Titulo = datosgrafico.graficotitulo;
	var SubTitulo = datosgrafico.graficodesc;
	var TituloX = datosgrafico.graficotitulocolumnas;
	var TituloY = datosgrafico.graficotitulofilas;

	if (TituloX=="")
		TituloX = null;
	if (TituloY=="")
		TituloY = null;
	if (Titulo=="")
		Titulo = null;
	if (SubTitulo=="")
		SubTitulo = null;
	
	var TituloAlign = datosgrafico.graficotituloalign;
	var SubTituloAlign = datosgrafico.graficodescalign;
	
	var invertir = false;
	if (datosgrafico.graficoinvertir==1)
		invertir = true;

	var mostrarleyenda = false;
	if (datosgrafico.graficoleyendamostrar==1)
		mostrarleyenda = true;
		
	var TituloXAlign = datosgrafico.graficotitulocolumnasalign;
	var TituloYAlign = datosgrafico.graficotitulofilasalign;


	
	var Floating = false;
	if (datosgrafico.graficofilaflota==1)
		Floating = true;
	var ValX = null;
	if (datosgrafico.valorx!="")
		ValX = datosgrafico.valorx;
	var ValY = null;
	if (datosgrafico.valory!="")
		ValY = datosgrafico.valory;

	var LeyendaVerticalAlign = datosgrafico.graficoleyendaalinearvertical;
	var LeyendaAlign = datosgrafico.graficoleyendaalinear;


	var Medida = "";
	if (datosgrafico.graficomedida!=null)
		Medida = ' '+datosgrafico.graficomedida;
		
	var zoom = ""
	if (datosgrafico.graficozoom==1)
		zoom = "xy";
		
	chart = new Highcharts.Chart({
		chart: {
			renderTo: 'container_'+Codigo,
			type: Tipo,
			height: datosgrafico.graficoalto,
			zoomType: zoom,
			inverted: invertir
		},
		yAxis: {
			title: {
				text: TituloY,
				align: TituloYAlign
			},
			labels: {
				overflow: 'justify'
			}
		},
		title: {
			text: Titulo,
			align: TituloAlign
		},
		xAxis: {
			categories: categories
		},
		subtitle: {
			text: SubTitulo,
			align: SubTituloAlign
		},
		tooltip: {
			formatter: function() {
				return ''+
					this.point.name +': '+ this.y + Medida;
			}
		},
		plotOptions: {
			pie: {
				allowPointSelect: true,
				cursor: 'pointer',
				dataLabels: {
					enabled: true,
					color: '#000000',
					connectorColor: '#000000',
					formatter: function() {
						return '<b>'+ this.point.name +'</b>: '+ this.y + Medida;
					}
				},
				showInLegend: true
			}
		},		
		legend: {
			enabled :mostrarleyenda,
			layout: 'horizontal',
			floating :Floating,
			x: ValX,
			y: ValY,
			align: LeyendaAlign,
			verticalAlign: LeyendaVerticalAlign,
			borderWidth: 1,
			backgroundColor: '#FFFFFF',
			shadow: true
		},

		credits: {
			enabled: true
		},
		series: seriescarga
	});
}	







