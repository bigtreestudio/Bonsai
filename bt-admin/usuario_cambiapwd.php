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

$usuarios=new cUsuarios($conexion);
$ArregloDatos['usuariocod']=$_SESSION['usuariocod'];
if (!$usuarios->BuscarUsuarios ($ArregloDatos,$resultadousuarios,$numfilas) || $numfilas!=1)
	die();

$filausuario=$conexion->ObtenerSiguienteRegistro($resultadousuarios);
$usuarioemail=$filausuario["usuarioemail"];

$mensajeaccion = "";
if (isset($_SESSION['msgactualizacion']) && $_SESSION['msgactualizacion']!="")
	$mensajeaccion = '<p class="msg done">'.$_SESSION['msgactualizacion'].'</p>';
	


//----------------------------------------------------------------------------------------- 
?>
<script type="text/javascript">
<!--
function Validarjs(formulario_login) 
{
	if (formulario_login.clave2.value=="")
	{
		alert("Debe ingresar una contraseña");
		formulario_login.clave2.focus();
		return false;
	}
	if (!ValidarPassword(formulario_login.clave2.value,formulario_login.clave1.value,'<?php  echo $usuarioemail ?>',8))
	{
		alert("La contraseña no cumple los requerimientos");
		formulario_login.clave2.focus();
		return false;
	}
	if (formulario_login.clave2.value!=formulario_login.clave3.value)
	{
		alert("Las contraseña de confirmación no coincide");
		formulario_login.clave3.focus();
		return false;
	}		

	return true;
}
//-->
</script>
<div class="inner-page-title" style="padding-bottom:2px;">
    <h2>Cambiar Clave de Acceso</h2>
</div>
<div class="form">
    <div class="clearfix brisa">&nbsp;</div>
    <div class="ancho_10">
		<form method="post" action="<?php  echo $_SERVER['PHP_SELF'] ?>" name="formulario_login" id="formulario_login" class="general_form" >
			<div class="col-md-4 col-md-offset-1">
				<div class="form-group">
					<label for="clave1">Contrase&ntilde;a Actual</label>
					<input type="password"  class="form-control input-md" value="" autocomplete="off" id="clave1" name="clave1" />
				</div>
				<div class="form-group">
					<label for="clave2">Contrase&ntilde;a Nueva</label>
					<input type="password"  class="form-control input-md" value="" autocomplete="off" id="clave2" name="clave2" />
				</div>
				<div class="form-group">
					<label for="clave3">Confirmar Contrase&ntilde;a</label>
					<input type="password"  class="form-control input-md" value="" autocomplete="off" id="clave3" name="clave3" />
				</div>
				<div class="text-center">
					<div class="ancho_boton aire"> <input type="submit" class="boton verde" value="Modificar Contrase&ntilde;a" name="botonchgpwd" onClick="return Validarjs(formulario_login)" /></div>
                    <div class="ancho_boton aire"> <input type="reset" class="boton base" value="Limpiar" name="B2" /></div>
				</div>
			</div>
			<div class="col-md-5 col-md-offset-1">
				<div class="panel panel-primary">
					<div class="panel-heading">
						<h4 class="panel-title">
							La contrase&ntilde;a nueva debe cumplir los siguientes requerimientos
						</h4>
					</div>
					<div id="accordion1_1" class="panel-collapse collapse in" aria-expanded="true">
						<div class="panel-body"> 
							- Debe contener al menos una may&uacute;scula.<br>
							- Debe contener al menos un n&uacute;mero o caracter especial.<br>
							- Como m&iacute;nimo debe tener un largo de 8 car&aacute;cteres.<br>
							- No puede tener el mismo car&aacute;cter consecutivo mas de 4 veces.<br>
							- No puede ser parecida a la contrase&ntilde;a anterior.<br>
						</div>
					</div>
				</div>                        
			</div>
			<div class="clearboth"></div>
		</form>
		<div class="clearboth">&nbsp;</div>
        <?php  
        if (isset($_POST['botonchgpwd']))
        {
            $conexion->ManejoTransacciones("B");
            
            if($usuarios->CambiarPwd($_SESSION["usuariocod"],$_POST['clave1'],$_POST['clave2'],$_POST['clave3'])) 
            {		
                $conexion->ManejoTransacciones("C");
                FuncionesPHPLocal::MostrarMensaje($conexion,MSG_OK,"Se actualizó su contrase&ntilde;a de acceso. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
            } // si realizó el cambio
            else 
            {  // si se produjo un error en los datos
                $conexion->ManejoTransacciones("R");
            } // commit
        
        }
        ?>
              
	</div>
</div>        
<div style="height:50px; clear:both">&nbsp;</div>
<?php 

$oEncabezados->PieMenuEmergente();
?>


