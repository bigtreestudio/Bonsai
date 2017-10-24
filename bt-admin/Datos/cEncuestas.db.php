<?php  
abstract class cEncuestasdb
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


	protected function Buscar(&$resultado,&$numfilas)
	{
		$spnombre="sel_enc_encuestas";
		$sparam=array(
			);		
		
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar las encuestas.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	}
	


	protected function BuscarxCodigo($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_enc_encuestas_xencuestacod";
		$sparam=array(
			'pencuestacod'=> $datos['encuestacod']
			);	
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar la encuesta por código.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	}
	
	
	protected function BusquedaAvanzada($datos,&$resultado,&$numfilas)
	{
		
		$spnombre="sel_enc_encuestas_xbusqueda_avanzada";
		$sparam=array(
			'pxencuestapregunta'=> $datos['xencuestapregunta'],
			'pencuestapregunta'=> $datos['encuestapregunta'],
			'pxencuestatipocod'=> $datos['xencuestatipocod'],
			'pencuestatipocod'=> $datos['encuestatipocod'],
			'pxencuestaestado'=> $datos['xencuestaestado'],
			'pencuestaestado'=> $datos['encuestaestado'],			
			'pxcatcod'=> $datos['xcatcod'],
			'pcatcod'=> $datos['catcod'],			
			'porderby'=> $datos['orderby'],
			'plimit'=> $datos['limit']
			);
			
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar las encuestas.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	}





	protected function BuscarEncuestasOpciones($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_enc_encuestas_encuestas_opciones_xencuestacod";
		$sparam=array(
			'pencuestacod'=> $datos['encuestacod'],
		);
			
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar las encuestas.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	}




	protected function EliminarEncuesta($datos)
	{
		$spnombre="del_enc_encuestas_xencuestacod";
		$sparam=array(
			'pencuestacod'=> $datos['encuestacod']
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al eliminar la encuesta por código.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	}




	protected function ModificarEncuesta($datos)
	{
		$spnombre="upd_enc_encuestas_xencuestacod";
		$sparam=array(
			'pencuestatipocod'=> $datos['encuestatipocod'],
			'pencuestapregunta'=> $datos['encuestapregunta'],
			'pcatcod'=> $datos['catcod'],
			'pencuestaestado'=> $datos['encuestaestado'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			'pencuestacod'=> $datos['encuestacod']
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$query,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al modificar la encuesta. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		return true;
	}


	protected function InsertarEncuesta($datos,&$codigoinsertado)
	{			
		$spnombre="ins_enc_encuestas";
		$sparam=array(
			'pencuestatipocod'=> $datos['encuestatipocod'],
			'pencuestapregunta'=> $datos['encuestapregunta'],
			'pcatcod'=> $datos['catcod'],
			'pencuestaestado'=> $datos['encuestaestado'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s")
			);
				
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$query,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al insertar una nueva encuesta. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		$codigoinsertado=$this->conexion->UltimoCodigoInsertado();

		return true;
	}



	protected function ModificarEstadoEncuesta($datos)
	{
		$spnombre="upd_enc_encuestas_estado_xencuestacod";
		$sparam=array(
			'pencuestaestado'=> $datos['encuestaestado'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			'pencuestacod'=> $datos['encuestacod']
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$query,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al modificar el estado de la encuesta. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		return true;	
	}

}
?>