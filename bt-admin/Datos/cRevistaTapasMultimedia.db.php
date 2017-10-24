<?php  
abstract class cRevistaTapasMultimediadb
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

// Parámetros de Entrada:
//		datos: arreglo de datos
//			convocatoriacod = codigo de la convocatoria

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no

	protected function BuscarxCodigo($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_rev_tapas_tapa_multimedia_xrevtapamulcod";
		$sparam=array(
			'prevtapamulcod'=> $datos['revtapamulcod']
			);
	
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar la tapa del multimedia por código.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;	
	}
	
	
	// Parámetros de Entrada:
//		datos: arreglo de datos
//			convocatoriacod = codigo de la convocatoria

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no

	protected function BuscarxCodigoRevista($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_rev_tapas_tapa_multimedia_xrevtapacod";
		$sparam=array(
			'prevtapacod'=> $datos['revtapacod']
			);
	
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar la tapa del multimedia por código de revista.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;	
	}
//------------------------------------------------------------------------------------------	
// Retorna en un arreglo con los datos de una convocatoria

// Parámetros de Entrada:

// Retorna:
//		resultado= Arreglo con todos los datos de una convocatoria.
//		numfilas= cantidad de filas 
//		la función retorna true o false si se pudo ejecutar con éxito o no	
	

	protected function BusquedaAvanzada ($datos,&$resultado,&$numfilas)
	{
		
		$spnombre="sel_rev_tapas_tapa_multimedia_busqueda_avanzada";
		$sparam=array(
			'pxrevtapacod'=> $datos['xrevtapacod'],
			'prevtapacod'=> $datos['revtapacod'],
			'pxrevtapamuldescripcion'=> $datos['xrevtapamuldescripcion'],
			'prevtapamuldescripcion'=> $datos['revtapamuldescripcion'],
			'pxrevtapamulestado'=> $datos['xrevtapamulestado'],
			'prevtapamulestado'=> $datos['revtapamulestado'],
			'porderby'=> $datos['orderby'],
			'plimit'=> $datos['limit']
			);
	
	
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar las tapas de los multimedia.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	}
	
	
//----------------------------------------------------------------------------------------- 
// Inserta nueva convocatoria

// Parámetros de Entrada:
//			convocatoriadesc: descripción de la convocatoria
//			convocatoriaestado: estado de la convocatoria
// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	protected function Insertar($datos,&$codigoinsertado)
	{


		$spnombre="ins_rev_tapas_tapa_multimedia";
		$sparam=array(
			'prevtapacod'=> $datos['revtapacod'],
			'prevtapamulubic'=> $datos['revtapamulubic'],
			'prevtapamuldescripcion'=> $datos['revtapamuldescripcion'],
			'prevtapamulorden'=> $datos['revtapamulorden'],
			'prevtapamulestado'=> $datos['revtapamulestado'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s")
			);

	
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al insertar la tapa.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		$codigoinsertado=$this->conexion->UltimoCodigoInsertado();
		return true;
	} 

//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 
// Modifica los datos de una convocatoria

// Parámetros de Entrada:
//			convocatoriacod = codigo de la convocatoria
//			convocatoriadesc: descripción de la convocatoria
// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	protected function Modificar($datos)
	{

		$spnombre="upd_rev_tapas_tapa_multimedia_xrevtapamulcod";
		$sparam=array(
			'prevtapamuldescripcion'=> $datos['revtapamuldescripcion'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			'prevtapamulcod'=> $datos['revtapamulcod']
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al modificar la tapa.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	} 
	
//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 
// Eliminar una convocatoria
// Parámetros de Entrada:
//		datos: arreglo de datos
//			convocatoriacod = codigo de la convocatoria

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	protected function Eliminar($datos)
	{
		$spnombre="del_rev_tapas_tapa_multimedia_xrevtapamulcod";
		$sparam=array(
			'prevtapamulcod'=> $datos['revtapamulcod']
			);
	
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al eliminar la tapa.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	} 

//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 
// Activar/Desactivar  de una convocatoria cambiando el estado (ACTIVO/NOACTIVO)

// Parámetros de Entrada:
//		datos: arreglo de datos
//			convocatoriacod = codigo de la convocatoria
//			convocatoriaestado: estado de la convocatoria

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no	
	protected function ActivarDesactivar ($datos)
	{
		$spnombre="upd_rev_tapas_tapa_multimedia_estado_xrevtapamulcod";
		$sparam=array(
			'prevtapamulestado'=> $datos['revtapamulestado'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			'prevtapamulcod'=> $datos['revtapamulcod']
			);
	
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al activar/desactivar la tapa.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;
		}
		
		return true;
	}
	

	protected function BuscarRevTapaMulUltimoOrden($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_rev_tapas_tapa_multimedia_maximo_orden";
		$sparam=array(

			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar el proximo orden.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;	
	}
	
	protected function ModificarOrden($datos)
	{
		$spnombre="upd_rev_tapas_tapa_multimedia_orden_xrevtapamulcod";
		$sparam=array(
			'prevtapamulorden'=> $datos['revtapamulorden'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			'prevtapamulcod'=> $datos['revtapamulcod']
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno) )
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al modificar el orden de las tapas. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;
		}
		
		return true;	
	}	
	
}
?>