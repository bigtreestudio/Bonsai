<?php 
header('Content-Type: text/html; charset=iso-8859-1'); 

//error_reporting(0);
error_reporting(E_WARNING | E_ERROR);
if (isset($_GET['ejecutarsolo']))
{
	include(DIRRAIZCRONES."../config/include.php");
	include(DIRRAIZCRONES."../Librerias/FeedWriter.php");
	$conexion = new accesoBDLocal(SERVIDORBD,USUARIOBD,CLAVEBD);
	$conexion->SeleccionBD(BASEDATOS);
	
	// carga las constantes generales
	FuncionesPHPLocal::CargarConstantes($conexion,array("roles"=>"si","sistema"=>SISTEMA));
	$conexion->SetearAdmiGeneral(ADMISITE);
}

function ProcesarRSS($html)
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

header('Content-Type: text/html; charset=UTF-8');
function GenerarRSS($conexion,$archivo,$tituloRss, $catcod="")
{

	$oNoticias = new cNoticiasPublicacion($conexion);
	if (isset($catcod) && $catcod!="")
		$datos['catcod'] = $catcod;
		
	$datos['limit'] = "LIMIT 0,50";
	$datos['orderby'] = "noticiafecha desc";
	if(!$oNoticias->BusquedaAvanzada ($datos,$resultado,$numfilas))
		return false;
		
	$feed = new FeedWriter(RSS2);
	$feed->setTitle(($tituloRss.' - '.PROJECTNAME));
	$feed->setLink(DOMINIOPORTAL.RAIZPORTAL.'rss');
	$feed->setDescription((PROJECTNAME.' - RSS de '.$tituloRss));
	$feed->setChannelElement('rights', utf8_encode('Copyright (c) 2012'));
		
	while ($fila = $conexion->ObtenerSiguienteRegistro($resultado))
	{
        $itemNota = $feed->createNewItem();
        $itemNota->setTitle(utf8_encode($fila['noticiatitulo']));
        $itemNota->setLink(DOMINIOPORTAL.RAIZPORTAL.$fila['catdominio']."/".$fila['noticiadominio']);
        $itemNota->setDescription(utf8_encode(html_entity_decode(ProcesarRSS($fila['noticiacopete']), ENT_COMPAT, 'ISO8859-1')));
        $itemNota->setDate($fila['noticiafecha']);
		if ($fila['multimediaubic']!="")
		{
			$linkPhoto = $fila['multimediacatcarpeta'].CARPETA_SERVIDOR_MULTIMEDIA_THUMBSXL.$fila['multimediaubic'];
			$imgSize = filesize(CARPETA_SERVIDOR_MULTIMEDIA_FISICA . $linkPhoto);
			$size = getimagesize(CARPETA_SERVIDOR_MULTIMEDIA_FISICA . $linkPhoto); 
			$itemNota->setEncloser(DOMINIO_SERVIDOR_MULTIMEDIA . $linkPhoto, $imgSize, $size['mime']);
		}
		$feed->addItem($itemNota);
	}
	$rssGenerado = str_replace('&', '&amp;', $feed->generateFeed());
	file_put_contents(CARPETARSS.$archivo , $rssGenerado);

	return true;	
}


function GenerarRSSAgenda($conexion,$archivo,$catcod="")
{

	$oAgenda = new cAgenda($conexion);
	if (isset($catcod) && $catcod!="")
		$datos['catcod'] = $catcod;
		
	$datos['limit'] = "LIMIT 0,50";
	$datos['orderby'] = "agendafdesde ASC, horainicio ASC";
	$datos['agendaestadocod'] = AGEPUBLICADO;
	if(!$oAgenda->BuscarAgendaBusquedaAvanzanda ($datos,$resultado,$numfilas))
		return false;
		
	$feed = new FeedWriter(RSS2);
	$feed->setTitle(utf8_encode('Eventos - '.PROJECTNAME));
	$feed->setLink(DOMINIOPORTAL.RAIZPORTAL.'rss');
	$feed->setDescription(utf8_encode(PROJECTNAME.' - RSS de eventos'));
	$feed->setChannelElement('rights', utf8_encode('Copyright (c) 2012'));
		
	while ($fila = $conexion->ObtenerSiguienteRegistro($resultado))
	{
        $itemNota = $feed->createNewItem();
        $itemNota->setTitle(utf8_encode($fila['agendatitulo']));
        $itemNota->setLink(DOMINIOPORTAL.RAIZPORTAL."agenda");
        $itemNota->setDescription(utf8_encode(html_entity_decode(ProcesarRSS($fila['agendaobservaciones']), ENT_COMPAT, 'ISO8859-1')));
        $itemNota->setDate($fila['ultmodfecha']);
        $itemNota->startDate($fila['agendafdesde']." ".$fila['horainicio']);
        $itemNota->endDate($fila['agendafhasta']." ".$fila['horafin']);
        $feed->addItem($itemNota);
	}
	$rssGenerado = str_replace('&', '&amp;', $feed->generateFeed());
	file_put_contents(CARPETARSS.$archivo , $rssGenerado);
	
	return true;	
}





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
GenerarRSSAgenda($conexion,"ultimoseventos.xml");
$conexion->MoverPunteroaPosicion($resultado,0);


?>