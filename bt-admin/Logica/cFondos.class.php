<?php 
include(DIR_CLASES_DB."cFondos.db.php");

class cFondos extends cFondosdb
{

	protected $conexion;
	protected $formato;

	function __construct($conexion,$formato=FMT_TEXTO){
		$this->conexion = &$conexion;
		$this->formato = &$formato;
		parent::__construct();
	}

	function __destruct(){parent::__destruct();}

	public function FondosSP(&$spnombre,&$sparam)
	{
		if (!parent::FondosSP($spnombre,$sparam))
			return false;
		return true;	
	}

	
	
	public function BuscarxCodigo($datos,&$resultado,&$numfilas)
	{
		if (!parent::BuscarxCodigo($datos,$resultado,$numfilas))
			return false;
		return true;
	}



	public function BusquedaAvanzada($datos,&$resultado,&$numfilas)
	{
		$sparam=array(
			'xfondodesc'=> 0,
			'fondodesc'=> "",
			'limit'=> '',
			'orderby'=> "fondocod DESC"
		);

		if(isset($datos['fondodesc']) && $datos['fondodesc']!="")
		{
			$sparam['fondodesc']= $datos['fondodesc'];
			$sparam['xfondodesc']= 1;
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

		if (!parent::Insertar($datos,$codigoinsertado))
			return false;
		return true;
	}



	public function Modificar($datos)
	{
		if (!$this->_ValidarModificar($datos))
			return false;

		if (!parent::Modificar($datos))
			return false;

		return true;
	}



	public function Eliminar($datos)
	{
		if (!$this->_ValidarEliminar($datos))
			return false;

		if (!parent::Eliminar($datos))
			return false;

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
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error debe ingresar un código valido.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
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
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error debe ingresar un código valido.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		return true;
	}



	private function _ValidarDatosVacios($datos)
	{

		return true;
	}

	public function GuardarImagen($datos)
	{	
		if(isset($datos['size']) && $datos['size']!="" && isset($datos['name']) && $datos['name']!="" && isset($datos['file']) && $datos['file']!="")
		{
			
			if(!$this->InsertarImgDesdeTemporal($datos))
				return false;
			$datos['fondoimgsize']=$datos['size'];
			$datos['fondoimgnombre']=$datos['name'];
			if(!$this->ModificarFotoLink($datos))
			 	return false;
				
		}
		return true;
	}
	
	public function InsertarImgDesdeTemporal(&$datos)
	{
		$pathinfo = pathinfo($datos['name']);
		$extension = strtolower($pathinfo['extension']);
		
		switch($extension)
		{
			case "jpg":
			case "gif":
			case "png":
				break;
			default:
				FuncionesPHPLocal::MostrarMensaje($this->conexion,"Formato de archivo no permitido.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
				return false;
				break;
		}
		if(!is_dir(CARPETA_SERVIDOR_MULTIMEDIA_FISICA.CARPETA_SERVIDOR_MULTIMEDIA_FONDOS)){ 
			@mkdir(CARPETA_SERVIDOR_MULTIMEDIA_FISICA.CARPETA_SERVIDOR_MULTIMEDIA_FONDOS);
		}
		$oImagen = new cFuncionesMultimedia();
		//Subir imagenes
		$nombrearchivo = $datos['file'];//.$extension;
		$carpetaorigen = CARPETA_SERVIDOR_MULTIMEDIA_FISICA.CARPETA_SERVIDOR_MULTIMEDIA_TMP;


		$carpetadestino = CARPETA_SERVIDOR_MULTIMEDIA_FISICA.CARPETA_SERVIDOR_MULTIMEDIA_FONDOS.CARPETA_SERVIDOR_MULTIMEDIA_FONDOS_N;
		if(!$oImagen->RedimensionarImagen($nombrearchivo,$carpetaorigen,$carpetadestino,TAMANIONORMAL,TAMANIONORMAL))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al generar las imagenes.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		$carpetadestino = CARPETA_SERVIDOR_MULTIMEDIA_FISICA.CARPETA_SERVIDOR_MULTIMEDIA_FONDOS.CARPETA_SERVIDOR_MULTIMEDIA_FONDOS_THUMB;
		if(!$oImagen->RedimensionarImagen($nombrearchivo,$carpetaorigen,$carpetadestino,TAMANIOFONDOSTHUMB,TAMANIOFONDOSTHUMB))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al generar las imagenes.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		
		@unlink($carpetaorigen);
		
		$datos['fondoimgubic']="/".$nombrearchivo;
		return true;
	
	}
	
	public function ModificarFotoLink($datos)
	{
		if(!parent::ModificarFotoLink($datos))
			return false;
		return true;		
	}


	public function Publicar()
	{
		$datos = array();
		$datos['orderby'] = "fondocod ASC";
		if(!$this->BusquedaAvanzada($datos,$resultado,$numfilas))
			return false;
			
		$variableGuardar = "<?php  \n";
		while($fila = $this->conexion->ObtenerSiguienteRegistro($resultado))
		{
			if ($fila['fondoimgubic']!="")
				$variableGuardar .= "\$arregloFondos[".$fila['fondocod']."] = \"".DOMINIO_SERVIDOR_MULTIMEDIA."fondos/N".$fila['fondoimgubic']."\";\n"; 
		}
		$variableGuardar .= "?>";
		file_put_contents('../especiales/informes/informe_'.$id_informe.'.php' , $variableGuardar);
		if(!FuncionesPHPLocal::GuardarArchivo(PUBLICA,$variableGuardar,"fondos.php"))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_INF,"Error, al generar el archivo de la galeria. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		return true;
	}


}
?>