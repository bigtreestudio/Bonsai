<?php  
abstract class cPlantillasHtmldb
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
		$spnombre="sel_tap_plantillas_html_xplanthtmlcod";
		$sparam=array(
			'pplanthtmlcod'=> $datos['planthtmlcod']
			);		
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al obtener los datos de la plantilla (html). ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		return true;
	
	}


   protected function PlantillasHtmlSP(&$spnombre,&$sparam)
   {

		$spnombre="sel_tap_plantillas_html";
		$sparam=array(
			);
	   return true;
   }
   
   
	protected function BusquedaAvanzada($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_tap_plantillas_html_busqueda";
		$sparam=array(
			'pxplanthtmldesc'=> $datos['xplanthtmldesc'],
			'pplanthtmldesc'=> $datos['planthtmldesc'],
			'porderby'=> $datos['orderby'],
			'plimit'=> $datos['limit']
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al obtener las plantillas html. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	}



	protected function BuscarPlantillasxPlanthtmlcod($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_tap_plantillas_xplanthtmlcod";
		$sparam=array(
			'pplanthtmlcod'=> $datos['planthtmlcod']
			);

		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al obtener las plantillas por plantilla html. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	}



   
   	protected function Insertar($datos,&$codigoinsertado)
	{			
		$spnombre="ins_tap_plantillas_html";
		$sparam=array(
			'pplanthtmldesc'=> $datos['planthtmldesc'],
			'pplanthtmlheader'=> $datos['planthtmlheader'],
			'pplanthtmlfooter'=> $datos['planthtmlfooter'],
			'pplanthtmldisco'=> $datos['planthtmldisco'],
			'pplanthtmldefault'=> $datos['planthtmldefault'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s")
			);				
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$query,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al insertar una nueva plantilla. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		$codigoinsertado=$this->conexion->UltimoCodigoInsertado();

		return true;
	}

   
  	protected function Modificar($datos)
	{
		$spnombre="upd_tap_plantillas_html_xplanthtmlcod";
		$sparam=array(
			'pplanthtmldesc'=> $datos['planthtmldesc'],
			'pplanthtmlheader'=> $datos['planthtmlheader'],
			'pplanthtmlfooter'=> $datos['planthtmlfooter'],
			'pplanthtmldisco'=> $datos['planthtmldisco'],
			'pplanthtmldefault'=> $datos['planthtmldefault'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			'pplanthtmlcod'=> $datos['planthtmlcod']
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$query,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al modificar la plantilla. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		return true;	
	}
	
 
  	protected function ResetearPlantillasDefault()
	{
		$spnombre="upd_tap_plantillas_html_resetdefault";
		$sparam=array(
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$query,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al resetear las plantillas default. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		return true;	
	}
	
 

  	protected function Eliminar($datos)
	{
		$spnombre="del_tap_plantillas_html_xplanthtmlcod";
		$sparam=array(
			'pplanthtmlcod'=> $datos['planthtmlcod']
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