<?php 

class FuncionesPHPLocal
{

//----------------------------------------------------------------------------------------- 
// Genera todas las constantes necesarias para el sistema


	static function ObtenerDatosCliente()
	{
	  $temp=array();
	  $ip=$_SERVER['REMOTE_ADDR'];
	  $datos=$_SERVER['HTTP_USER_AGENT'];
	  array_push($temp,$ip);
	  if(strpos($datos,"Windows")!==false)
		array_push($temp,"Windows");
	  elseif(strpos($datos,"Mac")!==false)
		array_push($temp,"Mac");
	  elseif(strpos($datos,"Linux")!==false)
		array_push($temp,"Linux");
	  
	  if(strpos($datos,"MSIE")!==false)
		array_push($temp,"Internet Explorer");
	  elseif(strpos($datos,"Firefox")!==false)
		array_push($temp,"Firefox");
	  elseif(strpos($datos,"Chrome")!==false)
		array_push($temp,"Google Chrome");
	  elseif(strpos($datos,"Safari")!==false)
		array_push($temp,"Safari");
	  elseif(strpos($datos,"Opera")!==false)
		array_push($temp,"Opera");
	  else
		array_push($temp,"Navegador desconocido");
	  
	  return $temp;   
	  
	}


	static function CargarConstantes($conexion,$tipocarga)
	{

		if(isset($tipocarga['multimedia']))
		{
			// Constantes Generales 
			
			$spparam=array();

			if(!$conexion->ejecutarStoredProcedure("sel_mul_multimedia_constantes",$spparam,$resultado,$numfilas,$errno))
				die("Error al cargar las constantes de multimedia");
			
			while ($fila = $conexion->ObtenerSiguienteRegistro($resultado)) 
				if(!defined($fila['cte'])) 
					define ($fila['cte'],$fila['valor']);
		
		}

			
		
			
	}


//----------------------------------------------------------------------------------------- 	
// Convierte la fecha de un formato a otro

	static function ConvertirFecha($fecha,$formatoinput,$formatooutput)
	{
		if($fecha=='') return "";
		
		if($formatoinput=='dd/mm/aaaa' && $formatooutput=='aaaa/mm/dd')
			return substr($fecha,6,4)."/".substr($fecha,3,2)."/".substr($fecha,0,2);
		elseif($formatoinput=='dd/mm/aaaa' && $formatooutput=='aaaa-mm-dd')
			return substr($fecha,6,4)."-".substr($fecha,3,2)."-".substr($fecha,0,2);
		elseif($formatoinput=='aaaa-mm-dd' && $formatooutput=='dd/mm/aaaa')
			return substr($fecha,8,2)."/".substr($fecha,5,2)."/".substr($fecha,0,4);
		elseif($formatoinput=='aaaa-mm-dd' && $formatooutput=='dd-mm-aaaa')
			return substr($fecha,8,2)."-".substr($fecha,5,2)."-".substr($fecha,0,4);
		elseif($formatoinput=='timestamp' && $formatooutput=='dd/mm/aaaa')
			return substr($fecha,6,2)."/".substr($fecha,4,2)."/".substr($fecha,0,4);
	}
	

//----------------------------------------------------------------------------------------- 
// Muestra un mensaje en pantalla y, segun el nivel del mensaje, envía tambien mail a ADMISITE

// Parametros: $formatomensaje es un array asociativo
//					$formatomensaje['formato']=FMT_TEXTO|FMT_TABLA
//					$formatomensaje['cantcols'] usado para colspan cuando es FMT_TABLA
//				$enviarmail: en caso que sea necesario enviar mail y esta variable esta seteada en true, envia mail al administrador
//						definida en la conexion

	static function MostrarMensaje($conexion,$texto,$ubicerror)
	{
		echo $texto;
	}

		
//----------------------------------------------------------------------------------------- 
// Valida el contenido del campo según tipovalidacion

	static function ValidarContenido($conexion,$campo,$tipovalidacion)
	{
		switch($tipovalidacion)
		{
			case "AlfanumericoPuro": // campo alfanumerico sin caracteres especiales
				if ( strspn(strtoupper($campo), '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ' ) != strlen($campo) ) 
					return false;
				break;
			case "Email": // campo alfanumerico con algunos caracteres especiales (emails)
				if(!@eregi("^[-\._a-z0-9]+@([a-z0-9]+[-\.]{1}){1,2}[a-z]{2,4}([\.]{1}[a-z]{2}){0,1}$",$campo))
					return false;
				break;
			case "FechaDDMMAAAA": // valida fecha en formato DD/MM/AAAA
				if(!@eregi("^[0-9]{2}/[0-9]{2}/[0-9]{4}$",$campo))
					return false;	
				list($dia, $mes, $anio) = split ('/', $campo); 
				if(!checkdate($mes, $dia, $anio))
					return false;
				break;
			case "NumericoEntero": // campo numerico entero
				if ( strspn($campo, '0123456789' ) != strlen($campo) ) 
					return false;
				break;

			default:
				FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Validación no definida.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
				return false;
				break;
		}
		return true;
	}
	
	static function ArmarLinkMD5Front($pagina,$parametros,&$get,&$md5)
	{
		$codigospagina["noticia.php"]="NOT04234dFE$";
		$codigospagina["pagina.php"]="PAG09634dF%$";
		$codigospagina["galeria.php"]="GAL8342bY&$";
		$codigospagina["album.php"]="ALB3512bY?$";
		$codigospagina["banner_click.php"]="BAN4242bZ?$";
		
		$get_array=array();
		$get="";
		$codigo="";
		$md5="";
		
		if(!isset($codigospagina[$pagina]) || count($parametros)<1)
			return false;
		
		foreach($parametros as $nombparam => $valparam)
		{
			$get_array[]=$nombparam."=".$valparam;
			$codigo.=$valparam;
		}
		
		$codigo.=$codigospagina[$pagina];
		
		$md5=md5($codigo);
	
		$get=implode("&amp;",$get_array);
		$get.="&amp;md5=".$md5;
		
		return true;
	}
	
	
	



static function ReemplazarTextoFechas($texto)
{
	// reemplazo meses  
	$texto = str_replace("January","Enero",$texto); 
	$texto = str_replace("February","Febrero",$texto); 
	$texto = str_replace("March","Marzo",$texto); 
	$texto = str_replace("April","Abril",$texto); 
	$texto = str_replace("May","Mayo",$texto); 
	$texto = str_replace("June","Junio",$texto); 
	$texto = str_replace("July","Julio",$texto); 
	$texto = str_replace("August","Agosto",$texto); 
	$texto = str_replace("September","Septiembre",$texto); 
	$texto = str_replace("October","Octubre",$texto); 
	$texto = str_replace("November","Noviembre",$texto); 
	$texto = str_replace("December","Diciembre",$texto); 

	// reemplazo meses cortos 
	$texto = str_replace("Jan","Ene",$texto); 
	$texto = str_replace("Apr","Abr",$texto); 
	$texto = str_replace("Aug","Ago",$texto); 
	$texto = str_replace("Sep","Sep",$texto); 
	$texto = str_replace("Oct","Oct",$texto); 
	$texto = str_replace("Nov","Nov",$texto); 
	$texto = str_replace("Dec","Dic",$texto); 

	// reemplazo días 
	$texto = str_replace("Monday","Lunes",$texto); 
	$texto = str_replace("Tuesday","Martes",$texto); 
	$texto = str_replace("Wednesday","Miércoles",$texto); 
	$texto = str_replace("Thursday","Jueves",$texto); 
	$texto = str_replace("Friday","Viernes",$texto); 
	$texto = str_replace("Saturday","Sábado",$texto); 
	$texto = str_replace("Sunday","Domingo",$texto); 
	
	// reemplazo dias cortos 
	$texto = str_replace("Mon","Lun",$texto); 
	$texto = str_replace("Tue","Mar",$texto); 
	$texto = str_replace("Wed","Mié",$texto); 
	$texto = str_replace("Thu","Jue",$texto); 
	$texto = str_replace("Fri","Vie",$texto); 
	$texto = str_replace("Sat","Sáb",$texto); 
	$texto = str_replace("Sun","Dom",$texto); 

	return $texto;
}




static function ReemplazarDiasSemanaBase($texto)
{
       // reemplazo dias cortos
       $texto = str_replace("Monday","Lu",$texto);
       $texto = str_replace("Tuesday","Ma",$texto);
       $texto = str_replace("Wednesday","Mi",$texto);
       $texto = str_replace("Thursday","Ju",$texto);
       $texto = str_replace("Friday","Vi",$texto);
       $texto = str_replace("Saturday","Sa",$texto);
       $texto = str_replace("Sunday","Do",$texto);
       
       // reemplazo dias cortos
       $texto = str_replace("Mon","Lu",$texto);
       $texto = str_replace("Tue","Ma",$texto);
       $texto = str_replace("Wed","Mi",$texto);
       $texto = str_replace("Thu","Ju",$texto);
       $texto = str_replace("Fri","Vi",$texto);
       $texto = str_replace("Sat","Sa",$texto);
       $texto = str_replace("Sun","Do",$texto);
       
       return $texto;
}

	static function ObtenerImgYoutube ($codigoYoutube,$size) 
	{
		if($codigoYoutube!='' && $size!='') {
			$videoth = 'http://img.youtube.com/vi/'.$codigoYoutube.'/'.$size.'.jpg';
			return $videoth;
		}
	}

	static function Error404()
	{
		header ("HTTP/1.0 404 Not Found"); 
		header ("Location: /errormsg/404.php"); 
	}
	
//----------------------------------------------------------------------------------------- 
// Arma un combo con la informacion del SP enviado

// Parametros:
// 		->spnombre y spparam se usan para armar el query
// 		->nomformulario es el nombre del formulario sobre el que está el combo
// 		->nomcombo es el nombre del combo
// 		->clavecombo y desccombo son campos del query con los que arma el combo
// 		->claveselec es el registro que hay que seleccionar
// 		->textolineauno es el texto del primer option del combo, cuando no hay nada seleccionado
// 		->regactual, en caso que se haya seleccionado un option se retorna la fila completa
// 		->seleccionado, en caso que se haya seleccionado un option del combo
//		->$filas, si es 1, es un combo, sino es una lista
//		->$onchange: la accion javascript al momento de seleccionar un elemento de la lista
//		->$style: estilo a aplicar sobre el combo
//		->$multiple: si se permite que en una lista se puedan elegir mas de un item
//		->$vertextocompleto: indica si se muestra el link abajo para ver texto completo

	static function ArmarCombo_SinSalto_BD($conexion,$spnombre,$spparam,$nomformulario,$nombrecombo,$clavecombo,$desccombo,$claveselec,$textolineauno,&$regactual,&$seleccionado,$filas=1,$onchange="",$style="width: 370px",$multiple=false,$vertextocompleto=true,$onDbClick="",$disabled=false,$class="",$tabindex="")
	{
		// Cargo los registros del query seleccionado
		if(!$conexion->ejecutarStoredProcedure($spnombre,$spparam,$resultado,$num_filas,$errno))
			return false;
		$txtdisabled = "";
		if ($disabled)
			$txtdisabled = "disabled='disabled'";

		$tabindextxt = "";
		if ($tabindex!="")
			$tabindextxt = " tabindex='".$tabindex."'";

		// Generación del combo, poniendo como primera linea $textolineauno
		echo "<select name='".$nombrecombo."' id='".$nombrecombo."' size='".$filas."' class='textoinput ".$class."' style='".$style."' onchange='".$onchange."'  ondblclick='".$onDbClick."' ".($multiple?"multiple ='multiple'":"")." ".$txtdisabled.$tabindextxt."  >\n";
		if($filas==1)
			echo "<option value=''>".FuncionesPHPLocal::HtmlspecialcharsBigtree($textolineauno,ENT_QUOTES)."</option>\n";

		$seleccionado=false;
		if($num_filas>0) 
		{
			// Recorre el cursor desde el principio, poniendo cada fila en una linea del combo
			while ($fila = $conexion->ObtenerSiguienteRegistro($resultado))
			{ 
				if($fila[$clavecombo]==$claveselec) 
				{
					echo "<option selected=\"selected\" value='".FuncionesPHPLocal::HtmlspecialcharsBigtree($fila[$clavecombo],ENT_QUOTES)."'> ".FuncionesPHPLocal::HtmlspecialcharsBigtree($fila[$desccombo],ENT_QUOTES)." </option>\n";
					$seleccionado=true;
					$regactual=$fila;
				}
				else
					echo "<option value='".FuncionesPHPLocal::HtmlspecialcharsBigtree($fila[$clavecombo],ENT_QUOTES)."'> ".FuncionesPHPLocal::HtmlspecialcharsBigtree($fila[$desccombo],ENT_QUOTES)." </option>\n";
			}
		}
	
		echo("</select>\n");
		/*
		if($filas==1 && $vertextocompleto)
			echo "<br /><a href='javascript:void(0)' onclick=\"javascript:popup('ventanatexto.php?textopopup='+".$nomformulario.".".$nombrecombo."[".$nomformulario.".".$nombrecombo.".selectedIndex].text,'ventanatexto',330,80,screen.availWidth-350,screen.availHeight-140,'yes')\" class='linkfondoblanco'><span class='textoaclaraciones'>Ver texto completo</span></a>\n";
		*/	
	}
	


	static function UrlFriendly($str)
	{
		
		$friendlyURL = htmlentities($str, ENT_COMPAT, "UTF-8", false); 
		$friendlyURL = preg_replace('/&([a-z]{1,2})(?:acute|lig|grave|ring|tilde|uml|cedil|caron);/i','\1',$friendlyURL);
		$friendlyURL = html_entity_decode($friendlyURL,ENT_COMPAT, "UTF-8"); 
		$friendlyURL = preg_replace('/[^a-z0-9-]+/i', '-', $friendlyURL);
		$friendlyURL = preg_replace('/-+/', '-', $friendlyURL);
		$friendlyURL = trim($friendlyURL, '-');
		$friendlyURL = strtolower($friendlyURL);
		return $friendlyURL;
		
	}


	static function js_array($arreglo) {
	  $temp=array();
	  foreach ($arreglo as $fila)
	  { 
		$temp[] = FuncionesPHPLocal::js_str($fila);
	  }
	  return '['.implode(',', $temp).']';
	}
	
	static function js_str($s) {
	  return '"'.addcslashes($s, "\0..\37\"\\").'"';
	}
	
	static function js_query($conexion,$resultado,$clave) {
	  $temp=array();
	  while ($fila = $conexion->ObtenerSiguienteRegistro($resultado))
	  { 
		$temp[] = FuncionesPHPLocal::js_str($fila[$clave]);
	  }
	  return '['.implode(',', $temp).']';
	}	



	static function ProcesarHtmlCuerpoEditor($conexion,$cuerpohtml)
	{
		cSepararHTML::ProcesarHTML($cuerpohtml,$partes);
		$html_generado="";
		foreach($partes as $partehtml)
		{
			if(is_array($partehtml))
			{
				if (class_exists($partehtml['Tipo']))
				{
					$ObjectClass = new $partehtml['Tipo']($conexion);
					if(!$ObjectClass->Mostrar($partehtml,$html_generado))
						return false;
					unset($ObjectClass);	
				}
			}else
				$html_generado .= $partehtml;
		}
		return $html_generado;
	}



	static function ObtenerValoresPaginado($total,$totalporpagina,$paginaactual,&$cantpaginas,&$paginasiguiente,&$paginaanterior)
	{
		$i=1;
		$paginicio = 0;
		while ($i<=$paginaactual)
		{
			$pagfin = $totalporpagina * $i;
			$paginicio = $pagfin - $totalporpagina;
			$i++;
		}
		
		$primerpagina = 1;
		$ultimapagina = ceil($total / $totalporpagina);
		if ($ultimapagina==0)
			$ultimapagina=1;
		
		
		$paginasiguiente = $paginaactual + 1;
		$paginaanterior = $paginaactual - 1;
		
		if ($paginasiguiente > $ultimapagina)
			$paginasiguiente = $ultimapagina;
		
		if ($paginaanterior < $primerpagina)
			$paginaanterior = $primerpagina;
		
		
		$cantpaginastotales = ceil($total/$totalporpagina);
		
		$iniciopaginador = 1;
		$cantpaginas = 10;
		if ($cantpaginas > $cantpaginastotales)
			$cantpaginas = $cantpaginastotales;
		
		if ($paginaactual>$cantpaginas)
			$iniciopaginador = $paginaactual - $cantpaginas + 1;
			
		return true;	
	}



	static function ArmarPaginadoFront($cantidadpaginacion,$element_count,$page,&$primera,&$ultima,&$numpages,&$current,&$TotalSiguiente,&$TotalVer)
	{
			
			$cant = $cantidadpaginacion;
			if ($element_count>$cantidadpaginacion)
				$TotalSiguiente = ($page*$cantidadpaginacion);
			else
				$TotalSiguiente = $element_count;
				
			if ($TotalSiguiente>$cantidadpaginacion)	
				$TotalVer = $TotalSiguiente - $cantidadpaginacion;
			elseif($element_count==0)
				$TotalVer = 0;
			else
				$TotalVer = 1;

			$current = ( $page - 1 ) * $cant;
						
			// Paginacion
			if ($current + $cant <= $element_count)
				$next_page = $page + 1;
			else
				$next_page = $page;
			
			if ($current - $cant >= 0)
				$prev_page = $page - 1;
			else
				$prev_page = $page;
			
			$mostrar = 4;
			$primera = $page - abs($mostrar / 2);
			$ultima = $page + abs($mostrar / 2);
			$numpages = ceil($element_count / $cant);
			
			
			if ($numpages < 1)
				$numpages = 1;
			
			if ($primera < 1) {
				$primera = 1;
				if ($numpages > $mostrar)
					$ultima = $mostrar;
				else
					$ultima = $numpages;
			}
			
			if ($ultima > $numpages) {
				$ultima = $numpages;
				$primera = $ultima - $mostrar;
				if ($primera < 1)
					$primera = 1;
			}
		
			return true;
	}


    static function EscapearCaracteres($oracion)
    {
		$oracion = strtolower($oracion);
		$caracteresescapeados = trim(str_replace(array('/'),'', $oracion));
		$caracteresescapeados = trim(str_replace(array("á","é","í","ó","ú","ñ"),array("a","e","i","o","u","n"), $caracteresescapeados));
		$caracteresescapeados = trim(str_replace(array("Á","É","Í","Ó","Ú","Ñ"),array("a","e","i","o","u","n"), $caracteresescapeados));
		return $caracteresescapeados;	
    }
	
	static function cortar_string ($string, $largo) { 
	   $marca = "<!--corte-->"; 
	
	   if (strlen($string) > $largo) { 
			
		   $string = wordwrap($string, $largo, $marca); 
		   $string = explode($marca, $string); 
		   $string = $string[0]." [...]"; 
	   } 
	   return $string; 
	
	}
	
	static function ConvertiraUtf8($arreglo)
	{
		$arregloenviar=array();
		foreach($arreglo as $clave=>$valor)
		{	
			if (is_array($valor))
				$arregloenviar[$clave] = FuncionesPHPLocal::ConvertiraUtf8($valor);
			else	
				$arregloenviar[$clave]=utf8_decode($valor);
		}
		

		return $arregloenviar;
	}

	static function DecodificarUtf8($arreglo)
	{
		$arregloenviar=array();

		foreach($arreglo as $clave=>$valor)
		{	
			if (is_array($valor))
				$arregloenviar[$clave] = FuncionesPHPLocal::DecodificarUtf8($valor);
			else	
				$arregloenviar[$clave]=utf8_encode($valor);
		}
		
		return $arregloenviar;
	}



	static function ArmarUrlFriendly($titulo,$prefijo,$codigo,$codigoprincipio = false)
	{
		
		$dominio="";
		$dominionuevo = FuncionesPHPLocal::EscapearCaracteres($titulo);
		$dominionuevo=preg_replace('/[^a-zA-Z0-9-_ ]/', '-', trim($dominionuevo));
		$dominionuevo=str_replace(' ', '-', trim($dominionuevo));

		$str = "a|al|algo|alguien|alguno|ante|bajo|buena|bueno|buenos|cada|com|con|de|del|desde|diferente|durante|e|el|en|entre|es|etc|excepto|hacia|hasta|i|la|las|los|mediante|mia|mias|mio|mios|mucho|nada|nadie|ningun|no|nuestra|nuestras|nuestro|nuestros|o|otras|otro|otros|para|pero|poco|por|que|salvo|se|segun|si|sin|sobre|su|suya|suyas|suyo|suyos|tal|te|tipo|todo|tras|tuya|tuyas|tuyo|tuyos|u|un|unas|unos|vuestra|vuestras|vuestro|vuestros";
		$patterns = array(
			'/[^A-Za-zñ0-9- \?!]/i',
			'/\b('.$str.')\b/ie'
		);	
		
		$string = preg_replace($patterns, '', $dominionuevo);
		if (strlen($string)>200)
			$string = substr($string,0,200);

		if ($codigo!="")
		{
			if ($codigoprincipio)
				$dominio= $codigo."-".$prefijo.$string;
			else
				$dominio=  $string."-".$prefijo.$codigo;
		}else
			$dominio = $prefijo.$string;
			
		$dominio = preg_replace('/\-\-+/', '-', $dominio);
		
		return $dominio;
	}

	static function HtmlspecialcharsBigtree($string,$flags="ENT_COMPAT | ENT_HTML401",$encoding="ISO8859-1",$double_encode=true)
	{
		return htmlspecialchars($string,$flags,$encoding,$double_encode);
		
	}
	
	static function ObtenerCodigoYoutube($url)
	{
		$pattern = "/(?:https?:\/\/)?(?:www\.)?youtu\.?be(?:\.com)?\/?.*(?:watch|embed)?(?:.*v=|v\/|\/)([\w-_]+)/i";
		preg_match($pattern, $url, $matches);
		return $matches[1];
	}

} // Fin clase FuncionesPHPLocal

?>