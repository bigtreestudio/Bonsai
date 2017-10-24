<?php 
class cAlbums
{

	protected $conexion;
	function __construct($conexion){
		$this->conexion = &$conexion;
	}

	public function BuscarxCodigo($datos)
	{
		$archivo = "albums_".$datos['albumcod'].".json";
		if(file_exists(PUBLICA.$archivo))
		{
			$string = file_get_contents(PUBLICA.$archivo);
			$arrayJson = json_decode($string,true);
			$array = FuncionesPHPLocal::ConvertiraUtf8($arrayJson[$datos['albumcod']]);
			return $array;
		}
		else
			return array();
	}
	
}
?>