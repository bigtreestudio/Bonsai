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

?>
<link href="modulos/mul_multimedia/css/popup.css" rel="stylesheet" title="style" media="all" />
<script type="text/javascript" src="modulos/mul_multimedia/js/funciones.js"></script>
<script type="text/javascript" src="modulos/gal_galerias/js/gal_galeria_fotos.js"></script>
<div>
    <div id="mul_multimedia_fotos">
        <ul>
            <li><a href="#mul_multimedia_seleccionar_imagen">Seleccionar una Im&aacute;gen Existente</a></li>
        </ul>
        <div id="mul_multimedia_seleccionar_imagen">
        	<div class="form" id="TableMultimediawidth" style="width:700px;">
            	<form action="javascript:void(0)" method="post" name="formbusquedamultimedia" id="formbusquedamultimedia">
                	<div class="ancho_10">
                        <div class="ancho_2" style="margin-top:12px">
                            <label>Buscar</label>
                        </div>
                        <div class="ancho_5" style="margin-top:12px">
                            <input type="text" name="criteriobusqueda" id="criteriobusqueda" class="full" value="" />
                        </div>
                        <div class="ancho_1">&nbsp;</div>
                        <div class="ancho_2">
                            <div class="menubarra">
                                <ul>
                                    <li><a class="left boton azul" href="javascript:void(0)" onclick="gridReloadMultimedia()">Buscar</a></li>
                                </ul>
                            </div>        
                        </div>
                        <div class="clear aire_vertical">&nbsp;</div>
                    </div>
                    <div class="clear aire_vertical">&nbsp;</div>
                </form>
            	<table id="TableMultimedia"></table>
                <div id="pagermultimedia"></div>
            </div>
        </div>
   </div>     
</div>