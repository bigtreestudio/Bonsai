<? 
abstract class cProvinciasdb
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

	protected function ProvinciasSP(&$spnombre,&$sparam)
	{
		$spnombre="sel_provincias";
		$sparam=array(
			'porderby'=> "provinciadesc"
			);		
		return true;
	}


	protected function Buscar(&$resultado,&$numfilas)
	{
		$this->ProvinciasSP($spnombre,$sparam);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar las provincias por pais.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
	
		return true;
	
	}


	protected function StoreProvinciaxEstado($datos,&$spnombre,&$sparam)
	{
		$spnombre="sel_provincias_xprovinciaestado";
		$sparam=array(
			'pprovinciaestado'=> $datos['provinciaestado'],
			'porderby'=> "provinciadesc"
			);		
		return true;	
	}


	protected function BuscarProvinciasActivas(&$resultado,&$numfilas)
	{
		$datos['provinciaestado'] = ACTIVO;
		$this->StoreProvinciaxEstado($datos,$spnombre,$sparam);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar las provincias activas.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
	
		return true;
	
	}
	
	
	protected function BuscarProvinciaxCodigo($datos,&$resultado,&$numfilas)
	{
		
		$spnombre="sel_provincias_xprovinciacod";
		$sparam=array(
			'pprovinciacod'=> $datos['provinciacod']
			);
	
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar la provincia por código.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
	
		return true;
	}
	
	protected function BusquedaAvanzada($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_provincias_busqueda";
		$sparam=array(
			'porderby'=> $datos['orderby'],
			'plimit'=> $datos['limit']
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar las provincias.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
	
		return true;
	
	}

	
	
	protected function Insertar($datos,&$codigoinsertado)
	{

		$spnombre="ins_provincias";
		$sparam=array(
			'pprovinciadesc'=> $datos['provinciadesc'],
			'pprovinciaestado'=> NOACTIVO,
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s")
			);	

		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno) && $numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al insertar la provincia.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		$codigoinsertado=$this->conexion->UltimoCodigoInsertado();
		return true;
	}
	
	
	
	protected function Modificar($datos)
	{

	
		$spnombre="upd_provincias_xprovinciacod";
		$sparam=array(
			'pprovinciadesc'=> $datos['provinciadesc'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			'pprovinciacod'=> $datos['provinciacod']
			);		
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al modificar la provincia.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	}




	protected function ActivarDesactivarProvincia($datos)
	{
		
		$spnombre="upd_provincias_activar_desactivar_xprovinciacod";
		$sparam=array(
			'pprovinciaestado'=> $datos['provinciaestado'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			'pprovinciacod'=> $datos['provinciacod']
			);		
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al activar / desactivar la provincia.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		return true;
	}
	
	
	
	protected function Eliminar($datos)
	{

		$spnombre="del_provincias_xprovinciacod";
		$sparam=array(
			'pprovinciacod'=> $datos['provinciacod']
			);
		
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno) && $numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al eliminar la provincia.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	}

	

}


?>