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

$oNoticias = new cNoticias($conexion);

if (!isset($_GET['noticiacod']) || $_GET['noticiacod']=='')
	die();
	
if(strlen($_GET['noticiacod'])>10)
	die();
	
if (!FuncionesPHPLocal::ValidarContenido($conexion,$_GET['noticiacod'],"NumericoEntero"))
	die();

$noticiacod = $_GET['noticiacod'];
if(!$oNoticias->BuscarxCodigo($_GET,$resultado,$numfilas))
	return false;

FuncionesPHPLocal::ArmarLinkMD5Front("noticia.php",array("codigo"=>$noticiacod,session_name()=>session_id()),$getPrevisualizar,$md5Prev);

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Previsualizaci&oacute;n de Noticias</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body>
<form action="<?php  echo DOMINIOWEB ?>noticia.php?codigo=<?php  echo $noticiacod?>&md5=<?php  echo $md5Prev ?>" method="post" name="paginas_admi">
	<input type="hidden" name="<?php  echo session_name() ?>" value="<?php  echo session_id() ?>">
</form>
<script language="javascript" type="text/javascript">
	document.paginas_admi.submit();
</script>
</body>
</html>
