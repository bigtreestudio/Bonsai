<?php 
include(DIR_CLASES_DB."cPlanTags.db.php");

class cPlanTags extends cPlanTagsdb
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
			'xplantagcod'=> 0,
			'plantagcod'=> "",
			'xplantagnombre'=> 0,
			'plantagnombre'=> "",
			'xplantagcatcod'=> 0,
			'plantagcatcod'=> "",
			'xplanejecod'=> 0,
			'planejecod'=> "",
			'xplantagestado'=> 0,
			'plantagestado'=> "-1",
			'limit'=> '',
			'orderby'=> "plantagorden ASC"
		);

		if(isset($datos['plantagcod']) && $datos['plantagcod']!="")
		{
			$sparam['plantagcod']= $datos['plantagcod'];
			$sparam['xplantagcod']= 1;
		}
		if(isset($datos['plantagnombre']) && $datos['plantagnombre']!="")
		{
			$sparam['plantagnombre']= $datos['plantagnombre'];
			$sparam['xplantagnombre']= 1;
		}
		if(isset($datos['plantagcatcod']) && $datos['plantagcatcod']!="")
		{
			$sparam['plantagcatcod']= $datos['plantagcatcod'];
			$sparam['xplantagcatcod']= 1;
		}
		if(isset($datos['planejecod']) && $datos['planejecod']!="")
		{
			$sparam['planejecod']= $datos['planejecod'];
			$sparam['xplanejecod']= 1;
		}
		if(isset($datos['plantagestado']) && $datos['plantagestado']!="")
		{
			$sparam['plantagestado']= $datos['plantagestado'];
			$sparam['xplantagestado']= 1;
		}


		if(isset($datos['orderby']) && $datos['orderby']!="")
			$sparam['orderby']= $datos['orderby'];

		if(isset($datos['limit']) && $datos['limit']!="")
			$sparam['limit']= $datos['limit'];

		if (!parent::BusquedaAvanzada($sparam,$resultado,$numfilas))
			return false;
		return true;
	}



	public function plan_tags_categoriasSP(&$spnombre,&$sparam)
	{
		if (!parent::plan_tags_categoriasSP($spnombre,$sparam))
			return false;
		return true;
	}



	public function plan_tags_categoriasSPResult(&$resultado,&$numfilas)
	{
		if (!$this->plan_tags_categoriasSP($spnombre,$sparam))
			return false;

		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno) )
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar por codigo. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		return true;
	}



	public function Insertar($datos,&$codigoinsertado)
	{
		if (!$this->_ValidarInsertar($datos))
			return false;

		$this->ObtenerProximoOrden($datos,$proxorden);
		$datos['plantagorden'] = $proxorden;
		$datos['plantagestado'] = ACTIVO;
		$this->_SetearNull($datos);
		if (!parent::Insertar($datos,$codigoinsertado))
			return false;
		$datos['plantagcod'] =$codigoinsertado;
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

		$datosmodif['plantagcod'] = $datos['plantagcod'];
		$datosmodif['plantagestado'] = ELIMINADO;
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
		$datosmodif['plantagcod'] = $datos['plantagcod'];
		$datosmodif['plantagestado'] = ACTIVO;
		if (!$this->ModificarEstado($datosmodif))
			return false;
		return true;
	}



	public function DesActivar($datos)
	{
		$datosmodif['plantagcod'] = $datos['plantagcod'];
		$datosmodif['plantagestado'] = NOACTIVO;
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
		$nombrearchivo = "plan_tags";
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
		$array = array();
		$datos['plantagestado'] = ACTIVO;
		if(!$this->BusquedaAvanzada($datos,$resultados,$numfilas))
			return false;
		if($numfilas>0)
		{
			while($fila = $this->conexion->ObtenerSiguienteRegistro($resultados))
			{
				$array[$fila['plantagcod']] = $fila;
			}
		}
		return true;
	}



	public function PublicarJsonxCodigo($datos)
	{
		$nombrearchivo = "plan_tags_".$datos['plantagcod'];
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
		$array = array();
		if(!$this->BuscarxCodigo($datos,$resultados,$numfilas))
			return false;
		if($numfilas==1)
		{
			while($fila = $this->conexion->ObtenerSiguienteRegistro($resultados))
			{
				$array[$fila['plantagcod']] = $fila;
			}
		}
		return true;
	}



	public function ModificarOrdenCompleto($datos)
	{
		$datosmodif['plantagorden'] = 1;
		$arregloOrden = explode(",",$datos['orden']);
		foreach ($arregloOrden as $plantagcod){
			$datosmodif['plantagcod'] = $plantagcod;
			if (!parent::ModificarOrden($datosmodif))
				return false;
			if (!$this->PublicarJsonxCodigo($datosmodif))
				return false;

			$datosmodif['plantagorden']++;
		}
			if (!$this->Publicar($datosmodif))
				return false;

		return true;
	}



	private function ObtenerProximoOrden($datos,&$proxorden)
	{
		$proxorden = 0;
		if (!parent::BuscarUltimoOrden($datos,$resultado,$numfilas))
			return false;

		if ($numfilas!=0){
			$datos = $this->conexion->ObtenerSiguienteRegistro($resultado);
			$proxorden = $datos['maximo'] + 1;
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


		if (!isset($datos['plantagnombre']) || $datos['plantagnombre']=="")
			$datos['plantagnombre']="NULL";

		if (!isset($datos['plantagcatcod']) || $datos['plantagcatcod']=="")
			$datos['plantagcatcod']="NULL";

		if (!isset($datos['plantagcolor']) || $datos['plantagcolor']=="")
			$datos['plantagcolor']="NULL";

		if (!isset($datos['plantagclass']) || $datos['plantagclass']=="")
			$datos['plantagclass']="NULL";

		if (!isset($datos['planejecod']) || $datos['planejecod']=="")
			$datos['planejecod']="NULL";
		return true;
	}



	private function _ValidarDatosVacios($datos)
	{


		if (!isset($datos['plantagnombre']) || $datos['plantagnombre']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Debe ingresar un nombre",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		if (!isset($datos['plantagcatcod']) || $datos['plantagcatcod']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Debe ingresar una categoría",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		if (!FuncionesPHPLocal::ValidarContenido($this->conexion,$datos['plantagcatcod'],"NumericoEntero"))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error debe ingresar un campo numerico.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		if (!$this->conexion->TraerCampo('plan_tags_categorias','plantagcatcod',array('plantagcatcod='.$datos['plantagcatcod']),$dato,$numfilas,$errno))
			return false;


		if ($numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error debe ingresar un campo valido.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		if (!isset($datos['plantagcolor']) || $datos['plantagcolor']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Debe ingresar un color",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		/*if (!isset($datos['plantagclass']) || $datos['plantagclass']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Debe ingresar un class",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}*/

		/*if (!isset($datos['planejecod']) || $datos['planejecod']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Debe ingresar un eje",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		if (!FuncionesPHPLocal::ValidarContenido($this->conexion,$datos['planejecod'],"NumericoEntero"))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error debe ingresar un campo numerico.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}*/

		
		return true;
	}





}
?>