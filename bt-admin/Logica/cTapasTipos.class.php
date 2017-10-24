<?php  
include(DIR_CLASES_DB."cTapasTipos.db.php");

class cTapasTipos extends cTapasTiposdb	
{
	protected $conexion;
	protected $formato;
	private $prefijo_archivo_portada = "portada_";
	private $extension_archivos = ".html";
	private $home = "index";
	
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



// Parámetros de Entrada:
//	Sin parametros de entrada

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	public function TapasTiposSP(&$resultado,&$numfilas)
	{
		if (!parent::TapasTiposSP ($resultado,$numfilas))
			return false;
		return true;			
	}


// Trae el tipo de tapa por codigo

// Parámetros de Entrada:
//	tapatipocod = Codigo del tipo de tapa

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	public function BuscarxCodigo($datos,&$resultado,&$numfilas)
	{
		if (!parent::BuscarxCodigo ($datos,$resultado,$numfilas))
			return false;
		return true;			
	}
	
// Trae el tipo de tapa por codigo

// Parámetros de Entrada:
//	tapatipocod = Codigo del tipo de tapa

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	public function BuscarxCodigoDeTapaPublicada($datos,&$resultado,&$numfilas)
	{
		if (!parent::BuscarxCodigoDeTapaPublicada ($datos,$resultado,$numfilas))
			return false;
		return true;			
	}
		



// Trae los tipos de tapas por filtros

// Parámetros de Entrada:
//		datos: arreglo de datos
//			tapatipodesc = descripcion (la busqueda se realiza con un like)
//			tapatipoestado = Estado del tipo de tapa
//			orderby = orden de los resultados, (opcional, por default se ordena por el campo tapatipocod)
//			limit = limite de la consulta (opcional)

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	public function BusquedaAvanzada($datos,&$resultado,&$numfilas)
	{
		$sparam=array(
			'xtapatipodesc'=> 0,
			'tapatipodesc'=> "",
			'xtapatipoestado'=> 0,
			'tapatipoestado'=> "",
			'orderby'=> "tapatipocod DESC",
			'limit'=> ""
		);	
		
		if (isset ($datos['tapatipodesc']) && $datos['tapatipodesc']!="")
		{
			$sparam['tapatipodesc']= $datos['tapatipodesc'];
			$sparam['xtapatipodesc']= 1;
		}	
		if (isset ($datos['tapatipoestado']) && $datos['tapatipoestado']!="")
		{
			$sparam['tapatipoestado']= $datos['tapatipoestado'];
			$sparam['xtapatipoestado']= 1;
		}		

		if (isset ($datos['orderby']) && $datos['orderby']!="")
			$sparam['orderby']= $datos['orderby'];
			
		if (isset ($datos['limit']) && $datos['limit']!="")
			$sparam['limit']= $datos['limit'];
	
		if (!parent::BusquedaAvanzada ($sparam,$resultado,$numfilas))
			return false;
		return true;			
	}





// Elimina un tipo de tapa por codigo

// Parámetros de Entrada:
//	tapatipocod = Codigo del tipo de tapa

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	
	public function Eliminar($datos)
	{
		if (!$this->_ValidarEliminar($datos))
			return false;

		if (!parent::Eliminar($datos))
			return false;
		
		return true;
	}



// Modifica un tipo de tapa

// Parámetros de Entrada:
//	tapatipocod = Codigo del tipo de tapa

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function Modificar($datos)
	{	
		if (!$this->_ValidarModificar($datos))
			return false;
			
		if ($datos['tapatipohome']==0)
			$datos['tapatipoarchivo'] = $this->prefijo_archivo_portada.$datos['tapatipocod'].$this->extension_archivos;
		else
		{	
			$datos['tapatipoarchivo'] = $this->home.$this->extension_archivos;
			$datos['tapatipourlfriendly'] = "";
		}
			
		$datos['tapacodpublicada'] = "NULL";
		if ($datos['menutipocod']=="")
			$datos['menutipocod'] = "NULL";
		if ($datos['menucod']=="")
			$datos['menucod'] = "NULL";
		if ($datos['fondocod']=="")
			$datos['fondocod'] = "NULL";

		if(!parent::Modificar($datos))
			return false;

		
		return true;
	} 

// Actualiza la tapa que se encuentra publicada.

	public function ModificarTapaPublicada($datos)
	{	

		if(!parent::ModificarTapaPublicada($datos))
			return false;
			
			

		return true;
	} 


// Inserta un tipo de tapa

// Parámetros de Entrada:
	//	datos = Arreglo asociativo de:
		//tapatipodesc: Descripcion del tipo de tapa
		//tapatipoarchivo: nombre del archivo del tipo de tapa
		//tapatipourlfriendly: Url friendly del tipo de tapa
// Retorna:
//		tapatipocod: Codigo del tipo de tapa insertado
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function Insertar($datos,&$tapatipocod)
	{	

		if (!$this->_ValidarInsertar($datos))
			return false;
			
		$datos['tapatipoarchivo'] = "";
		$datos['tapatipoestado'] = ACTIVO;	
		$datos['tapacodpublicada'] = "NULL";
		if ($datos['menutipocod']=="")
			$datos['menutipocod'] = "NULL";
		if ($datos['menucod']=="")
			$datos['menucod'] = "NULL";
		if ($datos['fondocod']=="")
			$datos['fondocod'] = "NULL";
		if(!parent::Insertar($datos,$tapatipocod))
			return false;

		if ($datos['tapatipohome']==0)
			$datos['tapatipoarchivo'] = $this->prefijo_archivo_portada.$tapatipocod.$this->extension_archivos;
		else
		{	
			$datos['tapatipoarchivo'] = $this->home.$this->extension_archivos;
			$datos['tapatipourlfriendly'] = "";
		}
		
		$datos['tapatipocod'] = $tapatipocod;
		if(!parent::ModificarArchivo($datos))
			return false;
		
		return true;
	} 
	


// Modifica el estado de un tipo de tapa

// Parámetros de Entrada:
	//	tapatipocod = Codigo del tipo de tapa
	//	tapatipoestado = Estado del tipo de tapa

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	public function ModificarEstado($datos)
	{
		if (!parent::ModificarEstado($datos))
			return false;
			
		return true;	
	}



// Modifica el estado a no activo de un tipo de tapa

// Parámetros de Entrada:
	//	tapatipocod = Codigo del tipo de tapa

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function DesActivar($datos)
	{
		
		$datosmodificar['tapatipocod'] = $datos['tapatipocod'];
		$datosmodificar['tapatipoestado'] = NOACTIVO;
		if (!$this->ModificarEstado($datosmodificar))
			return false;
		
		return true;
	}
	
	
// Modifica el estado a activo de un tipo de tapa

// Parámetros de Entrada:
	//	tapatipocod = Codigo del tipo de tapa

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function Activar($datos)
	{
		
		$datosmodificar['tapatipocod'] = $datos['tapatipocod'];
		$datosmodificar['tapatipoestado'] = ACTIVO;
		if (!$this->ModificarEstado($datosmodificar))
			return false;
		
		return true;
	}
	




// Valida los datos obligatorios

// Parámetros de Entrada:
	//tapatipodesc: Descripcion del tipo de tapa
	//tapatipoarchivo: nombre del archivo del tipo de tapa
	//tapatipourlfriendly: Url friendly del tipo de tapa

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	private function _ValidarDatosVacios($datos)
	{
		
		if ($datos['tapatipodesc']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar una descripcion. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;
		}

		
		if ($datos['tapatipohome']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar si es o no portada. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;
		}
		if ($datos['tapatipohome']!=0 && $datos['tapatipohome']!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar si es o no portada. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;
		}	
		
/*		if ($datos['fondocod']!="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar un fondo. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;
		}			
*/		
		if ($datos['tapatipohome']==1)
		{
			if (!parent::BuscarxTipoHome($datos,$resultado,$numfilas))
				return false;
			
			if ($numfilas>0)
			{
				$datosportada = $this->conexion->ObtenerSiguienteRegistro($resultado);
				if ($datosportada['tapatipocod']!=$datos['tapatipocod'])
				{
					FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, ya existe una portada home. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
					return false;
				}
			}
		}else
		{
			if ($datos['tapatipourlfriendly']=="")
			{
				FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar un tipo de url friendly. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
				return false;
			}
			if (!FuncionesPHPLocal::ValidarContenido($this->conexion,$datos['tapatipourlfriendly'],"URL"))
			{
				FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar un tipo de url valida. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
				return false;
			}
			
		}
		
		return true;
	}
	
	
	
// Valida los datos de alta de un tipo de tapa

// Parámetros de Entrada:
	//tapatipodesc: Descripcion del tipo de tapa
	//tapatipoarchivo: nombre del archivo del tipo de tapa
	//tapatipourlfriendly: Url friendly del tipo de tapa

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	
	private function _ValidarInsertar($datos)
	{

		if (!$this->_ValidarDatosVacios($datos))
			return false;

		return true;
	}



// Valida los datos al modificar un tipo de tapa

// Parámetros de Entrada:
	//tapatipocod: Codigo del tipo de tapa
	//tapatipodesc: Descripcion del tipo de tapa
	//tapatipoarchivo: nombre del archivo del tipo de tapa
	//tapatipourlfriendly: Url friendly del tipo de tapa

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	private function _ValidarModificar($datos)
	{
		if (!$this->_ValidarDatosVacios($datos))
			return false;
		
		if (!$this->BuscarxCodigo($datos,$resultado,$numfilas))
			return false;
		
		if ($numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar un tipo de tapa valido. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;	
		}	

		return true;
	}
	



// Valida los datos al eliminar un tipo de tapa
	//Verifique que no existan tapas asociadas

// Parámetros de Entrada:
	//tapatipocod: Codigo del tipo de tapa

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	private function _ValidarEliminar($datos)
	{
		if (!$this->BuscarxCodigo($datos,$resultado,$numfilas))
			return false;
		
		if ($numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar un tipo de tapa valido. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;	
		}	

		$oTapas = new cTapas($this->conexion,$this->formato);
		$datosbusqueda['tapatipocod'] = $datos['tapatipocod'];
		if(!$oTapas->BusquedaAvanzada($datosbusqueda,$resultado,$numfilas))
			return false;
			
		if ($numfilas!=0)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, el tipo de tapa tiene portadas relacionadas. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;	
		}	
			
		return true;
	}
	



}//FIN CLASS
?>