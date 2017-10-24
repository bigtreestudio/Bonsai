<?php 
define("SISTEMA",'SITIOCONFIGURACION');
define("TITLESISTEMA",'BONSAI CMS');
define("PROJECTNAME", "BONSAI CMS");

//SITIOPRODUCTIVO 1 - desarrollo
//SITIOPRODUCTIVO 2 - testing
//SITIOPRODUCTIVO 3 - producccion
define("SITIOPRODUCTIVO",'1');

//CONECTORMYSQL 1 - Mysql
//CONECTORMYSQL 2 - Mysqlli
define("CONECTORMYSQL",'2');

switch(SITIOPRODUCTIVO)
{
	case 1:
		define("RAIZPORTAL","");
		define("DOMINIOPORTAL","http://bonsailocal.com.ar/");
		define("BASEDATOS",'bonsai');
		define("SERVIDORBD",'localhost');
		define("USUARIOBD",'root');
		define("CLAVEBD",'');
		error_reporting(E_ALL & ~E_DEPRECATED);  
		break;
	case 2:
		define("RAIZPORTAL","");
		define("DOMINIOPORTAL","");
		define("BASEDATOS",'bonsai');
		define("SERVIDORBD",'localhost');
		define("USUARIOBD",'root');
		define("CLAVEBD",'');
		error_reporting(E_ALL & ~E_DEPRECATED); 
		break;
	case 3:
		define("DOMINIOPORTAL","");
		define("BASEDATOS",'bonsai');
		define("SERVIDORBD",'localhost');
		define("USUARIOBD",'root');
		define("CLAVEBD",'');		
		define("RAIZPORTAL","");
		error_reporting(0);  
		break;
}
define("TIEMPOSESION",10800); // cantidad de segundos que dura la sesion
?>