<?php
class cPlanObjetivos
{
	protected $conexion;
	function __construct($conexion){
		$this->conexion = &$conexion;
	}
	public function BuscarxCodigo($datos)
	{
		$archivo = "plan_objetivos_".$datos['planobjetivocod'].".json";
		if(file_exists(PUBLICA."json/Plan/".$archivo))
		{
			$string = file_get_contents(PUBLICA."json/Plan/".$archivo);
			$arrayJson = json_decode($string,true);
			$array = FuncionesPHPLocal::ConvertiraUtf8($arrayJson[$datos['planobjetivocod']]);
			return $array;
		}
		else
			return false;
	}


	public function BusquedaListado()
	{
		$archivo = "plan_objetivos.json";
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




}
?>