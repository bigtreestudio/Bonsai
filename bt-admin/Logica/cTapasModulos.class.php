<?php  
include(DIR_CLASES_DB."cTapasModulos.db.php");

class cTapasModulos extends cTapasModulosdb	
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



// Trae los datos de los modulos por codigo

// Parámetros de Entrada:
//		datos: arreglo de datos
//			tapacod = codigo de la tapa

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	public function BuscarModulosxTapa($datos,&$resultado,&$numfilas)
	{
		if (!parent::BuscarModulosxTapa ($datos,$resultado,$numfilas))
			return false;
		return true;			
	}

//	datos de entradas :
//   limit order by

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	public function BusquedaAvanzada($datos,&$resultado,&$numfilas)
	{
		$sparam=array(
			'xcatcod'=> "",
			'catcod'=> 0,
			'xmodulodesc'=> 0,
			'modulodesc'=> "",
			'xmoduloestado'=> 0,
			'moduloestado'=> "",
			'xmodulotipocod'=> 0,
			'modulotipocod'=> "",
			'orderby'=> "modulocod desc",
			'limit'=> ""	
		);
		if (isset ($datos['catcod']) && $datos['catcod']!="")
		{
			$sparam['catcod']= $datos['catcod'];
			$sparam['xcatcod']= 1;
		}
		if (isset ($datos['modulodesc']) && $datos['modulodesc']!="")
		{
			$sparam['modulodesc']= $datos['modulodesc'];
			$sparam['xmodulodesc']= 1;
		}
		if (isset ($datos['moduloestado']) && $datos['moduloestado']!="")
		{
			$sparam['moduloestado']= $datos['moduloestado'];
			$sparam['xmoduloestado']= 1;
		}
		if (isset ($datos['modulotipocod']) && $datos['modulotipocod']!="")
		{
			$sparam['modulotipocod']= $datos['modulotipocod'];
			$sparam['xmodulotipocod']= 1;
		}	
		if (isset ($datos['orderby']) && $datos['orderby']!="")
			$sparam['orderby']= $datos['orderby'];
			
		if (isset ($datos['limit']) && $datos['limit']!="")
			$sparam['limit']= $datos['limit'];
		
		
		if (!parent::BusquedaAvanzada ($sparam,$resultado,$numfilas))
			return false;
			
		return true;			
	}



// Trae los datos de los modulos por codigo

// Parámetros de Entrada:
//		datos: arreglo de datos
//			modulocod = codigo de la frrase

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	public function BuscarxCodigo($datos,&$resultado,&$numfilas)
	{
		if (!parent::BuscarxCodigo ($datos,$resultado,$numfilas))
			return false;
		return true;			
	}



// Parámetros de Entrada:
//		datos: arreglo de datos
//			modulocod = codigo del modulo
// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no	
	
	public function EliminarModulo($datos)
	{
		if (!parent::EliminarModulo($datos))
			return false;
			
		if(!$this->PublicarJson())
			return false;	
				
		return true;
	}

	public function EliminarModuloEnTapa($datos)
	{
		if (!parent::EliminarModuloEnTapa($datos))
			return false;
		return true;
	}
	public function ModificarModulo($datos)
	{	
		
		if (!$this->_ValidarModificar($datos))
			return false;
		
		if($datos['moduloicono']=="")
			$datos['moduloicono']="NULL";	

		if(!parent::ModificarModulo($datos))
			return false;
			
		if(!$this->PublicarJson())
			return false;		
		
		return true;
	} 


	public function InsertarModulo($datos,&$frasecod)
	{	

		if (!$this->_ValidarInsertar($datos))
			return false;
			
		$datos['moduloestado'] = ACTIVO;	
		if($datos['moduloicono']=="")
			$datos['moduloicono']="NULL";
		if(!parent::InsertarModulo($datos,$frasecod))
			return false;
			
		if(!$this->PublicarJson())
			return false;		
		
		return true;
	} 
	
// Parámetros de Entrada:
//		modulocod= codigo del modulo.
//      moduloestado = nuevo estado del modulo

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no	
	public function DesActivarModulo($datos)
	{
		
		$datosmodificar['modulocod'] = $datos['modulocod'];
		$datosmodificar['moduloestado'] = NOACTIVO;
		
		if (!$this->ModificarEstadoModulo($datosmodificar))
			return false;
		
		return true;
	}
	
	// Parámetros de Entrada:
//		modulocod= codigo del modulo.
//      moduloestado = nuevo estado del modulo

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no	
	public function ActivarModulo($datos)
	{
		
		$datosmodificar['modulocod'] = $datos['modulocod'];
		$datosmodificar['moduloestado'] = ACTIVO;
		
		if (!$this->ModificarEstadoModulo($datosmodificar))
			return false;
		
		return true;
	}
	
// Retorna true o false si pudo cambiarle el estado del modulo
// Parámetros de Entrada:
//		modulocod = codigo del modulo.
// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function ModificarEstadoModulo($datos)
	{
		if (!parent::ModificarEstadoModulo($datos))
			return false;
		
		if(!$this->PublicarJson())
			return false;	
		
		return true;	
	}
	
	
	public function PublicarJson()
	{
		
		
		$spparam=array();
		if(!$this->conexion->ejecutarStoredProcedure("sel_tap_modulos_tipos",$spparam,$resultadoTipos,$numfilasTipos,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al generar json de datos. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		while ($filaTipos = $this->conexion->ObtenerSiguienteRegistro($resultadoTipos)) 
		{
			$array = array();
			$sql = "SELECT a.* FROM tap_modulos_categorias AS a INNER JOIN tap_modulos AS b ON a.catcod=b.catcod
			WHERE b.moduloestado=10 AND b.modulotipocod=".$filaTipos['modulotipocod']." GROUP BY catcod";
			
			$erroren ="";
			if(!$this->conexion->_EjecutarQuery($sql,$erroren,$resultado,$errno))
			{
				FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al generar json de datos. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
				return false;

			}
			
			$datosbuscar['moduloestado'] = ACTIVO;
			$datosbuscar['modulotipocod'] = $filaTipos['modulotipocod'];
			$datosbuscar['orderby'] = "modulocod ASC";
			
			while ($fila = $this->conexion->ObtenerSiguienteRegistro($resultado)) 
			{
				$datosbuscar['catcod'] = $fila['catcod'];
				$array[$fila['catcod']] = array();
				$array[$fila['catcod']]['catcod'] = $fila['catcod'];
				$array[$fila['catcod']]['catcod'] = $fila['catcod'];
				$array[$fila['catcod']]['catdesc'] = utf8_encode($fila['catdesc']);
				$array[$fila['catcod']]['modulos']=array();
				if(!$this->BusquedaAvanzada($datosbuscar,$resultadoModulos,$numfilaModulos))
				{
					FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al generar json de datos. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
					return false;
				}
				while ($filaModulos = $this->conexion->ObtenerSiguienteRegistro($resultadoModulos)) 
				{
					$array[$fila['catcod']]['modulos'][$filaModulos['modulocod']] = array();
					$array[$fila['catcod']]['modulos'][$filaModulos['modulocod']]['modulocod'] = $filaModulos['modulocod']; 
					$array[$fila['catcod']]['modulos'][$filaModulos['modulocod']]['modulodesc'] = utf8_encode($filaModulos['modulodesc']);
					$array[$fila['catcod']]['modulos'][$filaModulos['modulocod']]['moduloarchivo'] = $filaModulos['moduloarchivo']; 
					$array[$fila['catcod']]['modulos'][$filaModulos['modulocod']]['modulotipocod'] = $filaModulos['modulotipocod']; 
					$array[$fila['catcod']]['modulos'][$filaModulos['modulocod']]['moduloaccesodirecto'] = $filaModulos['moduloaccesodirecto']; 
					$array[$fila['catcod']]['modulos'][$filaModulos['modulocod']]['moduloicono'] =  utf8_encode($filaModulos['moduloicono']); 
				}
				
			}
			$json = json_encode($array);
			file_put_contents(PUBLICA."json/tapas_modulos_".$filaTipos['modulotipocod'].".json" , $json);	
		}
		return true;	
	}


	
	private function _ValidarInsertar($datos)
	{

		if (!$this->_ValidarDatosVacios($datos))
			return false;

		return true;
	}

// Retorna true o false al modificar si alguno de los campos esta vacio.

	private function _ValidarModificar($datos)
	{
		if (!$this->_ValidarDatosVacios($datos))
			return false;
		if (!$this->BuscarxCodigo($datos,$resultado,$numfilas))
			return false;
		if ($numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar un modulo valido. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;
		}

		return true;
	}
	

	private function _ValidarEliminar($datos)
	{
		if (!$this->BuscarxCodigo($datos,$resultado,$numfilas))
			return false;
		if ($numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar una frase valida. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;
		}

		
		return true;
	}
	
	private function _ValidarDatosVacios($datos)
	{
		
		if ($datos['modulodesc']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar una descripcion. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;
		}
		if ($datos['modulotipocod']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar un tipo de modulo. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;
		}
		if ($datos['catcod']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe seleccionar una categoria. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;
		}
		if ($datos['moduloarchivo']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar el nombre del archivo. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;
		}	
		if(!isset($datos['moduloaccesodirecto']) || $datos['moduloaccesodirecto']=='')
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe seleccionar si tiene acceso directo. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;
		}
		if($datos['moduloaccesodirecto']=='1')
		{
			if(!isset($datos['moduloicono']) || $datos['moduloicono']=='')
			{
				FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar un icono. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
				return false;
			}
		}	
	
		return true;
	}

// Parámetros de Entrada:
//	Sin parametros de entrada

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	public function TapasModulosConfeccionarCategoriasSP(&$resultado,&$numfilas)
	{
		if (!parent::TapasModulosConfeccionarCategoriasSP($resultado,$numfilas))
			return false;
		return true;			
	}
	
}//FIN CLASS
?>