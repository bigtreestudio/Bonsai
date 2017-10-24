<?php  
abstract class cTapasMenuTiposdb
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

	protected function BuscarxCte($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_tap_menu_tipos_xmenutipocte";
		$sparam=array(
			'pmenutipocte'=> $datos['menutipocte']
			);
	
	
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar el tipo de menú por constante.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	}



	protected function BuscarxCodigo($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_tap_menu_tipos_xmenutipocod";
		$sparam=array(
			'pmenutipocod'=> $datos['menutipocod']
			);
	
	
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar el tipo de menú.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	}
	


	protected function Buscar($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_tap_menu_tipos";
		$sparam=array(
			);
	
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar el tipo de menú.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	}

	protected function BusquedaAvanzada($datos,&$resultado,&$numfilas)
	{	
		$spnombre="sel_tap_menu_tipos_busqueda";
		$sparam=array(
			'porderby'=> $datos['orderby'],
			'plimit'=> $datos['limit']
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar el tipo de menu.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	}

	protected function BusquedaAvanzadaMenuTipo($datos,&$resultado,&$numfilas)
	{	
		$spnombre="sel_tap_menu_tipos_busqueda";
		
		
		$sparam=array(
			'pxmenutipodesc'=> $datos['xmenutipodesc'],
			'pmenutipodesc'=> $datos['menutipodesc'],
			'pxmenutipocte'=> $datos['xmenutipocte'],
			'pmenutipocte'=> $datos['menutipocte'],
			'pxmenuclass'=> $datos['xmenuclass'],
			'pmenuclass'=> $datos['menuclass'],
			'pxmenutipoarchivo'=> $datos['xmenutipoarchivo'],
			'pmenutipoarchivo'=> $datos['menutipoarchivo'],
			'porderby'=> $datos['orderby'],
			'plimit'=> $datos['limit']
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar el tipo de menú.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	}
	
	//----------------------------------------------------------------------------------------- 
// Inserta nuevo formato

// Parámetros de Entrada:
//			visualizaciondesc: descripción de la visualizacion 
//			visualizaciontipocod: codigo del tipo visualizacion
// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	protected function Insertar($datos,&$codigoinsertado)
	{

		$spnombre="ins_tap_menu_tipos";
		$sparam=array(
			'pmenutipodesc'=> $datos['menutipodesc'],
			'pmenutipocte'=> strtoupper($datos['menutipocte']),
			'pmenutipoarchivo'=> $datos['menutipoarchivo'],
			'pmenuanchoautomatico'=> $datos['menuanchoautomatico'],
			'pmenuclass'=> $datos['menuclass'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s")
			);
	
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al insertar el tipo de menú.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	} 

//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 
// Modifica los datos de un formato

// Parámetros de Entrada:
//			visualizaciondesc: descripción de la visualizacion 
//			visualizaciontipocod: codigo del tipo visualizacion
// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	protected function Modificar($datos)
	{
		$spnombre="upd_tap_menu_tipos_xmenutipocod";
		$sparam=array(
			'pmenutipodesc'=> $datos['menutipodesc'],
			'pmenutipocte'=> strtoupper($datos['menutipocte']),
			'pmenutipoarchivo'=> $datos['menutipoarchivo'],
			'pmenuanchoautomatico'=> $datos['menuanchoautomatico'],
			'pmenuclass'=> $datos['menuclass'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			'pmenutipocod'=> $datos['menutipocod']
			);
	
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al modificar el tipo de menú.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	} 
	
//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 
// Eliminar un formato multimedia
// Parámetros de Entrada:
//		datos: arreglo de datos
//			visualizacioncod = codigo de la visualizacion

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	protected function Eliminar($datos)
	{
		$spnombre="del_tap_menu_tipos_xmenutipocod";
		$sparam=array(
			'pmenutipocod'=> $datos['menutipocod']
			);
	
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al eliminar el tipo de menú.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	} 


}


?>
