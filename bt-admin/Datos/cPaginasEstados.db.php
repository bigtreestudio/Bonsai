<?php  
abstract class cPaginasEstadosdb
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
// Retorna los estados a los cuales tiene permiso un usuario

// Parámetros de Entrada:
//		datos: arreglo de datos
//			usuariocod = codigo del usuario

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no
	protected function ObtenerEstadosCantidades($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_pag_paginas_estados_cantidades";
		$sparam=array(
			);	
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar las cantidades de estados por usuario.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	}


//----------------------------------------------------------------------------------------- 
// Retorna los estados a los cuales tiene permiso un usuario

// Parámetros de Entrada:
//		datos: arreglo de datos


// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no
	protected function ObtenerEstados($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_pag_paginas_estados";
		$sparam=array(
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar los estados de la pagina.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	}


// Parámetros de Entrada:
//		datos: arreglo de datos
//			pagestadocod = codigo de la pagina estado

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no

	protected function BuscarxCodigo($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_pag_paginas_estados_xpagestadocod";
		$sparam=array(
			'ppagestadocod'=> $datos['pagestadocod']
			);		
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar el estado de la página por código.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;	
	}
	
//------------------------------------------------------------------------------------------	

//------------------------------------------------------------------------------------------	
// Retorna en un arreglo con los datos de una pagina estado

// Parámetros de Entrada:
//			pagestadodesc: descripción del estado de la pagina estado
//			pagestadocte: descripción de la contante de la pagina estado
//			pagestadomuestracantidad: codigo si muestra cantidad de la pagina estado 
//          pagestadosemuestra: codigo si muestra de la pagina estado 
// Retorna:
//		resultado= Arreglo con todos los datos de un formato multimedia.
//		numfilas= cantidad de filas 
//		la función retorna true o false si se pudo ejecutar con éxito o no		
	

	protected function BusquedaAvanzada ($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_pag_paginas_estados_busqueda";
		$sparam=array(
			'pxpagestadodesc'=> $datos['xpagestadodesc'],
			'ppagestadodesc'=> $datos['pagestadodesc'],
			'pxpagestadocte'=> $datos['xpagestadocte'],
			'ppagestadocte'=> $datos['pagestadocte'],
			'pxpagestadomuestracantidad'=> $datos['xpagestadomuestracantidad'],
			'ppagestadomuestracantidad'=> $datos['pagestadomuestracantidad'],
			'pxpagestadosemuestra'=> $datos['xpagestadosemuestra'],
			'ppagestadosemuestra'=> $datos['pagestadosemuestra'],
			'porderby'=> $datos['orderby'],
			'plimit'=> $datos['limit']
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar los estados páginas.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	}
	
//----------------------------------------------------------------------------------------- 
// Inserta nueva pagina estado

// Parámetros de Entrada:
//			pagestadocod = codigo de la pagina estado
//			pagestadodesc: descripción del estado de la pagina estado
//			pagestadocte: descripción de la contante de la pagina estado
//			pagestadomuestracantidad: codigo si muestra cantidad de la pagina estado 
//          pagestadosemuestra: codigo si muestra de la pagina estado 
// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	protected function Insertar($datos,&$codigoinsertado)
	{
		$spnombre="ins_pag_paginas_estados";
		$sparam=array(
			'ppagestadocod'=> $datos['pagestadocod'],
			'ppagestadodesc'=> $datos['pagestadodesc'],
			'ppagestadocte'=> $datos['pagestadocte'],
			'ppagestadomuestracantidad'=> $datos['pagestadomuestracantidad'],
			'ppagestadosemuestra'=> $datos['pagestadosemuestra'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s")
			);
	
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al insertar el estado página.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	} 

//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 
// Modifica los datos de una pagina estado

// Parámetros de Entrada:
//			pagestadocod = codigo de la pagina estado
//			pagestadodesc: descripción del estado de la pagina estado
//			pagestadocte: descripción de la contante de la pagina estado
//			pagestadomuestracantidad: codigo si muestra cantidad de la pagina estado 
//          pagestadosemuestra: codigo si muestra de la pagina estado 
// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	protected function Modificar($datos)
	{
		$spnombre="upd_pag_paginas_estados_xpagestadocod";
		$sparam=array(
			'ppagestadodesc'=> $datos['pagestadodesc'],
			'ppagestadocte'=> strtoupper ($datos['pagestadocte']),
			'ppagestadomuestracantidad'=> $datos['pagestadomuestracantidad'],
			'ppagestadosemuestra'=> $datos['pagestadosemuestra'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			'ppagestadocod'=> $datos['pagestadocod']
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al modificar el estado página.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	} 
	
//----------------------------------------------------------------------------------------- 

// Eliminar una pagina estado
// Parámetros de Entrada:
//		datos: arreglo de datos
// Parámetros de Entrada:
//			pagestadocod = codigo de la pagina estado

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	protected function Eliminar($datos)
	{
		$spnombre="del_pag_paginas_estados_xpagestadocod";
		$sparam=array(
			'ppagestadocod'=> $datos['pagestadocod']
			);
	
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al eliminar el estado página.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	} 

}


?>