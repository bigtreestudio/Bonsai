<?php 
/*
CLASE LOGICA PARA EL MANEJO DE LAS ACCIONES DE LAS NOTICIAS.
*/
include(DIR_CLASES_DB."cNoticiasWorkflowRoles.db.php");

class cNoticiasWorkflowRoles extends cNoticiasWorkflowRolesdb	
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
// Parámetros de Entrada:
//		datos: arreglo de datos
//			
// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function BusquedaAvanzada($datos,&$resultado,&$numfilas)
	{
					
		$sparam=array(
			'xnoticiaestadocodinicial'=>0,
			'noticiaestadocodinicial'=>"",
			'xrolcod'=> 0,
			'rolcod'=>"",
			'orderby'=> "r.rolcod ASC",
			'limit'=> ""
		);	
		if (isset ($datos['noticiaestadocodbusqueda']) && $datos['noticiaestadocodbusqueda']!="")
		{
			$sparam['noticiaestadocodinicial']= $datos['noticiaestadocodbusqueda'];
			$sparam['xnoticiaestadocodinicial']= 1;
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
// Retorna las acciones de un rol en un estado de la noticia

// Parámetros de Entrada:
//		datos: arreglo de datos
//			rolcod = codigo del usuario
//			noticiaestadocod = estado de la noticia

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function ObtenerAccionesRol($datos,&$resultado,&$numfilas)
	{
		$datos['noticiaestadocodinicial'] = $datos['noticiaestadocod'];
		$arregloestadocodfinal[] = NOTPUBLICADA;
		$arregloestadocodfinal[] = NOTELIMINADA;
		$datos['noticiaestadocodfinalnot'] = NOTPUBLICADA.",".NOTELIMINADA;
		if (isset($datos['arregloestadofinal']) && count($datos['arregloestadofinal']))
			foreach ($datos['arregloestadofinal'] as $estado)
				$arregloestadocodfinal[] = $estado;
			
		$datos['noticiaestadocodfinalnot'] = implode(",",$arregloestadocodfinal);
		
		if (!parent::ObtenerAccionesRol($datos,$resultado,$numfilas))
			return false;
		
		return true;
	}



//----------------------------------------------------------------------------------------- 
// Retorna verdadero o falso si tiene o no la acción de publicar

// Parámetros de Entrada:
//		datos: arreglo de datos
//			rolcod = codigo del usuario
//			noticiaestadocod = estado de la noticia

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function TieneAccionPublicar($datos,&$nombreboton,&$noticiaworkflowcod)
	{
		$datos['noticiaestadocodinicial'] = $datos['noticiaestadocod'];
		$datos['noticiaestadocodfinal'] = NOTPUBLICADA;
		if (!parent::ObtenerAccionxEstadosxRol($datos,$resultado,$numfilas))
			return false;
		$nombreboton="";
		if ($numfilas>0)
		{
			$datos = $this->conexion->ObtenerSiguienteRegistro($resultado);
			$nombreboton = $datos['noticiaaccion'];
			$noticiaworkflowcod = $datos['noticiaworkflowcod'];
			return true;
		}
		
		return false;
	}


//----------------------------------------------------------------------------------------- 
// Retorna verdadero o falso si tiene o no la acción de publicar

// Parámetros de Entrada:
//		datos: arreglo de datos
//			rolcod = codigo del usuario
//			noticiaestadocod = estado de la noticia

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function TieneAccionEliminar($datos,&$nombreboton,&$noticiaworkflowcod)
	{
		$datos['noticiaestadocodinicial'] = $datos['noticiaestadocod'];
		$datos['noticiaestadocodfinal'] = NOTELIMINADA;
		if (!parent::ObtenerAccionxEstadosxRol($datos,$resultado,$numfilas))
			return false;
		
		$nombreboton="";
		if ($numfilas>0)
		{
			$datos = $this->conexion->ObtenerSiguienteRegistro($resultado);
			$nombreboton = $datos['noticiaaccion'];
			$noticiaworkflowcod = $datos['noticiaworkflowcod'];
			return true;
		}
		return false;
	}

//----------------------------------------------------------------------------------------- 
// Retorna verdadero o falso si tiene o no la acción de publicar

// Parámetros de Entrada:
//		datos: arreglo de datos
//			rolcod = codigo del usuario
//			noticiaestadocod = estado de la noticia

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function TieneAccionDespublicar($datos,&$nombreboton,&$noticiaworkflowcod)
	{
		$datos['noticiaestadocodinicial'] = $datos['noticiaestadocod'];
		$datos['noticiaestadocodfinal'] = NOTDESPUBLICADAS;
		if (!parent::ObtenerAccionxEstadosxRol($datos,$resultado,$numfilas))
			return false;
		
		$nombreboton="";
		if ($numfilas>0)
		{
			$datos = $this->conexion->ObtenerSiguienteRegistro($resultado);
			$nombreboton = $datos['noticiaaccion'];
			$noticiaworkflowcod = $datos['noticiaworkflowcod'];
			return true;
		}
		return false;
	}


//----------------------------------------------------------------------------------------- 
// Retorna los workflowcod de un rol de la noticia

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
// Retorna los workflowcod de un rol de la noticia

// Parámetros de Entrada:
//		datos: arreglo de datos
//			rolcod = codigo del usuario
//          noticiaestadocodinicial = estado inicial de la noticia

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function ObtenerWorkflowCodxRolxEstadoInicialNoticia($datos,&$resultado,&$numfilas)
	{
		$datos['noticiaestadocodinicial'] = $datos['noticiaestadocod'];
		if (!parent::ObtenerWorkflowCodxRolxEstadoInicialNoticia($datos,$resultado,$numfilas))
			return false;
		
		return true;
	}

//----------------------------------------------------------------------------------------- 

// Retorna las acciones de un rol en un estado de la noticia

// Parámetros de Entrada:
//		datos: arreglo de datos
//			rolcod = codigo del usuario
//			noticiaestadocod = estado de la noticia

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function ObtenerAccionesxRolxWorkflowCod($datos,&$resultado,&$numfilas)
	{
		if (!parent::ObtenerAccionesxRolxWorkflowCod($datos,$resultado,$numfilas))
			return false;
		
		return true;
	}



//----------------------------------------------------------------------------------------- 
// Retorna las acciones de un rol en un estado de la noticia

// Parámetros de Entrada:
//		datos: arreglo de datos
//			noticiaestadocod = estado actual de la noticia
//			rolcod = codigo del rol
//			workflowcod = codigo de la acción a realizar

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function PuedeRealizarAccionNoticia($datos,&$datosworkflow)
	{
		
		$datosworkflow = array();
		$datos['noticiaestadocodinicial'] = $datos['noticiaestadocod'];
		if (!parent::ObtenerAccionesxRolxEstadoxWorkflowCod($datos,$resultado,$numfilas))
			return false;
		
		if ($numfilas>0)
		{
			$datosworkflow = $this->conexion->ObtenerSiguienteRegistro($resultado); 
			return true;
		}
		return false;
	}

//------------------------------------------------------------------------------------------	
// Insertar un nuevo noticia workflow roles

// Parámetros de Entrada:
//		datos: array asociativo con los datos a agregar
//			rolcod = codigo del rol
//			noticiaworkflowcod = codigo de la noticia workflow
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
// Eliminar un noticia workflow roles
// Parámetros de Entrada:
//		datos: arreglo de datos
//			rolcod = codigo del rol
//			noticiaworkflowcod = codigo de la noticia workflow
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
				$opcion = substr($nombre_var,0,19);
				if ($opcion=="noticiaworkflowcod_")
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
		
		if(!$this->ObtenerWorkflowCodxRolxEstadoInicialNoticia($datos,$resultadoworkfow,$numfilasworkflow))
			return false;
		
		$arreglocodigosinsertados = array();	
		while($fila=$this->conexion->ObtenerSiguienteRegistro($resultadoworkfow))
			$arreglocodigosinsertados[$fila['noticiaworkflowcod']] = $fila['noticiaworkflowcod'];	
			
			
		$arregloeliminar = array_diff($arreglocodigosinsertados,$arreglocodigos);
		$arregloinsertar = array_diff($arreglocodigos,$arreglocodigosinsertados);
		
		
		foreach($arregloeliminar as $noticiaworkflowcod)
		{
			//ELIMINO ROLCOD, NOTICIAWORKFLOWCOD	
			$datos['noticiaworkflowcod'] = $noticiaworkflowcod;
			if(!$this->Eliminar($datos))
				return false;
		}
		foreach($arregloinsertar as $noticiaworkflowcod)
		{
			//INSERTO ROLCOD, NOTICIAWORKFLOWCOD	
			$datos['noticiaworkflowcod'] = $noticiaworkflowcod;
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