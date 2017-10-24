<?php  
abstract class cEncuestasRespuestasdb
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


	protected function BuscarporCodigos($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_enc_encuestas_respuestas_xencuestacod_xopcioncod";
		$sparam=array(
			'pencuestacod'=> $datos['encuestacod'],
			'popcioncod'=> $datos['opcioncod']
		);
	
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar las respuestas.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	}
	
	
	protected function BuscarporCodigoEncuesta($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_enc_encuestas_respuestas_xencuestacod";
		$sparam=array(
			'pencuestacod'=> $datos['encuestacod']
			);	
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar las respuestas por codigo de encuesta.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	}
	



	protected function BuscarCantidadRespuestasxEncuesta($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_enc_encuestas_totales_xencuestacod";
		$sparam=array(
			'pencuestacod'=> $datos['encuestacod']
		);
	
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar las respuestas.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	}
	
	
	protected function Insertar($datos,&$codigoinsertado)
	{
		$spnombre="ins_enc_encuestas_respuestas";
		$sparam=array(
			'pencuestacod'=> $datos['encuestacod'],
			'popcioncod'=> $datos['opcioncod'],
			'prespuestaip'=> $datos['respuestaip'],
			'prespuestaso'=> $datos['respuestaso'],
			'prespuestanavegador'=> $datos['respuestanavegador'],
			'prespuestafecha'=> $datos['respuestafecha']
			);				
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$query,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al votar una encuesta. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		$codigoinsertado=$this->conexion->UltimoCodigoInsertado();

		return true;
	}
}
?>