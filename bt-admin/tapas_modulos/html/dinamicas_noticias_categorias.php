<?php 
FuncionesPHPLocal::CargarConstantes($vars['conexion'],array("roles"=>"si","sistema"=>SISTEMA,"multimedia"=>"si"));

$oNoticias = new cNoticias($vars['conexion']);

$catcod="";
if (isset($vars['zonamodulocod']))
{
	$objDataModel = json_decode($vars['modulodata']);
		
	if (isset($objDataModel->catcod) && is_int((int)$objDataModel->catcod))
		$catcod = $objDataModel->catcod;
	$oCategorias=new cCategorias($vars['conexion']);
	$datosP=array('catcod'=>$catcod);
	$oCategorias->BuscarxCodigo($datosP,$resultadoP,$numfilasP);
	$datosCategoria = $vars['conexion']->ObtenerSiguienteRegistro($resultadoP);
}

$oTapasZonasModulos = new cTapasZonasModulos($vars['conexion'],"");
$datosBusqueda['tapacod'] = $vars['tapacod'];
$datosBusqueda['modulodata'] = "noticiacod";
$arregloNoticias = $oTapasZonasModulos->ArmarArregloModuloData($vars['tapacod'],"noticiacod","noticiacod");

$stringNoticias = implode(",",$arregloNoticias);
?>
<div class="noticiasLstHome tap_modules clearfix" id="module_<?php  echo $vars['zonamodulocod']?>" <?php  echo  $vars['mouseaction']?>>
	<?php  echo $vars['htmledit']?>
	<h1 style="color:<?php  echo $datosCategoria["catcolor"]?>;border-color:<?php  echo $datosCategoria["catcolor"]?>"><?php  echo $datosCategoria["catnom"]?></h1>
    $$Tipo='Include' Archivo='categoria_lst_home.php' Parametros='noticias=<?php  echo $stringNoticias?>||dominio=<?php  echo $datosCategoria['catdominio']?>||catcod=<?php  echo $datosCategoria['catcod']?>||tapacod=<?php  echo $vars['tapacod']?>||color=<?php  echo $datosCategoria["catcolor"]?>'$$
     
</div>
<?php  ?>