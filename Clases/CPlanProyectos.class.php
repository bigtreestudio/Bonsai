<?php
class cPlanProyectos
{
	protected $conexion;
	function __construct($conexion){
		$this->conexion = &$conexion;
	}
	public function BuscarxCodigo($datos)
	{
		$archivo = "plan_proyectos_".$datos['planproyectocod'].".json";
		if(file_exists(PUBLICA."json/Plan/".$archivo))
		{
			$string = file_get_contents(PUBLICA."json/Plan/".$archivo);
			$arrayJson = json_decode($string,true);
			$array = FuncionesPHPLocal::ConvertiraUtf8($arrayJson[$datos['planproyectocod']]);
			return $array;
		}
		else
			return false;
	}


	public function BusquedaListado()
	{
		$archivo = "plan_proyectos.json";
		if(file_exists(PUBLICA."json/Plan/".$archivo))
		{
			$string = file_get_contents(PUBLICA."json/Plan/".$archivo);
			$arrayJson = json_decode($string,true);
			$array = FuncionesPHPLocal::ConvertiraUtf8($arrayJson);
			return $array;
		}
		else
			return false;
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
			FuncionesPHPLocal::MostrarMensaje($this->conexion,"Error al buscar el archivo multimedia por codigo y multimedia. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
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
			FuncionesPHPLocal::MostrarMensaje($this->conexion,"Error al buscar el archivo multimedia por codigo y multimedia. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
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
			FuncionesPHPLocal::MostrarMensaje($this->conexion,"Error al buscar el archivo multimedia por codigo y multimedia. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;
		}
		return true;
	}




}
?>