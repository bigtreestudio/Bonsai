<?php  
include(DIR_CLASES_DB."cContactos.db.php");

class cContactos extends cContactosdb	
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
//	Sin parametros de entrada

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	public function Buscar(&$resultado,&$numfilas)
	{
		if (!parent::Buscar ($resultado,$numfilas))
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
			'xformulariotipotitulo'=> 0,
			'formulariotipotitulo'=> "",
			'xformulariotipocod'=> 0,
			'formulariotipocod'=> "",
			'xformularioestado'=> 0,
			'formularioestado'=> "",							
			'orderby'=> "formulariocod desc",
			'limit'=> ""
		);	
		
		if (isset ($datos['formulariotipotitulo']) && $datos['formulariotipotitulo']!="")
		{
			$sparam['formulariotipotitulo']= $datos['formulariotipotitulo'];
			$sparam['xformulariotipotitulo']= 1;
		}	
		if (isset ($datos['formularioestado']) && $datos['formularioestado']!="")
		{
			$sparam['formularioestado']= $datos['formularioestado'];
			$sparam['xformularioestado']= 1;
		}		
		if (isset ($datos['formulariotipocod']) && $datos['formulariotipocod']!="")
		{
			$sparam['formulariotipocod']= $datos['formulariotipocod'];
			$sparam['xformulariotipocod']= 1;
		}	
		
		if (isset ($datos['orderby']) && $datos['orderby']!="")
			$sparam['orderby']= $datos['orderby'];
			
		if (isset ($datos['limit']) && $datos['limit']!="")
			$sparam['limit']= $datos['limit'];
	
		
		if (!parent::BusquedaAvanzada ($sparam,$resultado,$numfilas))
			return false;
		return true;			
	}

	public function BuscarEncuestasOpciones($datos,&$resultado,&$numfilas)
	{
		if (!parent::BuscarEncuestasOpciones ($datos,$resultado,$numfilas))
			return false;
		return true;			
	}

// Trae los datos de la tapa por codigo

// Parámetros de Entrada:
//		datos: arreglo de datos
//			tapacod = codigo de la Tapa

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
//----------------------------------------------------------------------------------------- 

// Parámetros de Entrada:
//		datos: arreglo de datos
//			

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function BusquedaFormularioTipoSP(&$spnombre,&$sparam)
	{
		
		if (!parent::BusquedaFormularioTipoSP ($spnombre,$sparam))
			return false;
		
		return true;		
	}	
// Parámetros de Entrada:
//		datos: arreglo de datos
//			

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function BusquedaFormularioTiposxFormulariotipocod(&$spnombre,&$sparam)
	{
		
		if (!parent::BusquedaFormularioTiposxFormulariotipocod ($spnombre,$sparam))
			return false;
		
		return true;		
	}
// Parámetros de Entrada:
//		datos: arreglo de datos
//			tapacod = codigo del formulario
// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no	
	
	public function EliminarFormulario($datos)
	{
//		if (!$this->_ValidarEliminar($datos))
	//		return false;;
		
		if (!parent::EliminarFormulario($datos))
			return false;
		
		return true;
	}

//
	public function ModificarFormulario($datos)
	{	
		if (!$this->_ValidarModificar($datos))
			return false;
			
		$datos['formulariociudad'] = $datos['departamentocod'];
		if(!parent::ModificarFormulario($datos))
			return false;

		$datos["formulariodominio"] = FuncionesPHPLocal::EscapearCaracteres($datos["formulariotipotitulo"]);
		$datos["formulariodominio"]=preg_replace('/[^a-zA-Z0-9-_ ]/', '-', trim($datos["formulariodominio"]));
		$datos["formulariodominio"]=str_replace(' ', '-', trim($datos["formulariodominio"]))."_c".$datos["formulariocod"];
	
		if(!$this->GenerarDominio($datos))
			return false;

		
		return true;
	} 


	public function InsertarFormulario($datos,&$formulariocod)
	{	


		if (!$this->_ValidarInsertar($datos))
			return false;
			
		$datos['formularioestado'] = ACTIVO;	
		$datos['formulariociudad'] = $datos['departamentocod'];
		
		
		if(!parent::InsertarFormulario($datos,$formulariocod))
			return false;
		
		$datos["formulariodominio"] = FuncionesPHPLocal::EscapearCaracteres($datos["formulariotipotitulo"]);
		$datos["formulariodominio"]=preg_replace('/[^a-zA-Z0-9-_ ]/', '-', trim($datos["formulariodominio"]));
		$datos["formulariodominio"]=str_replace(' ', '-', trim($datos["formulariodominio"]))."_c".$formulariocod;
	
		$datos["formulariocod"]=$formulariocod;
		if(!$this->GenerarDominio($datos))
			return false;			
		
		return true;
	} 
	
	// Parámetros de Entrada:
//		galeriacod = codigo de galerias.
//      galeriaestadocod = nuevo estado de la galerias

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no
	public function GenerarDominio($datos)
	{
		if (!parent::GenerarDominio($datos))
			return false;
			
		return true;	
	}
	
// Parámetros de Entrada:
//		tapacod= codigo de la tapa.
//      tapaestado = nuevo estado de la tapa

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no	
	public function DesActivarFormulario($datos)
	{
		
		$datosmodificar['formulariocod'] = $datos['formulariocod'];
		$datosmodificar['formularioestado'] = NOACTIVO;
		if (!$this->ModificarEstadoFormulario($datosmodificar))
			return false;
		
		return true;
	}
	
	// Parámetros de Entrada:
//		tapacod= codigo de la tapa.
//      tapaestado = nuevo estado de la tapa

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no	
	public function ActivarFormulario($datos)
	{
		
		$datosmodificar['formulariocod'] = $datos['formulariocod'];
		$datosmodificar['formularioestado'] = ACTIVO;
		if (!$this->ModificarEstadoFormulario($datosmodificar))
			return false;
		
		return true;
	}
	
// Retorna true o false si pudo cambiarle el estado de la tapa
// Parámetros de Entrada:
//		tapacod = codigo de la tapa.
// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no
	public function ModificarEstadoFormulario($datos)
	{
		if (!parent::ModificarEstadoFormulario($datos))
			return false;
			
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

		return true;
	}
	
	
	private function _ValidarDatosVacios($datos)
	{
		

		if ($datos['formulariotipocod']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar un tipo de formulario. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;
		}	
		if ($datos['formulariotipotitulo']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar un titulo al formulario. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;
		}	
		if ($datos['provinciacod']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar una provincia. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;
		}


		if ($datos['departamentocod']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar una ciudad. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;
		}
			
		return true;
	}

	private function _ValidarEliminar($datos)
	{

		$oEncuestasOpciones= new cEncuestasOpciones($this->conexion);
		if (!$oEncuestasOpciones->BuscarxCodigo($datos,$resultado,$numfilas))
			return false;
	
		while($fila = $this->conexion->ObtenerSiguienteRegistro($resultado))
		{
			if($fila['opcioncantvotos']>0){
				FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"La encuesta contiene respuestas asociadas. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
					return false;
				}
		}			
	
		return true;
	}
	
}//FIN CLASS
?>