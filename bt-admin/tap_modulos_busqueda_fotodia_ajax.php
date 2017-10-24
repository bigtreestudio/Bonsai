<?php 
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

$oFotosDia = new cFotosDia($conexion);
$oMultimedia = new cMultimedia($conexion,"");

//$oNoticiasCategorias = new cNoticiasCategorias($conexion);

header('Content-Type: text/html; charset=iso-8859-1'); 
$datos['fotodiatitulo'] = $_POST['fotodiatitulo'];
$datos['fotodiaestado'] = ACTIVO;
$datos['limit'] = "LIMIT 0,20";

if(!$oFotosDia->BusquedaAvanzada($datos,$resultado,$numfilas)) {
	$error = true;
}
if ($numfilas>0)
{
	
	while ($fila = $conexion->ObtenerSiguienteRegistro($resultado))
	{
		$imagen = $oMultimedia->DevolverDireccionImg($fila);
	?>
        <tr>
            <td style="text-align:center"><?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['fotodiacod'],ENT_QUOTES); ?></td>
            <td style="text-align:center"><img src="<?php  echo $imagen; ?>" /></td>
            <td style="text-align:left" id="fotodiatitulo_<?php  echo $fila['fotodiacod']?>"><?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['fotodiatitulo'],ENT_QUOTES); ?></td>
            <td style="text-align:left"><?php  echo FuncionesPHPLocal::ConvertirFecha($fila['fotofecha'],"aaaa-mm-dd","dd/mm/aaaa"); ?></td>
            <td style="text-align:center; font-weight:bold;">
               <a class="left add_noticia" href="javascript:void(0)" onclick="AgregarFoto(<?php  echo $fila['fotodiacod']?>,'<?php  echo $imagen?>')">&nbsp;</a>
            </td>
        </tr> 
	<?php 
	}
}
?>