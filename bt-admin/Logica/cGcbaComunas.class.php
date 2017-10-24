<?php 
include(DIR_CLASES_DB."cGcbaComunas.db.php");

{

	protected $formato;

		$this->conexion = &$conexion;
		$this->formato = &$formato;
		parent::__construct();
	}


	{
		if (!parent::BuscarxCodigo($datos,$resultado,$numfilas))
			return false;
		return true;
	}



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


			$sparam['orderby']= $datos['orderby'];

			$sparam['limit']= $datos['limit'];

			return false;
		return true;
	}



	{
		if (!$this->_ValidarInsertar($datos))
			return false;

		$this->_SetearNull($datos);
		if (!parent::Insertar($datos,$codigoinsertado))
			return false;
		$datos['comunacod'] =$codigoinsertado;
		if (!$this->Publicar($datos))
			return false;

	}



	{
		if (!$this->_ValidarModificar($datos))
			return false;

		if (!parent::Modificar($datos))
			return false;

			return false;

	}



	{
		if (!$this->_ValidarEliminar($datos))
			return false;

		$datosmodif['comunaestado'] = ELIMINADO;
		if (!$this->ModificarEstado($datosmodif))
			return false;
		return true;
	}



	{
		if (!parent::ModificarEstado($datos))
			return false;
		if (!$this->Publicar($datos))
			return false;

	}



	{
		$datosmodif['comunacod'] = $datos['comunacod'];
		$datosmodif['comunaestado'] = ACTIVO;
		if (!$this->ModificarEstado($datosmodif))
			return false;
		return true;
	}



	{
		$datosmodif['comunacod'] = $datos['comunacod'];
		$datosmodif['comunaestado'] = NOACTIVO;
		if (!$this->ModificarEstado($datosmodif))
			return false;
		return true;
	}



	{
		if (!$this->PublicarListadoJson())
			return false;
		return true;
	}



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



	{
		if(file_exists($carpeta.$nombrearchivo.".json"))
		{
			unlink($carpeta.$nombrearchivo.".json");
		}
		return true;
	}



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

	}



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

	}



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



	{


		if (!isset($datos['comunanumero']) || $datos['comunanumero']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Debe ingresar un n�mero",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
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
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Debe ingresar un per�metro",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		if (!isset($datos['comunaarea']) || $datos['comunaarea']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Debe ingresar un �rea",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		if (!isset($datos['comunapoligono']) || $datos['comunapoligono']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Debe ingresar un pol�gono",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		return true;
	}





?>