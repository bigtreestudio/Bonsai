<?php 
set_time_limit ( 600000 );
ini_set('memory_limit', '512M');
ob_start();
error_reporting(E_ALL);

//defino que uso como raiz en los crones... el __DIR__ no funciona en dattatec
//define("DIRRAIZCRONES",__DIR__."/");
define("DIRRAIZCRONES","");//para dattatec

include(DIRRAIZCRONES."../config/include.php");
include(DIRRAIZCRONES."../Librerias/FeedWriter.php");

$conexion = new accesoBDLocal(SERVIDORBD,USUARIOBD,CLAVEBD);
$conexion->SeleccionBD(BASEDATOS);
FuncionesPHPLocal::CargarConstantes($conexion,array("sistema"=>SISTEMA));

$oCrones = new cCrones($conexion);
$oCronesEjecucion = new cCronesEjecucion($conexion);

$datos['fecha']= date("Y-m-d H:i:s");
if(!$oCrones->BuscarCronesEjecutar($datos,$resultadoCrones,$numfilasCrones))
	return false;

while ($filaCrones = $conexion->ObtenerSiguienteRegistro($resultadoCrones))
{
	$conexion->ManejoTransacciones("B");
	if(!$oCronesEjecucion->IniciarCron($filaCrones,$cronejecucioncod))
	{
		
		$conexion->ManejoTransacciones("R");
	}
	else 
	{	
		
		$conexion->ManejoTransacciones("C");
		include(DIRRAIZCRONES."../../".$filaCrones['ubicarchivo']);
		$ret['Msg']= (ob_get_contents());
		$datosdevueltos=array();
		$datosdevueltos['json'] = $retjson = json_encode($ret);
		$datosdevueltos['ejecutobien'] = 1;
		ob_clean();
		
		$conexion->ManejoTransacciones("B");
		$datosdevueltos['cronejecucioncod']=$cronejecucioncod;
		$datosdevueltos['croncod'] = $filaCrones['croncod'];
		
		if(!$oCronesEjecucion->FinalizarCron($datosdevueltos))
			$conexion->ManejoTransacciones("R");	
		else
			$conexion->ManejoTransacciones("C");
		
	}
}
?>