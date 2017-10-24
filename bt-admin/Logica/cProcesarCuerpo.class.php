<? 

class cProcesarCuerpo 
{
	protected $conexion;
	protected $formato;
	protected $tamanios;

	
// CLASE cPaginas 
// EN CASO DE QUE LA VARIABLE ACTIVOS SE ENCUENTRE EN LA LLAMADA A LA CLASE,
// LAS FUNCIONES SE TRABAJARAN CON SOLO  LOS DATOS DE LAS PAGINAS QUE SE ENCUENTREN
// EN ESTADO EDICION O PUBLICADA

//-----------------------------------------------------------------------------------------
//  LAS FUNCIONES QUE HASTA AHORA TIENEN ESTO SON:  
// 	ArregloHijos
//  TieneHijos
//-----------------------------------------------------------------------------------------

	// Constructor de la clase
	function __construct($conexion,$formato=FMT_TEXTO){
		$this->conexion = &$conexion;
		$this->formato = $formato;
		$this->tamanios = array();
		$this->Init();
    } 
	
	// Destructor de la clase
	function __destruct() {	
    } 	

	
	
	
//-----------------------------------------------------------------------------------------
//							 PUBLICAS	
//----------------------------------------------------------------------------------------- 

	public function Init()
	{
		$this->tamanios['anchoI'] = "400";
		$this->tamanios['anchoD'] = "400";
		$this->tamanios['anchoC'] = "1090";
		
		$this->tamanios['videoI'] = "400";
		$this->tamanios['videoD'] = "400";
		$this->tamanios['videoC'] = "1090";
		$this->tamanios['videoAlto'] = "500";
		
	}

	public function SetearTamanios($tamanio = array())
	{
		if (isset($tamanio['fotoizq']))
			$this->tamanios['anchoI'] = $tamanio['fotoizq'];
		if (isset($tamanio['fotoder']))
			$this->tamanios['anchoD'] = $tamanio['fotoder'];
		if (isset($tamanio['fotocen']))
			$this->tamanios['anchoC'] = $tamanio['fotocen'];
		
		if (isset($tamanio['videoizq']))
			$this->tamanios['videoI'] = $tamanio['videoizq'];
		if (isset($tamanio['videoder']))
			$this->tamanios['videoD'] = $tamanio['videoder'];
		if (isset($tamanio['videocen']))
			$this->tamanios['videoC'] = $tamanio['videocen'];
		if (isset($tamanio['videoalto']))
			$this->tamanios['videoAlto'] = $tamanio['videoalto'];
		
	}
	


	public function ProcesarImagenesCuerpo($datosImagen,$cuerpo)
	{
		preg_match_all('@\@foto[a-zA-Z]\@@si', $cuerpo, $encontrados);

		$oMultimedia = new cMultimedia($this->conexion,"");
		if(count($encontrados[0])>0)
		{
			for ($i = 0; $i < count($encontrados[0]); $i++) 
			{
				$patron = "";
				if ($i <= count($encontrados[0])) 
				{
					if(array_key_exists($i,$datosImagen))
					{
						if ($encontrados[0][$i] == "@fotoC@") 
						{
						   $patron = '@\@fotoC\@@si';
						   $bloque = '<figure class="imagefull">';
							list($anchoImg, $alto, $tipo, $atributos) = getimagesize (CARPETA_SERVIDOR_MULTIMEDIA_FISICA.$datosImagen[$i]['ubicacion']);
							$ancho = $this->tamanios["anchoC"];
							if ($anchoImg<$ancho )
								$ancho = $anchoImg;
							$imagen = DOMINIO_SERVIDOR_MULTIMEDIA.Multimedia::GetImagenStatic($ancho, 0, $datosImagen[$i]['ubicacion']);
							$bloque .= '<img src="'.$imagen.'" class="img-responsive" alt="'.htmlentities($datosImagen[$i]['epigrafe']).'" title="'.htmlentities($datosImagen[$i]['epigrafe']).'" />';
						
							if (trim($datosImagen[$i]['epigrafe']) != "") {
								$bloque .= '<figcaption>' .( $datosImagen[$i]['epigrafe']). '</<figcaption>';
							}
							$bloque .= '</figure>';
						}
						if ($encontrados[0][$i] == "@fotoI@") 
						{
						   $patron = '@\@fotoI\@@si';
						   $bloque = '<figure class="imageizq">';
							list($anchoImg, $alto, $tipo, $atributos) = getimagesize (CARPETA_SERVIDOR_MULTIMEDIA_FISICA.$datosImagen[$i]['ubicacion']);
							$ancho = $this->tamanios["anchoI"];
							if ($anchoImg<$ancho)
								$ancho = $anchoImg;
							$imagen = DOMINIO_SERVIDOR_MULTIMEDIA.Multimedia::GetImagenStatic($ancho, 0, $datosImagen[$i]['ubicacion']);
							$bloque .= '<img src="'.$imagen.'" class="img-responsive" alt="'.htmlentities($datosImagen[$i]['epigrafe']).'" title="'.htmlentities($datosImagen[$i]['epigrafe']).'" />';
						
							if (trim($datosImagen[$i]['epigrafe']) != "") {
								$bloque .= '<figcaption>' .( $datosImagen[$i]['epigrafe']). '</figcaption>';
							}
							$bloque .= '</figure>';
						}
						if ($encontrados[0][$i] == "@fotoD@") 
						{
						   $patron = '@\@fotoD\@@si';
						   $bloque = '<figure class="imageder">';
							list($anchoImg, $alto, $tipo, $atributos) = getimagesize (CARPETA_SERVIDOR_MULTIMEDIA_FISICA.$datosImagen[$i]['ubicacion']);
							$ancho = $this->tamanios["anchoD"];
							if ($anchoImg<$ancho)
								$ancho = $anchoImg;
							$imagen = DOMINIO_SERVIDOR_MULTIMEDIA.Multimedia::GetImagenStatic($ancho, 0, $datosImagen[$i]['ubicacion']);
							$bloque .= '<img src="'.$imagen.'" class="img-responsive" alt="'.htmlentities($datosImagen[$i]['epigrafe']).'" title="'.htmlentities($datosImagen[$i]['epigrafe']).'" />';
							if (trim($datosImagen[$i]['epigrafe']) != "") {
								$bloque .= '<figcaption>' .( $datosImagen[$i]['epigrafe']). '</figcaption>';
							}
							$bloque .= '</figure>';
						}

						if ($patron != "") 
						{
							$cuerpo = preg_replace($patron, $bloque, $cuerpo, 1);
						}
					}
				}
			}
		}
		
		//si queda algo lo saco
	    return $cuerpo;
		
		
	}


	public function ProcesarVideosCuerpo($datosMultimedia,$cuerpo)
	{
		preg_match_all('@\@video[a-zA-Z]\@@si', $cuerpo, $encontrados);


		if(count($encontrados[0])>0)
		{
			for ($i = 0; $i < count($encontrados[0]); $i++) 
			{
				$patron = "";
				if ($i <= count($encontrados[0])) 
				{
					if(array_key_exists($i,$datosMultimedia))
					{
						if ($encontrados[0][$i] == "@videoC@") 
						{
						   $patron = '@\@videoC\@@si';
							$ancho = $this->tamanios["videoC"];
							$alto = $this->tamanios['videoAlto'];
						   $bloque = '<div class="videofull">';
						    $bloque .= Multimedia::VerVideo($this->conexion,$datosMultimedia[$i]['multimediacod'],$ancho,$alto,true);
							$bloque .= '</div>';
						}
						if ($encontrados[0][$i] == "@videoI@") 
						{
						   $patron = '@\@videoI\@@si';
							$ancho = $this->tamanios["videoI"];
							$alto = round($this->tamanios["videoI"]*$this->tamanios['videoAlto']/$this->tamanios["videoC"]);
						   $bloque = '<div class="videoizq">';
						    $bloque .= Multimedia::VerVideo($this->conexion,$datosMultimedia[$i]['multimediacod'],$ancho,$alto,true);
							$bloque .= '</div>';
						}
						if ($encontrados[0][$i] == "@videoD@") 
						{
						   $patron = '@\@videoD\@@si';
							$ancho = $this->tamanios["videoD"];
							$alto = round($this->tamanios["videoD"]*$this->tamanios['videoAlto']/$this->tamanios["videoC"]);
						   $bloque = '<div class="videoder">';
						    $bloque .= Multimedia::VerVideo($this->conexion,$datosMultimedia[$i]['multimediacod'],$ancho,$alto,true);
							$bloque .= '</div>';
						}

						if ($patron != "") 
						{
							$cuerpo = preg_replace($patron, $bloque, $cuerpo, 1);
						}
					}
				}
			}
		}
		
		//si queda algo lo saco
	    return $cuerpo;
		
		
	}



	public function reemplazarFrasesWide($cuerpo) {
		$patron = "/\(FW\)(.*)\(FW\)/";
		preg_match_all($patron, $cuerpo, $encontrados);
		for ($i = 0; $i < count($encontrados[0]); $i++) {
			$patron2 = "/\(A\)(.*)\(A\)/";
			preg_match_all($patron2, $encontrados[1][$i], $autor);
	
			if (isset($autor[1][0]) && $autor[1][0] <> "")
				$h3 = "<blockquote>" . str_ireplace($autor[0][0], "", $encontrados[1][$i]) . ($autor[1][0] <> "" ? "<br /><span>" . $autor[1][0] . "</span>" : "");
			else
				$h3 = "<blockquote>" . $encontrados[1][$i];
	
			$h3 .= '</blockquote>';
	
			$cuerpo = str_ireplace($encontrados[0][$i], $h3, $cuerpo);
		}
		return $cuerpo;
	}

	
	function ProcesarAtajos($cuerpo) 
	{
		//lo que busoc en el cuerpo
		$patron = "/\(atajo\)(.*?)\(atajofin\)/s";
		preg_match_all($patron, $cuerpo, $encontrados);
	
		//los que encuentro
		$arregloAtajos = $encontrados[1];
		$arregloTagModificar = $encontrados[0];

		for ($i = 0; $i < count($arregloAtajos); $i++) 
		{
				
				/*proceso la cantida de columnas*/
				$patronCols = "/\(Col\)(.*)\(Col\)/s";
				preg_match_all($patronCols, $arregloAtajos[$i], $encontradosCols);
				$columnas = $encontradosCols[1][0];
				
				//proceso la clase del atajo
				$patronDesc = "/\(Cl\)(.*)\(Cl\)/s";
				preg_match_all($patronDesc, $arregloAtajos[$i], $encontradosClass);
				$Class = $encontradosClass[1][0];
				
				//proceso la clase del atajo
				$patronDesc = "/\(Ic\)(.*)\(Ic\)/s";
				preg_match_all($patronDesc, $arregloAtajos[$i], $encontradosIconos);
				$Iconos = $encontradosIconos[1][0];
				
				//proceso la clase del atajo
				$patronDesc = "/\(Tex\)(.*)\(Tex\)/s";
				preg_match_all($patronDesc, $arregloAtajos[$i], $encontradosTextos);
				$Texto = $encontradosTextos[1][0];
				
				//proceso la clase del atajo
				$patronDesc = "/\(Link\)(.*)\(Link\)/s";
				preg_match_all($patronDesc, $arregloAtajos[$i], $encontradosLink);
				$Link = $encontradosLink[1][0];
				
				$html ='<div class="col-md-'.$columnas.'  col-sm-'.($columnas+1).'">';
				$html .='<a class="shortcut" href="'.$Link.'">';
				$html.='<span class="'.$Class.'">';
				$html.='<span class="glyphicon '.$Iconos.'"></span>';
				$html.='</span>';
				$html.='<h3>'.$Texto.'</h3>';
				$html.='</a></div>';
			
			//remplazo por cambios

			$cuerpo = str_ireplace($arregloTagModificar[$i], $html, $cuerpo);
		
		}
		return $cuerpo;
	}
	
	/*boton de descarga a un archivo - documento*/
	function ProcesarBotones($datosArchivos,$cuerpo) 
	{
		//lo que busoc en el cuerpo
		$patron = "/\(DWBUT\)(.*?)\(DWBUT\)/s";
		preg_match_all($patron, $cuerpo, $encontrados);
	
		//los que encuentro
		$arregloBotones = $encontrados[1];
		$arregloTagModificar = $encontrados[0];

		for ($i = 0; $i < count($arregloBotones); $i++) 
		{
			
			$html ='<a type="button" class="btn btn-primary" title=" Ir a '.$arregloBotones[$i].'" target="_blank" href="'.$datosArchivos[$i]["url"].'">'.$arregloBotones[$i].'</a>';
			
			//remplazo por cambios

			$cuerpo = str_ireplace($arregloTagModificar[$i], $html, $cuerpo);
		
		}
		return $cuerpo;
	}
	
	/*boton de descarga a un Link*/
	function ProcesarBotonesLinks($cuerpo) 
	{
		//lo que busoc en el cuerpo
		$patron = "/\(BUT\)(.*?)\(BUT\)/s";
		preg_match_all($patron, $cuerpo, $encontrados);
	
		//los que encuentro
		$arregloBotones = $encontrados[1];
		$arregloTagModificar = $encontrados[0];

		for ($i = 0; $i < count($arregloBotones); $i++) 
		{

			/*proceso la cantida de columnas*/
			$patronLinks = "/\(Link\)(.*)\(Link\)/s";
			preg_match_all($patronLinks, $arregloBotones[$i], $encontradosLinks);
			$link = $encontradosLinks[1][0];
			
			//proceso el texto del boton
			$patronDesc = "/\(Tex\)(.*)\(Tex\)/s";
			preg_match_all($patronDesc, $arregloBotones[$i], $encontradosTextos);
			$Texto = $encontradosTextos[1][0];
				
			$html ='<a type="button" class="btn btn-primary" title=" Ir a '.$Texto.'" target="_blank" href="'.$link.'">'.$Texto.'</a>';
			
			//remplazo por cambios

			$cuerpo = str_ireplace($arregloTagModificar[$i], $html, $cuerpo);
		
		}
		return $cuerpo;
	}
	
	function ProcesarGaleriaMosaico($datosFotos,$cuerpo) 
	{
		//lo que busoc en el cuerpo
		preg_match_all('@\@galleriaFM\@@si', $cuerpo, $encontrados);
		
		//los que encuentro
		//$arregloGaleria = $encontrados[1];
		$arregloTagModificar = $encontrados[0];
		if (count($datosFotos)>0 && count($encontrados)>0)
		{
			$html="<section><div class='row row-gallery'>";
			for ($i = 0; $i < count($datosFotos); $i++) 
			{
				list($anchoImg, $alto, $tipo, $atributos) = getimagesize (CARPETA_SERVIDOR_MULTIMEDIA_FISICA.$datosFotos[$i]['ubicacion']);
				$ancho = 960;
				//$alto=640;
				if ($anchoImg<$ancho)
					$ancho = $anchoImg;
				$imagen = DOMINIO_SERVIDOR_MULTIMEDIA.Multimedia::GetImagenStatic($ancho, 0, $datosFotos[$i]['ubicacion']);
				$imagenthumb = DOMINIO_SERVIDOR_MULTIMEDIA.Multimedia::GetImagenStatic(524, 348, $datosFotos[$i]['ubicacion'],1,true);
				$html .='<a class="col-xs-6 col-sm-4 col-md-3" href="'.$imagen.'" title="'.$datosFotos[$i]["epigrafe"].'">
					  <img class="img-responsive thumbnail" src="'.$imagenthumb.'">
					  <span class="info">
						<h3>'.$datosFotos[$i]["epigrafe"].'</h3>
					  </span>
					</a>';
			
			}
			$html.="</div>";
			$html.='
				<div class="modal modal-carousel" id="galleryModal" role="dialog">
					<div class="modal-dialog modal-lg">
					  <div class="modal-content">
						<button class="close" type="button" data-dismiss="modal">&times;</button>
						<div id="gallery" class="carousel">
						  <div class="carousel-inner" role="listbox"></div>
						  <a class="left carousel-control" href="#gallery" role="button" data-slide="prev">
							<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
							<span class="sr-only">Previous</span>
						  </a>
						  <a class="right carousel-control" href="#gallery" role="button" data-slide="next">
							<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
							<span class="sr-only">Next</span>
						  </a>
						</div>
					  </div>
					</div>
				 </div>
			</section>';
			//remplazo por cambios
			$cuerpo = str_ireplace($encontrados[0], $html, $cuerpo);
		}
		return $cuerpo;
	}
	

}// FIN CLASE

?>