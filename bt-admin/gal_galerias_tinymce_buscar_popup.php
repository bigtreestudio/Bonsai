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


//----------------------------------------------------------------------------------------- 	
$editorId = $_GET['editorid'];

?>

<script type="text/javascript" src="modulos/gal_galerias/js/gal_galerias_tiny.js"></script>
<script type="text/javascript">
jQuery(document).ready(function(){
	ListarGaleriasRelacionadasPopupTiny();	
});

</script>
<div class="inner-page-title" style="padding-bottom:2px;">
    <h2>Galerias</h2>
</div>
<div class="clear fixalto">&nbsp;</div>

<form action="gal_albums_gal_galerias.php" method="post" name="formgaleriatiny" onSubmit="return false;"  class="general_form" id="formgaleriatiny" >
    <div class="ancho_10">
        <div class="ancho_10">
            <div class="ancho_4">
                <label>Galeria:</label>
            </div>
            <div class="ancho_6">
               <input name="galeriatitulo" id="galeriatitulo" class="full" type="text"  onkeydown="doSearchgaleriaTiny(arguments[0]||event)" maxlength="100" size="120" value="" />
            </div>
        </div>
       <div class="clear fixalto">&nbsp;</div>
	   <input type="hidden" name="editorid" id="editorid" value="<?php  echo $editorId?>" />
    </div>

</form>
<div class="clear aire_vertical">&nbsp;</div>
<div id="LstGaleriasTiny" style="width:100%;">
    <table id="ListarGaleriasTiny"></table>
    <div id="pagergaleriaTiny"></div>
</div>
    

<div class="clearboth">&nbsp;</div>
    


<?php  
?>
