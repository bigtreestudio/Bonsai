<?php  
include(DIR_CLASES_DB."cTapasMenuTipos.db.php");

class cTapasMenuTipos extends cTapasMenuTiposdb	
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


// Trae las tapas

// Parámetros de Entrada:
//	datos: Array asociativo de datos
//		menutipocte: Tipo de cte del menú
//

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	public function BuscarxCte($datos,&$resultado,&$numfilas)
	{
		if (!parent::BuscarxCte ($datos,$resultado,$numfilas))
			return false;
		return true;			
	}


//----------------------------------------------------------------------------
// Trae las tapas

// Parámetros de Entrada:
//	datos: Array asociativo de datos
//		menutipocod: Codigo del Tipo del menú
//

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	public function BuscarxCodigo($datos,&$resultado,&$numfilas)
	{
		if (!parent::BuscarxCodigo ($datos,$resultado,$numfilas))
			return false;
		return true;			
	}

//-------------------------------------------------------------------
// Trae las tipos de menu

// Parámetros de Entrada:
//	datos: Array asociativo de datos
//

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	public function Buscar($datos,&$resultado,&$numfilas)
	{
		if (!parent::Buscar ($datos,$resultado,$numfilas))
			return false;
		return true;			
	}


	public function BusquedaAvanzada($datos,&$resultado,&$numfilas)
	{
		$sparam=array(
			'orderby'=> "menutipodesc ASC",
			'limit'=> ""
			);
			
			if (isset ($datos['orderby']) && $datos['orderby']!="")
			$sparam['orderby']= $datos['orderby'];
		if (isset ($datos['limit']) && $datos['limit']!="")
			$sparam['limit']= $datos['limit'];

		if (!parent::BusquedaAvanzada ($sparam,$resultado,$numfilas))
			return false;
		return true;			
	}	

// -----------------------------------------------------------------------------------	
// Parámetros de Entrada:
//	datos: Array asociativo de datos
//			menutipodesc: descripción del tipo de menu
//			menutipocte: descripción de la constante del tipo de menu
//          menutipoarchivo: descripción del archivo del tipo de menu
//          menuclass: descripción de la class del tipo de menu

//
// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	public function BusquedaAvanzadaTipos($datos,&$resultado,&$numfilas)
	{
		$sparam=array(
			'xmenutipodesc'=> 0,
			'menutipodesc'=> "",
			'xmenutipocte'=> 0,
			'menutipocte'=> "",
			'xmenuclass'=> 0,
			'menuclass'=> "",
			'xmenutipoarchivo'=> 0,
			'menutipoarchivo'=> "",
			'orderby'=> "menutipodesc ASC",
			'limit'=> ""
			);
		if (isset ($datos['menutipodesc']) && $datos['menutipodesc']!="")
		{
			$sparam['menutipodesc']= $datos['menutipodesc'];
			$sparam['xmenutipodesc']= 1;
		}
		if (isset ($datos['menutipocte']) && $datos['menutipocte']!="")
		{
			$sparam['menutipocte']= $datos['menutipocte'];
			$sparam['xmenutipocte']= 1;
		}
		if (isset ($datos['menuclass']) && $datos['menuclass']!="")
		{
			$sparam['menuclass']= $datos['menuclass'];
			$sparam['xmenuclass']= 1;
		}
		if (isset ($datos['menutipoarchivo']) && $datos['menutipoarchivo']!="")
		{
			$sparam['menutipoarchivo']= $datos['menutipoarchivo'];
			$sparam['xmenutipoarchivo']= 1;
		}
			
		if (isset ($datos['orderby']) && $datos['orderby']!="")
			$sparam['orderby']= $datos['orderby'];
		if (isset ($datos['limit']) && $datos['limit']!="")
			$sparam['limit']= $datos['limit'];

		if (!parent::BusquedaAvanzadaTipos ($sparam,$resultado,$numfilas))
			return false;
		return true;			
	}


//----------------------------------------------------------------------------------------- 
// Inserta nuevo formato

// Parámetros de Entrada:
//			menutipodesc: descripción del tipo de menu
//			menutipocte: descripción de la constante del tipo de menu
//          menutipoarchivo: descripción del archivo del tipo de menu
//          menuclass: descripción de la class del tipo de menu
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
// Modifica los datos de un formato

// Parámetros de Entrada:
//			menutipodesc: descripción del tipo de menu
//			menutipocte: descripción de la constante del tipo de menu
//          menutipoarchivo: descripción del archivo del tipo de menu
//          menuclass: descripción de la class del tipo de menu
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

// Eliminar un formato multimedia
// Parámetros de Entrada:
//		datos: arreglo de datos
//			menutipocod = codigo del tipo de menu

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
//			menutipodesc: descripción del tipo de menu
//			menutipocte: descripción de la constante del tipo de menu
//          menutipoarchivo: descripción del archivo del tipo de menu
//          menuclass: descripción de la class del tipo de menu

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function _ValidarDatosVacios($datos)
	{
		
		if (isset($datos['menutipodesc']) && $datos['menutipodesc']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar una descripción.  ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		if (!isset ($datos['menutipocte']) || ($datos['menutipocte']==""))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar una constante.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		else
		{
			if (!FuncionesPHPLocal::ValidarContenido($this->conexion,$datos['menutipocte'],"AlfanumericoPuro"))
			{
				FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRSOSP,"Error debe ingresar alfanúmericos puros. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
				return false;
			}
		}
		
		if (isset($datos['menuanchoautomatico']) && $datos['menuanchoautomatico']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar una ancho automatico.  ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		else
		{
			if (!FuncionesPHPLocal::ValidarContenido($this->conexion,$datos['menuanchoautomatico'],"NumericoEntero"))
			{
				FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRSOSP,"Error debe ingresar un número entero para el ancho. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
				return false;
			}
			else
			{
				if($datos['menuanchoautomatico']<0 || $datos['menuanchoautomatico']> 100)
				{
					FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRSOSP,"Error debe ingresar un munero entero ente 0 y 100 para el ancho. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
					return false;

				}
			}
		}
		if (isset($datos['menutipoarchivo']) && $datos['menutipoarchivo']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar un nombre de archivo.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		if (isset($datos['menuclass']) && $datos['menuclass']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar un nombre de la class.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	}

//----------------------------------------------------------------------------------------- 
// Retorna true o false al dar de alta si algunos de los campos esta vacio o si exite otro album con ese nombre

// Parámetros de Entrada:
//			menutipodesc: descripción del tipo de menu
//			menutipocte: descripción de la constante del tipo de menu
//          menutipoarchivo: descripción del archivo del tipo de menu
//          menuclass: descripción de la class del tipo de menu

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no
	function _ValidarDatosAlta($datos)
	{
		if (!$this->_ValidarDatosVacios($datos))
			return false;

		return true;
	}

//----------------------------------------------------------------------------------------- 
// Retorna true o false al modificar si algunos de los campos esta vacio o si exite otro album con ese nombre

// Parámetros de Entrada:
//          menutipocod: codigo del tipo de menu
//			menutipodesc: descripción del tipo de menu
//			menutipocte: descripción de la constante del tipo de menu
//          menutipoarchivo: descripción del archivo del tipo de menu
//          menuclass: descripción de la class del tipo de menu

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
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, menú inexistente.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;
		}
		return true;
	}

	
//----------------------------------------------------------------------------------------- 
// Retorna true o false al validar el activar o desactivar el formato

// Parámetros de Entrada:
//          menutipocod: codigo del tipo de menu
// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no	

	private function _ValidarEliminarDatos($datos)
	{
		
		if (!$this->BuscarxCodigo($datos,$resultado,$numfilas))
			return false;
	
		if ($numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, menú inexistente.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;
		}
			
		return true;
	}	

//----------------------------------------------------------------------------------------- 
	
}//FIN CLASE

?>