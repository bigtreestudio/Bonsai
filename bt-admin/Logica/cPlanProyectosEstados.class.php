<?php 
include(DIR_CLASES_DB."cPlanProyectosEstados.db.php");
class cPlanProyectosEstados extends cPlanProyectosEstadosdb
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
			'xplanproyectoestadocod'=> 0,
			'planproyectoestadocod'=> "",
			'xplanproyectoestadonombre'=> 0,
			'planproyectoestadonombre'=> "",
			'xplanproyectoestadoestado'=> 0,
			'planproyectoestadoestado'=> "-1",
			'limit'=> '',
			'orderby'=> "planproyectoestadocod DESC"
		);
		if(isset($datos['planproyectoestadocod']) && $datos['planproyectoestadocod']!="")
		{
			$sparam['planproyectoestadocod']= $datos['planproyectoestadocod'];
			$sparam['xplanproyectoestadocod']= 1;
		}
		if(isset($datos['planproyectoestadonombre']) && $datos['planproyectoestadonombre']!="")
		{
			$sparam['planproyectoestadonombre']= $datos['planproyectoestadonombre'];
			$sparam['xplanproyectoestadonombre']= 1;
		}
		if(isset($datos['planproyectoestadoestado']) && $datos['planproyectoestadoestado']!="")
		{
			$sparam['planproyectoestadoestado']= $datos['planproyectoestadoestado'];
			$sparam['xplanproyectoestadoestado']= 1;
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
		$datos['planproyectoestadoestado'] = ACTIVO;
		$this->_SetearNull($datos);
		if (!parent::Insertar($datos,$codigoinsertado))
			return false;
		$datos['planproyectoestadocod'] =$codigoinsertado;
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
		$datosmodif['planproyectoestadocod'] = $datos['planproyectoestadocod'];
		$datosmodif['planproyectoestadoestado'] = ELIMINADO;
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
		$datosmodif['planproyectoestadocod'] = $datos['planproyectoestadocod'];
		$datosmodif['planproyectoestadoestado'] = ACTIVO;
		if (!$this->ModificarEstado($datosmodif))
			return false;
		return true;
	}


	public function DesActivar($datos)
	{
		$datosmodif['planproyectoestadocod'] = $datos['planproyectoestadocod'];
		$datosmodif['planproyectoestadoestado'] = NOACTIVO;
		if (!$this->ModificarEstado($datosmodif))
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
		$nombrearchivo = "plan_proyectos_estados";
		$carpeta = PUBLICA."json/";
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
		$datos['planproyectoestadoestado'] = ACTIVO;
		if(!$this->BusquedaAvanzada($datos,$resultados,$numfilas))
			return false;
		if($numfilas>0)
		{
			while($fila = $this->conexion->ObtenerSiguienteRegistro($resultados))
			{
				$array[$fila['planproyectoestadocod']] = $fila;
			}
		}
		return true;
	}


	public function PublicarJsonxCodigo($datos)
	{
		$nombrearchivo = "plan_proyectos_estados_".$datos['planproyectoestadocod'];
		$carpeta = PUBLICA."json/";
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
		$array = array();
		if(!$this->BuscarxCodigo($datos,$resultados,$numfilas))
			return false;
		if($numfilas==1)
		{
			while($fila = $this->conexion->ObtenerSiguienteRegistro($resultados))
			{
				$array[$fila['planproyectoestadocod']] = $fila;
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


		if (!isset($datos['planproyectoestadonombre']) || $datos['planproyectoestadonombre']=="")
			$datos['planproyectoestadonombre']="NULL";

		if (!isset($datos['planproyectoestadocolor']) || $datos['planproyectoestadocolor']=="")
			$datos['planproyectoestadocolor']="NULL";
		return true;
	}


	private function _ValidarDatosVacios($datos)
	{


		if (!isset($datos['planproyectoestadonombre']) || $datos['planproyectoestadonombre']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Debe ingresar un nombre",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		if (!isset($datos['planproyectoestadocolor']) || $datos['planproyectoestadocolor']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Debe ingresar un color",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		return true;
	}




}
?>