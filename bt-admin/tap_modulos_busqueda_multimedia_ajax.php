<?
require('./config/include.php');

$conexion = new accesoBDLocal(SERVIDORBD,USUARIOBD,CLAVEBD);
$conexion->SeleccionBD(BASEDATOS);

// carga las constantes generales
FuncionesPHPLocal::CargarConstantes($conexion,array("roles"=>"si","sistema"=>SISTEMA,"multimedia"=>"si"));
$conexion->SetearAdmiGeneral(ADMISITE);

// arma las variables de sesion y verifica si se tiene permisos
$sesion = new Sesion($conexion,false); // Inicia session y no borra
$sesion->TienePermisos($conexion,$_SESSION['usuariocod'],$_SESSION['rolcod'],$_SERVER['PHP_SELF']);

// ve si el sistema está bloqueado
$oSistemaBloqueo = new SistemaBloqueo();
$oSistemaBloqueo->VerificarBloqueo($conexion);

$oEncabezados = new cEncabezados($conexion);
header('Content-Type: text/html; charset=iso-8859-1'); 

$oMultimedia = new cMultimedia($conexion,"");
$datos = $_POST;

if (isset($datos['multimediatitulo']))
	$datos['criteriobusqueda'] = $datos['multimediatitulo'];
if (isset($datos['multimedianombre']))
	$datos['criteriobusqueda'] = $datos['multimedianombre'];
	
$datos['limit'] = "LIMIT 0,20";
$datos['multimediaestadocod']=ACTIVO;
$datos['orderby']="multimediacod DESC";

if (!$oMultimedia->BusquedaPopup ($datos,$resultado,$numfilas))
	die();


if ($numfilas>0)
{
	
	while ($fila = $conexion->ObtenerSiguienteRegistro($resultado))
	{
		if (!isset($fila["multimediatitulo"]) || $fila["multimediatitulo"]=="")
			$fila["multimediatitulo"]=$fila["multimedianombre"];
		$imagen = '<img src="'.$oMultimedia->DevolverDireccionImg($fila).'" style="max-width:60px;" width="60" alt="Imagen" />';
	?>
        <tr>
            <td style="text-align:center"><? echo htmlspecialchars($fila['multimediacod'],ENT_QUOTES); ?></td>
            <td style="text-align:center"><? echo $imagen?></td>
            <td style="text-align:left" id="multimediatitulo_<? echo $fila['multimediacod']?>"><? echo htmlspecialchars($fila['multimediatitulo'],ENT_QUOTES); ?></td>
            <td style="text-align:center; font-weight:bold;">
               <a class="left add_noticia" href="javascript:void(0)" onclick="AgregarMultimedia(<? echo $fila['multimediacod'] ?>)">&nbsp;</a>
            </td>
        </tr> 
	<?
	}
}
?>