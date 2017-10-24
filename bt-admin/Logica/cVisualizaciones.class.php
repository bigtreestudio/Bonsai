<?php 
include(DIR_CLASES_DB."cVisualizaciones.db.php");

class cVisualizaciones extends cVisualizacionesdb	
{


	protected $conexion;
	protected $formato;
	private $prefijo_archivo = "noticia_";
	
	
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

// Parámetros de Entrada:
//	$datos = array asociativos
//		visualizaciontipocod: Tipo de visualizacion

// Retorna:
//		spnombre,sparam: nombre del stored procedures y parametros.
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	public function VisualizacionesSPxTipoActivos($datos,&$spnombre,&$sparam)
	{
		$datos['visualizacionestado'] = ACTIVO;
		if (!parent::VisualizacionesSPxTipo ($datos,$spnombre,$sparam))
			return false;
		return true;			
	}

//----------------------------------------------------------------------------------------- 

// Parámetros de Entrada:
//		datos: arreglo de datos
//			visualizacioncod = codigo de la visualizacion

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function BuscarVisualizacionxCodigo($datos,&$resultado,&$numfilas)
	{
		if (!parent::BuscarVisualizacionxCodigo ($datos,$resultado,$numfilas))
			return false;
		return true;	
	}
	
//------------------------------------------------------------------------------------------	
// Retorna en un arreglo con los datos de una visualizacion

// Parámetros de Entrada:
//			$datos = array asociativos
// 				visualizaciondesc = descripcion de la visualizacion a buscar
//				visualizaciontipocod = codigo del tipo de visualizacion a buscar
// Retorna:
//		resultado= Arreglo con todos los datos de un formato multimedia.
//		numfilas= cantidad de filas 
//		la función retorna true o false si se pudo ejecutar con éxito o no	
	function BusquedaAvanzada ($datos,&$resultado,&$numfilas)
	{
		
		$sparam=array(
			'xvisualizaciondesc'=> 0,
			'visualizaciondesc'=> "",
			'xvisualizaciontipocod'=> 0,
			'visualizaciontipocod'=> "",
			'orderby'=> "visualizaciondesc DESC",
			'limit'=> ""
		);	
			
		if (isset ($datos['visualizaciondesc']) && $datos['visualizaciondesc']!="")
		{
			$sparam['visualizaciondesc']= $datos['visualizaciondesc'];
			$sparam['xvisualizaciondesc']= 1;
		}	
		if (isset ($datos['visualizaciontipocod']) && $datos['visualizaciontipocod']!="")
		{
			$sparam['visualizaciontipocod']= $datos['visualizaciontipocod'];
			$sparam['xvisualizaciontipocod']= 1;
		}
		
		if (isset ($datos['orderby']) && $datos['orderby']!="")
			$sparam['orderby']= $datos['orderby'];
	
		if (isset ($datos['limit']) && $datos['limit']!="")
			$sparam['limit']= $datos['limit'];
		if (!parent::BusquedaAvanzada($sparam,$resultado,$numfilas))
			return false;

		return true;
	}


//----------------------------------------------------------------------------------------- 
// Inserta nuevo formato

// Parámetros de Entrada:
//			visualizaciondesc: descripción de la visualizacion 
//			visualizaciontipocod: codigo del tipo visualizacion
// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	public function Insertar($datos,&$codigoinsertado)
	{
		if (!$this->_ValidarDatosAlta($datos))
			return false;
		
		$datos['visualizacionestado']= ACTIVO;
		if(!parent::Insertar($datos,$codigoinsertado))
			return false;
			
		return true;
	} 

//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 
// Modifica los datos de un formato

// Parámetros de Entrada:
//			visualizaciondesc: descripción de la visualizacion 
//			visualizaciontipocod: codigo del tipo visualizacion
// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	public function Modificar($datos)
	{
		if (!$this->_ValidarDatosModificar($datos))
			return false;
		
		if(!parent::Modificar($datos))
			return false;
		return true;
	} 
	
//----------------------------------------------------------------------------------------- 

// Eliminar un formato multimedia
// Parámetros de Entrada:
//		datos: arreglo de datos
//			visualizacioncod = codigo de la visualizacion

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function Eliminar($datos)
	{	
		if (!$this->_ValidarEliminarDatos($datos,$datosdevueltos))
			return false;
			
		if(!parent::Eliminar($datos))
			return false;

		return true;	
	}	
	
//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 
// Activar/Desactivar  de una visualizacion cambiando el estado (ACTIVO/NOACTIVO)

// Parámetros de Entrada:
//		datos: arreglo de datos
//			visualizacioncod = codigo de la visualizacion

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no	
	public function ActivarDesactivar ($datos)
	{
		
		if (!$this->_ValidarActivarDesactivar($datos))
			return false;
	
		if (!parent::ActivarDesactivar($datos))
			return false;
		
		return true;
	}

//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 

// Activar un formato cambiando el estado
// Parámetros de Entrada:
//		datos: arreglo de datos
//			visualizacioncod = codigo de la visualizacion
// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function Activar($datos)
	{
		$datos['visualizacionestado'] = ACTIVO;
		if (!$this->ActivarDesactivar($datos))
			return false;
	
		return true;	
	} 
//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 

// Desactivar un formato cambiando el estado

// Parámetros de Entrada:
//		datos: arreglo de datos
//			visualizacioncod = codigo de la visualizacion

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no
	public function DesActivar($datos)
	{
		$datos['visualizacionestado'] = NOACTIVO;
		if (!$this->ActivarDesactivar($datos))
			return false;
	
		return true;	
	} 

    
//-----------------------------------------------------------------------------------------
//							 PRIVADAS	
//----------------------------------------------------------------------------------------- 

//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 
// Retorna true o false si algunos de los campos esta vacio

// Parámetros de Entrada:
//			visualizaciondesc: descripción de la visualizacion 
//			visualizaciontipocod: codigo del tipo visualizacion

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function _ValidarDatosVacios($datos)
	{
		
		if (isset($datos['visualizaciondesc']) && $datos['visualizaciondesc']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar una descripción.  ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		if (!isset ($datos['visualizaciontipocod']) || ($datos['visualizaciontipocod']==""))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar un tipo de visualización.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		$oVisualizacionesTipos=new cVisualizacionesTipos($this->conexion,$this->formato);
		if(!$oVisualizacionesTipos->BuscarxCodigo($datos,$resultado,$numfilas))
			return false;
		if ($numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar un tipo de visualización.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		return true;
	}

//----------------------------------------------------------------------------------------- 
// Retorna true o false al dar de alta si algunos de los campos esta vacio o si exite otro album con ese nombre

// Parámetros de Entrada:
//			visualizaciondesc: descripción de la visualizacion 
//			visualizaciontipocod: codigo del tipo visualizacion

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no
	function _ValidarDatosAlta($datos)
	{
		if (!$this->_ValidarDatosVacios($datos))
			return false;

		return true;
	}

//----------------------------------------------------------------------------------------- 
// Retorna true o false al modificar si algunos de los campos esta vacio o si exite otro album con ese nombre

// Parámetros de Entrada:
//			visualizaciondesc: descripción de la visualizacion 
//			visualizaciontipocod: codigo del tipo visualizacion

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function _ValidarDatosModificar($datos)
	{
		if (!$this->_ValidarDatosVacios($datos))
			return false;

		return true;
	}
//----------------------------------------------------------------------------------------- 
// Retorna true o false al validar el activar o desactivar el formato

// Parámetros de Entrada:
//		    visualizacioncod: código de la visualizacion
// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no	

	private function _ValidarActivarDesactivar($datos)
	{
		
		if (!$this->BuscarVisualizacionxCodigo($datos,$resultado,$numfilas))
			return false;
	
		if ($numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, visualización inexistente.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;
		}
			
		return true;
	}
	
//----------------------------------------------------------------------------------------- 
// Retorna true o false al validar el activar o desactivar el formato

// Parámetros de Entrada:
//		    visualizacioncod: código de la visualizacion
// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no	

	private function _ValidarEliminarDatos($datos,&$datosdevueltos)
	{
		
		if (!$this->BuscarVisualizacionxCodigo($datos,$resultado,$numfilas))
			return false;
	
		if ($numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, visualización inexistente.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;
		}
		$datosdevueltos = $this->conexion->ObtenerSiguienteRegistro($resultado);
			
		return true;
	}	

//----------------------------------------------------------------------------------------- 
	
}//FIN CLASE

?>