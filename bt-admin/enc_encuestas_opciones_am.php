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


$encuestacod=$_POST['encuestacod'];

$botonejecuta = "BtAlta";
$boton = "Alta";
$opcionnombre = "";
$opcionorden = "";
$onclick = "return InsertarEncuestasOpciones();";
$onclickmodif = "return ModificarEncuestasOpciones();";


?>
<link rel="stylesheet" type="text/css" href="modulos/enc_encuestas/css/estilos.css" />
<script type="text/javascript" src="modulos/enc_encuestas/js/enc_encuestas_opciones.js"></script>
<script>

jQuery(document).ready(function(){
	listarEncuestasOpciones();	
});
		

 </script>
    <div style="text-align:left">
        <div class="form">
            <form action="enc_encuestas.php" method="post" name="formbusquedaopcion" id="formbusquedaopcion" >
                <div class="datosgenerales">
                    <div>
                        <label>Opcion:</label>
                    </div>
                    <div class="clearboth aire_menor">&nbsp;</div>
                    <div>
                    
                        <input type="text" name="opcionnombre"  id="opcionnombre" class="full" value="<?php  echo $opcionnombre?>" size="90" maxlength="255">
                    </div>
                   
                   <div class="clear aire_vertical">&nbsp;</div>
                    <div class="menubarra">
                        <ul>
                            <li id="Guardar"><a class="boton verde" name="<?php  echo $botonejecuta?>" value="<?php  echo $boton?>" href="javascript:void(0)"  onclick="<?php  echo $onclick?>">Guardar</a></li>
                            <li style="display:none" id="Modificar"><a class="left boton base" name="<?php  echo $botonejecuta?>" value="<?php  echo $boton?>" href="javascript:void(0)"  onclick="<?php  echo $onclickmodif?>">Modificar</a></li>
                            <li style="display:none" id="Limpiar"><a class="left boton base" href="javascript:void(0)"  onclick="Limpiar()">Cancelar</a></li>
                            <li><a class="left boton base" href="javascript:void(0)"  onclick="DialogClose()">Cerrar Ventana</a></li>
                        </ul>
                    </div>
                </div>

                <input type="hidden" name="encuestacod" id="encuestacod" value="<?php  echo $encuestacod?>" />
                <input type="hidden" name="opciocodmodif" id="opciocodmodif" value="" />
            </form>            
        </div>
    </div>
   
    <div class="clear" style="height:1px;">&nbsp;</div>
    <div id="LstEncuestasOpciones" style="width:100%;">
       <table id="listarEncuestasOpciones"></table>
    </div>
    <div class="clear" style="height:1px;">&nbsp;</div>