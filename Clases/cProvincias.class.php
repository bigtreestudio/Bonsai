<?php 
//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 
// Clase con la lógica para el manejo de las noticias relacionadas

class cProvincias	
{
	protected $conexion;
	
	// Constructor de la clase
	public function __construct($conexion){
		$this->conexion = &$conexion;
    } 
	
	// Destructor de la clase
	public function __destruct() {	

    }
	
	public function BuscarxCodigo($provinciacod)
	{
		if(isset($provinciacod) && $provinciacod!="" && file_exists(PUBLICA."json/provincias.json"))
		{
			$string = file_get_contents(PUBLICA."json/provincias.json");
			$provincias = json_decode($string,true);
			if (isset($provincias[$provinciacod]))
				return $provincias[$provinciacod];
			else
				return array();
		}
		else
		{
			return false;
		}
		
	}
	
	public function BuscarProvincias()
	{
		$string = file_get_contents(PUBLICA."json/provincias.json");
		$provincias = json_decode($string,true);
		return $provincias;
	}
			
}//FIN CLASE

?>