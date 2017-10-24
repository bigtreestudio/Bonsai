<?php 
/*
CLASE LOGICA PARA EL MANEJO DE LAS ACCIONES DE LAS NOTICIAS.
*/
include(DIR_CLASES_DB."cNoticiasAcciones.db.php");

class cNoticiasAcciones extends cNoticiasAccionesdb	
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

	function ObtenerAccionesPermitidasxRol($datos,&$resultado,&$numfilas)
	{
		if (!parent::ObtenerAccionesPermitidasxRol($datos,$resultado,$numfilas))
			return false;
		
		return true;
	}

//----------------------------------------------------------------------------------------- 
// Retorna todas las acciones noticias.

// Parámetros de Entrada:
//		datos: arreglo de datos


// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no

	function ObtenerAcciones($datos,&$resultado,&$numfilas)
	{
		if (!parent::ObtenerAcciones($datos,$resultado,$numfilas))
			return false;
		
		return true;
	}
	
//----------------------------------------------------------------------------------------- 
// Retorna las acciones que tiene un usuario
// Parámetros de Entrada:
//		datos: arreglo de datos
//		usuariocod: codigo de usuario


// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no

	function ObtenerAccionesxUsuariocod($datos,&$resultado,&$numfilas)
	{
		if (!parent::ObtenerAccionesxUsuariocod($datos,$resultado,$numfilas))
			return false;
		
		return true;
	}	


//----------------------------------------------------------------------------------------- 
// Actualiza las acciones que tiene un usuario
// Parámetros de Entrada:
//		datos: arreglo de datos
//		usuariocod: codigo de usuario


// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function ActualizarAccionesUsuario($datos)
	{
		//array de acciones a asignar
		if (!$this->ObtenerDatosCheckAcciones($datos,$arrayfinal))
			return false;
	
		
		
		if (!$this->ObtenerAccionesxUsuariocod($datos,$resultadoacciones,$numfilas))
			return false;	

				
		if (!$this->ObtenerAcciones($datos,$resultado,$numfilas))
			return false;
			
		
		
		$arregloacciones= array();
		while ($fila = $this->conexion->ObtenerSiguienteRegistro($resultado))
			$arregloacciones[] = $fila['noticiaaccioncod'];
		

		$arrayinicial = array();
		while ($fila = $this->conexion->ObtenerSiguienteRegistro($resultadoacciones))
		{	
			if (in_array($fila['noticiaaccioncod'],$arregloacciones))
				$arrayinicial[] = $fila['noticiaaccioncod'];
		}
		
		$arraysacar = array_diff($arrayinicial,$arrayfinal);
		$arrayponer = array_diff($arrayfinal,$arrayinicial);

		$datosinsertar['usuariocod'] = $datos['usuariocod'];
		foreach($arrayponer as $noticiaaccioncod)
		{
		

			$datosinsertar['noticiaaccioncod'] = $noticiaaccioncod;
			if (!$this->AltaUsuarioAccion($datosinsertar))
				return false;
		}
		
		$datoseliminar['usuariocod'] = $datos['usuariocod'];
		foreach($arraysacar as $noticiaaccioncod)
		{
			$datoseliminar['noticiaaccioncod'] = $noticiaaccioncod;
			if (!$this->BajaUsuarioAccion($datoseliminar))
				return false;
		}

		return true;
	}
	
	public function AltaUsuarioAccion($datos)
	{
		if (!parent::AltaUsuarioAccion($datos))
			return false;
		
		return true;
	}
	
	public function BajaUsuarioAccion($datos)
	{

		if (!parent::BajaUsuarioAccion($datos))
			return false;
		
		return true;

	}	
//----------------------------------------------------------------------------------------- 
// Retorna si tiene acciones cheuqadas
// Parámetros de Entrada:
//		datos: arreglo de datos
//		usuariocod: codigo de usuario


// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no


	public function ObtenerDatosCheckAcciones($datos,&$arrayfinal)
	{
		
		$arrayfinal=array();
		foreach ($datos as $nombre_var => $valor_var) {
			if (empty($valor_var)) {
				$vacio[$nombre_var] = $valor_var;
			} else {
				
				$post[$nombre_var] = $valor_var;
				$opcion = substr($nombre_var,0,17);
				if ($opcion=="noticiaaccioncod_")
				{
					$arrayfinal[] = $valor_var;
				}
			}
		}

		return true;
	}

}//fin clase	

?>