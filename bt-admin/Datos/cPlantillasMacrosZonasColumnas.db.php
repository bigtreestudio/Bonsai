<?php  
abstract class cPlantillasMacrosZonasColumnasdb
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

		$spnombre="sel_tap_plantillas_macros_zonas_columnas_xplantmacrocolumnacod";
		$sparam=array(
			'pplantmacrocolumnacod'=> $datos['plantmacrocolumnacod']
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar la zona de la columna por codigo. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
			return false;
		}
		
		return true;
	}



	
	protected function BuscarZonasColumnasxMacrozonacod($datos,&$resultado,&$numfilas)
	{
		
		$spnombre="sel_tap_plantillas_macros_zonas_columnas_xmacrozonacod";
		$sparam=array(
			'pmacrozonacod'=> $datos['macrozonacod']
			);
	
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar las zonas por codigo de la zona del macro. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
			return false;
		}
		
		return true;
	}



	protected function Insertar($datos,&$codigoinsertado)
	{
		
		$spnombre="ins_tap_plantillas_macros_zonas_columnas";
		$sparam=array(
			'pmacrozonacod'=> $datos['macrozonacod'],
			'pcolumnacod'=> $datos['columnacod'],
			'pplantmacroorden'=> $datos['plantmacroorden'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s")
			);	
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al insertar la zona de la columna dentro del macro. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
			return false;
		}
		$codigoinsertado=$this->conexion->UltimoCodigoInsertado();

		return true;
	}


	protected function Eliminar($datos)
	{
		$spnombre="del_tap_plantillas_macros_zonas_columnas_xplantmacrocolumnacod";
		$sparam=array(
			'pplantmacrocolumnacod'=> $datos['plantmacrocolumnacod']
			);
	
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al eliminar la zona del macro. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
			return false;
		}

		return true;
	}


	protected function BuscarUltimoOrdenxMacrozonaCod($datos,&$resultado,&$numfilas)
	{

		$spnombre="sel_tap_plantillas_macros_zonas_maxorden";
		$sparam=array(
			'pmacrozonacod'=> $datos['macrozonacod']
			);

		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar el proximo orden.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;	
	}

	protected function ModificarOrden($datos)
	{
		$spnombre="upd_tap_plantillas_macros_zonas_columnas_orden_xplantmacrocolumnacod";
		$sparam=array(
			'pplantmacroorden'=> $datos['plantmacroorden'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			'pplantmacrocolumnacod'=> $datos['plantmacrocolumnacod']
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno) )
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al modificar el orden de las plantillas - macro - columnas. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;
		}
		
		return true;	
	}


}
?>