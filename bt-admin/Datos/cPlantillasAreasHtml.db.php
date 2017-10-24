<?php  
abstract class cPlantillasAreasHtmldb
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



	
	protected function TraerAreasHtml(&$resultado,&$numfilas)
	{

		$spnombre="sel_plantillas_areas_html";
		$sparam=array(
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar las areas. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
			return false;
		}
		
		return true;
	}

	
	protected function TraerAreasHtmlxCodigo($datos,&$resultado,&$numfilas)
	{

		$spnombre="sel_tap_plantillas_areas_html_xareahtmlcod";
		$sparam=array(
			'pareahtmlcod'=> $datos['areahtmlcod']
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar el area. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
			return false;
		}
		
		return true;
	}

	
	protected function BuscarAreasxAreaHtml($datos,&$resultado,&$numfilas)
	{

		$spnombre="sel_tap_plantillas_areas_xareahtmlcod";
		$sparam=array(
			'pareahtmlcod'=> $datos['areahtmlcod']
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar el area. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
			return false;
		}
		
		return true;
	}

	
	protected function Insertar($datos,&$codigoinsertado)
	{
		$spnombre="ins_tap_plantillas_areas_html";
		$sparam=array(
			'pareahtmldesc'=> $datos['areahtmldesc'],
			'pareahtmlinicio'=> $datos['areahtmlinicio'],
			'pareahtmlfin'=> $datos['areahtmlfin'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s")
			);

		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$query,$numfilas,$errno) || $numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al insertar el area html. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		$codigoinsertado=$this->conexion->UltimoCodigoInsertado();

		return true;
	}
	
	protected function Modificar($datos)
	{
		$spnombre="upd_tap_plantillas_areas_html_xareahtmlcod";
		$sparam=array(
			'pareahtmldesc'=> $datos['areahtmldesc'],
			'pareahtmlinicio'=> $datos['areahtmlinicio'],
			'pareahtmlfin'=> $datos['areahtmlfin'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			'pareahtmlcod'=> $datos['areahtmlcod']
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$query,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al modificar el area html. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		return true;
	}
	

	protected function Eliminar($datos)
	{
		$spnombre="del_tap_plantillas_areas_html_xareahtmlcod";
		$sparam=array(
			'pareahtmlcod'=> $datos['areahtmlcod']
			);


		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$query,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al eliminar el area de la plantilla. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		return true;
	}
	


}
?>