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

$oLogMensajes=new cLogMensajes($conexion);


	
//----------------------------------------------------------------------------------------- 	


//----------------------------------------------------------------------------------------- 	
// Inicio de pantalla
$result=true;



$ArregloDatos=array();
if (!$oLogMensajes->Buscar ($ArregloDatos,$numfilas,$resultado))
	$result=false;



?>
<script language="javascript" type="text/javascript">
var campos_linea=new Array("txt|codigo_mensaje","txt|descripcion","txt|nivel");
var datos_grilla=new Array();
// ciclo recorriendo los mismos datos que la grilla
datos_grilla[0]=new Array();
datos_grilla[0][0]="";
datos_grilla[0][1]="";
datos_grilla[0][2]="";
datos_grilla[0][3]="";
datos_grilla[0][4]="";
datos_grilla[0][5]="";

<?php 
	
$i = 1;
while($filastored=$conexion->ObtenerSiguienteRegistro($resultado))
{
	echo "datos_grilla[".$i."]=new Array();\n";
	echo "datos_grilla[".$i."][0]='".FuncionesPHPLocal::ReemplazarComillas($filastored['codigo_mensaje'])."';\n";
	echo "datos_grilla[".$i."][1]='".FuncionesPHPLocal::ReemplazarComillas($filastored['descripcion'])."';\n";
	echo "datos_grilla[".$i."][2]='".FuncionesPHPLocal::ReemplazarComillas($filastored['nivel'])."';\n";
	$i++;
}
?>
</script>

<script type="text/javascript">
<!--
function ValidarjsBotonBuscar(formulario) 
{
	if (formulario.codigo_mensaje.value=="")
	{
		alert("Debe ingresar el Código de Mensaje");
		formulario.codigo_mensaje.focus();
		return false;
	}	
	return true;
}
function Validarjs(formulario) 
{
	if (formulario.codigo_mensaje.value=="")
	{
		alert("Debe ingresar el Código de Mensaje");
		formulario.codigo_mensaje.focus();
		return false;
	}	
	if (formulario.descripcion.value=="")
	{
		alert("Debe ingresar la Descripción");
		formulario.descripcion.focus();
		return false;
	}
	if (formulario.nivel.value=="")
	{
		alert("Debe ingresar el Nivel");
		formulario.nivel.focus();
		return false;
	}
	return true;
}
//-->
</script>
<div class="inner-page-title" style="padding-bottom:2px;">
    <h2>Log Mensajes</h2>
</div>
<div class="form">
        
        <form action="logmensajes_upd.php" class="general_form" method="post" name="formulario" >
			<div class="ancho_1">&nbsp;</div>
            <div class="ancho_9">
                <div class="ancho_1">&nbsp;</div>
                <div class="ancho_9">
                    <div class="ancho_1">
                        <label>Log Mensaje</label>
                    </div>        
                    <div class="ancho_3">
						<?php 	
							FuncionesPHPLocal::ArmarCombo_SinSalto_BD($conexion,"sel_log_mensajes_orden",array('porderby'=>"codigo_mensaje"),"formulario","codigo","codigo_mensaje","codigotodo","","Nuevo Log Mensaje",$regnousar,$selecnousar,1,"SeleccionCombo(formulario,campos_linea,datos_grilla,this.selectedIndex)","","",false);
                        ?>
                    </div>
                </div>
                <div class="clearboth brisa_vertical"></div>

                <div class="ancho_1">&nbsp;</div>
                <div class="ancho_9">
                    <div class="ancho_1">
                        <label>C&oacute;digo de Mensaje</label>
                    </div>        
                    <div class="ancho_4">
                        <input name="codigo_mensaje" type="text" class="text" size="6" maxlength="6" value="" id="codigo_mensaje" />
                    </div>
                    <div class="ancho_5">&nbsp;</div>
                </div>
                <div class="clearboth brisa_vertical"></div>

                <div class="ancho_1">&nbsp;</div>
                <div class="ancho_9">
                    <div class="ancho_1">
                        <label>Descripci&oacute;n</label>
                    </div>        
                    <div class="ancho_4">
                        <input name="descripcion" type="text" class="text" size="50" maxlength="255" value="" id="descripcion" />
                    </div>
                    <div class="ancho_5">&nbsp;</div>
                </div>
                <div class="clearboth brisa_vertical"></div>

                <div class="ancho_1">&nbsp;</div>
                <div class="ancho_9">
                    <div class="ancho_1">
                        <label>Nivel</label>
                    </div>        
                    <div class="ancho_4">
                        <input name="nivel" type="text" class="text" size="4" maxlength="4" value="" id="nivel" />
                    </div>
                    <div class="ancho_5">&nbsp;</div>
                </div>
                <div class="clearboth brisa_vertical"></div>

                <div class="clearboth aire">&nbsp;</div>
                <div class="ancho_2">&nbsp;</div>
                <div class="ancho_8">
                     <div class="ancho_boton aire"><input type="submit" name="botonalta" class="boton verde" value="Agregar" onclick="return Validarjs(formulario)" /></div>
                     <div class="ancho_boton aire"><input name="Limpiar" type="button" class="boton base" id="Cancelar" value="Limpiar" onclick="formulario.codigo.selectedIndex=0;SeleccionCombo(formulario,campos_linea,datos_grilla,0)" /></div>
                     <div class="ancho_boton aire"><input type="submit" class="boton rojo" name="botonbaja " value="Borrar"  disabled="disabled" onclick="return confirm('¿Está seguro de que desea borrar el Stored Procedure?');" /></div>
                     <div class="ancho_boton aire"><input type="submit" name="botonbuscar" class="boton azul" value="Buscar" onclick="return ValidarjsBotonBuscar(formulario)" /></div>
                    <div class="clearfix aire">&nbsp;</div>
               </div>
               <div class="clearboth brisa_vertical"></div>
			</div>
        </form>
</div>        
<div style="height:50px; clear:both">&nbsp;</div>

<?php 
$oEncabezados->PieMenuEmergente();
?>
