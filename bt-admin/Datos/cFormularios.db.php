<?php  
abstract class cFormulariosdb
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
		$spnombre="sel_con_formulario_xformulariocod";
		$sparam=array(
			'pformulariocod'=> $datos['formulariocod']
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar el formulario por código.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	}

	protected function BuscarTiposFormulariosSP($datos,&$spnombre,&$sparam)
	{

		$spnombre="sel_con_formulario_tipos";
		$sparam=array(
			
			);
		
		return true;
	}
	
	protected function BusquedaAvanzada($datos,&$resultado,&$numfilas)
	{
		
		
		$spnombre="sel_con_formulario_datos_busqueda_avanzada";
		$sparam=array(
			'pxformularionombre'=> $datos['xformularionombre'],
			'pformularionombre'=> $datos['formularionombre'],
			'pxformulariotipocod'=> $datos['xformulariotipocod'],
			'pformulariotipocod'=> $datos['formulariotipocod'],						
			'porderby'=> $datos['orderby'],
			'plimit'=> $datos['limit']
			);
			
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar los datos de los formularios.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	}


	protected function BuscarDatosxCodigo($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_con_formulario_datos_xcodigo";
		$sparam=array(
			'pformulariodatoscod'=> $datos['formulariodatoscod']
			);	
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar el dato del formulario por código.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	}


}
?>