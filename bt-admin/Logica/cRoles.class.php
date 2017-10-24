<?php  
include(DIR_CLASES_DB."cRoles.db.php");

class cRoles extends cRolesdb	
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


	public function RolesSP(&$spnombre,&$spparam)
	{
		$spnombre="sel_roles_orden";
		$spparam=array("porderby"=>"rolcod");
		
		return true;
	}

//----------------------------------------------------------------------------------------- 
// Alta de un rol

// Parámetros de Entrada:
//		datos: un array asociativo con los datos a cargar

// Retorna:
//		rolcod: es el codigo del nuevo rol
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function Insertar($datos,&$rolcod)
	{
		if(!$this->_DatosValidosAlta($datos,$datosvalidados))
			return false;
			
		if (!parent::Insertar($datosvalidados,$rolcod))
			return false;


		return true;
	}

//----------------------------------------------------------------------------------------- 
// Modificar un rol

// Parámetros de Entrada:
//		datos: un array asociativo con los datos nuevos
//		rolcod: es el codigo del rol a modificar

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function Modificar($datos,$rolcod)
	{
		if(!$this->_DatosValidosModificacion($rolcod,$datos,$datosvalidados))
			return false;

		if (!parent::Modificar($datosvalidados,$rolcod))
			return false;

		return true;
	}



//----------------------------------------------------------------------------------------- 
// Borra un rol

// Parámetros de Entrada:
//		rolcod: es el codigo del rol a borrar

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	public function Eliminar($rolcod)
	{	
		if(!$this->_DatosValidosBorrado($rolcod))
			return false;

		if (!parent::Eliminar($rolcod))
			return false;

		return true;
	} 

//-----------------------------------------------------------------------------------------
//							 PRIVADAS	
//----------------------------------------------------------------------------------------- 

//----------------------------------------------------------------------------------------- 
// Retorna si los datos enviados son válidos para dar de alta un nuevo rol

// Parámetros de Entrada:
//		datos: un array asociativo con los datos a cargar

// Retorna:
//		datosvalidados: datos habilitados para cargar en la base
//		la función retorna true si los datos están correctos, false en caso contrario

	private function _DatosValidosAlta($datos,&$datosvalidados)
	{
		$datosvalidados=array();

		$this->RolesSP($spnombre,$spparam);
		$arraybusq=array("rolcod"=>$datos['rolcodnuevo']);
		if(!$this->conexion->BuscarRegistroxClave($spnombre,$spparam,$arraybusq,$query,$filalib,$numfilasmatcheo,$errno) || $numfilasmatcheo!=0)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_INF,"Ya existe un rol con ese código. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
			return false;
		}
		$arraybusq=array("rolnom"=>$datos['rolnom']);
		if(!$this->conexion->BuscarRegistroxClave($spnombre,$spparam,$arraybusq,$query,$filalib,$numfilasmatcheo,$errno) || $numfilasmatcheo!=0)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_INF,"Ya existe un rol con ese nombre. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
			return false;
		}


		if ($datos['rolcodnuevo']=="" || !FuncionesPHPLocal::ValidarContenido($this->conexion,$datos['rolcodnuevo'],"NumericoEntero") )
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRSOSP,"Error en el código de rol. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
			return false;
		}
		$datosvalidados['rolcodnuevo']=$datos['rolcodnuevo'];
		
		if ($datos['rolnom']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRSOSP,"Error en el nombre del rol. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
			return false;
		}
		$datosvalidados['rolnom']=$datos['rolnom'];

		if ($datos['roldesc']=='')
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRSOSP,"Error en la descripción del rol. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
			return false;
		}
		$datosvalidados['roldesc']=$datos['roldesc'];
		
					
		return true;
	}
	
//----------------------------------------------------------------------------------------- 
// Retorna si los datos enviados son válidos para actualizar un rol

// Parámetros de Entrada:
//		rolcod: es el rol a actualizar
//		datos: un array asociativo con los datos a cargar

// Retorna:
//		datosvalidados: datos habilitados para actualizar el rol
//		la función retorna true si los datos están correctos, false en caso contrario
	
	private function _DatosValidosModificacion($rolcod,$datos,&$datosvalidados)
	{
		$datosvalidados=array();

		$this->RolesSP($spnombre,$spparam);
		$arraybusq=array("rolcod"=>$datos['rolcodnuevo']);
		if(!$this->conexion->BuscarRegistroxClave($spnombre,$spparam,$arraybusq,$query,$filalib,$numfilasmatcheo,$errno) || ($numfilasmatcheo!=0 && $filalib["rolcod"]!=$datos["rolcodviejo"]))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_INF,"Ya existe un rol con ese código. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
			return false;
		}
		$arraybusq=array("rolcod"=>$datos['rolcodviejo']);
		if(!$this->conexion->BuscarRegistroxClave($spnombre,$spparam,$arraybusq,$query,$filalib,$numfilasmatcheo,$errno) || $numfilasmatcheo!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_INF,"No existe el rol. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
			return false;
		}
		$arraybusq=array("rolnom"=>$datos['rolnom']);
		if(!$this->conexion->BuscarRegistroxClave($spnombre,$spparam,$arraybusq,$query,$filalib,$numfilasmatcheo,$errno) || ($numfilasmatcheo!=0 && $filalib["rolcod"]!=$datos["rolcodviejo"]))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_INF,"Ya existe un rol con ese nombre. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
			return false;
		}


		if ($datos['rolcodnuevo']=="" || !FuncionesPHPLocal::ValidarContenido($this->conexion,$datos['rolcodnuevo'],"NumericoEntero") )
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRSOSP,"Error en el código de rol. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
			return false;
		}
		$datosvalidados['rolcodviejo']=$datos['rolcodviejo'];
		$datosvalidados['rolcodnuevo']=$datos['rolcodnuevo'];
		
		if ($datos['rolnom']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRSOSP,"Error en el nombre del rol. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
			return false;
		}
		$datosvalidados['rolnom']=$datos['rolnom'];

		if ($datos['roldesc']=='')
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRSOSP,"Error en la descripción del rol. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
			return false;
		}
		$datosvalidados['roldesc']=$datos['roldesc'];
		
					
		return true;
	}

//----------------------------------------------------------------------------------------- 
// Retorna si el rol se puede borrar

// Parámetros de Entrada:
//		rolcod: es el rol a borrar

// Retorna:
//		la función retorna true si los datos están correctos, false en caso contrario

	private function _DatosValidosBorrado($rolcod)
	{	
		$this->RolesSP($spnombre,$spparam);
		$arraybusq=array("rolcod"=>$rolcod);
		if(!$this->conexion->BuscarRegistroxClave($spnombre,$spparam,$arraybusq,$query,$filalib,$numfilasmatcheo,$errno) || $numfilasmatcheo!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_INF,"No existe el rol. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
			return false;
		}

		return true;
	}

//----------------------------------------------------------------------------------------- 
// Retorna los roles que puede asignar un usuario a otro, eliminando los que ya tiene asignados

// Parámetros de Entrada:
//		rolcodactualiza:   es el rol del usuario logueado que quiere dar de alta el usuario/rol.  
//		usuarioactualizar: es el usuariocod del usuario al cual se le quiere asignar el rol.

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no.
	
	public function RolesPosiblesAsignar($rolcodactualiza,$usuarioaactualizar,&$numfilas,&$roles_sin_asignar)
	{
	
		$roles_sin_asignar=array();			

		if(!$this->RolesDeUnUsuario($usuarioaactualizar,$numfilas_rolusuario,$resultado))
			return false;
	
		$roles=array();
		if($numfilas_rolusuario>0) {
			while($fila=$this->conexion->ObtenerSiguienteRegistro($resultado)) 
				$roles_asignados[]=$fila["rolcod"];
		} else
			$roles_asignados[]=0; // rol falso para que no de error el "in" del sql cuando no tiene roles asignados
	
		// genero un string para el "in" con los roles que ya tiene asignado el usuario
		$in_roles_asignados="(".implode(",",$roles_asignados).")";
		
		$datos['rolcodactualiza'] = $rolcodactualiza;
		$datos['in_roles_asignados'] = $in_roles_asignados;
		
		if(!parent::TraerRolesSinAsignar($datos,$resultado_roles,$numfilas_resu))
			return false;

		$roles_sin_asignar=array();
		if($numfilas_resu>0) {
			while($fila2=$this->conexion->ObtenerSiguienteRegistroArray($resultado_roles))
			{
				$roles_sin_asignar[]=$fila2["rolcod"];
			}
		}		
		
		$numfilas=count($roles_sin_asignar);

		return true;
	}
	
	
	
	public function TraerRolesActualizar($datos,&$resultado,&$numfilas)
	{
		$oRolesAbmRoles=new cRolesAbmRoles($this->conexion);
		$ArregloDatos['rolcodactualiza'] = $datos['rolcod'];
		if (!$oRolesAbmRoles->Buscar ($ArregloDatos,$numfilas,$resultado))
			return false;

		return true;		
	}
	
	
	public function RolesDeUnUsuario($usuariocod,&$numfilas,&$resultado)
	{
		if (!parent::RolesDeUnUsuario($usuariocod,$numfilas,$resultado))
			return false;
		
		return true;
	}

	public function RolesDeUnUsuarioSP($usuariocod,&$spnombre,&$spparam)
	{
		if (!parent::RolesDeUnUsuarioSP($usuariocod,$spnombre,$spparam))
			return false;
		
		return true;
	}


}


?>