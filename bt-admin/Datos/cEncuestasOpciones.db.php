<?php  
abstract class cEncuestasOpcionesdb
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


	protected function BuscarxCodigo($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_enc_encuestas_opciones_xencuestacod_opcioncod";
		$sparam=array(
			'pencuestacod'=> $datos['encuestacod'],
			'popcioncod'=> $datos['opcioncod']
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar las opciones por código de encuesta.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	}


	protected function BuscarxCodigoEncuestacod($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_enc_encuestas_opciones_xencuestacod_avanzado";
		$sparam=array(
			'pencuestacod'=> $datos['encuestacod'],
			'porderby'=> $datos['orderby'],
			'plimit'=> $datos['limit']
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar las opciones por código de encuesta.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
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



	protected function BuscarEncuestaUltimoOrden($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_enc_encuestas_opciones_maxorden";
		$sparam=array(
			"pencuestacod"=>$datos['encuestacod']
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar el proximo orden.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;	
	}	


	protected function Insertar($datos,&$codigoinsertado)
	{			
		$spnombre="ins_enc_encuestas_opciones";
		$sparam=array(
			'pencuestacod'=> $datos['encuestacod'],
			'popcionnombre'=> $datos['opcionnombre'],
			'popcionorden'=> $datos['opcionorden'],
			'popcioncantvotos'=> $datos['opcioncantvotos'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s")
			);
					
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$query,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al insertar una nueva opcion. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		$codigoinsertado=$this->conexion->UltimoCodigoInsertado();

		return true;
	}

	
	
	
	protected function Modificar($datos)
	{
		$spnombre="upd_enc_encuestas_opciones_xopcioncod";
		$sparam=array(
			'popcionnombre'=> $datos['opcionnombre'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			'popcioncod'=> $datos['opcioncod']
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$query,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al modificar la opcion de la encuesta. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		return true;
	}


	
	protected function ModificarOrden($datos)
	{

		
		$spnombre="upd_enc_encuestas_opciones_opcionorden_xopcioncod";
		$sparam=array(
			'popcionorden'=> $datos['opcionorden'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			'popcioncod'=> $datos['opcioncod']
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno) )
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al modificar el orden de las opciones. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;
		}
		
		return true;	
	}	
	
	
	protected function Eliminar($datos)
	{
		$spnombre="del_enc_encuestas_opciones_xopcioncod";
		$sparam=array(
			'popcioncod'=> $datos['opcioncod']
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al eliminar la opcion de la encuesta.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	}
	
	
	
	
	protected function EliminarOpcionesxEncuesta($datos)
	{
		$spnombre="del_enc_encuestas_opciones_xencuestacod";
		$sparam=array(
			'pencuestacod'=> $datos['encuestacod']
			);
			
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al eliminar la opcion de la encuesta.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	}


}
?>