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

$oGraficos = new cGraficos($conexion,"");


header('Content-Type: text/html; charset=iso-8859-1'); 

$_SESSION['msgactualizacion'] = "";
$ret['IsSuccess'] = false;
$error = false;

$_POST=FuncionesPHPLocal::ConvertiraUtf8 ($_POST);

$texto = "";



$conexion->ManejoTransacciones("B");
switch ($_POST['accion'])
{
	case 1:
		if ($oGraficos->Insertar($_POST,$graficocod))
		{
			$msgactualizacion = "Se ha agregado el grafico correctamente.";
			if ($_POST['conjuntocod']==GRAFPORCENTAJES)
				$archivo = "gra_graficos_am_porcentajes.php";
			else
				$archivo = "gra_graficos_am.php";

			FuncionesPHPLocal::ArmarLinkMD5($archivo,array("graficocod"=>$graficocod),$get,$md5);
			$ret['archivo'] = $archivo;	
			$ret['md5'] = $md5;	
			$ret['graficocod'] = $graficocod;	
			$ret['IsSuccess'] = true;	
		}
		break;
	case 2:
		if ($oGraficos->Modificar($_POST))
		{
			$msgactualizacion = "Se ha modificado el grafico correctamente.";
			$ret['IsSuccess'] = true;	
		}
		break;
	case 3:
		if ($oGraficos->Eliminar($_POST))
		{
			$ret['IsSuccess'] = true;
			$msgactualizacion = "Se ha eliminado la fila correctamente.";
			
		}
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