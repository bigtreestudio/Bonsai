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

$oFrases = new cFrases($conexion);
//$oNoticiasCategorias = new cNoticiasCategorias($conexion);


header('Content-Type: text/html; charset=iso-8859-1'); 
$datos = $_POST;
//$datos['fraseestado'] = PUBLICADO;
$datos['limit'] = "LIMIT 0,20";
if(!$oFrases->BusquedaAvanzada($datos,$resultadofrases,$numfilas)) {
	$error = true;
}

if ($numfilas>0)
{
	
	while ($fila = $conexion->ObtenerSiguienteRegistro($resultadofrases))
	{
	?>
        <tr>
            <td style="text-align:center"><?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['frasecod'],ENT_QUOTES); ?></td>
            <td style="text-align:left" id="fraseautor_<?php  echo $fila['frasecod']?>"><?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['fraseautor'],ENT_QUOTES); ?></td>
            <td style="text-align:center; font-weight:bold;">
               <a class="left" href="javascript:void(0)" onclick="AgregarFrase(<?php  echo $fila['frasecod']?>)">Seleccionar</a>
            </td>
        </tr> 
	<?php 
	}
}
?>