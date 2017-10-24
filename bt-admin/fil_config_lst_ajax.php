<?php  
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

$oFilesConfig=new cFilesConfig($conexion);



header('Content-Type: text/html; charset=iso-8859-1'); 

$datos = $_POST;
if (!$oFilesConfig->BusquedaAvanzada ($datos,$resultado,$numfilas))
	die();

$responce =new StdClass;
$responce->page = 1;
$responce->total = 1; 
$responce->records = $numfilas; 
$responce->rows = array();


if ($numfilas>0)
{
	
	$i=0;
	while ($fila = $conexion->ObtenerSiguienteRegistro($resultado))
	{
		
			FuncionesPHPLocal::ArmarLinkMD5("fil_config_am.php",array("filecod"=>$fila['filecod']),$getFile,$md5);
			$linkedit = '<a class="editar" href="fil_config_am.php?'.$getFile.'" title="Editar File" id="editar_'.$fila['filecod'].'">Editar</a>';
			
			$titulo = "<b>".utf8_encode( FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['filenombre'],ENT_QUOTES)).'</b><div style="font-size:11px; margin:2px 0;">'.utf8_encode( FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['filedesc'],ENT_QUOTES)).'</div>';
			$datosmostrar = array(
				$fila['filecod'],
				$titulo,
				utf8_encode( FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['filetipodesc'],ENT_QUOTES)),
				utf8_encode( FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['fileubic'],ENT_QUOTES)),
				$linkedit
				
			);
			$responce->rows[$i]['filecod'] = $fila['filecod'];
			$responce->rows[$i]['id'] = $fila['filecod'];
			$responce->rows[$i]['cell'] = $datosmostrar;
			$i++;
	}
}
echo json_encode($responce);
?>
