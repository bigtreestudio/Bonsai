<?php 
/*
CLASE LOGICA PARA EL MANEJO DE LOS ARCHIVOS MULTIMEDIA.
*/
include(DIR_CLASES_DB."cMultimediaFormatos.db.php");

class cMultimediaFormatos extends cMultimediaFormatosdb
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

// Parámetros de Entrada:
//		datos: arreglo de datos
//			formatocod = codigo del formato

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function BuscarMultimadiaFormatoxCodigo($datos,&$resultado,&$numfilas)
	{
		if (!parent::BuscarMultimadiaFormatoxCodigo ($datos,$resultado,$numfilas))
			return false;
		return true;	
	}
	
// Parámetros de Entrada:
//		datos: arreglo de datos
//			 = 

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function BuscarMultimadiaFormatosSP(&$spnombre,&$sparam)
	{
		if (!parent::BuscarMultimadiaFormatosSP ($spnombre,$sparam))
			return false;
		return true;	
	}	
	
	
	
//----------------------------------------------------------------------------------------- 
// FUNCION QUE RETORMA TODOS LOS FORMATOS ACTIVOS
// Parámetros de Entrada:
//		datos: arreglo de datos
//			formatocod = codigo del formato

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function BuscarMultimediaFormatosActivos(&$resultado,&$numfilas)
	{
		$datos['formatoestado'] = ACTIVO;
		if (!parent::BuscarMultimadiaFormatoxEstado ($datos,$resultado,$numfilas))
			return false;
		return true;	
	}
	
	
//------------------------------------------------------------------------------------------	
// Retorna en un arreglo con los datos de un formato multimedia

// Parámetros de Entrada:

// Retorna:
//		resultado= Arreglo con todos los datos de un formato multimedia.
//		numfilas= cantidad de filas 
//		la función retorna true o false si se pudo ejecutar con éxito o no	
	function BusquedaAvanzada ($datos,&$resultado,&$numfilas)
	{

		$sparam=array(
			'xformatodesc'=> 0,
			'formatodesc'=> "",
			'xformatoancho'=> 0,
			'formatoancho'=> "",
			'xformatoalto'=> 0,
			'formatoalto'=> "",
			'xformatocarpeta'=> 0,
			'formatocarpeta'=> "",
			'orderby'=> "formatodesc ASC",
			'limit'=> ""
		);	
			
		if (isset ($datos['formatodescbusqueda']) && $datos['formatodescbusqueda']!="")
		{
			$sparam['formatodesc']= $datos['formatodescbusqueda'];
			$sparam['xformatodesc']= 1;
		}	
		if (isset ($datos['formatoanchobusqueda']) && $datos['formatoanchobusqueda']!="")
		{
			$sparam['formatoancho']= $datos['formatoanchobusqueda'];
			$sparam['xformatoancho']= 1;
		}
		if (isset ($datos['formatoaltobusqueda']) && $datos['formatoaltobusqueda']!="")
		{
			$sparam['formatoalto']= $datos['formatoaltobusqueda'];
			$sparam['xformatoalto']= 1;
		}
		if (isset ($datos['formatocarpetabusqueda']) && $datos['formatocarpetabusqueda']!="")
		{
			$sparam['formatocarpeta']= $datos['formatocarpetabusqueda'];
			$sparam['xformatocarpeta']= 1;
		}

		if (isset ($datos['orderby']) && $datos['orderby']!="")
			$sparam['orderby']= $datos['orderby'];
	
		if (isset ($datos['limit']) && $datos['limit']!="")
			$sparam['limit']= $datos['limit'];
	
		if (!parent::BusquedaAvanzada($sparam,$resultado,$numfilas))
			return false;

		return true;
	}

//----------------------------------------------------------------------------------------- 
// Inserta nuevo formato

// Parámetros de Entrada:
//			formatodesc: descripción del formato
//			formatoancho: ancho del formato
//			formatoalto: alto del formato
//			formatocarpeta: formato de la carpeta
//			formatocropea: si se cropea el formato vale 1 si no vale 0	
// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	public function Insertar($datos,&$codigoinsertado)
	{
		if (!$this->_ValidarDatosAlta($datos))
			return false;
		
		$datos['formatoestado']= ACTIVO;
		if(!parent::Insertar($datos,$codigoinsertado))
			return false;
			
		$oMultimediaCategorias = new cMultimediaCategorias($this->conexion,$this->formato);
		if(!$oMultimediaCategorias->BuscarMultimediaCategorias($resultado,$numfilas))
			return false;
			
		while ($datoscategoria = $this->conexion->ObtenerSiguienteRegistro($resultado))
		{	
			$carpeta = CARPETA_SERVIDOR_MULTIMEDIA_FISICA.$datoscategoria['multimediacatcarpeta'].$datos['formatocarpeta'];
			if (!is_dir ($carpeta))
			{
				if (!mkdir($carpeta))
				{
					FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al crear la carpeta en el servidor de multimedia (".$datoscategoria['multimediacatcarpeta']."".$datos['formatocarpeta'].") ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
					return false;
				}
			}
			
		}
		
		return true;
	} 

//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 
// Modifica los datos de un formato

// Parámetros de Entrada:
//			formatodesc: descripción del formato
//			formatoancho: ancho del formato
//			formatoalto: alto del formato
//			formatocarpeta: formato de la carpeta
//			formatocropea: si se cropea el formato vale 1 si no vale 0	
// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	public function Modificar($datos)
	{
		if (!$this->_ValidarDatosModificar($datos))
			return false;
		
		if(!parent::Modificar($datos))
			return false;
		return true;
	} 
	
//----------------------------------------------------------------------------------------- 

// Eliminar un formato multimedia
// Parámetros de Entrada:
//		datos: arreglo de datos
//			formatocod = codigo del formato

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function Eliminar($datos)
	{	
		if (!$this->_ValidarEliminarDatos($datos,$datosdevueltos))
			return false;
			
		if(!parent::Eliminar($datos))
			return false;

		$oMultimediaCategorias = new cMultimediaCategorias($this->conexion,$this->formato);
		if(!$oMultimediaCategorias->BuscarMultimediaCategorias($resultado,$numfilas))
			return false;
			
		while ($datoscategoria = $this->conexion->ObtenerSiguienteRegistro($resultado))
		{	
			$carpeta = CARPETA_SERVIDOR_MULTIMEDIA_FISICA.$datoscategoria['multimediacatcarpeta'].$datosdevueltos['formatocarpeta'];
			if (is_dir ($carpeta))
			{
				if (($files = @scandir($carpeta)) && count($files) <= 2) {
					if (!rmdir($carpeta))
					{
						FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al eliminar la carpeta en el servidor de multimedia (".$datoscategoria['multimediacatcarpeta']."".$datosdevueltos['formatocarpeta'].") ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
						return false;
					}
				}else
				{
					FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"La carpeta (".$datoscategoria['multimediacatcarpeta']."".$datosdevueltos['formatocarpeta'].") no se encuentra vacia",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
					return false;
				}
				
			}
			
		}
		
		return true;	
	}	
	
//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 
// Activar/Desactivar  de un banner cambiando el estado (ACTIVO/NOACTIVO)

// Parámetros de Entrada:
//		datos: arreglo de datos
//			bannercod = codigo del banner

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no	
	public function ActivarDesactivar ($datos)
	{
		
		if (!$this->_ValidarActivarDesactivar($datos))
			return false;
	
		if (!parent::ActivarDesactivar($datos))
			return false;
		
		return true;
	}

//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 

// Activar un formato cambiando el estado
// Parámetros de Entrada:
//		datos: arreglo de datos
//			formatocod = codigo del banner
// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function Activar($datos)
	{
		$datos['formatoestado'] = ACTIVO;
		if (!$this->ActivarDesactivar($datos))
			return false;
	
		return true;	
	} 
//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 

// Desactivar un formato cambiando el estado

// Parámetros de Entrada:
//		datos: arreglo de datos
//			formatocod = codigo del formato

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no
	public function DesActivar($datos)
	{
		$datos['formatoestado'] = NOACTIVO;
		if (!$this->ActivarDesactivar($datos))
			return false;
	
		return true;	
	} 

    
//-----------------------------------------------------------------------------------------
//							 PRIVADAS	
//----------------------------------------------------------------------------------------- 

//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 
// Retorna true o false si algunos de los campos esta vacio

// Parámetros de Entrada:
//			formatodesc: descripción del formato
//			formatoancho: ancho del formato
//			formatoalto: alto del formato
//			formatocarpeta: formato de la carpeta
//			formatocropea: si se cropea el formato vale 1 si no vale 0	 

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function _ValidarDatosVacios($datos)
	{
		
		if (isset($datos['formatodesc']) && $datos['formatodesc']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar una descripción.  ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		if (isset($datos['formatoancho']) && $datos['formatoancho']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar un ancho del formato. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		if (!FuncionesPHPLocal::ValidarContenido($this->conexion,$datos['formatoancho'],"NumericoEntero"))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar un ancho valido (solo número).",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;
		}
		if (isset($datos['formatoalto']) && $datos['formatoalto']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar un alto del formato. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		if (!FuncionesPHPLocal::ValidarContenido($this->conexion,$datos['formatoalto'],"NumericoEntero"))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar un alto valido (solo número).",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		if (isset($datos['formatocarpeta']) && $datos['formatocarpeta']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar un formato de carpeta. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		if (isset($datos['formatocrop']) && $datos['formatocrop']!="1" && $datos['formatocrop']!="0" )
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, tipo de cropeo incorrecto. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		return true;
	}

//----------------------------------------------------------------------------------------- 
// Retorna true o false al dar de alta si algunos de los campos esta vacio o si exite otro album con ese nombre

// Parámetros de Entrada:
//		formatodesc: descripción del formato
//			formatoancho: ancho del formato
//			formatoalto: alto del formato
//			formatocarpeta: formato de la carpeta
//			formatocropea: si se cropea el formato vale 1 si no vale 0	 	 

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no
	function _ValidarDatosAlta($datos)
	{
		if (!$this->_ValidarDatosVacios($datos))
			return false;

		return true;
	}

//----------------------------------------------------------------------------------------- 
// Retorna true o false al modificar si algunos de los campos esta vacio o si exite otro album con ese nombre

// Parámetros de Entrada:
//		    formatodesc: descripción del formato
//			formatoancho: ancho del formato
//			formatoalto: alto del formato
//			formatocarpeta: formato de la carpeta
//			formatocropea: si se cropea el formato vale 1 si no vale 0	 	 

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function _ValidarDatosModificar($datos)
	{
		if (!$this->_ValidarDatosVacios($datos))
			return false;

		return true;
	}
//----------------------------------------------------------------------------------------- 
// Retorna true o false al validar el activar o desactivar el formato

// Parámetros de Entrada:
//		    formatocod: código del formato
// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no	

	private function _ValidarActivarDesactivar($datos)
	{
		
		if (!$this->BuscarMultimadiaFormatoxCodigo($datos,$resultado,$numfilas))
			return false;
	
		if ($numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error formato inexistente.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;
		}
			
		return true;
	}
	
//----------------------------------------------------------------------------------------- 
// Retorna true o false al validar el activar o desactivar el formato

// Parámetros de Entrada:
//		    formatocod: código del formato
// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no	

	private function _ValidarEliminarDatos($datos,&$datosdevueltos)
	{
		
		if (!$this->BuscarMultimadiaFormatoxCodigo($datos,$resultado,$numfilas))
			return false;
	
		if ($numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error formato inexistente.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;
		}
		$datosdevueltos = $this->conexion->ObtenerSiguienteRegistro($resultado);
			
		return true;
	}	

}//fin clase	

?>