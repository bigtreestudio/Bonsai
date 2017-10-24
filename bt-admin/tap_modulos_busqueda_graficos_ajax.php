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

$oGraficos = new cGraficos($conexion);
//$oNoticiasCategorias = new cNoticiasCategorias($conexion);


header('Content-Type: text/html; charset=iso-8859-1'); 

$datos = array();
if (!$oGraficos->BusquedaAvanzada ($datos,$resultado,$numfilas))
	die();

if ($numfilas>0)
{
	
	while ($fila = $conexion->ObtenerSiguienteRegistro($resultado))
	{
	?>
        <tr>
            <td style="text-align:center"><?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['graficocod'],ENT_QUOTES); ?></td>
            <td style="text-align:left" id="graficotitulo_<?php  echo $fila['graficocod']?>"><?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['graficotitulo'],ENT_QUOTES); ?></td>
            <td style="text-align:center; font-weight:bold;">
               <a class="left" href="javascript:void(0)" onclick="AgregarGrafico(<?php  echo $fila['graficocod']?>)">Seleccionar</a>
            </td>
        </tr> 
	<?php 
	}
}
?>