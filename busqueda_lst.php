<?php  
include("./config/include.php");
include(DIR_CLASES."cBusqueda.class.php");


$conexion = new accesoBDLocal(SERVIDORBD,USUARIOBD,CLAVEBD);
$conexion->SeleccionBD(BASEDATOS);

FuncionesPHPLocal::CargarConstantes($conexion,array("multimedia"=>"si"));


header('Content-Type: text/html; charset=ISO-8859-15'); 

include("busqueda_lst_ajax.php");
?>