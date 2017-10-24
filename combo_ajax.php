<?php  

require('./config/include.php');
include(DIR_CLASES."cDepartamentos.class.php");

$conexion = new accesoBDLocal(SERVIDORBD,USUARIOBD,CLAVEBD);
$conexion->SeleccionBD(BASEDATOS);

// arma las variables de sesion y verifica si se tiene permisos

$oEncabezados = new cEncabezados($conexion);
header('Content-Type: text/html; charset=iso-8859-1'); 

switch($_POST['tipo']){
	case 1:
		//muestro select de productos
		$oProductos= new cProductos($conexion);
		$datos["catcod"] = $_POST['catcod'];
		$datos["xproestado"] = 1;
		$datos['proestado']=ACTIVO;
		if ($oProductos->BusquedaAvanzada ($datos,$resultado,$numfilas))
		{		
			echo '<select name="procod" id="procod">';
			while ($filaProd=$conexion->ObtenerSiguienteRegistro($resultado))
			{
				echo '<option value="'.$filaProd["procod"].'">';
                echo  FuncionesPHPLocal::HtmlspecialcharsBigtree($filaProd["prodesc"], ENT_QUOTES);
                echo '</option>';
			}
			echo '</select>';
		}
		break;
	case 2:
		$provinciacod = $_POST['provinciacod'];
		$oDepartamentos = new cDepartamentos($conexion,$provinciacod,"");
		$oDepartamentos->DepartamentoSP($spnombre,$sparam);
		$campo = "departamentocod";
		$descripcion = "departamentodesc";
		$seleccione = "Todas las Ciudades";
		$onclick = "";
		if ($provinciacod!="")
		{
			FuncionesPHPLocal::ArmarCombo_SinSalto_BD($conexion,$spnombre,$sparam,"formulario",$campo,$campo,$descripcion,"",$seleccione,$regnousar,$selecnousar,1,$onclick,"width: 200px",false,false);
		}
		break;
	case 3:
		//cargo deptos para entrega
		$provinciacod = $_POST['provinciacod'];
		$oDepartamentos = new cDepartamentos($conexion,$provinciacod,"");
		$oDepartamentos->DepartamentoSP($spnombre,$sparam);
		$campo = "departamentocod";
		$nombrecombo = "ventaentregadepartamentocod";
		$descripcion = "departamentodesc";
		$seleccione = "Todas las Ciudades";
		$onclick = "";
		if ($provinciacod!="")
		{
			FuncionesPHPLocal::ArmarCombo_SinSalto_BD($conexion,$spnombre,$sparam,"formulario",$nombrecombo,$campo,$descripcion,"",$seleccione,$regnousar,$selecnousar,1,$onclick,"width: 200px",false,false);
		}
		break;
		
}
	
	
?>
<?php  ?>