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
$oTapasModulosCategorias= new cTapasModulosCategorias($conexion);
$modulocod='';

$datos = $_POST;
$datos['modulotipocod'] = 2;
$oTapasModulosCategorias->BuscarModulosTapasxCodigo($datos,$resultado,$numfilas);
if ($numfilas>0)
{
	
	?>
	<script type="text/javascript">
		jQuery(document).ready(function(){
			Droppable();
		});					
	</script>
	<?php  
	echo "<ul class='draggable'>";
	while($fila = $conexion->ObtenerSiguienteRegistro($resultado))
	{
		?>
        	<li class="ui-state-highlight TxtMoveModule" id="<?php  echo $fila['modulocod']?>">
				<?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['modulodesc'],ENT_QUOTES);?>
            </li>
        <?php  
		
	}
	echo "</ul>";
}
?>