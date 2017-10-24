<?php  
include(DIR_CLASES_DB."cGraficos.db.php");

class cGraficos extends cGraficosdb	
{
	protected $conexion;
	protected $formato;


	
//-----------------------------------------------------------------------------------------
//  LAS FUNCIONES QUE HASTA AHORA TIENEN ESTO SON:  
// 	ArregloHijos
//  TieneHijos
//  TraerDatosCategoria
//-----------------------------------------------------------------------------------------

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




	public function BuscarTiposActivos($datos,&$spnombre,&$sparam)
	{
		$datos['graficotipoestado'] = ACTIVO;
		if (!parent::BuscarTipos($datos,$spnombre,$sparam))
			return false;
	}
	

	public function BuscarTiposxCodigoxConjunto($datos,&$resultado,&$numfilas)
	{
		if (!parent::BuscarTiposxCodigoxConjunto($datos,$resultado,$numfilas))
			return false;
		
		
		return true;	
	}
	

//------------------------------------------------------------------------------------------	
// Retorna en un arreglo con los datos de un album

// Parámetros de Entrada:
//		graficotitulo: titulo del grafico, opcional

// Retorna:
//		resultado= Arreglo con todos los datos de un album.
//		numfilas= cantidad de filas 
//		la función retorna true o false si se pudo ejecutar con éxito o no	
	public function BusquedaAvanzada($datos,&$resultado,&$numfilas)
	{
		$sparam=array(
			'xgraficotitulo'=> 0,
			'graficotitulo'=> "",
			'xgraficoestado'=> 0,
			'graficoestado'=> "",
			'orderby'=> "graficocod ASC",
			'limit'=> ""
			);
			
		if (isset ($datos['graficotitulo']) && $datos['graficotitulo']!="")
		{
			$sparam['graficotitulo']= $datos['graficotitulo'];
			$sparam['xgraficotitulo']= 1;
		}
			
		if (isset ($datos['graficoestado']) && $datos['graficoestado']!="")
		{
			$sparam['graficoestado']= $datos['graficoestado'];
			$sparam['xgraficoestado']= 1;
		}
	
		if (isset ($datos['orderby']) && $datos['orderby']!="")
			$sparam['orderby']= $datos['orderby'];
		if (isset ($datos['limit']) && $datos['limit']!="")
			$sparam['limit']= $datos['limit'];

	
		if (!parent::BusquedaAvanzada($sparam,$resultado,$numfilas))
			return false;
		
		return true;
	}
	
	
	//------------------------------------------------------------------------------------------	
	// Retorna en un arreglo con los datos de un album
	
	// Parámetros de Entrada:
	//		albumcod: album a buscar
	
	// Retorna:
	//		resultado= Arreglo con todos los datos de un album.
	//		numfilas= cantidad de filas 
	//		la función retorna true o false si se pudo ejecutar con éxito o no
	public function BuscarxCodigo($datos,&$resultado,&$numfilas)
	{
		if (!parent::BuscarxCodigo($datos,$resultado,$numfilas))
			return false;
		
		return true;
	} 



//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 

// Modificar una columna

// Parámetros de Entrada:
//		datos: arreglo de datos
//			columnacod = codigo de la fila

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function Insertar($datos,&$codigoinsertado)
	{
		if (!$this->_ValidarInsertar($datos))
			return false;

		$datos['graficoleyendaalinear'] = "center";
		$datos['graficoleyendaalinearvertical'] = "bottom";
		$datos['graficoleyendamostrar'] = "1";
		$datos['graficoestado'] = NOPUBLICADO;
		if (!parent::Insertar ($datos,$codigoinsertado))
			return false;
			
		return true;	
	}	


//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 

// Modificar una columna

// Parámetros de Entrada:
//		datos: arreglo de datos
//			columnacod = codigo de la fila

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function Modificar($datos)
	{
		if (!$this->_ValidarModificar($datos))
			return false;

		if (!parent::Modificar ($datos))
			return false;
			
		return true;	
	}	


//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 

// Modificar una columna

// Parámetros de Entrada:
//		datos: arreglo de datos
//			columnacod = codigo de la fila

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function Eliminar($datos)
	{
		if (!$this->_ValidarEliminar($datos))
			return false;
	
		$oGraficosValoresPorc = new cGraficosValoresPorcentajes($this->conexion,$this->formato);
		$oGraficosValores = new cGraficosValores($this->conexion,$this->formato);
		$oGraficosColumnas = new cGraficosColumnas($this->conexion,$this->formato);
		$oGraficosFilas = new cGraficosFilas($this->conexion,$this->formato);

		if(!$oGraficosValoresPorc->EliminarxGrafico($datos))
			return false;
		if(!$oGraficosValores->EliminarxGrafico($datos))
			return false;
		if(!$oGraficosColumnas->EliminarxGrafico($datos))
			return false;
		if(!$oGraficosFilas->EliminarxGrafico($datos))
			return false;

		if (!parent::Eliminar ($datos))
			return false;
			
		return true;	
	}	


//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 

	public function MostrarGrafico($datosgrafico,&$stringcategorias,&$stringseries,&$stringdatosgrafico)
	{

		$oGraficosFilas = new cGraficosFilas($this->conexion);
		$oGraficosColumnas = new cGraficosColumnas($this->conexion);

		if ($datosgrafico['conjuntocod']==GRAFVALORES)
		{
	
			$oGraficosValores = new cGraficosValores($this->conexion);
			if (!$oGraficosFilas->BuscarxGrafico ($datosgrafico,$resultadofilas,$numfilasfilas))
				return false;
			if (!$oGraficosColumnas->BuscarxGrafico ($datosgrafico,$resultadocol,$numfilascol))
				return false;
	
			$arreglodatos = array();
			while ($fila = $this->conexion->ObtenerSiguienteRegistro($resultadocol))
				$arreglodatos[] = $fila;
				
			$i = 0;
	
			while ($fila = $this->conexion->ObtenerSiguienteRegistro($resultadofilas))
			{
				if (!$oGraficosValores->BuscarxGraficoxFila($fila,$resultadoval,$numfilasval))
					return false;
				$arreglofilas = array();
				while($datavalores = $this->conexion->ObtenerSiguienteRegistro($resultadoval))
					$arreglofilas[$datavalores['columnacod']] = $datavalores['valor'];
			
				$arreglodatosmostrar[$i]['name'] = utf8_encode($fila['filatitulo']);
				$arreglodatosmostrar[$i]['color'] = $fila['filacolor'];
			
				foreach($arreglodatos as $datos)
				{
					$valores = 0;
					if (array_key_exists($datos['columnacod'],$arreglofilas))
						$valores = $arreglofilas[$datos['columnacod']];
						
					if ($valores!="")
						$arreglodatosmostrar[$i]['data'][] = $valores;
					else
						$arreglodatosmostrar[$i]['data'][] = NULL;
				}  
				$i++; 
			}
			
			$this->conexion->MoverPunteroaPosicion($resultadocol,0);
	
			$stringcategorias = FuncionesPHPLocal::js_query($this->conexion,$resultadocol,"columnatitulo");
			$stringseries = json_encode($arreglodatosmostrar,JSON_NUMERIC_CHECK);
			$stringdatosgrafico = json_encode($datosgrafico,JSON_NUMERIC_CHECK);
		}else
		{
			$oGraficosValores = new cGraficosValoresPorcentajes($this->conexion);
			if (!$oGraficosFilas->BuscarxGrafico ($datosgrafico,$resultadofilas,$numfilasfilas))
				return false;
			
			if (!$oGraficosValores->BuscarxGrafico($datosgrafico,$resultadoval,$numfilasval))
				return false;
			$arreglofilas = array();
			while($datavalores = $this->conexion->ObtenerSiguienteRegistro($resultadoval))
				$arreglofilas[$datavalores['filacod']] = $datavalores['valor'];
			
			$i = 0;
			$arreglo=$arreglotitulo=$arreglocomuness=array();
			
			while ($fila = $this->conexion->ObtenerSiguienteRegistro($resultadofilas))
			{
				$valores = 0;
				if (array_key_exists($fila['filacod'],$arreglofilas))
					$valores = $arreglofilas[$fila['filacod']];
				
				if ($valores!="")
					$valormostrar = $valores;
				else
					$valormostrar = NULL;
				
				$arreglotitulos[]=utf8_encode($fila['filatitulo']);
				$arreglocarga['name']= utf8_encode($fila['filatitulo']);
				$arreglocarga['color']= $fila['filacolor'];
				$arreglocarga['y']= $valormostrar;
			
				$arreglo[] = $arreglocarga;
				$arreglocomunes[$i] = $arreglocarga;
				$i++;	
			}
			
			if ($datosgrafico['graficotipovalor']=="pie")
			{
				$arreglodatosmostrar['data']=$arreglo;
				$arreglodatosmostrar['type']=$datosgrafico['graficotipovalor'];
			}else
			{
				$arreglodatosmostrar['data']=$arreglocomunes;
				$arreglodatosmostrar['type']=$datosgrafico['graficotipovalor'];
			}
			$stringcategorias = FuncionesPHPLocal::js_array($arreglotitulos);
			$stringseries = "[".json_encode($arreglodatosmostrar,JSON_NUMERIC_CHECK)."]";
			$stringdatosgrafico = json_encode($datosgrafico,JSON_NUMERIC_CHECK);

		}
		
		return true;		
		
	}







//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 

// Function que valida los datos al momento de insertar un grafico

// Parámetros de Entrada:
//		datos: array asociativo con los datos a validar

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	private function _ValidarInsertar($datos)
	{
		
		if (!$this->_ValidarDatosVacios($datos))
			return false;

		return true;
	}


//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 

// Function que valida los datos al momento de modificar una fila

// Parámetros de Entrada:
//		datos: array asociativo con los datos a validar

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
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, grafico inexistente. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		return true;
	}


//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 

// Function que valida los datos al momento de eliminar un grafico

// Parámetros de Entrada:
//		datos: array asociativo con los datos a validar

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	private function _ValidarEliminar($datos)
	{
		
		if (!$this->BuscarxCodigo($datos,$resultado,$numfilas))
			return false;
			
		if ($numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, grafico inexistente. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		return true;
	}

//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 

// Function que valida los datos los datos basicos de una fila

// Parámetros de Entrada:
//		datos: array asociativo con los datos a validar
//			filatitulocod = titulo de la columna

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no	

	private function _ValidarDatosVacios($datos)
	{

		if ($datos['graficotitulo']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRSOSP,"Error debe ingresar un titulo. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		if ($datos['graficotipocod']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRSOSP,"Error debe ingresar un tipo. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		if ($datos['conjuntocod']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRSOSP,"Error debe seleccionar un eje. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		if (!$this->BuscarTiposxCodigoxConjunto($datos,$resultado,$numfilas))
			return false;

		if ($numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRSOSP,"Error, tipo inexistente. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		return true;
	}




}// FIN CLASE

?>