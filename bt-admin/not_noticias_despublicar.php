<?php  
ob_start();
require('./config/include.php');

$conexion = new accesoBDLocal(SERVIDORBD,USUARIOBD,CLAVEBD);
$conexion->SeleccionBD(BASEDATOS);

// carga las constantes generales
FuncionesPHPLocal::CargarConstantes($conexion,array("roles"=>"si","sistema"=>SISTEMA));
$conexion->SetearAdmiGeneral(ADMISITE);

// arma las variables de sesion y verifica si se tiene permisos
$sesion = new Sesion($conexion,false); // Inicia session y no borra
$sesion->TienePermisos($conexion,$_SESSION['usuariocod'],$_SESSION['rolcod'],$_SERVER['PHP_SELF']);

// ve si el sistema está bloqueado
$oSistemaBloqueo = new SistemaBloqueo();
$oSistemaBloqueo->VerificarBloqueo($conexion);

$oEncabezados = new cEncabezados($conexion);
$oEncabezados->EncabezadoMenuEmergente($_SESSION['rolcod'],$_SESSION['usuariocod']);

$oNoticiasPublicacion = new cNoticiasPublicacion($conexion);

$conexion->ManejoTransacciones("B");
header('Content-Type: text/html; charset=iso-8859-1'); 

$msg = array();
$msg['IsSucceed'] = false;

if (!isset($_GET['noticiacod']) || $_GET['noticiacod']=="" || !FuncionesPHPLocal::ValidarContenido($conexion,$_GET['noticiacod'],"NumericoEntero"))
{
	FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Error, codigo de noticia inexistente. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
	return false;
}

FuncionesPHPLocal::ArmarLinkMD5(basename($_SERVER['PHP_SELF']),array("noticiacod"=>$_GET['noticiacod'],"noticiaworkflowcod"=>$_GET['noticiaworkflowcod'],"accion"=>$_GET['accion']),$get,$md5);
if($_GET["md5"]!=$md5)
{
	FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Acción Ilegal.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
	$oEncabezados->PieMenuEmergente();
	die();
}

$datos = $_GET;
$datos['rolcod'] = $_SESSION['rolcod'];
?>
<div style="padding:50px; text-align:center; min-height:200px;">
<?php 
switch($datos['accion'])
{
	case 1:
		if($oNoticiasPublicacion->DespublicarNoticia($datos))
		{
			$msg['IsSucceed'] = true;
			FuncionesPHPLocal::MostrarMensaje($conexion,MSG_OK,"Se ha despublicado correctamente la noticia.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
		}	
		break;
				
}

if ($msg['IsSucceed'])
	$conexion->ManejoTransacciones("C");
else
{
	$conexion->ManejoTransacciones("R");
}
?>
    <div style="text-align:center; margin:auto; margin-top:10px;">
       <a href="not_noticias.php"><b>Volver al Listado de noticias</b></a>
    </div>
</div>
<?php  

$oEncabezados->PieMenuEmergente();
ob_end_flush();
?>