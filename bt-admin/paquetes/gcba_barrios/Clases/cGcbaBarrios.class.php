<?php
class cGcbaBarrios
{

	function __construct($conexion){
		$this->conexion = &$conexion;
	}

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





?>