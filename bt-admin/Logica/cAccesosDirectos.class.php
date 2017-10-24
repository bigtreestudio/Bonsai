<?php  
include(DIR_CLASES_DB."cAccesosDirectos.db.php");

class cAccesosDirectos extends cAccesosDirectosdb	
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
	public function BuscarAccesosDirectosxTipoacceso ($datos,&$resultado,&$numfilas)
	{
		if (!parent::BuscarAccesosDirectosxTipoacceso ($datos,$resultado,$numfilas))
			return false;

		return true;	
	}
	
	public function BuscarAccesosDirectos ($datos,&$resultado,&$numfilas)
	{
		if (!parent::BuscarAccesosDirectos ($datos,$resultado,$numfilas))
			return false;

		return true;	
	}	

/*

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
			
		return true;
	}

	public function Modificar ($datos)
	{
		if (!$this->_ValidarModificar($datos))
			return false;

		if (!parent::Modificar($datos))
			return false;
		
		return true;
	}

	public function Eliminar ($datos)
	{
		if (!$this->_ValidarEliminar($datos))
			return false;
	
		if (!parent::Eliminar($datos))
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
		
		return true;
	}
*/

}
?>