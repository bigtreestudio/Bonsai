<?php  
abstract class cVisualizacionesdb
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

// Parámetros de Entrada:
//	$datos = array asociativos
//		visualizaciontipocod: Tipo de visualizacion

// Retorna:
//		spnombre,sparam: nombre del stored procedures y parametros.
//		la función retorna true o false si se pudo ejecutar con éxito o no


   protected function VisualizacionesSPxTipo($datos,&$spnombre,&$sparam)
   {

		$spnombre="sel_visualizaciones_xvisualizaciontipocod_xestado";
		$sparam=array(
			"pvisualizaciontipocod"=> $datos['visualizaciontipocod'], 
			"pvisualizacionestado"=> $datos['visualizacionestado'] 
			);
	   return true;
   }
  
//----------------------------------------------------------------------------------------- 
// Parámetros de Entrada:
//		datos: arreglo de datos
//			visualizacioncod = codigo de la visualizacion

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no

	protected function BuscarVisualizacionxCodigo($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_vis_visualizaciones_xvisualizacioncod";
		$sparam=array(
			'pvisualizacioncod'=> $datos['visualizacioncod']
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar la visualización por código.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;	
	}
	
 
//------------------------------------------------------------------------------------------	
// Retorna en un arreglo con los datos de una visualizacion

// Parámetros de Entrada:
//			$datos = array asociativos
// 				visualizaciondesc = descripcion de la visualizacion a buscar
//				visualizaciontipocod = codigo del tipo de visualizacion a buscar
// Retorna:
//		resultado= Arreglo con todos los datos de un formato multimedia.
//		numfilas= cantidad de filas 
//		la función retorna true o false si se pudo ejecutar con éxito o no	
	

	protected function BusquedaAvanzada ($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_vis_visualizaciones_busqueda";
		$sparam=array(
			'pxvisualizaciondesc'=> $datos['xvisualizaciondesc'],
			'pvisualizaciondesc'=> $datos['visualizaciondesc'],
			'pxvisualizaciontipocod'=> $datos['xvisualizaciontipocod'],
			'pvisualizaciontipocod'=> $datos['visualizaciontipocod'],
			'porderby'=> $datos['orderby'],
			'plimit'=> $datos['limit']
			);
	
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar las visualizaciones.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
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

		$spnombre="ins_vis_visualizaciones";
		$sparam=array(
			'pvisualizacioncod'=> $datos['visualizacioncod'],
			'pvisualizaciontipocod'=> $datos['visualizaciontipocod'],
			'pvisualizaciondesc'=> $datos['visualizaciondesc'],
			'pvisualizacionestado'=> $datos['visualizacionestado'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s")
			);
	
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al insertar la visualización.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
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
		$spnombre="upd_vis_visualizaciones_xvisualizacioncod";
		$sparam=array(
			'pvisualizaciontipocod'=> $datos['visualizaciontipocod'],
			'pvisualizaciondesc'=> $datos['visualizaciondesc'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			'pvisualizacioncod'=> $datos['visualizacioncod']
			);
	
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al modificar la visualización.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
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
		$spnombre="del_vis_visualizaciones_xvisualizacioncod";
		$sparam=array(
			'pvisualizacioncod'=> $datos['visualizacioncod']
			);
	
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al eliminar la visualización.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	} 

//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 
// Activar/Desactivar  de una visualizacion cambiando el estado (ACTIVO/NOACTIVO)

// Parámetros de Entrada:
//		datos: arreglo de datos
//			visualizacioncod = codigo de la visualizacion

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no	
	protected function ActivarDesactivar ($datos)
	{
		$spnombre="upd_vis_visualizaciones_activar_desactivar_xvisualizacioncod";
		$sparam=array(
			'pvisualizacionestado'=> $datos['visualizacionestado'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			'pvisualizacioncod'=> $datos['visualizacioncod']
			);
	
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al activar/desactivar la visualización.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;
		}
		
		return true;
	}

}


?>