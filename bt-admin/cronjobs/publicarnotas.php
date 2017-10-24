<?php 
header('Content-Type: text/html; charset=iso-8859-1'); 
set_time_limit ( 600000000 );
ini_set('memory_limit', '512M');
error_reporting(E_WARNING | E_ERROR);
include("../config/include.php");
$conexion = new accesoBDLocal(SERVIDORBD,USUARIOBD,CLAVEBD);
$conexion->SeleccionBD(BASEDATOS);

// carga las constantes generales
FuncionesPHPLocal::CargarConstantes($conexion,array("roles"=>"si","sistema"=>SISTEMA));
$conexion->SetearAdmiGeneral(ADMISITE);


$oNoticias = new cNoticiasPublicacion($conexion);
$datosNoticia['orderby'] = "noticiafecha desc";
$datosNoticia['noticiadestacada'] = "1";
$sql = "select * from not_noticias order by noticiacod ASC";
$erroren="";
$conexion->_EjecutarQuery($sql,$erroren,$resultadoNoticias,$errno);

$_SESSION['usuariocod']=1;
$_SESSION['rolcod']=10;

while($datos = $conexion->ObtenerSiguienteRegistro($resultadoNoticias))
{
	$oNoticias->Publicar($datos,$noticiacod);
}
?>