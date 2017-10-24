<?php  
include(DIR_CLASES_DB."cUsuariosAccesos.db.php");

class cUsuariosAccesos extends cUsuariosAccesosdb
{
	protected $conexion;
	protected $formato;
	
	
	// Constructor de la clase
	function __construct($conexion,$formato=FMT_TEXTO){
		$this->conexion = &$conexion;
		$this->formato = $formato;
		parent::__construct(); 
    } 
	
	// Destructor de la clase
	function __destruct() {	
		parent::__destruct(); 
    } 	



//-----------------------------------------------------------------------------------------
//							 PUBLICAS	
//----------------------------------------------------------------------------------------- 

//----------------------------------------------------------------------------------------- 
	public function BuscarMiUltimoAcceso (&$resultado,&$numfilas)
	{
		$datos['usuariocod'] = $_SESSION['usuariocod'];
		if (!parent::BuscarUltimoAcceso ($datos,$resultado,$numfilas))
			return false;
		return true;	
	}

	
	public function Insertar($datos)
	{	

		$sistemaoperativo = $_SERVER["HTTP_USER_AGENT"];
		if (strstr($sistemaoperativo,'Win')) {
			$sistemaoperativo='Windows';
		} else if (strstr($sistemaoperativo,'Mac')) {
			$sistemaoperativo='Mac OS';
		} else if (strstr($sistemaoperativo,'Linux')) {
			$sistemaoperativo='Linux';
		} else if (strstr($sistemaoperativo,'Unix')) {
			$sistemaoperativo='Unix';
		} else {
			$sistemaoperativo='Otro';
		}		

		$datos=array(
			'usuariocod'=> $_SESSION['usuariocod'],
			'usuarioip'=> $_SERVER['REMOTE_ADDR'],
			'usuarioso'=> $sistemaoperativo,
			'usuarionavegador'=> $_SERVER['HTTP_USER_AGENT'],
			'usuariofecha'=> date("Y/m/d H:i:s")
			);				
		if (!parent::Insertar ($datos))
			return false;
			
		return true;
	}
	

}


?>