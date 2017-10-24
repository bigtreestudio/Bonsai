<?php  
abstract class cNoticiasWorkflowdb
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

//----------------------------------------------------------------------------------------- 


//----------------------------------------------------------------------------------------- 
// Retorna las acciones de un rol en un estado de la noticia

// Parámetros de Entrada:
//		datos: arreglo de datos
//			noticiaestadoinicial = estado de la noticia

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no
	protected function ObtenerAccionesEstadoInicial($datos,&$resultado,&$numfilas)
	{
		
		$spnombre="sel_not_noticias_workflow_xnoticiestadocodinicial";
		$sparam=array(
			'pnoticiaestadocodinicial'=> $datos['noticiestadocodinicial']
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar las acciones por estado inicial.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	}

//----------------------------------------------------------------------------------------- 
// Retorna en un arreglo con los datos de una noticia workflow
// Parámetros de Entrada:
//		datos: arreglo de datos
//			visualizacioncod = codigo de la visualizacion

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no

	protected function BuscarNoticiaWorkflowxCodigo($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_not_noticias_workflow_xnoticiaworkflowcod";
		$sparam=array(
			'pnoticiaworkflowcod'=> $datos['noticiaworkflowcod']
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar la noticia workflow por código.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;	
	}
	
 
//------------------------------------------------------------------------------------------	

//----------------------------------------------------------------------------------------- 
// Retorna en un arreglo con los datos de una noticia workflow
// Parámetros de Entrada:
//		datos: arreglo de datos
//			visualizacioncod = codigo de la visualizacion

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no

	protected function BuscaNoticiasxNoticiaestadocodinicial_Noticiaestadocodfinal($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_not_noticias_workflow_x_noticiaestadocodinicial_noticiaestadocodfinal";
		$sparam=array(
			'pnoticiaestadocodinicial'=> $datos['noticiaestadocodinicial'],
			'pnoticiaestadocodfinal'=> $datos['noticiaestadocodfinal']
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar la noticia workflow por estado inicial y estado final.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;	
	}
	
 
//------------------------------------------------------------------------------------------	

//------------------------------------------------------------------------------------------	
// Retorna en un arreglo con los datos de una noticia workflow

// Parámetros de Entrada:
//			$datos = array asociativos
//			noticiaestadocodinicial: descripción del estado inicial de la noticia workflow
//			noticiaestadocodfinal: descripción del estado final de la noticia workflow
//			noticiaaccion: descripcion de la accion
// Retorna:
//		resultado= Arreglo con todos los datos de un formato multimedia.
//		numfilas= cantidad de filas 
//		la función retorna true o false si se pudo ejecutar con éxito o no		
	

	protected function BusquedaAvanzada ($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_not_noticias_workflow_busqueda";
		$sparam=array(
			'pxnoticiaestadocodinicial'=> $datos['xnoticiaestadocodinicial'],
			'pnoticiaestadocodinicial'=> $datos['noticiaestadocodinicial'],
			'pxnoticiaestadocodfinal'=> $datos['xnoticiaestadocodfinal'],
			'pnoticiaestadocodfinal'=> $datos['noticiaestadocodfinal'],
			'pxnoticiaaccion'=> $datos['xnoticiaaccion'],
			'pnoticiaaccion'=> $datos['noticiaaccion'],
			'porderby'=> $datos['orderby'],
			'plimit'=> $datos['limit']
			);	
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar las noticias Workflow.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	}
	
//----------------------------------------------------------------------------------------- 
// Inserta nueva noticia workflow

// Parámetros de Entrada:
//			noticiaestadocodinicial: descripción del estado inicial de la noticia workflow
//			noticiaestadocodfinal: descripción del estado final de la noticia workflow
//			noticiaaccion: descripcion de la accion
// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	protected function Insertar($datos,&$codigoinsertado)
	{

		$spnombre="ins_not_noticias_workflow";
		$sparam=array(
			'pnoticiaestadocodinicial'=> $datos['noticiaestadocodinicial'],
			'pnoticiaestadocodfinal'=> $datos['noticiaestadocodfinal'],
			'pnoticiaaccion'=> $datos['noticiaaccion'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s")
			);
	
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al insertar la noticia workflow.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	} 

//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 
// Modifica los datos de una noticia workflow

// Parámetros de Entrada:
//			noticiaworkflowcod = codigo de la visualizacion
//			noticiaestadocodinicial: descripción del estado inicial de la noticia workflow
//			noticiaestadocodfinal: descripción del estado final de la noticia workflow
//			noticiaaccion: descripcion de la accion
// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	protected function Modificar($datos)
	{
		$spnombre="upd_not_noticias_workflow_xnoticiaworkflowcod";
		$sparam=array(
			'pnoticiaaccion'=> $datos['noticiaaccion'],
			'pnoticiaestadocodinicial'=> $datos['noticiaestadocodinicial'],
			'pnoticiaestadocodfinal'=> $datos['noticiaestadocodfinal'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			'pnoticiaworkflowcod'=> $datos['noticiaworkflowcod']
			);	
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al modificar la noticia workflow.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	} 
	
//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 

// Eliminar una noticia workflow
// Parámetros de Entrada:
//		datos: arreglo de datos
//			noticiaworkflowcod = codigo de la visualizacion

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	protected function Eliminar($datos)
	{
		$spnombre="del_not_noticias_workflow_xnoticiaworkflowcod";
		$sparam=array(
			'pnoticiaworkflowcod'=> $datos['noticiaworkflowcod']
			);
	
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al eliminar la noticia workflow.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	} 

}


?>