<?php  
abstract class cTapasModulosCategoriasdb
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
	protected function BuscarSPxTipo($datos,&$spnombre,&$sparam)
	{
		$spnombre="sel_tap_modulos_categorias_activas_xmodulotipocod";
		$sparam=array(
			"pmodulotipocod"=>$datos['modulotipocod']
			);
		
		return true;
	}
	

	protected function Buscar(&$resultado,&$numfilas)
	{
		$spnombre="sel_tap_modulos_categorias";
		$sparam=array(
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar los modulos categorias de las tapas.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	}
	
	protected function BuscarSP(&$spnombre,&$sparam)
	{
		$spnombre="sel_tap_modulos_categorias_activas";
		$sparam=array(
			);
		
		return true;
	}
	
	protected function BuscarxCodigo($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_tap_modulos_categorias_xcatcod";
		$sparam=array(
			'pcatcod'=> $datos['catcod']
			);	
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar las cagorias de los modulos de latapa por código.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	}

	protected function BusquedaAvanzadaTapasNoticiasModulos($datos,&$resultado,&$numfilas)
	{
		

		$spnombre="sel_tap_modulos";
		$sparam=array(
			'pxmodulodesc'=> $datos['xmodulodesc'],
			'pmodulodesc'=> $datos['modulodesc'],
			'porderby'=> $datos['orderby'],
			'plimit'=> $datos['limit']
		
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar los modulos categorias de las noticias.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	}
	
	protected function SpModulosTapasxCodigo($datos,&$spnombre,&$sparam)
	{

		$spnombre="sel_tap_modulos_xcatcod";
		$sparam=array(
			'pmoduloestado'=> $datos['moduloestado'],
			'pcatcod'=> $datos['catcod'],
			'pmodulotipocod'=> $datos['modulotipocod']
			);
		
		return true;
	}
	
	protected function BuscarModulosTapasxCodigo($datos,&$resultado,&$numfilas)
	{
		$this->SpModulosTapasxCodigo($datos,$spnombre,$sparam);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar los modulos categorias de las tapas.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	}
	


}
?>