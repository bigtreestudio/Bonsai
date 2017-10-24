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

$oUsuarios=new cUsuarios($conexion,"");


/*
FuncionesPHPLocal::ArmarLinkMD5(basename($_SERVER['PHP_SELF']),array("usuariocod"=>$_POST['usuariocod']),$get,$md5);
if($_POST["md5"]!=$md5)
{
	FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Acción Ilegal.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
	$oEncabezados->PieMenuEmergente();
	die();
}

$usuariocod = $_POST['usuariocod'];
FuncionesPHPLocal::ArmarLinkMD5("usuarios_modificar_datos.php",array("usuariocod"=>$usuariocod),$get_post,$md5_post);
*/
//----------------------------------------------------------------------------------------- 	
// Inicio de pantalla

header('Content-Type: text/html; charset=iso-8859-1'); 

$_POST=FuncionesPHPLocal::ConvertiraUtf8 ($_POST);

$conexion->ManejoTransacciones("B");
$result=true;

$ret['IsSuccess'] = false;
//print_r($_POST);die;
if (isset($_POST['accion']))
{
	switch ($_POST['accion'])	
	{
		case 1:
			$_POST['usuariocod'] = $_SESSION["usuariocod"];
			if($oUsuarios->Modificar($_POST))
			{
				$ret['IsSuccess'] = true;
				$_SESSION['usuarionombre'] = $_POST['usuarionombre'];
				$_SESSION['usuarioapellido'] = $_POST['usuarioapellido'];
			}
			if ($ret['IsSuccess'])
				FuncionesPHPLocal::MostrarMensaje($conexion,MSG_OK,'Se han modificado sus datos correctamente',array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
				
			$ret['Msg'] = ob_get_contents();
			break;
		
		default:
			$ret['Msg'] =  FuncionesPHPLocal::HtmlspecialcharsBigtree('Debe seleccionar una accion valida.',ENT_QUOTES);
			
			break;
	}
}else
	$ret['Msg'] =  FuncionesPHPLocal::HtmlspecialcharsBigtree('Debe seleccionar una acción',ENT_QUOTES);

if ($ret['IsSuccess'])
	$conexion->ManejoTransacciones("C");
else
	$conexion->ManejoTransacciones("R");
	
ob_end_clean();
echo json_encode($ret); 
ob_end_flush();
?>