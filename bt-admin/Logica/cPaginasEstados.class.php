<?php 
/*
CLASE LOGICA PARA EL MANEJO DE LAS ACCIONES DE LAS NOTICIAS.
*/
include(DIR_CLASES_DB."cPaginasEstados.db.php");

class cPaginasEstados extends cPaginasEstadosdb	
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
	public function PaginasEstadosSP(&$spnombre,&$spparam)
	{
		$spnombre="sel_pag_paginas_estados_xorden";
		$spparam=array("porderby"=>"pagestadodesc");
		
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

	public function ObtenerEstadosCantidades($datos,&$resultado,&$numfilas)
	{
		if (!parent::ObtenerEstadosCantidades($datos,$resultado,$numfilas))
			return false;
		
		return true;
	}


//----------------------------------------------------------------------------------------- 
// Parámetros de Entrada:
//		datos: arreglo de datos
//			pagestadocod = codigo de la pagina estado

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function BuscarxCodigo($datos,&$resultado,&$numfilas)
	{
		if (!parent::BuscarxCodigo ($datos,$resultado,$numfilas))
			return false;
		return true;	
	}
	

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
	function BusquedaAvanzada ($datos,&$resultado,&$numfilas)
	{
		$sparam=array(
			'xpagestadodesc'=> 0,
			'pagestadodesc'=> "",
			'xpagestadocte'=>0,
			'pagestadocte'=> "",
			'xpagestadomuestracantidad'=> 0,
			'pagestadomuestracantidad'=>"",
			'xpagestadosemuestra'=> 0,
			'pagestadosemuestra'=> "",
			'orderby'=> "pagestadocod ASC",
			'limit'=> ""
			);

		if (isset ($datos['pagestadodesc']) && $datos['pagestadodesc']!="")
		{
			$sparam['pagestadodesc']= $datos['pagestadodesc'];
			$sparam['xpagestadodesc']= 1;
		}	
		if (isset ($datos['pagestadocte']) && $datos['pagestadocte']!="")
		{
			$sparam['pagestadocte']= $datos['pagestadocte'];
			$sparam['xpagestadocte']= 1;
		}
		
		if (isset ($datos['pagestadomuestracantidad']) && $datos['pagestadomuestracantidad']!="")
		{
			$sparam['pagestadomuestracantidad']= $datos['pagestadomuestracantidad'];
			$sparam['xpagestadomuestracantidad']= 1;
		}

		if (isset ($datos['pagestadosemuestra']) && $datos['pagestadosemuestra']!="")
		{
			$sparam['pagestadosemuestra']= $datos['pagestadosemuestra'];
			$sparam['xpagestadosemuestra']= 1;
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
// Inserta nueva pagina estado

// Parámetros de Entrada:
//			pagestadocod = codigo de la pagina estado
//			pagestadodesc: descripción del estado de la pagina estado
//			pagestadocte: descripción de la contante de la pagina estado
//			pagestadomuestracantidad: codigo si muestra cantidad de la pagina estado 
//          pagestadosemuestra: codigo si muestra de la pagina estado 
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
// Modifica los datos de una pagina estado

// Parámetros de Entrada:
//			pagestadocod = codigo de la pagina estado
//			pagestadodesc: descripción del estado de la pagina estado
//			pagestadocte: descripción de la contante de la pagina estado
//			pagestadomuestracantidad: codigo si muestra cantidad de la pagina estado 
//          pagestadosemuestra: codigo si muestra de la pagina estado 
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

// Eliminar una pagina estado
// Parámetros de Entrada:
//		datos: arreglo de datos
// Parámetros de Entrada:
//			pagestadocod = codigo de la pagina estado

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
//			pagestadocod = codigo de la pagina estado
//			pagestadodesc: descripción del estado de la pagina estado
//			pagestadocte: descripción de la contante de la pagina estado
//			pagestadomuestracantidad: codigo si muestra cantidad de la pagina estado 
//          pagestadosemuestra: codigo si muestra de la pagina estado 
// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function _ValidarDatosVacios($datos)
	{
		if (isset($datos['pagestadocod']) && $datos['pagestadocod']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar un código.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}else
		{
			if ($datos['pagestadocod']<0  || $datos['pagestadocod']>99)
			{
				FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar un código ente 0 y 99.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
				return false;
			}
		}
		if (isset($datos['pagestadodesc']) && $datos['pagestadodesc']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar una descripción.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		if (isset($datos['pagestadocte']) && $datos['pagestadocte']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar una constante.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		if (!isset ($datos['pagestadomuestracantidad']) || ($datos['pagestadomuestracantidad']==""))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar si muestra cantidad.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		else
		{
			if($datos['pagestadomuestracantidad']!= 1 && $datos['pagestadomuestracantidad']!= 0)
			{
				FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, código de se muestra cantidad erroneo.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
				return false;
			}
		
		}
		
		if (!isset ($datos['pagestadosemuestra']) || ($datos['pagestadosemuestra']==""))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar si muestra.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		else
		{
			if($datos['pagestadosemuestra']!= 0 && $datos['pagestadosemuestra']!= 1)
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
//			pagestadocod = codigo de la pagina estado
//			pagestadodesc: descripción del estado de la pagina estado
//			pagestadocte: descripción de la contante de la pagina estado
//			pagestadomuestracantidad: codigo si muestra cantidad de la pagina estado 
//          pagestadosemuestra: codigo si muestra de la pagina estado 
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
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, ya existe una página estado con ese codigo.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;
		}

		return true;
	}

//----------------------------------------------------------------------------------------- 
// Retorna true o false al modificar si algunos de los campos esta vacio 

// Parámetros de Entrada:
//			pagestadocod = codigo de la pagina estado
//			pagestadodesc: descripción del estado de la pagina estado
//			pagestadocte: descripción de la contante de la pagina estado
//			pagestadomuestracantidad: codigo si muestra cantidad de la pagina estado 
//          pagestadosemuestra: codigo si muestra de la pagina estado 
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
//			pagestadocod = codigo de la pagina estado
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