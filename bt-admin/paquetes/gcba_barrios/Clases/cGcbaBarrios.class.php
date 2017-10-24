<?php
class cGcbaBarrios
{
	protected $conexion;
	function __construct($conexion){
		$this->conexion = &$conexion;
	}
	public function BuscarxCodigo($datos)
	{
		$archivo = "gcba_barrios_".$datos['barriocod'].".json";
		if(file_exists(PUBLICA."json/gcbabarrios/".$archivo))
		{
			$string = file_get_contents(PUBLICA."json/gcbabarrios/".$archivo);
			$arrayJson = json_decode($string,true);
			$array = FuncionesPHPLocal::ConvertiraUtf8($arrayJson[$datos['barriocod']]);
			return $array;
		}
		else
			return false;
	}


	public function BusquedaListado()
	{
		$archivo = "gcba_barrios.json";
		if(file_exists(PUBLICA."json/gcbabarrios/".$archivo))
		{
			$string = file_get_contents(PUBLICA."json/gcbabarrios/".$archivo);
			$arrayJson = json_decode($string,true);
			$array = FuncionesPHPLocal::ConvertiraUtf8($arrayJson);
			return $array;
		}
		else
			return false;
	}




}
?>