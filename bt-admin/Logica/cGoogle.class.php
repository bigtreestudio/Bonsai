<?php  
include(DIR_CLASES_DB."cGoogle.db.php");

class cGoogle extends cGoogledb	
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

	
	public function Buscar ($datos,&$resultado,&$numfilas)
	{	
		if (!parent::Buscar ($datos,$resultado,$numfilas))
			return false;
		return true;	
	}

			
	public function ModificarDatos ($datos)
	{	
		
		if (!$this->_ValidarModificar($datos))
			return false;
		
		$datos['googlepass'] = FuncionesPHPLocal::EncriptarFrase($datos['googlepass'],CLAVEENCRIPTACION);
		if (!parent::ModificarDatos($datos))
			return false;
			
		return true;
	}	
	

	public function ModificarProfiles ($datos)
	{
	
		if (!$this->_ValidarModificarProfile($datos))
			return false;
		if (!parent::ModificarProfiles($datos))
			return false;
			
		return true;
	}	
	
	
	private function _ValidarModificarProfile ($datos)
	{
		if (!isset ($datos['googleprofile']) || ($datos['googleprofile']==""))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, profile erroneo.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		return true;
	}	
	
	
	
	
	private function _ValidarModificar ($datos)
	{
		if (!$this->_ValidarDatosVacios($datos))
			return false;
		
		return true;
	}	
	

	
	private function _ValidarDatosVacios($datos)
	{
		
		//validar datos vacios del modicar datos
		if (!isset ($datos['googlecod']) || ($datos['googlecod']==""))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, codigo erroneo.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		if (!isset ($datos['googletitulo']) || ($datos['googletitulo']==""))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, para poder modificar debe ingresar el titulo.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		if (!isset ($datos['googleuser']) || ($datos['googleuser']==""))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, para poder modificar debe ingresar el nombre usuario.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		if (!isset ($datos['googlepass']) || ($datos['googlepass']==""))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar el password.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		if (!isset ($datos['googlepassconfirm']) || ($datos['googlepassconfirm']==""))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar la confirmacion del password.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		if ($datos['googlepass']!=$datos['googlepassconfirm'])
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, el password debe ser identico a la confirmacion.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		return true;	
		
		//	
	}
	
	
}

?>