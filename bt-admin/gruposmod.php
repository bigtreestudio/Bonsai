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

$oGruposModulos= new cGruposModulos($conexion);

//----------------------------------------------------------------------------------------- 

//----------------------------------------------------------------------------------------- 	
// Inicio de pantalla

$ArregloDatos['orderby']="grupomodcod";
if (!$oGruposModulos->Buscar ($ArregloDatos,$numfilas,$resultado))
	die();


?>
<link href="css/chosen.css" rel="stylesheet" title="style" media="all" />

<script type="text/javascript" src="js/chosen.jquery.min.js"></script>
<script language="javascript" type="text/javascript">
jQuery(document).ready(function(){
	$(".chzn-select-modulo").chosen();

});
</script>	
<script language="javascript" type="text/javascript">
var campos_linea=new Array("txt|grupomodtextomenu","txt|grupomodsec");
var datos_grilla=new Array();
// ciclo recorriendo los mismos datos que la grilla
datos_grilla[0]=new Array();
datos_grilla[0][0]="";
datos_grilla[0][1]="";
<?php 
	
$i = 1;
while($filagrupomod=$conexion->ObtenerSiguienteRegistro($resultado))
{
	echo "datos_grilla[".$i."]=new Array();\n";
	echo "datos_grilla[".$i."][0]='".FuncionesPHPLocal::ReemplazarComillas($filagrupomod['grupomodtextomenu'])."';\n";
	echo "datos_grilla[".$i."][1]='".FuncionesPHPLocal::ReemplazarComillas($filagrupomod['grupomodsec'])."';\n";
	$i++;
}
?>
</script>

<script type="text/javascript">
<!--
function Validarjs(formulario) 
	{
		if(formulario.grupomodtextomenu.value=="")
		{
			alert("Debe ingresar el texto en el menú");
			formulario.grupomodtextomenu.focus();
			return false;
		}
		if(formulario.grupomodsec.value=="")
		{
			alert("Debe ingresar la secuencia del grupo");
			formulario.grupomodsec.focus();
			return false;
		}
		if(!ValidarContenido(formulario.grupomodsec.value,"NumericoEntero"))
		{
			alert("La secuencia ingresada no es válida");
			formulario.grupomodsec.focus();
			return false;
		}
		return true;
	}
//-->
</script>



<div class="inner-page-title" style="padding-bottom:2px;">
    <h2>Grupos M&oacute;dulos</h2>
</div>
<div class="form">
            <form action="gruposmod_upd.php" method="post" class="general_form" name="formulario">
			<div class="ancho_10 aire">
                <div class="ancho_1">&nbsp;</div>
                <div class="ancho_9">
                    <div class="ancho_1">
                        <label>Grupo m&oacute;dulo:</label>
                    </div>        
                    <div class="ancho_4">
						<?php 	FuncionesPHPLocal::ArmarCombo_SinSalto_BD($conexion,"sel_gruposmod_orden",array('porderby'=>"grupomodcod"),"formulario","grupomodcod","grupomodcod","grupomodtodo","","Nuevo Grupo de Módulos",$regnousar,$selecnousar,1,"SeleccionCombo(formulario,campos_linea,datos_grilla,this.selectedIndex)","","",false,"","","chzn-select-modulo"); ?>
                    </div>
	                <div class="ancho_5">&nbsp;</div>
                </div>
                <div class="clearboth brisa_vertical"></div>


                <div class="ancho_1">&nbsp;</div>
                <div class="ancho_9">
                    <div class="ancho_1">
                        <label>Texto en el men&uacute;:</label>
                    </div>        
                    <div class="ancho_4">
                        <input name="grupomodtextomenu" id="grupomodtextomenu" class="text" type="text" size="27" maxlength="25" value="" />
                    </div>
	                <div class="ancho_5">&nbsp;</div>
                </div>
                <div class="clearboth brisa_vertical"></div>

                <div class="ancho_1">&nbsp;</div>
                <div class="ancho_9">
                    <div class="ancho_1">
                        <label>Secuencia:</label>
                    </div>        
                    <div class="ancho_4">
                        <input name="grupomodsec" id="grupomodsec" class="text" type="text"  size="05" maxlength="04" value="" />
                    </div>
	                <div class="ancho_5">&nbsp;</div>
                </div>
                <div class="clearboth aire">&nbsp;</div>
              
                <div class="ancho_1">&nbsp;</div>
                <div class="ancho_9">
                    <div class="ancho_boton aire"><input type="submit" class="boton verde" name="botonalta" value="Agregar" onclick="return Validarjs(formulario)" /></div>
                    <div class="ancho_boton aire"><input name="Cancelar" class="boton base" type="button" id="Cancelar" value="Limpiar" onclick="formulario.grupomodcod.selectedIndex=0;SeleccionCombo(formulario,campos_linea,datos_grilla,0)" /></div>
                    <div class="ancho_boton aire"><input type="submit" class="boton rojo" name="botonbaja" value="Borrar"  disabled="disabled" onclick="return confirm('¿Está seguro de que desea borrar el modulo?');" /></div>
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
