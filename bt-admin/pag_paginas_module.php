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

$oPaginasModulos = new cPaginasModulos($conexion);
if(!$oPaginasModulos->BuscarxPagina($_POST,$resultado,$numfilas))
	return false;


while($fila = $conexion->ObtenerSiguienteRegistro($resultado))
{
	?>
    	<li class="ui-state-highlight TxtMoveModule" id="modulo_<?php  echo $fila['pagmodulocod'] ?>">
			<div class="ancho_8">
				<div style="text-align:left;">Cod:<?php  echo $fila['pagmodulocod'] ?></div> 
                <div style="text-align:center"><?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['modulodesc'],ENT_QUOTES);?></div>
            </div>
			<div class="ancho_2">
				<a href="javascript:void(0)" onclick="AbrirEditarModulos(<?php  echo $fila['pagmodulocod'] ?>)" title="Editar Datos">
					<img src="images/edit_action.gif" alt="Editar Datos" />
                </a>    
				<a href="javascript:void(0)" onclick="EliminarModulo(<?php  echo $fila['pagmodulocod'] ?>)" title="Eliminar Modulo">
					<img src="images/cross.png" alt="Eliminar Modulo" />
                </a>    
            </div>
            	
        </li>
	<?php  
}

?>