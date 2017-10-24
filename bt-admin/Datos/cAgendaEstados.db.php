<?php  
/**
 * @clase acceso a base de datos de la agenda estados
 * 
 * @author      <Bigtree Studio SRL>
 * @descripcion  <Clase con los accesos a la base de datos para el manejo de los eventos de la agenda estados>
*/

abstract class cAgendaEstadosdb
{
	
	// Constructor de la clase
	function __construct(){


    } 
	
	// Destructor de la clase
	function __destruct() {	
    } 	


//-----------------------------------------------------------------------------------------
//							 PUBLICAS	
//----------------------------------------------------------------------------------------- 

//------------------------------------------------------------------------------------------	
//------------------------------------------------------------------------------------------	
// Retorna en un arreglo con los datos de un estado de la agenda

// Parámetros de Entrada:
//	datos: Array asociativo de datos
//          agendaestadocod: codigo del estado de la agenda
//

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no

	protected function BuscarxCodigo($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_age_agenda_estados_xagendaestadocod";
		$sparam=array(
			'pagendaestadocod'=> $datos['agendaestadocod']
			);	
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscarel estado de la agenda.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
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

	protected function BusquedaAvanzada ($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_age_agenda_estados_busqueda";
		$sparam=array(
			'pxagendaestadodesc'=> $datos['xagendaestadodesc'],
			'pagendaestadodesc'=> $datos['agendaestadodesc'],
			'pxagendaestadocte'=> $datos['xagendaestadocte'],
			'pagendaestadocte'=> $datos['agendaestadocte'],
			'porderby'=> $datos['orderby'],
			'plimit'=> $datos['limit']
			);
	
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar el estado de la agenda.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
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
	
	protected function Insertar($datos,&$codigoinsertado)
	{

		$spnombre="ins_age_agenda_estados";
		$sparam=array(
			'pagendaestadodesc'=> $datos['agendaestadodesc'],
			'pagendaestadoestado'=> $datos['agendaestadoestado'],
			'pagendaestadocolor'=> $datos['agendaestadocolor'],
			'pagendaestadocte'=> $datos['agendaestadocte'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s")
			);	
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al insertar el estado de la agenda.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
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
	
	protected function Modificar($datos)
	{
		$spnombre="upd_age_agenda_estados_xagendaestadocod";
		$sparam=array(
			'pagendaestadodesc'=> $datos['agendaestadodesc'],
			'pagendaestadocolor'=> $datos['agendaestadocolor'],
			'pagendaestadocte'=> $datos['agendaestadocte'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			'pagendaestadocod'=> $datos['agendaestadocod']
			);
	
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al modificar el estado de la agenda.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	} 
	
//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 
// Eliminar un estado de la agenda
// Parámetros de Entrada:
//		datos: arreglo de datos
//          agendaestadocod: codigo del estado de la agenda

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	protected function Eliminar($datos)
	{
		$spnombre="del_age_agenda_estados_xagendaestadocod";
		$sparam=array(
			'pagendaestadocod'=> $datos['agendaestadocod']
			);	
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al eliminar el estado de la agenda.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
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
	protected function ActivarDesactivar ($datos)
	{
		$spnombre="upd_age_agenda_estados_activar_desactivar_xagendaestadocod";
		$sparam=array(
			'pagendaestadoestado'=> $datos['agendaestadoestado'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			'pagendaestadocod'=> $datos['agendaestadocod']
			);	
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al activar/desactivar el estado de la agenda.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;
		}
		
		return true;
	}

}
?>