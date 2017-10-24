<?php
class cPlanTagsCategorias
{
	protected $conexion;
	function __construct($conexion){
		$this->conexion = &$conexion;
	}
	public function BuscarxCodigo($datos)
	{
		$archivo = "plan_tags_categorias_".$datos['plantagcatcod'].".json";
		if(file_exists(PUBLICA."json/Plan/".$archivo))
		{
			$string = file_get_contents(PUBLICA."json/Plan/".$archivo);
			$arrayJson = json_decode($string,true);
			$array = FuncionesPHPLocal::ConvertiraUtf8($arrayJson[$datos['plantagcatcod']]);
			return $array;
		}
		else
			return false;
	}


	public function BusquedaListado()
	{
		$archivo = "plan_tags_categorias.json";
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