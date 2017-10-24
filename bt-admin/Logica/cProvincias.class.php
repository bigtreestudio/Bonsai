<? 
include(DIR_CLASES_DB."cProvincias.db.php");

class cProvincias extends cProvinciasdb	
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
	public function ProvinciasSP(&$spnombre,&$sparam)
	{
		if (!parent::ProvinciasSP($spnombre,$sparam))
			return false;
		return true;	
	}

	public function Buscar (&$resultado,&$numfilas)
	{
		if (!parent::Buscar ($resultado,$numfilas))
			return false;
		return true;	
	}


	public function BuscarxCodigo ($datos,&$resultado,&$numfilas)
	{
		if (!parent::BuscarProvinciaxCodigo ($datos,$resultado,$numfilas))
			return false;
		return true;	
	}

	public function BusquedaAvanzada($datos,&$resultado,&$numfilas)
	{
		$sparam=array(
			'orderby'=> "provincia ASC",
			'limit'=> ""
		);	
	
		
		if (isset ($datos['orderby']) && $datos['orderby']!="")
			$sparam['orderby']= $datos['orderby'];
			
		if (isset ($datos['limit']) && $datos['limit']!="")
			$sparam['limit']= $datos['limit'];
		if (!parent::BusquedaAvanzada($sparam,$resultado,$numfilas))
			return false;
		return true;	
	}

	public function StoreProvinciaxEstado($datos,&$spnombre,&$sparam)
	{
		if (!parent::StoreProvinciaxEstado($datos,$spnombre,$sparam))
			return false;
		return true;	
	}


	public function BuscarProvinciasActivas (&$numfilas,&$resultado)
	{
		if (!parent::BuscarProvinciasActivas ($numfilas,$resultado))
			return false;
		return true;	
	}


	public function Insertar ($datos,&$codigoarchivo)
	{
		if (!$this->_ValidarInsertarProvincia($datos))
			return false;

		if (!parent::Insertar($datos,$codigoinsertado))
			return false;
		
		if (!$this->PublicarTodos())
			return false;
			
		return true;
	}

	public function Modificar ($datos)
	{
		if (!$this->_ValidarModificarProvincia($datos))
			return false;

		if (!parent::Modificar($datos))
			return false;
		
		if (!$this->PublicarTodos())
			return false;
			
		return true;
	}

	public function Eliminar ($datos)
	{
		if (!$this->_ValidarEliminarProvincia($datos))
			return false;
	
		if (!parent::Eliminar($datos))
			return false;
		
		if (!$this->PublicarTodos())
			return false;
			
		return true;
	}


	public function ActivarDesactivarProvincia ($datos)
	{
		if (!$this->_ValidarActivarDesactivarProvincia($datos))
			return false;
	
		if (!parent::ActivarDesactivarProvincia($datos))
			return false;
		
		if (!$this->PublicarTodos())
			return false;
		
		return true;
	}



	public function Activar($datos)
	{
		$datos['provinciaestado'] = ACTIVO;
		if (!$this->ActivarDesactivarProvincia($datos))
			return false;
		
		return true;	
	} 

	public function DesActivar($datos)
	{
		$datos['provinciaestado'] = NOACTIVO;
		if (!$this->ActivarDesactivarProvincia($datos))
			return false;
	
		return true;	
	}
	
	public function PuedoEliminarProvincia ($datos)
	{
		
		$oDepartamentos = new cDepartamentos($this->conexion,$datos['provinciacod']);
		if(!$oDepartamentos->Buscar($resultado,$numfilas))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al Departamentos. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		//echo $numfilas;
		if($numfilas > 0)
			return false;
			
		return true;		
	
	} 



	private function _ValidarDatosVaciosProvincia($datos)
	{
		if (!isset ($datos['provinciadesc']) || ($datos['provinciadesc']==""))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar una descripción de la provincia.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		return true;
	}


	private function _ValidarInsertarProvincia ($datos)
	{
		if (!$this->_ValidarDatosVaciosProvincia($datos))
			return false;
		
		return true;
	}

	private function _ValidarModificarProvincia($datos)
	{
		
		if (!$this->_ValidarDatosVaciosProvincia($datos))
			return false;
	
		if (!$this->BuscarxCodigo($datos,$resultado,$numfilas))
			return false;
	
		if ($numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, provincia inexistente.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		return true;
	}


	private function _ValidarEliminarProvincia($datos)
	{
		
		if (!$this->BuscarxCodigo($datos,$resultado,$numfilas))
			return false;
	
		if ($numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, provincia inexistente.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		
		$oDepartamentos = new cDepartamentos($this->conexion,$datos['provinciacod'],$this->formato);
		if (!$oDepartamentos->Buscar($resultadoprov,$numfilasprov))
			return false;
		if ($numfilasprov>0)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_INF,"Error, la provincia contiene ciudades asociadas, debe eliminarlas.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	}

	private function _ValidarActivarDesactivarProvincia($datos)
	{
		
		if (!$this->BuscarxCodigo($datos,$resultado,$numfilas))
			return false;
	
		if ($numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, provincia inexistente.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
			
		return true;
	}
	
	public function PublicarTodos()
	{
		$array = array();
		if(!$this->BuscarProvinciasActivas($resultado,$numfilas))
			return false;
			
		if($numfilas>0)
		{
			while($fila= $this->conexion->ObtenerSiguienteRegistro($resultado))
			{
				$array[$fila['provinciacod']]= FuncionesPHPLocal::DecodificarUtf8($fila);
			}
			$json = json_encode($array);
			file_put_contents(PUBLICA."json/provincias.json" , $json);
			
		}
		return true;
	}

}
?>