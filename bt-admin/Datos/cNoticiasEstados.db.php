<?php  
abstract class cNoticiasEstadosdb
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
// Parámetros de Entrada:
//		datos: arreglo de datos
//			noticiaestadocod = codigo de la visualizacion

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no

	protected function BuscarxCodigo($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_not_noticias_estados_xnoticiaestadocod";
		$sparam=array(
			'pnoticiaestadocod'=> $datos['noticiaestadocod']
			);		
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar el estado de la noticia por código.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;	
	}
	
 

//----------------------------------------------------------------------------------------- 
// Retorna los estados a los cuales tiene permiso un usuario

// Parámetros de Entrada:
//		datos: arreglo de datos
//			usuariocod = codigo del usuario

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no
	protected function ObtenerEstadosPermitidasxRol($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_not_noticias_estados_xusuario";
		$sparam=array(
			'pusuariocod'=> $datos['usuariocod']
			);	
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar los estados del usuario.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	}


//----------------------------------------------------------------------------------------- 
// Retorna los estados a los cuales tiene permiso un usuario

// Parámetros de Entrada:
//		datos: arreglo de datos
//			usuariocod = codigo del usuario

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no
	protected function ObtenerEstadosCantidadesxUsuario($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_not_noticias_estados_cantidades_xusuariocod";
		$sparam=array(
			'pusuariocod'=> $datos['usuariocod']
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
		$spnombre="sel_not_noticias_estados";
		$sparam=array(
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar los estados de la noticia.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	}


//----------------------------------------------------------------------------------------- 
// Retorna las acciones de un usuario dado

// Parámetros de Entrada:
//		usuariocod: codigo de usuario


// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no
	protected function ObtenerEstadosxUsuariocod($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_not_noticias_estados_usuarios_xusuariocod";
		$sparam=array(
			'pusuariocod'=> $datos['usuariocod']
			);
			
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar los estados de un usuario.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	}
	
//----------------------------------------------------------------------------------------- 



	protected function AltaUsuarioEstado($datos)
	{
		
		$spnombre="ins_not_noticias_estados_usuarios";
		$sparam=array(
			'pusuariocod'=> $datos['usuariocod'],
			'pnoticiaestadocod'=> $datos['noticiaestadocod'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s")
			);
		
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno) || $numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"No se pudieron insertar el usuario al estado. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
			return false;
		}
		
		return true;

	}


	protected function BajaUsuarioEstado($datos)
	{
		
		$spnombre="del_not_noticias_estados_usuarios_xusuariocod_noticiaestadocod";
		$sparam=array(
			'pusuariocod'=> $datos['usuariocod'],
			'pnoticiaestadocod'=> $datos['noticiaestadocod']
			);
					
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno) || $numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"No se pudieron dar de baja el usuario del estado. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
			return false;
		}
		
		return true;

	}
	


//------------------------------------------------------------------------------------------	
//------------------------------------------------------------------------------------------	
// Retorna en un arreglo con los datos de una pagina estado

// Parámetros de Entrada:
//			noticiaestadodesc: descripción del estado de la noticia estado
//			noticiaestadocte: descripción de la contante de la noticia estado
//			noticiaestadomuestracantidad: codigo si muestra cantidad de la noticia estado 
//          noticiaestadosemuestra: codigo si muestra de la noticia estado 
// Retorna:
//		resultado= Arreglo con todos los datos de un formato multimedia.
//		numfilas= cantidad de filas 
//		la función retorna true o false si se pudo ejecutar con éxito o no	
	

	protected function BusquedaAvanzada ($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_not_noticias_estados_busqueda";
		$sparam=array(
			'pxnoticiaestadodesc'=> $datos['xnoticiaestadodesc'],
			'pnoticiaestadodesc'=> $datos['noticiaestadodesc'],
			'pxnoticiaestadocte'=> $datos['xnoticiaestadocte'],
			'pnoticiaestadocte'=> $datos['noticiaestadocte'],
			'pxnoticiaestadomuestracantidad'=> $datos['xnoticiaestadomuestracantidad'],
			'pnoticiaestadomuestracantidad'=> $datos['noticiaestadomuestracantidad'],
			'pxnoticiaestadosemuestra'=> $datos['xnoticiaestadosemuestra'],
			'pnoticiaestadosemuestra'=> $datos['noticiaestadosemuestra'],
			'porderby'=> $datos['orderby'],
			'plimit'=> $datos['limit']
			);		
			
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar los estados noticia.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	}
	
//----------------------------------------------------------------------------------------- 
// Inserta nueva noticia estado

// Parámetros de Entrada:
//			noticiaestadocod = codigo de la noticia estado
//			noticiaestadodesc: descripción del estado de la noticia estado
//			noticiaestadocte: descripción de la contante de la noticia estado
//			noticiaestadomuestracantidad: codigo si muestra cantidad de la noticia estado 
//          noticiaestadosemuestra: codigo si muestra de la noticia estado 
// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	protected function Insertar($datos,&$codigoinsertado)
	{
		$spnombre="ins_not_noticias_estados";
		$sparam=array(
			'pnoticiaestadocod'=> $datos['noticiaestadocod'],
			'pnoticiaestadodesc'=> $datos['noticiaestadodesc'],
			'pnoticiaestadocte'=> $datos['noticiaestadocte'],
			'pnoticiaestadomuestracantidad'=> $datos['noticiaestadomuestracantidad'],
			'pnoticiaestadosemuestra'=> $datos['noticiaestadosemuestra'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s")
			);	
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al insertar el estado noticia.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	} 

//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 
// Modifica los datos de una noticia estado

// Parámetros de Entrada:
// Parámetros de Entrada:
//			noticiaestadocod = codigo de la noticia estado
//			noticiaestadodesc: descripción del estado de la noticia estado
//			noticiaestadocte: descripción de la contante de la noticia estado
//			noticiaestadomuestracantidad: codigo si muestra cantidad de la noticia estado 
//          noticiaestadosemuestra: codigo si muestra de la noticia estado 
// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	protected function Modificar($datos)
	{
		$spnombre="upd_not_noticias_estados_xnoticiaestadocod";
		$sparam=array(
			'pnoticiaestadodesc'=> $datos['noticiaestadodesc'],
			'pnoticiaestadocte'=> $datos['noticiaestadocte'],
			'pnoticiaestadomuestracantidad'=> $datos['noticiaestadomuestracantidad'],
			'pnoticiaestadosemuestra'=> $datos['noticiaestadosemuestra'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			'pnoticiaestadocod'=> $datos['noticiaestadocod']
			);		
		
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al modificar el estado noticia.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	} 
	
//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 
// Eliminar una noticia estado
// Parámetros de Entrada:
//		datos: arreglo de datos
// Parámetros de Entrada:
//			noticiaestadocod = codigo de la noticia estado

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	protected function Eliminar($datos)
	{
		$spnombre="del_not_noticias_estados_xnoticiaestadocod";
		$sparam=array(
			'pnoticiaestadocod'=> $datos['noticiaestadocod']
			);
			
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al eliminar el estado noticia.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	} 

}


?>