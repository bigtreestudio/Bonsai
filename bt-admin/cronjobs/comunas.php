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


$sql = "select * from gcba_comunas order by comunanumero ASC";
$erroren="";
$conexion->_EjecutarQuery($sql,$erroren,$resultado,$errno);
while($fila = $conexion->ObtenerSiguienteRegistro($resultado))
{
	$vecComunas["comuna ".$fila["comunanumero"]]=$fila["comunacod"];
}
print_r($vecComunas);

$sql = "select * from proyectos_tmp where comunas<>'' and comunas is not null order by comunas ASC";
$erroren="";
$conexion->_EjecutarQuery($sql,$erroren,$resultado,$errno);

$canttags=0;
while($fila = $conexion->ObtenerSiguienteRegistro($resultado))
{
	$comunas=explode("/", $fila["comunas"]);
	foreach($comunas as $comuna)
	{
		$nombre=strtolower(trim($comuna));
		
		$sql2 = "insert into plan_proyectos_comunas (`planproyectocod`, `comunacod`, `ultmodfecha`, `ultmodusuario`) values ( '".$fila["id"]."','".$vecComunas[$nombre]."', now(),1);";
		echo $sql2."<br>";
		$erroren="";
		$conexion->_EjecutarQuery($sql2,$erroren,$resultadoptags,$errno);
		
	}
}
?>