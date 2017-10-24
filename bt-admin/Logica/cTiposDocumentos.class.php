<?php  
include(DIR_CLASES_DB."cTiposDocumentos.db.php");

class cTiposDocumentos extends cTiposDocumentosdb	
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

	public function StoreTiposDocumento(&$spnombre,&$sparam)
	{
		if (!parent::StoreTiposDocumento($spnombre,$sparam))
			return false;
		return true;	
	}


	public function BuscarTiposDocumentoxCodigo ($datos,&$resultado,&$numfilas)
	{
		if (!parent::BuscarTiposDocumentoxCodigo ($datos,$resultado,$numfilas))
			return false;
		return true;	
	}



	public function StoreTiposDocumentoActivas(&$spnombre,&$sparam)
	{
		$datos['tipodocumentoestadocod'] = ACTIVO;
		if (!parent::StoreTiposDocumentoxEstado($datos,$spnombre,$sparam))
			return false;
		return true;	
	}


	public function BuscarTiposDocumentoActivas (&$numfilas,&$resultado)
	{
		if (!parent::BuscarTiposDocumentoActivas ($numfilas,$resultado))
			return false;
		return true;	
	}

	public function Buscar (&$resultado,&$numfilas)
	{
		
		if (!parent::Buscar ($resultado,$numfilas))
			return false;
		return true;	
	}
	
	public function BusquedaAvanzada($datos,&$resultado,&$numfilas)
	{
		$sparam=array(
			'orderby'=> "tipodocumentonombre ASC",
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
	
	public function Insertar ($datos,&$codigoarchivo)
	{
		if (!$this->_ValidarInsertar($datos))
			return false;

		if (!parent::Insertar($datos,$codigoinsertado))
			return false;
			
		return true;
	}
	
	private function _ValidarDatosVacios($datos)
	{
		if (!isset ($datos['tipodocumentonombre']) || ($datos['tipodocumentonombre']==""))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar un nombre de documento.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
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

	public function Modificar ($datos)
	{
		if (!$this->_ValidarModificar($datos))
			return false;

		if (!parent::Modificar($datos))
			return false;
		
		return true;
	}
	
	private function _ValidarModificar($datos)
	{
		
		if (!$this->_ValidarDatosVacios($datos))
			return false;
	
		if (!$this->BuscarTiposDocumentoxCodigo($datos,$resultado,$numfilas))
			return false;
	
		if ($numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, Tipo de documento inexistente.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

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
	
	
	
	private function _ValidarEliminar($datos)
	{
		
		if (!$this->BuscarTiposDocumentoxCodigo($datos,$resultado,$numfilas))
			return false;
	
		if ($numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, tipo de documento inexistente.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		$oUsuarios = new cUsuarios($this->conexion);
		if (!$oUsuarios->BuscarUsuarioxTipoDocumento ($datos,$resultadousuarios,$numfilasusuarios))
			return false;
		if($numfilasusuarios > 0)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, el tipo de documento tiene usuarios asignados.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		return true;
	}
	
	public function PuedeEliminarDoc($datos,$muestromsg)
	{
		
		$oUsuarios = new cUsuarios($this->conexion);
		if (!$oUsuarios->BuscarUsuarioxTipoDocumento ($datos,$resultadousuarios,$numfilasusuarios))
			return false;
		//echo $numfilasusuarios;
		if($numfilasusuarios > 0)
		{
			if($muestromsg)
				FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, el tipo de documento tiene usuarios asignados.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
				return false;
				
		}
		return true;
	}


	public function ActivarDesactivar ($datos)
	{
		if (!$this->_ValidarActivarDesactivar($datos))
			return false;

		if (!parent::ActivarDesactivar($datos))
			return false;
		
		return true;
	}
	
	
	public function Activar($datos)
	{
		$datos['tipodocumentoestadocod'] = ACTIVO;
		if (!$this->ActivarDesactivar($datos))
			return false;
	
		return true;	
	} 

	public function DesActivar($datos)
	{

		$datos['tipodocumentoestadocod'] = NOACTIVO;

		if (!$this->ActivarDesactivar($datos))
			return false;
	
		return true;	
	} 
	
	private function _ValidarActivarDesactivar($datos)
	{
		
		if (!$this->BuscarTiposDocumentoxCodigo($datos,$resultado,$numfilas))
			return false;
	
		if ($numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, tipo de documento inexistente.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
			
		return true;
	}

}
?>