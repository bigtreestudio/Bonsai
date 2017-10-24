<?php 
header('Content-Type: text/html; charset=iso-8859-1'); 
//error_reporting(0);
error_reporting(E_WARNING | E_ERROR);
if (isset($_GET['ejecutarsolo']))
{
	include(__DIR__."/../config/include.php");
	$conexion = new accesoBDLocal(SERVIDORBD,USUARIOBD,CLAVEBD);
	$conexion->SeleccionBD(BASEDATOS);
	
	// carga las constantes generales
	FuncionesPHPLocal::CargarConstantes($conexion,array("roles"=>"si","sistema"=>SISTEMA));
	$conexion->SetearAdmiGeneral(ADMISITE);
}

include(__DIR__.'/../Librerias/FeedWriter.php');
header('Content-Type: text/html; charset=UTF-8');


function Procesar($html)
{
	cSepararHTML::ProcesarHTML($html,$partes);
	$html_generado = "";
	foreach($partes as $partehtml)
	{
		if(!is_array($partehtml))
			$html_generado .= $partehtml;
	}	
	return $html_generado;
}


function ArmarArregloCodigos($arbol,&$arreglo)
{
	
	foreach($arbol as $datoscategoria)
	{
		$arreglo[$datoscategoria['catcod']]=$datoscategoria['catcod'];
		if (count($datoscategoria['subarbol'])>0)
			ArmarArregloCodigos($datoscategoria['subarbol'],$arreglo);
	}
	return true;
}

function GenerarRSS($conexion,$archivo,$tituloRss, $catcod="", $orden="noticiafecha desc", $fdesde="", $fhasta="")
{
	$oNoticias = new cNoticiasPublicacion($conexion);
	if (isset($catcod) && $catcod!="")
	{
		$oCategorias = new cCategorias($conexion);
		if(!$oCategorias->ArmarArbolCategorias($catcod,$arbol))
			die();
		$arreglocodigos = array();	
		ArmarArregloCodigos($arbol,$arreglocodigos);
		$arreglocodigos[$catcod] = $catcod;
		$datos['catcod'] = implode(",",$arreglocodigos);
	}
	$datos['limit'] = "LIMIT 0,150";
	$datos['orderby'] = $orden;
	if ($fdesde!="" && $fhasta!="")
	{
		$datos['noticiafecha'] = $fdesde;
		$datos['noticiafecha2'] = $fhasta;
	}
	if(!$oNoticias->BusquedaAvanzada ($datos,$resultado,$numfilas))
		return false;

	$xmlFile = fopen(CARPETAMOBILEXML.$archivo, "wb");
	fwrite($xmlFile, "<?php xml version=\"1.0\" encoding=\"utf-8\"?>\r\n");
	fflush($xmlFile);
	fwrite($xmlFile, "<noticias>\r\n");

	while ($fila = $conexion->ObtenerSiguienteRegistro($resultado))
	{
		fwrite($xmlFile, "<item id='".$fila['noticiacod']."'>\r\n");
		fwrite($xmlFile, "<title>".utf8_encode($fila['noticiatitulo'])."</title>\r\n");
		if ($fila['noticiahrefexterno'])
			fwrite($xmlFile, "<link>".$fila['noticiahrefexterno']."</link>\r\n");
		else
			fwrite($xmlFile, "<link>".$fila['catdominio']."/".$fila['noticiadominio']."</link>\r\n");
		fwrite($xmlFile, "<description><![CDATA[".utf8_encode(Procesar($fila['noticiacopete']))."]]></description>\r\n");
		fwrite($xmlFile, "<descriptionLong><![CDATA[".utf8_encode(Procesar($fila['noticiacuerpo']))."]]></descriptionLong>\r\n");
		fwrite($xmlFile, "<Fecha>".FuncionesPHPLocal::ConvertirFecha(substr($fila['noticiafecha'],0,10),"aaaa-mm-dd","dd/mm/aaaa")."</Fecha>\r\n");
		fwrite($xmlFile, "<Hora>".substr($fila['noticiafecha'],11,5)."</Hora>\r\n");
		if ($fila['multimediaubic']!="")
		{
			$linkPhoto = $fila['multimediacatcarpeta'].CARPETA_SERVIDOR_MULTIMEDIA_THUMBSXL.$fila['multimediaubic'];
			$imgSize = filesize(CARPETA_SERVIDOR_MULTIMEDIA_FISICA . $linkPhoto);
			$img = CARPETA_SERVIDOR_MULTIMEDIA_FISICA.$linkPhoto;
			list($ancho, $alto, $tipo, $atr) = getimagesize($img);

			fwrite($xmlFile, "<imagen width=\"".$ancho."\" height=\"".$alto."\"><![CDATA[".DOMINIO_SERVIDOR_MULTIMEDIA . $linkPhoto."]]></imagen>\r\n");

			$linkPhoto = $fila['multimediacatcarpeta'].CARPETA_SERVIDOR_MULTIMEDIA_THUMBS.$fila['multimediaubic'];
			$imgSize = filesize(CARPETA_SERVIDOR_MULTIMEDIA_FISICA . $linkPhoto);
			$img = CARPETA_SERVIDOR_MULTIMEDIA_FISICA.$linkPhoto;
			list($ancho, $alto, $tipo, $atr) = getimagesize($img);
			fwrite($xmlFile, "<imagenThumbs width=\"".$ancho."\" height=\"".$alto."\"><![CDATA[".DOMINIO_SERVIDOR_MULTIMEDIA . $linkPhoto."]]></imagenThumbs>\r\n");
		}else
		{	
			fwrite($xmlFile, "<imagen></imagen>\r\n");
			fwrite($xmlFile, "<imagenThumbs></imagenThumbs>\r\n");
		}
		fwrite($xmlFile, "<visualizaciones>".$fila['visualizaciones']."</visualizaciones>\r\n");
		fwrite($xmlFile, "</item>\r\n");
	}
	fwrite($xmlFile, "</noticias>\r\n");
	fclose($xmlFile);

	return true;	
}



function GenerarXMLSeccionesMobile($conexion,$resultadoSecciones)
{
	$xmlFile = fopen(CARPETAMOBILEXML."secciones_portal.xml", "wb");
	fwrite($xmlFile, "<?php xml version=\"1.0\" encoding=\"utf-8\"?>\r\n");
	fflush($xmlFile);
	fwrite($xmlFile, "<secciones>\r\n");
	while ($fila = $conexion->ObtenerSiguienteRegistro($resultadoSecciones))
	{
		$nombrecat = trim(str_replace(array("á","é","í","ó","ú","ñ"),array("a","e","i","o","u","n"), utf8_encode($fila['catnom'])));
		$nombrecat=preg_replace('/[^a-zA-Z0-9-_ ]/', '', trim($nombrecat));
		$nombrecat=str_replace(' ', '', trim($nombrecat));
		fwrite($xmlFile, "<seccion dominio='".$fila['catdominio']."'>\r\n");
		fwrite($xmlFile, "<title>".utf8_encode($fila['catnom'])."</title>\r\n");
		fwrite($xmlFile, "<xml>".strtolower($nombrecat).".xml</xml>\r\n");
		fwrite($xmlFile, "<color>".$fila['catcolor']."</color>\r\n");
		fwrite($xmlFile, "</seccion>\r\n");
		fflush($xmlFile);
	}
	fwrite($xmlFile, "</secciones>\r\n");

	fclose($xmlFile);
	return true;	
}



function GenerarRSSMobileSeccion($conexion,$resultado,$archivo,$tituloRss, $catcod="")
{
   $html="";	
   $html.=" <ul class='nav estirar principales' id='nav1' role='menubar'>";	
   $tabindex=1;
   $akey=1;
   $nombrecategoria="";
   while ($fila = $conexion->ObtenerSiguienteRegistro($resultado))
	{
		if ($fila['catestado']==ACTIVO)
		{		
			$html.=" <li role='menuitem'  style='border-color:".$fila['catcolor']."; border-width: 5px;'>";		
				$nombrecategoria = trim(str_replace(array("á","é","í","ó","ú","ñ"),array("a","e","i","o","u","n"), utf8_encode($fila['catnom'])));
				$nombrecategoria=preg_replace('/[^a-zA-Z0-9-_ ]/', '', trim($nombrecategoria));
				$nombrecategoria=str_replace(' ', '', trim($nombrecategoria));
				$html.="<a href='/m/".$fila['catdominio']."/' title='".$fila['catdominio']."' tabindex='".$tabindex."' accesskey='".$akey."' />";	
					$html.= FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['catnom']);
				$html.="</a>";
			$html.="</li>";
			$tabindex++;
			$akey++;
		}	
	}
	$html.="</ul>";
	file_put_contents(PUBLICA.$archivo , $html);
	return true;	
}



function GenerarHome($conexion,$archivo)
{

	$oNoticias = new cNoticiasPublicacion($conexion);
	$datos['limit'] = "LIMIT 0,5";
	$datos['orderby'] = "noticiafecha desc";
	if(!$oNoticias->BusquedaAvanzada ($datos,$resultado,$numfilas))
		return false;
		
    $html="";
		
	
	$destacada = "destacada";
	$thumbs = "ThumbsXL";
	while ($fila = $conexion->ObtenerSiguienteRegistro($resultado))
	{
		
		$html.="<div class='article ".$destacada." estirar'>";
			$html.="<div class='antetitulo'>";	
				$html.="<span class='contenido' >";
				  $html.="<a style='color:".$fila['catcolor']."' href=\"".$fila['catdominio']."/\" title=\"".$fila['catnom']."\">";	
					$html.=$fila['catnom'];
				  $html.="</a>";		
				$html.="</span>";
			$html.="</div>";
			$html.="<h2 class='destacado'>";
			if (!preg_match('|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $fila['noticiadominio']))
				$html.="<a href=\"".$fila['catdominio']."/".$fila['noticiadominio']."\">";
			else
				$html.="<a href=\"/m/".$fila['catdominio']."/".$fila['noticiadominio']."\">";		
			$html.= FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['noticiatitulo'],ENT_QUOTES);
				$html.="</a>";
			$html.=" </h2>";
			
			if($fila["multimediaubic"]!="")
			{
				$img = DOMINIO_SERVIDOR_MULTIMEDIA.$fila['multimediacatcarpeta'].$thumbs.'/'.$fila['multimediaubic'];
				list($ancho, $alto, $tipo, $atr) = getimagesize($img);
				
				$html.="<div class='miniatura'>";
					$html.="<div class='foto figure'>";
						$html.="<a href=\"".$fila['catdominio']."/".$fila['noticiadominio']."\" title=\"Ir a la noticia ". FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['noticiatitulo'],ENT_QUOTES)."\">";
							$linkFoto = $img;
							$estilo="";
							if ($destacada=="")
								$estilo = "style=\"width:".$ancho."px; height=".$alto."px\"";
							$html.="<img src=".$linkFoto." alt=\"". FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['noticiatitulo'],ENT_QUOTES)."\" ".$estilo." >";
						$html.="</a>";
					$html.="</div>";
				$html.="</div>";				
			}
			$html.="<p>"; 
				$html.=$fila['noticiacopete']; 
			$html.="</p>"; 
		$html.=" </div>";
		$thumbs = "Thumbs";
		$destacada = "";
	}
	file_put_contents(PUBLICA.$archivo, $html);
	return true;	
}
$fhasta=date("d/m/Y");
$fdesde = strtotime ( '-2 day' , strtotime ( date("Y-m-d H:i:s") ) ) ;
$fdesde = date ( 'd/m/Y' , $fdesde );
GenerarRSS($conexion,"noticiasmasvistas.xml","Noticias mas vistas","", "visualizaciones DESC", $fdesde, $fhasta);
GenerarRSS($conexion,"ultimasnoticias.xml","Últimas noticias");
$oCategorias = new cCategorias ($conexion);
if(!$oCategorias->BuscaCategoriasRaiz($resultado,$numfilas))
	return false;
while ($fila = $conexion->ObtenerSiguienteRegistro($resultado))
{
	if ($fila['catestado']==ACTIVO)
	{
		$nombrecat = trim(str_replace(array("á","é","í","ó","ú","ñ"),array("a","e","i","o","u","n"), utf8_encode($fila['catnom'])));
		$nombrecat=preg_replace('/[^a-zA-Z0-9-_ ]/', '', trim($nombrecat));
		$nombrecat=str_replace(' ', '', trim($nombrecat));
		GenerarRSS($conexion,strtolower($nombrecat).".xml","Últimas noticias de ".utf8_encode($fila['catnom']),$fila['catcod']);
	}	
}
$conexion->MoverPunteroaPosicion($resultado,0);
GenerarXMLSeccionesMobile($conexion,$resultado);
$conexion->MoverPunteroaPosicion($resultado,0);
GenerarRSSMobileSeccion($conexion,$resultado,"index_mobile_seccion.html","Secciones");
GenerarHome($conexion,"index_mobile.html");


?>