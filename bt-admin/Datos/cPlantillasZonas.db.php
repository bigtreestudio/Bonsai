<?php  
abstract class cPlantillasZonasdb
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



	protected function BuscarModulosxplantmacrocolumnacod($datos,&$resultado,&$numfilas)
	{

		$spnombre="sel_tap_plantillas_zonas_zonas_modulos_macros_zonas_columnas_xplantmacrocolumnacod";
		$sparam=array(
			'pplantmacrocolumnacod'=> $datos['plantmacrocolumnacod']
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar los modulos de las columnas. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
			return false;
		}
		
		return true;
	}
	
	
	protected function BuscarZonasxPlantMacroColumnacod($datos,&$resultado,&$numfilas)
	{

		$spnombre="sel_tap_plantillas_zonas_xplantmacrocolumnacod";
		$sparam=array(
			'pplantmacrocolumnacod'=> $datos['plantmacrocolumnacod']
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar las zonas. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
			return false;
		}
		
		return true;
	}

	protected function BuscarxCodigo($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_tap_plantillas_zonas_xzonacod";
		$sparam=array(
			'pzonacod'=> $datos['zonacod']
			);
		
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al obtener la plantilla - macro. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		return true;
	
	}
	
	protected function BuscarModulosxCodigoPlantMacro($datos,&$resultado,&$numfilas)
	{
		$spnombre="del_tap_plantillas_zonas_xplantmacrocod";
		$sparam=array(
			'pplantmacrocod'=> $datos['plantmacrocod']
			);
		
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al obtener los modulos por el macro de la plantilla. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		return true;
	
	}
	

	

	
	protected function Insertar($datos,&$codigoinsertado)
	{
		$spnombre="ins_tap_plantillas_zonas";
		$sparam=array(
			'pplantmacrocolumnacod'=> $datos['plantmacrocolumnacod'],
			'pcolestructuracod'=> $datos['colestructuracod'],
			'pplantcod'=> $datos['plantcod'],
			'pmacrocod'=> $datos['macrocod'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s")
			);
	
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$query,$numfilas,$errno) || $numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al insertar la tapas - plantillas - zonas. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		$codigoinsertado=$this->conexion->UltimoCodigoInsertado();

		return true;
	}
	
	
	protected function Modificar($datos)
	{
		$spnombre="upd_tap_plantillas_zonas_xzonacod";
		$sparam=array(
			'pzonadatos'=> $datos['zonadatos'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			'pzonacod'=> $datos['zonacod']
			);	
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$query,$numfilas,$errno) || $numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al modificar la zona. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		return true;
	}
	
	
	

	protected function Eliminar($datos)
	{

		$spnombre="del_tap_plantillas_zonas_xplantmacrocolumnacod";
		$sparam=array(
			'pplantmacrocolumnacod'=> $datos['plantmacrocolumnacod']
			);
		
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$query,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al eliminar la columna de la zona. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		return true;
	}
}
?>