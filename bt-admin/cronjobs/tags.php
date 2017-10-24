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

$sql = "select * from plan_tags order by plantagcod ASC";
$erroren="";
$conexion->_EjecutarQuery($sql,$erroren,$resultado,$errno);
while($fila = $conexion->ObtenerSiguienteRegistro($resultado))
{
	$nombre=strtolower(trim($fila["plantagnombre"]));
	$vectags[$nombre]=$fila["plantagcod"];
}

$sql = "select * from proyectos_tmp where etiquetas<>'' and etiquetas is not null  order by etiquetas ASC";
$erroren="";
$conexion->_EjecutarQuery($sql,$erroren,$resultado,$errno);
$canttags=0;
while($fila = $conexion->ObtenerSiguienteRegistro($resultado))
{
	$tags=explode(",", $fila["etiquetas"]);
	foreach($tags as $tagnombre)
	{
		$nombre=strtolower(trim($tagnombre));
		if (isset($vectags[$nombre]))
		{/*
			echo $nombre."<br>";
			//nuevo tag
			$canttags++;
			$sqlt = "insert into plan_tags (plantagnombre, plantagestado, ultmodfecha, ultmodusuario) values ('".trim($tagnombre)."',10, now(),1);";
			$erroren="";
			$conexion->_EjecutarQuery($sqlt,$erroren,$resultadotags,$errno);
			$tagcod=$conexion->UltimoCodigoInsertado()."<br>";
			$vectags[$nombre]=$tagcod;*/
			$sql2 = "insert into plan_proyectos_tags (plantagcod, planproyectocod, ultmodfecha, ultmodusuario) values ('".$vectags[$nombre]."', '".$fila["id"]."', now(),1);";
			echo $sql2."<br><br>";
			$erroren="";
		$conexion->_EjecutarQuery($sql2,$erroren,$resultadoptags,$errno);
		}
		
		
		/*
		*/
	}
}
print_r($vectags);
?>