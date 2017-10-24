<?php 
require('./config/include.php');

$conexion = new accesoBDLocal(SERVIDORBD,USUARIOBD,CLAVEBD);
$conexion->SeleccionBD(BASEDATOS);

// carga las constantes generales
FuncionesPHPLocal::CargarConstantes($conexion,array("roles"=>"si","sistema"=>SISTEMA));


// arma las variables de sesion y verifica si se tiene permisos
$sesion = new Sesion($conexion,false); // Inicia session 

$oEncabezados = new cEncabezados($conexion);
//print_r($_POST);
if(!isset($_POST['usuariocod']) || trim($_POST['usuariocod'])=="")
{
	header("Location:index.php?msg_error=1");
	die();
}
if(!isset($_POST['md5']) || trim($_POST['md5'])=="")
{
	header("Location:index.php?msg_error=1");
	die();
}
$array = array("usuariocod"=>$_POST['usuariocod']);
FuncionesPHPLocal::ArmarLinkMD5Front("usuario_valida_cambia_pass.php",$array,$get,$md5);
//echo $md5;die;
if ($_POST['md5']!=$md5)
{
	echo "md5";
	//header("Location:index.php?msg_error=1");
	die();
}

if(!isset($_POST['claveactual']) || trim($_POST['claveactual'])=="")
{
	echo "actual";
	//header("Location:index.php?msg_error=1");
	die();
}
if(!isset($_POST['clavenueva']) || trim($_POST['clavenueva'])=="")
{
	echo "nueva";
	//header("Location:index.php?msg_error=1");
	die();
}
if(!isset($_POST['claveconf']) || trim($_POST['claveconf'])=="")
{
	echo "conf";
	//header("Location:index.php?msg_error=1");
	die();
}

$oEncabezados->EncabezadoMenuEmergente("","");
$oUsuarios = new cUsuarios($conexion);

?>
<div class="panel-style space">
    <div class="form-group">
<?
$conexion->ManejoTransacciones("B");
$error = true;
if($oUsuarios->CambiarPwd($_POST['usuariocod'],$_POST['claveactual'],$_POST['clavenueva'],$_POST['claveconf']))
{	
	$datos['usuariocod'] = $_POST['usuariocod'];
	$datos["usuarioestado"] = USUARIOACT;
	if($oUsuarios->ModificarEstado($datos))
		$error = false;
}
	
	
if ($error)	
	$conexion->ManejoTransacciones("R");
else
{
	$conexion->ManejoTransacciones("C");
	?>
    	<div class="alert alert-success">
        	<strong>Contrase&ntilde;a modificada.</strong> Por favor ingrese nuevamente con su nueva contrase&ntilde;a.
        </div>
    <? 	
}
?>
	</div>
	<div class="form-group">
        <div class="text-center">
            <a href="/bt-admin" class="btn btn-primary">Volver al Inicio</a>
        </div>
    </div>
</div>
<? 	
	
$oEncabezados->PieMenuEmergente();

?>