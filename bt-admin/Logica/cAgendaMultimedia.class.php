<?php 
//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 
// Clase con la lógica para el manejo de las noticias relacionadas

include(DIR_CLASES_DB."cAgendaMultimedia.db.php");

class cAgendaMultimedia extends cAgendaMultimediadb	
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
// Retorna una consulta con los archivos de fotos de multimedia de una noticia

// Parámetros de Entrada:
//		datos: arreglo de datos
//			codigo = codigo de la noticia (la que se quiere buscar los relacionados)

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function BuscarMultimedias($datos,&$arreglo)
	{
		$oMultimedia = new cMultimedia($this->conexion,"");


		$arreglo = array();

		if ($datos['codigo']=="")
			return true;
			
		$datos['multimediaconjuntocod'] = $datos['tipo'];
		$datos['agendacod'] = $datos['codigo'];
		if (!parent::BuscarMultimediaxCodigoEventoxMultimediaConjunto($datos,$resultado,$numfilas))
			return false;


		$puedeeditar = true;
			
		$i = 0;
		while ($fila = $this->conexion->ObtenerSiguienteRegistro($resultado))
		{
			$arreglo[$i]['codigo'] = $fila['agendacod'];
			$arreglo[$i]['multimediacod'] = $fila['multimediacod'];
			$arreglo[$i]['multimediaconjuntocod'] = $fila['multimediaconjuntocod'];
			$arreglo[$i]['multimedianombre'] = $fila['multimedianombre'];
			$arreglo[$i]['multimediatitulo'] = $fila['agemultimediatitulo'];
			$arreglo[$i]['multimediadesc'] = $fila['agemultimediadesc'];
			$arreglo[$i]['home'] = $fila['agemultimediamuestrahome'];
			$arreglo[$i]['puedeeditar'] = $puedeeditar;
			$img = $oMultimedia->DevolverDireccionImg($fila);	
			$arreglo[$i]['multimediaimg'] = $img;
			$i++;
		}
		return true;
	}
	
	
	
	public function Asociar($datos)
	{
		$datos['agendacod'] = $datos['codigo'];
		$datos['agemultimediamuestrahome'] = 0;
		$datos['agemultimediatitulo'] = $datos['multimediatitulo'];
		$datos['agemultimediadesc'] = $datos['multimediadesc'];
		
		if (!$this->Insertar($datos))
			return false;

		return true;	
	}
	
//----------------------------------------------------------------------------------------- 
// Eliminar Multimedia a una noticia

// Parámetros de Entrada:
//		datos: arreglo de datos
//			noticiacod = codigo de la noticia
//			multimediacod = codigol multimedia

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function DesAsociar($datos)
	{
		$datos['agendacod']=$datos['codigo'];
		if (!$this->_ValidarEliminar($datos))
			return false;
	
		if (!parent::Eliminar($datos))
			return false;
			
		return true;	
	}

//----------------------------------------------------------------------------------------- 
// Retorna una consulta con los datos del multimedia por pagina y multimedia

// Parámetros de Entrada:
//		datos: arreglo de datos
//			pagcod = codigo de la pagina (la que se quiere buscar los relacionados)
//			multimediacod = codigo del archivo multimedia (la que se quiere buscar los relacionados)

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function BuscarMultimediaxCodigoEvento($datos,&$resultado,&$numfilas)
	{
		if (!parent::BuscarMultimediaxCodigoEvento($datos,$resultado,$numfilas))
			return false;

		return true;
	}
//----------------------------------------------------------------------------------------- 
// Retorna una consulta con los datos del multimedia por pagina y multimedia

// Parámetros de Entrada:
//		datos: arreglo de datos
//			pagcod = codigo de la pagina (la que se quiere buscar los relacionados)
//			multimediacod = codigo del archivo multimedia (la que se quiere buscar los relacionados)

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function BuscarMultimediaxCodigoEventoxCodigoMultimedia($datos,&$resultado,&$numfilas)
	{
		if (!parent::BuscarMultimediaxCodigoEventoxCodigoMultimedia($datos,$resultado,$numfilas))
			return false;

		return true;
	}

	

//----------------------------------------------------------------------------------------- 
// Retorna una consulta con los archivos de fotos de multimedia de una pagina

// Parámetros de Entrada:
//		datos: arreglo de datos
//			pagcod = codigo de la pagina (la que se quiere buscar los relacionados)

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function BuscarMultimediaFotosxCodigoEvento($datos,&$resultado,&$numfilas)
	{
		$datos['multimediaconjuntocod'] = FOTOS;
		if (!parent::BuscarMultimediaxCodigoEventoxMultimediaConjunto($datos,$resultado,$numfilas))
			return false;

		return true;
	}

//----------------------------------------------------------------------------------------- 
// Retorna una consulta con los archivos de videos de multimedia de una pagina

// Parámetros de Entrada:
//		datos: arreglo de datos
//			pagcod = codigo de la pagina (la que se quiere buscar los relacionados)

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function BuscarMultimediaVideosxCodigoEvento($datos,&$resultado,&$numfilas)
	{
		$datos['multimediaconjuntocod'] = VIDEOS;
		if (!parent::BuscarMultimediaxCodigoEventoxMultimediaConjunto($datos,$resultado,$numfilas))
			return false;

		return true;
	}

//----------------------------------------------------------------------------------------- 
// Retorna una consulta con los archivos de audios de multimedia de una pagina

// Parámetros de Entrada:
//		datos: arreglo de datos
//			pagcod = codigo de la pagina (la que se quiere buscar los relacionados)

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function BuscarMultimediaAudiosxCodigoEvento($datos,&$resultado,&$numfilas)
	{
		$datos['multimediaconjuntocod'] = AUDIOS;
		if (!parent::BuscarMultimediaxCodigoEventoxMultimediaConjunto($datos,$resultado,$numfilas))
			return false;

		return true;
	}
	
	



//----------------------------------------------------------------------------------------- 
// Insertar Multimedia a una pagina

// Parámetros de Entrada:
//		datos: arreglo de datos
//			pagcod = codigo de la pagina
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
		$datos['agemultimediaorden'] = $proxorden;
		$datos['usuariodioalta'] = $_SESSION['usuariocod'];
		$datos['agemultimediafalta'] = date("Y/m/d H:i:s");
		if (!parent::Insertar($datos))
			return false;

		return true;	
	}


//----------------------------------------------------------------------------------------- 
// Modificar Preview Multimedia

// Parámetros de Entrada:
//		datos: arreglo de datos
//			agendacod = codigo del evento
//			multimediacod = codigo multimedia
//			multimediacodpreview = codigo multimedia a relacionar



// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function ModificarPreview($datos)
	{			
		$datos['multimediacodpreview'] = $datos['multimediacodRelacion'];
		$datos['multimediacod'] = $datos['multimediacod'];
		$datos['agendacod'] = $datos['codigo'];
		if (!parent::ModificarPreview($datos))
			return false;

		return true;	
	}


//----------------------------------------------------------------------------------------- 
// Insertar Multimedia a una pagina //NO VALIDA DATOS

// Parámetros de Entrada:
//		datos: arreglo de datos
//			pagcod = codigo de la pagina
//			multimediacod = codigol multimedia
//			multimediaconjuntocod = Código del conjunto de multimedia insertado
//				FOTOS
//				VIDEOS
//				AUDIOS


// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function InsertarDuplicar($datos)
	{
		$datos['usuariodioalta'] = $_SESSION['usuariocod'];
		$datos['agemultimediafalta'] = date("Y/m/d H:i:s");
		if (!parent::Insertar($datos))
			return false;

		return true;	
	}


//----------------------------------------------------------------------------------------- 
// Eliminar Multimedia a una pagina

// Parámetros de Entrada:
//		datos: arreglo de datos
//			pagcod = codigo de la pagina
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
		return true;	
	}



//----------------------------------------------------------------------------------------- 
// Modificar si la imagen es de home o no

// Parámetros de Entrada:
//		datos: arreglo de datos
//			codigo = codigo de la agenda
//			multimediacod = codigol multimedia

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function ModificarHomeMultimedia($datos)
	{
		//VERIFICAR QUE SE ENCUENTREN RELACIONADOS
		$datos['agendacod'] = $datos['codigo'];
		if (!$this->BuscarMultimediaxCodigoEventoxCodigoMultimedia($datos,$resultado,$numfilas))
			return false;
		if ($numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Multimedia inexistente. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		$datos['agemultimediamuestrahome'] = $datos['multimediahome'];
		if (!parent::ModificarHomeMultimedia($datos))
			return false;

			
		return true;	
	}
	

//----------------------------------------------------------------------------------------- 
// Modificar el titulo del multimedia

// Parámetros de Entrada:
//		datos: arreglo de datos
//			pagcod = codigo de la agenda
//			multimediacod = codigo multimedia
//			multimediatitulo = titulo a modificar

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function ModificarTituloMultimedia($datos)
	{
		//VERIFICAR QUE SE ENCUENTREN RELACIONADOS
		$datos['agendacod'] = $datos['codigo'];
		if (!$this->BuscarMultimediaxCodigoEventoxCodigoMultimedia($datos,$resultado,$numfilas))
			return false;
		if ($numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Multimedia inexistente. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		$datos['agemultimediatitulo'] = $datos['multimediatitulo'];
		if (!parent::ModificarTituloMultimedia($datos))
			return false;

			
		return true;	
	}
	
	
	
//----------------------------------------------------------------------------------------- 
// Modificar la descripcion del multimedia

// Parámetros de Entrada:
//		datos: arreglo de datos
//			pagcod = codigo de la agenda
//			multimediacod = codigo multimedia
//			multimediadesc = descripcion a modificar

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function ModificarDescripcionMultimedia($datos)
	{
		//VERIFICAR QUE SE ENCUENTREN RELACIONADOS
		$datos['agendacod'] = $datos['codigo'];
		if (!$this->BuscarMultimediaxCodigoEventoxCodigoMultimedia($datos,$resultado,$numfilas))
			return false;
		if ($numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Multimedia inexistente. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		$datos['agemultimediadesc'] = $datos['multimediadesc'];
		if (!parent::ModificarDescripcionMultimedia($datos))
			return false;

			
		return true;	
	}
	
	

//----------------------------------------------------------------------------------------- 
// Eliminar Multimedia a una pagina

// Parámetros de Entrada:
//		datos: arreglo de datos
//			pagcod = codigo de la pagina
//			multimediacod = codigol multimedia

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function EliminarCompletoxEventocod($datos)
	{
		if (!$this->_ValidarEliminar($datos))
			return false;
			
		if (!parent::EliminarCompletoxEventocod($datos))
			return false;
		return true;	
	}

	public function ModificarOrden($datos)
	{
		$datosmodif['agemultimediaorden'] = 1;
		$datosmodif['agendacod'] = $datos['agendacod'];
		$arreglomultimedia = $datos['multimedia'];
		foreach ($arreglomultimedia as $multimediacod)
		{
			$datosmodif['multimediacod'] = $multimediacod;
			if (!parent::ModificarOrden($datosmodif))
				return false;
			$datosmodif['agemultimediaorden']++;
		}
		return true;
	}



	private function _ValidarInsertar($datos)
	{
		$ArregloDatos['agendacod']=$datos['agendacod'];
		$oAgenda = new cAgenda($this->conexion,$this->formato);
		if (!$oAgenda->BuscarxCodigo($ArregloDatos,$resultado,$numfilas))
			return false;
		if ($numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Evento inexistente. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		//VERIFICAR QUE NO SE ENCUENTREN RELACIONADOS
		if (!$this->BuscarMultimediaxCodigoEventoxCodigoMultimedia($datos,$resultado,$numfilas))
			return false;
			
		if ($numfilas>0)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"El archivo multimedia ya se encuentra relacionado al evento. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
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
		if (!parent::BuscarMultimediaUltimoOrdenxEventoxConjunto($datos,$resultado,$numfilas))
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