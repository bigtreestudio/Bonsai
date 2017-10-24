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



$esmodif = false;

header('Content-Type: text/html; charset=iso-8859-1'); 

$oAlbums = new cAlbums($conexion,"");



?>
<script type="text/javascript" src="js/grid.locale-es.js"></script>
<script type="text/javascript" src="js/jquery.jqGrid.min.js"></script>
<script type="text/javascript" src="modulos/gal_albums_gal_galerias/js/gal_albums_gal_galerias.js"></script>
<script type="text/javascript">
jQuery(document).ready(function(){
	ListarAlbums();			
});
</script>

<div class="inner-page-title" style="padding-bottom:2px;">
    <h2>Listado de Galerias del album <?php  echo utf8_encode($albumtitulo)?></h2>
</div>
    
<form action="gal_albums_gal_galerias.php" method="post" name="formbusqueda"  class="general_form" id="formbusqueda" >
    <div class="ancho_10">
        <div class="ancho_3">
            <div class="ancho_4">
                <label>Galeria:</label>
            </div>
            <div class="ancho_6">
               <input name="galeriatitulo" id="galeriatitulo" class="full" type="text"  onkeydown="doSearch(arguments[0]||event)" maxlength="100" size="60" value="" />
            </div>
        </div>
        <div class="ancho_05">&nbsp;</div>
        <div class="ancho_3">&nbsp;</div>
       <div class="clear fixalto">&nbsp;</div>
    </div>
    <input type="hidden" name="albumcod" id="albumcod" value="<?php  echo $albumcod;?>" />
</form>

<div class="clear aire_vertical">&nbsp;</div>

<div class="menubarra">
    <ul>
        <li><a class="left" href="ban_banners_am.php">Crear nueva galeria</a></li>
		<li><a class="left" href="javascript:void(0)" onclick="Resetear()">Limpiar B&uacute;squeda</a></li>
    
    </ul>    
</div>

         		
<div id="Popup"></div>
<div class="clear aire_vertical">&nbsp;</div>
<div id="LstAlbums" style="width:100%;">
    <table id="ListarAlbums"></table>
    <div id="pager2"></div>
</div>
    

<div class="clearboth">&nbsp;</div>
