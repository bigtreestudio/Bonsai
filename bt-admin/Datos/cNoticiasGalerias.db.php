<?php  
abstract class cNoticiasGaleriasdb
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

	protected function BuscarGaleriasRelacionadasxNoticia($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_gal_galerias_relacionadas_xcodigonoticia";
		$sparam=array(
			'pnoticiacod'=> $datos['noticiacod']
			);		
			
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno) )
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar las galerias relacionadas. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;	
	}

	protected function BuscarGaleriasRelacionadasxCodigo($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_not_noticias_gal_galerias_xnoticiacod_noticiacodrel";
		$sparam=array(
			'pnoticiacod'=> $datos['noticiacod'],
			'pgaleriacod'=> $datos['galeriacod']
			);			
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno) )
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar las galerias relacionadas. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;	
	}



	protected function BuscarGaleriaUltimoOrden($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_not_noticias_gal_galerias_maxorden_xnoticiacod";
		$sparam=array(
			'pnoticiacod'=> $datos['noticiacod']
			);	
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar el proximo orden.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;	
	}





	protected function Insertar($datos)
	{
		$spnombre="ins_not_noticias_gal_galerias";
		$sparam=array(
			'pnoticiacod'=> $datos['noticiacod'],
			'pgaleriacod'=> $datos['galeriacod'],
			'pgaleriaorden'=> $datos['galeriaorden'],
			'pgaleriaimportante'=> $datos['galeriaimportante'],
			'pusuariodioalta'=> $datos['usuariodioalta'],
			'pgaleriafalta'=> $datos['galeriafalta'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s")
			);
		
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno) )
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al insertar la galeria relacionada. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;	
	}


	protected function Eliminar($datos)
	{
		$spnombre="del_not_noticias_gal_galerias_xnoticiacod_galeriacod";
		$sparam=array(
			'pnoticiacod'=> $datos['noticiacod'],
			'pgaleriacod'=> $datos['galeriacod']
			);	
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno) )
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al eliminar la galeria relacionada. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;	
	}

	protected function EliminarCompletoxNoticiacod($datos)
	{
		$spnombre="del_not_noticias_gal_galerias_xnoticiacod";
		$sparam=array(
			'pnoticiacod'=> $datos['noticiacod']
			);	
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno) )
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al eliminar la galeria relacionada. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;	
	}
	
	protected function ModificarOrden($datos)
	{
			
		$spnombre="upd_not_noticias_gal_galerias_orden_xnoticiacod_galeriacod";
		$sparam=array(
			'pgaleriaorden'=> $datos['galeriaorden'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			'pnoticiacod'=> $datos['noticiacod'],
			'pgaleriacod'=> $datos['galeriacod']
			);

		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno) )
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al modificar el orden de las galerias relacionadas. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		return true;	
	}

	protected function ModificarImportancia($datos)
	{
			
		$spnombre="upd_not_noticias_gal_galerias_importancia_xnoticiacod_galeriacod";
		$sparam=array(
			'pgaleriaimportante'=> $datos['galeriaimportante'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			'pnoticiacod'=> $datos['noticiacod'],
			'pgaleriacod'=> $datos['galeriacod']
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno) )
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al modificar el orden de las noticias relacionadas. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		return true;	
	}


}
?>