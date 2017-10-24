<?php 
require('./config/include.php');

$conexion = new accesoBDLocal(SERVIDORBD,USUARIOBD,CLAVEBD);
$conexion->SeleccionBD(BASEDATOS);

// carga las constantes generales
FuncionesPHPLocal::CargarConstantes($conexion,array("roles"=>"si","sistema"=>SISTEMA));
$conexion->SetearAdmiGeneral(ADMISITE);

// arma las variables de sesion y verifica si se tiene permisos
$sesion = new Sesion($conexion,false); // Inicia session y no borra
//$sesion->TienePermisos($conexion,$_SESSION['usuariocod'],$_SESSION['rolcod'],$_SERVER['PHP_SELF']);

// ve si el sistema está bloqueado
$oSistemaBloqueo = new SistemaBloqueo();
$oSistemaBloqueo->VerificarBloqueo($conexion);

$oEncabezados = new cEncabezados($conexion);

$oCompromisos = new cOgpCompromisos($conexion);
//$oCompromisosCategorias = new cNoticiasCategorias($conexion);

header('Content-Type: text/html; charset=iso-8859-1'); 
$datos['usuariocod'] = $_SESSION['usuariocod'];
$datos['rolcod'] = $_SESSION['rolcod']; 
$datos['orderby'] = "compromisocod desc";
$datos['compromisotitulo'] = $_POST['compromisotitulo'];
if (isset($_POST['temacod']) && $_POST['temacod']!="")
	$datos['temacod'] = $_POST['temacod'];
$datos['compromisoestado'] = 10;
$datos['limit'] = "LIMIT 0,20";

if(!$oCompromisos->BusquedaAvanzada($datos,$resultadoCompromisos,$numfilas)) {
	$error = true;
}
if ($numfilas>0)
{
	
	while ($fila = $conexion->ObtenerSiguienteRegistro($resultadoCompromisos))
	{
	?>
        <tr>
            <td style="text-align:center"><?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['compromisocod'],ENT_QUOTES); ?></td>
            <td style="text-align:left" id="compromisotitulo_<?php  echo $fila['compromisocod']?>"><?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['compromisotitulo'],ENT_QUOTES); ?></td>
            <td style="text-align:center; font-weight:bold;">
               <input type="hidden" name="noticiacopete_<?php  echo $fila['compromisocod']?>" id="noticiacopete_<?php  echo $fila['compromisocod']?>" value="<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree(utf8_encode($fila['noticiacopete']),ENT_QUOTES); ?>" />
               <a class="left add_noticia" href="javascript:void(0)" onclick="AgregarCompromiso(<?php  echo $fila['compromisocod']?>)">&nbsp;</a>
            </td>
        </tr> 
	<?php 
	}
}
?>