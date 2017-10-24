<?php      
include("./config/include.php");

$conexion = new accesoBDLocal(SERVIDORBD,USUARIOBD,CLAVEBD);

$conexion->SeleccionBD(BASEDATOS);

FuncionesPHPLocal::CargarConstantes($conexion,array("multimedia"=>"si"));

$oEncabezados = new cEncabezados($conexion);



$html=file_get_contents(PUBLICA."index.html");

$oEncabezados->Procesar($html,$htmlprocesado);

?>         