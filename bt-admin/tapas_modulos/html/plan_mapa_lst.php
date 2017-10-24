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
		while($datosComuna = $conexion->ObtenerSiguienteRegistro($resultado)){?>
          <a class="lst-mapa-item" id="comuna_<? echo $datosComuna['comunanumero']?>" href="javascript:void(0)" data-id="<? echo $datosComuna['comunanumero']?>">
            <h4>Comuna N&deg;<? echo FuncionesPHPLocal::HtmlspecialcharsBigtree($datosComuna['comunanumero'],ENT_QUOTES)?></h4>
            <p><? echo FuncionesPHPLocal::HtmlspecialcharsBigtree($datosComuna['comunabarrios'],ENT_QUOTES)?></p>
          </a>
        <? if ($i==8) break;
			$i++;
		}?>
    </div>
      <div class="group-data">
		<? while($datosComuna = $conexion->ObtenerSiguienteRegistro($resultado)){?>
          <a class="lst-mapa-item" id="comuna_<? echo $datosComuna['comunanumero']?>" href="javascript:void(0)" data-id="<? echo $datosComuna['comunanumero']?>">
            <h4>Comuna N&deg;<? echo FuncionesPHPLocal::HtmlspecialcharsBigtree($datosComuna['comunanumero'],ENT_QUOTES)?></h4>
            <p><? echo FuncionesPHPLocal::HtmlspecialcharsBigtree($datosComuna['comunabarrios'],ENT_QUOTES)?></p>
          </a>
        <? }?>
    </div>
    <div class="clearboth"></div>
    </div>
    </div>	
</div>
<?php  