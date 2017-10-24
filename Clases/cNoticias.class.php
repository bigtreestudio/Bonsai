<?
include(DIR_DATA."noticiasData.php");
//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 
// Clase con la lógica para el manejo de las noticias 

class cNoticias 
{
	protected $conexion;

	
	// Constructor de la clase
	public function __construct($conexion){
		$this->conexion = &$conexion;
    } 
	
	// Destructor de la clase
	public function __destruct() {	

    } 	



	public function BuscarNoticia($datos,$folder)
	{
		
		$file = PUBLICA."json/noticias/".$folder."/noticia_".$datos['noticiacod'].".json";
		if (file_exists($file))
		{
			$archivo = file_get_contents($file);
			$datosnoticia = FuncionesPHPLocal::DecodificarUtf8(json_decode($archivo,1));
			return $datosnoticia;
		}else
		{
			$spnombre="sel_not_noticias_publicadas_xcodigo";
			$sparam=array(
				'pnoticiacod'=> $datos['noticiacod']
				);
			if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
			{
				FuncionesPHPLocal::MostrarMensaje($this->conexion,"Error al buscar la noticia.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__));
				return false;
			}
			if ($numfilas!=1)
				return false;
				
			$datosnoticia = $this->conexion->ObtenerSiguienteRegistro($resultado);
			
			//armo array de fotos, videos, etc
			
			//devuelvo el array
			return $datosnoticia;	
		}
	
	}
	

	public function BuscarNoticiaPrevisualizacion($datos)
	{
		$spnombre="sel_not_noticias_xcodigo";
		$sparam=array(
			'pnoticiacod'=> $datos['noticiacod']
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,"Error al buscar la noticia.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__));
			return false;
		}
		if ($numfilas!=1)
			return false;
			
		$datosnoticia = $this->conexion->ObtenerSiguienteRegistro($resultado);
			
		//armo array de fotos, videos, etc
			
		//devuelvo el array
		return $datosnoticia;		
	}
	



	public function CargarRelacionadas(&$oNoticiasData)
	{
		$spnombre="sel_not_noticias_relacionadas_xcodigonoticia";
		$sparam=array(
			'pnoticiacod'=> $oNoticiasData->getCodigo()
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,"Error al buscar las relacionadas.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__));
			return false;
		}

		$relacionadas = array();	
		while ($fila = $this->conexion->ObtenerSiguienteRegistro($resultado))	
		{
			$oNoticiaRelacionada = new NoticiasData();
			$this->SetData($oNoticiaRelacionada,$fila);
			$oNoticiaRelacionada->setNoticiaImportante($fila['noticiaimportante']);
			$relacionadas[] = $oNoticiaRelacionada;
			unset($oNoticiaRelacionada);
		}
		$oNoticiasData->setRelacionadas($relacionadas);
		
		return true;	
	}
	
	
	public function CargarImagenes(&$oNoticiasData,$cantidad=NULL)
	{
			
		$spnombre="sel_not_noticias_mul_multimedia_xnoticiacod";
		$sparam=array(
			'pnoticiacod'=> $oNoticiasData->getCodigo(),
			'pmultimediaconjuntocod'=> FOTOS,
			'plimit'=>""
			);
			
		if ($cantidad!=NULL)
			$sparam['plimit'] = "Limit 0,".$cantidad;
			
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,"Error al buscar las imagenes de la noticia.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__));
			return false;
		}
		$imagenes = array();	
		$oMultimediaService = new cMultimedia($this->conexion);
		while ($fila = $this->conexion->ObtenerSiguienteRegistro($resultado))	
		{
			$oMultimedia = new MultimediaData();
			$oMultimediaService->SetData($oMultimedia,$fila);
			$imagenes[] = $oMultimedia;
			unset($oMultimedia);
		}
		unset($oMultimediaService);
		$oNoticiasData->setImagenes($imagenes);


		return true;	
	}




	public function CargarGalerias(&$oNoticiasData)
	{
		$spnombre="sel_not_noticias_gal_galerias_xnoticiacod";
		$sparam=array(
			'pnoticiacod'=> $oNoticiasData->getCodigo(),
			'pgaleriaestadocod'=> 10
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,"Error al buscar las galerias relacionadas.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__));
			return false;
		}
			

		$ArregloGalerias = array();	
		$oGaleriaService = new cGalerias($this->conexion);
		$oMultimediaService = new cMultimedia($this->conexion);
		while ($fila = $this->conexion->ObtenerSiguienteRegistro($resultado))	
		{	
			$oGaleriasData = new GaleriasData();
			$oMultimediaData = new MultimediaData();
			$oGaleriaService->SetData($oGaleriasData,$fila);
			$oMultimediaService->SetData($oMultimediaData,$fila);
			$oGaleriasData->SetMultimedia($oMultimediaData);
			$ArregloGalerias[] = $oGaleriasData;
			unset($oMultimediaData);
			unset($oGaleriasData);
		}
		unset($oGaleriaService);
		unset($oMultimediaService);
		$oNoticiasData->setGalerias($ArregloGalerias);
		
		return true;	
	}


	public function getUltimasNoticias($datos)
	{
		$spnombre="sel_not_noticias_publicadas_busqueda";
		$sparam=array(
			'pxcatcod'=>0,
			'pcatcod'=> "-1",
			'porderby'=> "noticiafecha DESC",
			'plimit'=> ""
			);
		if (isset($datos['catcod']) && $datos['catcod']!="")
		{	
			$sparam['pxcatcod'] = 1;
			$sparam['pcatcod'] = $datos['catcod'];
		}
		if (isset($datos['orderby']) && $datos['orderby']!="")
			$sparam['porderby'] = $datos['orderby'];
		if (isset($datos['limit']) && $datos['limit']!="")
			$sparam['plimit'] = $datos['limit'];
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,"Error al buscar las galerias relacionadas.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__));
			return false;
		}
		
		$UltimasNoticias = array();	
		while ($fila = $this->conexion->ObtenerSiguienteRegistro($resultado))	
		{	
			$oNoticia = new NoticiasData();
			$this->SetData($oNoticia,$fila);
			$UltimasNoticias[] = $oNoticia;
			unset($oNoticia);
		}

		return $UltimasNoticias;	
	}





	/*--------------------------------------------------------------------------------------------------*/
	/*FUNCIONES DE BUSQUEDA DE NOTICIAS*/
	/*--------------------------------------------------------------------------------------------------*/

	public function BusquedaNoticiasporTag($termino,&$CantidadTotal,$limit="")
	{
		$spnombre="sel_not_noticias_publicadas_xtag";
		$sparam=array(
			'pfields'=> "COUNT(a.noticiacod) as total",
			'pnoticiatag'=> $termino,
			'porderby'=> "a.noticiafecha DESC",
			'plimit'=> ""
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,"Error al buscar las noticias.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__));
			return false;
		}
		
		$CantidadTotal = 0;
		if ($numfilas>0)
		{
			$datosTotales = $this->conexion->ObtenerSiguienteRegistro($resultado);
			$CantidadTotal = $datosTotales['total'];	
		}
		$sparam['pfields'] = "a.noticiatitulo, a.noticiacopete,  a.catdominio, a.noticiadominio, a.noticiafecha, a.noticiatitulocorto, a.noticiavolanta, a.noticiatags";
		if ($limit!="")
			$sparam['plimit'] = $limit;

		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,"Error al buscar las noticias.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__));
			return false;
		}

		$ArregloNoticiasTag = array();	
		while ($fila = $this->conexion->ObtenerSiguienteRegistro($resultado))	
		{	
			$oNoticia = new NoticiasData();
			$this->SetData($oNoticia,$fila);
			$ArregloNoticiasTag[] = $oNoticia;
			unset($oNoticia);
		}

		return $ArregloNoticiasTag;	
		
	}



	public function BusquedaNoticias($termino,&$CantidadTotal,$limit="")
	{
		if(strlen(trim($termino))<3)
			return $this->BusquedaNoticiasLike($termino,$limit="");
		
		$palabras = preg_split('/ /',trim($termino));
		$palabrasarreglo = implode(',',$palabras);
		$consultapalabra = " >".trim($termino)." <".trim($termino)."*";
		foreach($palabras as $valor) { 
				$consultapalabra .= " >".$valor." <".$valor."*";
		} 	
		$cantidadTotal = $this->BusquedaCantidadNoticias($consultapalabra);

		$spnombre="sel_not_noticias_publicadas_xbusqueda";
		$sparam=array(
			'pterm'=> $consultapalabra,
			'plimit'=> ""
			);
		if ($limit!="")
			$sparam['plimit'] = $limit;

		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,"Error al buscar las noticias.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__));
			return false;
		}

		$ArregloBusqueda = array();	
		while ($fila = $this->conexion->ObtenerSiguienteRegistro($resultado))	
		{	
			$oNoticia = new NoticiasData();
			$this->SetData($oNoticia,$fila);
			$ArregloBusqueda[] = $oNoticia;
			unset($oNoticia);
		}

		return $ArregloBusqueda;	
		
	}
	


	private function BusquedaCantidadNoticias($termino,$limit="")
	{
		$spnombre="sel_not_noticias_publicadas_xbusqueda_cantidad";
		$sparam=array(
			'pterm'=> $termino
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,"Error al buscar la cantida de noticias.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__));
			return false;
		}
		$cantidad = 0;
		if ($numfilas>0)
		{
			$datosTotales = $this->conexion->ObtenerSiguienteRegistro($resultado);
			$cantidad = $datosTotales['total'];	
		}

		return $cantidad;	
		
	}
	

	private function BusquedaNoticiasLike($termino,$limit="")
	{
		$spnombre="sel_not_noticias_publicadas_xbusqueda_like";
		$sparam=array(
			'pfields'=> "COUNT(a.noticiacod) as total",
			'pterm'=> $termino,
			'plimit'=> ""
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,"Error al buscar las noticias.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__));
			return false;
		}
		
		$busqueda['cantidadTotal'] = 0;
		if ($numfilas>0)
		{
			$datosTotales = $this->conexion->ObtenerSiguienteRegistro($resultado);
			$busqueda['cantidadTotal'] = $datosTotales['total'];	
		}
		$sparam['pfields'] = "DISTINCT(a.noticiacod) as noticiacod, a.catdominio, a.catnom, a.catcod, a.noticiatitulo, a.noticiacopete, a.noticiadominio, a.noticiafecha, a.noticiatitulocorto, a.noticiavolanta";
		if ($limit!="")
			$sparam['plimit'] = $limit;

		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,"Error al buscar las noticias.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__));
			return false;
		}

		$ArregloBusqueda = array();	
		while ($fila = $this->conexion->ObtenerSiguienteRegistro($resultado))	
		{	
			$oNoticia = new NoticiasData();
			$this->SetData($oNoticia,$fila);
			$ArregloBusqueda[] = $oNoticia;
			unset($oNoticia);
		}


		return $ArregloBusqueda;	
		
	}
	

	public function CargarVideos(&$oNoticiasData,$cantidad=NULL)
	{
			
		$spnombre="sel_not_noticias_mul_multimedia_xnoticiacod";
		$sparam=array(
			'pnoticiacod'=> $oNoticiasData->getCodigo(),
			'pmultimediaconjuntocod'=> VIDEOS,
			'plimit'=>""
			);
			
		if ($cantidad!=NULL)
			$sparam['plimit'] = "Limit 0,".$cantidad;
			
			
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,"Error al buscar los videos de la noticia.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__));
			return false;
		}
		

		$videos = array();	
		$oMultimediaService = new cMultimedia($this->conexion);
		while ($fila = $this->conexion->ObtenerSiguienteRegistro($resultado))	
		{
			$oMultimedia = new MultimediaData();
			$oMultimediaService->SetData($oMultimedia,$fila);
			$videos[] = $oMultimedia;
			unset($oMultimedia);
		}

		unset($oMultimediaService);
		$oNoticiasData->setVideos($videos);


		return true;	
	}


	public function CargarAudios(&$oNoticiasData,$cantidad=NULL)
	{
			
		$spnombre="sel_not_noticias_mul_multimedia_xnoticiacod";
		$sparam=array(
			'pnoticiacod'=> $oNoticiasData->getCodigo(),
			'pmultimediaconjuntocod'=> AUDIOS,
			'plimit'=>""
			);
			
		if ($cantidad!=NULL)
			$sparam['plimit'] = "Limit 0,".$cantidad;
			
			
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,"Error al buscar los audios de la noticia.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__));
			return false;
		}
		

		$audios = array();	
		$oMultimediaService = new cMultimedia($this->conexion);
		while ($fila = $this->conexion->ObtenerSiguienteRegistro($resultado))	
		{
			$oMultimedia = new MultimediaData();
			$oMultimediaService->SetData($oMultimedia,$fila);
			$audios[] = $oMultimedia;
			unset($oMultimedia);
		}

		unset($oMultimediaService);
		$oNoticiasData->setAudios($audios);


		return true;	
	}



	public function BusquedaNoticiasporTema($datos,&$CantidadTotal,&$resultado,$limit="")
	{
		$spnombre="sel_not_noticias_publicadas_xtema";
		$sparam=array(
			'pfields'=> "COUNT(notpub.noticiacod) as total",
			'ptemacod'=> $datos['temacod'],
			'porderby'=> "notpub.noticiafecha DESC",
			'plimit'=> ""
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,"Error al buscar las noticias.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__));
			return false;
		}
		
		$CantidadTotal = 0;
		if ($numfilas>0)
		{
			$datosTotales = $this->conexion->ObtenerSiguienteRegistro($resultado);
			$CantidadTotal = $datosTotales['total'];	
		}
		$sparam['pfields'] = "notpub.*, c.multimediadesc, c.multimedianombre, c.multimediaubic, mc.multimediacatcarpeta, mulVid.multimediaidexterno, mulVid.multimediatipocod AS tipovideo";
		if ($limit!="")
			$sparam['plimit'] = $limit;

		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,"Error al buscar las noticias.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__));
			return false;
		}

		return true;	
		
	}
			
}//FIN CLASE





?>