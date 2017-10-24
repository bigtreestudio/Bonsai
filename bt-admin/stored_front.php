<?php 

require('./config/include.php');

$conexion = new accesoBDLocal(SERVIDORBD,USUARIOBD,CLAVEBD);
$conexion->SeleccionBD(BASEDATOS);

// carga las constantes generales
FuncionesPHPLocal::CargarConstantes($conexion,array("roles"=>"si","sistema"=>SISTEMA));
$conexion->SetearAdmiGeneral(ADMISITE);

// arma las variables de sesion y verifica si se tiene permisos
$sesion = new Sesion($conexion,false); // Inicia session y no borra
//$sesion->TienePermisos($conexion,$_SESSION['usuariocod'],$_SESSION['rolcod'],$_SERVER['PHP_SELF']);

// ve si el sistema está bloqueado
$oSistemaBloqueo = new SistemaBloqueo();
$oSistemaBloqueo->VerificarBloqueo($conexion);

$oEncabezados = new cEncabezados($conexion);
$oEncabezados->EncabezadoMenuEmergente($_SESSION['rolcod'],$_SESSION['usuariocod']);

$oStoredFront=new cStoredFront($conexion);


	
//----------------------------------------------------------------------------------------- 	


//----------------------------------------------------------------------------------------- 	
// Inicio de pantalla
$result=true;
$codigo=0;
if (isset ($_GET['spcod']))
{
	$ArregloDatos['spcod']=$_GET['spcod'];
	if (!$oStoredFront->Buscar ($ArregloDatos,$numfilas,$resultado))
		$result=false;

	if ($result)
	{
		if ($numfilas==1)
			$codigo=$_GET['spcod'];
	}
}


$ArregloDatos=array();
if (!$oStoredFront->Buscar ($ArregloDatos,$numfilas,$resultado))
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
var campos_linea=new Array("txt|spnombre","txt|spoperacion","txt|sptabla","txt|spsqlstring","txt|spobserv","hid|spcod");
var datos_grilla=new Array();
// ciclo recorriendo los mismos datos que la grilla
datos_grilla[0]=new Array();
datos_grilla[0][0]="";
datos_grilla[0][1]="";
datos_grilla[0][2]="";
datos_grilla[0][3]="";
datos_grilla[0][4]="";
datos_grilla[0][5]="";

<?
	
$i = 1;
while($filastored=$conexion->ObtenerSiguienteRegistro($resultado))
{
	echo "datos_grilla[".$i."]=new Array();\n";
	echo "datos_grilla[".$i."][0]='".FuncionesPHPLocal::ReemplazarComillas($filastored['spnombre'])."';\n";
	echo "datos_grilla[".$i."][1]='".FuncionesPHPLocal::ReemplazarComillas($filastored['spoperacion'])."';\n";
	echo "datos_grilla[".$i."][2]='".FuncionesPHPLocal::ReemplazarComillas($filastored['sptabla'])."';\n";
	echo "datos_grilla[".$i."][3]='".FuncionesPHPLocal::ReemplazarComillas($filastored['spsqlstring'])."';\n";
	echo "datos_grilla[".$i."][4]='".FuncionesPHPLocal::ReemplazarComillas($filastored['spobserv'])."';\n";
	echo "datos_grilla[".$i."][5]='".FuncionesPHPLocal::ReemplazarComillas($filastored['spcod'])."';\n";
	$i++;
}
?>
</script>

<script type="text/javascript">
<!--
function ValidarjsBotonBuscar(formulario) 
{
	if (formulario.spnombre.value=="")
	{
		alert("Debe ingresar el nombre del Stored Procedure Front");
		formulario.spnombre.focus();
		return false;
	}	
	return true;
}

function Validarjs(formulario) 
{
	if (formulario.spnombre.value=="")
	{
		alert("Debe ingresar el nombre del Stored Procedure Front");
		formulario.spnombre.focus();
		return false;
	}else
	{
		var i=1;
		while (i<datos_grilla.length)
		{
			if (formulario.spcodviejo.value!=datos_grilla[i][5])
			{	
				if (datos_grilla[i][0]==formulario.spnombre.value)
				{
					alert("Existe otro Stored Procedure Front con el mismo nombre");
					formulario.spnombre.focus();
					return false;
				}
			}	
			i++;
		}
	
	}
	if (formulario.sptabla.value=="")
	{
		alert("Debe ingresar el nombre de la Tabla");
		formulario.sptabla.focus();
		return false;
	}
	if (formulario.spoperacion.value=="")
	{
		alert("Debe ingresar la Operación");
		formulario.spoperacion.focus();
		return false;
	}
	if (formulario.spsqlstring.value=="")
	{
		alert("Debe ingresar el SQL");
		formulario.spsqlstring.focus();
		return false;
	}
	return true;
}
//-->
</script>
<div class="inner-page-title" style="padding-bottom:2px;">
    <h2>Stored Procedures Front</h2>
</div>
<div class="form">
        <form action="stored_front_upd.php" class="general_form" method="post" name="formulario" >
			<div class="ancho_10 aire">
                <div class="ancho_1">&nbsp;</div>
                <div class="ancho_9">
                    <div class="ancho_1">
                        <label>Stored:</label>
                    </div>        
                    <div class="ancho_4">
						<?	
							FuncionesPHPLocal::ArmarCombo_SinSalto_BD($conexion,"sel_stored_front_orden",array('porderby'=>"spcod"),"formulario","spcodviejo","spcod","sptodo",$codigo,"Nuevo Stored",$regnousar,$selecnousar,1,"SeleccionCombo(formulario,campos_linea,datos_grilla,this.selectedIndex)","","",false,"","","chzn-select-modulo");
                        ?>
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
                        <input name="spnombre" type="text" class="text" size="50" maxlength="255" value="" id="spnombre" />
                        <input name="spcod" type="hidden" value="" />
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
                        <input name="sptabla" type="text" class="text" size="50" maxlength="255" value="" id="sptabla" />
                    </div>
                    <div class="ancho_5">&nbsp;</div>
                </div>
                <div class="clearboth brisa_vertical"></div>

                <div class="ancho_1">&nbsp;</div>
                <div class="ancho_9">
                    <div class="ancho_1">
                        <label>Operaci&oacute;n:</label>
                    </div>        
                    <div class="ancho_4">
                        <input name="spoperacion" type="text" class="text" size="3" maxlength="3" value="" id="spoperacion" />
                    </div>
                    <div class="ancho_5">&nbsp;</div>
                </div>
                <div class="clearboth brisa_vertical"></div>


                <div class="ancho_1">&nbsp;</div>
                <div class="ancho_9">
                    <div class="ancho_1">
                        <a href="javascript:void(0)" onclick="popup('stored_frontpopup.php?Destino=spsqlstring','ventana_descripciones',430,360,screen.availWidth-450,screen.availHeight-420,'yes');"> 
                            <b>Generar C&oacute;digo SQL</b>
                        </a>
                        <br /><br />
                        <a href="javascript:void(0)" onclick="popup('stored_frontphp.php?Destino=spsqlstring','ventana_descripciones',430,360,screen.availWidth-450,screen.availHeight-420,'yes');"> 
                            <b>Generar C&oacute;digo PHP</b>
                        </a>
                   </div>        
                    <div class="ancho_4">
                        <textarea id="spsqlstring" name="spsqlstring"  rows="6" class="full"></textarea>
                    </div>
                    <div class="ancho_5">&nbsp;</div>
                </div>
                <div class="clearboth brisa_vertical"></div>


                <div class="ancho_1">&nbsp;</div>
                <div class="ancho_9">
                    <div class="ancho_1">
                        Observaciones
                    </div>        
                    <div class="ancho_4">
                        <textarea name="spobserv" id="spobserv" rows="6" cols="50" class="full"></textarea>
                    </div>
                    <div class="ancho_5">&nbsp;</div>
                </div>
                <div class="clearboth brisa_vertical"></div>

                 <div class="clearboth aire">&nbsp;</div>
                 <div class="ancho_1">&nbsp;</div>
                 <div class="ancho_9">
                     <div class="ancho_boton aire"><input type="submit" class="boton verde" name="botonalta" value="Agregar" onclick="return Validarjs(formulario)" /></div>
                     <div class="ancho_boton aire"><input name="Limpiar" type="button" class="boton base" id="Cancelar" value="Limpiar" onclick="formulario.spcodviejo.selectedIndex=0;SeleccionCombo(formulario,campos_linea,datos_grilla,0)" /></div>
                     <div class="ancho_boton aire"><input type="submit" class="boton rojo" name="botonbaja" value="Borrar"  disabled="disabled" onclick="return confirm('¿Está seguro de que desea borrar el Stored Procedure?');" /></div>
                     <div class="ancho_boton aire"><input type="submit" name="botonbuscar" class="boton azul" value="Buscar" onclick="return ValidarjsBotonBuscar(formulario)" /></div>
                    <div class="clearfix aire">&nbsp;</div>
               </div>
               <div class="clearboth brisa_vertical"></div>

                                                   
            </div>
    	</form>
</div>        
<div style="height:50px; clear:both">&nbsp;</div>
<?
$oEncabezados->PieMenuEmergente();
?>
