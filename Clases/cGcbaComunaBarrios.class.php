<?php
class cGcbaComunaBarrios
{
	protected $conexion;
	function __construct($conexion){
		$this->conexion = &$conexion;
	}
	public function BuscarxCodigo($datos)
	{
		$archivo = "gcba_comunas_barrios_".$datos['comunabarriocod'].".json";
		if(file_exists(PUBLICA."json/gcbabarrios/".$archivo))
		{
			$string = file_get_contents(PUBLICA."json/gcbabarrios/".$archivo);
			$arrayJson = json_decode($string,true);
			$array = FuncionesPHPLocal::ConvertiraUtf8($arrayJson[$datos['comunabarriocod']]);
			return $array;
		}
		else
			return false;
	}


	public function BusquedaListado()
	{
		$archivo = "gcba_comunas_barrios.json";
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


	public function gcba_comunasSP(&$spnombre,&$sparam)
	{
		if (!parent::gcba_comunasSP($spnombre,$sparam))
			return false;
		return true;
	}


	public function gcba_comunasSPResult(&$resultado,&$numfilas)
	{
		if (!$this->gcba_comunasSP($spnombre,$sparam))
			return false;

		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno) )
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,"Error al buscar el archivo multimedia por codigo y multimedia. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;
		}
		return true;
	}


	public function gcba_barriosSP(&$spnombre,&$sparam)
	{
		if (!parent::gcba_barriosSP($spnombre,$sparam))
			return false;
		return true;
	}


	public function gcba_barriosSPResult(&$resultado,&$numfilas)
	{
		if (!$this->gcba_barriosSP($spnombre,$sparam))
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