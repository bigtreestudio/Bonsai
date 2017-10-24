<?php 
/*
CLASE LOGICA PARA EL MANEJO DE LOS ARCHIVOS MULTIMEDIA.
*/
include(DIR_CLASES_DB."cRevistaTapasMultimedia.db.php");

class cRevistaTapasMultimedia extends cRevistaTapasMultimediadb
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
//			convocatoriacod = codigo de la convocatoria

// Retorna:
//		numfilas,resultado: cantidad de filas y query de resultado
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function BuscarxCodigo($datos,&$resultado,&$numfilas)
	{
		if (!parent::BuscarxCodigo ($datos,$resultado,$numfilas))
			return false;
		return true;	
	}
	
	
	public function BuscarxCodigoRevista($datos,&$resultado,&$numfilas)
	{
		if (!parent::BuscarxCodigoRevista ($datos,$resultado,$numfilas))
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
			'xrevtapacod'=> 0,
			'revtapacod'=> "",
			'xrevtapamuldescripcion'=> 0,
			'revtapamuldescripcion'=> "",
			'xrevtapamulestado'=> 0,
			'revtapamulestado'=> "",
			'orderby'=> "revtapamulorden ASC",
			'limit'=> ""
			);	

		if (isset ($datos['revtapacod']) && $datos['revtapacod']!="")
		{
			$sparam['revtapacod']= $datos['revtapacod'];
			$sparam['xrevtapacod']= 1;
		}	
		if (isset ($datos['revtapamuldescripcion']) && $datos['revtapamuldescripcion']!="")
		{
			$sparam['revtapamuldescripcion']= $datos['revtapamuldescripcion'];
			$sparam['xrevtapamuldescripcion']= 1;
		}
		if (isset ($datos['revtapamulestado']) && $datos['revtapamulestado']!="")
		{
			$sparam['revtapamulestado']= $datos['revtapamulestado'];
			$sparam['xrevtapamulestado']= 1;
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
// Inserta nueva convocatoria

// Parámetros de Entrada:
//			convocatoriadesc: descripción de la convocatoria
//			convocatoriaestado: estado de la convocatoria
// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no
	
	public function Insertar($datos,&$codigoinsertado)
	{
		$datos['revtapamuldescripcion']="";	
		$datos['revtapamulestado']= ACTIVO;
		$datos['revtapamulubic']= $datos['revtapamulubic'];
	
		$this->ObtenerProximoOrden($datos,$proxorden);	
		$datos['revtapamulorden']= $proxorden;
	
		if(!parent::Insertar($datos,$codigoinsertado))
			return false;
		
		
		$input = fopen($datos['ubicacionfisica'], "r");
		$temp = tmpfile();
		$realSize = stream_copy_to_stream($input, $temp);
		fclose($input);		
		

		$carpetaFecha = date("Ym")."/";
		$nombrearchivo=$datos['revtapamulubic'];
		
		if(!is_dir(CARPETA_SERVIDOR_MULTIMEDIA_FISICA."tapas/".CARPETA_SERVIDOR_MULTIMEDIA_TAPAS)){ 
			@mkdir(CARPETA_SERVIDOR_MULTIMEDIA_FISICA."tapas/".CARPETA_SERVIDOR_MULTIMEDIA_TAPAS);
		}
		$target = fopen(CARPETA_SERVIDOR_MULTIMEDIA_FISICA."tapas/".CARPETA_SERVIDOR_MULTIMEDIA_TAPAS.$nombrearchivo, "w");        
		fseek($temp, 0, SEEK_SET);
		stream_copy_to_stream($temp, $target);
		fclose($target);



		//Redimensiona la imagen al tamaño de la img normal
		$oImagen = new cFuncionesMultimedia();
		$carpetalocal = CARPETA_SERVIDOR_MULTIMEDIA_FISICA."tapas/".CARPETA_SERVIDOR_MULTIMEDIA_TAPAS;
		$carpetadestino = CARPETA_SERVIDOR_MULTIMEDIA_FISICA."tapas/".CARPETA_SERVIDOR_MULTIMEDIA_TAPAS;
		if(!$oImagen->RedimensionarImagen($nombrearchivo,$carpetalocal,$carpetadestino,TAMANIONORMAL,TAMANIONORMAL))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al generar las imagenes.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		
		//Subir imagenes
		$oImagen = new cFuncionesMultimedia();
		$carpetalocal = CARPETA_SERVIDOR_MULTIMEDIA_FISICA."tapas/".CARPETA_SERVIDOR_MULTIMEDIA_TAPAS;
		$carpetadestino = CARPETA_SERVIDOR_MULTIMEDIA_FISICA."tapas/".CARPETA_SERVIDOR_MULTIMEDIA_TAPAS_THUMBS;
		if(!$oImagen->RedimensionarImagen($nombrearchivo,$carpetalocal,$carpetadestino,TAMANIOTHUMB,TAMANIOTHUMB))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al generar las imagenes.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	} 

//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 
// Modifica los datos de una convocatoria

// Parámetros de Entrada:
//			convocatoriacod = codigo de la convocatoria
//			convocatoriadesc: descripción de la convocatoria
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

// Eliminar una convocatoria
// Parámetros de Entrada:
//		datos: arreglo de datos
//			convocatoriacod = codigo de la convocatoria

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function Eliminar($datos)
	{	
		if (!$this->BuscarxCodigo($datos,$resultado,$numfilas))
			return false;
	
		if ($numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, tapa inexistente.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;
		}
		$datostapamul = $this->conexion->ObtenerSiguienteRegistro($resultado);
		$carpetaorigen = CARPETA_SERVIDOR_MULTIMEDIA_FISICA."/tapas/".CARPETA_SERVIDOR_MULTIMEDIA_TAPAS.$datostapamul['revtapamulubic'];
		
		@unlink($carpetaorigen);	
		if(!parent::Eliminar($datos))
			return false;
			
		return true;	
	}	

	public function EliminarMultimedias($datos)
	{	
		if (!$this->BuscarxCodigoRevista($datos,$resultado,$numfilas))
			return false;
	
		if ($numfilas>0)
		{

			while ($datostapamul = $this->conexion->ObtenerSiguienteRegistro($resultado)){
				$carpetaorigen = CARPETA_SERVIDOR_MULTIMEDIA_FISICA."/tapas/".CARPETA_SERVIDOR_MULTIMEDIA_TAPAS.$datostapamul['revtapamulubic'];
				
				@unlink($carpetaorigen);	
				if(!parent::Eliminar($datostapamul))
					return false;
			}
		}
		
		
		return true;	
	}	

//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 
// Activar/Desactivar  de una convocatoria cambiando el estado (ACTIVO/NOACTIVO)

// Parámetros de Entrada:
//		datos: arreglo de datos
//			convocatoriacod = codigo de la convocatoria
//			convocatoriaestado: estado de la convocatoria

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

// Activar una convocatoria cambiando el estado
// Parámetros de Entrada:
//		datos: arreglo de datos
//			convocatoriacod = codigo de la convocatoria
//			convocatoriaestado: estado de la convocatoria

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no	

	public function Activar($datos)
	{
		$datos['revtapamulestado'] = ACTIVO;
		if (!$this->ActivarDesactivar($datos))
			return false;
			
	
		return true;	
	} 
//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 

// Desactivar una convocatoria cambiando el estado

// Parámetros de Entrada:
//		datos: arreglo de datos
//			convocatoriacod = codigo de la convocatoria
//			convocatoriaestado: estado de la convocatoria

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no
	public function DesActivar($datos)
	{
		$datos['revtapamulestado'] = NOACTIVO;
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
//			convocatoriadesc: descripción de la convocatoria

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function _ValidarDatosVacios($datos)
	{
		/*if (isset($datos['revtapamuldescripcion']) && $datos['revtapamuldescripcion']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, debe ingresar una descripción de la tapa.  ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}*/
		return true;
	}



//----------------------------------------------------------------------------------------- 
// Retorna true o false al dar de alta si algunos de los campos esta vacio o si exite otro album con ese nombre

// Parámetros de Entrada:
//			convocatoriadesc: descripción de la convocatoria

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
//			convocatoriacod = codigo de la convocatoria
//			convocatoriaestado: estado de la convocatoria

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function _ValidarDatosModificar($datos)
	{
		if (!$this->_ValidarDatosVacios($datos))
			return false;
			
		if (!$this->BuscarxCodigo($datos,$resultado,$numfilas))
			return false;
	
		if ($numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, tapa inexistente.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;
		}
			
		return true;
	}	
			
//----------------------------------------------------------------------------------------- 
// Retorna true o false al validar el activar o desactivar el formato

// Parámetros de Entrada:
//			convocatoriacod = codigo de la convocatoria
// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no	

	private function _ValidarActivarDesactivar($datos)
	{
		
		if (!$this->BuscarxCodigo($datos,$resultado,$numfilas))
			return false;
	
		if ($numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error, tapa de la revista inexistente.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;
		}
			
		return true;
	}
	
//----------------------------------------------------------------------------------------- 
// Retorna true o false al validar el activar o desactivar el formato

// Parámetros de Entrada:
//			convocatoriacod = codigo de la convocatoria
// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no	

	
	
	public function InsertarTapaMultDesdeTemporal($datos)
	{
		if (isset($datos['name']) && $datos['name']!=""){
			$pathinfo = pathinfo($datos['name']);
			$extension = strtolower($pathinfo['extension']);
			
			if ($extension!="jpg" && $extension!="png" && $extension!="gif")
			{
				FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Formato de archivo no permitido.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
				return false;
			}
			
			
			$datos['revtapamulubic'] = $datos['file'];
			
			if(!is_dir(CARPETA_SERVIDOR_MULTIMEDIA_FISICA.CARPETA_SERVIDOR_MULTIMEDIA_TAPAS)){ 
				@mkdir(CARPETA_SERVIDOR_MULTIMEDIA_FISICA.CARPETA_SERVIDOR_MULTIMEDIA_TAPAS);
			}
	
			//Subir imagenes
			$oImagen = new cFuncionesMultimedia();
			$nombrearchivo = $datos['file'];
			$carpetaorigen = CARPETA_SERVIDOR_MULTIMEDIA_FISICA."/"."tmp/".$datos['file'];
			$carpetadestino = CARPETA_SERVIDOR_MULTIMEDIA_FISICA."/tapas/".CARPETA_SERVIDOR_MULTIMEDIA_TAPAS;
			
			if (!copy($carpetaorigen, $carpetadestino.'/'.$nombrearchivo)) 
				echo "Error al copiar archivo...\n";
			
			@unlink($carpetaorigen);
		
		}
		return true;
	
	}

	
//----------------------------------------------------------------------------------------- 
//Retorna true o false si pudo cambiar el orden de los albumes

// Parámetros de Entrada:
//		albumorden = orden de los albums.

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no		
	public function ModificarOrden($datos)
	{
		$arreglorevtapamul = explode(",",$datos['revtapamulorden']);
		
		$datosmodif['revtapamulorden'] = 1;
		foreach ($arreglorevtapamul as $revtapamulcod)
		{
			$datosmodif['revtapamulcod'] = $revtapamulcod;
			if (!parent::ModificarOrden($datosmodif))
					return false;
			$datosmodif['revtapamulorden']++;
		}
		
		return true;
	}
//----------------------------------------------------------------------------------------- 
// Retorna proxorden. proximo orden de la frase

// Parámetros de Entrada:
//		frasecod = codigo de las frases.

// Retorna:
//		la función retorna true o false si se pudo ejecutar con éxito o no	
	private function ObtenerProximoOrden($datos,&$proxorden)
	{
		$proxorden = 0;
		if (!parent::BuscarRevTapaMulUltimoOrden($datos,$resultado,$numfilas))
			return false;
			
		if ($numfilas!=0)
		{
			$datos = $this->conexion->ObtenerSiguienteRegistro($resultado);
			$proxorden = $datos['maximo'] + 1;
		}
		return true;
	}		
	
	
}//fin clase	

?>