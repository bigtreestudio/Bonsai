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

//----------------------------------------------------------------------------------------- 	
// Inicio de pantalla

$spnombre="sel_modulos_orden";
$spparam=array("porderby"=>"modulotextomenu");
if(!$conexion->ejecutarStoredProcedure($spnombre,$spparam,$resultado,$numfilas,$errno))
{
	FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Error seleccionando modulos. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
	die();
}

?>
<link href="css/chosen.css" rel="stylesheet" title="style" media="all" />

<script type="text/javascript" src="js/chosen.jquery.min.js"></script>
<script language="javascript" type="text/javascript">
jQuery(document).ready(function(){
	$(".chzn-select-modulo").chosen();

});
</script>	
<script language="javascript" type="text/javascript">

var campos_linea=new Array("lst|listaarchivosizq[]|listaarchivosder[]");
var datos_grilla=new Array();

// ciclo recorriendo los mismos datos que la grilla
datos_grilla[0]=new Array();
datos_grilla[0][0]="";
<?php 

$i = 1;
while($filagrupo=$conexion->ObtenerSiguienteRegistro($resultado))
{
	echo "datos_grilla[".$i."]=new Array();\n";
	echo "datos_grilla[".$i."][0]=new Array(";
	
	if(!$conexion->ejecutarStoredProcedure("sel_archivos_xmodulocod_orden",array('pmodulocod'=>$filagrupo['modulocod'],'porderby'=>"archivonom"),$resularchivo,$numfilas,$errno)) die();

	$codigosel=array();
	while($filaarchivo=$conexion->ObtenerSiguienteRegistro($resularchivo))
		$codigosel[]=$filaarchivo["archivocod"];
	
	if(count($codigosel)>0)
		echo "'".implode("','",$codigosel)."'";
	
	echo ");\n";
	$i++;
}
?>
</script>

<script type="text/javascript">
<!--
function Validarjs(formulario) 
	{
		if (formulario.modulocod.value=="")
		{
			alert("Debe seleccionar un módulo");
			formulario.modulocod.focus();
			return false;
		}

		return true;
	}
//-->
</script>
<div class="inner-page-title" style="padding-bottom:2px;">
    <h2>M&oacute;dulos Archivos</h2>
</div>
<div class="form">
        <form action="modulos_archivos_upd.php" class="general_form" method="post" name="formulario">
			<div class="ancho_1">&nbsp;</div>
            <div class="ancho_9">
                <div class="ancho_1" style="margin-top:10px">
                    <label>M&oacute;dulo</label>
                </div>
                <div class="ancho_3">
                    <?php 	
                        FuncionesPHPLocal::ArmarCombo_SinSalto_BD($conexion,"sel_modulos_orden",array("porderby"=>"modulotextomenu"),"formulario","modulocod","modulocod","modulotodo","","Seleccione un Módulo",$regnousar,$selecnousar,1,"SeleccionCombo(formulario,campos_linea,datos_grilla,this.selectedIndex)","","",false,"","","chzn-select-modulo");
                    ?>
                </div>
                <div class="clearboth">&nbsp;</div>
                <div class="ancho_10">
                    <?php 
                        FuncionesPHPLocal::ArmarListas($conexion,"sel_archivos_orden",array('porderby'=>"archivonom"),"archivocod","archivonom","formulario","listaarchivos","Archivos Disponibles","Archivos Seleccionados","width:100%",15,false)
                    ?>
                </div>
               <div class="clearboth aire">&nbsp;</div>
               <div class="ancho_9">
                    <div class="ancho_boton aire"><input type="submit" class="boton verde" name="botonalta" value="Actualizar" onclick="SeleccionarTodosLista(formulario['listaarchivosder[]']);return Validarjs(formulario)" /></div>
                    <div class="ancho_boton aire"><input type="button" class="boton base" name="botoncancelar" value="Cancelar" onclick="formulario.modulocod.selectedIndex=0;SeleccionCombo(formulario,campos_linea,datos_grilla,0)" /></div>
               </div>
            </div>
            <div class="clearfix aire">&nbsp;</div>
        </form>
    </div>
</div>        
<div style="height:50px; clear:both">&nbsp;</div>

<?php 
$oEncabezados->PieMenuEmergente();
?>