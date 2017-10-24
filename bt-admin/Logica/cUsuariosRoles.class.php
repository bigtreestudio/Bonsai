<?php  
include(DIR_CLASES_DB."cUsuariosRoles.db.php");

class cUsuariosRoles extends cUsuariosRolesdb
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


	public function AltaUsuarioRol($datos)
	{
		
		if (!$this->_ValidarDatosAltaUsuarioRol($datos))
			return false;
	
		if (!parent::AltaUsuarioRol($datos))
			return false;
		
		return true;

	}
	
	public function AltaUsuarioRolExt($datos)
	{
		if (!parent::AltaUsuarioRol($datos))
			return false;
		
		return true;

	}


	public function BajaUsuarioRol($datos)
	{
		
		if (!$this->_ValidarDatosBajaUsuarioRol($datos))
			return false;
	
		if (!parent::BajaUsuarioRol($datos))
			return false;
		
		return true;

	}


	public function ObtenerDatosCheckRoles($datos,&$arrayfinal)
	{
		
		$arrayfinal=array();
		foreach ($datos as $nombre_var => $valor_var) {
			if (empty($valor_var)) {
				$vacio[$nombre_var] = $valor_var;
			} else {
				
				$post[$nombre_var] = $valor_var;
				$opcion = substr($nombre_var,0,7);
				if ($opcion=="rolcod_")
				{
					$arrayfinal[] = $valor_var;
				}
			}
		}
		return true;
	}



	public function ActualizarRolesUsuario($datos)
	{
		//array de roles a asignar
		if (!$this->ObtenerDatosCheckRoles($datos,$arrayfinal))
			return false;
		
		$oRoles=new cRoles($this->conexion);
		if (!$oRoles->RolesDeUnUsuario($datos['usuariocod'],$numfilas,$resultadoroles))
			return false;
				
		if (!$oRoles->TraerRolesActualizar($_SESSION,$resultado,$numfilas))
			return false;
			
		$arregloroles = array();
		while ($fila = $this->conexion->ObtenerSiguienteRegistro($resultado))
			$arregloroles[] = $fila['rolcodactualizado'];
		
		$arrayinicial = array();
		while ($fila = $this->conexion->ObtenerSiguienteRegistro($resultadoroles))
		{	
			if (in_array($fila['rolcod'],$arregloroles))
				$arrayinicial[] = $fila['rolcod'];
		}
		
		$arraysacar = array_diff($arrayinicial,$arrayfinal);
		$arrayponer = array_diff($arrayfinal,$arrayinicial);

		$datosinsertar['usuariocod'] = $datos['usuariocod'];
		foreach($arrayponer as $rolcod)
		{
			$datosinsertar['rolcod'] = $rolcod;
			if (!$this->AltaUsuarioRol($datosinsertar))
				return false;
		}
		
		$datoseliminar['usuariocod'] = $datos['usuariocod'];
		foreach($arraysacar as $rolcod)
		{
			$datoseliminar['rolcod'] = $rolcod;
			if (!$this->BajaUsuarioRol($datoseliminar))
				return false;
		}

		return true;
	}


//-----------------------------------------------------------------------------------------
//							 PRIVADAS	
//----------------------------------------------------------------------------------------- 
	private function _ValidarDatosUsuarioRol($datos)
	{
		$oRoles=new cRoles($this->conexion);
		if (!$oRoles->RolesPosiblesAsignar($_SESSION['rolcod'],$datos['usuariocod'],$numfilas,$roles_sin_asignar))			
			return false;

		if (!in_array($datos['rolcod'],$roles_sin_asignar))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRSOSP,"Error, no puede asignar dicho rol. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
			return false;
		}
	
	
		return true;
	}
	
	
	private function _ValidarDatosAltaUsuarioRol($datos)
	{
		if (!$this->_ValidarDatosUsuarioRol($datos))
			return false;
	
	
		return true;
	}

	private function _ValidarDatosBajaUsuarioRol($datos)
	{


		return true;
	}




}


?>