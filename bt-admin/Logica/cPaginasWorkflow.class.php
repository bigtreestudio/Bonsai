<?php 
/*
CLASE LOGICA PARA EL MANEJO DE LAS ACCIONES DE LAS NOTICIAS.
*/
include(DIR_CLASES_DB."cPaginasWorkflow.db.php");

class cPaginasWorkflow extends cPaginasWorkflowdb	
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
// Retorna las acciones de un rol en un estado de la pagina

// Parámetros de Entrada:
//		datos: arreglo de datos
//			pagestadocod = estado de la pagina

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function ObtenerAccionesEstadoInicial($datos,&$resultado,&$numfilas)
	{
		$datos['paginaestadocodinicial'] = $datos['pagestadocod'];
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

	public function BuscarPaginaWorkflowxCodigo($datos,&$resultado,&$numfilas)
	{
		if (!parent::BuscarPaginaWorkflowxCodigo ($datos,$resultado,$numfilas))
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

	public function BuscaPaginaxPaginaestadocodinicial_Paginaestadocodfinal($datos,&$resultado,&$numfilas)
	{
		if (!parent::BuscaPaginaxPaginaestadocodinicial_Paginaestadocodfinal ($datos,$resultado,$numfilas))
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
			'xpaginaestadocodinicial'=> 0,
			'paginaestadocodinicial'=> "",
			'xpaginaestadocodfinal'=>  0,
			'paginaestadocodfinal'=> "",
			'xpaginaaccion'=>  0,
			'paginaaccion'=> "",
			'orderby'=> "paginaworkflowcod ASC",
			'limit'=> ""
			);
		if (isset ($datos['paginaestadocodinicial']) && $datos['paginaestadocodinicial']!="")
		{
			$sparam['paginaestadocodinicial']= $datos['paginaestadocodinicial'];
			$sparam['xpaginaestadocodinicial']= 1;
		}	
		if (isset ($datos['paginaestadocodfinal']) && $datos['paginaestadocodfinal']!="")
		{
			$sparam['paginaestadocodfinal']= $datos['paginaestadocodfinal'];
			$sparam['xpaginaestadocodfinal']= 1;
		}
		
		if (isset ($datos['paginaaccion']) && $datos['paginaaccion']!="")
		{
			$sparam['paginaaccion']= $datos['paginaaccion'];
			$sparam['xpaginaaccion']= 1;
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
		if (isset($datos['paginaestadocodinicial']) && $datos['paginaestadocodinicial']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar un estado inicial.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		else
		{

				$oPaginasEstados=new cPaginasEstados($this->conexion,$this->formato);
				$datosbuscar['pagestadocod'] = $datos['paginaestadocodinicial'];
				if(!$oPaginasEstados->BuscarxCodigo($datosbuscar,$resultado,$numfilas))
					return false;
				if ($numfilas!=1)
				{
					FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, código de estado erroneo.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
					return false;
				}
		}
		if (isset($datos['paginaestadocodfinal']) && $datos['paginaestadocodfinal']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar un estado final.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		else
		{
				$oPaginasEstados=new cPaginasEstados($this->conexion,$this->formato);
				$datosbuscar['pagestadocod'] = $datos['paginaestadocodfinal'];
				if(!$oPaginasEstados->BuscarxCodigo($datosbuscar,$resultado,$numfilas))
					return false;
				if ($numfilas!=1)
				{
					FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, Código de estado erroneo.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
					return false;
				}
		}

		if (!isset ($datos['paginaaccion']) || ($datos['paginaaccion']==""))
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
		
		if (!$this->BuscaPaginaxPaginaestadocodinicial_Paginaestadocodfinal($datos,$resultado,$numfilas))
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
	

		if (!$this->BuscarPaginaWorkflowxCodigo($datos,$resultado,$numfilas))
			return false;
		if ($numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, noticia workflow inexistente.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;
		}
		
		if (!$this->BuscaPaginaxPaginaestadocodinicial_Paginaestadocodfinal($datos,$resultado,$numfilas))
			return false;	
		
		if ($numfilas!=0)
		{
			$fila = $this->conexion->ObtenerSiguienteRegistro($resultado);
			if($fila['paginaworkflowcod'] != $datos['paginaworkflowcod'])
			{
				FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, ya existe otra página workflow con ese estado inicial y final.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
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
		
		if (!$this->BuscarPaginaWorkflowxCodigo($datos,$resultado,$numfilas))
			return false;
	
		if ($numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, página workflow inexistente.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;
		}
			
		return true;
	}	

//----------------------------------------------------------------------------------------- 
	
}//FIN CLASE

?>