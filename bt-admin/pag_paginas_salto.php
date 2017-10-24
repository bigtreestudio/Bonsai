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

$oPagina = new cPaginas($conexion);

if (!isset($_GET['pagcod']) || $_GET['pagcod']=='')
	die();
	
if(strlen($_GET['pagcod'])>10)
	die();
	
if (!FuncionesPHPLocal::ValidarContenido($conexion,$_GET['pagcod'],"NumericoEntero"))
	die();

$pagcod = $_GET['pagcod'];
if(!$oPagina->BuscarxCodigo($_GET,$resultado,$numfilas))
	return false;

FuncionesPHPLocal::ArmarLinkMD5Front("pagina.php",array("codigo"=>$pagcod,session_name()=>session_id()),$getPrevisualizar,$md5Prev);

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Previsualizaci&oacute;n de P&aacute;ginas</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body>
<form action="<?php  echo DOMINIOWEB ?>pagina.php?codigo=<?php  echo $pagcod?>&md5=<?php  echo $md5Prev ?>" method="post" name="paginas_admi">
	<input type="hidden" name="<?php  echo session_name() ?>" value="<?php  echo session_id() ?>">
</form>
<script language="javascript" type="text/javascript">
	document.paginas_admi.submit();
</script>
</body>
</html>
