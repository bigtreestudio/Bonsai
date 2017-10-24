<?php  
abstract class cPaginasWorkflowRolesdb
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
// Retorna un resultado con filtros de la tabla pag_paginas_workflow_roles

// Parámetros de Entrada:
//		datos: arreglo de datos
//			rolcod = codigo del rol (opcional)
//			noticiaestadocodinicial = estado de la noticia inicial (opcional)
//			orderby = orden de devolucion
//			limit = limite de la consulta

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no

	protected function BusquedaAvanzada ($datos,&$resultado,&$numfilas)
	{
		
		$spnombre="sel_pag_paginas_workflow_roles_xbusqueda";
		$sparam=array(
			'pxrolcod'=> $datos['xrolcod'],
			'prolcod'=> $datos['rolcod'],
			'ppaginaestadocodinicial'=> $datos['paginaestadocodinicial'],
			'pxpaginaestadocodinicial'=> $datos['xpaginaestadocodinicial'],
			'porderby'=> $datos['orderby'],
			'plimit'=> $datos['limit']
			);
			
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar la acciones de un rol (busqueda avanzada). ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
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
		$spnombre="sel_pag_paginas_workflow_roles_acciones_xrol_xestadoinicial";
		$sparam=array(
			'prolcod'=> $datos['rolcod'],
			'ppaginaestadocodinicial'=> $datos['paginaestadocodinicial'],
			'ppaginaestadocodfinalnot'=> $datos['paginaestadocodfinalnot']
			);	

		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar las acciones del rol.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	}

//----------------------------------------------------------------------------------------- 
// Retorna las acciones de un rol en un estado de la pagina

// Parámetros de Entrada:
//		datos: arreglo de datos
//			rolcod = codigo del usuario
//			pagestadocod = estado de la pagina

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no
	protected function ObtenerAccionxEstadosxRol($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_pag_paginas_workflow_roles_acciones_xrol_xestadoinicial_xestadofinal";
		$sparam=array(
			'prolcod'=> $datos['rolcod'],
			'ppaginaestadocodinicial'=> $datos['paginaestadocodinicial'],
			'ppaginaestadocodfinal'=> $datos['paginaestadocodfinal']
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
//			pagestado = estado de la pagina

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no
	protected function ObtenerAccionesxRolxEstadoxWorkflowCod($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_pag_paginas_workflow_roles_xrolcod_paginaestadocodinicial_paginaworkflowcod";
		$sparam=array(
			'prolcod'=> $datos['rolcod'],
			'ppaginaworkflowcod'=> $datos['paginaworkflowcod'],
			'ppaginaestadocodinicial'=> $datos['paginaestadocodinicial']
			);
	
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar la accion por rol, estado y id.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	}

//----------------------------------------------------------------------------------------- 
// Retorna los workflowcod de un rol a partir de un estado inicial de una pagina

// Parámetros de Entrada:
//		datos: arreglo de datos
//			rolcod = codigo del usuario
//			paginaestadocodinicial = estado inicial de la pagina


// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no
	protected function ObtenerWorkflowCodxRolxEstadoInicial($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_pag_paginas_workflow_roles_xrolcod_paginaestadocodinicial";
		$sparam=array(
			'prolcod'=> $datos['rolcod'],
			'ppaginaestadocodinicial'=> $datos['paginaestadocodinicial']
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar las acciones del rol por estado inicial.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	}
	


//----------------------------------------------------------------------------------------- 
// Retorna los workflowcod de un rol de la pagina

// Parámetros de Entrada:
//		datos: arreglo de datos
//			rolcod = codigo del usuario

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no
	protected function ObtenerWorkflowCodxRol($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_pag_paginas_workflow_roles_xrolcod";
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



//------------------------------------------------------------------------------------------	
// Insertar un nuevo noticia workflow roles
// Parámetros de Entrada:
//		datos: array asociativo con los datos a agregar
//			rolcod = codigo del rol
//			paginaworkflowcod = codigo del workflow de la pagina
// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	protected function Insertar($datos)
	{
		$spnombre="ins_pag_paginas_workflow_roles";
		$sparam=array(
			'prolcod'=> $datos['rolcod'],
			'ppaginaworkflowcod'=> $datos['paginaworkflowcod'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s")
			);

		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al insertar el workflow al rol. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
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
		$spnombre="del_pag_paginas_workflow_roles_xrolcod_paginaworkflowcod";
		$sparam=array(
			'prolcod'=> $datos['rolcod'],
			'ppaginaworkflowcod'=> $datos['paginaworkflowcod']
			);		
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al eliminar workflow del rol. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
			return false;
		}

		return true;	
	}
	
	
}

?>