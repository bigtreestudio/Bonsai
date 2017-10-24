<? 
set_time_limit ( 600000 );
ini_set('memory_limit', '512M');
ob_start();
error_reporting(E_ALL);

define("DIRRAIZCRONES","");//para dattatec
$_SERVER['DOCUMENT_ROOT'] = "/var/www/html/aftic/";
$varDir = "/var/www/html/aftic/bt-admin/";

include($varDir."config/include.php");


$conexion = new accesoBDLocal(SERVIDORBD,USUARIOBD,CLAVEBD);
$conexion->SeleccionBD(BASEDATOS);
FuncionesPHPLocal::CargarConstantes($conexion,array("sistema"=>SISTEMA));


$sql = "INSERT INTO usuarios_cv_niveles (nivelnombre, nivelestadocod, utlmodfecha, ultmodusuario) VALUES ('Maestria', '10', '2015-11-30 12:51:22', '1')";

if (!$conexion->_EjecutarQuery($sql,$erroren,$result,$errno))
	die("error al insertar el usuario");


while($fila= $conexion->ObtenerSiguienteRegistro($result))
	print_r($fila);


echo "corrio bien";
?>