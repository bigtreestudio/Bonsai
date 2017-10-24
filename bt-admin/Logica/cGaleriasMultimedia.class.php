<?php 
//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 
// Clase con la lógica para el manejo de las noticias relacionadas

include(DIR_CLASES_DB."cGaleriasMultimedia.db.php");

class cGaleriasMultimedia extends cGaleriasMultimediadb	
{


	protected $conexion;
	protected $formato;
	
	
	// Constructor de la clase
	function __construct($conexion,$formato=FMT_TEXTO){
		$this->conexion = &$conexion;
		$this->formato = $formato;
		parent::__construct(); 
    } 
	
	// Destructor de la clase
	function __destruct() {	
		parent::__destruct(); 
    } 	

//----------------------------------------------------------------------------------------- 
// Retorna una consulta con los datos del multimedia por galeria y multimedia

// Parámetros de Entrada:
//		datos: arreglo de datos
//			galeriacod = codigo de la galeria (la que se quiere buscar los relacionados)
//			multimediacod = codigo del archivo multimedia (la que se quiere buscar los relacionados)

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function BuscarMultimediaxCodigoGaleriaxCodigoMultimedia($datos,&$resultado,&$numfilas)
	{
		if (!parent::BuscarMultimediaxCodigoGaleriaxCodigoMultimedia($datos,$resultado,$numfilas))
			return false;

		return true;
	}
	

	

//----------------------------------------------------------------------------------------- 
// Retorna una consulta con los archivos de fotos de multimedia de una galeria

// Parámetros de Entrada:
//		datos: arreglo de datos
//			galeriacod = codigo de la galeria (la que se quiere buscar los relacionados)

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function BuscarMultimediaFotosxCodigoGaleria($datos,&$resultado,&$numfilas)
	{
		$datos['multimediaconjuntocod'] = FOTOS;
		if (!parent::BuscarMultimediaxCodigoGaleriaxMultimediaConjunto($datos,$resultado,$numfilas))
			return false;

		return true;
	}

//----------------------------------------------------------------------------------------- 
// Retorna una consulta con los archivos de videos de multimedia de una galeria

// Parámetros de Entrada:
//		datos: arreglo de datos
//			galeriacod = codigo de la galerai (la que se quiere buscar los relacionados)

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function BuscarMultimediaVideosxCodigoGaleria($datos,&$resultado,&$numfilas)
	{
		$datos['multimediaconjuntocod'] = VIDEOS;
		if (!parent::BuscarMultimediaxCodigoGaleriaxMultimediaConjunto($datos,$resultado,$numfilas))
			return false;

		return true;
	}


	public function BuscarMultimediaxCodigoGaleria($datos,&$resultado,&$numfilas)
	{
		if (!parent::BuscarMultimediaxCodigoGaleriaxMultimediaConjunto($datos,$resultado,$numfilas))
			return false;

		return true;
	}
//----------------------------------------------------------------------------------------- 
// Retorna una consulta con los archivos de audios de multimedia de una galeria

// Parámetros de Entrada:
//		datos: arreglo de datos
//			galeriacod = codigo de la galerai (la que se quiere buscar los relacionados)

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function BuscarMultimediaAudiosxCodigoGaleria($datos,&$resultado,&$numfilas)
	{
		$datos['multimediaconjuntocod'] = AUDIOS;
		if (!parent::BuscarMultimediaxCodigoGaleriaxMultimediaConjunto($datos,$resultado,$numfilas))
			return false;

		return true;
	}
	
	
//----------------------------------------------------------------------------------------- 
// Genera un nuevo archivo multimedia y lo inserta como imagen de la galeria

// Parámetros de Entrada:
//		datos: arreglo de datos
//			galeriacod = codigo de la galeria
//				FOTOS


// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function InsertarImagen($datos)
	{
		$oGaleria = new cGalerias($this->conexion,$this->formato);
		if(!$oGaleria->BuscarxCodigo($datos,$resultado,$numfilas))
			return false;
		$datosGaleria = $this->conexion->ObtenerSiguienteRegistro($resultado);	

		$datos['multimediaconjuntocod'] = FOTOS;
		$datos['multimediadesc'] = "NULL";
		$datos['multimedianombre'] = $datos['multimedianombre'];
		$datos['multimediaubic'] = $datos['multimediaubic'];
		$datos['multimediacatcod'] = 1;
		$datos['multimedia_titulo'] = $datos['multimedia_titulo_prop'] = $datos['galmultimediatitulo'] = $datosGaleria['galeriatitulo'];
		$datos['multimedia_desc'] = $datos['galmultimediadesc'] = $datos['multimedia_desc_prop'] = "";
		$oMultimedia = new cMultimedia($this->conexion,CARPNOTICIAS,$this->formato);
		if(!$oMultimedia->InsertarImagen($datos,$multimediacod))
			return false;
		
		$datos['multimediacod'] = $multimediacod;
		$datos['multimediacodpreview'] = "NULL";
		if (!$this->Insertar($datos))
			return false;
		
		if(!$oGaleria->Publicar($datos))
			return false;
		
								
		return true;	
	}


//----------------------------------------------------------------------------------------- 
// Genera un nuevo archivo multimedia y lo inserta como video de la noticia

// Parámetros de Entrada:
//		datos: arreglo de datos
//			galeriacod = codigo de la galeria
//				VIDEOS


// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function InsertarVideo($datos)
	{
		
		$oGaleria = new cGalerias($this->conexion,$this->formato);
		if(!$oGaleria->BuscarxCodigo($datos,$resultado,$numfilas))
			return false;
		$datosGaleria = $this->conexion->ObtenerSiguienteRegistro($resultado);	
			
		$datos['multimedia_titulo'] = $datos['multimedia_titulo_prop'] = $datos['galmultimediatitulo'] = $datosGaleria['galeriatitulo'];
		$datos['multimedia_desc'] = $datos['galmultimediadesc'] = $datos['multimedia_desc_prop'] = "";
		$datos['catcodvideoprop'] = "";
		$oMultimedia = new cMultimediaGeneral($this->conexion,CARPNOTICIAS,$this->formato);
		if (!$oMultimedia->InsertarVideo($datos,$multimediacod))
			return false;
		
		//$datos['notmultimediamuestrahome'] = 0;
		$datos['multimediacodpreview'] = "NULL";
		$datos['multimediacod'] = $multimediacod;
		if (!$this->Insertar($datos))
			return false;
		
		if(!$oGaleria->Publicar($datos))
			return false;
				
		return true;	
	}
	

//----------------------------------------------------------------------------------------- 
// Genera un nuevo archivo multimedia y lo inserta como audio de la galeria

// Parámetros de Entrada:
//		datos: arreglo de datos
//			galeriacod = codigo de la galeria
//				FOTOS


// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function InsertarAudio($datos)
	{
		$oGaleria = new cGalerias($this->conexion,$this->formato);
		if(!$oGaleria->BuscarxCodigo($datos,$resultado,$numfilas))
			return false;
		$datosGaleria = $this->conexion->ObtenerSiguienteRegistro($resultado);	
		
		$datos['multimediaconjuntocod'] = AUDIOS;
		$datos['multimediadesc'] = "NULL";
		$datos['multimedianombre'] = $datos['multimedianombre'];
		$datos['multimediaubic'] = $datos['multimediaubic'];
		$datos['multimediacatcod'] = 1;
		$oMultimedia = new cMultimedia($this->conexion,CARPNOTICIAS."audios/",$this->formato);
		
		if(!$oMultimedia->InsertarAudio($datos,$multimediacod))
			return false;
		
		$datos['multimediacodpreview'] = "NULL";
		$datos['multimediacod'] = $multimediacod;
		if (!$this->Insertar($datos))
			return false;
			
		if(!$oGaleria->Publicar($datos))
			return false;	
			
		return true;	
	}


//----------------------------------------------------------------------------------------- 
// Insertar un archivo multimedia a una imagen

// Parámetros de Entrada:
//		datos: arreglo de datos
//			galeriacod = codigo de la galeria
//			multimediacod = codigol multimedia
//				FOTOS

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function InsertarImagenMultimedia($datos)
	{
		$oGaleria = new cGalerias($this->conexion,$this->formato);
		if(!$oGaleria->BuscarxCodigo($datos,$resultado,$numfilas))
			return false;
		$datosGaleria = $this->conexion->ObtenerSiguienteRegistro($resultado);	
		
		//obtener descripcion del multimedia
		$oMultimedia = new cMultimedia($this->conexion,"noticias", $this->formato);
		if(!$oMultimedia->BuscarMultimediaxCodigo($datos,$resultado,$numfilas))
			return false;
		$datosMultimedia = $this->conexion->ObtenerSiguienteRegistro($resultado);
		
		$datos['multimediaconjuntocod'] = FOTOS;
		$datos['multimediacod'] = $datosMultimedia['multimediacod'];
		$datos['galmultimediatitulo'] = $datosMultimedia['multimediatitulo'];
		$datos['galmultimediadesc'] = $datosMultimedia['multimediadesc'];
		
		if ($datos['galmultimediatitulo']=="")
			$datos['galmultimediatitulo'] = $datosGaleria['galeriatitulo'];
		
		$datos['multimediacodpreview'] = "NULL";
		if (!$this->Insertar($datos))
			return false;
			
		return true;	
	}
	
	
//----------------------------------------------------------------------------------------- 
// Genera un nuevo archivo multimedia y lo inserta como video de la galeria

// Parámetros de Entrada:
//		datos: arreglo de datos
//			galeriacod = codigo de la galeria
//				VIDEOS


// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function InsertarVideoMultimedia($datos)
	{
		$oGaleria = new cGalerias($this->conexion,$this->formato);
		if(!$oGaleria->BuscarxCodigo($datos,$resultado,$numfilas))
			return false;
		$datosGaleria = $this->conexion->ObtenerSiguienteRegistro($resultado);	

		$oMultimedia = new cMultimedia($this->conexion,"noticias", $this->formato);
		if(!$oMultimedia->BuscarMultimediaxCodigo($datos,$resultado,$numfilas))
			return false;
		$datosMultimedia = $this->conexion->ObtenerSiguienteRegistro($resultado);
		
		$datos['multimediaconjuntocod'] = VIDEOS;
		$datos['multimediacod'] = $datosMultimedia['multimediacod'];
		$datos['galmultimediatitulo'] = $datosMultimedia['multimediatitulo'];
		$datos['galmultimediadesc'] = $datosMultimedia['multimediadesc'];

		if ($datos['galmultimediatitulo']=="")
			$datos['galmultimediatitulo'] = $datosGaleria['galeriatitulo'];
		
		if ($datosMultimedia['multimediapreview']!="")
			$datos['multimediacodpreview'] = $datosMultimedia['multimediapreview'];
		else
			$datos['multimediacodpreview'] = "NULL";


		if (!$this->Insertar($datos))
			return false;
			
		return true;	
	}

//----------------------------------------------------------------------------------------- 
// Genera un nuevo archivo multimedia y lo inserta como audio de la galeria

// Parámetros de Entrada:
//		datos: arreglo de datos
//			galeriacod = codigo de la galeria
//				AUDIOS


// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function InsertarAudioMultimedia($datos)
	{

		$oGaleria = new cGalerias($this->conexion,$this->formato);
		if(!$oGaleria->BuscarxCodigo($datos,$resultado,$numfilas))
			return false;
		$datosGaleria = $this->conexion->ObtenerSiguienteRegistro($resultado);	


		$oMultimedia = new cMultimedia($this->conexion,"noticias", $this->formato);
		if(!$oMultimedia->BuscarMultimediaxCodigo($datos,$resultado,$numfilas))
			return false;
		$datosMultimedia = $this->conexion->ObtenerSiguienteRegistro($resultado);

		$datos['multimediaconjuntocod'] = AUDIOS;
		$datos['multimediacod'] = $datosMultimedia['multimediacod'];
		$datos['galmultimediatitulo'] = $datosMultimedia['multimediatitulo'];
		$datos['galmultimediadesc'] = $datosMultimedia['multimediadesc'];

		if ($datos['galmultimediatitulo']=="")
			$datos['galmultimediatitulo'] = $datosGaleria['galeriatitulo'];
		
		if ($datosMultimedia['multimediapreview']!="")
			$datos['multimediacodpreview'] = $datosMultimedia['multimediapreview'];
		else
			$datos['multimediacodpreview'] = "NULL";
		if (!$this->Insertar($datos))
			return false;
			
		return true;	
	}
	

//----------------------------------------------------------------------------------------- 
// Insertar Multimedia a una galeria

// Parámetros de Entrada:
//		datos: arreglo de datos
//			galeriacod = codigo de la galeria
//			multimediacod = codigol multimedia
//			multimediaconjuntocod = Código del conjunto de multimedia insertado
//				FOTOS
//				VIDEOS
//				AUDIOS


// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function Insertar($datos)
	{
		if (!$this->_ValidarInsertar($datos))
			return false;
			
		$this->ObtenerProximoOrden($datos,$proxorden);
		$datos['galmultimediaorden'] = $proxorden;
		$datos['usuariodioalta'] = $_SESSION['usuariocod'];
		$datos['galmultimediafalta'] = date("Y/m/d H:i:s");
		if (!parent::Insertar($datos))
			return false;

		return true;	
	}





//----------------------------------------------------------------------------------------- 
// Eliminar Multimedia a una galeria

// Parámetros de Entrada:
//		datos: arreglo de datos
//			galeriacod = codigo de la galeria
//			multimediacod = codigol multimedia

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function Eliminar($datos)
	{
		if (!$this->_ValidarEliminar($datos))
			return false;
			
		if (!parent::Eliminar($datos))
			return false;
			
		$oGaleria = new cGalerias($this->conexion,$this->formato);
		if(!$oGaleria->Publicar($datos))
			return false;
		return true;	
	}


//----------------------------------------------------------------------------------------- 
// Eliminar Multimedias seleccionados de una galeria

// Parámetros de Entrada:
//		datos: arreglo de datos
//			galeriacod = codigo de la galeria
//			multimedia = codigol multimedia

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function EliminarMultimediasSeleccionados($datos)
	{
		$multimedias = explode(",",$datos['multimedia']);
		$datoseliminar['galeriacod'] = $datos['galeriacod'];
		if (count($multimedias)>0)
		{
			foreach($multimedias as $multimediacod)
			{
				if ($multimediacod!="")
				{
					$datoseliminar['multimediacod'] = $multimediacod;
					if (!$this->Eliminar($datoseliminar))
						return false;
				}
			}	
		}
		return true;	
	}



	public function ModificarOrden($datos)
	{
		$datosmodif['galmultimediaorden'] = 1;
		$datosmodif['galeriacod'] = $datos['galeriacod'];
		$arreglomultimedia = $datos['multimedia'];
		foreach ($arreglomultimedia as $multimediacod)
		{
			$datosmodif['multimediacod'] = $multimediacod;
			if (!parent::ModificarOrden($datosmodif))
				return false;
			$datosmodif['galmultimediaorden']++;
		}
		$oGaleria = new cGalerias($this->conexion,$this->formato);
		if(!$oGaleria->Publicar($datos))
			return false;
		return true;
	}



	
//----------------------------------------------------------------------------------------- 
// Modificar el titulo del multimedia

// Parámetros de Entrada:
//		datos: arreglo de datos
//			noticiacod = codigo de la noticia
//			multimediacod = codigo multimedia
//			multimediatitulo = titulo a modificar

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function ModificarTituloMultimedia($datos)
	{
		//VERIFICAR QUE SE ENCUENTREN RELACIONADOS
		$datos['galeriacod'] = $datos['codigo'];
		if (!$this->BuscarMultimediaxCodigoGaleriaxCodigoMultimedia($datos,$resultado,$numfilas))
			return false;
		if ($numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Multimedia inexistente. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		$datos['galmultimediatitulo'] = $datos['multimediatitulo'];
		if (!parent::ModificarTituloMultimedia($datos))
			return false;

		$oGaleria = new cGalerias($this->conexion,$this->formato);
		if(!$oGaleria->Publicar($datos))
			return false;	
		return true;	
	}
	
	
	
//----------------------------------------------------------------------------------------- 
// Modificar la descripcion del multimedia

// Parámetros de Entrada:
//		datos: arreglo de datos
//			noticiacod = codigo de la noticia
//			multimediacod = codigo multimedia
//			multimediadesc = descripcion a modificar

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function ModificarDescripcionMultimedia($datos)
	{
		//VERIFICAR QUE SE ENCUENTREN RELACIONADOS
		$datos['galeriacod'] = $datos['codigo'];
		if (!$this->BuscarMultimediaxCodigoGaleriaxCodigoMultimedia($datos,$resultado,$numfilas))
			return false;
		if ($numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Multimedia inexistente. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		$datos['galmultimediadesc'] = $datos['multimediadesc'];
		if (!parent::ModificarDescripcionMultimedia($datos))
			return false;

		$oGaleria = new cGalerias($this->conexion,$this->formato);
		if(!$oGaleria->Publicar($datos))
			return false;	
		return true;	
	}
	

//----------------------------------------------------------------------------------------- 
// Modificar Previes Multimedia

// Parámetros de Entrada:
//		datos: arreglo de datos
//			galeriacod = codigo de la galeria
//			multimediacod = codigo multimedia
//			multimediacodpreview = codigo multimedia a relacionar



// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function ModificarPreview($datos)
	{			
		$datos['multimediacod'] = $datos['multimediacod'];

		$oMultimedia = new cMultimedia($this->conexion,CARPNOTICIAS,$this->formato);
		if (!$oMultimedia->BuscarMultimediaxCodigo($datos,$resultado,$numfilas))
			return false;
		if ($numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Multimedia inexistente.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}	
		$datosMultimedia = $this->conexion->ObtenerSiguienteRegistro($resultado);
		if ($datosMultimedia['multimediapreview']=="" && $datosMultimedia['multimediaidexterno']!="")
		{
			$datosMultimedia['multimediapreview'] = $datos['multimediacodRelacion'];
			if (!$oMultimedia->ModificarPreview($datosMultimedia))
				return false;
		}


		$datos['multimediacodpreview'] = $datos['multimediacodRelacion'];
		$datos['galeriacod'] = $datos['codigo'];
		if (!parent::ModificarPreview($datos))
			return false;
		
		$oGaleria = new cGalerias($this->conexion,$this->formato);
		if(!$oGaleria->Publicar($datos))
			return false;
		return true;	
	}



	public function SubirRelacionarPreview($datos)
	{
		$oMultimedia = new cMultimediaGeneral($this->conexion,CARPNOTICIAS,$this->formato);
		if (!$oMultimedia->InsertarImagen($datos,$multimediacod))
			return false;
		
		$datos['multimediacodRelacion'] = $multimediacod; 
		$datos['multimediacod'] = $datos['multimediacod'];
		$datos['galeriacod'] = $datos['codigo'];
		if(!$this->ModificarPreview($datos))	
			return false;
		
		$oGaleria = new cGalerias($this->conexion,$this->formato);
		if(!$oGaleria->Publicar($datos))
			return false;	
		return true;	
		
	}

	private function _ValidarInsertar($datos)
	{
		
		if(!$this->BuscarMultimediaxCodigoGaleriaxCodigoMultimedia($datos,$resultado,$numfilas))
			return false;
	
		if($numfilas==1){
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, El elemento ya se encuentra asociado a la galería. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	}
	
	

	private function _ValidarEliminar($datos)
	{


		return true;
	}







	private function ObtenerProximoOrden($datos,&$proxorden)
	{
		$proxorden = 0;
		if (!parent::BuscarMultimediaUltimoOrdenxGaleria($datos,$resultado,$numfilas))
			return false;
			
		if ($numfilas!=0)
		{
			$datos = $this->conexion->ObtenerSiguienteRegistro($resultado);
			$proxorden = $datos['maximo'] + 1;
		}
		return true;
	}

}//FIN CLASE

?>