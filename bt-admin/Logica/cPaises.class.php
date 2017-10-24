<? 
include(DIR_CLASES_DB."cPaises.db.php");

class cpaises extends cpaisesdb	
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
	public function paisesSP(&$spnombre,&$sparam)
	{
		if (!parent::paisesSP($spnombre,$sparam))
			return false;
		return true;	
	}

	public function BusquedaAvanzada($datos,&$resultado,&$numfilas)
	{
		$sparam=array(
			'orderby'=> "paisdesc ASC",
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


	public function BuscarxCodigo ($datos,&$resultado,&$numfilas)
	{
		if (!parent::BuscarpaisxCodigo ($datos,$resultado,$numfilas))
			return false;

		return true;	
	}



	public function StorepaisesActivos(&$spnombre,&$sparam)
	{
		$datos['paisestado'] = ACTIVO;
		if (!parent::StorepaísesxEstado($datos,$spnombre,$sparam))
			return false;
		return true;	
	}


	public function BuscarpaisesActivos (&$numfilas,&$resultado)
	{
		if (!parent::BuscarpaisesActivos ($numfilas,$resultado))
			return false;
		return true;	
	}


	public function Insertar ($datos,&$codigoarchivo)
	{
		if (!$this->_ValidarInsertar($datos))
			return false;

		if (!parent::Insertar($datos,$codigoinsertado))
			return false;
			
		if (!$this->PublicarTodos())
			return false;	
			
		return true;
	}

	public function Modificar ($datos)
	{
		if (!$this->_ValidarModificar($datos))
			return false;

		if (!parent::Modificar($datos))
			return false;
		
		if (!$this->PublicarTodos())
			return false;
		
		return true;
	}

	public function Eliminar ($datos)
	{
		if (!$this->_ValidarEliminar($datos))
			return false;
	
		if (!parent::Eliminar($datos))
			return false;
			
		if (!$this->PublicarTodos())
			return false;	
		
		return true;
	}


	public function ActivarDesactivar ($datos)
	{
		if (!$this->_ValidarActivarDesactivar($datos))
			return false;
	
		if (!parent::ActivarDesactivar($datos))
			return false;
		
		if (!$this->PublicarTodos())
			return false;
		return true;
	}



	public function Activar($datos)
	{
		$datos['paisestado'] = ACTIVO;
		if (!$this->ActivarDesactivar($datos))
			return false;
	
		return true;	
	} 

	public function DesActivar($datos)
	{
		$datos['paisestado'] = NOACTIVO;
		if (!$this->ActivarDesactivar($datos))
			return false;
	
		return true;	
	} 



	private function _ValidarDatosVacios($datos)
	{
		if (!isset ($datos['paisdesc']) || ($datos['paisdesc']==""))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar una descripción del país.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		return true;
	}


	private function _ValidarInsertar ($datos)
	{
		if (!$this->_ValidarDatosVacios($datos))
			return false;
		
		return true;
	}

	private function _ValidarModificar($datos)
	{
		
		if (!$this->_ValidarDatosVacios($datos))
			return false;
	
		if (!$this->BuscarxCodigo($datos,$resultado,$numfilas))
			return false;
	
		if ($numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, país inexistente.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		return true;
	}


	private function _ValidarEliminar($datos)
	{
		
		if (!$this->BuscarxCodigo($datos,$resultado,$numfilas))
			return false;
	
		if ($numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, país inexistente.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		/*
		$oDepartamentos = new cDepartamentos($this->conexion,$this->paíscod,$datos['provinciacod'],$this->formato);
		if (!$oDepartamentos->BuscarDepartamentos($resultadoprov,$numfilasprov))
			return false;
		if ($numfilasprov>0)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_INF,"Error, la provincia contiene ciudades asociadas, debe eliminarlas.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		*/
		return true;
	}

	private function _ValidarActivarDesactivar($datos)
	{
		
		if (!$this->BuscarxCodigo($datos,$resultado,$numfilas))
			return false;
	
		if ($numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, país inexistente.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
			
		return true;
	}
	
	public function PublicarTodos()
	{
		$array = array();
		if(!$this->BuscarpaisesActivos($resultado,$numfilas))
			return false;
			
		if($numfilas>0)
		{
			while($fila= $this->conexion->ObtenerSiguienteRegistro($resultado))
			{
				$array[$fila['paiscod']]= FuncionesPHPLocal::DecodificarUtf8($fila);
			}
			$json = json_encode($array);
			file_put_contents(PUBLICA."json/paises.json" , $json);
			
		}
		return true;
	}
	

}
?>