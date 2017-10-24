<?php  
ob_start();
require('./config/include.php');
include(DIR_LIBRERIAS."recaptchalib.php");

$conexion = new accesoBDLocal(SERVIDORBD,USUARIOBD,CLAVEBD);
$conexion->SeleccionBD(BASEDATOS);

// carga las constantes generales
FuncionesPHPLocal::CargarConstantes($conexion,array("roles"=>"si","sistema"=>SISTEMA));
$conexion->SetearAdmiGeneral(ADMISITE);

// arma las variables de sesion y verifica si se tiene permisos
$sesion = new Sesion($conexion,true); // Inicia session y borra todo

// ve si el sistema está bloqueado
$oSistemaBloqueo = new SistemaBloqueo();
$oSistemaBloqueo->VerificarBloqueo($conexion);

$oEncabezados = new cEncabezados($conexion);
$oEncabezados->EncabezadoMenuEmergenteLogin();

$msg = "";
$error = false;
$usuario = "";
if (isset($_GET['msg_error']))
{
	if (isset($_GET['usuarioiderror']))
		$usuario = $_GET['usuarioiderror'];
	$error = true;
	switch ($_GET['msg_error'])
	{
		case 1:
			$msg = "<strong>Acceso Denegado</strong> | <b>Usuario y/o Clave no v&aacute;lidos.</b>";
			break;
		case 2:
			$msg = "<strong>Su sesi&oacute;n ha caducado, por favor, vuelva a ingresar</span>";
			break;
		case 3:
			$msg = "<strong>Es necesario tildar el casillero verificador</span>";
			break;
		case 4:
			$msg = "<strong>C&oacute;digo verificador incorrecto</span>";
			break;
		
	}
	
}

if (isset($_GET['msg_accion']))
{
	$error = true;
	switch ($_GET['msg_accion'])
	{
		case 1:
			$msg = "<div style='color:#090; font-size:11px;'><strong>Recuperar Contrase&ntilde;a</strong> | <b>Se ha enviado a una nueva contrase&ntilde;a a su email.</b></div>";
			break;
		
	}
	
}

	
?>
<script type="text/javascript">
	$(document).ready(function() {
		// Tabs
		$('#tabs, #tabs2').tabs();

	});

    
	function ValidarJS(formulario_login) 
	{
		if (formulario_login.usuarioid.value=="")
		{
			alert("Debe ingresar su id de usuario");
			formulario_login.usuarioid.focus();
			return false;
		}
		else if (formulario_login.usuarioclave.value=="")
		{
			alert("Debe ingresar su contraseña");
			formulario_login.usuarioclave.focus();
			return false;
		}
		else
			return true;
	}

	function ValidarJSEnvioContrasenia(formulario) 
	{
		if($("#usuarioidmail").val()=="" || !ValidarContenido($("#usuarioidmail").val(),"Email"))
		{
			alert("Debe ingresar el eMail y debe tener un formato válido");
			formulario.usuarioidmail.focus();
			return false;	
		}
		
		return true;
	}


</script>
<script src='https://www.google.com/recaptcha/api.js'></script>

		<?php  if ($error){?>
            <div class="msje_error">
            <p>
                <?php  echo $msg?>
            </p>
            </div>
        <?php  }?>
		<form action="usuario_valida.php" id="formulario_login" name="formulario_login" method="post">
		<div class="wrapper-login">
			<input type="text" id="usuarioid" name="usuarioid" placeholder="contacto@bigtree.com.ar" value="<? echo $usuario?>" class="user"/>
			<input type="password" placeholder="xxxxxxxxxx" value="" name="usuarioclave" id="usuarioclave" class="password"/>
		</div>
        <? if (LOGINCAPTCHA==1){?>
        <div class="g-recaptcha" data-sitekey="<? echo PUBLICKEYCAPTCHA?>"></div>
		<br>
        <? }?>
        <button class="login-button" type="submit">Ingresar</button>
		<br />
        <a href="recuperar_password.php" class="login-button">Olvid&eacute; mi contrase&ntilde;a</a>
		</form>

		
<?php  $oEncabezados->PieMenuEmergenteLogin(); ?>
