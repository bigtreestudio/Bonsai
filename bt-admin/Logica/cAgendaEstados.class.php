<?php 
/**
 * @clase logica de la agenda
 * 
 * @author      <Bigtree Studio SRL>
 * @descripcion  <Clase con la lógica para el manejo de los eventos de la agenda>
 * @extend  <cAgendaEstadosdb - Clase con los accesos a los stored_procedures de cAgendaEstados>
*/

include(DIR_CLASES_DB."cAgendaEstados.db.php");

class cAgendaEstados extends cAgendaEstadosdb
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
//							FUNCIONES PUBLICAS	
//----------------------------------------------------------------------------------------- 

//-----------------------------------------------------------------------------------------
//							 PUBLICAS	
//----------------------------------------------------------------------------------------- 

//------------------------------------------------------------------------------------------	
// Retorna en un arreglo con los datos de un estado de la agenda

// Parámetros de Entrada:
//	datos: Array asociativo de datos
//          agendaestadocod: codigo del estado de la agenda
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
// Retorna en un arreglo con los datos de un estado de la agenda

// Parámetros de Entrada:
//			agendaestadodesc: descripción del estado de la agenda
//			agendaestadocte: descripción de la constante del estado de la agenda

// Retorna:
//		resultado= Arreglo con todos los datos de un formato multimedia.
//		numfilas= cantidad de filas 
//		la función retorna true o false si se pudo ejecutar con éxito o no	
	function BusquedaAvanzada ($datos,&$resultado,&$numfilas)
	{
		
		$sparam=array(
			'xagendaestadodesc'=> 0,
			'agendaestadodesc'=> "",
			'xagendaestadocte'=> 0,
			'agendaestadocte'=> "",
			'orderby'=> "agendaestadocod ASC",
			'limit'=> ""
			);

		if (isset ($datos['agendaestadodesc']) && $datos['agendaestadodesc']!="")
		{
			$sparam['agendaestadodesc']= $datos['agendaestadodesc'];
			$sparam['xagendaestadodesc']= 1;
		}	
		if (isset ($datos['agendaestadocte']) && $datos['agendaestadocte']!="")
		{
			$sparam['agendaestadocte']= $datos['agendaestadocte'];
			$sparam['xagendaestadocte']= 1;
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
// Inserta nuevo estado de la agenda

// Parámetros de Entrada:
//			agendaestadodesc: descripción del estado de la agenda
//			agendaestadocte: descripción de la constante del estado de la agenda
//          agendaestadocolor: color del estado de la agenda
// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	public function Insertar($datos,&$codigoinsertado)
	{
		if (!$this->_ValidarDatosAlta($datos))
			return false;
		
		$datos['agendaestadoestado']= ACTIVO;
		if(!parent::Insertar($datos,$codigoinsertado))
			return false;

		return true;
	} 

//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 
// Modifica los datos de un estado de la agenda

// Parámetros de Entrada:
//          agendaestadocod: codigo del estado de la agenda
//			agendaestadodesc: descripción del estado de la agenda
//			agendaestadocte: descripción de la constante del estado de la agenda
//          agendaestadocolor: color del estado de la agenda
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

// Eliminar un estado de la agenda
// Parámetros de Entrada:
//		datos: arreglo de datos
//          agendaestadocod: codigo del estado de la agenda

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
// Activar/Desactivar  de un estado de la agenda cambiando el estado (ACTIVO/NOACTIVO)

// Parámetros de Entrada:
//		datos: arreglo de datos
//          agendaestadocod: codigo del estado de la agenda
//			agendaestadoestado: estado del estado de la agenda

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

// Activar un tipo de estado de la agenda cambiando el estado
// Parámetros de Entrada:
//		datos: arreglo de datos
//          agendaestadocod: codigo del estado de la agenda
//			agendaestadoestado: estado del estado de la agenda
// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function Activar($datos)
	{
		$datos['agendaestadoestado'] = ACTIVO;
		if (!$this->ActivarDesactivar($datos))
			return false;
	
		return true;	
	} 
//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 

// Desactivar un estado de la agenda cambiando el estado

// Parámetros de Entrada:
//		datos: arreglo de datos
//          agendaestadocod: codigo del estado de la agenda
//			agendaestadoestado: estado del estado de la agenda

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no
	public function DesActivar($datos)
	{
		$datos['agendaestadoestado'] = NOACTIVO;
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
//			agendaestadodesc: descripción del estado de la agenda
//			agendaestadocte: descripción de la constante del estado de la agenda
//          agendaestadocolor: color del estado de la agenda

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function _ValidarDatosVacios($datos)
	{
		
		if (isset($datos['agendaestadodesc']) && $datos['agendaestadodesc']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar una descripción.  ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		if (isset($datos['agendaestadocte']) && $datos['agendaestadocte']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar una constante. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		if (isset($datos['agendaestadocolor']) && $datos['agendaestadocolor']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar un color. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		return true;
	}

//----------------------------------------------------------------------------------------- 
// Retorna true o false al dar de alta si algunos de los campos esta vacio 

// Parámetros de Entrada:
//			agendaestadodesc: descripción del estado de la agenda
//			agendaestadocte: descripción de la constante del estado de la agenda
//          agendaestadocolor: color del estado de la agenda

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
//          agendaestadocod: codigo del estado de la agenda
//			agendaestadodesc: descripción del estado de la agenda
//			agendaestadocte: descripción de la constante del estado de la agenda
//          agendaestadocolor: color del estado de la agenda

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
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, estado de la agenda inexistente.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;
		}
		return true;
	}
//----------------------------------------------------------------------------------------- 
// Retorna true o false al validar el activar o desactivar el estado de la agenda

// Parámetros de Entrada:
//          agendaestadocod: codigo del estado de la agenda
// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no	

	private function _ValidarActivarDesactivar($datos)
	{
		
		if (!$this->BuscarxCodigo($datos,$resultado,$numfilas))
			return false;
	
		if ($numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, estado de la agenda inexistente.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;
		}
		return true;
	}
	
//----------------------------------------------------------------------------------------- 
// Retorna true o false al validar el activar o desactivar el estado de la agenda

// Parámetros de Entrada:
//          agendaestadocod: codigo del estado de la agenda
// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no	

	private function _ValidarEliminarDatos($datos)
	{
		
		if (!$this->BuscarxCodigo($datos,$resultado,$numfilas))
			return false;
	
		if ($numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, estado de la agenda inexistente.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;
		}
		return true;
	}

}//fin clase	

?>