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

$oGalerias = new cGalerias($conexion);

if (!isset($_GET['galeriacod']) || $_GET['galeriacod']=='')
	die();
	
if(strlen($_GET['galeriacod'])>10)
	die();
	
if (!FuncionesPHPLocal::ValidarContenido($conexion,$_GET['galeriacod'],"NumericoEntero"))
	die();

$galeriacod = $_GET['galeriacod'];
if(!$oGalerias->BuscarxCodigo($_GET,$resultado,$numfilas))
	return false;

FuncionesPHPLocal::ArmarLinkMD5Front("galeria.php",array("codigo"=>$galeriacod,session_name()=>session_id()),$getPrevisualizar,$md5Prev);

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Previsualizaci&oacute;n de Galeria</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body>
<form action="<?php  echo DOMINIOWEB ?>galeria.php?codigo=<?php  echo $galeriacod?>&md5=<?php  echo $md5Prev ?>" method="post" name="paginas_admi">
	<input type="hidden" name="<?php  echo session_name() ?>" value="<?php  echo session_id() ?>">
</form>
<script language="javascript" type="text/javascript">
	document.paginas_admi.submit();
</script>
</body>
</html>
