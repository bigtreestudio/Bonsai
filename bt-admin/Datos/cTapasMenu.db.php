<?php  
abstract class cTapasMenudb
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
		$spnombre="sel_tap_menu_xmenucod";
		$sparam=array(
			'pmenucod'=> $datos['menucod']
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar las tapas por codigo.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	}
	
	protected function BuscarDominios($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_dominios_sitio";
		$sparam=array(
					'pxtipo'=> $datos['pxtipo'],
					'ptipo'=> $datos['ptipo'],
					'plimit'=> $datos['limit'],
					'porderby'=> $datos['orderby']
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar los dominios del sitio.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	}	

	protected function BuscarxTipo($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_tap_menu_xmenutipocod";
		$sparam=array(
			'pmenutipocod'=> $datos['menutipocod'],
			'porderby'=> $datos['orderby']
			);

		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar las tapas.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	}
	
	
	protected function BuscarMenuxSuperior($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_tap_menu_xmenucodsup";
		$sparam=array(
			'pmenucodsup'=> $datos['menucodsup'],
			'pmenutipocod'=> $datos['menutipocod']
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar las tapas por superior.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	}
	
	
	
	protected function BuscaMenusRaiz($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_tap_menu_xmenucodsupnull";
		$sparam=array(
			'pmenutipocod'=> $datos['menutipocod']
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar las tapas por superior.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	}
	
	
	
	
	
	
	protected function Insertar($datos,&$codigoinsertado)
	{			
		$spnombre="ins_tap_menu";
		$sparam=array(
			'pmenudesc'=> $datos['menudesc'],
			'pmenulink'=> $datos['menulink'],
			'pmenutitle'=> $datos['menutitle'],
			'pmenuaccesskey'=> $datos['menuaccesskey'],
			'pmenucodsup'=> $datos['menucodsup'],
			'pmenutipocod'=> $datos['menutipocod'],
			'pmenuorden'=> $datos['menuorden'],
			'pmenutarget'=> $datos['menutarget'],
			'pmenuclass'=> $datos['menuclass'],
			'pmenuclassli'=> $datos['menuclassli'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s")
			);
	
					
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$query,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al insertar un nuevo menu. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		$codigoinsertado=$this->conexion->UltimoCodigoInsertado();

		return true;
	}
	
	

	protected function Modificar($datos)
	{
		$spnombre="upd_tap_menu_xmenucod";
		$sparam=array(
			'pmenudesc'=> $datos['menudesc'],
			'pmenulink'=> $datos['menulink'],
			'pmenutitle'=> $datos['menutitle'],
			'pmenuaccesskey'=> $datos['menuaccesskey'],
			'pmenutarget'=> $datos['menutarget'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			'pmenucod'=> $datos['menucod'],
			'pmenuclass'=> $datos['menuclass'],
			'pmenuclassli'=> $datos['menuclassli'],
			'pmenutipocod'=> $datos['menutipocod']
			);			
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno) )
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al modificar del menú. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;
		}
		
		return true;	
	}
	


	protected function Eliminar($datos)
	{
		$spnombre="del_tap_menu_xmenucod";
		$sparam=array(
			'pmenucod'=> $datos['menucod']
			);

		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno) )
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al eliminar del menú. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;
		}
		
		return true;	
	}
	



	protected function ModificarOrden($datos)
	{
		$spnombre="upd_tap_menu_orden_xmenucod_xmenutipocod";
		$sparam=array(
			'pmenuorden'=> $datos['menuorden'],
			'pmenucodsup'=> $datos['menucodsup'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			'pmenucod'=> $datos['menucod'],
			'pmenutipocod'=> $datos['menutipocod']
			);
			
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno) )
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al modificar del menú. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;
		}
		
		return true;	
	}
	



	protected function BuscarUltimoOrden($datos,&$resultado,&$numfilas)
	{

		$spnombre="sel_tap_menu_maxorden_xmenutipocod";
		$sparam=array(
			'pmenutipocod'=> $datos['menutipocod']
			);

		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar el proximo orden.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;	
	}
}
?>