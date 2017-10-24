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

header('Content-Type: text/html; charset=iso-8859-1'); 

?>
<link rel="stylesheet" type="text/css" href="modulos/rev_tapas/css/estilos.css" />

<script type="text/javascript">var sizeLimitFile = <?php  echo TAMANIOARCHIVOS;?>;</script>
<script type="text/javascript">var sizeLimitFileAudio = <?php  echo TAMANIOARCHIVOSAUDIO;?>;</script>
<script type="text/javascript" src="modulos/mul_multimedia/js/funciones.js"></script>
<script type="text/javascript" src="modulos/rev_tapas/js/rev_tapas_tapa_multimedia_am.js"></script>
<script type="text/javascript">
</script>
    <div style="text-align:left">
        <div class="aire_vertical ">
            <form action="rev_tapas_am.php" method="post" name="formulariorevtapas" id="formulariorevtapas" >
                <div class="datosgenerales">
                    <div class="clearboth aire_menor">&nbsp;</div>
                    <div class="ancho_10">
                        <div class="ancho_10">
                        <div id="mul_multimedia_bt_subir_tapa"></div> 
                        <input type="hidden" name="size" id="size"  value="" />
                        <input type="hidden" name="name" id="name"  value="" />
                        <input type="hidden" name="file" id="file"  value="" />
                        <div class="clear fixalto">&nbsp;</div>
                    </div>
                    <div class="menubarra">
                        <ul>
                            <li><a class="left" href="javascript:void(0)"  onclick="DialogClose()">Cerrar Ventana</a></li>
                        </ul>
                    </div>         			
                    <input type="hidden" name="revtapacod" id="revtapacod" value="<?php  echo $_POST['revtapacod']?>"/>
            </form>
        </div>
    </div>
