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

$_SESSION['datosusuario'] = $_SESSION['busqueda'] = array();
$_SESSION['volver'] ="con_formularios_tipos.php"; 
//----------------------------------------------------------------------------------------- 	
// Inicio de pantalla
?>

<script type="text/javascript" src="js/grid.locale-es.js"></script>
<script type="text/javascript" src="js/jquery.jqGrid.min.js"></script>
<script type="text/javascript" src="modulos/con_contactos/js/con_contactos_tipos.js"></script>
<script type="text/javascript">
</script>

<div class="inner-page-title" style="padding-bottom:2px;">
    <h2>Listado de Tipos de Formularios</h2>
</div>
<div class="txt_izq">
     <form action="con_formularios_tipos.php" method="post" name="formbusqueda" id="formbusqueda">
    </form>
</div>

<div class="clear aire_vertical">&nbsp;</div>
<div class="menubarra">
    <ul>
        <li><a class="left" href="javascript:void(0)" onclick="AltaTipoFormulario()">Nuevo Tipo de Formulario</a></li>
    </ul>    
</div>

<div id="Popup"></div>         		
<div id="LstTiposFormularios" style="width:100%;">
    <table id="ListarTiposFormularios"></table>
    <div id="pager2"></div>
</div>
    
<div class="clearboth">&nbsp;</div>
    
<?php  
$oEncabezados->PieMenuEmergente();
?>