<?php 
header('Content-Type: text/html; charset=iso-8859-1'); 
//error_reporting(0);
error_reporting(E_WARNING | E_ERROR);
if (isset($_GET['ejecutarsolo']))
{
	include(DIRRAIZCRONES."../config/include.php");
	$conexion = new accesoBDLocal(SERVIDORBD,USUARIOBD,CLAVEBD);
	$conexion->SeleccionBD(BASEDATOS);
	
	// carga las constantes generales
	FuncionesPHPLocal::CargarConstantes($conexion,array("roles"=>"si","sistema"=>SISTEMA));
	$conexion->SetearAdmiGeneral(ADMISITE);
}


// carga las constantes generales
FuncionesPHPLocal::CargarConstantes($conexion,array("roles"=>"si","sistema"=>SISTEMA));
$conexion->SetearAdmiGeneral(ADMISITE);

$oNoticias = new cNoticiasPublicacion($conexion);
$datosNoticia['orderby'] = "noticiafecha desc";
$datosNoticia['noticiadestacada'] = "1";

$sql = "SELECT notpub.*, c.multimediadesc, c.multimedianombre, c.multimediaubic, mc.multimediacatcarpeta,
  nottem.temacod, nottem.tematitulo 
FROM not_noticias_publicadas AS notpub 
LEFT JOIN (
 SELECT notmul.noticiacod, notmul.multimediacod  FROM not_noticias_mul_multimedia AS notmul INNER JOIN (
 SELECT multimediacod, MIN(notmultimediaorden) AS notmultimediaorden 
 FROM not_noticias_mul_multimedia WHERE multimediaconjuntocod=1 GROUP BY noticiacod
 ) menor ON notmul.multimediacod=menor.multimediacod AND notmul.notmultimediaorden= menor.notmultimediaorden GROUP BY noticiacod
) noticiamultimedia 
ON notpub.noticiacod=noticiamultimedia.noticiacod 
LEFT JOIN mul_multimedia AS c ON noticiamultimedia.multimediacod=c.multimediacod AND c.multimediatipocod=2 
LEFT JOIN mul_multimedia_categorias AS mc ON c.multimediacatcod=mc.multimediacatcod
LEFT JOIN ( 
	SELECT noticiacod,b.tematitulo, b.temacod FROM not_noticias_temas AS a INNER JOIN tem_temas AS b ON a.temacod = b.temacod 
	GROUP BY noticiacod
) nottem ON notpub.noticiacod=nottem.noticiacod 
WHERE notpub.noticiadestacada=1 ORDER BY noticiafecha DESC limit 0,3";
$erroren="";
$conexion->_EjecutarQuery($sql,$erroren,$resultadoNoticias,$errno);


$html = '<div class="recomendadas">';
$html .= '<div class="titulorecomendadas">';
$html .= 'Recomendadas';
$html .= '</div>';
$html .= '<div class="clearboth"></div>';
$html .= '<ul class="clearfix">';


while($fila = $conexion->ObtenerSiguienteRegistro($resultadoNoticias))
{
	$html .= '<li>';
	
	$tieneimg = false;
	$class="";
	if ($fila['multimediaubic']!="")
	{
		$imagen = DOMINIO_SERVIDOR_MULTIMEDIA.$fila['multimediacatcarpeta']."Thumbs/".$fila['multimediaubic'];
		$alt =  FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['multimediadesc'],ENT_QUOTES);
		$html .= '<div class="foto">';
		$html .= '<img src="'.$imagen.'" alt="'.$alt.'" />';
		$html .= '</div>';
		$tieneimg = true;
		$class=" conFoto";
	}
	$html .= '<div class="txtRecomendada'.$class.'">';
	
	$html .= '<div class="categoria">';
	
	if ($fila['temacod']!="")
	{
		$dominioTema = FuncionesPHPLocal::EscapearCaracteres($fila['tematitulo']);
		$dominioTema=preg_replace('/[^a-zA-Z0-9-_ ]/', '-', trim($dominioTema));
		$dominioTema=str_replace(' ', '-', trim($dominioTema))."_t".$fila['temacod'];
		$html .= '<a href="/'.$dominioTema.'" title="'. FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['tematitulo'],ENT_QUOTES).'">'. FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['tematitulo'],ENT_QUOTES).'</a>';
	}else
	{
		$dominio = $fila['catdominio'];
		$html .= '<a href="/'.$dominio.'" title="'. FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['catnom'],ENT_QUOTES).'">'. FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['catnom'],ENT_QUOTES).'</a>';		
	}
	$html .= '</div>';
	
	$html .= '<div class="titulonota">';
	$target = "";
	if ($fila['noticiahref']!="")
	{
		$target = ' target="_blank"';
		$dominio = $fila['noticiahref'];
	}else	
		$dominio = $fila['catdominio']."/".$fila['noticiadominio'];
	$html .= '<a href="/'.$dominio.'" '.$target.' title="'. FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['noticiatitulo'],ENT_QUOTES).'">'. FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['noticiatitulo'],ENT_QUOTES).'</a>';
	$html .= '</div>';
	$html .= '<div class="clearboth"></div>';
	
	$html .= '</div>';
	$html .= '<div class="clearboth"></div>';
	
	$html .= '</li>';
}
$html .= '</ul>';
$html .= '</div>';
    	


if (!file_put_contents(PUBLICA."recomendadas.html", $html)){
	throw new Exception('imposible escribir: '.PUBLICA."recomendadas.html"."\n");
}



?>