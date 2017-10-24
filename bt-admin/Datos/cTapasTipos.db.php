<?php  
abstract class cTapasTiposdb
{
	
	// Constructor de la clase
	function __construct(){


    } 
	
	// Destructor de la clase
	function __destruct() {	
    } 	

//							 PUBLICAS	
//----------------------------------------------------------------------------------------- 

//----------------------------------------------------------------------------------------- 
	   protected function TapasTiposSP(&$spnombre,&$sparam)
       {
		$spnombre="sel_tap_tapas_tipos";
		$sparam=array(
			);
   
		   return true;
       }
	   
	   
	   
	   
	   
	protected function BuscarxCodigo($datos,&$resultado,&$numfilas)
	{

		$spnombre="sel_tap_tapas_tipos_xtapatipocod";
		$sparam=array(
			'ptapatipocod'=> $datos['tapatipocod']
			);
		
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar el tipo de tapa por código.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	}
	
	protected function BuscarxCodigoDeTapaPublicada($datos,&$resultado,&$numfilas)
	{

		$spnombre="sel_tap_tapas_tipos_xtapacodpublicada";
		$sparam=array(
			'ptapacodpublicada'=> $datos['tapacod']
			);
		
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar la tapa publicada.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	}	
	
	
	protected function BuscarxTipoHome($datos,&$resultado,&$numfilas)
	{

		$spnombre="sel_tap_tapas_tipos_xtapatipohome";
		$sparam=array(
			'ptapatipohome'=> $datos['tapatipohome']
			);		
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar el tipo de tapa por tipo home.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	}
	
	



	protected function BusquedaAvanzada($datos,&$resultado,&$numfilas)
	{
		
		$spnombre="sel_tap_tapas_tipos_xbusqueda_avanzada";
		$sparam=array(
			'pxtapatipodesc'=> $datos['xtapatipodesc'],
			'ptapatipodesc'=> $datos['tapatipodesc'],
			'pxtapatipoestado'=> $datos['xtapatipoestado'],
			'ptapatipoestado'=> $datos['tapatipoestado'],
			'porderby'=> $datos['orderby'],
			'plimit'=> $datos['limit']
			);
			
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar los tipos de tapa.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	}





	protected function Eliminar($datos)
	{
		$spnombre="del_tap_tapas_tipos_xtapatipocod";
		$sparam=array(
			'ptapatipocod'=> $datos['tapatipocod']
			);
		
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al eliminar el tipo de tapa por código.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	}




	protected function Modificar($datos)
	{
		
		$spnombre="upd_tap_tapas_tipos_xtapatipocod";
		$sparam=array(
			'ptapatipodesc'=> $datos['tapatipodesc'],
			'ptapatipoarchivo'=> $datos['tapatipoarchivo'],
			'ptapacodpublicada'=> $datos['tapacodpublicada'],
			'ptapatipourlfriendly'=> $datos['tapatipourlfriendly'],
			'ptapatipohome'=> $datos['tapatipohome'],
			'pmenutipocod'=> $datos['menutipocod'],
			'pmenucod'=> $datos['menucod'],
			'pfondocod'=> $datos['fondocod'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			'ptapatipocod'=> $datos['tapatipocod']
			);

		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$query,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al modificar el tipo de tapa. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		return true;
	}

	protected function ModificarTapaPublicada($datos)
	{
		$spnombre="upd_tap_tapas_tapacodpublicada_tipos_xtapatipocod";
		$sparam=array(
			'ptapacodpublicada'=> $datos['tapacod'],
			'ptapatipocod'=> $datos['tapatipocod']
			);

		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$query,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al modificar la tapa publicada. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		return true;
	}	


	protected function Insertar($datos,&$codigoinsertado)
	{			
		$spnombre="ins_tap_tapas_tipos";
		$sparam=array(
			'ptapatipodesc'=> $datos['tapatipodesc'],
			'ptapatipoarchivo'=> $datos['tapatipoarchivo'],
			'ptapacodpublicada'=> $datos['tapacodpublicada'],
			'ptapatipourlfriendly'=> $datos['tapatipourlfriendly'],
			'ptapatipoestado'=> $datos['tapatipoestado'],
			'ptapatipohome'=> $datos['tapatipohome'],
			'pmenutipocod'=> $datos['menutipocod'],
			'pmenucod'=> $datos['menucod'],
			'pfondocod'=> $datos['fondocod'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s")
			);				
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$query,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al insertar un tipo de tapa. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		$codigoinsertado=$this->conexion->UltimoCodigoInsertado();

		return true;
	}



	protected function ModificarEstado($datos)
	{
		$spnombre="upd_tap_tapas_tipos_estado_xtapatipocod";
		$sparam=array(
			'ptapatipoestado'=> $datos['tapatipoestado'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			'ptapatipocod'=> $datos['tapatipocod']
			);

		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$query,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al modificar el estado del tipo de tapa. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		return true;	
	}
	   
	   
	   
	protected function ModificarArchivo($datos)
	{
		$spnombre="upd_tap_tapas_tipos_archivo_tapatipoarchivo_xtapatipocod";
		$sparam=array(
			'ptapatipoarchivo'=> $datos['tapatipoarchivo'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			'ptapatipocod'=> $datos['tapatipocod']
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$query,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al modificar el nombre del archivo del tipo de tapa. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		return true;	
	}
	   
}
?>