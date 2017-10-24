<?php  
abstract class cRolesdb
{
	
	// Constructor de la clase
	function __construct(){


    } 
	
	// Destructor de la clase
	function __destruct() {	
    } 	


//----------------------------------------------------------------------------------------- 
// Alta de un rol

// Parámetros de Entrada:
//		datos: un array asociativo con los datos a cargar

// Retorna:
//		rolcod: es el codigo del nuevo rol
//		la función retorna true o false si se pudo ejecutar con éxito o no

	protected function Insertar($datos,&$rolcod)
	{
			
		$spnombre="ins_roles";
		$spparam =array("prolcod"=>$datos['rolcodnuevo']);
		$spparam+=array("prolnom"=>$datos['rolnom']);
		$spparam+=array("proldesc"=>$datos['roldesc']);
		$spparam+=array("pultmodusuario"=>$_SESSION['usuariocod']);
		$spparam+=array("pultmodfecha"=>date("Y/m/d H:i:s"));

		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$spparam,$query,$numfilas,$errno) || $numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"No se ha podido insertar el rol.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
			return false;
		}
		
		$rolcod=$datos["rolcodnuevo"];
		
		return true;
	}

//----------------------------------------------------------------------------------------- 
// Modificar un rol

// Parámetros de Entrada:
//		datos: un array asociativo con los datos nuevos
//		rolcod: es el codigo del rol a modificar

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	protected function Modificar($datos,$rolcod)
	{
		$spnombre="upd_roles_xrolcod";
		$spparam =array("prolcodviejo"=>$datos['rolcodviejo']);
		$spparam+=array("prolcodnuevo"=>$datos['rolcodnuevo']);
		$spparam+=array("prolnom"=>$datos['rolnom']);
		$spparam+=array("proldesc"=>$datos['roldesc']);
		$spparam+=array("pultmodusuario"=>$_SESSION['usuariocod']);
		$spparam+=array("pultmodfecha"=>date("Y/m/d H:i:s"));

		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$spparam,$query,$numfilas,$errno) || $numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"No se ha podido actualizar el rol.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
			return false;
		}
				
		return true;
	}



//----------------------------------------------------------------------------------------- 
// Borra un rol

// Parámetros de Entrada:
//		rolcod: es el codigo del rol a borrar

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	protected function Eliminar($rolcod)
	{	
		$spnombre="del_roles_xrolcod";
		$spparam =array("prolcod"=>$rolcod);

		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$spparam,$query,$numfilas,$errno) || $numfilas!=1)
		{
			if($errno==MYSQLFORKEY)
				FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_INF,"Existen módulos/roles relacionados con el rol '".$rolcod."'. Imposible borrarlo. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
			else
				FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"No se han podido borrar el rol.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));

			return false;
		}

		return true;
	} 

//-----------------------------------------------------------------------------------------
//							 PRIVADAS	
//----------------------------------------------------------------------------------------- 

	
//----------------------------------------------------------------------------------------- 
// Retorna una consulta con todos los roles asociados a un usuario

// Parámetros de Entrada:
//		usuariocod: buscar roles a los que acceda este usuario

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no

	protected function RolesDeUnUsuario($usuariocod,&$numfilas,&$resultado)
	{
		$this->RolesDeUnUsuarioSP($usuariocod,$spnombre,$spparam);

		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$spparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error seleccionando roles de un usuario. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
			return false;
		}
		
		return true;
	}

//----------------------------------------------------------------------------------------- 
// Retorna el SP y los parametros para cargar los roles de un usuario

// Parámetros de Entrada:
//		usuariocod:

// Retorna:
//		spnombre,spparam
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	protected function RolesDeUnUsuarioSP($usuariocod,&$spnombre,&$spparam)
	{
		$spnombre="sel_roles_xusuariocod";
		$spparam=array("pusuariocod"=>$usuariocod);
		
		return true;
	}



	protected function TraerRolesSinAsignar($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_roles_abm_roles_sin_asignar_xrolactualiza";
		$spparam=array("prolcodactualiza"=>$datos['rolcodactualiza'],"prolesasignados"=>$datos['in_roles_asignados']);

		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$spparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"No se ha podido traer los roles sin asignar de un usuario.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
			return false;
		}
		
		return true;	
	}


}


?>