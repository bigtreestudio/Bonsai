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

$oModulos=new cModulos($conexion); 


//----------------------------------------------------------------------------------------- 

//----------------------------------------------------------------------------------------- 		


//----------------------------------------------------------------------------------------- 	
// Inicio de pantalla

$ArregloDatos=array();
if (!$oModulos->Buscar ($ArregloDatos,$numfilas,$resultado))
{
	FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Error al buscar los datos del modulo",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
	die();
}

?>	

<link href="css/elfinder.min.css" rel="stylesheet" title="style" media="all" />
<link href="css/theme.css" rel="stylesheet" title="style" media="all" />

<script type="text/javascript" src="js/elfinder.min.js"></script>
<script type="text/javascript" src="js/elfinder.es.js" charset="utf-8"></script>

<script language="javascript" type="text/javascript">
var campos_linea=new Array("txt|modulocodnuevo","txt|modulodesc","txt|modulotextomenu","cmb|archivocod","txt|modulosec","txt|moduloimg","opt|modulodash","opt|modulomostrar","opt|moduloacciones");
var datos_grilla=new Array();
// ciclo recorriendo los mismos datos que la grilla
datos_grilla[0]=new Array();
datos_grilla[0][0]="";
datos_grilla[0][1]="";
datos_grilla[0][2]="";
datos_grilla[0][3]="";
datos_grilla[0][4]="";
datos_grilla[0][5]="";
datos_grilla[0][6]="";
datos_grilla[0][7]="";

<?php 
	
$i = 1;
while($filamodulo=$conexion->ObtenerSiguienteRegistro($resultado))
{
	

	echo "datos_grilla[".$i."]=new Array();\n";
	echo "datos_grilla[".$i."][0]='".FuncionesPHPLocal::ReemplazarComillas($filamodulo['modulocod'])."';\n";
	echo "datos_grilla[".$i."][1]='".FuncionesPHPLocal::ReemplazarComillas($filamodulo['modulodesc'])."';\n";
	echo "datos_grilla[".$i."][2]='".FuncionesPHPLocal::ReemplazarComillas($filamodulo['modulotextomenu'])."';\n";
	echo "datos_grilla[".$i."][3]='".FuncionesPHPLocal::ReemplazarComillas($filamodulo['archivocod'])."';\n";
	echo "datos_grilla[".$i."][4]='".FuncionesPHPLocal::ReemplazarComillas($filamodulo['modulosec'])."';\n";
	echo "datos_grilla[".$i."][5]='".FuncionesPHPLocal::ReemplazarComillas($filamodulo['moduloimg'])."';\n";
	echo "datos_grilla[".$i."][6]='".FuncionesPHPLocal::ReemplazarComillas($filamodulo['modulodash'])."';\n";
	echo "datos_grilla[".$i."][7]='".FuncionesPHPLocal::ReemplazarComillas($filamodulo['modulomostrar'])."';\n";
	echo "datos_grilla[".$i."][8]='".FuncionesPHPLocal::ReemplazarComillas($filamodulo['moduloacciones'])."';\n";

	
	$i++;
}
?>
</script>
<link href="css/chosen.css" rel="stylesheet" title="style" media="all" />

<script type="text/javascript" src="js/chosen.jquery.min.js"></script>
<script language="javascript" type="text/javascript">
jQuery(document).ready(function(){
	$(".chzn-select-modulos").chosen();

});
</script>

<script type="text/javascript">
<!--
function Validarjs(formulario) 
	{
		if (formulario.modulocodnuevo.value=="")
		{
			alert("Debe ingresar el código del modulo");
			formulario.modulocodnuevo.focus();
			return false;
		}
		if (!ValidarContenido(formulario.modulocodnuevo.value,"NumericoEntero"))
		{
			alert("El código ingresado no es válido");
			formulario.modulocodnuevo.focus();
			return false;
		}	
		if (formulario.modulotextomenu.value=="")
		{
			alert("Debe ingresar el texto en el menú");
			formulario.modulotextomenu.focus();
			return false;
		}
		if (formulario.archivocod.value=="")
		{
			alert("Debe seleccionar el archivo a llamar");
			formulario.archivocod.focus();
			return false;
		}
		if (formulario.modulosec.value=="")
		{
			alert("Debe ingresar la secuencia del modulo");
			formulario.modulosec.focus();
			return false;
		}
		if (!ValidarContenido(formulario.modulosec.value,"NumericoEntero"))
		{
			alert("La secuencia ingresada no es válida");
			formulario.modulosec.focus();
			return false;
		}
		if ((!formulario.modulomostrar[0].checked) && (!formulario.modulomostrar[1].checked) && (!formulario.modulomostrar[2].checked))
		{
			alert("Debe seleccionar si el módulo es un link / se muestra o no en el menú");
			return false;
		}

		return true;
	}
//-->
</script>

<div class="inner-page-title" style="padding-bottom:2px;">
    <h2>M&oacute;dulos</h2>
</div>
<div class="form">
        <form action="modulos_upd.php" class="general_form" method="post" name="formulario">
			<div class="ancho_10 aire">
                                    
                <div class="ancho_1">&nbsp;</div>
                <div class="ancho_9">
                    <div class="ancho_1">
                        <label>M&oacute;dulo:</label>
                    </div>        
                    <div class="ancho_4">
						<?php 	
							FuncionesPHPLocal::ArmarCombo_SinSalto_BD($conexion,"sel_modulos_orden",array('porderby'=>"modulocod"),"formulario","modulocodviejo","modulocod","modulotodo","","Nuevo módulo",$regnousar,$selecnousar,1,"SeleccionCombo(formulario,campos_linea,datos_grilla,this.selectedIndex)","","",false,"","","chzn-select-modulos");
                        ?>
                    </div>
                </div>
                <div class="clearboth brisa_vertical"></div>
                                    
                <div class="ancho_1">&nbsp;</div>
                <div class="ancho_9">
                    <div class="ancho_1">
                        <label>C&oacute;digo:</label>
                    </div>        
                    <div class="ancho_4">
                        <input name="modulocodnuevo" id="modulocodnuevo" type="text" style="text-align:right" class="text" size="5" maxlength="4" value="" />
                    </div>
                </div>
                <div class="clearboth brisa_vertical"></div>
                                    
                <div class="ancho_1">&nbsp;</div>
                <div class="ancho_9">
                    <div class="ancho_1">
                        <label>Descripci&oacute;n:</label>
                    </div>        
                    <div class="ancho_4">
                        <input name="modulodesc" id="modulodesc" type="text" class="text" size="50" maxlength="80" value="" />
                    </div>
                </div>
                <div class="clearboth brisa_vertical"></div>
                                    
                <div class="ancho_1">&nbsp;</div>
                <div class="ancho_9">
                    <div class="ancho_1">
                        <label>Texto en el men&uacute;:</label>
                    </div>        
                    <div class="ancho_4">
                        <input name="modulotextomenu" id="modulotextomenu" type="text" class="text" size="27" maxlength="80" value="" />
                    </div>
                </div>
                <div class="clearboth brisa_vertical"></div>
                                    
                <div class="ancho_1">&nbsp;</div>
                <div class="ancho_9">
                    <div class="ancho_1">
                        <label>Link:</label>
                    </div>        
                    <div class="ancho_4">
						<?php 	
                            $spnombrearch="sel_archivos_orden";
                            $spparamarch=array("porderby"=>"archivonom");
                            FuncionesPHPLocal::ArmarCombo_SinSalto_BD($conexion,$spnombrearch,$spparamarch,"formulario","archivocod","archivocod","archivotodo","","--Seleccione un archivo",$filaarchivo,$actualizar,1,"","",false,false,"","","");
                        ?>
                    </div>
                </div>
                <div class="clearboth brisa_vertical"></div>
                                    
                <div class="ancho_1">&nbsp;</div>
                <div class="ancho_9">
                    <div class="ancho_1">
                        <label>Secuencia:</label>
                    </div>        
                    <div class="ancho_4">
                        <input name="modulosec" id="modulosec" type="text" style="text-align:right" class="text" size="05" maxlength="04" value="" />
                    </div>
                </div>
               <div class="clearboth brisa_vertical"></div>
                <div class="ancho_1">&nbsp;</div>
                <div class="ancho_9">
                    <div class="ancho_1">
                        <label>Im&aacute;gen (class):</label>
                    </div>        
                    <div class="ancho_4">
                        <input name="moduloimg" id="moduloimg" type="text" class="text" size="50" maxlength="80" value="" />
                    </div>
                </div>               
                <div class="clearboth brisa_vertical"></div>
                <div class="ancho_1">&nbsp;</div>
                <div class="ancho_9">
                    <div class="ancho_1" style=" vertical-align:middle">
                        <label>En Dashboard:</label>
                    </div>     
                    <div class="ancho_9">
                        <div class="ancho_1">
                            <label class="radio_label" for="modulodash_si">Si</label>
                            <input name="modulodash" id="modulodash_si" type="radio" class="radio" value="1" />
                        </div>
                        <div class="ancho_1">
                            <label class="radio_label" for="modulodash_no">No</label>
                            <input  name="modulodash" id="modulodash_no" type="radio" class="radio" value="0" />
                        </div>
                    </div>
                </div>
                <div class="clearboth brisa_vertical"></div>
                <div class="ancho_1">&nbsp;</div>
                <div class="ancho_9">
                	<div class="ancho_1">
                    	<label class="radio_label"  for="modulomostrarlink">Link</label>
                    	<input name="modulomostrar" id="modulomostrarlink" type="radio" class="radio" value="L" />
                    </div>
                	<div class="ancho_2">
                    	<label class="radio_label" for="modulomostrarsi">Se muestra</label>
                    	<input name="modulomostrar" id="modulomostrarsi" type="radio" class="radio" value="S" />
                    </div>
                	<div class="ancho_2">
                    	<label class="radio_label" for="modulomostrarno">No se muestra</label>
                    	<input name="modulomostrar" id="modulomostrarno" type="radio" class="radio" value="N" />
                    </div>
                </div>
                
                <div class="clearboth brisa_vertical"></div>
                <div class="ancho_1">&nbsp;</div>
                <div class="ancho_9">
                    <div class="ancho_1" style=" vertical-align:middle">
                        <label>Tiene acciones:</label>
                    </div>     
                    <div class="ancho_9">
                        <div class="ancho_1">
                            <label class="radio_label" for="moduloacciones_si">Si</label>
                            <input name="moduloacciones" id="moduloacciones_si" type="radio" class="radio" value="1" />
                        </div>
                        <div class="ancho_1">
                            <label class="radio_label" for="moduloacciones_no">No</label>
                            <input name="moduloacciones" id="moduloacciones_no" type="radio" class="radio" value="0" />
                        </div>
                    </div>
                </div>
               <div class="clearboth brisa_vertical"></div>
                 <div class="clearboth aire">&nbsp;</div>
                 <div class="ancho_1">&nbsp;</div>
                 <div class="ancho_9">
                    <div class="ancho_boton aire"><input type="submit" class="boton verde" name="botonalta" value="Agregar" onclick="return Validarjs(formulario)" /></div>
                    <div class="ancho_boton aire"><input name="Cancelar" class="boton base" type="button"  id="Cancelar" value="Limpiar" onclick="formulario.modulocodviejo.selectedIndex=0;SeleccionCombo(formulario,campos_linea,datos_grilla,0)" /></div>
                    <div class="ancho_boton aire"><input type="submit" class="boton rojo" name="botonbaja" value="Borrar" disabled="disabled"  onclick="return confirm('¿Está seguro de que desea borrar el modulo?');" /></div>
                    <div class="clearfix aire">&nbsp;</div>
               </div>
               <div class="clearboth brisa_vertical"></div>
                                    
            </div>
        </form>
</div>        
<div class="clearboth">&nbsp;</div>


<?php 
$oEncabezados->PieMenuEmergente();
?>
