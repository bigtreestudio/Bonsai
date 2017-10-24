<?php 
/*
CLASE LOGICA PARA EL MANEJO DE LAS NOTICIAS.
*/
include(DIR_CLASES_DB."cNoticiasPermisos.db.php");

class cNoticiasPermisos extends cNoticiasPermisosdb	
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
// Retorna los estados a los cuales tiene permiso un usuario

// Parámetros de Entrada:
//		datos: arreglo de datos
//			usuariocod = codigo del usuario

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no

	function ObtenerEstadosxUsuario($datos,&$resultado,&$numfilas)
	{
		if (!parent::ObtenerEstadosxUsuario($datos,$resultado,$numfilas))
			return false;
		
		return true;
	}

//----------------------------------------------------------------------------------------- 
// Retorna los estados a los cuales tiene permiso un usuario

// Parámetros de Entrada:
//		datos: arreglo de datos
//			usuariocod = codigo del usuario

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no

	function PuedeEditarNoticiaxEstado($datos,&$resultado,&$numfilas)
	{
		if (!parent::PuedeEditarNoticiaxEstado($datos,$resultado,$numfilas))
			return false;		
		
		return true;
	}


//----------------------------------------------------------------------------------------- 
// Retorna los estados a los cuales tiene permiso un usuario

// Parámetros de Entrada:
//		datos: arreglo de datos
//			usuariocod = codigo del usuario

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no

	function PuedeEditarNoticia($datos,&$resultado,&$numfilas)
	{

		$oNoticias = new cNoticias($this->conexion);
		if (!$oNoticias->BuscarxCodigo($datos,$resultado,$numfilas))
			return false;
		
		$datosvalidar =$this->conexion->ObtenerSiguienteRegistro($resultado);

		if($datosvalidar['noticiaestadocod']==NOTPUBLICADA)
			return false;
		
		$datosvalidar['usuariocod']=$_SESSION['usuariocod'];
		if (!$this->PuedeEditarNoticiaxEstado($datosvalidar,$resultadoest,$numfilasest))
			return false;

		if($numfilasest==0)
			return false;
		
		return true;
	}
	
	
//----------------------------------------------------------------------------------------- 
// Retorna si un usuario puede o no bajar a edición una noticia publicada

// Parámetros de Entrada:
//		datos: arreglo de datos
//			usuariocod = codigo del usuario
//			noticiacod = codigo de la noticia

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function PuedeBajaraEdicion($datos)
	{
		$oNoticias = new cNoticias($this->conexion, $this->formato);
		if(!$oNoticias->BuscarxCodigo($datos,$resultado,$numfilas))
			return false;
			
		$datosnoticia = $this->conexion->ObtenerSiguienteRegistro($resultado);
		if ($datosnoticia['noticiaestadocod']==NOTPUBLICADA && $datosnoticia['noticiacopiacod']=="")
			return true;

		
		return false;	
	}
	
	
}//fin clase	

?>