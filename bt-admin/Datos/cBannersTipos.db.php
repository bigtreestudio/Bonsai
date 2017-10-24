<?php  
abstract class cBannersTiposdb
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

// Retorna una consulta con los datos del tipo de banner

// Parámetros de Entrada:
//		datos: arreglo de datos
//		    bannertipocod: código del tipo de banner

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	function BuscarBannerTipoxCodigo($datos,&$resultado,&$numfilas)
	{
		
		$spnombre="sel_ban_banners_tipos_xbannertipocod";
		$sparam=array(
			'pbannertipocod'=> $datos['bannertipocod']
			);
		
		
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar los banners por tipo. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
			return false;
		}
		
		return true;	
	}
	
//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 

//Busca banner tipo

// Retorna una consulta con los datos de todos los tipos de banner

// Parámetros de Entrada:
//		datos: arreglo de datos

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no

	function BuscarBannerTipo($datos,&$resultado,&$numfilas)
	{
		
		$spnombre="sel_ban_banners_tipos";
		$sparam=array(
			'porderby'=> "bannertipocod"
			);
		
		
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar los banners por tipo. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
			return false;
		}
		
		return true;	
	}

		
//------------------------------------------------------------------------------------------	
// Retorna en un arreglo con los datos de un formato multimedia

// Parámetros de Entrada:
//		    bannertipodesc: descripción del tipo de banner
//			bannerancho: ancho del tipo de banner
//			banneralto: alto del tipo de banner
// Retorna:
//		resultado= Arreglo con todos los datos de un formato multimedia.
//		numfilas= cantidad de filas 
//		la función retorna true o false si se pudo ejecutar con éxito o no	
	

	protected function BusquedaAvanzada ($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_ban_banners_tipos_busqueda";
		$sparam=array(
			'pxbannertipodesc'=> $datos['xbannertipodesc'],
			'pbannertipodesc'=> $datos['bannertipodesc'],
			'pxbanneralto'=> $datos['xbanneralto'],
			'pbanneralto'=> $datos['banneralto'],
			'pxbannerancho'=> $datos['xbannerancho'],
			'pbannerancho'=> $datos['bannerancho'],
			'porderby'=> $datos['orderby'],
			'plimit'=> $datos['limit']
			);	
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar el formato multimedia.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	}
//----------------------------------------------------------------------------------------- 
// Inserta nuevo tipo de banner

// Parámetros de Entrada:
//		    bannertipodesc: descripción del tipo de banner
//			bannerancho: ancho del tipo de banner
//			banneralto: alto del tipo de banner
// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	protected function Insertar($datos,&$codigoinsertado)
	{

		$spnombre="ins_ban_banners_tipos";
		$sparam=array(
			'pbannertipodesc'=> $datos['bannertipodesc'],
			'pbanneralto'=> $datos['banneralto'],
			'pbannerancho'=> $datos['bannerancho'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s")
			);	
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al insertar el formato multimedia.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	} 

//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 
// Modifica los datos de un tipo de banner

// Parámetros de Entrada:
 //		    bannertipocod: código del tipo de banner
//		    bannertipodesc: descripción del tipo de banner
//			bannerancho: ancho del tipo de banner
//			banneralto: alto del tipo de banner
// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	protected function Modificar($datos)
	{
		$spnombre="upd_ban_banners_tipos_xbannertipocod";
		$sparam=array(
			'pbannertipodesc'=> $datos['bannertipodesc'],
			'pbanneralto'=> $datos['banneralto'],
			'pbannerancho'=> $datos['bannerancho'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			'pbannertipocod'=> $datos['bannertipocod']
			);	
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al modificar el formato multimedia.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	} 
	
//----------------------------------------------------------------------------------------- 

// Eliminar un tipo de banner
// Parámetros de Entrada:
//		datos: arreglo de datos
 //		    bannertipocod: código del tipo de banner

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	protected function Eliminar($datos)
	{
		$spnombre="del_ban_banners_tipos_xbannertipocod";
		$sparam=array(
			'pbannertipocod'=> $datos['bannertipocod']
			);	
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al eliminar el formato multimedia.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	} 

}
?>