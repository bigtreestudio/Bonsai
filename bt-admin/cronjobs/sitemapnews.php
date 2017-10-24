<?php 
header('Content-Type: text/html; charset=iso-8859-1'); 
//error_reporting(0);
error_reporting(E_WARNING | E_ERROR);

include('../config/include.php');

$conexion = new accesoBDLocal(SERVIDORBD,USUARIOBD,CLAVEBD);
$conexion->SeleccionBD(BASEDATOS);

// carga las constantes generales
FuncionesPHPLocal::CargarConstantes($conexion,array("roles"=>"si","sistema"=>SISTEMA));
$conexion->SetearAdmiGeneral(ADMISITE);


$oNoticias = new cNoticiasPublicacion($conexion);
if(!$oNoticias->BuscarNoticiasGoogleNews($resultadoNoticias,$numfilas))
	return false;

$xmlFile = fopen(CARPETASITEMAP."google-news.xml", "wb");
fwrite($xmlFile, "<?php xml version=\"1.0\" encoding=\"utf-8\"?>\r\n");
fwrite($xmlFile, "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\" xmlns:news=\"http://www.google.com/schemas/sitemap-news/0.9\">\r\n");
fflush($xmlFile);

while ($fila = $conexion->ObtenerSiguienteRegistro($resultadoNoticias))
{
        $url = DOMINIOWEB;
        fwrite($xmlFile, "<url>\r\n");
        fwrite($xmlFile, "<loc>".DOMINIOWEB."</loc>\r\n");
        fwrite($xmlFile, "<news:news>\r\n");
        fwrite($xmlFile, "<news:publication>\r\n");
        fwrite($xmlFile, "<news:name>".PROJECTNAME."</news:name>\r\n");
        fwrite($xmlFile, "<news:language>es</news:language>\r\n");
        fwrite($xmlFile, "</news:publication>\r\n");
        fwrite($xmlFile, "<news:genres>".$fila['catnom'].", Noticia</news:genres>\r\n");
        fwrite($xmlFile, "<news:publication_date>".date("Y-m-d", strtotime($fila['ultmodfecha']))."</news:publication_date>\r\n");
        fwrite($xmlFile, "<news:title>".utf8_encode($fila['noticiatitulo'])."</news:title>\r\n");
        fwrite($xmlFile, "<news:keywords>".utf8_encode($fila['noticiatags'])."</news:keywords>\r\n");
        fwrite($xmlFile, "</news:news>\r\n");
        fwrite($xmlFile, "</url>\r\n");
        fflush($xmlFile);
}

fwrite($xmlFile, "</urlset>");

fclose($xmlFile);


?>