<? 
set_time_limit ( 600000 );
ini_set('memory_limit', '512M');
ob_start();
error_reporting(E_ALL);

define("DIRRAIZCRONES","");//para dattatec
$_SERVER['DOCUMENT_ROOT'] = "/var/www/html/aftic/";
$varDir = "/var/www/html/aftic/bt-admin/";

include($varDir."config/include.php");
include_once $varDir.'Spreadsheet/PHPepeExcel.php';
include($_SERVER['DOCUMENT_ROOT']."/bt-admin/cronjobs/sql_constantes.php");


define("BDSQLSERVERUSER","usrcnc");
define("BDSQLSERVERPASS","servicioweb2014");
define("BDSQLSERVERACCESO","sql2005cnc");
//define("DIRLOCAL",__DIR__);
define("DIRLOCAL","/var/www/html/aftic/");
$_SERVER['DOCUMENT_ROOT'] = DIRLOCAL;


$conexionSqlServer=odbc_connect(BDSQLSERVERACCESO, BDSQLSERVERUSER, BDSQLSERVERPASS) or die(odbc_errormsg());
	$erroren="";
$ExecuteSql=odbc_exec($conexionSqlServer,"select * from numeracion"); 
while($datos = (odbc_fetch_array($ExecuteSql)))
{		
	print_r($datos);
}
/*
$conexion = new accesoBDLocal(SERVIDORBD,USUARIOBD,CLAVEBD);
$conexion->SeleccionBD(BASEDATOS);
FuncionesPHPLocal::CargarConstantes($conexion,array("sistema"=>SISTEMA));


$sql = "Select * from GEOGRAFICA";

if (!$conexion->_EjecutarQuery($sql,$erroren,$resinsert,$errno))
	die("error al modificar el usuario");

while ($fila = $conexion->ObtenerSiguienteRegistro($resinsert))
	print_r($fila);


*/
echo "corrio ok!";

?>