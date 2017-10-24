<?php  
abstract class cTiposDocumentosdb
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


	protected function StoreTiposDocumento(&$spnombre,&$sparam)
	{
		$spnombre="sel_tipos_documento";
		$sparam=array(
			'porderby'=> "tipodocumentocod"
			);
			
		return true;	
	}



	protected function StoreTiposDocumentoxEstado($datos,&$spnombre,&$sparam)
	{
		$spnombre="sel_tipos_documento_xtipodocumentoestadocod";
		$sparam=array(
			'ptipodocumentoestadocod'=> $datos['tipodocumentoestadocod'],
			'porderby'=> "tipodocumentocod"
			);

		return true;	
	}


	protected function BuscarTiposDocumentoActivas(&$resultado,&$numfilas)
	{
		$datos['tipodocumentoestadocod'] = ACTIVO;
		$this->StoreTiposDocumentoxEstado($datos,$spnombre,$sparam);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar los tipos de documentos activos.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
	
		return true;
	
	}
	
	
	protected function BuscarTiposDocumentoxCodigo($datos,&$resultado,&$numfilas)
	{
		
		$spnombre="sel_tipos_documento_xtipodocumentocod";
		$sparam=array(
			'ptipodocumentocod'=> $datos['tipodocumentocod']
			);	
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar la el tipo de documento por código.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
	
		return true;
	}
	


	protected function Buscar(&$resultado,&$numfilas)
	{
		$this->StoreTiposDocumento($spnombre,$sparam);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar los tipos de documentos.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
	
		return true;
	
	}
	
	protected function BusquedaAvanzada($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_tipos_documento_busqueda";
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

		$spnombre="ins_tipos_documento";
		$sparam=array(
			'ptipodocumentonombre'=> $datos['tipodocumentonombre'],
			'ptipodocumentoestadocod'=> NOACTIVO,
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s")
			);
	
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al insertar el Tipo de documento.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		
		$codigoinsertado=$this->conexion->UltimoCodigoInsertado();
		return true;
	}


	protected function Modificar($datos)
	{

	
		$spnombre="upd_tipos_documento_xtipodocumentocod";
		$sparam=array(
			'ptipodocumentonombre'=> $datos['tipodocumentonombre'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			'ptipodocumentocod'=> $datos['tipodocumentocod']
			);
			
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al modificar el tipo de documento.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	}

	protected function Eliminar($datos)
	{

		$spnombre="del_tipos_documento_xtipodocumentocod";
		$sparam=array(
			'ptipodocumentocod'=> $datos['tipodocumentocod']
			);
	
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno) && $numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al eliminar el tipo de documento.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	}

	protected function ActivarDesactivar($datos)
	{
		
		$spnombre="upd_tipos_documento_activar_desactivar_xtipodocumentocod";
		$sparam=array(
			'ptipodocumentoestadocod'=> $datos['tipodocumentoestadocod'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			'ptipodocumentocod'=> $datos['tipodocumentocod']
		);	
		
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al activar / desactivar el tipo de documento.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		return true;
	}
	

}


?>