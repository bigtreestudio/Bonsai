<?php  
include(DIR_CLASES_DB."cAlbumsGalerias.db.php");

class cAlbumsGalerias extends cAlbumsGaleriasdb	
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



//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 

// Parámetros de Entrada:
//		datos: arreglo de datos
//			albumcod = codigo del album
//          galeriacod = codigo de la galeria

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function BuscarAlbumGaleriaxCodigo($datos,&$resultado,&$numfilas)
	{
		if (!parent::BuscarAlbumGaleriaxCodigo ($datos,$resultado,$numfilas))
			return false;
		return true;	
	}
	
//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 

// Parámetros de Entrada:
//		datos: arreglo de datos
//			albumcod = codigo del album
//          galeriatitulo = titulo de la galeria

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function BusquedaAvanzada($datos,&$resultado,&$numfilas)
	{
					
		$sparam=array(
			'xalbumcod'=> 0,
			'albumcod'=> "",
			'xalbumtitulo'=> 0,
			'albumtitulo'=> "",
			'xgaleriatitulo'=> 0,
			'galeriatitulo'=> "",
			'xgaleriaestadocod'=> 0,
			'galeriaestadocod'=> "-1",
			'orderby'=> "albumgaleriaorden ASC",
			'limit'=> ""
		);	
			
		if (isset ($datos['albumcod']) && $datos['albumcod']!="")
		{
			$sparam['albumcod']= $datos['albumcod'];
			$sparam['xalbumcod']= 1;
		}	
		if (isset ($datos['albumtitulo']) && $datos['albumtitulo']!="")
		{
			$sparam['albumtitulo']= $datos['albumtitulo'];
			$sparam['xalbumtitulo']= 1;
		}	
		if (isset ($datos['galeriatitulo']) && $datos['galeriatitulo']!="")
		{
			$sparam['galeriatitulo']= $datos['galeriatitulo'];
			$sparam['xgaleriatitulo']= 1;
		}
		
		if (isset ($datos['galeriaestadocod']) && $datos['galeriaestadocod']!="")
		{
			$sparam['galeriaestadocod']= $datos['galeriaestadocod'];
			$sparam['xgaleriaestadocod']= 1;
		}
				
		if (isset ($datos['orderby']) && $datos['orderby']!="")
			$sparam['orderby']= $datos['orderby'];
			
		if (isset ($datos['limit']) && $datos['limit']!="")
			$sparam['limit']= $datos['limit'];
		
		if (!parent::BusquedaAvanzada ($sparam,$resultado,$numfilas))
			return false;
		return true;	
	}

//------------------------------------------------------------------------------------------	
// Retorna en un arreglo con los datos de un album

// Parámetros de Entrada:

// Retorna:
//		resultado= Arreglo con el maximo orden del album de galeria.
//		numfilas= cantidad de filas 
//		la función retorna true o false si se pudo ejecutar con éxito o no	
	public function BuscarAlbumUltimoOrden($datos,&$resultado,&$numfilas)
	{
		$sparam=array(
			);
		if (!parent::BuscarAlbumUltimoOrden($sparam,$resultado,$numfilas))
			return false;
		
		return true;
	}	

//----------------------------------------------------------------------------------------- 

//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 

// Insertar un nuevo banner

// Parámetros de Entrada:
//		datos: array asociativo con los datos a agregar
//			albumcod = codigo del album
//			galeriacod = codigo de la galeria
//			albumgaleriaorden = orden de el album de la galeria

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function InsertarAlbumsGalerias($datos)
	{
		if(!$this->BuscarAlbumGaleriaxCodigo($datos,$resultado,$numfilas))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, la galeria ya existe. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;
		}
		
		if($numfilas>0)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, la galeria ya existe. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;
		}
		$this->ObtenerProximoOrden($datos,$proxorden);
		$datos['albumgaleriaorden']= $proxorden;
		if (!parent::Insertar ($datos))
			return false;
			
		$oAlbums = new cAlbums($this->conexion,$this->formato);	
		if (!$oAlbums->Publicar($datos))
				return false;
					
		return true;	
	}
	
//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 

// Eliminarun banner cambiando el estado

// Parámetros de Entrada:
//		datos: arreglo de datos
//			albumcod = codigo del album
//			galeriacod = codigo de la galeria


// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function EliminarAlbumsGalerias($datos)
	{	
		
		if(!$this->BuscarAlbumGaleriaxCodigo($datos,$resultado,$numfilas))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error no exite la galeria ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;
		}
		
		if($numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, la galeria no existe. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;
		}
		if (!parent::Eliminar($datos))
			return false;
			
		$oAlbums = new cAlbums($this->conexion,$this->formato);	
		if (!$oAlbums->Publicar($datos))
				return false;	
		
		return true;	
	}	

//----------------------------------------------------------------------------------------- 

	
//----------------------------------------------------------------------------------------- 
//Retorna true o false si pudo cambiar el orden de los albumes

// Parámetros de Entrada:
//		albumcod = codigo del album
//		galeriacod = codigo de la galeria
//		albumgaleriaorden = orden de los albums.

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no		
	public function ModificarOrden($datos)
	{
		$oAlbums = new cAlbums($this->conexion,$this->formato);	
		$arregloalbumgal = explode(",",$datos['orden']);
		$datosmodif['albumcod'] = $datos['albumcod'];
		$datosmodif['albumgaleriaorden'] = 1;
		foreach ($arregloalbumgal as $galeriacod)
		{
			$datosmodif['galeriacod'] = $galeriacod;
			if (!parent::ModificarOrden($datosmodif))
					return false;
					
			if (!$oAlbums->Publicar($datosmodif))
				return false;		
			$datosmodif['albumgaleriaorden']++;
		}
		
		return true;
	}
	
//----------------------------------------------------------------------------------------- 
// Retorna proxorden. proximo orden del album

// Parámetros de Entrada:
//		albumcod = codigo del album.


// Retorna:
//		proxorden= el proximo mayor orden del album de galeria.
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


}// FIN CLASE

?>