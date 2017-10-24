<?php  
abstract class cCronesEjecuciondb
{
	
	// Constructor de la clase
	function __construct(){


    } 
	
	// Destructor de la clase
	function __destruct() {	
    } 	

	

	protected function BuscarErroresxEjecutobien($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_cron_crones_ejecucion_xejecutobien";
		$sparam=array(
			'pejecutobien'=> $datos['ejecutobien'],
			'penviado'=> $datos['enviado']
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje("Error al buscar la ejecutobien del cron ");
			return false;
		}
		$codigoinsertado=$this->conexion->UltimoCodigoInsertado();
		return true;
	}
	
	
	
	protected function Insertar($datos,&$codigoinsertado)
	{
		$spnombre="ins_cron_crones_ejecucion";
		$sparam=array(
			'pcroncod'=> $datos['croncod'],
			'pfinicio'=> date("Y-m-d H:i:s")
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje("Error al insertar la ejecutobien del cron ");
			return false;
		}
		$codigoinsertado=$this->conexion->UltimoCodigoInsertado();
		return true;
	}
	
	
	
	protected function ActualizarFechaFinxCroncod($datos)
	{
		$spnombre="upd_cron_crones_ejecucion_xcronejecucioncod";
		$sparam=array(
			'pcronejecucioncod'=> $datos['cronejecucioncod'],
			'pffin'=> date("Y-m-d H:i:s"),
			'pejecutobien'=> $datos['ejecutobien'],
			'pjson'=> $datos['json']   
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje("Error al insertar la ejecutobien del cron ");
			return false;
		}

		return true;
	}


	protected function ActualizarCronesEjecucionEnviadoxCronejecucioncod($datos)
	{
		$spnombre="upd_cron_crones_ejecucion_enviado_xcronejecucioncod";
		$sparam=array(
			'penviado'=> $datos['enviado'],
			'pcronejecucioncod' => $datos['cronejecucioncod'],
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,"Error al actualiozar el cron de ejecucion. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
			return false;
		}
		return true;
	}



	

}


?>