<?php 
class cGalerias
{

	protected $conexion;
	function __construct($conexion){
		$this->conexion = &$conexion;
	}

	public function BusquedaListado()
	{
		$archivo = "galerias.json";
		if(file_exists(PUBLICA.$archivo))
		{
			$string = file_get_contents(PUBLICA.$archivo);
			$arrayJson = json_decode($string,true);
			$array = FuncionesPHPLocal::ConvertiraUtf8($arrayJson);
			$array = json_decode($string,true);
			return $array;
		}
		else
			return array();
	}



	public function BuscarxCodigo($datos)
	{
		$archivo = "galeria_".$datos['galeriacod'].".json";
		if(file_exists(PUBLICA.$archivo))
		{
			$string = file_get_contents(PUBLICA.$archivo);
			$arrayJson = json_decode($string,true);
			$array = FuncionesPHPLocal::ConvertiraUtf8($arrayJson[$datos['galeriacod']]);
			return $array;
		}
		else
			return array();
	}
	
	
	public function BuscarMultimediaxCodigo($datos)
	{
		$archivo = "galeria_".$datos['galeriacod'].".json";
		if(file_exists(PUBLICA.$archivo))
		{
			$string = file_get_contents(PUBLICA.$archivo);
			$arrayJson = json_decode($string,true);
			$arraygaleria = FuncionesPHPLocal::ConvertiraUtf8($arrayJson[$datos['galeriacod']]);
			$array = array();
			if(isset($arraygaleria['multimedias']['fotos'][$datos['multimediacod']]))
				$array = $arraygaleria['multimedias']['fotos'][$datos['multimediacod']];
			if(isset($arraygaleria['multimedias']['videos'][$datos['multimediacod']]))
				$array = $arraygaleria['multimedias']['videos'][$datos['multimediacod']];	
			if(isset($arraygaleria['multimedias']['audios'][$datos['multimediacod']]))
				$array = $arraygaleria['multimedias']['audios'][$datos['multimediacod']];
				
			return $array;		
							
		}
		else
			return array();
	}





}
?>