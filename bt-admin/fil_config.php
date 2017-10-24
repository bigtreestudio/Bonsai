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
$oEncabezados->EncabezadoMenuEmergente($_SESSION['rolcod'],$_SESSION['usuariocod']);


	
//----------------------------------------------------------------------------------------- 	


//----------------------------------------------------------------------------------------- 	
// Inicio de pantalla

?>
<script type="text/javascript" src="modulos/file_config/js/file.js"></script>

<div class="inner-page-title" style="padding-bottom:2px;">
    <h2>Archivos Parametros</h2>
</div>
 

<div class="clear aire_vertical">&nbsp;</div>

<div id="LstFiles" style="width:100%;">
    <table id="ListarFiles"></table>
</div>
<?php 
$oEncabezados->PieMenuEmergente();
?>
