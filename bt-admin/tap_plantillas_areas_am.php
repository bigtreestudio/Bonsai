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


$oPlantillasAreasHtml= new cPlantillasAreasHtml($conexion);
$oPlantilla= new cPlantillas($conexion);
$datos = $_POST;
$plantcod = $_POST['plantcod'];
if(!$oPlantilla->BuscarxCodigo($datos,$resultado,$numfilas))
	return false;

$datosplantilla = $conexion->ObtenerSiguienteRegistro($resultado);
if(!$oPlantillasAreasHtml->TraerAreasHtml($resultadoAreas,$numfilasAreas))
	return false;

$botonejecuta = "BtAlta";
$boton = "Alta";
$enviomail = "";
$enviotipo = "";
$onclick = "return InsertarEmail();";


?>
<script type="text/javascript" src="modulos/tap_plantillas_areas/js/tap_plantillas_areas.js"></script>
    <div style="text-align:left">
        <div class="form">
            <form action="tap_plantillas.php" method="post" name="formplantillasareas" id="formplantillasareas" >
            	<input type="hidden" name="plantcod" value="<?php  echo $plantcod?>" id="plantcod" />
                <div class="datosgenerales">
                    <div class="ancho_5">
                        <div>
                            <label>Seleccione un &aacute;rea:</label>
                        </div>
                        <div class="clearboth aire_menor">&nbsp;</div>
                        <div>
                            <select name="areahtmlcod" id="areahtmlcod" style="width:300px;">
                            	<option value="">Seleccione un &aacute;rea</option>
								<?php  while ($filaArea = $conexion->ObtenerSiguienteRegistro($resultadoAreas)){?>
                                	<option value="<?php  echo $filaArea['areahtmlcod']?>"><?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($filaArea['areahtmldesc'],ENT_QUOTES)?></option>
                                <?php  }?>
                            </select>
                        </div>
					</div>

                   <div class="clear aire_vertical">&nbsp;</div>
                    <div class="menubarra">
                        <ul>
                            <li id="Guardar"><a class="boton verde" name="Agregar" value="Agregar" href="javascript:void(0)" onclick="AgregarArea()">Agregar</a></li>
                            <li><a class="left boton base" href="javascript:void(0)"  onclick="DialogClose()">Cerrar Ventana</a></li>
                        </ul>
                    </div>
                </div>
            </form>            
        </div>
    </div>
   
    <div class="clear" style="height:1px;">&nbsp;</div>
    <div id="LstAreas" style="width:100%;">
       <table id="ListarAreas"></table>
    </div>
    <div class="clear" style="height:1px;">&nbsp;</div>