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
function Validarphp($conexion,&$datosvalidados)
{
	$datosvalidados=array();
	
	if($_POST['rolcod']=="")
	{
		FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRSOSP,"Error en la selección del rol. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
		return false;
	}

	if(!$conexion->BuscarRegistroxClave("sel_roles_orden",array("porderby"=>"rolcod"),array("rolcod"=>$_POST['rolcod']),$resultado,$filaret,$numfilasmatcheo,$errno))
	{
		FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRSOSP,"Error al seleccionar el rol. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
		return false;
	}
	
	if($numfilasmatcheo!=1)
	{
		FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRSOSP,"Error al seleccionar el rol. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
		return false;
	}
	$datosvalidados["rolcod"]=$_POST['rolcod'];

	if(isset($_POST['listamodulosder']))
		$arrayfinal=$_POST['listamodulosder'];
	else
		$arrayfinal=array();

	$arrayinicial=array();
	if(!$conexion->ejecutarStoredProcedure("sel_modulos_xrolcod_orden",array('prolcod'=>$datosvalidados["rolcod"],'porderby'=>"modulocod"),$resultado,$numfilas,$errno))
	{
		FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Error al seleccionar los módulos. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
		return false;
	}
	while($filamodulos=$conexion->ObtenerSiguienteRegistro($resultado))
		$arrayinicial[]=$filamodulos['modulocod'];

	$arraysacar=array_diff($arrayinicial,$arrayfinal);
	$arrayponer=array_diff($arrayfinal,$arrayinicial);

	$datosvalidados["modulossacar"]=$arraysacar;
	$datosvalidados["modulosponer"]=$arrayponer;
	
	return true;
}

//----------------------------------------------------------------------------------------- 	

	
//----------------------------------------------------------------------------------------- 	
// Inicio de pantalla

$spnombre="sel_roles_orden";
$spparam=array("porderby"=>"rolcod");
if(!$conexion->ejecutarStoredProcedure($spnombre,$spparam,$resultado,$numfilas,$errno))
{
	FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Error seleccionando roles. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
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
	
	if(!$conexion->ejecutarStoredProcedure("sel_modulos_xrolcod_orden",array('prolcod'=>$filagrupo['rolcod'],'porderby'=>"modulocod"),$resulmodulo,$numfilas,$errno)) die();

	$codigosel=array();
	while($filamodulo=$conexion->ObtenerSiguienteRegistro($resulmodulo))
		$codigosel[]=$filamodulo["modulocod"];
	
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
		if (formulario.rolcod.value=="")
		{
			alert("Debe seleccionar un rol");
			formulario.rolcod.focus();
			return false;
		}

		return true;
	}
//-->
</script>

<div class="inner-page-title" style="padding-bottom:2px;">
    <h2>Roles - M&oacute;dulos</h2>
</div>
<div class="form">
        <form action="roles_modulos_upd.php" class="general_form" method="post" name="formulario">
			<div class="ancho_1">&nbsp;</div>
            <div class="ancho_9">
                    <div class="ancho_2" style=" margin:5px 15px 0 0;">
                        <label>Roles</label>
                    </div>
                    <div class="ancho_3">
                        <?php 	
                            FuncionesPHPLocal::ArmarCombo_SinSalto_BD($conexion,"sel_roles_orden",array("porderby"=>"rolcod"),"formulario","rolcod","rolcod","rolnom","","Seleccione un Rol",$regnousar,$selecnousar,1,"SeleccionCombo(formulario,campos_linea,datos_grilla,this.selectedIndex)","","",false,"","","chzn-select-modulo");
                        ?>
                    </div>
                    <div class="clearboth">&nbsp;</div>
                    <div class="ancho_10">
                        <?php 
                            FuncionesPHPLocal::ArmarListas($conexion,"sel_modulos_orden",array('porderby'=>"modulocod"),"modulocod","modulotodo","formulario","listamodulos","Módulos Disponibles","Módulos Seleccionados","width: 100%",15,false)
                        ?>
                    </div>
                   <div class="clearboth aire">&nbsp;</div>
                   <div class="ancho_2">&nbsp;</div>
                   <div class="ancho_9">
                        <div class="ancho_boton aire"><input type="submit" class="boton verde" name="botonalta"  value="Actualizar" onclick="SeleccionarTodosLista(formulario['listamodulosder[]']);return Validarjs(formulario)" /></div>
                        <div class="ancho_boton aire"><input type="button" class="boton base" name="botoncancelar" value="Cancelar" onclick="formulario.rolcod.selectedIndex=0;SeleccionCombo(formulario,campos_linea,datos_grilla,0)" /></div>
                   </div>
                   <div class="clearboth brisa_vertical"></div>
            </div>
        </form>
</div>        
<div style="height:50px; clear:both">&nbsp;</div>

<?php 
$oEncabezados->PieMenuEmergente();
?>