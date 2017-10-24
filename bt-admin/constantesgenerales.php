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

$oConstantesGrales=new cConstantesGrales($conexion);


	
//----------------------------------------------------------------------------------------- 	


//----------------------------------------------------------------------------------------- 	
// Inicio de pantalla
$result=true;



$ArregloDatos=array();
if (!$oConstantesGrales->Buscar ($ArregloDatos,$numfilas,$resultado))
	$result=false;


?>
<link href="css/chosen.css" rel="stylesheet" title="style" media="all" />

<script type="text/javascript" src="js/chosen.jquery.min.js"></script>
<script language="javascript" type="text/javascript">
jQuery(document).ready(function(){
	$(".chzn-select-modulo").chosen();

});
</script>
<script language="javascript" type="text/javascript">
var campos_linea=new Array("txt|sistemanom","txt|constantetipo","txt|constantecod","txt|constantenom","txt|constantedesc");
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
	echo "datos_grilla[".$i."][0]='".FuncionesPHPLocal::ReemplazarComillas($filastored['sistemanom'])."';\n";
	echo "datos_grilla[".$i."][1]='".FuncionesPHPLocal::ReemplazarComillas($filastored['constantetipo'])."';\n";
	echo "datos_grilla[".$i."][2]='".FuncionesPHPLocal::ReemplazarComillas($filastored['constantecod'])."';\n";
	echo "datos_grilla[".$i."][3]='".FuncionesPHPLocal::ReemplazarComillas($filastored['constantenom'])."';\n";
	echo "datos_grilla[".$i."][4]='".FuncionesPHPLocal::ReemplazarComillas($filastored['constantedesc'])."';\n";
	$i++;
}
?>
</script>

<script type="text/javascript">
<!--
function ValidarjsBotonBuscar(formulario) 
{
	if (formulario.constantecod.value=="")
	{
		alert("Debe ingresar el Código de Constante");
		formulario.constantecod.focus();
		return false;
	}
	if (formulario.constantenom.value=="")
	{
		alert("Debe ingresar el Nombre de la Constante");
		formulario.constantenom.focus();
		return false;
	}
	return true;
}
function Validarjs(formulario) 
{
	if (formulario.sistemanom.value=="")
	{
		alert("Debe ingresar el Nombre del Sistema");
		formulario.sistemanom.focus();
		return false;
	}	
	if (formulario.constantetipo.value=="")
	{
		alert("Debe ingresar el Tipo de Constante");
		formulario.constantetipo.focus();
		return false;
	}
	if (formulario.constantecod.value=="")
	{
		alert("Debe ingresar el Código de Constante");
		formulario.constantecod.focus();
		return false;
	}
	if (formulario.constantenom.value=="")
	{
		alert("Debe ingresar el Nombre de la Constante");
		formulario.constantenom.focus();
		return false;
	}
	if (formulario.constantedesc.value=="")
	{
		alert("Debe ingresar una Descripción de la Constante");
		formulario.constantedesc.focus();
		return false;
	}

	return true;
}
//-->
</script>
<div class="inner-page-title" style="padding-bottom:2px;">
    <h2>Constantes Generales</h2>
</div>
<div class="form">
        <form action="constantesgenerales_upd.php" class="general_form" method="post" name="formulario" >
                <div class="ancho_1">&nbsp;</div>
                <div class="ancho_9">
                    <div class="ancho_1">
                        <label>Constante:</label>
                    </div>        
                    <div class="ancho_4">
						<?php 	
							FuncionesPHPLocal::ArmarCombo_SinSalto_BD($conexion,"sel_constantes_grales_orden",array('porderby'=>"constantecod"),"formulario","codigo","codigo","todo","","Nueva Constante General",$regnousar,$selecnousar,1,"SeleccionCombo(formulario,campos_linea,datos_grilla,this.selectedIndex)","","",false,"","","chzn-select-modulo");
                        ?>
                    </div>
	                <div class="ancho_5">&nbsp;</div>
                </div>
                <div class="clearboth brisa_vertical"></div>

                <div class="ancho_1">&nbsp;</div>
                <div class="ancho_9">
                    <div class="ancho_1">
                        <label>Nombre del Sistema:</label>
                    </div>        
                    <div class="ancho_4">
                        <input name="sistemanom" type="text" class="text" size="20" maxlength="20" value="<?php  echo SISTEMA?>" id="sistemanom" />
                    </div>
	                <div class="ancho_5">&nbsp;</div>
                </div>
                <div class="clearboth brisa_vertical"></div>

                <div class="ancho_1">&nbsp;</div>
                <div class="ancho_9">
                    <div class="ancho_1">
                        <label>Tipo:</label>
                    </div>        
                    <div class="ancho_4">
                        <input name="constantetipo" type="text" class="text" size="50" maxlength="50" value="" id="constantetipo" />
                    </div>
	                <div class="ancho_5">&nbsp;</div>
                </div>
                <div class="clearboth brisa_vertical"></div>


                <div class="ancho_1">&nbsp;</div>
                <div class="ancho_9">
                    <div class="ancho_1">
                        <label>C&oacute;digo:</label>
                    </div>        
                    <div class="ancho_4">
                        <input name="constantecod" type="text" class="text" size="50" maxlength="150" value="" id="constantecod" />
                    </div>
	                <div class="ancho_5">&nbsp;</div>
                </div>
                <div class="clearboth brisa_vertical"></div>

                <div class="ancho_1">&nbsp;</div>
                <div class="ancho_9">
                    <div class="ancho_1">
                        <label>Nombre:</label>
                    </div>        
                    <div class="ancho_4">
                        <input name="constantenom" type="text" class="text" size="50" maxlength="100" value="" id="constantenom" />
                    </div>
	                <div class="ancho_5">&nbsp;</div>
                </div>
                <div class="clearboth brisa_vertical"></div>

                <div class="ancho_1">&nbsp;</div>
                <div class="ancho_9">
                    <div class="ancho_1">
                        <label>Descripci&oacute;n:</label>
                    </div>        
                    <div class="ancho_4">
                        <input name="constantedesc" type="text" class="text" size="50" maxlength="50" value="" id="constantedesc" />
                    </div>
	                <div class="ancho_5">&nbsp;</div>
                </div>
                <div class="clearboth brisa_vertical"></div>

                
                <div class="clearboth aire">&nbsp;</div>
                <div class="ancho_1">&nbsp;</div>
                <div class="ancho_9">
                    <div class="ancho_boton aire"><input type="submit" name="botonalta" class="boton verde" value="Agregar" onclick="return Validarjs(formulario)" /></div>
                    <div class="ancho_boton aire"><input name="Limpiar" type="button" class="boton base" id="Cancelar" value="Limpiar" onclick="formulario.codigo.selectedIndex=0;SeleccionCombo(formulario,campos_linea,datos_grilla,0)" /></div>
                    <div class="ancho_boton aire"><input type="submit" class="boton rojo" name="botonbaja" value="Borrar"  disabled="disabled" onclick="return confirm('¿Está seguro de que desea borrar el Stored Procedure?');" /></div>
                    <div class="ancho_boton aire"><input type="submit" name="botonbuscar " class="boton azul" value="Buscar" onclick="return ValidarjsBotonBuscar(formulario)" /></div>
                    <div class="clearfix aire">&nbsp;</div>
               </div>
               <div class="clearboth brisa_vertical"></div>
        </form>
</div>        
<div style="height:50px; clear:both">&nbsp;</div>

<?php 
$oEncabezados->PieMenuEmergente();
?>
