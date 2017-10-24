<?php 
//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 

class cNoticiasEstadisticas
{
	
	protected $conexion;
	
	// Constructor de la clase
	public function __construct($conexion){
		$this->conexion = &$conexion;
    } 
	
	// Destructor de la clase
	public function __destruct() {	

    } 	

	public function BuscarxNoticia($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_not_noticias_estadisticas_xnoticiacod";
		$sparam=array(
			'pnoticiacod'=> $datos['noticiacod']
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,"Error al buscar las estadisticas de la noticia.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__));
			return false;
		}

		return true;	
	}
	

	public function SumarCantidad($datos)
	{
		
		if(!$this->BuscarxNoticia($datos,$resultado,$numfilas))
			return false;
			
		if ($numfilas==1)
		{	
			$spnombre="upd_not_noticias_estadisticas_visualizacion_xnoticiacod";
			$sparam=array(
				'pnoticiacod'=> $datos['noticiacod']
				);
			if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
			{
				FuncionesPHPLocal::MostrarMensaje($this->conexion,"Error al sumar al visualizador de noticias.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__));
				return false;
			}
		}else
		{
			if(!$this->InsertarCantidad($datos))
				return false;
		}

		return true;	
	}


	public function InsertarCantidad($datos)
	{

		$spnombre="ins_not_noticias_estadisticas_xnoticiacod";
		$sparam=array(
			'pnoticiacod'=> $datos['noticiacod']
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,"Error al sumar al visualizador de noticias.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__));
			return false;
		}

		return true;	
	}


	

			
}//FIN CLASE
?>