<?php  
include(DIR_CLASES_DB."cUsuariosModulosAcciones.db.php");

class cUsuariosModulosAcciones extends cUsuariosModulosAccionesdb
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
	public function TienePermisosAccion ($codigo)
	{
		$datos['usuariocod'] = $_SESSION['usuariocod'];
		$datos['accioncodigo'] = $codigo;
		if (!parent::BuscarAccionxUsuario ($datos,$resultado,$numfilas))
			return false;
		if ($numfilas>0)
			return true;	
			
		return false;	
	}

	public function BuscarAccionesxUsuario ($datos,&$resultado,&$numfilas)
	{
		if (!parent::BuscarAccionesxUsuario ($datos,$resultado,$numfilas))
			return false;
			
		return true;	
	}


	public function Actualizar($datos)
	{
		$oUsuariosModulosAcciones = new cUsuariosModulosAcciones($this->conexion,$this->formato);
		if(!$oUsuariosModulosAcciones->TienePermisosAccion("000110"))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"No tiene permisos para modificar las acciones.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		$datosdevueltos = array();
		foreach ($datos as $nombre_var => $valor_var) {
			if (empty($valor_var)) {
				$vacio[$nombre_var] = $valor_var;
			} else {
				
				$post[$nombre_var] = $valor_var;
				$opcion = substr($nombre_var,0,13);
				
				if ($opcion=="accioncodigo_")
				{
					$accioncodigo = substr($nombre_var,13,strlen($nombre_var));
					$datosdevueltos[$valor_var]=$valor_var;
				}
				
			}
		}

		if(!$this->BuscarAccionesxUsuario ($datos,$resultado,$numfilas))
			return false;
		$arregloinsertados = array();
		while($fila = $this->conexion->ObtenerSiguienteRegistro($resultado))
			$arregloinsertados[$fila['accioncodigo']] = $fila['accioncodigo'];
		
		$oModulosAcciones = new cModulosAcciones($this->conexion,$this->conexion);
		$datos['rolcodactualiza'] = $_SESSION['rolcod'];
		if(!$oModulosAcciones->BuscarAccionesxUsuarioxRolcodActualiza($datos,$resultadoModulos,$numfilasModulos))
			return false;
		$arregloAccionesHabilitadas = array();	
		while($fila = $this->conexion->ObtenerSiguienteRegistro($resultadoModulos))
			$arregloAccionesHabilitadas[$fila['accioncodigo']] = $fila['accioncodigo'];
		
		$arregloeliminar = array_diff($arregloinsertados,$datosdevueltos);
		$arregloinsertar = array_diff($datosdevueltos,$arregloinsertados);

		$datoseliminar['usuariocod'] = $datosinsertar['usuariocod'] = $datos['usuariocod'];
		foreach ($arregloinsertar as $accioncodigo)
		{
			if (array_key_exists($accioncodigo,$arregloAccionesHabilitadas))
			{
				$datosinsertar['accioncodigo'] = $accioncodigo;
				if(!$this->Insertar($datosinsertar))
					return false;
			}
		}

		foreach ($arregloeliminar as $accioncodigo)
		{
			if (array_key_exists($accioncodigo,$arregloAccionesHabilitadas))
			{
				$datoseliminar['accioncodigo'] = $accioncodigo;
				if(!$this->Eliminar($datoseliminar))
					return false;
			}
		}
		
		return true;	
	}
	
	
	
	public function Insertar($datos)
	{	
		if (!$this->_ValidarInsertar($datos))
			return false;
			
		if (!parent::Insertar ($datos))
			return false;
			
		return true;
	}
	

	
	public function Eliminar($datos)
	{	
		if (!$this->_ValidarEliminar($datos))
			return false;
			
		if (!parent::Eliminar ($datos))
			return false;
			
		return true;
	}
	




// Valida los datos obligatorios

// Parámetros de Entrada:
//	accioncod = Codigo de la accion
//	usuariocod = Codigo del usuario

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	private function _ValidarDatosVacios($datos)
	{
		
		if ($datos['accioncodigo']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe seleccionar una accion. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;
		}
		if ($datos['usuariocod']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe seleccionar un usuario. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;
		}

		return true;
	}


// Valida los insertar datos

// Parámetros de Entrada:
//	accioncod = Codigo de la accion
//	usuariocod = Codigo del usuario

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	
	private function _ValidarInsertar($datos)
	{
		if (!$this->_ValidarDatosVacios($datos))
			return false;

		return true;
	}


// Valida los datos al eliminar una accion a un usuario

// Parámetros de Entrada:
//	accioncod = Codigo de la accion
//	usuariocod = Codigo del usuario

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	
	private function _ValidarEliminar($datos)
	{

		return true;
	}

}


?>