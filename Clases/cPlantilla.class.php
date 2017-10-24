<?php 
//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 
// Clase con la lógica para el manejo de las noticias relacionadas

class cProvincias
{
	protected $conexion;
	protected $datosnoticia;
	protected $multimedia;
	protected $relacionadas;
	protected $tags;
	
	// Constructor de la clase
	public function __construct($conexion){
		$this->conexion = &$conexion;
    } 
	
	// Destructor de la clase
	public function __destruct() {	

    } 	

	public function ProvinciasSP(&$spnombre,&$sparam)
	{
		$spnombre="sel_provincias";
		$sparam=array(
			'porderby'=> "provinciadesc"
			);		
		return true;
	}

	
			
}//FIN CLASE

?>