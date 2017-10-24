<?php  
abstract class cNoticiasWorkflowRolesdb
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
// Retorna si un rol puede realizar una acción

// Parámetros de Entrada:
//		datos: arreglo de datos
//			rolcod = codigo del usuario
//			noticiaestado = estado de la noticia

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no
	protected function BusquedaAvanzada ($datos,&$resultado,&$numfilas)
	{
		
		$spnombre="sel_not_noticias_workflow_roles_xbusqueda";
		$sparam=array(
			'pxrolcod'=> $datos['xrolcod'],
			'prolcod'=> $datos['rolcod'],
			'pnoticiaestadocodinicial'=> $datos['noticiaestadocodinicial'],
			'pxnoticiaestadocodinicial'=> $datos['xnoticiaestadocodinicial'],
			'porderby'=> $datos['orderby'],
			'plimit'=> $datos['limit']
			);
			
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar el album - galeria. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
			return false;
		}


		return true;
	}	

//----------------------------------------------------------------------------------------- 
// Retorna las acciones de un rol en un estado de la noticia

// Parámetros de Entrada:
//		datos: arreglo de datos
//			rolcod = codigo del usuario
//			noticiaestado = estado de la noticia

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no
	protected function ObtenerAccionesRol($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_not_noticias_workflow_roles_acciones_xrol_xestadoinicial";
		$sparam=array(
			'prolcod'=> $datos['rolcod'],
			'pnoticiaestadocodinicial'=> $datos['noticiaestadocodinicial'],
			'pnoticiaestadocodfinalnot'=> $datos['noticiaestadocodfinalnot']
			);	

		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar las acciones del rol.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	}

//----------------------------------------------------------------------------------------- 
// Retorna las acciones de un rol en un estado de la noticia

// Parámetros de Entrada:
//		datos: arreglo de datos
//			rolcod = codigo del usuario
//			noticiaestado = estado de la noticia

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no
	protected function ObtenerAccionxEstadosxRol($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_not_noticias_workflow_roles_acciones_xrol_xestadoinicial_xestadofinal";
		$sparam=array(
			'prolcod'=> $datos['rolcod'],
			'pnoticiaestadocodinicial'=> $datos['noticiaestadocodinicial'],
			'pnoticiaestadocodfinal'=> $datos['noticiaestadocodfinal']
			);	

		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar las acciones del rol.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	}


//----------------------------------------------------------------------------------------- 
// Retorna si un rol puede realizar una acción

// Parámetros de Entrada:
//		datos: arreglo de datos
//			rolcod = codigo del usuario
//			noticiaestado = estado de la noticia

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no
	protected function ObtenerAccionesxRolxWorkflowCod($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_not_noticias_workflow_roles_xrolcod_noticiaworkflowcod";
		$sparam=array(
			'prolcod'=> $datos['rolcod'],
			'pnoticiaworkflowcod'=> $datos['noticiaworkflowcod']
			);

		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar las acciones del rol.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	}

//----------------------------------------------------------------------------------------- 
// Retorna los workflowcod de un rol de la noticia

// Parámetros de Entrada:
//		datos: arreglo de datos
//			rolcod = codigo del usuario

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no
	protected function ObtenerWorkflowCodxRol($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_not_noticias_workflow_roles_xrolcod";
		$sparam=array(
			'prolcod'=> $datos['rolcod']
			);
			
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar las acciones del rol.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	}
	

//----------------------------------------------------------------------------------------- 
// Retorna los workflowcod de un rol de la noticia

// Parámetros de Entrada:
//		datos: arreglo de datos
//			rolcod = codigo del usuario
//			noticiaestadocod = estado de la noticia


// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no
	protected function ObtenerWorkflowCodxRolxEstadoInicialNoticia($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_not_noticias_workflow_roles_xrolcod_noticiaestadocodinicial";
		$sparam=array(
			'prolcod'=> $datos['rolcod'],
			'pnoticiaestadocodinicial'=> $datos['noticiaestadocodinicial']
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar las acciones del rol por estado inicial.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	}
	

//----------------------------------------------------------------------------------------- 
// Retorna si un rol puede realizar una acción

// Parámetros de Entrada:
//		datos: arreglo de datos
//			rolcod = codigo del usuario
//			noticiaestado = estado de la noticia

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no
	protected function ObtenerAccionesxRolxEstadoxWorkflowCod($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_not_noticias_workflow_roles_xrolcod_noticiaestadocodinicial_noticiaworkflowcod";
		$sparam=array(
			'prolcod'=> $datos['rolcod'],
			'pnoticiaworkflowcod'=> $datos['noticiaworkflowcod'],
			'pnoticiaestadocodinicial'=> $datos['noticiaestadocodinicial']
			);
	
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar la accion por rol, estado y id.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	}
//------------------------------------------------------------------------------------------	
// Insertar un nuevo noticia workflow roles
// Parámetros de Entrada:
//		datos: array asociativo con los datos a agregar
//			rolcod = codigo del rol
//			noticiaworkflowcod = codigo de la noticia workflow
// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	protected function Insertar($datos)
	{
		$spnombre="ins_not_noticias_workflow_roles";
		$sparam=array(
			'prolcod'=> $datos['rolcod'],
			'pnoticiaworkflowcod'=> $datos['noticiaworkflowcod'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s")
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al insertar workflow. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
			return false;
		}

		return true;	
	}
//----------------------------------------------------------------------------------------- 
// Eliminar un noticia workflow roles
// Parámetros de Entrada:
//		datos: arreglo de datos
//			rolcod = codigo del rol
//			noticiaworkflowcod = codigo de la noticia workflow
// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	protected function Eliminar($datos)
	{
		$spnombre="del_not_noticias_workflow_roles_xrolcod_noticiaworkflowcod";
		$sparam=array(
			'prolcod'=> $datos['rolcod'],
			'pnoticiaworkflowcod'=> $datos['noticiaworkflowcod']
			);
		
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al eliminar workflow. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
			return false;
		}

		return true;	
	}
}

?>