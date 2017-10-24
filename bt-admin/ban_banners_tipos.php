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
$volver= "ban_banners_tipos.php"; 

//----------------------------------------------------------------------------------------- 	
// Inicio de pantalla
?>

<script type="text/javascript" src="js/grid.locale-es.js"></script>
<script type="text/javascript" src="js/jquery.jqGrid.min.js"></script>
<script type="text/javascript" src="modulos/ban_banners/js/ban_banners_tipos.js"></script>
<script type="text/javascript">
</script>

<div class="inner-page-title" style="padding-bottom:2px;">
    <h2>Listado de Banners Tipos  </h2>
</div>
   
<div class="form"> 
<form action="ban_banners_tipos.php" method="post" name="formbusqueda"  class="general_form" id="formbusqueda" >
<div class="ancho_10">
        <div class="ancho_2">
            <div class="ancho_4">
                <label>Descripci&oacute;n:</label>
            </div>
            <div class="ancho_6">
               <input name="bannertipodesc" id="bannertipodesc" class="full" type="text"  onkeydown="doSearch(arguments[0]||event)" maxlength="100" size="60" value="" />
            </div>
        </div>
        <div class="ancho_05">&nbsp;</div>
		<div class="ancho_2">
            <div class="ancho_2">
                <label>Ancho:</label>
            </div>
            <div class="ancho_6">
               <input name="bannerancho" id="bannerancho" class="full" type="text"  onkeydown="doSearch(arguments[0]||event)" maxlength="100" size="60" value="" />
            </div>
        </div>
        <div class="ancho_05">&nbsp;</div>
		<div class="ancho_2">
            <div class="ancho_2">
                <label>Alto:</label>
            </div>
            <div class="ancho_6">
               <input name="banneralto" id="banneralto" class="full" type="text"  onkeydown="doSearch(arguments[0]||event)" maxlength="100" size="60" value="" />
            </div>
        </div>
        <div class="ancho_05">&nbsp;</div>
        <div class="ancho_3">&nbsp;</div>
       <div class="clear fixalto">&nbsp;</div>
    </div>
</form>
</div>
<div class="clear aire_vertical">&nbsp;</div>

<div class="menubarra">
    <ul>
        <li><div class="ancho_boton aire"><a class="boton verde" href="javascript:void(0)" onclick="AltaBannersTipos()">Agregar Tipo Banner</a></div></li>
        <li><div class="ancho_boton aire"><a class="boton base" href="javascript:void(0)" onclick="Resetear()">Limpiar Busqueda</a></div></li>
    </ul>    
</div>
<div id="Popup"></div>
<div class="clear aire_vertical">&nbsp;</div>
<div id="LstBannersTipos" style="width:100%;">
    <table id="ListarBannersTipos"></table>
    <div id="pager2"></div>
</div>
<div class="clearboth">&nbsp;</div>
<?php  
$oEncabezados->PieMenuEmergente();
?>