<?php 
/*
CLASE LOGICA PARA EL MANEJO DE LAS ACCIONES DE LAS PAGINAS.
*/
include(DIR_CLASES_DB."cPaginasWorkflowRoles.db.php");

class cPaginasWorkflowRoles extends cPaginasWorkflowRolesdb	
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


//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 
// Retorna un resultado un resultado con las acciones que se pueden realizar por distintos filtros

// Parámetros de Entrada:
//		datos: arreglo de datos
//			rolcod = codigo del rol (opcional)
//			noticiaestadocodinicial = estado de la noticia inicial (opcional)
//			orderby = orden de devolucion
//			limit = limite de la consulta

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no
	public function BusquedaAvanzada($datos,&$resultado,&$numfilas)
	{
					
		$sparam=array(
			'xpaginaestadocodinicial'=>0,
			'paginaestadocodinicial'=>"",
			'xrolcod'=> 0,
			'rolcod'=>"",
			'orderby'=> "r.rolcod ASC",
			'limit'=> ""
		);	
		if (isset ($datos['pagestadocodbusqueda']) && $datos['pagestadocodbusqueda']!="")
		{
			$sparam['paginaestadocodinicial']= $datos['pagestadocodbusqueda'];
			$sparam['xpaginaestadocodinicial']= 1;
		}
		if (isset ($datos['rolcodbusqueda']) && $datos['rolcodbusqueda']!="")
		{
			$sparam['rolcod']= $datos['rolcodbusqueda'];
			$sparam['xrolcod']= 1;		
		}
		if (isset ($datos['orderby']) && $datos['orderby']!="")
			$sparam['orderby']= $datos['orderby'];
			
		if (isset ($datos['limit']) && $datos['limit']!="")
			$sparam['limit']= $datos['limit'];
		
		if (!parent::BusquedaAvanzada ($sparam,$resultado,$numfilas))
			return false;
		return true;	
	}

//------------------------------------------------------------------------------------------	
//----------------------------------------------------------------------------------------- 
// Retorna las acciones de un rol en un estado de la pagina

// Parámetros de Entrada:
//		datos: arreglo de datos
//			rolcod = codigo del usuario
//			pagestadocod = estado de la pagina

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function ObtenerAccionesRol($datos,&$resultado,&$numfilas)
	{
		$datos['paginaestadocodinicial'] = $datos['pagestadocod'];
		$arregloestadocodfinal[] = PAGPUBLICADA;
		$arregloestadocodfinal[] = PAGELIMINADA;
		$datos['paginaestadocodfinalnot'] = PAGPUBLICADA.",".PAGELIMINADA;
		if (isset($datos['arregloestadofinal']) && count($datos['arregloestadofinal']))
			foreach ($datos['arregloestadofinal'] as $estado)
				$arregloestadocodfinal[] = $estado;
			
		$datos['paginaestadocodfinalnot'] = implode(",",$arregloestadocodfinal);
		
		if (!parent::ObtenerAccionesRol($datos,$resultado,$numfilas))
			return false;
		
		return true;
	}


//----------------------------------------------------------------------------------------- 
// Retorna verdadero o falso si tiene o no la acción de publicar

// Parámetros de Entrada:
//		datos: arreglo de datos
//			rolcod = codigo del usuario
//			pagestadocod = estado de la pagina

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function TieneAccionPublicar($datos,&$nombreboton,&$paginaworkflowcod)
	{
		$datos['paginaestadocodinicial'] = $datos['pagestadocod'];
		$datos['paginaestadocodfinal'] = PAGPUBLICADA;
		if (!parent::ObtenerAccionxEstadosxRol($datos,$resultado,$numfilas))
			return false;
		$nombreboton="";
		if ($numfilas>0)
		{
			$datos = $this->conexion->ObtenerSiguienteRegistro($resultado);
			$nombreboton = $datos['paginaaccion'];
			$paginaworkflowcod = $datos['paginaworkflowcod'];
			return true;
		}
		
		return false;
	}

//----------------------------------------------------------------------------------------- 
// Retorna verdadero o falso si tiene o no la acción de eliminar

// Parámetros de Entrada:
//		datos: arreglo de datos
//			rolcod = codigo del usuario
//			pagestadocod = estado de la pagina

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function TieneAccionEliminar($datos,&$nombreboton,&$noticiaworkflowcod)
	{
		$datos['paginaestadocodinicial'] = $datos['pagestadocod'];
		$datos['paginaestadocodfinal'] = PAGELIMINADA;
		if (!parent::ObtenerAccionxEstadosxRol($datos,$resultado,$numfilas))
			return false;
		$nombreboton="";
		if ($numfilas>0)
		{
			$datos = $this->conexion->ObtenerSiguienteRegistro($resultado);
			$nombreboton = $datos['paginaaccion'];
			$paginaworkflowcod = $datos['paginaworkflowcod'];
			return true;
		}

		return false;
	}

//----------------------------------------------------------------------------------------- 
// Retorna verdadero o falso si tiene o no la acción de despublicar


// Parámetros de Entrada:
//		datos: arreglo de datos
//			rolcod = codigo del usuario
//			pagestadocod = estado de la pagina

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function TieneAccionDespublicar($datos,&$nombreboton,&$noticiaworkflowcod)
	{
		$datos['paginaestadocodinicial'] = $datos['pagestadocod'];
		$datos['paginaestadocodfinal'] = PAGDESPUBLICADA;
		if (!parent::ObtenerAccionxEstadosxRol($datos,$resultado,$numfilas))
			return false;
		$nombreboton="";
		if ($numfilas>0)
		{
			$datos = $this->conexion->ObtenerSiguienteRegistro($resultado);
			$nombreboton = $datos['paginaaccion'];
			$paginaworkflowcod = $datos['paginaworkflowcod'];
			return true;
		}
		return false;
	}
	
	
//----------------------------------------------------------------------------------------- 
// Retorna las acciones de un rol en un estado de la pagina

// Parámetros de Entrada:
//		datos: arreglo de datos
//			paginaestadocod = estado actual de la noticia
//			rolcod = codigo del rol
//			workflowcod = codigo de la acción a realizar

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function PuedeRealizarAccionPagina($datos,&$datosworkflow)
	{
		
		$datosworkflow = array();
		$datos['paginaestadocodinicial'] = $datos['pagestadocod'];
		if (!parent::ObtenerAccionesxRolxEstadoxWorkflowCod($datos,$resultado,$numfilas))
			return false;
		
		if ($numfilas>0)
		{
			$datosworkflow = $this->conexion->ObtenerSiguienteRegistro($resultado); 
			return true;
		}
		return false;
	}
	

//----------------------------------------------------------------------------------------- 
// Retorna los workflowcod de un rol de la pagina

// Parámetros de Entrada:
//		datos: arreglo de datos
//			rolcod = codigo del usuario

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function ObtenerWorkflowCodxRol($datos,&$resultado,&$numfilas)
	{
		if (!parent::ObtenerWorkflowCodxRol($datos,$resultado,$numfilas))
			return false;
		
		return true;
	}
	
	
//----------------------------------------------------------------------------------------- 
// Retorna los workflowcod de un rol a partir de un estado inicial de una pagina

// Parámetros de Entrada:
//		datos: arreglo de datos
//			rolcod = codigo del usuario
//			pagestadocod = estado inicial de la pagina


// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function ObtenerWorkflowCodxRolxEstadoInicial($datos,&$resultado,&$numfilas)
	{
		$datos['paginaestadocodinicial'] = $datos['pagestadocod'];
		if (!parent::ObtenerWorkflowCodxRolxEstadoInicial($datos,$resultado,$numfilas))
			return false;
		
		return true;
	}

	
//------------------------------------------------------------------------------------------	
// Insertar un nuevo workflow de la pagina al rol

// Parámetros de Entrada:
//		datos: array asociativo con los datos a agregar
//			rolcod = codigo del rol
//			paginaworkflowcod = codigo del workflow de la pagina
// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function Insertar($datos)
	{
		if (!$this->_ValidarInsertar($datos))
			return false;
					
		if (!parent::Insertar($datos))
			return false;
		
		return true;
	}
//----------------------------------------------------------------------------------------- 
// Eliminar un workflow a un rol
// Parámetros de Entrada:
//		datos: arreglo de datos
//			rolcod = codigo del rol
//			paginaworkflowcod = codigo del workflow de la pagina
// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	
	public function Eliminar($datos)
	{
		if (!$this->_ValidarEliminar($datos))
			return false;
					
		if (!parent::Eliminar($datos))
			return false;
		
		return true;
	}
//----------------------------------------------------------------------------------------- 
// Retorna un array con los id selecionados
// Parámetros de Entrada:
//		noticiaworkflowcod_ = codigo de la noticia workflow.
// Retorna:
//		arrayfinal= array con los id selecionados.
//		la función retorna true o false si se pudo ejecutar con éxito o no	

	public function ObtenerIdsSeleccionados($datos,&$arrayfinal)
	{
		$arrayfinal=array();
		foreach ($datos as $nombre_var => $valor_var) {
			if (empty($valor_var)) {
				$vacio[$nombre_var] = $valor_var;
			} else {
				
				$post[$nombre_var] = $valor_var;
				$opcion = substr($nombre_var,0,18);
				if ($opcion=="paginaworkflowcod_")
					$arrayfinal[] = $valor_var;
			}
		}
		return true;
	}

//----------------------------------------------------------------------------------------- 
// Actualiza el codigo de una noticia workflow roles
// Parámetros de Entrada:
//		datos: array asociativo con los datos a agregar
// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no
	public function ActualizarWorkflow ($datos)
	{
		
		if (!$this->ObtenerIdsSeleccionados($datos,$arreglocodigos))
			return false;
		//print_r($arreglocodigos);
		
		if(!$this->ObtenerWorkflowCodxRolxEstadoInicial($datos,$resultadoworkfow,$numfilasworkflow))
			return false;
		
		$arreglocodigosinsertados = array();	
		while($fila=$this->conexion->ObtenerSiguienteRegistro($resultadoworkfow))
			$arreglocodigosinsertados[$fila['paginaworkflowcod']] = $fila['paginaworkflowcod'];	
			
			
		$arregloeliminar = array_diff($arreglocodigosinsertados,$arreglocodigos);
		$arregloinsertar = array_diff($arreglocodigos,$arreglocodigosinsertados);
		
		
		foreach($arregloeliminar as $paginaworkflowcod)
		{
			//ELIMINO ROLCOD, PAGINAWORKFLOWCOD	
			$datos['paginaworkflowcod'] = $paginaworkflowcod;
			if(!$this->Eliminar($datos))
				return false;
		}
		foreach($arregloinsertar as $paginaworkflowcod)
		{
			//INSERTO ROLCOD, PAGINAWORKFLOWCOD	
			$datos['paginaworkflowcod'] = $paginaworkflowcod;
			if(!$this->Insertar($datos))
				return false;
			
		}
		return true;

	}

//----------------------------------------------------------------------------------------- 
// Function que valida los datos al momento de eliminar un nuevo noticia workflow roles
// Parámetros de Entrada:
//		datos: array asociativo con los datos a validar
// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	
	private function _ValidarEliminar($datos)
	{
		$oRoles = new cRoles($this->conexion);
		$oRoles->RolesSP($spnombre,$spparam);
		$arraybusq=array("rolcod"=>$datos['rolcod']);
		if(!$this->conexion->BuscarRegistroxClave($spnombre,$spparam,$arraybusq,$query,$filalib,$numfilasmatcheo,$errno) || $numfilasmatcheo!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_INF,"No existe el rol. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
			return false;
		}
		return true;
	}
//----------------------------------------------------------------------------------------- 
// Function que valida los datos al momento de insertar un nuevo noticia workflow roles
// Parámetros de Entrada:
//		datos: array asociativo con los datos a validar
// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no	
	private function _ValidarInsertar($datos)
	{
		$oRoles = new cRoles($this->conexion);
		$oRoles->RolesSP($spnombre,$spparam);
		$arraybusq=array("rolcod"=>$datos['rolcod']);
		if(!$this->conexion->BuscarRegistroxClave($spnombre,$spparam,$arraybusq,$query,$filalib,$numfilasmatcheo,$errno) || $numfilasmatcheo!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_INF,"No existe el rol. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
			return false;
		}
		return true;
	}
	
	

}//fin clase	

?>