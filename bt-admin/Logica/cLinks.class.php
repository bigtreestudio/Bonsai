<?php  
include(DIR_CLASES_DB."cLinks.db.php");

class cLinks extends cLinksdb	
{
	protected $conexion;
	protected $formato;
	protected $activos;

	
// CLASE cLinks
// EN CASO DE QUE LA VARIABLE ACTIVOS SE ENCUENTRE EN LA LLAMADA A LA CLASE,
// LAS FUNCIONES SE TRABAJARAN CON SOLO  LOS DATOS DE LOS CATEGORIAS QUE SE ENCUENTREN
// EN ESTADO ACTIVO, O PENDIENTES DE MODIFICACION.

//-----------------------------------------------------------------------------------------

//-----------------------------------------------------------------------------------------

	// Constructor de la clase
	function __construct($conexion,$activos=true,$formato=FMT_TEXTO){
		$this->conexion = &$conexion;
		$this->activos = $activos;
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
//// Retorna si existen link en esa categoria

// Parámetros de Entrada:
//		catcod: catetoria a buscar

	public function BuscarLinkxCategoria($datos,&$resultado,&$numfilas)
	{	
	
		if (!parent::BuscarLinkxCategoria($datos,$resultado,$numfilas))
			return false;
		
		return true;
	} 
	
	
	//// Retorna si existen link en esa categoria

// Parámetros de Entrada:
//		
// LISTA TODAS LAS CATEGORIAS

	public function BuscarListadoxCategoria($datos,&$resultado,&$numfilas)
	{	
			$sparam=array(
				'orderby'=> "catorden ASC",
				'limit'=> ""
		);	
			
						
		if (isset ($datos['orderby']) && $datos['orderby']!="")
			$sparam['orderby']= $datos['orderby'];
			
		if (isset ($datos['limit']) && $datos['limit']!="")
			$sparam['limit']= $datos['limit'];
		
		if (!parent::BuscarListadoxCategoria($sparam,$resultado,$numfilas))
			return false;
		
		return true;
	} 
//busca todos los datos de una categoria 
	
	public function BuscarCategoriaxCatcod($datos,&$resultado,&$numfilas)
	{	
		
		if (!parent::BuscarCategoriaxCatcod($datos,$resultado,$numfilas))
			return false;
		
		return true;
	} 
	
	// Parámetros de Entrada:
//		linkcod: busco todos los datos corrspondientes a ese linkcod

	public function BuscarxLinkCod($datos,&$resultado,&$numfilas)
	{	
	
			$sparam=array(
				'xlinkcod'=> 0,
				'linkcod'=> "",
				);
					
			if (isset ($datos['linkcod']) && $datos['linkcod']!="")
				{
					$sparam['linkcod']= $datos['linkcod'];
					$sparam['xlinkcod']= 1;
				}
				
		if (!parent::BuscarxLinkCod($sparam,$resultado,$numfilas))
			return false;
		
		return true;
	} 
//------------------------------------------------------------------------------------	
// Retorna en un arreglo con los datos de un categoria

// Parámetros de Entrada:
//		catcod: catetoria a buscar

// Retorna:
//		resultado= Arreglo con todos los datos de un categoria.
//		numfilas= cantidad de filas 
//		la función retorna true o false si se pudo ejecutar con éxito o no
	public function BuscarAvanzadaxLink($datos,&$resultado,&$numfilas)
	{	
		$sparam=array(
				'xcatcod'=> 0,
				'catcod'=> "",
				'orderby'=> "",
				'limit'=> ""
			);	
			
		if (isset ($datos['catcod']) && $datos['catcod']!="")
		{
			$sparam['catcod']= $datos['catcod'];
			$sparam['xcatcod']= 1;
		}
				
		if (isset ($datos['orderby']) && $datos['orderby']!="")
			$sparam['orderby']= $datos['orderby'];
			
		if (isset ($datos['limit']) && $datos['limit']!="")
			$sparam['limit']= $datos['limit'];
			
		if (!parent::BuscarAvanzadaxLink($sparam,$resultado,$numfilas))
			return false;
		
		return true;
	} 
	
//----------------------------------------------------------------------------------------- 
// Retorna proxorden. proximo orden de los links o de las categorias

// Parámetros de Entrada:
//		linkcod= codigo del link
//		catcod= codigo de la categoria

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no	

	private function ObtenerProximoOrden($datos,&$proxorden)
	{
		$proxorden = 0;
		if (!parent::BuscarLinkUltimoOrden($datos,$resultado,$numfilas))
			return false;
			
		if ($numfilas!=0)
		{
			$datos = $this->conexion->ObtenerSiguienteRegistro($resultado);
			$proxorden = $datos['maximo'] + 1;
		}
		return true;
	}


//----------------------------------------------------------------------------------------- 
//ABM DE LINKS.-
//----------------------------------------------------------------------------------------- 

//----------------------------------------------------------------------------------------- 

//----------------------------------------------------------------------------------------- 
// Modifica los datos de uN LINK

// Parámetros de Entrada: LINKCOD

	
	public function ModificarLink($datos)
	{	
		if (!$this->_ValidarModificar($datos))
			return false;
			$datos['linkarchubic']="";
			$datos['linkarchsize']="";
		
		if(!$this->Modificar($datos))
			return false;

		
		return true;
	} 
	
//----------------------------------------------------------------------------------------- 
// Inserta una LINK nuevo.

// Parámetros de Entrada: catcod

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	public function InsertarLink($datos,&$codigoinsertado)
	{
		if (!$this->_ValidarInsertar($datos))
			return false;
		
		$this->ObtenerProximoOrden($datos,$proxorden);	
			$datos['linkorden']= $proxorden;
			$datos['linkestado'] = ACTIVO;
			$datos['linkarchubic']="";
			$datos['linkarchsize']="";
		if(!$this->Insertar($datos,$codigoinsertado))
			return false;
		
			
		return true;
	} 


//----------------------------------------------------------------------------------------- 
//FIN DE ABM DE CATEGORIAS.-
//----------------------------------------------------------------------------------------- 


//-----------------------------------------------------------------------------------------
//							 PRIVADAS	
//----------------------------------------------------------------------------------------- 
// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function _ValidarDatosVacios($datos)
	{
		
		if ($datos['linktitulo']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar un titulo. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		if ($datos['linklink']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar un LINK. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		return true;
	}

//----------------------------------------------------------------------------------------- 
// Retorna true o false al dar de alta si algunos de los campos esta vacio o si exite otra categoria con ese nombre

// Parámetros de Entrada:
//		catcod valida si existe
// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no
	function _ValidarInsertar($datos)
	{
		if (!$this->_ValidarDatosVacios($datos))
			return false;

		if ($datos['catcod']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, no pertenece a una categoria. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		
		return true;
	}

//----------------------------------------------------------------------------------------- 
// Retorna true o false al modificar si alguno de los campos esta vacio.

	public function _ValidarModificar($datos)
	{
		if (!$this->_ValidarDatosVacios($datos))
			return false;

		return true;
	}
//----------------------------------------------------------------------------------------- 
// Retorna true o false si pudo eliminar el link

// Parámetros de Entrada:
//		linkcod = codigo del link a eliminar.

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function EliminarLink($datos)
	{
			
		if (!parent::EliminarLink($datos))
			return false;
			
			
		return true;
	}


//----------------------------------------------------------------------------------------- 
// Retorna true o false si pudo cambiarle el estado al link

// Parámetros de Entrada:
//		linkcod = codigo del link.

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no
	public function ModificarEstadoLink($datos)
	{
		if (!parent::ModificarEstadoLink($datos))
			return false;
			
		return true;	
	}
	

//----------------------------------------------------------------------------------------- 
// Retorna true o false si pudo cambiarle el estado a ACTIVO el link.

// Parámetros de Entrada:
//		catcod = codigo del link.
//      linkestado = nuevo estado del link

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no
	public function ActivarLink($datos)
	{
		
		$datosmodificar['linkcod'] = $datos['linkcod'];
		$datosmodificar['linkestado'] = ACTIVO;
		if (!$this->ModificarEstadoLink($datosmodificar))
			return false;
		
		return true;
	}
//----------------------------------------------------------------------------------------- 
// Retorna true o false si pudo cambiarle el estado a DESACTIVAR a el link

// Parámetros de Entrada:
//		linkcod = codigo del link.
//      linkestado = nuevo estado del link

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no	
	public function DesActivarLink($datos)
	{
		
		$datosmodificar['linkcod'] = $datos['linkcod'];
		$datosmodificar['linkestado'] = NOACTIVO;
		if (!$this->ModificarEstadoLink($datosmodificar))
			return false;
		
		return true;
	}
//----------------------------------------------------------------------------------------- 
//Retorna true o false si pudo cambiar el orden de los links

// Parámetros de Entrada:
//		linkorden = orden de los links

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no		
	public function ModificarOrden($datos)
	{
		$arreglolinks = explode(",",$datos['linkorden']);
		
		$datosmodif['linkorden'] = 1;
		
		foreach ($arreglolinks as $linkcod)
		{
			$datosmodif['linkcod'] = $linkcod;
			
			
			if (!parent::ModificarOrden($datosmodif))
					return false;
			$datosmodif['linkorden']++;
		}
		
		return true;
	}
// Retorna true o false si pudo eliminar la categoria

// Parámetros de Entrada:
//		catcod = codigo de la categoria a eliminar

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function EliminarCategoria($datos)
	{
		if(!$this->EliminarValidar($datos))
			return false;
		
		if (!parent::EliminarCategoria($datos))
				return false;
		
						
		return true;
	}
	
		public function EliminarValidar($datos)
	{	
		if(!$this->BuscarLinkxCategoria($datos,$resultado,$numfilas))
				return false;
		
		
		if ($numfilas>0)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_OK,"Error, hay links cargados a esa categoria. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>''));
			return false;
		}
		
		return true;
	}

// Retorna true o false si pudo cambiarle el estado a ACTIVO el link.

// Parámetros de Entrada:
//		catcod = codigo del link.
//      linkestado = nuevo estado del link

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no
	public function ActivarCategoria($datos)
	{
		
		$datosmodificar['catcod'] = $datos['catcod'];
		$datosmodificar['catestado'] = ACTIVO;
		if (!$this->ModificarEstadoCategoria($datosmodificar))
			return false;
		
		return true;
	}
//----------------------------------------------------------------------------------------- 
// Retorna true o false si pudo cambiarle el estado a DESACTIVAR a el link

// Parámetros de Entrada:
//		linkcod = codigo del link.
//      linkestado = nuevo estado del link

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no	
	public function DesActivarCategoria($datos)
	{
		
		$datosmodificar['catcod'] = $datos['catcod'];
		$datosmodificar['catestado'] = NOACTIVO;
		if (!$this->ModificarEstadoCategoria($datosmodificar))
			return false;
		
		return true;
	}


//Retorna true o false si pudo cambiar el orden de las categorias

// Parámetros de Entrada:
//		catorden = orden de las categorias.

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no	

	
	public function ModificarOrdenCategoria($datos)
	{
		$arregloCategorias = explode(",",$datos['catorden']);
		
		$datosmodif['catorden'] = 1;
		
		foreach ($arregloCategorias as $catcod)
		{
			$datosmodif['catcod'] = $catcod;
			
			
			if (!parent::ModificarOrdenCategoria($datosmodif))
					return false;
			$datosmodif['catorden']++;
		}
		
		return true;
	}
// Retorna true o false si pudo cambiarle el estado a la categoria

// Parámetros de Entrada:
//		catcod = codigo de la categoria.

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no
	public function ModificarEstadoCategoria($datos)
	{
		if (!parent::ModificarEstadoCategoria($datos))
			return false;
			
		return true;	
	}
	

	public function InsertarCategorias($datos,&$codigoinsertaxsdo)
	{

		if (!$this->_ValidarInsertarCategorias($datos))
			return false;
		
		$this->ObtenerProximoOrden($datos,$proxorden);	
			$datos['catorden']= $proxorden;
			$datos['catestado'] = ACTIVO;
			$datos['catsuperior']="NULL";
						

		if(!parent::InsertarCategorias($datos,$codigoinsertado))
			return false;
		
		return true;
	} 
	
	function _ValidarInsertarCategorias($datos)
	{
		if (!$this->_ValidarDatosVaciosCategorias($datos))
			return false;
				
		return true;
	}
	
	public function _ValidarDatosVaciosCategorias($datos)
	{
		if ($datos['catnom']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar un nombre. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;
		}


		return true;
	}
	
	
	// Modifica los datos de uN LINK

// Parámetros de Entrada: LINKCOD

	
	public function ModificarCategoria($datos)
	{	
		if (!$this->_ValidarModificarCategoria($datos))
			return false;
			
		if(!parent::ModificarCategoria($datos))
			return false;

		
		return true;
	} 
	
	public function _ValidarModificarCategoria($datos)
	{
		if (!$this->_ValidarDatosVaciosCategorias($datos))
			return false;
			
			$datos['catestado'] = ACTIVO;
			$datos['catsuperior']="NULL";

		return true;
	}
	
}// FIN CLASE

?>