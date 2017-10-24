<?php  
/**
 * @clase acceso a base de datos de la agenda
 * 
 * @author      <Bigtree Studio SRL>
 * @descripcion  <Clase con los accesos a la base de datos para el manejo de los eventos de la agenda>
*/

abstract class cAgendadb
{
	
	// Constructor de la clase
	function __construct(){


    } 
	
	// Destructor de la clase
	function __destruct() {	
    } 	


//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 

// Retorna una consulta con los datos completos del evento

// Parámetros de Entrada:
//		datos: arreglo de datos
//			agendacod = codigo de la agenda

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no

	protected function BuscarxCodigo($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_age_agenda_xagendacod";
		$sparam=array(
			'pagendacod'=> $datos['agendacod']
			);

		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"No se ha podido traer los datos de la agenda  por codigo.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;	
	}
	

//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 
// Retorna en un query todos los eventos de una categoria

// Parámetros de Entrada:
//		catcod: catetoria a buscar

// Retorna:
//		resultado= Arreglo con todos los datos de un categoria.
//		numfilas= cantidad de filas 
//		la función retorna true o false si se pudo ejecutar con éxito o no

	protected function BuscarxCategoria($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_age_agenda_xcatcod";
		$sparam=array(
			'pcatcod'=> $datos['catcod']
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"No se ha podido traer los datos de la agenda  por categoria.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;	
	}
	

//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 

//------------------------------------------------------------------------------------------	
// Retorna un conjunto de registros con la busqueda sobre la agenda

// Parámetros de Entrada:
//	ArregloDatos: arreglo de datos
//		agendacod = codigo de la agenda
//		fechainicio = fecha de inicio del evento
//		fechafin = fecha de finalización del evento
//		agendatitulo = titulo del evento (busca con la condición like)
//		agendaestadocod = estado de la agenda (busca con la condición in - se puede pasar mas de un parametro, deben ser separados con coma)
//		catcod = codigo de la categoria
//		orderby = Campo para el ordenamiento de los resultados
//		limit = Campo para filtrar por cantidad de resultados - se debe pasar el siguiente formato - LIMIT 0,10.

// Retorna:
//		resultado= Arreglo con todos los eventos encontrados.
//		numfilas= cantidad de filas 
//		la función retorna true o false si se pudo ejecutar con éxito o no	

	protected function BuscarAgendaBusquedaAvanzanda($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_age_agenda_busqueda";
		$sparam=array(
			'pxagendacod'=> $datos['xagendacod'],
			'pagendacod'=> $datos['agendacod'],
			'pxfecha'=> $datos['xfecha'],
			'pfechainicio'=> $datos['fechainicio'],
			'pfechafin'=> $datos['fechafin'],
			'pxagendatitulo'=> $datos['xagendatitulo'],
			'pagendatitulo'=> $datos['agendatitulo'],
			'pxagendaestadocod'=> $datos['xagendaestadocod'],
			'pagendaestadocod'=> $datos['agendaestadocod'],
			'pxcatcod'=> $datos['xcatcod'],
			'pcatcod'=> $datos['catcod'],
			'porderby'=> $datos['orderby'],
			'plimit'=> $datos['limit']
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"No se ha podido buscar los datos de la agenda.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;	
	}


//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 
// Retorna en un arreglo con los datos de un categoria 

// Parámetros de Entrada:
//	ArregloDatos: arreglo de datos

// Retorna:
//		resultado= Arreglo con todos los datos de un categoria.
//		numfilas= cantidad de filas 
//		la función retorna true o false si se pudo ejecutar con éxito o no	
	
	protected function BuscarAgendaBusquedaFechaMayor($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_age_agenda_busqueda_fechamayor";
		$sparam=array(
			'pxfecha'=> $datos['xfecha'],
			'pfechainicio'=> $datos['fechainicio'],
			'pxagendaestadocod'=> $datos['xagendaestadocod'],
			'pagendaestadocod'=> $datos['agendaestadocod'],
			'porderby'=> $datos['orderby'],
			'plimit'=> $datos['limit']
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"No se ha podido buscar los datos de la agenda.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;	
	}
	
	
//----------------------------------------------------------------------------------------- 
//------------------------------------------------------------------------------------------	
// Retorna un conjunto de eventos por estado

// Parámetros de Entrada:
//	ArregloDatos: arreglo de datos
//		agendaestadocod = estado de la agenda (busca con la condición in - se puede pasar mas de un parametro, deben ser separados con coma)

// Retorna:
//		resultado= Arreglo con todos los datos de un categoria.
//		numfilas= cantidad de filas 
//		la función retorna true o false si se pudo ejecutar con éxito o no	
	
	protected function BuscarAgendaxEstado($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_age_agenda_xagendaestadocod";
		$sparam=array(
			'pagendaestadocod'=> $datos['agendaestadocod']
			);

		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"No se ha podido traer los datos de la agenda  por estado.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;	
	}



//----------------------------------------------------------------------------------------- 
//------------------------------------------------------------------------------------------	
// Retorna los eventos de la agenda por un rango de fechas

// Parámetros de Entrada:
//	fecha1: fecha inicial a buscar
//	fecha2: fecha final a buscar

// Retorna:
//		resultado= query con los resultados encontrados.
//		numfilas= cantidad de filas 
//		la función retorna true o false si se pudo ejecutar con éxito o no	

	protected function TraerAgendaxRango($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_age_agenda_xrango";
		$sparam=array(
			'pfecha1'=> $datos['fecha1'],
			'pfecha2'=> $datos['fecha2']
			);

		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"No se ha podido traer los datos de la agenda por fechas.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;	

	}
	


//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 
// Inserta un evento nuevo en la agenda.

// Parámetros de Entrada:
//	horariofdesde = fecha de la agenda para transformar a codigo de dia de la semana
//	horariodesde = hora de inicio de la agenda
//	horariohasta = hora de fin de la agenda
//	agendaestadocod = estado de la agenda
//	horariofdesde  = fecha de inicio de la agenda
//	horariofhasta = fecha de fin de la agenda
//	agendaobservaciones = observaciones de la agenda
//	agendatitulo = titulo de la agenda

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	protected function Insertar($datos,&$codigoinsertado)
	{
		$spnombre="ins_age_agenda";
		$sparam=array(
			'pcatcod'=> $datos['catcod'],
			'pagendatitulo'=> $datos['agendatitulo'],
			'pagendaobservaciones'=> $datos['agendaobservaciones'],
		    'pagendabajada' => $datos['agendabajada'],
			'pagendafdesde'=> $datos['agendafdesde'],
			'pagendafhasta'=> $datos['agendafhasta'],
			'phorainicio'=> $datos['horainicio'],
			'phorafin'=> $datos['horafin'],
			'pagendaestadocod'=> $datos['agendaestadocod'],
			'pagendadiasemana'=> $datos['agendadiasemana'],
			'pusuariodioalta'=> $_SESSION['usuariocod'],
			'pagendafalta'=> date("Y/m/d H:i:s"),
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			'pultmodusuario'=> $_SESSION['usuariocod']
			);

		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno) || $numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"No se ha podido insertar los datos en la agenda.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		$codigoinsertado=$this->conexion->UltimoCodigoInsertado();
		
		return true;
	}



//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 
// Modifica los datos de un evento de la agenda

// Parámetros de Entrada:
//	horariofdesde = fecha de la agenda para transformar a codigo de dia de la semana
//	horariodesde = hora de inicio de la agenda
//	horariohasta = hora de fin de la agenda
//	agendaestadocod = estado de la agenda
//	horariofdesde  = fecha de inicio de la agenda
//	horariofhasta = fecha de fin de la agenda
//	agendaobservaciones = observaciones de la agenda
//	agendatitulo = titulo de la agenda
//	agendacod = codigo de la agenda

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	protected function Modificar($datos)
	{

		$spnombre="upd_age_agenda_xagendacod";
		$sparam=array(
			'pcatcod'=> $datos['catcod'],
			'pagendatitulo'=> $datos['agendatitulo'],
			'pagendaobservaciones'=> $datos['agendaobservaciones'],
		    'pagendabajada' => $datos['agendabajada'],
			'pagendafdesde'=> $datos['agendafdesde'],
			'pagendafhasta'=> $datos['agendafhasta'],
			'phorainicio'=> $datos['horainicio'],
			'phorafin'=> $datos['horafin'],
			'pagendaestadocod'=> $datos['agendaestadocod'],
			'pagendadiasemana'=> $datos['agendadiasemana'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pagendacod'=> $datos['agendacod']
			);
			
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$query,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"No se ha podido actualizar los datos de la agenda.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
				
		return true;
	}



//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 
// Modifica el horario de un evento de la agenda

// Parámetros de Entrada:
//	horariofdesde = fecha de la agenda para transformar a codigo de dia de la semana
//	horariodesde = hora de inicio de la agenda
//	horariohasta = hora de fin de la agenda
//	'horariofdesde  = fecha de inicio de la agenda
//	'horariofhasta = fecha de fin de la agenda
//	agendacod = codigo de la agenda

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	protected function ModificarHorarioAgenda($datos)
	{
		
		$spnombre="upd_age_agenda_horario_xagendacod";
		$sparam=array(
			'pagendafdesde'=> $datos['agendafdesde'],
			'pagendafhasta'=> $datos['agendafhasta'],
			'phorainicio'=> $datos['horainicio'],
			'phorafin'=> $datos['horafin'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pagendacod'=> $datos['agendacod']
			);
			
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$query,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"No se ha podido actualizar el turno.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
				
		return true;
	}


//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 
// Modifica el estado de un evento de la agenda

// Parámetros de Entrada:
//		agendacod = codigo de agenda.
//      agendaestadocod = nuevo estado de la agenda

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	protected function ModificarEstadoAgenda($datos)
	{
		$spnombre="upd_age_agenda_estado_xagendacod";
		$sparam=array(
			'pagendaestadocod'=> $datos['agendaestadocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pagendacod'=> $datos['agendacod']
			);
			
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$query,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"No se ha podido actualizar el estado de los datos de la agenda.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
				
		return true;
	}
	



//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 
// Elimina un evento de la agenda

// Parámetros de Entrada:
//		agendacod = codigo de la agenda eliminar.

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	protected function Eliminar($datos)
	{	
	
		$spnombre="del_age_agenda_xagendacod";
		$sparam=array(
			'pagendacod'=> $datos['agendacod']
			);
	
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno) || $numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"No se ha podido eliminar los datos de la agenda.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
				
		return true;
	} 
	
//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 
// Activar/Desactivar  de una agenda cambiando el estado (ACTIVO/NOACTIVO)

// Parámetros de Entrada:
//		datos: arreglo de datos
//			agendacod = codigo del evento
//			agendaestado: estado del evento

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no	
	protected function ActivarDesactivar ($datos)
	{
		$spnombre="upd_age_agenda_estados_xagendacod";
		$sparam=array(
			'pagendaestado'=> $datos['agendaestado'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			'pagendacod'=> $datos['agendacod']
			);

		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al activar/desactivar el evento.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;
		}
		
		return true;
	}


}


?>