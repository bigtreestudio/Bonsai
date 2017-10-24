<?php 
//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 
// Clase con la lógica para el manejo de las noticias relacionadas

include(DIR_CLASES_DB."cPaginasMultimedia.db.php");

class cPaginasMultimedia extends cPaginasMultimediadb	
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
		$datos['pagcod'] = $datos['codigo'];
		if (!parent::BuscarMultimediaxCodigoPaginaxMultimediaConjunto($datos,$resultado,$numfilas))
			return false;

		$arreglo = array();

		$puedeeditar = true;
		if (!$this->PuedeEditarArchivosMultimedia($datos))
			$puedeeditar = false;
			
		$i = 0;
		while ($fila = $this->conexion->ObtenerSiguienteRegistro($resultado))
		{
			$arreglo[$i]['codigo'] = $fila['pagcod'];
			$arreglo[$i]['multimediacod'] = $fila['multimediacod'];
			$arreglo[$i]['multimediaconjuntocod'] = $fila['multimediaconjuntocod'];
			$arreglo[$i]['multimedianombre'] = $fila['multimedianombre'];
			$arreglo[$i]['multimediatitulo'] = $fila['pagmultimediatitulo'];
			$arreglo[$i]['multimediadesc'] = $fila['pagmultimediadesc'];
			$arreglo[$i]['home'] = $fila['pagmultimediamuestrahome'];
			$arreglo[$i]['puedeeditar'] = $puedeeditar;
			$img = $oMultimedia->DevolverDireccionImg($fila);	
			$arreglo[$i]['multimediaimg'] = $img;
			$i++;
		}
		return true;
	}
	
	
	
	public function Asociar($datos)
	{
		$datos['pagcod'] = $datos['codigo'];
		$datos['pagmultimediatitulo'] = $datos['multimediatitulo'];
		$datos['pagmultimediadesc'] = $datos['multimediadesc'];
		$this->_SetearNull($datos);
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
		$datos['pagcod']=$datos['codigo'];
		if (!$this->_ValidarEliminar($datos))
			return false;
	
		if (!parent::Eliminar($datos))
			return false;
			
		return true;	
	}


	
//----------------------------------------------------------------------------------------- 
// Retorna verdadero o falso si puede o no editar un archivo multimedia

// Parámetros de Entrada:
//		datos: arreglo de datos
//			pagcod = codigo de la pagina (la que se quiere buscar los relacionados)

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	public function PuedeEditarArchivosMultimedia($datos)
	{
		$oPaginas = new cPaginas($this->conexion,$this->formato);
		$oPaginasWorkflowRoles = new cPaginasWorkflowRoles($this->conexion,$this->formato);
		if (!$oPaginas->BuscarxCodigo($datos,$resultado,$numfilas))
			return false;
			
		if ($numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, pagina inexistente. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		$datopagina =  $this->conexion->ObtenerSiguienteRegistro($resultado); 
		
		
		switch ($datopagina['pagestadocod'])
		{
			case PAGPUBLICADA:
			case PAGELIMINADA:
				return false;
		}
		$datopagina['rolcod'] = $datos['rolcod'];
		if(!$oPaginasWorkflowRoles->ObtenerAccionesRol($datopagina,$resultado,$numfilas))
			return false;
		
		if ($numfilas>0)
			return true;
			
		return false;	
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

	public function BuscarMultimediaxCodigoPagina($datos,&$resultado,&$numfilas)
	{
		if (!parent::BuscarMultimediaxCodigoPagina($datos,$resultado,$numfilas))
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

	public function BuscarMultimediaxCodigoPaginaxCodigoMultimedia($datos,&$resultado,&$numfilas)
	{
		if (!parent::BuscarMultimediaxCodigoPaginaxCodigoMultimedia($datos,$resultado,$numfilas))
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

	public function BuscarMultimediaFotosxCodigoPagina($datos,&$resultado,&$numfilas)
	{
		$datos['multimediaconjuntocod'] = FOTOS;
		if (!parent::BuscarMultimediaxCodigoPaginaxMultimediaConjunto($datos,$resultado,$numfilas))
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

	public function BuscarMultimediaVideosxCodigoPagina($datos,&$resultado,&$numfilas)
	{
		$datos['multimediaconjuntocod'] = VIDEOS;
		if (!parent::BuscarMultimediaxCodigoPaginaxMultimediaConjunto($datos,$resultado,$numfilas))
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

	public function BuscarMultimediaAudiosxCodigoPagina($datos,&$resultado,&$numfilas)
	{
		$datos['multimediaconjuntocod'] = AUDIOS;
		if (!parent::BuscarMultimediaxCodigoPaginaxMultimediaConjunto($datos,$resultado,$numfilas))
			return false;

		return true;
	}
	
	
//----------------------------------------------------------------------------------------- 
// Modificar si la imagen es de home o no

// Parámetros de Entrada:
//		datos: arreglo de datos
//			codigo = codigo de la pagina
//			multimediacod = codigol multimedia

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function ModificarHomeMultimedia($datos)
	{
		//VERIFICAR QUE SE ENCUENTREN RELACIONADOS
		$datos['pagcod'] = $datos['codigo'];
		if (!$this->BuscarMultimediaxCodigoPaginaxCodigoMultimedia($datos,$resultado,$numfilas))
			return false;
		if ($numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Multimedia inexistente. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		$datos['pagmultimediamuestrahome'] = $datos['multimediahome'];
		$this->_SetearNull($datos);
		if (!parent::ModificarHomeMultimedia($datos))
			return false;

			
		return true;	
	}
	

//----------------------------------------------------------------------------------------- 
// Modificar el titulo del multimedia

// Parámetros de Entrada:
//		datos: arreglo de datos
//			pagcod = codigo de la pagina
//			multimediacod = codigo multimedia
//			multimediatitulo = titulo a modificar

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function ModificarTituloMultimedia($datos)
	{
		//VERIFICAR QUE SE ENCUENTREN RELACIONADOS
		$datos['pagcod'] = $datos['codigo'];
		if (!$this->BuscarMultimediaxCodigoPaginaxCodigoMultimedia($datos,$resultado,$numfilas))
			return false;
		if ($numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Multimedia inexistente. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		$datos['pagmultimediatitulo'] = $datos['multimediatitulo'];
		$this->_SetearNull($datos);
		if (!parent::ModificarTituloMultimedia($datos))
			return false;

			
		return true;	
	}
	
	
	
//----------------------------------------------------------------------------------------- 
// Modificar la descripcion del multimedia

// Parámetros de Entrada:
//		datos: arreglo de datos
//			pagcod = codigo de la pagina
//			multimediacod = codigo multimedia
//			multimediadesc = descripcion a modificar

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function ModificarDescripcionMultimedia($datos)
	{
		//VERIFICAR QUE SE ENCUENTREN RELACIONADOS
		$datos['pagcod'] = $datos['codigo'];
		if (!$this->BuscarMultimediaxCodigoPaginaxCodigoMultimedia($datos,$resultado,$numfilas))
			return false;
		if ($numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Multimedia inexistente. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		$datos['pagmultimediadesc'] = $datos['multimediadesc'];
		$this->_SetearNull($datos);
		if (!parent::ModificarDescripcionMultimedia($datos))
			return false;

			
		return true;	
	}
	
	
	

//----------------------------------------------------------------------------------------- 
// Modificar Previes Multimedia

// Parámetros de Entrada:
//		datos: arreglo de datos
//			pagcod = codigo de la pagina
//			multimediacod = codigo multimedia
//			multimediacodpreview = codigo multimedia a relacionar



// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function ModificarPreview($datos)
	{			
		$datos['multimediacodpreview'] = $datos['multimediacodRelacion'];
		$datos['multimediacod'] = $datos['multimediacod'];
		$datos['pagcod'] = $datos['codigo'];
		$this->_SetearNull($datos);
		if (!parent::ModificarPreview($datos))
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
		$datos['pagmultimediaorden'] = $proxorden;
		$datos['usuariodioalta'] = $_SESSION['usuariocod'];
		$datos['pagmultimediafalta'] = date("Y/m/d H:i:s");
		$this->_SetearNull($datos);
		
		if (!parent::Insertar($datos))
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
		$datos['pagmultimediafalta'] = date("Y/m/d H:i:s");
		$this->_SetearNull($datos);
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
// Eliminar Multimedia a una pagina

// Parámetros de Entrada:
//		datos: arreglo de datos
//			pagcod = codigo de la pagina
//			multimediacod = codigol multimedia

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function EliminarCompletoxPaginacod($datos)
	{
		if (!$this->_ValidarEliminar($datos))
			return false;
			
		if (!parent::EliminarCompletoxPaginacod($datos))
			return false;
		return true;	
	}



	public function ModificarOrden($datos)
	{
		$datosmodif['pagmultimediaorden'] = 1;
		$datosmodif['pagcod'] = $datos['codigo'];
		$arreglomultimedia = $datos['multimedia'];
		foreach ($arreglomultimedia as $multimediacod)
		{
			$datosmodif['multimediacod'] = $multimediacod;
			if (!parent::ModificarOrden($datosmodif))
				return false;
			$datosmodif['pagmultimediaorden']++;
		}
		return true;
	}



	private function _ValidarInsertar($datos)
	{
		$ArregloDatos['pagcod']=$datos['pagcod'];
		$oPaginas = new cPaginas($this->conexion,$this->formato);
		if (!$oPaginas->BuscarxCodigo($ArregloDatos,$resultado,$numfilas))
			return false;
		if ($numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Pagina inexistente. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		//VERIFICAR QUE NO SE ENCUENTREN RELACIONADOS
		if (!$this->BuscarMultimediaxCodigoPaginaxCodigoMultimedia($datos,$resultado,$numfilas))
			return false;
			
		if ($numfilas>0)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"El archivo multimedia ya se encuentra relacionado a la pagina. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
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
		if (!parent::BuscarMultimediaUltimoOrdenxPaginaxConjunto($datos,$resultado,$numfilas))
			return false;
			
		if ($numfilas!=0)
		{
			$datos = $this->conexion->ObtenerSiguienteRegistro($resultado);
			$proxorden = $datos['maximo'] + 1;
		}
		return true;
	}
	
	private function _SetearNull(&$datos)
	{


		if (!isset($datos['multimediaconjuntocod']) || $datos['multimediaconjuntocod']=="")
			$datos['multimediaconjuntocod']="NULL";

		if (!isset($datos['multimediacod']) || $datos['multimediacod']=="")
			$datos['multimediacod']="NULL";

		if (!isset($datos['pagmultimediaorden']) || $datos['pagmultimediaorden']=="")
			$datos['pagmultimediaorden']="NULL";

		/*if (!isset($datos['pagmultimediatitulo']) || $datos['pagmultimediatitulo']=="")
			$datos['pagmultimediatitulo']="NULL";

		if (!isset($datos['pagmultimediadesc']) || $datos['pagmultimediadesc']=="")
			$datos['pagmultimediadesc']="NULL";*/

		if (!isset($datos['multimediacodpreview']) || $datos['multimediacodpreview']=="")
			$datos['multimediacodpreview']="NULL";

		if (!isset($datos['pagmultimediamuestrahome']) || $datos['pagmultimediamuestrahome']=="")
			$datos['pagmultimediamuestrahome']="0";

		if (!isset($datos['usuariodioalta']) || $datos['usuariodioalta']=="")
			$datos['usuariodioalta']="NULL";
		
		if (!isset($datos['pagmultimediafalta']) || $datos['pagmultimediafalta']=="")
			$datos['pagmultimediafalta']="NULL";	
			
		return true;
	}

}//FIN CLASE

?>