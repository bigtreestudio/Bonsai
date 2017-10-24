<?php  
abstract class cPlantillasMacrosZonasdb
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

	
	protected function BuscarxCodigo($datos,&$resultado,&$numfilas)
	{
		
		$spnombre="sel_tap_plantillas_macros_zonas_xmacrozonacod";
		$sparam=array(
			'pmacrozonacod'=> $datos['macrozonacod']
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar las zonas por codigo. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
			return false;
		}
		
		return true;
	}



	
	protected function BuscarZonasxPlantMacrocod($datos,&$resultado,&$numfilas)
	{
		
		$spnombre="sel_tap_plantillas_macros_zonas_xplantmacrocod";
		$sparam=array(
			'pplantmacrocod'=> $datos['plantmacrocod']
			);

		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar las zonas por codigo de plantilla. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
			return false;
		}
		
		return true;
	}



	protected function Insertar($datos,&$codigoinsertado)
	{
		
		$spnombre="ins_tap_plantillas_macros_zonas";
		$sparam=array(
			'pplantmacrocod'=> $datos['plantmacrocod'],
			'pplantcod'=> $datos['plantcod'],
			'pmacrocod'=> $datos['macrocod'],
			'pestructuracod'=> $datos['estructuracod'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s")
			);
	
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al insertar la zona del macro. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
			return false;
		}
		$codigoinsertado=$this->conexion->UltimoCodigoInsertado();

		return true;
	}


	protected function Eliminar($datos)
	{
		
		$spnombre="del_tap_plantillas_macros_zonas_xplantmacrocod";
		$sparam=array(
			'pplantmacrocod'=> $datos['plantmacrocod']
			);	
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al eliminar la zona del macro. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
			return false;
		}

		return true;
	}



}
?>