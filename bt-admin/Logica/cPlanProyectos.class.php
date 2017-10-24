<?php 
include(DIR_CLASES_DB."cPlanProyectos.db.php");   

class cPlanProyectos extends cPlanProyectosdb 
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
			'xplanproyectocod'=> 0,
			'planproyectocod'=> "",
			'xplanproyectonombre'=> 0,
			'planproyectonombre'=> "",
			'xplanobjetivocod'=> 0,
			'planobjetivocod'=> "",
			'xplanjurisdiccioncod'=> 0,
			'planjurisdiccioncod'=> "",
			'xplanproyectoestadocod'=> 0,
			'planproyectoestadocod'=> "",
			'xplanproyectoestado'=> 0,
			'planproyectoestado'=> "-1",
			'limit'=> '',
			'orderby'=> "planproyectocod DESC"
		);

		if(isset($datos['planproyectocod']) && $datos['planproyectocod']!="")
		{
			$sparam['planproyectocod']= $datos['planproyectocod'];
			$sparam['xplanproyectocod']= 1;
		}
		if(isset($datos['planproyectonombre']) && $datos['planproyectonombre']!="")
		{
			$sparam['planproyectonombre']= $datos['planproyectonombre'];
			$sparam['xplanproyectonombre']= 1;
		}
		if(isset($datos['planobjetivocod']) && $datos['planobjetivocod']!="")
		{
			$sparam['planobjetivocod']= $datos['planobjetivocod'];
			$sparam['xplanobjetivocod']= 1;
		}
		if(isset($datos['planjurisdiccioncod']) && $datos['planjurisdiccioncod']!="")
		{
			$sparam['planjurisdiccioncod']= $datos['planjurisdiccioncod'];
			$sparam['xplanjurisdiccioncod']= 1;
		}
		if(isset($datos['planproyectoestadocod']) && $datos['planproyectoestadocod']!="")
		{
			$sparam['planproyectoestadocod']= $datos['planproyectoestadocod'];
			$sparam['xplanproyectoestadocod']= 1;
		}
		if(isset($datos['planproyectoestado']) && $datos['planproyectoestado']!="")
		{
			$sparam['planproyectoestado']= $datos['planproyectoestado'];
			$sparam['xplanproyectoestado']= 1;
		}


		if(isset($datos['orderby']) && $datos['orderby']!="")
			$sparam['orderby']= $datos['orderby'];

		if(isset($datos['limit']) && $datos['limit']!="")
			$sparam['limit']= $datos['limit'];

		if (!parent::BusquedaAvanzada($sparam,$resultado,$numfilas))
			return false;
		return true;
	}



	public function plan_objetivosSP(&$spnombre,&$sparam)
	{
		if (!parent::plan_objetivosSP($spnombre,$sparam))
			return false;
		return true;
	}



	public function plan_objetivosSPResult(&$resultado,&$numfilas)
	{
		if (!$this->plan_objetivosSP($spnombre,$sparam))
			return false;

		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno) )
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar por codigo. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		return true;
	}



	public function plan_jurisdiccionesSP(&$spnombre,&$sparam)
	{
		if (!parent::plan_jurisdiccionesSP($spnombre,$sparam))
			return false;
		return true;
	}



	public function plan_jurisdiccionesSPResult(&$resultado,&$numfilas)
	{
		if (!$this->plan_jurisdiccionesSP($spnombre,$sparam))
			return false;

		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno) )
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar por codigo. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		return true;
	}



	public function plan_proyectos_estadosSP(&$spnombre,&$sparam)
	{
		if (!parent::plan_proyectos_estadosSP($spnombre,$sparam))
			return false;
		return true;
	}



	public function plan_proyectos_estadosSPResult(&$resultado,&$numfilas)
	{
		if (!$this->plan_proyectos_estadosSP($spnombre,$sparam))
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

		$datos['planproyectofdesde']=FuncionesPHPLocal::ConvertirFecha( $datos['planproyectofdesde'],'dd/mm/aaaa','aaaa-mm-dd');
		$datos['planproyectofhasta']=FuncionesPHPLocal::ConvertirFecha( $datos['planproyectofhasta'],'dd/mm/aaaa','aaaa-mm-dd');
		$datos['planproyectoestado'] = ACTIVO;
		$this->_SetearNull($datos);
		if (!parent::Insertar($datos,$codigoinsertado))
			return false;
		$datos['planproyectocod'] =$codigoinsertado;
		if (!$this->Publicar($datos))
			return false;

		return true;
	}



	public function Modificar($datos)
	{
		if (!$this->_ValidarModificar($datos))
			return false;

		$datos['planproyectofdesde']=FuncionesPHPLocal::ConvertirFecha( $datos['planproyectofdesde'],'dd/mm/aaaa','aaaa-mm-dd');
		$datos['planproyectofhasta']=FuncionesPHPLocal::ConvertirFecha( $datos['planproyectofhasta'],'dd/mm/aaaa','aaaa-mm-dd');
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

		$datosmodif['planproyectocod'] = $datos['planproyectocod'];
		$datosmodif['planproyectoestado'] = ELIMINADO;
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
		$datosmodif['planproyectocod'] = $datos['planproyectocod'];
		$datosmodif['planproyectoestado'] = ACTIVO;
		if (!$this->ModificarEstado($datosmodif))
			return false;
		return true;
	}



	public function DesActivar($datos)
	{
		$datosmodif['planproyectocod'] = $datos['planproyectocod'];
		$datosmodif['planproyectoestado'] = NOACTIVO;
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
		$nombrearchivo = "plan_proyectos";
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
		$datos['planproyectoestado'] = ACTIVO;
		if(!$this->BusquedaAvanzada($datos,$resultados,$numfilas))
			return false;
		if($numfilas>0)
		{
			while($fila = $this->conexion->ObtenerSiguienteRegistro($resultados))
			{
				$array[$fila['planproyectocod']] = $fila;
			}
		}
		return true;
	}



	public function PublicarJsonxCodigo($datos)
	{
		$nombrearchivo = "plan_proyectos_".$datos['planproyectocod'];
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
				$array[$fila['planproyectocod']] = $fila;
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


		if (!isset($datos['planproyectonombre']) || $datos['planproyectonombre']=="")
			$datos['planproyectonombre']="NULL";

		if (!isset($datos['planproyectodescripcion']) || $datos['planproyectodescripcion']=="")
			$datos['planproyectodescripcion']="NULL";

		if (!isset($datos['planproyectoobjetivo']) || $datos['planproyectoobjetivo']=="")
			$datos['planproyectoobjetivo']="NULL";

		if (!isset($datos['planobjetivocod']) || $datos['planobjetivocod']=="")
			$datos['planobjetivocod']="NULL";

		if (!isset($datos['planjurisdiccioncod']) || $datos['planjurisdiccioncod']=="")
			$datos['planjurisdiccioncod']="NULL";

		if (!isset($datos['planproyectofdesde']) || $datos['planproyectofdesde']=="")
			$datos['planproyectofdesde']="NULL";

		if (!isset($datos['planproyectofhasta']) || $datos['planproyectofhasta']=="")
			$datos['planproyectofhasta']="NULL";

		if (!isset($datos['planproyectoestadocod']) || $datos['planproyectoestadocod']=="")
			$datos['planproyectoestadocod']="NULL";
		return true;
	}



	private function _ValidarDatosVacios($datos)
	{


		if (!isset($datos['planproyectonombre']) || $datos['planproyectonombre']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Debe ingresar un nombre",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		if (!isset($datos['planproyectodescripcion']) || $datos['planproyectodescripcion']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Debe ingresar una descripción",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		if (!isset($datos['planproyectoobjetivo']) || $datos['planproyectoobjetivo']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Debe ingresar un objetivo",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		if (!isset($datos['planobjetivocod']) || $datos['planobjetivocod']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Debe ingresar un código de objetivo",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		if (!FuncionesPHPLocal::ValidarContenido($this->conexion,$datos['planobjetivocod'],"NumericoEntero"))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error debe ingresar un campo numerico.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		if (!$this->conexion->TraerCampo('plan_objetivos','planobjetivocod',array('planobjetivocod='.$datos['planobjetivocod']),$dato,$numfilas,$errno))
			return false;


		if ($numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error debe ingresar un campo valido.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}


		if (!isset($datos['planjurisdiccioncod']) || $datos['planjurisdiccioncod']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Debe ingresar una jurisdicción",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		if (!FuncionesPHPLocal::ValidarContenido($this->conexion,$datos['planjurisdiccioncod'],"NumericoEntero"))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error debe ingresar un campo numerico.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		if (!$this->conexion->TraerCampo('plan_jurisdicciones','planjurisdiccioncod',array('planjurisdiccioncod='.$datos['planjurisdiccioncod']),$dato,$numfilas,$errno))
			return false;


		if ($numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error debe ingresar un campo valido.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		if (!isset($datos['planproyectofdesde']) || $datos['planproyectofdesde']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Debe ingresar una fecha desde",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		if (!FuncionesPHPLocal::ValidarContenido($this->conexion,$datos['planproyectofdesde'],"FechaDDMMAAAA"))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error debe ingresar una fecha valida.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		if (!isset($datos['planproyectofhasta']) || $datos['planproyectofhasta']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Debe ingresar una fecha hasta",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		if (!FuncionesPHPLocal::ValidarContenido($this->conexion,$datos['planproyectofhasta'],"FechaDDMMAAAA"))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error debe ingresar una fecha valida.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		$fechainicio = strtotime(FuncionesPHPLocal::ConvertirFecha( $datos['planproyectofdesde'],'dd/mm/aaaa','aaaa-mm-dd'));
		$fechafin = strtotime(FuncionesPHPLocal::ConvertirFecha( $datos['planproyectofhasta'],'dd/mm/aaaa','aaaa-mm-dd'));
		if($fechainicio>$fechafin)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error la fecha de inicio debe ser menor a la fecha de fin.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
			
		}

		if (!isset($datos['planproyectoestadocod']) || $datos['planproyectoestadocod']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Debe ingresar un estado",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		if (!FuncionesPHPLocal::ValidarContenido($this->conexion,$datos['planproyectoestadocod'],"NumericoEntero"))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error debe ingresar un campo numerico.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}


		if (!$this->conexion->TraerCampo('plan_proyectos_estados','planproyectoestadocod',array('planproyectoestadocod='.$datos['planproyectoestadocod']),$dato,$numfilas,$errno))
			return false;


		if ($numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error debe ingresar un campo valido.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		return true;
	}





}
?>