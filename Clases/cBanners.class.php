<?php 
//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 

class cBanners
{
	
	protected $conexion;
	
	// Constructor de la clase
	public function __construct($conexion){
		$this->conexion = &$conexion;
    } 
	
	// Destructor de la clase
	public function __destruct() {	

    } 	

	
	public function BuscarxCodigo($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_ban_banners_xbannercod";
		$sparam=array(
			'pbannercod'=> $datos['bannercod']
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,"Error al buscar el banner.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__));
			return false;
		}

		return true;	
	}

	public function SumarCantidad($datos)
	{
		$spnombre="upd_ban_banners_contador_xbannercod";
		$sparam=array(
			'pbannercod'=> $datos['bannercod']
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,"Error al sumar al contador de banners.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__));
			return false;
		}

		return true;	
	}


	

			
}//FIN CLASE
?>