<?php  
include(DIR_CLASES_DB."cFormulariosEnvios.db.php");

class cFormulariosEnvios extends cFormulariosEnviosdb	
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

// Trae los formularios

// Parámetros de Entrada:
//	Sin parametros de entrada

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	public function BuscarxCodigo($datos,&$resultado,&$numfilas)
	{
		if (!parent::BuscarxCodigo ($datos,$resultado,$numfilas))
			return false;
		return true;			
	}

	
	public function BusquedaAvanzada($datos,&$resultado,&$numfilas)
	{
		
		
		$sparam=array(
			'formulariocod'=> $datos['formulariocod'],
			'orderby'=> "formulariodatoscod desc",
			'limit'=> ""
		);	
		

		if (isset ($datos['orderby']) && $datos['orderby']!="")
			$sparam['orderby']= $datos['orderby'];
			
		if (isset ($datos['limit']) && $datos['limit']!="")
			$sparam['limit']= $datos['limit'];
	
		
		if (!parent::BusquedaAvanzada ($sparam,$resultado,$numfilas))
			return false;
		return true;			
	}

// Parámetros de Entrada:
//		datos: arreglo de datos
//			tapacod = codigo del formulario
// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no	
	
	public function Eliminar($datos)
	{
		if (!$this->_ValidarEliminar($datos))
			return false;;
		
		if (!parent::Eliminar($datos))
			return false;
		
		return true;
	}


	public function Insertar($datos,&$enviocod)
	{	

		if (!$this->_ValidarInsertar($datos))
			return false;
			
		if(!parent::Insertar($datos,$enviocod))
			return false;
		
		return true;
	} 
	
	
	
	private function _ValidarInsertar($datos)
	{

		if (!$this->_ValidarDatosVacios($datos))
			return false;

		return true;
	}


	private function _ValidarDatosVacios($datos)
	{
		

		if ($datos['enviomail']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar un Email. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;
		}	
		if(!FuncionesPHPLocal::ValidarContenido($this->conexion,$datos['enviomail'],"Email"))
		{	
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar un Email válido.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		if ($datos['enviotipo']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar un tipo de envio. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;
		}
		else
		{
			$oFormulariosEnviosTipos=new cFormulariosEnviosTipos($this->conexion,$this->formato);
			if(!$oFormulariosEnviosTipos->BuscarxCodigo($datos,$resultado,$numfilas))
				return false;
			if($numfilas!=1)
			{
				FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, tipo de envio inexistente. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
				return false;
			}	
		}	
			
		return true;
	}

	private function _ValidarEliminar($datos)
	{

		if (!$this->BuscarxCodigo($datos,$resultado,$numfilas))
			return false;
	
		if ($numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, Email inexistente.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;
		}
	
		return true;
	}
	
}//FIN CLASS
?>