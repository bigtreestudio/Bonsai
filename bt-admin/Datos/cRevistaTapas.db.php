<?php  
abstract class cRevistaTapasdb
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


	protected function BusquedaAvanzada($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_rev_tapas_xbusqueda_avanzada";
		$sparam=array(
			'pxrevtapatitulo'=> $datos['xrevtapatitulo'],
			'prevtapatitulo'=> $datos['revtapatitulo'],
			'pxrevtapanumero'=> $datos['xrevtapanumero'],
			'prevtapanumero'=> $datos['revtapanumero'],			
			'porderby'=> $datos['orderby'],
			'plimit'=> $datos['limit']
			);
			
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar las Tapas.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	}

	protected function BuscarxCodigo($datos,&$resultado,&$numfilas)
	{

		$spnombre="sel_rev_tapas_xrevtapacod";
		$sparam=array(
			'prevtapacod'=> $datos['revtapacod']
			);	
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar la Tapa por código.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	}


	protected function Eliminar($datos)
	{
		$spnombre="del_rev_tapas_xrevtapacod";
		$sparam=array(
			'prevtapacod'=> $datos['revtapacod']
			);
					
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al eliminar la tapa por código.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	}

	protected function Modificar($datos)
	{
		$spnombre="upd_rev_tapas_xrevtapacod";
		$sparam=array(
			'prevtapatitulo'=> $datos['revtapatitulo'],
			'prevtapadesc'=> $datos['revtapadesc'],
			'prevtapafecha'=> $datos['revtapafecha'],
			'prevtapatarget'=> $datos['revtapatarget'],
			'prevtapanumero'=> $datos['revtapanumero'],
			'prevtapalink'=> $datos['revtapalink'],
			'prevtapatipocod'=> $datos['revtapatipocod'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			'prevtapacod'=> $datos['revtapacod']
			);
					
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$query,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al modificar la tapa. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		return true;
	}



	protected function Insertar($datos,&$codigoinsertado)
	{			
		$spnombre="ins_rev_tapas";
		$sparam=array(
			'prevtapatitulo'=> $datos['revtapatitulo'],
			'prevtapatipocod'=> $datos['revtapatipocod'],
			'prevtapadesc'=> $datos['revtapadesc'],
			'prevtapafecha'=> $datos['revtapafecha'],
			'prevtapatarget'=> $datos['revtapatarget'],
			'prevtapanumero'=> $datos['revtapanumero'],
			'prevtapalink'=> $datos['revtapalink'],
			'prevtapaestado'=> $datos['revtapaestado'],
			'prevtapafalta'=> date("Y/m/d H:i:s"),
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s")
			);
				
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$query,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al insertar una nueva tapa. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		$codigoinsertado=$this->conexion->UltimoCodigoInsertado();

		return true;
	}

	
	protected function ModificarEstado($datos)
	{
		$spnombre="upd_rev_tapas_estado_xrevtapacod";
		$sparam=array(
			'prevtapaestado'=> $datos['revtapaestado'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			'prevtapacod'=> $datos['revtapacod']
			);
			
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$query,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al modificar el estado de la tapa ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		return true;	
	}
		

	protected function ActualizarImagen($datos)
	{
		$spnombre="upd_rev_tapas_imagen_xrevtapacod";
		$sparam=array(
			'prevtapaarchubic'=> $datos['revtapaarchubic'],
			'prevtapaarchnombre'=> $datos['revtapaarchnombre'],
			'prevtapaarchsize'=> $datos['revtapaarchsize'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			'prevtapacod'=> $datos['revtapacod']
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al insertar la tapa. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		return true;
	
	}	

}
?>