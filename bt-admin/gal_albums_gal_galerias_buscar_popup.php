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

$_SESSION['datosusuario'] = $_SESSION['busqueda'] = array();
//----------------------------------------------------------------------------------------- 	
// Inicio de pantalla

$albumcod= $_POST['albumcod'];
/*$oClientes= new cClientes($conexion);
$datos['orderby'] = "clientecod desc";
if (!$oClientes->BusquedaAvanzada($datos,$resultado,$numfilas))
	return false;	*/
?>
<link rel="stylesheet" type="text/css" href="modulos/gal_albums_gal_galerias/css/gal_albums_gal_galerias.css" />

<script type="text/javascript" src="modulos/gal_albums_gal_galerias/js/gal_albums_gal_galerias_buscar_popup.js"></script>
<script type="text/javascript">
</script>
<div class="inner-page-title" style="padding-bottom:2px;">
    <h2>Galer&iacute;as</h2>
</div>
<div class="clear fixalto">&nbsp;</div>

<div class="form">
<form action="gal_albums_gal_galerias.php" method="post" name="formbusquedagaleria"  class="general_form" id="formbusquedagaleria" >
    <div class="ancho_10">
        <div class="ancho_10">
            <div class="ancho_2">
                <label>Galeria:</label>
            </div>
            <div class="ancho_6">
               <input name="galeriatitulo" id="galeriatitulo" class="full" type="text"  onkeydown="doSearch(arguments[0]||event)" maxlength="100" size="120" value="" />
            </div>
        </div>
       <div class="clear fixalto">&nbsp;</div>
    </div>
    <input type="hidden" name="albumcod" id="albumcod" value="<?php  echo $albumcod;?>" />
</form>
</div>
<div class="clear aire_vertical">&nbsp;</div>
<div id="LstGalerias" style="width:100%;">
    <table id="ListarGalerias"></table>
    <div id="pager2"></div>
</div>
    

<div class="clearboth">&nbsp;</div>
    


<?php  
?>
