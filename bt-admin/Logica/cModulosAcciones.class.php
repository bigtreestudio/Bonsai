<?php 
//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 
// Clase con la lógica para el manejo de roles

include(DIR_CLASES_DB."cModulosAcciones.db.php");

class cModulosAcciones extends cModulosAccionesdb	
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
// Retorna una consulta con todos los usuarios que cumplan con las condiciones

// Parámetros de Entrada:
//		ArregloDatos: array asociativo con los filtros. Claves: usuarionombre, usuarioapellido, usuariocuit, usuarioemail

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function PuedeEditarTodasAcciones()
	{
		switch($_SESSION['rolcod'])	
		{
			case ADMISITE:
			case ADMIN:
				return true;	
			break;
		}
		return false;
	}


	public function BuscarModulosAccionesxUsuarioxRolcodActualiza($datos,&$resultado,&$numfilas)
	{
		if (!parent::BuscarModulosAccionesxUsuarioxRolcodActualiza($datos,$resultado,$numfilas))
			return false;

		return true;
	}


	public function BuscarAccionesxUsuarioxRolcodActualiza($datos,&$resultado,&$numfilas)
	{
		if (!parent::BuscarAccionesxUsuarioxRolcodActualiza($datos,$resultado,$numfilas))
			return false;

		return true;
	}


	public function BuscarAccionesxModulos($datos,&$resultado,&$numfilas)
	{
		$datos['xusuariocod']=0;
		if (isset($datos['usuariocod']) && $datos['usuariocod']!="")
			$datos['xusuariocod']=1;

		if (!parent::BuscarAccionesxModulos($datos,$resultado,$numfilas))
			return false;
		
		return true;	
	}



}

?>