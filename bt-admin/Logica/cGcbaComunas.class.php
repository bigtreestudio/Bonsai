<?php 
include(DIR_CLASES_DB."cGcbaComunas.db.php");
class cGcbaComunas extends cGcbaComunasdb
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
			'xcomunacod'=> 0,
			'comunacod'=> "",
			'xcomunanumero'=> 0,
			'comunanumero'=> "",
			'xcomunabarrios'=> 0,
			'comunabarrios'=> "",
			'xcomunaestado'=> 0,
			'comunaestado'=> "-1",
			'limit'=> '',
			'orderby'=> "comunacod DESC"
		);
		if(isset($datos['comunacod']) && $datos['comunacod']!="")
		{
			$sparam['comunacod']= $datos['comunacod'];
			$sparam['xcomunacod']= 1;
		}
		if(isset($datos['comunanumero']) && $datos['comunanumero']!="")
		{
			$sparam['comunanumero']= $datos['comunanumero'];
			$sparam['xcomunanumero']= 1;
		}
		if(isset($datos['comunabarrios']) && $datos['comunabarrios']!="")
		{
			$sparam['comunabarrios']= $datos['comunabarrios'];
			$sparam['xcomunabarrios']= 1;
		}
		if(isset($datos['comunaestado']) && $datos['comunaestado']!="")
		{
			$sparam['comunaestado']= $datos['comunaestado'];
			$sparam['xcomunaestado']= 1;
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
		$datos['comunaestado'] = ACTIVO;
		$this->_SetearNull($datos);
		if (!parent::Insertar($datos,$codigoinsertado))
			return false;
		$datos['comunacod'] =$codigoinsertado;
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
		$datosmodif['comunacod'] = $datos['comunacod'];
		$datosmodif['comunaestado'] = ELIMINADO;
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


	public function Activar($datos)
	{
		$datosmodif['comunacod'] = $datos['comunacod'];
		$datosmodif['comunaestado'] = ACTIVO;
		if (!$this->ModificarEstado($datosmodif))
			return false;
		return true;
	}


	public function DesActivar($datos)
	{
		$datosmodif['comunacod'] = $datos['comunacod'];
		$datosmodif['comunaestado'] = NOACTIVO;
		if (!$this->ModificarEstado($datosmodif))
			return false;
		return true;
	}


	public function Publicar($datos)
	{
		if (!$this->PublicarListadoJson())
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
		$nombrearchivo = "gcba_comunas";
		$carpeta = PUBLICA."json/gcba/";
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
		$array = array();
		$datos['comunaestado'] = ACTIVO;
		if(!$this->BusquedaAvanzada($datos,$resultados,$numfilas))
			return false;
		if($numfilas>0)
		{
			while($fila = $this->conexion->ObtenerSiguienteRegistro($resultados))
			{
				$array[$fila['comunacod']] = $fila;
			}
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


		if (!isset($datos['comunanumero']) || $datos['comunanumero']=="")
			$datos['comunanumero']="NULL";

		if (!isset($datos['comunabarrios']) || $datos['comunabarrios']=="")
			$datos['comunabarrios']="NULL";

		if (!isset($datos['comunaperimetro']) || $datos['comunaperimetro']=="")
			$datos['comunaperimetro']="NULL";

		if (!isset($datos['comunaarea']) || $datos['comunaarea']=="")
			$datos['comunaarea']="NULL";

		if (!isset($datos['comunapoligono']) || $datos['comunapoligono']=="")
			$datos['comunapoligono']="NULL";
		return true;
	}


	private function _ValidarDatosVacios($datos)
	{


		if (!isset($datos['comunanumero']) || $datos['comunanumero']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Debe ingresar un número",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		if (!FuncionesPHPLocal::ValidarContenido($this->conexion,$datos['comunanumero'],"NumericoEntero"))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error debe ingresar un campo numerico.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		if (!isset($datos['comunabarrios']) || $datos['comunabarrios']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Debe ingresar un barrio",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		if (!isset($datos['comunaperimetro']) || $datos['comunaperimetro']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Debe ingresar un perímetro",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		if (!isset($datos['comunaarea']) || $datos['comunaarea']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Debe ingresar un área",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		if (!isset($datos['comunapoligono']) || $datos['comunapoligono']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Debe ingresar un polígono",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		return true;
	}




}
?>