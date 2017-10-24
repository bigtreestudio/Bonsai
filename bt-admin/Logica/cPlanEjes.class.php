<?php 
include(DIR_CLASES_DB."cPlanEjes.db.php");

class cPlanEjes extends cPlanEjesdb
{

	protected $conexion;
	protected $formato;

	function __construct($conexion,$formato=FMT_TEXTO){
		$this->conexion = &$conexion;
		$this->formato = &$formato;
		parent::__construct();
	}

	function __destruct(){parent::__destruct();}

	public function BuscarxCodigo($datos,&$resultado,&$numfilas)
	{
		if (!parent::BuscarxCodigo($datos,$resultado,$numfilas))
			return false;
		return true;
	}



	public function BusquedaAvanzada($datos,&$resultado,&$numfilas)
	{
		$sparam=array(
			'xplanejecod'=> 0,
			'planejecod'=> "",
			'xplanejenombre'=> 0,
			'planejenombre'=> "",
			'xplanejeconstante'=> 0,
			'planejeconstante'=> "",
			'xplanejeestado'=> 0,
			'planejeestado'=> "-1",
			'limit'=> '',
			'orderby'=> "planejecod DESC"
		);

		if(isset($datos['planejecod']) && $datos['planejecod']!="")
		{
			$sparam['planejecod']= $datos['planejecod'];
			$sparam['xplanejecod']= 1;
		}
		if(isset($datos['planejenombre']) && $datos['planejenombre']!="")
		{
			$sparam['planejenombre']= $datos['planejenombre'];
			$sparam['xplanejenombre']= 1;
		}
		if(isset($datos['planejeconstante']) && $datos['planejeconstante']!="")
		{
			$sparam['planejeconstante']= $datos['planejeconstante'];
			$sparam['xplanejeconstante']= 1;
		}
		if(isset($datos['planejeestado']) && $datos['planejeestado']!="")
		{
			$sparam['planejeestado']= $datos['planejeestado'];
			$sparam['xplanejeestado']= 1;
		}


		if(isset($datos['orderby']) && $datos['orderby']!="")
			$sparam['orderby']= $datos['orderby'];

		if(isset($datos['limit']) && $datos['limit']!="")
			$sparam['limit']= $datos['limit'];

		if (!parent::BusquedaAvanzada($sparam,$resultado,$numfilas))
			return false;
		return true;
	}



	public function Insertar($datos,&$codigoinsertado)
	{
		if (!$this->_ValidarInsertar($datos))
			return false;

		$datos['planejeestado'] = ACTIVO;
		$this->_SetearNull($datos);
		if (!parent::Insertar($datos,$codigoinsertado))
			return false;
		$datos['planejecod'] =$codigoinsertado;
		if (!$this->Publicar($datos))
			return false;

		return true;
	}



	public function Modificar($datos)
	{
		if (!$this->_ValidarModificar($datos))
			return false;

		$this->_SetearNull($datos);
		if (!parent::Modificar($datos))
			return false;

		if (!$this->Publicar($datos))
			return false;

		return true;
	}



	public function Eliminar($datos)
	{
		if (!$this->_ValidarEliminar($datos))
			return false;

		$datosmodif['planejecod'] = $datos['planejecod'];
		$datosmodif['planejeestado'] = ELIMINADO;
		if (!$this->ModificarEstado($datosmodif))
			return false;
		return true;
	}



	public function ModificarEstado($datos)
	{
		if (!parent::ModificarEstado($datos))
			return false;
		if (!$this->Publicar($datos))
			return false;

		return true;
	}



	public function Publicar($datos)
	{
		if (!$this->PublicarListadoJson())
			return false;
		if (!$this->PublicarJsonxCodigo($datos))
			return false;
		return true;
	}



	public function GuardarDatosJson($nombrearchivo,$carpeta,$array)
	{
		$datosJson = FuncionesPHPLocal::DecodificarUtf8($array);
		$jsonData = json_encode($datosJson);
		if(!is_dir($carpeta)){
			@mkdir($carpeta);
		}
		if(!FuncionesPHPLocal::GuardarArchivo($carpeta,$jsonData,$nombrearchivo.".json"))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_INF,"Error, al generar el archivo json. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		return true;
	}



	public function EliminarDatosJson($nombrearchivo,$carpeta)
	{
		if(file_exists($carpeta.$nombrearchivo.".json"))
		{
			unlink($carpeta.$nombrearchivo.".json");
		}
		return true;
	}



	public function PublicarListadoJson()
	{
		$nombrearchivo = "plan_ejes";
		$carpeta = PUBLICA."json/Plan/";
		if(!$this->GerenarArrayDatosJsonListado($array))
			return false;
		if(count($array)>0)
		{
			if(!$this->GuardarDatosJson($nombrearchivo,$carpeta,$array))
				return false;
		}
		else
		{
			if(!$this->EliminarDatosJson($nombrearchivo,$carpeta))
				return false;
		}
		return true;
	}



	public function GerenarArrayDatosJsonListado(&$array)
	{
		$oMultimedia = new cMultimedia($this->conexion,$this->formato);
		$array = array();
		$datos['planejeestado'] = ACTIVO;
		if(!$this->BusquedaAvanzada($datos,$resultados,$numfilas))
			return false;
		if($numfilas>0)
		{
			while($fila = $this->conexion->ObtenerSiguienteRegistro($resultados))
			{
				$array[$fila['planejecod']] = $fila;
				$datosmultimedia['multimediaconjuntocod'] = FOTOS;
				$datosmultimedia['multimediacod'] = $fila['multimediacod'];
				if(!$oMultimedia->BuscarMultimediaxCodigo($datosmultimedia,$resultadoFotos,$numfilasFotos))
					return false;
				if($numfilasFotos>0)
				{
					$filaFotos= $this->conexion->ObtenerSiguienteRegistro($resultadoFotos);
					$array[$fila['planejecod']]['multimediacod_url'] = CARPETA_SERVIDOR_MULTIMEDIA_NOTICIAS.CARPETA_SERVIDOR_MULTIMEDIA_ORIGINALES.$filaFotos['multimediaubic'];
				}
			}
		}
		return true;
	}



	public function PublicarJsonxCodigo($datos)
	{
		$nombrearchivo = "plan_ejes_".$datos['planejecod'];
		$carpeta = PUBLICA."json/Plan/";
		if(!$this->GerenarArrayDatosJsonxCodigo($datos,$array))
			return false;
		if(count($array)>0)
		{
			if(!$this->GuardarDatosJson($nombrearchivo,$carpeta,$array))
				return false;
		}
		else
		{
			if(!$this->EliminarDatosJson($nombrearchivo,$carpeta))
				return false;
		}
		return true;
	}



	public function GerenarArrayDatosJsonxCodigo($datos,&$array)
	{
		$oMultimedia = new cMultimedia($this->conexion,$this->formato);
		$array = array();
		if(!$this->BuscarxCodigo($datos,$resultados,$numfilas))
			return false;
		if($numfilas==1)
		{
			while($fila = $this->conexion->ObtenerSiguienteRegistro($resultados))
			{
				$array[$fila['planejecod']] = $fila;
				$datosmultimedia['multimediaconjuntocod'] = FOTOS;
				$datosmultimedia['multimediacod'] = $fila['multimediacod'];
				if(!$oMultimedia->BuscarMultimediaxCodigo($datosmultimedia,$resultadoFotos,$numfilasFotos))
					return false;
				if($numfilasFotos>0)
				{
					$filaFotos= $this->conexion->ObtenerSiguienteRegistro($resultadoFotos);
					$array[$fila['planejecod']]['multimediacod_url'] = CARPETA_SERVIDOR_MULTIMEDIA_NOTICIAS.CARPETA_SERVIDOR_MULTIMEDIA_ORIGINALES.$filaFotos['multimediaubic'];
				}
			}
		}
		return true;
	}



	public function GenerarDatosMultimedia($fila,&$arraymultimedia,$id,$prefijo)
	{
		$arraytemp = array();
		switch ($fila['multimediaconjuntocod'])
		{
			case FOTOS:
				$arraytemp['codigo'] = $fila[$id];
				$arraytemp['conjunto'] = $fila['multimediaconjuntocod'];
				$arraytemp['tipo'] = $fila['multimediatipocod'];
				$arraytemp['titulo'] = $fila[$prefijo.'multimediatitulo'];
				$arraytemp['descripcion'] = $fila[$prefijo.'multimediadesc'];
				$arraytemp['nombre_archivo'] = $fila['multimedianombre'];
				if(isset($fila[$prefijo.'multimediaorden']))
					$arraytemp['orden'] = $fila[$prefijo.'multimediaorden'];
				if(isset($fila[$prefijo.'multimediamuestrahome']))
					$arraytemp['muestrahome'] = $fila[$prefijo.'multimediamuestrahome'];
				$arraytemp['idexterno'] = $fila['multimediaidexterno'];
				$arraytemp['url'] = CARPETA_SERVIDOR_MULTIMEDIA_NOTICIAS.CARPETA_SERVIDOR_MULTIMEDIA_ORIGINALES.$fila['multimediaubic'];
				if(isset($fila['previewubic']) && $fila['previewubic']!="")
					$arraytemp['url_preview'] = CARPETA_SERVIDOR_MULTIMEDIA_NOTICIAS.CARPETA_SERVIDOR_MULTIMEDIA_ORIGINALES.$fila['previewubic'];
				$arraymultimedia['fotos'][$fila[$id]] = $arraytemp;
			break;
			case VIDEOS:
				$arraytemp['codigo'] = $fila[$id];
				$arraytemp['conjunto'] = $fila['multimediaconjuntocod'];
				$arraytemp['tipo'] = $fila['multimediatipocod'];
				$arraytemp['titulo'] = $fila[$prefijo.'multimediatitulo'];
				$arraytemp['descripcion'] = $fila[$prefijo.'multimediadesc'];
				$arraytemp['nombre_archivo'] = $fila['multimedianombre'];
				if(isset($fila[$prefijo.'multimediaorden']))
					$arraytemp['orden'] = $fila[$prefijo.'multimediaorden'];
				if(isset($fila[$prefijo.'multimediamuestrahome']))
					$arraytemp['muestrahome'] = $fila[$prefijo.'multimediamuestrahome'];
				$arraytemp['idexterno'] = $fila['multimediaidexterno'];
				if(isset($fila['multimediaidexterno']) && $fila['multimediaidexterno']!="")
					$arraytemp['url'] = "";
				else
					$arraytemp['url'] = CARPETA_SERVIDOR_MULTIMEDIA_NOTICIAS."videos/".$fila['multimediaubic'];
				if(isset($fila['previewubic']) && $fila['previewubic']!="")
					$arraytemp['url_preview'] = CARPETA_SERVIDOR_MULTIMEDIA_NOTICIAS.CARPETA_SERVIDOR_MULTIMEDIA_ORIGINALES.$fila['previewubic'];
				$arraymultimedia['videos'][$fila[$id]] = $arraytemp;
			break;
			case AUDIOS:
				$arraytemp['codigo'] = $fila[$id];
				$arraytemp['conjunto'] = $fila['multimediaconjuntocod'];
				$arraytemp['tipo'] = $fila['multimediatipocod'];
				$arraytemp['titulo'] = $fila[$prefijo.'multimediatitulo'];
				$arraytemp['descripcion'] = $fila[$prefijo.'multimediadesc'];
				$arraytemp['nombre_archivo'] = $fila['multimedianombre'];
				if(isset($fila[$prefijo.'multimediaorden']))
					$arraytemp['orden'] = $fila[$prefijo.'multimediaorden'];
				if(isset($fila[$prefijo.'multimediamuestrahome']))
					$arraytemp['muestrahome'] = $fila[$prefijo.'multimediamuestrahome'];
				$arraytemp['idexterno'] = $fila['multimediaidexterno'];
				if(isset($fila['multimediaidexterno']) && $fila['multimediaidexterno']!="")
					$arraytemp['url'] = "";
				else
					$arraytemp['url'] = CARPETA_SERVIDOR_MULTIMEDIA_NOTICIAS."audios/".$fila['multimediaubic'];
				if(isset($fila['previewubic']) && $fila['previewubic']!="")
					$arraytemp['url_preview'] = CARPETA_SERVIDOR_MULTIMEDIA_NOTICIAS.CARPETA_SERVIDOR_MULTIMEDIA_ORIGINALES.$fila['previewubic'];
				$arraymultimedia['audios'][$fila[$id]] = $arraytemp;
			break;
			case FILES:
				$arraytemp['codigo'] = $fila[$id];
				$arraytemp['conjunto'] = $fila['multimediaconjuntocod'];
				$arraytemp['tipo'] = $fila['multimediatipocod'];
				$arraytemp['titulo'] = $fila[$prefijo.'multimediatitulo'];
				$arraytemp['descripcion'] = $fila[$prefijo.'multimediadesc'];
				$arraytemp['nombre_archivo'] = $fila['multimedianombre'];
				if(isset($fila[$prefijo.'multimediaorden']))
					$arraytemp['orden'] = $fila[$prefijo.'multimediaorden'];
				if(isset($fila[$prefijo.'multimediamuestrahome']))
					$arraytemp['muestrahome'] = $fila[$prefijo.'multimediamuestrahome'];
				$arraytemp['idexterno'] = $fila['multimediaidexterno'];
				if(isset($fila['multimediaidexterno']) && $fila['multimediaidexterno']!="")
					$arraytemp['url'] = "";
				else
					$arraytemp['url'] = CARPETA_SERVIDOR_MULTIMEDIA_NOTICIAS.CARPETA_SERVIDOR_MULTIMEDIA_ARCHIVOS.$fila['multimediaubic'];
				if(isset($fila['previewubic']) && $fila['previewubic']!="")
					$arraytemp['url_preview'] = CARPETA_SERVIDOR_MULTIMEDIA_NOTICIAS.CARPETA_SERVIDOR_MULTIMEDIA_ORIGINALES.$fila['previewubic'];
				$arraymultimedia['archivos'][$fila[$id]] = $arraytemp;
			break;
		}
		return true;
	}




//-----------------------------------------------------------------------------------------
//FUNCIONES PRIVADAS
//-----------------------------------------------------------------------------------------

	private function _ValidarInsertar($datos)
	{
		if (!$this->_ValidarDatosVacios($datos))
			return false;

		return true;
	}



	private function _ValidarModificar($datos)
	{
		if (!$this->BuscarxCodigo($datos,$resultado,$numfilas))
			return false;

		if ($numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error debe ingresar un codigo valido.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		if (!$this->_ValidarDatosVacios($datos))
			return false;

		return true;
	}



	private function _ValidarEliminar($datos)
	{
		if (!$this->BuscarxCodigo($datos,$resultado,$numfilas))
			return false;

		if ($numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error debe ingresar un c&oacute;digo valido.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		return true;
	}



	private function _SetearNull(&$datos)
	{


		if (!isset($datos['planejenombre']) || $datos['planejenombre']=="")
			$datos['planejenombre']="NULL";

		if (!isset($datos['multimediacod']) || $datos['multimediacod']=="")
			$datos['multimediacod']="NULL";

		if (!isset($datos['planejeconstante']) || $datos['planejeconstante']=="")
			$datos['planejeconstante']="NULL";

		if (!isset($datos['planejedescripcion']) || $datos['planejedescripcion']=="")
			$datos['planejedescripcion']="NULL";

		if (!isset($datos['planejecolor']) || $datos['planejecolor']=="")
			$datos['planejecolor']="NULL";
		return true;
	}



	private function _ValidarDatosVacios($datos)
	{


		if (!isset($datos['planejenombre']) || $datos['planejenombre']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Debe ingresar un nombre",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		/*if (!isset($datos['multimediacod']) || $datos['multimediacod']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Debe ingresar una imagen",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}*/
		if (isset($datos['multimediacod']) && $datos['multimediacod']!="")
		{
			if (!FuncionesPHPLocal::ValidarContenido($this->conexion,$datos['multimediacod'],"NumericoEntero"))
			{
				FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error debe ingresar un campo numerico.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
				return false;
			}
		}
		if (!isset($datos['planejeconstante']) || $datos['planejeconstante']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Debe ingresar una constante",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		/*if (!isset($datos['planejedescripcion']) || $datos['planejedescripcion']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Debe ingresar una descripción",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		*/
		if (!isset($datos['planejecolor']) || $datos['planejecolor']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Debe ingresar un color",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		return true;
	}





}
?>