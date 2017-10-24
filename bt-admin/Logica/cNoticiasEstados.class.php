<?php 
/*
CLASE LOGICA PARA EL MANEJO DE LAS ACCIONES DE LAS NOTICIAS.
*/
include(DIR_CLASES_DB."cNoticiasEstados.db.php");

class cNoticiasEstados extends cNoticiasEstadosdb	
{
	protected $conexion;
	protected $formato;
	
	
	// Constructor de la clase
	function __construct($conexion,$formato=FMT_TEXTO){
		$this->conexion = &$conexion;
		$this->formato = $formato;
		parent::__construct(); 
    } 
	
	// Destructor de la clase
	function __destruct() {	
		parent::__destruct(); 
    } 	


//-----------------------------------------------------------------------------------------
//							 PUBLICAS	
//----------------------------------------------------------------------------------------- 
	public function NoticiasEstadosSP(&$spnombre,&$spparam)
	{
		$spnombre="sel_not_noticias_estados_xorden";
		$spparam=array("porderby"=>"noticiaestadodesc");
		
		return true;
	}

//----------------------------------------------------------------------------------------- 
// Parámetros de Entrada:
//		datos: arreglo de datos
//			noticiaestadocod = codigo de la visualizacion

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function BuscarxCodigo($datos,&$resultado,&$numfilas)
	{
		if (!parent::BuscarxCodigo ($datos,$resultado,$numfilas))
			return false;
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

	function ObtenerEstadosPermitidasxRol($datos,&$resultado,&$numfilas)
	{
		if (!parent::ObtenerEstadosPermitidasxRol($datos,$resultado,$numfilas))
			return false;
		
		return true;
	}

//----------------------------------------------------------------------------------------- 
// Retorna todos los estados noticias.

// Parámetros de Entrada:
//		datos: arreglo de datos


// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no

	function ObtenerEstados($datos,&$resultado,&$numfilas)
	{
		if (!parent::ObtenerEstados($datos,$resultado,$numfilas))
			return false;
		
		return true;
	}
	
//----------------------------------------------------------------------------------------- 
// Retorna todos los estados noticias.

// Parámetros de Entrada:
//		datos: arreglo de datos


// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function ObtenerEstadosCantidadesxUsuario($datos,&$resultado,&$numfilas)
	{
		if (!parent::ObtenerEstadosCantidadesxUsuario($datos,$resultado,$numfilas))
			return false;
		
		return true;
	}
	
//----------------------------------------------------------------------------------------- 
// Retorna las acciones que tiene un usuario
// Parámetros de Entrada:
//		datos: arreglo de datos
//		usuariocod: codigo de usuario


// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no

	function ObtenerEstadosxUsuariocod($datos,&$resultado,&$numfilas)
	{
		if (!parent::ObtenerEstadosxUsuariocod($datos,$resultado,$numfilas))
			return false;
		
		return true;
	}	


//----------------------------------------------------------------------------------------- 
// Actualiza las acciones que tiene un usuario
// Parámetros de Entrada:
//		datos: arreglo de datos
//		usuariocod: codigo de usuario


// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function ActualizarEstadosUsuario($datos)
	{
		//array de acciones a asignar
		

		if (!$this->ObtenerDatosCheckEstados($datos,$arrayfinal))
			return false;
		
		if (!$this->ObtenerEstadosxUsuariocod($datos,$resultadoestados,$numfilas))
			return false;	
				
		if (!$this->ObtenerEstados($datos,$resultado,$numfilas))
			return false;

		$arregloestados= array();
		while ($fila = $this->conexion->ObtenerSiguienteRegistro($resultado))
			$arregloestados[] = $fila['noticiaestadocod'];
		

		$arrayinicial = array();
		while ($fila = $this->conexion->ObtenerSiguienteRegistro($resultadoestados))
		{	
			if (in_array($fila['noticiaestadocod'],$arregloestados))
				$arrayinicial[] = $fila['noticiaestadocod'];
		}
		
		$arraysacar = array_diff($arrayinicial,$arrayfinal);
		$arrayponer = array_diff($arrayfinal,$arrayinicial);

		$datosinsertar['usuariocod'] = $datos['usuariocod'];


		foreach($arrayponer as $noticiaestadocod)
		{
		

			$datosinsertar['noticiaestadocod'] = $noticiaestadocod;
			if (!$this->AltaUsuarioEstado($datosinsertar))
				return false;
		}
		
		$datoseliminar['usuariocod'] = $datos['usuariocod'];
		foreach($arraysacar as $noticiaestadocod)
		{
			$datoseliminar['noticiaestadocod'] = $noticiaestadocod;
			if (!$this->BajaUsuarioEstado($datoseliminar))
				return false;
		}

		return true;
	}
	
	public function AltaUsuarioEstado($datos)
	{


		if (!parent::AltaUsuarioEstado($datos))
			return false;
		
		return true;
	}
	
	public function BajaUsuarioEstado($datos)
	{

		if (!parent::BajaUsuarioEstado($datos))
			return false;
		
		return true;

	}	
//----------------------------------------------------------------------------------------- 
// Retorna si tiene estados cheuqadas
// Parámetros de Entrada:
//		datos: arreglo de datos
//		usuariocod: codigo de usuario


// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no


	public function ObtenerDatosCheckEstados($datos,&$arrayfinal)
	{
		

		$arrayfinal=array();
		foreach ($datos as $nombre_var => $valor_var) {
				
			$post[$nombre_var] = $valor_var;
			$opcion = substr($nombre_var,0,17);
			if ($opcion=="noticiaestadocod_")
			{
				$arrayfinal[] = $valor_var;
			}
		}

		return true;
	}
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
	function BusquedaAvanzada ($datos,&$resultado,&$numfilas)
	{
		$sparam=array(
			'xnoticiaestadodesc'=> 0,
			'noticiaestadodesc'=> "",
			'xnoticiaestadocte'=> 0,
			'noticiaestadocte'=> "",
			'xnoticiaestadomuestracantidad'=> 0,
			'noticiaestadomuestracantidad'=> "",
			'xnoticiaestadosemuestra'=> 0,
			'noticiaestadosemuestra'=> "",
			'orderby'=> "noticiaestadocod ASC",
			'limit'=> ""
			);		

		if (isset ($datos['noticiaestadodesc']) && $datos['noticiaestadodesc']!="")
		{
			$sparam['noticiaestadodesc']= $datos['noticiaestadodesc'];
			$sparam['xnoticiaestadodesc']= 1;
		}	
		if (isset ($datos['noticiaestadocte']) && $datos['noticiaestadocte']!="")
		{
			$sparam['noticiaestadocte']= $datos['noticiaestadocte'];
			$sparam['xnoticiaestadocte']= 1;
		}
		
		if (isset ($datos['noticiaestadomuestracantidad']) && $datos['noticiaestadomuestracantidad']!="")
		{
			$sparam['noticiaestadomuestracantidad']= $datos['noticiaestadomuestracantidad'];
			$sparam['xnoticiaestadomuestracantidad']= 1;
		}

		if (isset ($datos['noticiaestadosemuestra']) && $datos['noticiaestadosemuestra']!="")
		{
			$sparam['noticiaestadosemuestra']= $datos['noticiaestadosemuestra'];
			$sparam['xnoticiaestadosemuestra']= 1;
		}

		
		if (isset ($datos['orderby']) && $datos['orderby']!="")
			$sparam['orderby']= $datos['orderby'];
	
		if (isset ($datos['limit']) && $datos['limit']!="")
			$sparam['limit']= $datos['limit'];
		if (!parent::BusquedaAvanzada($sparam,$resultado,$numfilas))
			return false;

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

	
	public function Insertar($datos,&$codigoinsertado)
	{
		if (!$this->_ValidarDatosAlta($datos))
			return false;
			
		if(!parent::Insertar($datos,$codigoinsertado))
			return false;
			
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

	
	public function Modificar($datos)
	{
		if (!$this->_ValidarDatosModificar($datos))
			return false;
		
		if(!parent::Modificar($datos))
			return false;
		return true;
	} 
	
//----------------------------------------------------------------------------------------- 
// Eliminar una noticia estado
// Parámetros de Entrada:
//		datos: arreglo de datos
// Parámetros de Entrada:
//			noticiaestadocod = codigo de la noticia estado

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function Eliminar($datos)
	{	
		if (!$this->_ValidarEliminarDatos($datos))
			return false;
			
		if(!parent::Eliminar($datos))
			return false;

		return true;	
	}	
	
//-----------------------------------------------------------------------------------------
//							 PRIVADAS	
//----------------------------------------------------------------------------------------- 

//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 
// Retorna true o false si algunos de los campos esta vacio

// Parámetros de Entrada:
//			noticiaestadocod = codigo de la noticia estado
//			noticiaestadodesc: descripción del estado de la noticia estado
//			noticiaestadocte: descripción de la contante de la noticia estado
//			noticiaestadomuestracantidad: codigo si muestra cantidad de la noticia estado 
//          noticiaestadosemuestra: codigo si muestra de la noticia estado 
// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no


	public function _ValidarDatosVacios($datos)
	{
		if (isset($datos['noticiaestadocod']) && $datos['noticiaestadocod']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar un código.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}else
		{
			if ($datos['noticiaestadocod']<0  || $datos['noticiaestadocod']>99)
			{
				FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar un código ente 0 y 99.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
				return false;
			}
		}
		if (isset($datos['noticiaestadodesc']) && $datos['noticiaestadodesc']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar una descripción.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		if (isset($datos['noticiaestadocte']) && $datos['noticiaestadocte']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar una constante.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		if (!isset ($datos['noticiaestadomuestracantidad']) || ($datos['noticiaestadomuestracantidad']==""))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar si muestra cantidad.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		else
		{
			if($datos['noticiaestadomuestracantidad']!= 1 && $datos['noticiaestadomuestracantidad']!= 0)
			{
				FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, código de se muestra cantidad erroneo.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
				return false;
			}
		
		}
		
		if (!isset ($datos['noticiaestadosemuestra']) || ($datos['noticiaestadosemuestra']==""))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar si muestra.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		else
		{
			if($datos['noticiaestadosemuestra']!= 0 && $datos['noticiaestadosemuestra']!= 1)
			{
				FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, código de se muestra erroneo.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
				return false;
			}
		
		}

		
		return true;
	}

//----------------------------------------------------------------------------------------- 
// Retorna true o false al dar de alta si algunos de los campos esta vacio 
// Parámetros de Entrada:
//			noticiaestadocod = codigo de la noticia estado
//			noticiaestadodesc: descripción del estado de la noticia estado
//			noticiaestadocte: descripción de la contante de la noticia estado
//			noticiaestadomuestracantidad: codigo si muestra cantidad de la noticia estado 
//          noticiaestadosemuestra: codigo si muestra de la noticia estado 
// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	function _ValidarDatosAlta($datos)
	{
		if (!$this->_ValidarDatosVacios($datos))
			return false;
		if (!$this->BuscarxCodigo($datos,$resultado,$numfilas))
			return false;
		if ($numfilas!=0)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, ya existe una noticia estado con ese codigo.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;
		}

		return true;
	}

//----------------------------------------------------------------------------------------- 
// Retorna true o false al modificar si algunos de los campos esta vacio 

// Parámetros de Entrada:
//			noticiaestadocod = codigo de la noticia estado
//			noticiaestadodesc: descripción del estado de la noticia estado
//			noticiaestadocte: descripción de la contante de la noticia estado
//			noticiaestadomuestracantidad: codigo si muestra cantidad de la noticia estado 
//          noticiaestadosemuestra: codigo si muestra de la noticia estado 
// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no


	public function _ValidarDatosModificar($datos)
	{
		if (!$this->_ValidarDatosVacios($datos))
			return false;
	

		if (!$this->BuscarxCodigo($datos,$resultado,$numfilas))
			return false;
		if ($numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, página estado inexistente.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;
		}
		
		return true;
	}
//----------------------------------------------------------------------------------------- 
// Retorna true o false al validar eliminar la noticia estado

// Parámetros de Entrada:
//			noticiaestadocod = codigo de la noticia estado
// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no	


	private function _ValidarEliminarDatos($datos)
	{
		
		if (!$this->BuscarxCodigo($datos,$resultado,$numfilas))
			return false;
		if ($numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, página estado inexistente.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;
		}
			
		return true;
	}	

//----------------------------------------------------------------------------------------- 
	
}//FIN CLASE

?>