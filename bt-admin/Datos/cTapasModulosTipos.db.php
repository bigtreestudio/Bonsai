<?php  
abstract class cTapasModulosTiposdb
{
	
	// Constructor de la clase
	function __construct(){


    } 
	
	// Destructor de la clase
	function __destruct() {	
    } 	

	
//-----------------------------------------------------------------------------------------
//							 PUBLICAS	
//----------------------------------------------------------------------------------------- 

//----------------------------------------------------------------------------------------- 



	protected function BuscarSP(&$spnombre,&$sparam)
	{
		$spnombre="sel_tap_modulos_tipos";
		$sparam=array(
			);
		
		return true;
	}



}
?>