<?php  
abstract class cTapasModulosdb
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



	
	protected function BuscarModulosxTapa($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_tap_tapas_zonas_modulos_tap_modulos_xtapacod";
		$sparam=array(
			'ptapacod'=> $datos['tapacod'],
			);
			
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar los Modulos de la tapa.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	}

	protected function BusquedaAvanzada($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_tap_modulos_confeccionar_xbusqueda_avanzada";
		$sparam=array(
			'pxcatcod'=> $datos['xcatcod'],
			'pcatcod'=> $datos['catcod'],
			'pxmodulodesc'=> $datos['xmodulodesc'],
			'pmodulodesc'=> $datos['modulodesc'],
			'pxmoduloestado'=> $datos['xmoduloestado'],
			'pmoduloestado'=> $datos['moduloestado'],
			'pxmodulotipocod'=> $datos['xmodulotipocod'],
			'pmodulotipocod'=> $datos['modulotipocod'],
			'porderby'=> $datos['orderby'],
			'plimit'=> $datos['limit']
			);
			
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar los Modulos.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	}

	protected function BuscarxCodigo($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_tap_modulos_modulos_categorias_xmodulocod";
		$sparam=array(
			'pmodulocod'=> $datos['modulocod']
			);	
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar el modulo por código.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	}


	protected function EliminarModulo($datos)
	{
		$spnombre="del_tap_modulos_xmodulocod";
		$sparam=array(
			'pmodulocod'=> $datos['modulocod']
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al eliminar el Modulo por código.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	}
	
	protected function EliminarModuloEnTapa($datos)
	{
		$spnombre="del_tap_modulos_xzonamodulocod";
		$sparam=array(
			'pzonamodulocod'=> $datos['zonamodulocod']
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al eliminar el Modulo por código.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	}	

	protected function ModificarModulo($datos)
	{
		$spnombre="upd_tap_modulos_xmodulocod";
		$sparam=array(
			'pcatcod'=> $datos['catcod'],
			'pmodulotipocod'=>$datos['modulotipocod'],
			'pmodulodesc'=> $datos['modulodesc'],
			'pmoduloarchivo'=> $datos['moduloarchivo'],
			'pmoduloestado'=> $datos['moduloestado'],
			'pmoduloaccesodirecto'=> $datos['moduloaccesodirecto'],
			'pmoduloicono'=> $datos['moduloicono'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			'pmodulocod'=> $datos['modulocod']
			);
			
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$query,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al modificar el modulo. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		return true;
	}



	protected function InsertarModulo($datos,&$codigoinsertado)
	{			
		$spnombre="ins_tap_modulos";
		$sparam=array(
			'pcatcod'=> $datos['catcod'],
			'pmodulotipocod'=>$datos['modulotipocod'],
			'pmodulodesc'=> $datos['modulodesc'],
			'pmoduloarchivo'=> $datos['moduloarchivo'],
			'pmoduloaccesodirecto'=> $datos['moduloaccesodirecto'],
			'pmoduloicono'=> $datos['moduloicono'],
			'pmoduloestado'=> $datos['moduloestado'],
			'pmoduloaccesodirecto'=> $datos['moduloaccesodirecto'],
			'pmoduloicono'=> $datos['moduloicono'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s")
			);				
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$query,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al insertar un nuevo modulo. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		$codigoinsertado=$this->conexion->UltimoCodigoInsertado();

		return true;
	}

	protected function ModificarEstadoModulo($datos)
	{
		$spnombre="upd_tap_modulos_modif_estado_xmodulocod";
		$sparam=array(
			'pmoduloestado'=> $datos['moduloestado'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pmodulocod'=> $datos['modulocod']
			);

		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$query,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al modificar el estado del  modulo. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		return true;	
	}
	

   protected function TapasModulosConfeccionarCategoriasSP(&$spnombre,&$sparam)
   {
	 	$spnombre="sel_tap_modulos_categorias";
		$sparam=array(
			);
	   return true;
   }
}
?>