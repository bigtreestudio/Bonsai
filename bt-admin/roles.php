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

$roles=new cRoles($conexion);

//----------------------------------------------------------------------------------------- 	
// Inicio de pantalla

$roles->RolesSP($spnombre,$spparam);
if(!$conexion->ejecutarStoredProcedure($spnombre,$spparam,$resultado,$numfilas,$errno))
{
	FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Error seleccionando roles. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
	die();
}

?>	
<script language="javascript" type="text/javascript">
var campos_linea=new Array("txt|rolcodnuevo","txt|rolnom","txt|roldesc");
var datos_grilla=new Array();
// ciclo recorriendo los mismos datos que la grilla
datos_grilla[0]=new Array();
datos_grilla[0][0]="";
datos_grilla[0][1]="";
datos_grilla[0][2]="";
datos_grilla[0][3]="";
<?php 
$i = 1;
while($filarol=$conexion->ObtenerSiguienteRegistro($resultado))
{
	echo "datos_grilla[".$i."]=new Array();\n";
	echo "datos_grilla[".$i."][0]='".FuncionesPHPLocal::ReemplazarComillas($filarol['rolcod'])."';\n";
	echo "datos_grilla[".$i."][1]='".FuncionesPHPLocal::ReemplazarComillas($filarol['rolnom'])."';\n";
	echo "datos_grilla[".$i."][2]='".FuncionesPHPLocal::ReemplazarComillas($filarol['roldesc'])."';\n";
	$i++;
}
?>
</script>

<script type="text/javascript">
<!--
function Validarjs(formulario) 
	{
		if (formulario.rolcodnuevo.value=="")
		{
			alert("Debe ingresar el código del rol");
			formulario.rolcodnuevo.focus();
			return false;
		}
		if (!ValidarContenido(formulario.rolcodnuevo.value,"NumericoEntero"))
		{
			alert("El código del rol debe ser numérico");
			formulario.rolcodnuevo.focus();
			return false;
		}
		if (formulario.rolnom.value=="")
		{
			alert("Debe ingresar el nombre del rol");
			formulario.rolnom.focus();
			return false;
		}
		if (formulario.roldesc.value=="")
		{
			alert("Debe ingresar la descripción del rol");
			formulario.roldesc.focus();
			return false;
		}
		
		return true;
	}
//-->
</script>


<div class="inner-page-title" style="padding-bottom:2px;">
    <h2>Roles</h2>
</div>
<div class="form">
          <form action="roles_upd.php" class="general_form" method="post" name="formulario">
			<div class="ancho_10 aire">
                <div class="ancho_1">&nbsp;</div>
                <div class="ancho_9">
                    <div class="ancho_1">
                        <label>Rol:</label>
                    </div>        
                    <div class="ancho_4">
						<?php 	
                        $roles->RolesSP($spnombre,$spparam);
                        FuncionesPHPLocal::ArmarCombo_SinSalto_BD($conexion,$spnombre,$spparam,"formulario","rolcodviejo","rolcod","roltodo","","Nuevo Rol",$regnousar,$selecnousar,1,"SeleccionCombo(formulario,campos_linea,datos_grilla,this.selectedIndex)","","",false);
                        ?>
                    </div>
                </div>
                <div class="clearboth brisa_vertical"></div>
            
                <div class="ancho_1">&nbsp;</div>
                <div class="ancho_9">
                    <div class="ancho_1">
                        <label>C&oacute;digo de Rol:</label>
                    </div>        
                    <div class="ancho_4">
                        <input name="rolcodnuevo" id="rolcodnuevo" type="text" class="text" size="3" maxlength="2" value="" />
                    </div>
                </div>
                <div class="clearboth brisa_vertical"></div>
            
                <div class="ancho_1">&nbsp;</div>
                <div class="ancho_9">
                    <div class="ancho_1">
                        <label>Nombre:</label>
                    </div>        
                    <div class="ancho_4">
                        <input name="rolnom" id="rolnom" type="text" class="text" size="11" maxlength="11" value="" />
                    </div>
                </div>
                <div class="clearboth brisa_vertical"></div>
            
                <div class="ancho_1">&nbsp;</div>
                <div class="ancho_9">
                    <div class="ancho_1">
                        <label>Descripci&oacute;n:</label>
                    </div>        
                    <div class="ancho_4">
                        <input name="roldesc" id="roldesc" type="text" class="text" size="50" maxlength="60" value="" />
                    </div>
                </div>
                <div class="clearboth brisa_vertical"></div>
            </div>
            <div class="clearboth brisa_vertical"></div>

             <div class="clearboth aire">&nbsp;</div>
             <div class="ancho_1">&nbsp;</div>
             <div class="ancho_9">
                 <div class="ancho_boton aire"><input type="submit" class="boton verde" name="botonalta" value="Modificar" onclick="return Validarjs(formulario)" /></div>
                 <div class="ancho_boton aire"> <input name="Cancelar" class="boton base" type="button"  id="Limpiar" value="Limpiar" onclick="formulario.rolcodviejo.selectedIndex=0;SeleccionCombo(formulario,campos_linea,datos_grilla,0)" /></div>
                 <div class="ancho_boton aire"><input type="submit" class="boton rojo" name="botonbaja" value="Borrar"  onclick="return confirm('¿Está seguro de que desea borrar el rol?');" /></div>
                <div class="clearfix aire">&nbsp;</div>
           </div>
		   <div class="clearboth brisa_vertical"></div>
         </form>
</div>        
<div style="height:50px; clear:both">&nbsp;</div>

<?php 
$oEncabezados->PieMenuEmergente();
?>
