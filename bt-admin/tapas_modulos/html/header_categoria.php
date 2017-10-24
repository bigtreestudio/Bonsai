<?php 
FuncionesPHPLocal::CargarConstantes($vars['conexion'],array("roles"=>"si","sistema"=>SISTEMA,"multimedia"=>"si"));



$oCategorias = new cCategorias($vars['conexion']);
$objDataModel = json_decode($vars['modulodata']);
$datosbusqueda['catcod'] = $objDataModel->catcod;
if(!$oCategorias->BuscarxCodigo($datosbusqueda,$resultado,$numfilas))
	return false;
	
$datoscategoria = $vars['conexion']->ObtenerSiguienteRegistro($resultado);
	
if (isset($vars['zonamodulocod']))
{
	$objDataModel = json_decode($vars['modulodata']);
}
?>
   
    <div class="header_categoria tap_modules" style="background-color:<?php  echo  $datoscategoria['catcolor'] ?>; padding-left:20px; padding-top:8px "  id="module_<?php  echo $vars['zonamodulocod']?>" <?php  echo  $vars['mouseaction']?>>
      <?php  echo $vars['htmledit']?>   
      <div class="imagen texto" style="padding:13px 0 0 70px" >
        <?php  echo $datoscategoria['catnom']?>
      </div>
      
    </div>
<?php  	
?>