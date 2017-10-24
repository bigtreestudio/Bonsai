<?php  
abstract class cPlantillasdb
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
// Retorna los datos de la plantilla

// Parámetros de Entrada:

// Retorna:
//		spnombre,spparam
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	protected function BuscarxCodigo($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_tap_plantillas_xplantcod";
		$sparam=array(
			'pplantcod'=> $datos['plantcod']
			);
		
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al obtener los datos de la plantilla. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		return true;
	
	}


	protected function BusquedaAvanzada($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_tap_plantillas_busqueda";
		$sparam=array(
			'pxplantdesc'=> $datos['xplantdesc'],
			'pplantdesc'=> $datos['plantdesc'],
			'porderby'=> $datos['orderby'],
			'plimit'=> $datos['limit']
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al obtener las plantillas. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	}

   protected function PlantillasSP(&$spnombre,&$sparam)
   {
	 	$spnombre="sel_tap_plantillas";
		$sparam=array(
			);
	   return true;
   }
   
   
   
	protected function Insertar($datos,&$codigoinsertado)
	{
		$spnombre="ins_tap_plantillas";
		$sparam=array(
			'pplanthtmlcod'=> $datos['planthtmlcod'],
			'pplantdesc'=> $datos['plantdesc'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s")
			);
	
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$query,$numfilas,$errno) || $numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al insertar la plantilla. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		$codigoinsertado=$this->conexion->UltimoCodigoInsertado();

		return true;
	}
	
	
	protected function Modificar($datos)
	{

		$spnombre="upd_tap_plantillas_xplantcod";
		$sparam=array(
			'pplantdesc'=> $datos['plantdesc'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			'pplantcod'=> $datos['plantcod']
			);		
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$query,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al modificar la plantilla. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		return true;
	}



	protected function Eliminar($datos)
	{

		$spnombre="del_tap_plantillas_xplantcod";
		$sparam=array(
			'pplantcod'=> $datos['plantcod']
			);		
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$query,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al eliminar la plantilla. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		return true;
	}

   

}


?>