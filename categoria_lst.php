<?php  
include("./config/include.php");
include(DIR_CLASES."cNoticias.class.php");
include(DIR_CLASES."cNoticiasCategorias.class.php");
include(DIR_CLASES."cMultimedia.class.php");


$conexion = new accesoBDLocal(SERVIDORBD,USUARIOBD,CLAVEBD);
$conexion->SeleccionBD(BASEDATOS);

FuncionesPHPLocal::CargarConstantes($conexion,array("multimedia"=>"si"));


header('Content-Type: text/html; charset=ISO-8859-15'); 

include("categoria_lst_ajax.php");
?>