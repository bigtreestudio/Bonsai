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


$formulariocod=$_POST['formulariocod'];

$botonejecuta = "BtAlta";
$boton = "Alta";
$enviomail = "";
$enviotipo = "";
$onclick = "return InsertarEmail();";


?>
<link rel="stylesheet" type="text/css" href="modulos/enc_encuestas/css/estilos.css" />
<script type="text/javascript" src="modulos/con_contactos/js/con_contactos_email.js"></script>
<script>

jQuery(document).ready(function(){
	ListarEmails();	
});
		

 </script>
    <div style="text-align:left">
        <div class="form">
            <form action="enc_encuestas.php" method="post" name="formbusquedaemail" id="formbusquedaemail" >
                <div class="datosgenerales">
                    <div class="ancho_5">
                        <div>
                            <label>Email:</label>
                        </div>
                        <div class="clearboth aire_menor">&nbsp;</div>
                        <div>
                            <input type="text" name="enviomail"  id="enviomail" class="full" value="<?php  echo $enviomail?>" size="90" maxlength="255">
                        </div>
					</div>
                    <div class="ancho_05">&nbsp;</div>
                    <div class="ancho_4">
                        <div>
                            <label>Tipo:</label>
                        </div>
                        <div class="clearboth aire_menor">&nbsp;</div>
                        <div>
                        <?php 	
                            $oFormulariosEnviosTipos=new cFormulariosEnviosTipos($conexion);
                            $oFormulariosEnviosTipos->FormulariosEnviosTiposSP($spnombre,$spparam);
                            FuncionesPHPLocal::ArmarCombo_SinSalto_BD($conexion,$spnombre,$spparam,"formbusquedaemail","enviotipo","enviotipo","enviotipodesc",$enviotipo,"Seleccione un tipo",$regnousar,$selecnousar,1,"","","",false);
                        ?>
                        </div>
                    </div>
                   <div class="clear aire_vertical">&nbsp;</div>
                    <div class="menubarra">
                        <ul>
                            <li id="Guardar"><a class="boton verde" name="<?php  echo $botonejecuta?>" value="<?php  echo $boton?>" href="javascript:void(0)"  onclick="<?php  echo $onclick?>">Guardar</a></li>
                            <li><a class="left boton base" href="javascript:void(0)"  onclick="DialogClose()">Cerrar Ventana</a></li>
                        </ul>
                    </div>
                </div>

                <input type="hidden" name="formulariocod" id="formulariocod" value="<?php  echo $formulariocod?>" />
            </form>            
        </div>
    </div>
   
    <div class="clear" style="height:1px;">&nbsp;</div>
    <div id="LstEmails" style="width:100%;">
       <table id="ListarEmails"></table>
       <div id="pager3"></div>
    </div>
    <div class="clear" style="height:1px;">&nbsp;</div>