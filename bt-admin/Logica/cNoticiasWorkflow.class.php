<?php 
/*
CLASE LOGICA PARA EL MANEJO DE LAS ACCIONES DE LAS NOTICIAS.
*/
include(DIR_CLASES_DB."cNoticiasWorkflow.db.php");

class cNoticiasWorkflow extends cNoticiasWorkflowdb	
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


//----------------------------------------------------------------------------------------- 
// Retorna las acciones de un rol en un estado de la noticia

// Parámetros de Entrada:
//		datos: arreglo de datos
//			noticiaestadocod = estado de la noticia

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function ObtenerAccionesEstadoInicial($datos,&$resultado,&$numfilas)
	{
		$datos['noticiestadocodinicial'] = $datos['noticiaestadocod'];
		if (!parent::ObtenerAccionesEstadoInicial($datos,$resultado,$numfilas))
			return false;
		
		return true;
	}

//----------------------------------------------------------------------------------------- 
// Retorna en un arreglo con los datos de una noticia workflow
// Parámetros de Entrada:
//		datos: arreglo de datos
//			noticiaworkflowcod = codigo de la visualizacion
// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function BuscarNoticiaWorkflowxCodigo($datos,&$resultado,&$numfilas)
	{
		if (!parent::BuscarNoticiaWorkflowxCodigo ($datos,$resultado,$numfilas))
			return false;
		return true;	
	}
	

//----------------------------------------------------------------------------------------- 
// Retorna en un arreglo con los datos de una noticia workflow
// Parámetros de Entrada:
//		datos: arreglo de datos
//			noticiaestadocodinicial: descripción del estado inicial de la noticia workflow
//			noticiaestadocodfinal: descripción del estado final de la noticia workflow
// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function BuscaNoticiasxNoticiaestadocodinicial_Noticiaestadocodfinal($datos,&$resultado,&$numfilas)
	{
		if (!parent::BuscaNoticiasxNoticiaestadocodinicial_Noticiaestadocodfinal ($datos,$resultado,$numfilas))
			return false;
		return true;	
	}
	
	
	
//------------------------------------------------------------------------------------------	
// Retorna en un arreglo con los datos de una noticia workflow

// Parámetros de Entrada:
//			$datos = array asociativos
//			noticiaestadocodinicial: descripción del estado inicial de la noticia workflow
//			noticiaestadocodfinal: descripción del estado final de la noticia workflow
//			noticiaaccion: descripcion de la accion
// Retorna:
//		resultado= Arreglo con todos los datos de un formato multimedia.
//		numfilas= cantidad de filas 
//		la función retorna true o false si se pudo ejecutar con éxito o no	
	function BusquedaAvanzada ($datos,&$resultado,&$numfilas)
	{
		$sparam=array(
			'xnoticiaestadocodinicial'=> 0,
			'noticiaestadocodinicial'=> "",
			'xnoticiaestadocodfinal'=> 0,
			'noticiaestadocodfinal'=> "",
			'xnoticiaaccion'=> 0,
			'noticiaaccion'=> "",
			'orderby'=> "noticiaworkflowcod ASC",
			'limit'=> ""
			);	

			
		if (isset ($datos['noticiaestadocodinicial']) && $datos['noticiaestadocodinicial']!="")
		{
			$sparam['noticiaestadocodinicial']= $datos['noticiaestadocodinicial'];
			$sparam['xnoticiaestadocodinicial']= 1;
		}	
		if (isset ($datos['noticiaestadocodfinal']) && $datos['noticiaestadocodfinal']!="")
		{
			$sparam['noticiaestadocodfinal']= $datos['noticiaestadocodfinal'];
			$sparam['xnoticiaestadocodfinal']= 1;
		}
		
		if (isset ($datos['noticiaaccion']) && $datos['noticiaaccion']!="")
		{
			$sparam['noticiaaccion']= $datos['noticiaaccion'];
			$sparam['xnoticiaaccion']= 1;
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
// Inserta nueva noticia workflow

// Parámetros de Entrada:
//			noticiaestadocodinicial: descripción del estado inicial de la noticia workflow
//			noticiaestadocodfinal: descripción del estado final de la noticia workflow
//			noticiaaccion: descripcion de la accion
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
// Modifica los datos de una noticia workflow

// Parámetros de Entrada:
//			noticiaworkflowcod = codigo de la visualizacion
//			noticiaestadocodinicial: descripción del estado inicial de la noticia workflow
//			noticiaestadocodfinal: descripción del estado final de la noticia workflow
//			noticiaaccion: descripcion de la accion
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

// Eliminar una noticia workflow
// Parámetros de Entrada:
//		datos: arreglo de datos
//			noticiaworkflowcod = codigo de la visualizacion

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
//			noticiaestadocodinicial: descripción del estado inicial de la noticia workflow
//			noticiaestadocodfinal: descripción del estado final de la noticia workflow
//			noticiaaccion: descripcion de la accion
// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function _ValidarDatosVacios($datos)
	{
		if (isset($datos['noticiaestadocodinicial']) && $datos['noticiaestadocodinicial']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar un estado inicial.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		else
		{

				$oNoticiasEstados=new cNoticiasEstados($this->conexion,$this->formato);
				$datosbuscar['noticiaestadocod'] = $datos['noticiaestadocodinicial'];
				if(!$oNoticiasEstados->BuscarxCodigo($datosbuscar,$resultado,$numfilas))
					return false;
				if ($numfilas!=1)
				{
					FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, Código de estado erroneo.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
					return false;
				}
		}
		if (isset($datos['noticiaestadocodfinal']) && $datos['noticiaestadocodfinal']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar un estado final.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		else
		{
				$oNoticiasEstados=new cNoticiasEstados($this->conexion,$this->formato);
				$datosbuscar['noticiaestadocod'] = $datos['noticiaestadocodfinal'];
				if(!$oNoticiasEstados->BuscarxCodigo($datosbuscar,$resultado,$numfilas))
					return false;
				if ($numfilas!=1)
				{
					FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, Código de estado erroneo.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
					return false;
				}
		}

		if (!isset ($datos['noticiaaccion']) || ($datos['noticiaaccion']==""))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar una descripción de la acción.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	}

//----------------------------------------------------------------------------------------- 
// Retorna true o false al dar de alta si algunos de los campos esta vacio o si exite otro album con ese nombre

// Parámetros de Entrada:
//			noticiaestadocodinicial: descripción del estado inicial de la noticia workflow
//			noticiaestadocodfinal: descripción del estado final de la noticia workflow
//			noticiaaccion: descripcion de la accion
// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no
	function _ValidarDatosAlta($datos)
	{
		if (!$this->_ValidarDatosVacios($datos))
			return false;
		
		if (!$this->BuscaNoticiasxNoticiaestadocodinicial_Noticiaestadocodfinal($datos,$resultado,$numfilas))
			return false;	
		
		if ($numfilas!=0)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, ya existe otro con ese estado inicial y final.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		return true;
	}

//----------------------------------------------------------------------------------------- 
// Retorna true o false al modificar si algunos de los campos esta vacio o si exite otro album con ese nombre

// Parámetros de Entrada:
//			noticiaworkflowcod = codigo de la visualizacion
//			noticiaestadocodinicial: descripción del estado inicial de la noticia workflow
//			noticiaestadocodfinal: descripción del estado final de la noticia workflow
//			noticiaaccion: descripcion de la accion
// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function _ValidarDatosModificar($datos)
	{
		if (!$this->_ValidarDatosVacios($datos))
			return false;
	

		if (!$this->BuscarNoticiaWorkflowxCodigo($datos,$resultado,$numfilas))
			return false;
		if ($numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, noticia workflow inexistente.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;
		}
		
		if (!$this->BuscaNoticiasxNoticiaestadocodinicial_Noticiaestadocodfinal($datos,$resultado,$numfilas))
			return false;	
		
		if ($numfilas!=0)
		{
			$fila = $this->conexion->ObtenerSiguienteRegistro($resultado);
			if($fila['noticiaworkflowcod'] != $datos['noticiaworkflowcod'])
			{
				FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, ya existe otra noticia workflow con ese estado inicial y final.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
				return false;
			}
		}

		return true;
	}
//----------------------------------------------------------------------------------------- 
// Retorna true o false al validar el activar o desactivar el formato

// Parámetros de Entrada:
//		    visualizacioncod: código de la visualizacion
// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no	

	private function _ValidarEliminarDatos($datos)
	{
		
		if (!$this->BuscarNoticiaWorkflowxCodigo($datos,$resultado,$numfilas))
			return false;
	
		if ($numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, noticia workflow inexistente.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;
		}
			
		return true;
	}	

//----------------------------------------------------------------------------------------- 
	
}//FIN CLASE

?>