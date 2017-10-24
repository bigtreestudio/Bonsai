<?php  
include(DIR_CLASES_DB."cTapasTiposMetadataCampos.db.php");

class cTapasTiposMetadataCampos extends cTapasTiposMetadataCamposdb	
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

// Parámetros de Entrada:
//	datos: Array asociativo de datos
//

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	public function BuscarCamposActivos(&$resultado,&$numfilas)
	{
		$datos['tapatipometadataestado'] = ACTIVO;
		if (!parent::BuscarCamposxEstado ($datos,$resultado,$numfilas))
			return false;
		return true;			
	}

//------------------------------------------------------------------------------------------	
// Retorna en un arreglo con los datos de un tipo de metadata campo

// Parámetros de Entrada:
//	datos: Array asociativo de datos
//		menutipocod: Codigo del Tipo del menú
//

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	public function BuscarxCodigo($datos,&$resultado,&$numfilas)
	{
		if (!parent::BuscarxCodigo ($datos,$resultado,$numfilas))
			return false;
		return true;			
	}

	
//------------------------------------------------------------------------------------------	
// Retorna en un arreglo con los datos de un tipo de metadata campo

// Parámetros de Entrada:
//			tapatipometadatacampo: descripción del tipo de metadata campo
//			tapatipometadatacte: descripción de la constante del tipo de metadata campo

// Retorna:
//		resultado= Arreglo con todos los datos de un formato multimedia.
//		numfilas= cantidad de filas 
//		la función retorna true o false si se pudo ejecutar con éxito o no	
	function BusquedaAvanzada ($datos,&$resultado,&$numfilas)
	{
		$sparam=array(
			'xtapatipometadatacampo'=> 0,
			'tapatipometadatacampo'=> "",
			'xtapatipometadatacte'=> 0,
			'tapatipometadatacte'=> "",
			'xtapatipometadataestado'=> 0,
			'tapatipometadataestado'=> "-1",
			'orderby'=> "tapatipometadatacampo ASC",
			'limit'=> ""
			);
			
		if (isset ($datos['tapatipometadatacampo']) && $datos['tapatipometadatacampo']!="")
		{
			$sparam['tapatipometadatacampo']= $datos['tapatipometadatacampo'];
			$sparam['xtapatipometadatacampo']= 1;
		}	
		if (isset ($datos['tapatipometadatacte']) && $datos['tapatipometadatacte']!="")
		{
			$sparam['tapatipometadatacte']= $datos['tapatipometadatacte'];
			$sparam['xtapatipometadatacte']= 1;
		}
		if (isset ($datos['tapatipometadataestado']) && $datos['tapatipometadataestado']!="")
		{
			$sparam['tapatipometadataestado']= $datos['tapatipometadataestado'];
			$sparam['xtapatipometadataestado']= 1;
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
// Inserta nuevo tipo de metadata campo

// Parámetros de Entrada:
//			tapatipometadatacampo: descripción del tipo de metadata campo
//			tapatipometadatacte: descripción de la constante del tipo de metadata campo
// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	public function Insertar($datos,&$codigoinsertado)
	{
		if (!$this->_ValidarDatosAlta($datos))
			return false;
		
		$datos['tapatipometadataestado']= ACTIVO;
		if(!parent::Insertar($datos,$codigoinsertado))
			return false;

		return true;
	} 

//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 
// Modifica los datos de un tipo de metadata campo

// Parámetros de Entrada:
//			tapatipometadatacampo: descripción del tipo de metadata campo
//			tapatipometadatacte: descripción de la constante del tipo de metadata campo
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

// Eliminar un tipo de metadata campo
// Parámetros de Entrada:
//		datos: arreglo de datos
//			tapatipometadatacod: codigo del tipo de metadata campo

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function Eliminar($datos)
	{	
		if (!$this->_ValidarEliminarDatos($datos))
			return false;
			
		if(!parent::Eliminar($datos))
			return false;

		return true;	
	}	
	
//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 
// Activar/Desactivar  de un tipo de metadata campo cambiando el estado (ACTIVO/NOACTIVO)

// Parámetros de Entrada:
//		datos: arreglo de datos
//			tapatipometadatacod: codigo del tipo de metadata campo
//			tapatipometadataestado: estado del tipo de metadata campo

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

// Activar un tipo de metadata campo cambiando el estado
// Parámetros de Entrada:
//		datos: arreglo de datos
//			tapatipometadatacod: codigo del tipo de metadata campo
//			tapatipometadataestado: estado del tipo de metadata campo
// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function Activar($datos)
	{
		$datos['tapatipometadataestado'] = ACTIVO;
		if (!$this->ActivarDesactivar($datos))
			return false;
	
		return true;	
	} 
//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 

// Desactivar un tipo de metadata campo cambiando el estado

// Parámetros de Entrada:
//		datos: arreglo de datos
//			tapatipometadatacod: codigo del tipo de metadata campo
//			tapatipometadataestado: estado del tipo de metadata campo

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no
	public function DesActivar($datos)
	{
		$datos['tapatipometadataestado'] = NOACTIVO;
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
//			tapatipometadatacampo: descripción del tipo de metadata campo
//			tapatipometadatacte: descripción de la constante del tipo de metadata campo

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function _ValidarDatosVacios($datos)
	{
		
		if (isset($datos['tapatipometadatacampo']) && $datos['tapatipometadatacampo']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar una descripción.  ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		if (isset($datos['tapatipometadatacte']) && $datos['tapatipometadatacte']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar una constante. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		if (!FuncionesPHPLocal::ValidarContenido($this->conexion,$datos['tapatipometadatacte'],"AlfanumericoPuro"))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error debe ingresar alfanúmericos puros",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;
		}
		return true;
	}

//----------------------------------------------------------------------------------------- 
// Retorna true o false al dar de alta si algunos de los campos esta vacio 

// Parámetros de Entrada:
//			tapatipometadatacampo: descripción del tipo de metadata campo
//			tapatipometadatacte: descripción de la constante del tipo de metadata campo

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no
	function _ValidarDatosAlta($datos)
	{
		if (!$this->_ValidarDatosVacios($datos))
			return false;

		return true;
	}

//----------------------------------------------------------------------------------------- 
// Retorna true o false al modificar si algunos de los campos esta vacio o si exite 

// Parámetros de Entrada:
//			tapatipometadatacod: codigo del tipo de metadata campo
//			tapatipometadatacampo: descripción del tipo de metadata campo
//			tapatipometadatacte: descripción de la constante del tipo de metadata campo

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function _ValidarDatosModificar($datos)
	{
		if (!$this->_ValidarDatosVacios($datos))
			return false;
		
		if (!$this->BuscarxCodigo($datos,$resultado,$numfilas))
			return false;
	
		if ($numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, tipo de metadata campo inexistente.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;
		}
		return true;
	}
//----------------------------------------------------------------------------------------- 
// Retorna true o false al validar el activar o desactivar el tipo de metadata campo

// Parámetros de Entrada:
//			tapatipometadatacod: codigo del tipo de metadata campo
// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no	

	private function _ValidarActivarDesactivar($datos)
	{
		
		if (!$this->BuscarxCodigo($datos,$resultado,$numfilas))
			return false;
	
		if ($numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, tipo de metadata campo inexistente.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;
		}
		return true;
	}
	
//----------------------------------------------------------------------------------------- 
// Retorna true o false al validar el activar o desactivar el tipo de metadata campo

// Parámetros de Entrada:
//			tapatipometadatacod: codigo del tipo de metadata campo
// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no	

	private function _ValidarEliminarDatos($datos)
	{
		
		if (!$this->BuscarxCodigo($datos,$resultado,$numfilas))
			return false;
	
		if ($numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, tipo de metadata campo inexistente.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;
		}
		return true;
	}

}//fin clase	

?>