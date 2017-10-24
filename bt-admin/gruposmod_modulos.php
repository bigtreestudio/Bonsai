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

$ArregloDatos['orderby']="grupomodtextomenu";
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

var campos_linea=new Array("lst|listamodulosizq[]|listamodulosder[]");
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
	
	if(!$conexion->ejecutarStoredProcedure("sel_modulos_xgrupomodcod_orden",array('pgrupomodcod'=>$filagrupo['grupomodcod'],'porderby'=>"modulocod"),$resulmodulo,$numfilas,$errno)) die();

	$codigosel=array();
	while($filasubtema=$conexion->ObtenerSiguienteRegistro($resulmodulo))
		$codigosel[]=$filasubtema["modulocod"];
	
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
		if (formulario.grupomodcod.value=="")
		{
			alert("Debe seleccionar un grupo de módulos");
			formulario.grupomodcod.focus();
			return false;
		}

		return true;
	}
//-->
</script>

<div class="inner-page-title" style="padding-bottom:2px;">
    <h2>Grupo de M&oacute;dulos - M&oacute;dulos</h2>
</div>
<div class="form">
        <form action="gruposmod_modulos_upd.php" class="general_form" method="post" name="formulario">
			<div class="ancho_1">&nbsp;</div>
            <div class="ancho_9">
                        <div class="ancho_2" style="margin-top:5px;">
                            <label>Grupo de M&oacute;dulos:</label>
                        </div>
                        <div class="ancho_4">
                            <?php 	
                                FuncionesPHPLocal::ArmarCombo_SinSalto_BD($conexion,"sel_gruposmod_orden",array("porderby"=>"grupomodtextomenu"),"formulario","grupomodcod","grupomodcod","grupomodtextomenu","","Seleccione un Grupo de Módulos",$regnousar,$selecnousar,1,"SeleccionCombo(formulario,campos_linea,datos_grilla,this.selectedIndex)","","",false,"","","chzn-select-modulo"); 
							?>
                        </div>
                        <div class="clearboth">&nbsp;</div>
                        <div class="ancho_10">
							<?php 
                                FuncionesPHPLocal::ArmarListas($conexion,"sel_modulos_orden",array('porderby'=>"modulocod"),"modulocod","modulotodo","formulario","listamodulos","Módulos Disponibles","Módulos Seleccionados","width:100%",15,false)
                            ?>
						</div>
                       <div class="clearboth aire">&nbsp;</div>
                       <div class="ancho_9">
                           <div class="ancho_boton aire"> <input type="submit" name="botonalta" class="boton verde" value="Aceptar" onclick="SeleccionarTodosLista(formulario['listamodulosder[]']);return Validarjs(formulario)" /></div>
                           <div class="ancho_boton aire"><input type="button" class="boton base" name="Limpiar" value="Limpiar" onclick="formulario.grupomodcod.selectedIndex=0;SeleccionCombo(formulario,campos_linea,datos_grilla,0)" /></div>
                       </div>
                       <div class="clearboth brisa_vertical"></div>
                </div>
                <div class="clearfix aire">&nbsp;</div>
        </form>
</div>        
<div style="height:50px; clear:both">&nbsp;</div>

<?php 
$oEncabezados->PieMenuEmergente();
?>
