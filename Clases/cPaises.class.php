<?php 
//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 
// Clase con la lógica para el manejo de las noticias relacionadas

class cPaises	
{
	protected $conexion;
	
	// Constructor de la clase
	public function __construct($conexion){
		$this->conexion = &$conexion;
    } 
	
	// Destructor de la clase
	public function __destruct() {	

    }
	
	public function BuscarxCodigo($paiscod)
	{
		if(isset($paiscod) && $paiscod!="" && file_exists(PUBLICA."json/paises.json"))
		{
			$string = file_get_contents(PUBLICA."json/paises.json");
			$paises = json_decode($string,true);
			if (isset($paises[$paiscod]))
				return $paises[$paiscod];
			else
				return array();
		}
		else
		{
			return false;
		}
		
	}
	
	public function BuscarPaises()
	{
		$string = file_get_contents(PUBLICA."json/paises.json");
		$paises = json_decode($string,true);
		return $paises;
	}
			
}//FIN CLASE

?>