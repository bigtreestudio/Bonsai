<?php 
ini_set("mysql.trace_mode",0);
putenv("TZ=America/Argentina/Buenos_Aires");
ini_set("default_charset", "ISO-8859-1");
mb_internal_encoding("ISO-8859-1");
mb_http_output('ISO-8859-1');
mb_http_input('ISO-8859-1');
mb_regex_encoding('ISO-8859-1');

define("PROJECTNAME", "BA en OGP");

define("DOCUMENT_ROOT",$_SERVER['DOCUMENT_ROOT']);
define("RAIZ_SITIO","/");
define("DIR_ROOT",$_SERVER['DOCUMENT_ROOT']."/"); // directorio sobre el que está corriendo el sistema
define("DIR_ARCH",DIR_ROOT."/");

define("DIR_DATA",DIR_ROOT."data/"); 
define("DIR_CLASES",DIR_ROOT."Clases/"); 
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
require_once(DIR_LIBRERIAS."funcionesphp.php");
require_once(DIR_LIBRERIAS."cCalendario.php");
require_once(DIR_LIBRERIAS."cSepararHTML.php");
require_once(DIR_LIBRERIAS."Multimedia.php");
require_once(DIR_PLANTILLA."cEncabezados.php");

include(DIR_CLASES."cGraficos.class.php");
include(DIR_CLASES."cAgenda.class.php");
include(DIR_CLASES."cMenu.class.php");
?>