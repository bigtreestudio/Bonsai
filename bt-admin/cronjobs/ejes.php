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


$sql = "select * from plan_ejes order by planejecod ASC";
$erroren="";
$conexion->_EjecutarQuery($sql,$erroren,$resultado,$errno);
while($fila = $conexion->ObtenerSiguienteRegistro($resultado))
{
	$nombre=strtolower(trim($fila["planejenombre"]));
	$vecEjes[$nombre]=$fila["planejecod"];
}
print_r($vecEjes);

$sql = "select * from proyectos_tmp where eje2<>'' and eje2 is not null order by eje2 ASC";
$erroren="";
$conexion->_EjecutarQuery($sql,$erroren,$resultado,$errno);

$canttags=0;
while($fila = $conexion->ObtenerSiguienteRegistro($resultado))
{
	$ejes=explode("/", $fila["eje2"]);
	foreach($ejes as $eje)
	{
		$nombre=strtolower(trim($eje));
		
		$sql2 = "insert into plan_proyectos_ejes (`planproyectocod`, `planejecod`, `ultmodfecha`, `ultmodusuario`) values ('".$vecEjes[$nombre]."', '".$fila["id"]."', now(),1);";
		echo $sql2."<br>";
		$erroren="";
		$conexion->_EjecutarQuery($sql2,$erroren,$resultadoptags,$errno);
		
	}
}
?>