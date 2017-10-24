<?php 
FuncionesPHPLocal::CargarConstantes($vars['conexion'],array("roles"=>"si","sistema"=>SISTEMA,"multimedia"=>"si"));


$Alto=0;
if (isset($vars['zonamodulocod']))
{
	$objDataModel = json_decode($vars['modulodata']);
	
}
$sql = "SELECT * FROM gcba_comunas AS a WHERE comunaestado=10";
$erroren = "";
$conexion->_EjecutarQuery($sql,$erroren,$resultado,$errno);
$cantidad = $conexion->ObtenerCantidadDeRegistros($resultado);
$arrayComunas = array();
?>
<div class="tap_modules" id="module_<?php  echo $vars['zonamodulocod']?>" <?php  echo  $vars['mouseaction']?>>
	<?php  echo $vars['htmledit']?>
	<div class="list-group list-group-panel">
      <div class="list-group-item list-heading list-heading-default">
        <h3>Comunas</h3>
      </div>
      <div class="groupDataCompleto">
      <div class="group-data">
		<? 
		$i = 1;
		while($datosComuna = $conexion->ObtenerSiguienteRegistro($resultado)){
			$sql = "SELECT b.barriocod, b.barrionombre FROM gcba_comunas_barrios AS a INNER JOIN gcba_barrios AS b ON a.barriocod=b.barriocod 
					WHERE a.comunacod=".$datosComuna['comunacod'];
			$erroren = "";
			$conexion->_EjecutarQuery($sql,$erroren,$resultadoBarrio,$errno);
			$cantidadBarrio = $conexion->ObtenerCantidadDeRegistros($resultadoBarrio);
			$j = 1;
			?>
          <div class="mapa-item-barrios" id="comuna_<? echo $datosComuna['comunanumero']?>" href="javascript:void(0)" data-id="<? echo $datosComuna['comunanumero']?>">
            <h4>Comuna N&deg;<? echo FuncionesPHPLocal::HtmlspecialcharsBigtree($datosComuna['comunanumero'],ENT_QUOTES)?></h4>
            <p>
				<? if ($cantidadBarrio>0){?>
					<? while($datosComuna = $conexion->ObtenerSiguienteRegistro($resultadoBarrio)){?>
						<a href="javascript:void(0)" class="item-barrio item-barrio-<? echo $datosComuna['barriocod']?>" data-id="<? echo $datosComuna['barriocod']?>">
							<? echo FuncionesPHPLocal::HtmlspecialcharsBigtree($datosComuna['barrionombre'],ENT_QUOTES)?>
         				</a><? echo ($j<$cantidadBarrio) ? " - " : ""; $j++;?>
					<? }?>
				<? }?>
            </p>    
          </div>
        <? if ($i==8) break;
			$i++;
		}?>
    </div>
      <div class="group-data">
		<? while($datosComuna = $conexion->ObtenerSiguienteRegistro($resultado)){
			$sql = "SELECT b.barriocod, b.barrionombre FROM gcba_comunas_barrios AS a INNER JOIN gcba_barrios AS b ON a.barriocod=b.barriocod 
					WHERE a.comunacod=".$datosComuna['comunacod'];
			$erroren = "";
			$conexion->_EjecutarQuery($sql,$erroren,$resultadoBarrio,$errno);
			$cantidadBarrio = $conexion->ObtenerCantidadDeRegistros($resultadoBarrio);
			$i = 1;
			?>
          <div class="mapa-item-barrios" id="comuna_<? echo $datosComuna['comunanumero']?>" href="javascript:void(0)" data-id="<? echo $datosComuna['comunanumero']?>">
            <h4>Comuna N&deg;<? echo FuncionesPHPLocal::HtmlspecialcharsBigtree($datosComuna['comunanumero'],ENT_QUOTES)?></h4>
            <p>
				<? if ($cantidadBarrio>0){?>
					<? while($datosComuna = $conexion->ObtenerSiguienteRegistro($resultadoBarrio)){?>
						<a href="javascript:void(0)" class="item-barrio item-barrio-<? echo $datosComuna['barriocod']?>" data-id="<? echo $datosComuna['barriocod']?>">
							<? echo FuncionesPHPLocal::HtmlspecialcharsBigtree($datosComuna['barrionombre'],ENT_QUOTES)?>
         				</a><? echo ($i<$cantidadBarrio) ? " - " : ""; $i++;?>
					<? }?>
				<? }?>
            </p>    
          </div>
        <? }?>
    </div>
    <div class="clearboth"></div>
    </div>
    </div>	
</div>
<?php  