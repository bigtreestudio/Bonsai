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

// ve si el sistema est� bloqueado
$oSistemaBloqueo = new SistemaBloqueo();
$oSistemaBloqueo->VerificarBloqueo($conexion);

$oEncabezados = new cEncabezados($conexion);

header('Content-Type: text/html; charset=iso-8859-1'); 

//----------------------------------------------------------------------------------------- 	
// Inicio de pantalla

$oAgenda = new cAgenda($conexion,"");
$conexion->ManejoTransacciones("B");
$result=true;
$texto = "";
$ret['IsSuccess'] = false;
switch ($_POST['accion'])	
{
	case 1:
		$_POST=FuncionesPHPLocal::ConvertiraUtf8 ($_POST);
		$datos = $_POST;
		$datos['horariodesde'] = $_POST['horainicio'].":".$_POST['minutosinicio'];
		$datos['horariohasta'] = $_POST['horafin'].":".$_POST['minutosfin'];
		$datos['horariofdesde'] = FuncionesPHPLocal::ConvertirFecha($_POST['agendafdesde'],"dd/mm/aaaa","aaaa-mm-dd");
		$datos['horariofhasta'] = FuncionesPHPLocal::ConvertirFecha($_POST['agendafhasta'],"dd/mm/aaaa","aaaa-mm-dd");
		$datos["horarioestadocod"]=ACTIVO;

		if($oAgenda->Insertar($datos,$agendacod))	{
			$ret['IsSuccess'] = true;
			FuncionesPHPLocal::ArmarLinkMD5("age_agenda_alta.php",array("agendacod"=>$agendacod),$getagenda,$md5agenda);
			$ret['header'] = "age_agenda_alta.php?".str_replace("&amp;","&",$getagenda);	
			$msgactualizacion = "Se ha agregado el evento correctamente.";		
		}
		
		break;
	
	case 2:
		$_POST=FuncionesPHPLocal::ConvertiraUtf8 ($_POST);
		$datos = $_POST;
		$datos['horariodesde'] = $_POST['horainicio'].":".$_POST['minutosinicio'];
		$datos['horariohasta'] = $_POST['horafin'].":".$_POST['minutosfin'];
		$datos['horariofdesde'] = FuncionesPHPLocal::ConvertirFecha($_POST['agendafdesde'],"dd/mm/aaaa","aaaa-mm-dd");
		$datos['horariofhasta'] = FuncionesPHPLocal::ConvertirFecha($_POST['agendafhasta'],"dd/mm/aaaa","aaaa-mm-dd");

		if($oAgenda->Modificar($datos)){
			$ret['IsSuccess'] = true;
			FuncionesPHPLocal::ArmarLinkMD5("age_agenda_alta.php",array("agendacod"=>$_POST['agendacod']),$getagenda,$md5agenda);
			$ret['header'] = "age_agenda_alta.php?".str_replace("&amp;","&",$getagenda);	
			$msgactualizacion = "Se ha agregado el evento correctamente.";
			}

		break;
	
	case 3:
		$datos = $_POST;
		if($oAgenda->Eliminar($datos)){
			$ret['IsSuccess'] = true;
			$msgactualizacion = "Se ha eliminado el evento correctamente.";
		}
		break;
	
		
	case 4:
		if ($oAgenda->DesActivar($_POST))
		{
			$ret['IsSuccess'] = true;
			$msgactualizacion = "Se ha desactivado el evento correctamente.";
			
		}
		break;

	case 5:
		if ($oAgenda->Activar($_POST))
		{
			$ret['IsSuccess'] = true;
			$msgactualizacion = "Se ha activado el evento correctamente.";
			
		}
		break;
	
		
	default:
		$ret['Msg'] =  FuncionesPHPLocal::HtmlspecialcharsBigtree('Debe seleccionar una accion valida.',ENT_QUOTES);
		
		break;
}

if ($ret['IsSuccess'])
{	
	FuncionesPHPLocal::MostrarMensaje($conexion,MSG_OK,$msgactualizacion,array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$texto));
	$conexion->ManejoTransacciones("C");
}
else
	$conexion->ManejoTransacciones("R");



$ret['Msg'] = ob_get_contents();
ob_clean();
echo json_encode($ret); 
ob_end_flush();
?>