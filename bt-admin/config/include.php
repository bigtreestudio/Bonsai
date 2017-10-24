<?
ini_set("mysql.trace_mode",0);

putenv("TZ=America/Argentina/Buenos_Aires");

ini_set("default_charset", "ISO-8859-1");
mb_internal_encoding("ISO-8859-1");
mb_http_output('ISO-8859-1');
mb_http_input('ISO-8859-1');
mb_regex_encoding('ISO-8859-1');

define("DOCUMENT_ROOT",$_SERVER['DOCUMENT_ROOT']);
define("RAIZ_SITIO","/");
define("DIR_ROOT",$_SERVER['DOCUMENT_ROOT']."/bt-admin/"); // directorio sobre el que está corriendo el sistema
define("DIR_ARCH",DIR_ROOT."/");
define("DIR_CLASES",DIR_ROOT."Clases/"); 
define("DIR_CLASES_DB",DIR_ROOT."Datos/"); 
define("DIR_CLASES_LOGICA",DIR_ROOT."Logica/"); 
define("DIR_LIBRERIAS",DIR_ROOT."Librerias/"); 
define("DIR_PLANTILLA",DIR_ROOT."Plantillas/"); 
define("DIR_BUSQUEDA",DIR_ROOT."||".DIR_ROOT.DIR_CLASES."||".DIR_ROOT.DIR_LIBRERIAS."||".DIR_ROOT.DIR_PLANTILLA);

require_once(DIR_ROOT."config/parametros.php");

require_once(DIR_ROOT."config/constantes.php");

switch(CONECTORMYSQL)
{
	case 1;
		require_once(DIR_LIBRERIAS."accesoBD.php");
		break;
	case 2;
		require_once(DIR_LIBRERIAS."accesoBDMySqlLi.php");
		break;
}
require_once(DIR_ROOT."config/constantes_tamanios.php");
require_once(DIR_LIBRERIAS."funcionesphp.php");
require_once(DIR_LIBRERIAS."cBrowser.php");
require_once(DIR_LIBRERIAS."cFuncionesMultimedia.php");
require_once(DIR_LIBRERIAS."cDateTime.php");
require_once(DIR_LIBRERIAS."cSepararHTML.php");
require_once(DIR_LIBRERIAS."sesion.php");
require_once(DIR_LIBRERIAS."PHPMailerAutoload.php");
require_once(DIR_LIBRERIAS."sistema_bloqueo.php");
require_once(DIR_LIBRERIAS."Multimedia.php");

require_once(DIR_PLANTILLA."cEncabezados.php");
require_once(DIR_PLANTILLA."cMails.php");

$archivosdir=array();
if ($dh = opendir(DIR_CLASES_LOGICA)) 
{
	while (($file = readdir($dh)) !== false)
	{
		$array=explode('.', $file);
		$ext=array_pop($array);
			if($file!='.' && $file!='..' && $file!='cExcel.php' && $ext=="php")
			{
				require_once(DIR_CLASES_LOGICA.$file);
			}
	} // fin while
closedir($dh);
}



?>