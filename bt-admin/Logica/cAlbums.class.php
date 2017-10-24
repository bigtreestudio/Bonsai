<?php  
include(DIR_CLASES_DB."cAlbums.db.php");

class cAlbums extends cAlbumsdb	
{
	protected $conexion;
	protected $formato;
	protected $activos;

	
//-----------------------------------------------------------------------------------------
//  LAS FUNCIONES QUE HASTA AHORA TIENEN ESTO SON:  
// 	ArregloHijos
//  TieneHijos
//  TraerDatosCategoria
//-----------------------------------------------------------------------------------------

	// Constructor de la clase
	function __construct($conexion,$activos=true,$formato=FMT_TEXTO){
		$this->conexion = &$conexion;
		$this->activos = $activos;
		$this->formato = $formato;
		parent::__construct(); 
    } 
	
	// Destructor de la clase
	function __destruct() {	
		parent::__destruct(); 
    } 	

	
	
	
//-----------------------------------------------------------------------------------------
//							 PUBLICAS	
//----------------------------------------------------------------------------------------- 


//------------------------------------------------------------------------------------------	
// Retorna en un arreglo con los datos de un album

// Parámetros de Entrada:
//		albumcod: album a buscar

// Retorna:
//		resultado= Arreglo con todos los datos de un album.
//		numfilas= cantidad de filas 
//		la función retorna true o false si se pudo ejecutar con éxito o no
	public function BuscarxCodigo($datos,&$resultado,&$numfilas)
	{
		if (!parent::BuscarxCodigo($datos,$resultado,$numfilas))
			return false;
		
		return true;
	} 
//------------------------------------------------------------------------------------------	
// Retorna en un arreglo con los datos de un album

// Parámetros de Entrada:
//		albumcod: album a buscar

// Retorna:
//		resultado= Arreglo con todos los datos de un album.
//		numfilas= cantidad de filas 
//		la función retorna true o false si se pudo ejecutar con éxito o no
	public function BuscarGaleriasAlbumsxalbumcod($datos,&$resultado,&$numfilas)
	{
		if (!parent::BuscarGaleriasAlbumsxalbumcod($datos,$resultado,$numfilas))
			return false;
		
		return true;
	}	
	
	
//------------------------------------------------------------------------------------------	
// Retorna en un arreglo con los datos del raiz de un album


// Retorna:
//		resultado= Arreglo con todos los datos de un album.
//		numfilas= cantidad de filas 
//		la función retorna true o false si se pudo ejecutar con éxito o no		

	public function BuscaAlbumRaiz(&$resultado,&$numfilas)
	{
		if (!parent::BuscaAlbumRaiz($resultado,$numfilas))
			return false;
		
		return true;
	}

//------------------------------------------------------------------------------------------	
// Retorna en un arreglo con los datos de un album

// Parámetros de Entrada:
//		albumsuperior: album superior a buscar

// Retorna:
//		resultado= Arreglo con todos los datos de un album.
//		numfilas= cantidad de filas 
//		la función retorna true o false si se pudo ejecutar con éxito o no	
	public function BuscarAlbumxAlbumSuperior($datos,&$resultado,&$numfilas)
	{
		if (!parent::BuscarAlbumxAlbumSuperior($datos,$resultado,$numfilas))
			return false;
		
		return true;
	}
//------------------------------------------------------------------------------------------	
// Retorna en un arreglo con los datos de un album

// Parámetros de Entrada:
//		catsuperior: album superior a buscar.Si vale "", entonces retorna el raiz del album

// Retorna:
//		resultado= Arreglo con todos los datos de un album.
//		numfilas= cantidad de filas 
//		la función retorna true o false si se pudo ejecutar con éxito o no	
	public function BuscarAvanzadaxAlbumSuperior($datos,&$resultado,&$numfilas)
	{
		$sparam=array(
			'xalbumsuperior'=> 0,
			'xalbumsuperior1'=> 0,
			'albumsuperior1'=> "",
			'xalbumestadocod'=> 0,
			'albumestadocod'=> "-1",
			'orderby'=> "albumorden ASC",
			'limit'=> ""
			);	
			
			
		if (isset($datos['albumsuperior']) && $datos['albumsuperior']!="")
		{
			$sparam['albumsuperior1'] = $datos['albumsuperior1'];
			$sparam['xalbumsuperior1'] = 1;
		}
		else
			$sparam['xalbumsuperior'] = 1;
			
		if (isset($datos['albumestadocod']) && $datos['albumestadocod']!="")
		{
			$sparam['palbumestadocod'] = $datos['albumestadocod'];
			$sparam['pxalbumestadocod'] = 1;
		}
		if (isset ($datos['orderby']) && $datos['orderby']!="")
			$sparam['orderby']= $datos['orderby'];
		if (isset ($datos['limit']) && $datos['limit']!="")
			$sparam['limit']= $datos['limit'];

	
		if (!parent::BuscarAvanzadaxAlbumSuperior($sparam,$resultado,$numfilas))
			return false;
		
		return true;
	}
//------------------------------------------------------------------------------------------	
// Retorna en un arreglo con los datos de un album

// Parámetros de Entrada:
//		albumsuperior: album superior a buscar.Si vale "", entonces retorna el raiz del album

// Retorna:
//		resultado= Arreglo con el maximo orden de un album.
//		numfilas= cantidad de filas 
//		la función retorna true o false si se pudo ejecutar con éxito o no	
	public function BuscarAlbumUltimoOrden($datos,&$resultado,&$numfilas)
	{
		$sparam=array(
			'xalbumsuperior'=> 0,
			'albumsuperior'=> "",
			);
			
		if (isset ($datos['albumsuperior']) && $datos['albumsuperior']!="NULL")
		{
			$sparam['albumsuperior']= $datos['albumsuperior'];
			$sparam['xalbumsuperior']= 1;
		}		
		if (!parent::BuscarAlbumUltimoOrden($sparam,$resultado,$numfilas))
			return false;
		
		return true;
	}	

//----------------------------------------------------------------------------------------- 
// Retorna un arreglo con todos los padres de una categoria

// Parámetros de Entrada:
//		albumcod: albumcod a buscar
//		nivelarbol= Se inicializa en 0.

// Retorna:
//		arrcat: devuelve el arreglo con todos los padres del album
//		nivelarbol: Devuelve el nivel en que se encuentra el album.
//		la función retorna true o false si se pudo ejecutar con éxito o no
 
 
 	public function ArregloPadres($albumcod,&$arrcat,&$nivelarbol)
	{
		if ($albumcod!="")
		{
			$datosalbum['albumcod'] = $albumcod;
			if (!$this->BuscarxCodigo($datosalbum,$resultado,$numfilas))
				return false;
			$result=true;
		
			if ($numfilas==0)
				$result=false;

			if ($result)
			{		
				while ($filasub=$this->conexion->ObtenerSiguienteRegistro($resultado))
				{
					$padre=$filasub['albumsuperior'];
					
					$arrcat[]=$filasub;
				}
				$nivelarbol++;
				if ($padre!="")
					if (!$this->ArregloPadres($padre,$arrcat,$nivelarbol))
						return false;

				$darvueltaarreglo=asort($arrcat);
			}
		}
		return true;
	} 


//----------------------------------------------------------------------------------------- 
// Retorna un arreglo con todos los hijos de un album

// Parámetros de Entrada:
//		albumcod: album a buscar
//		cantidadarreglo: Se inicializa en 0.

// Retorna:
//		arrcat: devuelve el arreglo con todos los hijos del categoria
//		errcat: el error en caso de que se produzca
//		cantidadarreglo: La cantidad total del arreglo.
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	public function ArregloHijos($albumcod,&$arrcat,&$cantidadarreglo)
	{

		$arrcat = array();
		if ($albumcod!="")
		{
			$datosalbum['albumsuperior'] = $albumcod;
			if (!$this->BuscarAlbumxAlbumSuperior($datosalbum,$resultado,$numfilas))
				return false;
			
			$result=true;
			if ($numfilas==0)
				$result=false;

			if ($result)
			{		
				while ($filasub=$this->conexion->ObtenerSiguienteRegistro($resultado))
				{
					$arrcat[$cantidadarreglo]=$filasub;
					$cantidadarreglo++;
				}
			}
		}
		else
		{
			if (!$this->BuscaAlbumRaiz($resultado,$numfilas))
				return false;
			
			while ($filasub=$this->conexion->ObtenerSiguienteRegistro($resultado))
			{
				$arrcat[$cantidadarreglo]=$filasub;
				$cantidadarreglo++;
			}
		}
	
		return true;
	} 




//----------------------------------------------------------------------------------------- 
// Retorna un ok si tiene hijos

// Parámetros de Entrada:
//		albumcod: album a buscar

// Retorna:
//		errcat: el error en caso de que se produzca
//		ok: devulve verdadero en caso de que tenga hijos, falso si no tiene.
//		la función retorna true o false si se pudo ejecutar con éxito o no

	
	public function TieneHijos($albumcod,&$ok)
	{
		
		$datosalb['albumsuperior'] = $albumcod;
		if (!$this->BuscarAlbumxAlbumSuperior($datosalb,$resultado,$numfilas))
		{	
			$ok = false;
			return false;
		}

		$result=true;
		if ($result)
		{		
			if ($filasub=$this->conexion->ObtenerSiguienteRegistro($resultado))
				$ok=true;
			else
				$ok=false;
		}
		return true;
	} 

	
//----------------------------------------------------------------------------------------- 
// Retorna la rama ascendente de un categoria con redirección

// Parámetros de Entrada:
//		albumcod: album a buscar

// Retorna:
//		jerarquia: un string con la ruta (href)
//		errcat: el error en caso de que se produzca
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function MostrarJerarquia($albumcod,&$jerarquia,&$nivel)
	{
		$i=1;
		$jerarquia="";
		$nivel=0;
		$arrjerarquia = array();
		if(!$this->ArregloPadres($albumcod,$arrjerarquia,$nivel))
			return false;

		if ($nivel!=0)
			$jerarquia.="<a href='gal_albums.php'>Inicio</a> &raquo; ";
		else
			$jerarquia.="<span class=\"bold\">Inicio</span>";
		
		foreach ($arrjerarquia as $clave=>$valor) 
		{
			
			if ($i!=$nivel)
			{ 
				FuncionesPHPLocal::ArmarLinkMD5("gal_albums.php",array("albumcod"=>$valor['albumcod']),$get,$md5);
				$jerarquia.="<a href='gal_albums.php?albumsuperior=";
				$jerarquia.= FuncionesPHPLocal::HtmlspecialcharsBigtree($valor['albumcod'],ENT_QUOTES);
				$jerarquia.="&md5=";
				$jerarquia.= FuncionesPHPLocal::HtmlspecialcharsBigtree($md5,ENT_QUOTES);
				$jerarquia.="' class='bold'>";
				$jerarquia.=$valor['albumtitulo']."</a> &raquo; ";
			}
			else
				$jerarquia.="<span class=\"bold\">".$valor['albumtitulo']."</span>";

			$i++;
		}
		$nivel=0;

		return true;
	} 
	
//----------------------------------------------------------------------------------------- 
// Retorna la rama ascendente de un album

// Parámetros de Entrada:
//		albumcod: albumgoria a buscar

// Retorna:
//		jerarquia: un string con la ruta
//		errcat: el error en caso de que se produzca
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	public function MostrarArbolJerarquia($albumcod,&$jerarquia,$estilos=true)
	{
		$arrcat=array();
		if(!$this->ArregloPadres($albumcod,$arrjerarquia,$nivel))
			return false;

		$i=1;
		$jerarquia="";
		foreach ($arrjerarquia as $clave=>$valor) 
		{
			if ($i!=$nivel)
				$jerarquia.= FuncionesPHPLocal::HtmlspecialcharsBigtree($valor['albumtitulo'],ENT_QUOTES)." &raquo; ";
			else
			{
				if($estilos)
					$jerarquia.="<span class='negrita'>". FuncionesPHPLocal::HtmlspecialcharsBigtree($valor['albumtitulo'],ENT_QUOTES)."</span>";	
				else
					$jerarquia.= FuncionesPHPLocal::HtmlspecialcharsBigtree($valor['albumtitulo'],ENT_QUOTES);	
			}
			$i++;
		}
		$nivel=0;
		
		return true;
	} 

//----------------------------------------------------------------------------------------- 
//ABM DE ALBUMS.-
//----------------------------------------------------------------------------------------- 

//----------------------------------------------------------------------------------------- 

//----------------------------------------------------------------------------------------- 
// Modifica los datos de un album

// Parámetros de Entrada:

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	public function ModificarAlbums($datos)
	{
		
		if (!$this->_ValidarDatosModificar($datos))
			return false;
		
		if ($datos['albumsuperior']=="")
			$datos['albumsuperior']="NULL";
		if(!$this->Modificar($datos))
			return false;

		$datos["albumdominio"] = FuncionesPHPLocal::EscapearCaracteres($datos["albumtitulo"]);
		$datos["albumdominio"]=preg_replace('/[^a-zA-Z0-9-_ ]/', '-', trim($datos["albumdominio"]));
		$datos["albumdominio"]="/album/".str_replace(' ', '-', trim($datos["albumdominio"]))."_a".$datos["albumcod"];
	 
		if(!$this->GenerarDominio($datos))
			return false;	
			
		if (!$this->Publicar($datos))
				return false;			
		return true;
	} 
	
//----------------------------------------------------------------------------------------- 
// Inserta un album nuevo.

// Parámetros de Entrada:

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	public function InsertarAlbums($datos,&$codigoinsertado)
	{

		if (!$this->_ValidarDatosAlta($datos))
			return false;
		
		if ($datos['albumsuperior']=="")
			$datos['albumsuperior']="NULL";
		$this->ObtenerProximoOrden($datos,$proxorden);	
		$datos['albumorden']= $proxorden;
		if(!$this->Insertar($datos,$codigoinsertado))
			return false;
		
		$datos["albumdominio"] = FuncionesPHPLocal::EscapearCaracteres($datos["albumtitulo"]);
		$datos["albumdominio"]=preg_replace('/[^a-zA-Z0-9-_ ]/', '-', trim($datos["albumdominio"]));
		$datos["albumdominio"]="/album/".str_replace(' ', '-', trim($datos["albumdominio"]))."_a".$codigoinsertado;
	
		$datos["albumcod"]=$codigoinsertado;
		if(!$this->GenerarDominio($datos))
			return false;
			
		if (!$this->Publicar($datos))
				return false;				
		return true;
	} 


//----------------------------------------------------------------------------------------- 
//FIN DE ABM DE CATEGORIAS.-
//----------------------------------------------------------------------------------------- 


//-----------------------------------------------------------------------------------------
//							 PRIVADAS	
//----------------------------------------------------------------------------------------- 

//----------------------------------------------------------------------------------------- 
// Retorna True o false, de acuerdo si existe algún album de la misma jerarquía que contenga el
// mismo nombre, tambien si alguno que esté pendiente de modificación.

// Parámetros de Entrada:
//		$padre= Codigo del album padre.
//		Se pasa el padre pq busco solo los nombres que existen con el mismo padre
//		nombre= nombre del album que deseo buscar.


// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	public function _VerificarNombre ($nombre,$padre,&$datosencontrados)
	{
		$datosencontrados = array();
		if ($padre=="NULL") 
		{
			$datos['albumtitulo'] = $nombre;
			if (!parent::BuscaAlbumNombreRaiz($datos,$resultado,$numfilas))
				return false;
		}
		else
		{
			$datos['albumtitulo'] = $nombre;
			$datos['albumsuperior'] = $padre;
			if (!parent::BuscaAlbumTituloxAlbumSuperior($datos,$resultado,$numfilas))
				return false;
		}	
		
		if ($numfilas!=0)
		{	
			$datosencontrados = $this->conexion->ObtenerSiguienteRegistro($resultado);
			return false;
		}
		
		return true;
	}

//----------------------------------------------------------------------------------------- 
// Retorna true o false si algunos de los campos esta vacio

// Parámetros de Entrada:
//		albumtitulo = nombre del album.
//      albumsuperior = si existe el cogido del album: 		 

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function _ValidarDatosVacios($datos)
	{
		if ($datos['albumtitulo']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar un nombre del album. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		if ($datos['albumsuperior']!="")
		{
			$datoscat['albumcod'] = $datos['albumsuperior'];
			if (!$this->BuscarxCodigo($datoscat,$resultado,$numfilas))
				return false;
			if ($numfilas!=1)
			{	
				FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar un nombre del album. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
				return false;
			}
		}
		return true;
	}
	
	public function Publicar($datos)
	{
		//if (!$this->PublicarListadoJson())
			//return false;
		if (!$this->PublicarJsonxCodigo($datos))
			return false;
		return true;
	}
	
	public function GuardarDatosJson($nombrearchivo,$carpeta,$array)
	{
		$datosJson = FuncionesPHPLocal::DecodificarUtf8($array);
		$jsonData = json_encode($datosJson);
		if(!is_dir($carpeta)){
			@mkdir($carpeta);
		}
		if(!FuncionesPHPLocal::GuardarArchivo($carpeta,$jsonData,$nombrearchivo.".json"))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_INF,"Error, al generar el archivo json. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		return true;
	}



	public function EliminarDatosJson($nombrearchivo,$carpeta)
	{
		if(file_exists($carpeta.$nombrearchivo.".json"))
		{
			unlink($carpeta.$nombrearchivo.".json");
		}
		return true;
	}



	public function PublicarListadoJson()
	{
		$nombrearchivo = "galerias";
		$carpeta = PUBLICA;
		if(!$this->GerenarArrayDatosJsonListado($array))
			return false;
		if(count($array)>0)
		{
			if(!$this->GuardarDatosJson($nombrearchivo,$carpeta,$array))
				return false;
		}
		else
		{
			if(!$this->EliminarDatosJson($nombrearchivo,$carpeta))
				return false;
		}
		return true;
	}



	public function GerenarArrayDatosJsonListado(&$array)
	{
		$array = array();
		$datos['galeriaorden'] = ACTIVO;
		if(!$this->BuscarAvanzadaxGaleria($datos,$resultados,$numfilas))
			return false;
		if($numfilas>0)
		{
			while($fila = $this->conexion->ObtenerSiguienteRegistro($resultados))
			{
				$array[$fila['galeriacod']] = $fila;
				$oGaleriasMultimedia = new cGaleriasMultimedia($this->conexion,$this->formato);
				$array[$fila['galeriacod']]['multimedias']['fotos'] = array();
				$datosmultimedia['multimediaconjuntocod'] = FOTOS;
				$datosmultimedia['galeriacod'] = $fila['galeriacod'];
				if(!$oGaleriasMultimedia->BuscarMultimediaFotosxCodigoGaleria($datosmultimedia,$resultadoFotos,$numfilasFotos))
					return false;
				if($numfilasFotos>0)
				{
					while($filaFotos= $this->conexion->ObtenerSiguienteRegistro($resultadoFotos))
					{
						if(!$this->GenerarDatosMultimedia($filaFotos,$array[$fila['galeriacod']]['multimedias'],'multimediacod','gal'))
							return false;
					}
				}

				$array[$fila['galeriacod']]['multimedias']['audios'] = array();
				$datosmultimedia['multimediaconjuntocod'] = AUDIOS;
				$datosmultimedia['galeriacod'] = $fila['galeriacod'];
				if(!$oGaleriasMultimedia->BuscarMultimediaAudiosxCodigoGaleria($datosmultimedia,$resultadoAudios,$numfilasAudios))
					return false;
				if($numfilasAudios>0)
				{
					while($filaAudios= $this->conexion->ObtenerSiguienteRegistro($resultadoAudios))
					{
						if(!$this->GenerarDatosMultimedia($filaAudios,$array[$fila['galeriacod']]['multimedias'],'multimediacod','gal'))
							return false;
					}
				}

				$array[$fila['galeriacod']]['multimedias']['videos'] = array();
				$datosmultimedia['multimediaconjuntocod'] = VIDEOS;
				$datosmultimedia['galeriacod'] = $fila['galeriacod'];
				if(!$oGaleriasMultimedia->BuscarMultimediaVideosxCodigoGaleria($datosmultimedia,$resultadoVideos,$numfilasVideos))
					return false;
				if($numfilasVideos>0)
				{
					while($filaVideos= $this->conexion->ObtenerSiguienteRegistro($resultadoVideos))
					{
						if(!$this->GenerarDatosMultimedia($filaVideos,$array[$fila['galeriacod']]['multimedias'],'multimediacod','gal'))
							return false;
					}
				}
				
				if(isset($array[$fila['galeriacod']]['multimedias']['fotos']) && count($array[$fila['galeriacod']]['multimedias']['fotos'])==0)
					unset($array[$fila['galeriacod']]['multimedias']['fotos']);
				if(isset($array[$fila['galeriacod']]['multimedias']['videos']) && count($array[$fila['galeriacod']]['multimedias']['videos'])==0)
					unset($array[$fila['galeriacod']]['multimedias']['videos']);
				if(isset($array[$fila['galeriacod']]['multimedias']['audios']) && count($array[$fila['galeriacod']]['multimedias']['audios'])==0)
					unset($array[$fila['galeriacod']]['multimedias']['audios']);

			}
			
			
		}
		return true;
	}



	public function PublicarJsonxCodigo($datos)
	{
		$nombrearchivo = "albums_".$datos['albumcod'];
		$carpeta = PUBLICA;
		if(!$this->GerenarArrayDatosJsonxCodigo($datos,$array))
			return false;
		//print_r($array);	
		if(count($array)>0)
		{
			if(!$this->GuardarDatosJson($nombrearchivo,$carpeta,$array))
				return false;
		}
		else
		{
			if(!$this->EliminarDatosJson($nombrearchivo,$carpeta))
				return false;
		}
		return true;
	}



	public function GerenarArrayDatosJsonxCodigo($datos,&$array)
	{
		$array = array();
		if(!$this->BuscarxCodigo($datos,$resultados,$numfilas))
			return false;
		if($numfilas==1)
		{
			while($fila = $this->conexion->ObtenerSiguienteRegistro($resultados))
			{
				$array[$fila['albumcod']] = $fila;
				$oAlbumsGalerias = new cAlbumsGalerias($this->conexion,$this->formato);
				$datosgaleria['galeriaestadocod'] = ACTIVO;
				$datosgaleria['albumcod'] = $fila['albumcod'];
				$array[$fila['albumcod']]['galerias'] = array();
				if(!$oAlbumsGalerias->BusquedaAvanzada($datosgaleria,$resultadoGalerias,$numfilasGalerias))
					return false;
				if($numfilasGalerias>0)
				{
					while($filaGalerias= $this->conexion->ObtenerSiguienteRegistro($resultadoGalerias))
					{
						$array[$fila['albumcod']]['galerias'][$filaGalerias['galeriacod']]['albumcod'] = $filaGalerias['albumcod'];
						$array[$fila['albumcod']]['galerias'][$filaGalerias['galeriacod']]['galeriacod'] = $filaGalerias['galeriacod'];
						$array[$fila['albumcod']]['galerias'][$filaGalerias['galeriacod']]['albumgaleriaorden'] = $filaGalerias['albumgaleriaorden'];
						$array[$fila['albumcod']]['galerias'][$filaGalerias['galeriacod']]['albumtitulo'] = $filaGalerias['albumtitulo'];
						$array[$fila['albumcod']]['galerias'][$filaGalerias['galeriacod']]['galeriatitulo'] = $filaGalerias['galeriatitulo'];
						$array[$fila['albumcod']]['galerias'][$filaGalerias['galeriacod']]['galeriadesc'] = $filaGalerias['galeriadesc'];
						$array[$fila['albumcod']]['galerias'][$filaGalerias['galeriacod']]['galeriadominio'] = $filaGalerias['galeriadominio'];
						$array[$fila['albumcod']]['galerias'][$filaGalerias['galeriacod']]['galeriadominio'] = $filaGalerias['galeriadominio'];
						$array[$fila['albumcod']]['galerias'][$filaGalerias['galeriacod']]['multimediaconjuntocod'] = $filaGalerias['multimediaconjuntocod'];
						
						
						if(!$this->GenerarDatosMultimedia($filaGalerias,$array[$fila['albumcod']]['galerias'][$filaGalerias['galeriacod']]['multimedias'],'multimediacod',''))
							return false;
					}
				}

				
				if(isset($array[$fila['albumcod']]['galerias']) && count($array[$fila['albumcod']]['galerias'])==0)
					unset($array[$fila['albumcod']]['galerias']);
				;

			}
							
		}
		return true;
	}
	
	public function GenerarDatosMultimedia($fila,&$arraymultimedia,$id,$prefijo)
	{
		$arraytemp = array();
		switch ($fila['multimediaconjuntocod'])
		{
			case FOTOS:
					$arraytemp['codigo'] = $fila[$id];
					$arraytemp['conjunto'] = $fila['multimediaconjuntocod'];
					$arraytemp['tipo'] = $fila['multimediatipocod'];
					$arraytemp['titulo'] = $fila[$prefijo.'multimediatitulo'];
					$arraytemp['descripcion'] = $fila[$prefijo.'multimediadesc'];
					$arraytemp['nombre_archivo'] = $fila['multimedianombre'];
					$arraytemp['orden'] = $fila[$prefijo.'multimediaorden'];
					$arraytemp['idexterno'] = $fila['multimediaidexterno'];
					$arraytemp['url'] = CARPETA_SERVIDOR_MULTIMEDIA_NOTICIAS.CARPETA_SERVIDOR_MULTIMEDIA_ORIGINALES.$fila['multimediaubic'];
					if(isset($fila['previewubic']) && $fila['previewubic']!="")
					$arraytemp['url_preview'] = CARPETA_SERVIDOR_MULTIMEDIA_NOTICIAS.CARPETA_SERVIDOR_MULTIMEDIA_ORIGINALES.$fila['previewubic'];
					$arraymultimedia['fotos'][$fila[$id]] = $arraytemp;
				break;	
			case VIDEOS:
					$arraytemp['codigo'] = $fila[$id];
					$arraytemp['conjunto'] = $fila['multimediaconjuntocod'];
					$arraytemp['tipo'] = $fila['multimediatipocod'];
					$arraytemp['titulo'] = $fila[$prefijo.'multimediatitulo'];
					$arraytemp['descripcion'] = $fila[$prefijo.'multimediadesc'];
					$arraytemp['nombre_archivo'] = $fila['multimedianombre'];
					$arraytemp['orden'] = $fila[$prefijo.'multimediaorden'];
					$arraytemp['idexterno'] = $fila['multimediaidexterno'];
					if(isset($fila['multimediaidexterno']) && $fila['multimediaidexterno']!="")
						$arraytemp['url'] = "";	
					else
						$arraytemp['url'] = CARPETA_SERVIDOR_MULTIMEDIA_NOTICIAS."videos/".$fila['multimediaubic'];
					if(isset($fila['previewubic']) && $fila['previewubic']!="")
					$arraytemp['url_preview'] = CARPETA_SERVIDOR_MULTIMEDIA_NOTICIAS.CARPETA_SERVIDOR_MULTIMEDIA_ORIGINALES.$fila['previewubic'];
					$arraymultimedia['videos'][$fila[$id]] = $arraytemp;
				break;
			case AUDIOS:
					$arraytemp['codigo'] = $fila[$id];
					$arraytemp['conjunto'] = $fila['multimediaconjuntocod'];
					$arraytemp['tipo'] = $fila['multimediatipocod'];
					$arraytemp['titulo'] = $fila[$prefijo.'multimediatitulo'];
					$arraytemp['descripcion'] = $fila[$prefijo.'multimediadesc'];
					$arraytemp['nombre_archivo'] = $fila['multimedianombre'];
					$arraytemp['orden'] = $fila[$prefijo.'multimediaorden'];
					$arraytemp['idexterno'] = $fila['multimediaidexterno'];
					if(isset($fila['multimediaidexterno']) && $fila['multimediaidexterno']!="")
						$arraytemp['url'] = "";
					else
						$arraytemp['url'] = CARPETA_SERVIDOR_MULTIMEDIA_NOTICIAS."audios/".$fila['multimediaubic'];	
					if(isset($fila['previewubic']) && $fila['previewubic']!="")
					$arraytemp['url_preview'] = CARPETA_SERVIDOR_MULTIMEDIA_NOTICIAS.CARPETA_SERVIDOR_MULTIMEDIA_ORIGINALES.$fila['previewubic'];
					$arraymultimedia['audios'][$fila[$id]] = $arraytemp;
				break;
		}
		return true;
	}

//----------------------------------------------------------------------------------------- 
// Retorna true o false al dar de alta si algunos de los campos esta vacio o si exite otro album con ese nombre

// Parámetros de Entrada:
//		albumtitulo = nombre del album.
//      albumsuperior = si existe el cogido del album: 		 

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no
	public function _ValidarDatosAlta($datos)
	{
		if (!$this->_ValidarDatosVacios($datos))
			return false;

		if(!$this->_VerificarNombre($datos['albumtitulo'],$datos['albumsuperior'],$datosnombre))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_INF,"Ya existe un album con ese nombre. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;
		}

		return true;
	}

//----------------------------------------------------------------------------------------- 
// Retorna true o false al modificar si algunos de los campos esta vacio o si exite otro album con ese nombre

// Parámetros de Entrada:
//		albumtitulo = nombre del album.
//      albumsuperior = si existe el cogido del album: 		 

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no
	public function _ValidarDatosModificar($datos)
	{
		if (!$this->_ValidarDatosVacios($datos))
			return false;

		if(!$this->_VerificarNombre($datos['albumtitulo'],$datos['albumsuperior'],$datosnombre))
		{
			if ($datosnombre['albumcod']!=$datos['albumcod'])
			{	
				FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_INF,"Ya existe un album con ese nombre. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
				return false;
			}
		}


		return true;
	}
//----------------------------------------------------------------------------------------- 
// Retorna true o false si pudo eliminar el album

// Parámetros de Entrada:
//		albumcod = codigo del album a eliminar.

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function EliminarAlbums($datos)
	{

		if (!$this->PuedeEliminarAlbum($datos,true))
			return false;
			
		if (!parent::Eliminar($datos))
			return false;
		
		if (!$this->Publicar($datos))
				return false;	
			
		return true;
	}

//----------------------------------------------------------------------------------------- 
// Retorna true o false si el album tiene hijos

// Parámetros de Entrada:
//		albumcod = codigo del album a eliminar.

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no
	public function PuedeEliminarAlbum($datos,$mostrarmsg=false)
	{

		if (!$this->ArregloHijos($datos['albumcod'],$arrcat,$cantidadarreglo))
			return false;
	
		if ($cantidadarreglo>0)
		{
			if ($mostrarmsg)
				FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_INF,"El album contiene subalbumes asociadas. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;
		}
		
		if (!$this->BuscarxCodigo($datos,$resultado,$numfilas))
			return false;
			
		if ($numfilas!=1)
		{
			if ($mostrarmsg)
				FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_INF,"El album no existe. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;
		}	
		
		if (!$this->BuscarGaleriasAlbumsxalbumcod($datos,$resultadoalbum,$numfilasalbum))
			return false;
			
		if($numfilasalbum>0){
			return false;
			}

						
		return true;
	}



//----------------------------------------------------------------------------------------- 
// Retorna proxorden. proximo orden del album

// Parámetros de Entrada:
//		albumcod = codigo del album.
//      albumestadocod = nuevo estado del album

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no	
	private function ObtenerProximoOrden($datos,&$proxorden)
	{
		$proxorden = 0;
		if (!$this->BuscarAlbumUltimoOrden($datos,$resultado,$numfilas))
			return false;
			
		if ($numfilas!=0)
		{
			$datos = $this->conexion->ObtenerSiguienteRegistro($resultado);
			$proxorden = $datos['maximo'] + 1;
		}
		return true;
	}
//----------------------------------------------------------------------------------------- 
//Retorna true o false si pudo cambiar el orden de los albumes

// Parámetros de Entrada:
//		albumorden = orden de los albums.

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no		
	public function ModificarOrden($datos)
	{
		$arregloalbum = explode(",",$datos['orden']);
		
		$datosmodif['albumorden'] = 1;
		foreach ($arregloalbum as $albumcod)
		{
			$datosmodif['albumcod'] = $albumcod;
			if (!parent::ModificarOrden($datosmodif))
					return false;
			if (!$this->Publicar($datosmodif))
				return false;		
			$datosmodif['albumorden']++;
		}
		return true;
	}
	

}// FIN CLASE

?>