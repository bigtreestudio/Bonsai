<?php  
include(DIR_CLASES_DB."cTapasMenuPublicadas.db.php");

class cTapasMenuPublicadas extends cTapasMenuPublicadasdb	
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
//	datos: Array asociativo de datos
//		menutipocod: Tipo del menú
//

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no


	public function Publicar($datos)
	{
		$oMenuTipo = new cTapasMenuTipos($this->conexion,$this->formato);
		if(!$oMenuTipo->BuscarxCodigo($datos,$resultado,$numfilas))
			return false;
		if ($numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Error, tipo de menu inexistente. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}	
		
		if (!parent::Eliminar($datos))
			return false;

		$oMenu = new cTapasMenu($this->conexion,$this->formato);
		if(!$oMenu->BuscarxTipo($datos,$resultado,$numfilas))
			return false;

		while ($fila = $this->conexion->ObtenerSiguienteRegistro($resultado))
		{
			if ($fila['menucodsup']=="")
				$fila['menucodsup'] = "NULL";
			if (!parent::Insertar($fila))
				return false;
		}
		
		return true;
	}

	
}//FIN CLASS
?>