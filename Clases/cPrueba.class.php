<?php
class cPrueba
{
	protected $conexion;
	function __construct($conexion){
		$this->conexion = &$conexion;
	}
	public function BuscarxCodigo($datos)
	{
		$archivo = "prue_prueba_".$datos['pruebacod'].".json";
		if(file_exists(PUBLICA."json/prue_prueba/".$archivo))
		{
			$string = file_get_contents(PUBLICA."json/prue_prueba/".$archivo);
			$arrayJson = json_decode($string,true);
			$array = FuncionesPHPLocal::ConvertiraUtf8($arrayJson[$datos['pruebacod']]);
			return $array;
		}
		else
			return false;
	}


	public function BusquedaListado()
	{
		$archivo = "prue_prueba.json";
		if(file_exists(PUBLICA."json/prue_prueba/".$archivo))
		{
			$string = file_get_contents(PUBLICA."json/prue_prueba/".$archivo);
			$arrayJson = json_decode($string,true);
			$array = FuncionesPHPLocal::ConvertiraUtf8($arrayJson);
			return $array;
		}
		else
			return false;
	}




}
?>