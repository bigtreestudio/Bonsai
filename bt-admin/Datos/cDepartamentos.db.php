<?php  
abstract class cDepartamentosdb
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
// Retorna el SP y los parametros para cargar los roles del sistema

// Parámetros de Entrada:

// Retorna:
//		spnombre,spparam
//		la función retorna true o false si se pudo ejecutar con éxito o no

	protected function DepartamentoSP(&$spnombre,&$sparam)
	{
		$spnombre="sel_departamentos_xprovinciacod";
		$sparam=array(
			'pprovinciacod'=> $this->provinciacod,
			'porderby'=> "departamentodesc"
			);
		return true;
	}


	protected function Buscar(&$resultado,&$numfilas)
	{
		$this->DepartamentoSP($spnombre,$sparam);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar los departamentos por provincia.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
	
		return true;
	
	}
	
	
	protected function BusquedaAvanzada($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_departamentos_busqueda";
		$sparam=array(
			'pprovinciacod'=> $datos['provinciacod'],
			'porderby'=> $datos['orderby'],
			'plimit'=> $datos['limit']
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar los departamentos.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
	
		return true;
	
	}

	protected function StoreDepatamentoxEstado($datos,&$spnombre,&$sparam)
	{
		$spnombre="sel_departamentos_xdepartamentocod";
		$sparam=array(
			'pprovinciacod'=> $this->provinciacod,
			'pdepartamentoestado'=> $datos['departamentoestado'],
			'porderby'=> "departamentodesc"
			);	
		return true;
	}


	protected function BuscarDepartamentosActivas(&$resultado,&$numfilas)
	{
		$datos['departamentoestado'] = ACTIVO;
		$this->StoreDepatamentoxEstado($datos,$spnombre,$sparam);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar los departamentos por provincia activos.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
	
		return true;
	
	}
	
	protected function BuscarDepartamentoxCodigo($datos,&$resultado,&$numfilas)
	{
		
		$spnombre="sel_departamentos_xprovinciacod_departamentocod";
		$sparam=array(
			'pdepartamentocod'=> $datos['departamentocod'],
			'pprovinciacod'=> $this->provinciacod
			);
	
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar el departamento por código.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
	
		return true;
	}
	
	
	
	
	protected function Insertar($datos,&$codigoinsertado)
	{

		$spnombre="ins_departamentos";
		$sparam=array(
			'pprovinciacod'=> $this->provinciacod,
			'pdepartamentodesc'=> $datos['departamentodesc'],
			'pdepartamentoestado'=> ACTIVO,
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s")
			);
			
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno) && $numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al insertar la ciudad.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		$codigoinsertado=$this->conexion->UltimoCodigoInsertado();
		return true;
	}
	
	
	
	protected function Modificar($datos)
	{

	
		$spnombre="upd_departamentos_xdepartamentocod";
		$sparam=array(
			'pdepartamentodesc'=> $datos['departamentodesc'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			'pdepartamentocod'=> $datos['departamentocod'],
			'pprovinciacod'=> $this->provinciacod
			);			
			
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al modificar la ciudad.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		return true;
	}




	protected function ActivarDesactivarDepartamento($datos)
	{

		$spnombre="upd_departamentos_activar_desactivar_xdepartamentocod";
		$sparam=array(
			'pdepartamentoestado'=> $datos['departamentoestado'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			'pdepartamentocod'=> $datos['departamentocod'],
			'pprovinciacod'=> $this->provinciacod
			);
				
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al activar / desactivar la ciudad.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		return true;
	}
	
	
	
	protected function Eliminar($datos)
	{

		$spnombre="del_departamentos_xdepartamentocod_provinciacod";
		$sparam=array(
			'pdepartamentocod'=> $datos['departamentocod'],
			'pprovinciacod'=> $this->provinciacod
			);		

		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno) && $numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al eliminar la ciudad.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	}

	

}


?>