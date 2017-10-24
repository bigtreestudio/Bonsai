<?
$imgurl="";
if (isset($vars['zonamodulocod']))
{
	$objDataModel = json_decode($vars['modulodata']);
	
	if (isset($objDataModel->compromisoTitulo))
		$compromisoTitulo = $objDataModel->compromisoTitulo;

}
?>

<!-- Styles -->
<style>
body { background-color: #3f3e3b; color: #fff; }
#chartdiv {
	width		: 100%;
	height		: 500px;
	font-size	: 11px;
}							
</style>

<!-- Resources -->
<script src="https://www.amcharts.com/lib/3/amcharts.js"></script>
<script src="https://www.amcharts.com/lib/3/pie.js"></script>
<script src="https://www.amcharts.com/lib/3/plugins/export/export.min.js"></script>
<link rel="stylesheet" href="https://www.amcharts.com/lib/3/plugins/export/export.css" type="text/css" media="all" />
<script src="https://www.amcharts.com/lib/3/themes/chalk.js"></script>

<!-- Chart code -->
<script>
var chart = AmCharts.makeChart( "chartdiv", {
  "type": "pie",
  "theme": "chalk",
  "dataProvider": [ {
    "title": "New",
    "value": 4852
  }, {
    "title": "Returning",
    "value": 9899
  } ],
  "titleField": "title",
  "valueField": "value",
  "labelRadius": 5,

  "radius": "42%",
  "innerRadius": "60%",
  "labelText": "[[title]]",
  "export": {
    "enabled": true
  }
} );
</script>

<!-- HTML -->
<div id="chartdiv"></div>