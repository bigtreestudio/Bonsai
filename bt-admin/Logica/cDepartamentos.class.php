<?php  
include(DIR_CLASES_DB."cDepartamentos.db.php");

class cDepartamentos extends cDepartamentosdb	
{
	protected $conexion;
	protected $formato;
	protected $provinciacod;
	
	
	// Constructor de la clase
	function __construct($conexion,$provinciacod,$formato=FMT_TEXTO){
		$this->conexion = &$conexion;
		$this->formato = $formato;
		$this->provinciacod = $provinciacod;
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
	public function DepartamentoSP(&$spnombre,&$sparam)
	{
		if (!parent::DepartamentoSP($spnombre,$sparam))
			return false;
		return true;	
	}

	public function Buscar (&$numfilas,&$resultado)
	{
		if (!parent::Buscar ($numfilas,$resultado))
			return false;
		return true;	
	}
	
	public function BusquedaAvanzada($datos,&$resultado,&$numfilas)
	{
		$sparam=array(
			'provinciacod'=>"",
			'orderby'=> "departamentodesc ASC",
			'limit'=> ""
		);	
	
		if (isset ($datos['provinciacod']) && $datos['provinciacod']!="")
			$sparam['provinciacod']= $datos['provinciacod'];
			
		if (isset ($datos['orderby']) && $datos['orderby']!="")
			$sparam['orderby']= $datos['orderby'];
			
		if (isset ($datos['limit']) && $datos['limit']!="")
			$sparam['limit']= $datos['limit'];
		if (!parent::BusquedaAvanzada($sparam,$resultado,$numfilas))
			return false;
		return true;	
	}
	
	public function BusquedaDepartamentoxProvincia($datos,&$resultado,&$numfilas)
	{
		if (!parent::BusquedaDepartamentoxProvincia($datos,$resultado,$numfilas))
			return false;
		return true;	
	}


	public function BuscarDepartamentoxCodigo ($datos,&$resultado,&$numfilas)
	{
		if (!parent::BuscarDepartamentoxCodigo ($datos,$resultado,$numfilas))
			return false;
		return true;	
	}



	public function StoreDepatamentoxEstado($datos,&$spnombre,&$sparam)
	{
		if (!parent::StoreDepatamentoxEstado($datos,$spnombre,$sparam))
			return false;
		return true;	
	}


	public function BuscarDepartamentosActivas (&$numfilas,&$resultado)
	{
		if (!parent::BuscarDepartamentosActivas ($numfilas,$resultado))
			return false;
		return true;	
	}


	public function Insertar ($datos,&$codigoarchivo)
	{
		if (!$this->_ValidarInsertarDepartamento($datos))
			return false;

		if (!parent::Insertar($datos,$codigoinsertado))
			return false;
			
		return true;
	}

	public function Modificar ($datos)
	{
		if (!$this->_ValidarModificarDepartamento($datos))
			return false;

		if (!parent::Modificar($datos))
			return false;
		
		return true;
	}

	public function Eliminar ($datos)
	{
		if (!$this->_ValidarEliminarDepartamento($datos))
			return false;
	
		if (!parent::Eliminar($datos))
			return false;
		
		return true;
	}


	public function ActivarDesactivarDepartamento ($datos)
	{
		if (!$this->_ValidarActivarDesactivarDepartamento($datos))
			return false;
	
		if (!parent::ActivarDesactivarDepartamento($datos))
			return false;
		
		return true;
	}



	public function Activar($datos)
	{
		$datos['departamentoestado'] = ACTIVO;
		if (!$this->ActivarDesactivarDepartamento($datos))
			return false;
	
		return true;	
	} 

	public function DesActivar($datos)
	{
		$datos['departamentoestado'] = NOACTIVO;
		if (!$this->ActivarDesactivarDepartamento($datos))
			return false;
	
		return true;	
	} 



	private function _ValidarDatosVaciosDepartamento($datos)
	{
		if (!isset ($datos['departamentodesc']) || ($datos['departamentodesc']==""))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar una descripción de la provincia.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		$oProvincia = new cProvincias($this->conexion,$this->formato);
		if (!$oProvincia->BuscarxCodigo($datos,$resultado,$numfilas))
			return false;

		if ($numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, provincia inexistente.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		return true;
	}


	private function _ValidarInsertarDepartamento($datos)
	{
		if (!$this->_ValidarDatosVaciosDepartamento($datos))
			return false;
		
		return true;
	}

	private function _ValidarModificarDepartamento($datos)
	{
		
		if (!$this->_ValidarDatosVaciosDepartamento($datos))
			return false;
	
		if (!$this->BuscarDepartamentoxCodigo($datos,$resultado,$numfilas))
			return false;
	
		if ($numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, ciudad inexistente.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		return true;
	}


	private function _ValidarEliminarDepartamento($datos)
	{
		
		if (!$this->BuscarDepartamentoxCodigo($datos,$resultado,$numfilas))
			return false;
	
		if ($numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, ciudad inexistente.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		// buscar ciudades			
		return true;
	}

	private function _ValidarActivarDesactivarDepartamento($datos)
	{
		
		if (!$this->BuscarDepartamentoxCodigo($datos,$resultado,$numfilas))
			return false;
	
		if ($numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, ciudad inexistente.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
			
		return true;
	}
	

}
?>