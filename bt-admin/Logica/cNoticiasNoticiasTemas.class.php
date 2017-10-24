<?php  
// Clase que maneja los temas relacionados a un tramite.

include(DIR_CLASES_DB."cNoticiasNoticiasTemas.db.php");

class cNoticiasNoticiasTemas extends cNoticiasNoticiasTemasdb	
{
	protected $conexion;
	protected $formato;

	

//-----------------------------------------------------------------------------------------
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

	
	
	
//-----------------------------------------------------------------------------------------
//							 PUBLICAS	
//----------------------------------------------------------------------------------------- 


//------------------------------------------------------------------------------------	
// Retorna un query con todas los temas relacionados a un tramite

// Parámetros de Entrada:
//		noticiacod: tramite a buscar

// Retorna:
//		resultado= Arreglo con todos los datos de un categoria.
//		numfilas= cantidad de filas 
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	public function BuscarxNoticia($datos,&$resultado,&$numfilas)
	{
		$datos['temaestado'] = ACTIVO;
		if (!parent::BuscarxNoticia($datos,$resultado,$numfilas))
			return false;
		
		return true;
	} 

//------------------------------------------------------------------------------------	
// Retorna un query con todas el tema relacionado a un tramite

// Parámetros de Entrada:
//		noticiacod: codigo del tramite a buscar
//		temacod: codigo del tema a buscar

// Retorna:
//		resultado= Arreglo con todos los datos de un categoria.
//		numfilas= cantidad de filas 
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	public function BuscarxCodigo($datos,&$resultado,&$numfilas)
	{
		if (!parent::BuscarxCodigo($datos,$resultado,$numfilas))
			return false;
		
		return true;
	} 
//------------------------------------------------------------------------------------	
// Retorna un query con todas los temas relacionados a un tramite

// Parámetros de Entrada:
//		noticiacod: tramite a buscar

// Retorna:
//		resultado= Arreglo con todos los datos de un categoria.
//		numfilas= cantidad de filas 
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	public function BuscarxCodigoNoticia($datos,&$resultado,&$numfilas)
	{
		if (!parent::BuscarxCodigoNoticia($datos,$resultado,$numfilas))
			return false;
		
		return true;
	} 
	


//------------------------------------------------------------------------------------	
// Retorna un query con todas el tema relacionado a un tramite

// Parámetros de Entrada:
//		temacod: codigo del tema a buscar

// Retorna:
//		resultado= Arreglo con todos los datos de un categoria.
//		numfilas= cantidad de filas 
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	public function BuscarxTema($datos,&$resultado,&$numfilas)
	{
		if (!parent::BuscarxTema($datos,$resultado,$numfilas))
			return false;
		
		return true;
	} 


	function ObtenerDatosInsertar($datos,&$datosdevueltos)
	{
		$datosdevueltos = array();
		foreach ($datos as $nombre_var => $valor_var) {
			if (empty($valor_var)) {
				$vacio[$nombre_var] = $valor_var;
			} else {
				
				$post[$nombre_var] = $valor_var;
				$opcion = substr($nombre_var,0,8);
				if ($opcion=="tipocod_")
				{
					$tipocod = substr($nombre_var,8,strlen($nombre_var));
					$datosdevueltos[$tipocod]=$valor_var;
				}
				
			}
		}
		return true;
	}



	function Actualizar($datos)
	{
		if(!$this->BuscarxNoticia($datos,$resultado,$numfilas))
			return false;
		
		$arregloInicial = array();
		while($datosTemaNoticia = $this->conexion->ObtenerSiguienteRegistro($resultado))
			$arregloInicial[$datosTemaNoticia['temacod']]=$datosTemaNoticia['temacod'];
			
		$arregloFinal = array();	
		if (isset($datos['temacod']))	
			$arregloFinal = $datos['temacod'];
		
		$arraysacar=array_diff($arregloInicial,$arregloFinal);
		$arrayponer=array_diff($arregloFinal,$arregloInicial);


		$datosInsertar['noticiacod'] = $datosEliminar['noticiacod'] = $datos['noticiacod'];
		foreach ($arraysacar as $temacod)
		{
			$datosEliminar['temacod'] = $temacod;
			if(!$this->Eliminar($datosEliminar))
				return false;
		}


		foreach ($arrayponer as $temacod)
		{
			$datosInsertar['temacod'] = $temacod;
			if(!$this->Insertar($datosInsertar))
				return false;
		}

		return true;	
	}

	
//----------------------------------------------------------------------------------------- 
// Inserta una acción a un tramite

// Parámetros de Entrada:
	// noticiacod = codigo del tramite
	// temacod = codigo del tema

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	public function Insertar($datos)
	{

		if (!$this->_ValidarInsertar($datos))
			return false;
		
		if (!parent::Insertar($datos))
			return false;
			
			
		return true;
	} 

	public function InsertarDuplicar($datos)
	{


		if (!parent::Insertar($datos))
			return false;
			
			
		return true;
	} 


//-----------------------------------------------------------------------------------------
//							 PRIVADAS	
//----------------------------------------------------------------------------------------- 


//----------------------------------------------------------------------------------------- 
// Retorna true o false si algunos de los campos esta vacio

// Parámetros de Entrada:
//		noticiacod = codigo del tramite
//		temacod = codigo del tema


// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function _ValidarDatosVacios($datos)
	{
		if ($datos['noticiacod']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar una noticia. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;
		}

		if ($datos['temacod']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar un tema valido. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;
		}

		return true;
	}

//----------------------------------------------------------------------------------------- 
// Retorna true o false al dar de alta si algunos de los campos esta vacio o si exite otra categoria con ese nombre

// Parámetros de Entrada:
//		catnom = nombre de la categoria.
//      catsuperior = si existe el cogido de la categoria: 		 

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no
	function _ValidarInsertar($datos)
	{
		if (!$this->_ValidarDatosVacios($datos))
			return false;


		return true;
	}

//----------------------------------------------------------------------------------------- 
// Eliminar un tema de un tramite

// Parámetros de Entrada:
//		noticiacod = codigo del tramite

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function Eliminar($datos)
	{
		if (!$this->PuedeEliminar($datos))
			return false;
			
		if (!parent::Eliminar($datos))
			return false;
			
			
		return true;
	}


//----------------------------------------------------------------------------------------- 
// Elimina los temas de un tramite

// Parámetros de Entrada:
//		noticiacod = codigo del tramite

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function EliminarxNoticia($datos)
	{
		if (!$this->PuedeEliminarxNoticia($datos))
			return false;
			
		if (!parent::EliminarxNoticia($datos))
			return false;
			
			
		return true;
	}

//----------------------------------------------------------------------------------------- 
// Elimina los temas de un tramite

// Parámetros de Entrada:
//		noticiacod = codigo del tramite

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	public function EliminarCompletoxNoticiacod($datos)
	{		
		if (!parent::EliminarCompletoxNoticiacod($datos))
			return false;
		return true;	
	}

	

//----------------------------------------------------------------------------------------- 
// Retorna true o false si los temas del tramite pueden ser eliminadas

// Parámetros de Entrada:
//		noticiacod = codigo del tramite

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function PuedeEliminarxNoticia($datos)
	{
		if ($datos['noticiacod']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar una noticia. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;
		}
		return true;
	}


//----------------------------------------------------------------------------------------- 
// Retorna true o false si los temas del tramite pueden ser eliminadas

// Parámetros de Entrada:
//		noticiacod = codigo del tramite

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function PuedeEliminar($datos)
	{
		if ($datos['noticiacod']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar una noticia. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;
		}
		if ($datos['temacod']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar un tema. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;
		}
		return true;
	}


}// FIN CLASE

?>