<?php  
include(DIR_CLASES_DB."cRevistaTapas.db.php");

class cRevistaTapas extends cRevistaTapasdb	
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
//							 PUBLICAS	
//----------------------------------------------------------------------------------------- 

// Trae las tapas

//	datos de entradas :
//   limit order by

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	public function BusquedaAvanzada($datos,&$resultado,&$numfilas)
	{
		$sparam=array(
			'orderby'=> "revtapafecha ASC",
			'limit'=> ""
		);	
			
		$sparam=array(
			'xrevtapatitulo'=> 0,
			'revtapatitulo'=> "",
			'xrevtapanumero'=> 0,
			'revtapanumero'=> "",
			'orderby'=> "revtapacod desc",
			'limit'=> ""
		);	
		
		if (isset ($datos['revtapatitulo']) && $datos['revtapatitulo']!="")
		{
			$sparam['revtapatitulo']= $datos['revtapatitulo'];
			$sparam['xrevtapatitulo']= 1;
		}	

		if (isset ($datos['revtapanumero']) && $datos['revtapanumero']!="")
		{
			$sparam['revtapanumero']= $datos['revtapanumero'];
			$sparam['xrevtapanumero']= 1;
		}	
		
		if (isset ($datos['orderby']) && $datos['orderby']!="")
			$sparam['orderby']= $datos['orderby'];
			
		if (isset ($datos['limit']) && $datos['limit']!="")
			$sparam['limit']= $datos['limit'];
			
		if (!parent::BusquedaAvanzada ($sparam,$resultado,$numfilas))
			return false;
			
		return true;			
	}



// Trae los datos de la tapa por codigo

// Parámetros de Entrada:
//		datos: arreglo de datos
//			frasecod = codigo de la frrase

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	public function BuscarxCodigo($datos,&$resultado,&$numfilas)
	{

		if (!parent::BuscarxCodigo ($datos,$resultado,$numfilas))
			return false;
		return true;			
	}



// Parámetros de Entrada:
//		datos: arreglo de datos
//			tapacod = codigo de la Tapa
// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no	
	
	public function Eliminar($datos)
	{
		if (!$this->_ValidarEliminar($datos))
			return false;

		$oRevistaTapasMultimedia = new cRevistaTapasMultimedia($this->conexion, $this->formato);
		if(!$oRevistaTapasMultimedia ->EliminarMultimedias($datos))
			return false;

		if (!parent::Eliminar($datos))
			return false;
			
			
		return true;
	}

	public function Modificar($datos)
	{	
		if (!$this->_ValidarModificar($datos))
			return false;

		$datos['revtapafecha'] = FuncionesPHPLocal::ConvertirFecha($datos['revtapafecha'],"dd/mm/aaaa","aaaa-mm-dd");
			
		if(!parent::Modificar($datos))
			return false;

		return true;
	} 


	public function Insertar($datos,&$revtapacod)
	{	


		if (!$this->_ValidarInsertar($datos))
			return false;

		$datos['revtapafecha'] = FuncionesPHPLocal::ConvertirFecha($datos['revtapafecha'],"dd/mm/aaaa","aaaa-mm-dd");
		$datos['revtapaestado'] = ACTIVO;
		if(!parent::Insertar($datos,$revtapacod))
			return false;

		return true;
	} 

// Parámetros de Entrada:
//		tapacod= codigo de la tapa.
//      tapaestado = nuevo estado de la tapa

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no	
	public function DesActivar($datos)
	{
		
		$datosmodificar['revtapacod'] = $datos['revtapacod'];
		$datosmodificar['revtapaestado'] = NOACTIVO;
		if (!$this->ModificarEstado($datosmodificar))
			return false;
		
		return true;
	}
	
	// Parámetros de Entrada:
//		tapacod= codigo de la tapa.
//      tapaestado = nuevo estado de la tapa

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no	
	public function Activar($datos)
	{
		
		$datosmodificar['revtapacod'] = $datos['revtapacod'];
		$datosmodificar['revtapaestado'] = ACTIVO;
		if (!$this->ModificarEstado($datosmodificar))
			return false;
		
		return true;
	}
	
// Retorna true o false si pudo cambiarle el estado de la tapa
// Parámetros de Entrada:
//		tapacod = codigo de la tapa.
// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function ModificarEstado($datos)
	{
		if (!parent::ModificarEstado($datos))
			return false;
			
		return true;	
	}


	private function _ValidarInsertar($datos)
	{

		if (!$this->_ValidarDatosVacios($datos))
			return false;

		return true;
	}

// Retorna true o false al modificar si alguno de los campos esta vacio.

	private function _ValidarModificar($datos)
	{
		if (!$this->_ValidarDatosVacios($datos))
			return false;
		if (!$this->BuscarxCodigo($datos,$resultado,$numfilas))
			return false;
		if ($numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar una tapa valida. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;
		}

		return true;
	}
	

	private function _ValidarEliminar($datos)
	{
		if (!$this->BuscarxCodigo($datos,$resultado,$numfilas))
			return false;
		if ($numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar una tapa valida. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;
		}

		
		return true;
	}
	
	private function _ValidarDatosVacios($datos)
	{
		
		
		if ($datos['revtapatitulo']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar un nombre de la tapa. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));			
			return false;
		}
		if (!FuncionesPHPLocal::ValidarContenido($this->conexion,$datos['revtapafecha'],"FechaDDMMAAAA"))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error debe seleccionar la fecha valida.. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		if ($datos['revtapatipocod']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe seleccionar el tipo de la tapa. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));			
			return false;
		}
		
		$datos['revtapafecha'] = FuncionesPHPLocal::ConvertirFecha($datos['revtapafecha'],"dd/mm/aaaa","aaaa-mm-dd");
		
		
		return true;
	}
// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function InsertarImagen($datos)
	{
		if(!parent::ActualizarImagen($datos))
			return false;
		
		return true;	
	}

	public function EliminarImagen($datos)
	{
		$datos['revtapaarchubic']=NULL;
		$datos['revtapaarchnombre']=NULL;
		$datos['revtapaarchsize']=NULL;	
		
		if(!parent::ActualizarImagen($datos))
			return false;
		
		return true;	
	}	



	public function PublicarTapasImagenes($datos)
	{
		$oRevistaTapasMultimedia = new cRevistaTapasMultimedia($this->conexion,$this->formato);
		$datosBusqueda['revtapamulestado'] = ACTIVO;
		$datosBusqueda['revtapacod'] = $datos['revtapacod'];
		if (!$oRevistaTapasMultimedia->BusquedaAvanzada($datosBusqueda,$resultadoTapas,$numfilasTapas))
			return false;
				
		
		$domDoc = new DOMDocument('1.0','UTF-8');
		$root = $domDoc->createElement('Tapas');
		$NodoResultado = $domDoc->appendChild($root);
		
		
		while ($fila = $this->conexion->ObtenerSiguienteRegistro($resultadoTapas)) {

			$NodoBlog = $domDoc->createElement('Tapa');
			$NodoBlog->setAttribute("id", $fila["revtapamulcod"]);
			$SubNodoBlog = $NodoResultado->appendChild($NodoBlog);
			
			$NombreBlog = $domDoc->createElement('Imagen');
			$NodoNombre = $SubNodoBlog->appendChild($NombreBlog);
			$TituloBlog = $domDoc->createCDATASection($fila["revtapamulubic"]);
			$NodoNombre->appendChild($TituloBlog);

		}
		
		$domDoc->formatOutput = true;
		$Destino = $domDoc->saveXML();
		
		file_put_contents(CARPETA_SERVIDOR_MULTIMEDIA_FISICA.'tapas/xml/tapa_'.$datos['revtapacod'].'.xml' , $Destino);
		
		return true;	
	}	


}//FIN CLASS
?>