<?php

$sql = "SELECT a.planejecod, a.planejenombre, a.planejecolor, COUNT(c.planproyectocod) AS total 
		FROM plan_ejes AS a 
		LEFT JOIN plan_proyectos_ejes AS b ON a.planejecod = b.planejecod 
		LEFT JOIN plan_proyectos AS c ON b.planproyectocod = c.planproyectocod 
		GROUP BY a.planejecod";
$erroren = "";
$conexion->_EjecutarQuery($sql,$erroren,$resultado,$errno);


$cantidad = $conexion->ObtenerCantidadDeRegistros($resultado);
$i=0;
?>
<script type="text/javascript">
jQuery(document).ready(function(){
	$("#doughnutChart").drawDoughnutChart([
	  <? while($fila = $conexion->ObtenerSiguienteRegistro($resultado)){?>
		  { 
			title: "<? echo FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['planejenombre'],ENT_QUOTES)?>",
			value : <? echo $fila['total']?>,  
			color: "<? echo $fila['planejecolor']?>" 
		  }
		  <? 
			$i++;
			if ($i < $cantidad)
				echo ",";
	  }?>
	]);

});

</script>

<? ?>