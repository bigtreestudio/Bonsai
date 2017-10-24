<?php  
abstract class cGaleriasMultimediadb
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

	protected function BuscarMultimediaxCodigoGaleriaxCodigoMultimedia($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_gal_galerias_mul_multimedia_xnoticiacod_multimediacod";
		$sparam=array(
			'pgaleriacod'=> $datos['galeriacod'],
			'pmultimediacod'=> $datos['multimediacod']
			);			
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno) )
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar el archivo multimedia por codigo de galeria y codigo de multimedia. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;	
	}



	protected function BuscarMultimediaxCodigoGaleriaxMultimediaConjunto($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_gal_galerias_mul_multimedia_xgaleriacod";
		$sparam=array(
			'pgaleriacod' => $datos['galeriacod'],
			);		
			
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno) )
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar los archivos multimedia de la galeria. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;	
	}



	protected function Insertar($datos)
	{

		$spnombre="ins_gal_galerias_mul_multimedia";
		$sparam=array(
			'pgaleriacod'=> $datos['galeriacod'],
			'pmultimediacod'=> $datos['multimediacod'],
			'pgalmultimediaorden'=> $datos['galmultimediaorden'],
			'pgalmultimediatitulo'=> $datos['galmultimediatitulo'],
			'pgalmultimediadesc'=> $datos['galmultimediadesc'],
			'pmultimediacodpreview'=> $datos['multimediacodpreview'],
			'pusuariodioalta'=> $datos['usuariodioalta'],
			'pgalmultimediafalta'=> $datos['galmultimediafalta'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s")
			);
		
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno) )
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al insertar el multimedia a la galeria. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;	
	}


	protected function Eliminar($datos)
	{
		$spnombre="del_gal_galerias_mul_multimedia_xgaleriacod_multimediacod";
		$sparam=array(
			'pgaleriacod'=> $datos['galeriacod'],
			'pmultimediacod'=> $datos['multimediacod']
			);

		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno) )
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al eliminar el multimedia de la galeria. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;	
	}


	protected function ModificarOrden($datos)
	{
			
		$spnombre="upd_gal_galerias_mul_multimedia_orden_xgaleriacod_multimediacod";
		$sparam=array(
			'pgalmultimediaorden'=> $datos['galmultimediaorden'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			'pgaleriacod'=> $datos['galeriacod'],
			'pmultimediacod'=> $datos['multimediacod']
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno) )
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al modificar el orden de los multimedias relacionados. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		return true;	
	}



	protected function ModificarPreview($datos)
	{
					
		$spnombre="upd_gal_galerias_mul_multimedia_preview_xgaleriacod_multimediacod";
		$sparam=array(
			'pmultimediacodpreview'=> $datos['multimediacodpreview'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			'pgaleriacod'=> $datos['galeriacod'],
			'pmultimediacod'=> $datos['multimediacod']
			);
	
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno) )
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al modificar el preview de un multimedia. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		return true;	
	}


	protected function ModificarTituloMultimedia($datos)
	{
			
		$spnombre="upd_gal_galerias_mul_multimedia_titulo_xgaleriacod_multimediacod";
		$sparam=array(
			'pgalmultimediatitulo'=> $datos['galmultimediatitulo'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			'pgaleriacod'=> $datos['galeriacod'],
			'pmultimediacod'=> $datos['multimediacod']
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno) )
		{
		FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al modificar el titulo del multimedia. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		return true;	
	}


	protected function ModificarDescripcionMultimedia($datos)
	{
			
		$spnombre="upd_gal_galerias_mul_multimedia_descripcion_xgaleriacod_multimediacod";
		$sparam=array(
			'pgalmultimediadesc'=> $datos['galmultimediadesc'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			'pgaleriacod'=> $datos['galeriacod'],
			'pmultimediacod'=> $datos['multimediacod']
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno) )
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al modificar la descripcion del multimedia. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		return true;	
	}




	protected function BuscarMultimediaUltimoOrdenxGaleria($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_gal_galerias_mul_multimedia_max_orden_xgaleriacod";
		$sparam=array(
			'pgaleriacod'=> $datos['galeriacod'],
			);	


		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar el proximo orden.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;	
	}


}
?>