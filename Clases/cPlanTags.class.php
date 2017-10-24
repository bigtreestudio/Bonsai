<?php
class cPlanTags
{
	protected $conexion;
	function __construct($conexion){
		$this->conexion = &$conexion;
	}
	public function BuscarxCodigo($datos)
	{
		$archivo = "plan_tags_".$datos['plantagcod'].".json";
		if(file_exists(PUBLICA."json/Plan/".$archivo))
		{
			$string = file_get_contents(PUBLICA."json/Plan/".$archivo);
			$arrayJson = json_decode($string,true);
			$array = FuncionesPHPLocal::ConvertiraUtf8($arrayJson[$datos['plantagcod']]);
			return $array;
		}
		else
			return false;
	}


	public function BusquedaListado()
	{
		$archivo = "plan_tags.json";
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
			FuncionesPHPLocal::MostrarMensaje($this->conexion,"Error al buscar el archivo multimedia por codigo y multimedia. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;
		}
		return true;
	}




}
?>