<?php  
include(DIR_CLASES_DB."cTapasZonasModulos.db.php");

class cTapasZonasModulos extends cTapasZonasModulosdb	
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

// Trae los modulos de una tapa y un like de modulosdata

// Parámetros de Entrada:
//		datos: arreglo de datos
//			tapacod = codigo de la Tapa
//			modulosdata = Ej. "noticiacod"

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	public function BuscarModulosxModuloDataxTapa($datos,&$resultado,&$numfilas)
	{
		if (!parent::BuscarModulosxModuloDataxTapa ($datos,$resultado,$numfilas))
			return false;
		
		return true;			

	}
//----------------------------------------------------------------------------------------- 
// Trae los modulos de una zona y una tapa

// Parámetros de Entrada:
//		datos: arreglo de datos
//			tapacod = codigo de la Tapa
//			zonacod = codigo de la Zona

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
   public function ArmarArregloModuloData($tapacod, $campo,$modulodatalike)
	{
		$arreglo = array();
		$datosBusqueda['tapacod'] = $tapacod;
		$datosBusqueda['modulodata'] = $modulodatalike;
		$this->BuscarModulosxModuloDataxTapa($datosBusqueda,$resultadoTapas,$numfilasTapas);
		while($filaDatos = $this->conexion->ObtenerSiguienteRegistro($resultadoTapas))
		{
			$datosJson = json_decode($filaDatos['modulodata']);
			if (is_object($datosJson->$campo))
				foreach ($datosJson->$campo as $campo)
					$arreglo[$campo] = $campo;
			else
				if($datosJson->$campo!="")
					$arreglo[$datosJson->$campo] = $datosJson->$campo;
			
		}
		return $arreglo;
	}


// Trae los modulos de una zona y una tapa

// Parámetros de Entrada:
//		datos: arreglo de datos
//			tapacod = codigo de la Tapa
//			zonacod = codigo de la Zona

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	public function BuscarModulosxZonaxTapa($datos,&$resultado,&$numfilas)
	{
		if (!parent::BuscarModulosxZonaxTapa ($datos,$resultado,$numfilas))
			return false;
		return true;			
	}

//----------------------------------------------------------------------------------------- 
// Trae los datos del modulo

// Parámetros de Entrada:
//		datos: arreglo de datos
//			zonamodulocod = codigo del modulo

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	public function BuscarModuloxCodigo($datos,&$resultado,&$numfilas)
	{
		if (!parent::BuscarModuloxCodigo ($datos,$resultado,$numfilas))
			return false;
		return true;			
	}

//----------------------------------------------------------------------------------------- 
// Trae los datos del modulo

// Parámetros de Entrada:
//		datos: arreglo de datos
//			modulocod = codigo del modulo

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	public function BuscarModuloxCodigoModulo($datos,&$resultado,&$numfilas)
	{
		if (!parent::BuscarModuloxCodigoModulo ($datos,$resultado,$numfilas))
			return false;
		return true;			
	}

//----------------------------------------------------------------------------------------- 
// Inserta un modulo de los temporales

// Parámetros de Entrada:
//		datos: arreglo de datos
//			datos= datos a insertar

// Retorna:

	
	public function Insertar($datos,&$codigoinsertado)
	{
		if (!parent::Insertar ($datos,$codigoinsertado))
			return false;
		
		return true;			
	}
//----------------------------------------------------------------------------------------- 
// Trae los datos del modulo

// Parámetros de Entrada:
//		datos: arreglo de datos
//			datos= datos a modificar

// Retorna:

	
	public function Modificar($datos)
	{
		$datos['modulodata'] = json_encode($datos);
		if (!parent::Modificar ($datos))
			return false;
		return true;			
	}


	public function ModificarBloqueoZona($datos)
	{
		
		$oUsuariosModulosAcciones = new cUsuariosModulosAcciones($this->conexion,$this->formato);
		$puedeBloquear = $oUsuariosModulosAcciones->TienePermisosAccion("000610");
		
		if ($puedeBloquear)
		{
			$datos['modulobloqueado'] = ($datos['bloqueo']==1)?1:0;
			if (!parent::ModificarBloqueoZona ($datos))
				return false;
		}else
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"No tiene permisos para bloquear y desbloquear modulos",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			die();
		}
		return true;			
	}


//----------------------------------------------------------------------------------------- 
// Trae los datos del modulo a modificar, modifica o crear un dato si existe y despues guarda

// Parámetros de Entrada:
//		datos: arreglo de datos
//			datos= datos a modificar en json

// Retorna:

	
	public function ModificarAgregarParametrosExtras($datos)
	{
		if(!$this->BuscarModuloxCodigo($datos,$resultado,$numfilas))
			return false;
			
		if ($numfilas>0)
		{	
			$datosmodulo = $this->conexion->ObtenerSiguienteRegistro($resultado);
			
			$datosencontrados = json_decode($datosmodulo['modulodata']);
			foreach ($datos as $clave=>$datos_post)
			{
				$datosencontrados->$clave = $datos_post;
			}
			$datosmodulo['modulodata'] = json_encode($datosencontrados);
			if (!parent::Modificar ($datosmodulo))
				return false;
		}
		return true;			
	}


//----------------------------------------------------------------------------------------- 
// Trae los datos del modulo

// Parámetros de Entrada:
//		datos: arreglo de datos
//			datos= datos a modificar

// Retorna:

	
	public function ModificarOrdenZona($datos)
	{
		$datosinsertar['tapacod'] = $datos['tapacod'];
		$datosinsertar['zonacod'] = $datos['zonacod'];
		if (isset($datos['width']) && $datos['width']!="")
			$datosinsertar['width'] = $datos['width'];
		else
			$datosinsertar['width'] = "NULL";	
		$moduloorden = 1;
		if (isset($datos['module']) && count($datos['module'])>0)
		{
			foreach ($datos['module'] as $zonamodulocod)
			{
				$datosinsertar['zonamodulocod'] = $zonamodulocod;
				$datosinsertar['moduloorden'] = $moduloorden;
				if (!parent::ModificarOrdenZona ($datosinsertar))
					return false;
				$moduloorden++;	
			}
		}
		return true;			
	}

//----------------------------------------------------------------------------------------- 
// Eliminar un modulo

// Parámetros de Entrada:
//		datos: arreglo de datos
//			zonamodulocod= zonamodulocod

// Retorna:

	
	public function Eliminar($datos)
	{
		if (!parent::Eliminar ($datos))
			return false;
		return true;			
	}
//----------------------------------------------------------------------------------------- 
// Inserta el modulo en una zona

// Parámetros de Entrada:
//		datos: arreglo de datos
//			zonamodulocod= zonamodulocod
//			modulotmpcod= modulotmpcod

// Retorna:

	
	public function InsertarModuloEnZona($datos,&$codigoinsertado)
	{
		
		$oTapasZonasModulosTmp = new cTapasZonasModulosTmp($this->conexion);

		if (!$oTapasZonasModulosTmp->BuscarModulosTmpxModuloTmpcod($datos,$resultado,$numfilas))
			return false;
		if($numfilas<1)
			return false;
			
		if(!$this->BuscarModulosxZonaxTapa($datos,$resultadoModulos,$numfilasModulos))
			return false;
			
		$datosmodif['tapacod'] = $datos['tapacod'];
		$datosmodif['zonacod'] = $datos['zonacod'];
		$datosmodif['moduloorden'] = 1;
		
		if (isset($datos['width']) && $datos['width']!="")
			$datosmodif['width'] = $datos['width'];
		else
			$datosmodif['width'] = "NULL";	
			
		while($filaDatos = $this->conexion->ObtenerSiguienteRegistro($resultadoModulos))
		{
			if ($datosmodif['moduloorden']==$datos['moduloorden'])
				$datosmodif['moduloorden']++;

			$datosmodif['zonamodulocod'] = $filaDatos['zonamodulocod'];
			if (!parent::ModificarOrdenZona ($datosmodif))
				return false;
			$datosmodif['moduloorden']++;
		}
		$datosinsertar = $this->conexion->ObtenerSiguienteRegistro($resultado);
		if (isset($datos['width']) && $datos['width']!="")
			$datosinsertar['width'] = $datos['width'];
		else
			$datosinsertar['width'] = "NULL";	
		$datosinsertar['zonacod']=$datos['zonacod'];
		$datosinsertar['moduloorden']=$datos['moduloorden'];
		if (!$this->Insertar($datosinsertar,$codigoinsertado))
			return false;
		
		if (!$oTapasZonasModulosTmp->Eliminar($datos))
			return false;
			

		return true;			
	}
}
?>