<?php  
include(DIR_CLASES_DB."cPaginasModulos.db.php");

class cPaginasModulos extends cPaginasModulosdb	
{
	protected $conexion;
	protected $formato;

	
// CLASE cPaginasModulos 

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



//------------------------------------------------------------------------------------------	
// Retorna en un query con todos los modulos de una pagina

// Parámetros de Entrada:
//		pagcod: codigo de la pagina

// Retorna:
//		resultado= Arreglo con todos los datos de un categoria.
//		numfilas= cantidad de filas 
//		la función retorna true o false si se pudo ejecutar con éxito o no
	public function BuscarxPagina($datos,&$resultado,&$numfilas)
	{
		if (!parent::BuscarxPagina($datos,$resultado,$numfilas))
			return false;
		
		return true;
	} 
//------------------------------------------------------------------------------------------	
// Retorna en un query con todos los modulos de una pagina

// Parámetros de Entrada:
//		pagcod: codigo de la pagina

// Retorna:
//		resultado= Arreglo con todos los datos de un categoria.
//		numfilas= cantidad de filas 
//		la función retorna true o false si se pudo ejecutar con éxito o no


	public function BuscarModuloxCodigo($datos,&$resultado,&$numfilas)
	{
		if (!parent::BuscarModuloxCodigo($datos,$resultado,$numfilas))
			return false;
		
		return true;
	} 

//----------------------------------------------------------------------------------------- 
//ABM DE PAGINAS MODULOS.-
//----------------------------------------------------------------------------------------- 



//----------------------------------------------------------------------------------------- 
// Inserta un modulo nuevo en una pagina.

// Parámetros de Entrada:

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	public function ActualizarInsertar($datos,&$codigoinsertado)
	{

		if(!$this->BuscarxPagina($datos,$resultado,$numfilas))
			return false;

		$datosmodif['moduloorden'] = 1;
		while ($fila = $this->conexion->ObtenerSiguienteRegistro($resultado))
		{
			if ($datosmodif['moduloorden']==$datos['moduloorden'])
				$datosmodif['moduloorden']++;
				
			$datosmodif['pagmodulocod'] = $fila['pagmodulocod'];
			if (!parent::ModificarOrden($datosmodif))
				return false;
			
			$datosmodif['moduloorden']++;
		}
		
		if (!parent::Insertar($datos,$codigoinsertado))
			return false;
			
		if (!$this->GenerarColumnas($datos))
			return false;
				
		return true;
	} 
	



//----------------------------------------------------------------------------------------- 
// Inserta un modulo nuevo en una pagina.

// Parámetros de Entrada:

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	public function Insertar($datos,&$codigoinsertado)
	{
		if (!$this->_ValidarDatosAlta($datos))
			return false;

		if (!parent::Insertar($datos,$codigoinsertado))
			return false;
			
		return true;
	} 
	
//----------------------------------------------------------------------------------------- 
// Modifica los modulos de una pagina asignadso

// Parámetros de Entrada:

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	public function Modificar($datos)
	{
		
		if (!$this->_ValidarDatosModificar($datos))
			return false;

		if (!parent::Modificar($datos))
			return false;
			
		if (!$this->GenerarColumnas($datos))
			return false;
		return true;
	} 
	
//----------------------------------------------------------------------------------------- 
// Retorna true o false si pudo eliminar un modulo de una pagina 

// Parámetros de Entrada:
//		pagmodulocod = codigo de modulo pagina.

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function Eliminar($datos)
	{
		if (!$this->_ValidarDatosEliminar($datos))
			return false;
			
		if (!parent::Eliminar($datos))
			return false;
			
		if (!$this->GenerarColumnas($datos))
			return false;
			
		return true;
	}
//----------------------------------------------------------------------------------------- 
// Retorna true o false si pudo eliminar un modulo de una pagina xpagcod

// Parámetros de Entrada:
//		pagcod = codigo de  pagina.

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function EliminarxPagina($datos)
	{

		if (!parent::EliminarxPagina($datos))
			return false;
			
			
		return true;
	}	
	
	
//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 

// Insertar una nueva noticia publicada

// Parámetros de Entrada:
//		datos: array asociativo con los datos a agregar

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function GenerarColumnas($datos)
	{
		if (!$this->BuscarxPagina($datos,$resultado,$numfilas))
			return false;	

		$htmlModuleRender = "";
		while ($datospaginasmodulos = $this->conexion->ObtenerSiguienteRegistro($resultado))
		{
			$datospaginasmodulos['conexion'] = $this->conexion;
			$htmlModuleRender .= FuncionesPHPLocal::RenderFile("tapas_modulos/html/".$datospaginasmodulos['moduloarchivo'],$datospaginasmodulos);
		}

		if(!FuncionesPHPLocal::GuardarArchivo(PUBLICA."paginas/",$htmlModuleRender,"pagina_".$datos['pagcod'].".html"))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_INF,"Error, al generar el archivo de la columna. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		return true;
	}

//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 

// Eliminar un archivo de columna

// Parámetros de Entrada:
//		datos: array asociativo con los datos a agregar

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function EliminarColumnas($datos)
	{
		if (file_exists(PUBLICA."paginas/pagina_".$datos['pagcod'].".html"))
		{
			if(!unlink(PUBLICA."paginas/pagina_".$datos['pagcod'].".html"))
			{
				FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_INF,"Error, al eliminar el archivo de la columna. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
				return false;
			}
		}

		return true;
	}
	
	
//----------------------------------------------------------------------------------------- 
//Retorna true o false si pudo cambiar el orden de los modulos paginas

// Parámetros de Entrada:
//		moduloorden = orden de los modulos de las paginas.

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no		
	public function ModificarOrden($datos)
	{
		$datosmodif['moduloorden'] = 1;
		foreach ($datos['modulo'] as $pagmodulocod)
		{
			$datosmodif['pagmodulocod'] = $pagmodulocod;
			if (!parent::ModificarOrden($datosmodif))
					return false;
			$datosmodif['moduloorden']++;
		}
		if (!$this->GenerarColumnas($datos))
			return false;
		return true;
	}
	
	

	
	
//----------------------------------------------------------------------------------------- 
// Retorna true o false

// Parámetros de Entrada:
 		 

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no
	private function _ValidarDatosVacios($datos)
	{
		return true;
	}

	
//----------------------------------------------------------------------------------------- 
// Retorna true o false

// Parámetros de Entrada:
 		 

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no
	private function _ValidarDatosAlta($datos)
	{
		if (!$this->_ValidarDatosVacios($datos))
			return false;


		return true;
	}

//----------------------

// Parámetros de Entrada:
	 

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	private function _ValidarDatosModificar($datos)
	{
		if (!$this->_ValidarDatosVacios($datos))
			return false;

		return true;
	}


//----------------------------------------------------------------------------------------- 


// Parámetros de Entrada:


// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no
	private function _ValidarDatosEliminar($datos)
	{
		return true;
	}



//----------------------------------------------------------------------------------------- 
// Retorna proxorden. proximo orden del modulo pagina

// Parámetros de Entrada:


// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no	
	
	private function ObtenerProximoOrden($datos,&$proxorden)
	{
		$proxorden = 0;
		if (!parent::BuscarModuloPaginaUltimoOrden($datos,$resultado,$numfilas))
			return false;
			
		if ($numfilas!=0)
		{
			$datos = $this->conexion->ObtenerSiguienteRegistro($resultado);
			$proxorden = $datos['maximo'] + 1;
		}
		return true;
	}
	

}// FIN CLASE

?>