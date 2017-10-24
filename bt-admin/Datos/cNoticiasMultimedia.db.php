<?php  
abstract class cNoticiasMultimediadb
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
	protected function BuscarMultimediaxCodigoNoticia($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_not_noticias_mul_multimedia_xnoticiacod";
		$sparam=array(
			'pnoticiacod'=> $datos['noticiacod'],
			);			
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno) )
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar el archivo multimedia por codigo de noticia. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;	
	}
	
	protected function BuscarMultimediaxCodigoNoticiaxCodigoMultimedia($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_not_noticias_mul_multimedia_xnoticiacod_multimediacod";
		$sparam=array(
			'pnoticiacod'=> $datos['noticiacod'],
			'pmultimediacod'=> $datos['multimediacod']
			);			
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno) )
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar el archivo multimedia por codigo de noticia y codigo de multimedia. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;	
	}



	protected function BuscarMultimediaxCodigoNoticiaxMultimediaConjunto($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_not_noticias_mul_multimedia_xnoticiacod_mulconjuntocod";
		$sparam=array(
			'pnoticiacod' => $datos['noticiacod'],
			'pmultimediaconjuntocod' => $datos['multimediaconjuntocod']
			);		
			
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno) )
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar los archivos multimedia de la noticia. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;	
	}
	
	
	protected function BuscarMultimediaxCodigoNoticiaxMinimoOrden($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_not_noticias_mul_multimedia_xnoticiacod_minimoorden";
		$sparam=array(
			'pnoticiacod' => $datos['noticiacod']
			);		
			
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno) )
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar los archivos multimedia de la noticia. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;	
	}




	protected function Insertar($datos)
	{

		$spnombre="ins_not_noticias_mul_multimedia";
		$sparam=array(
			'pnoticiacod'=> $datos['noticiacod'],
			'pmultimediaconjuntocod'=> $datos['multimediaconjuntocod'],
			'pmultimediacod'=> $datos['multimediacod'],
			'pnotmultimediatitulo'=> $datos['notmultimediatitulo'],
			'pnotmultimediadesc'=> $datos['notmultimediadesc'],
			'pnotmultimediaorden'=> $datos['notmultimediaorden'],
			'pnotmultimediamuestrahome'=> $datos['notmultimediamuestrahome'],
			'pmultimediacodpreview'=> $datos['multimediacodpreview'],
			'pusuariodioalta'=> $datos['usuariodioalta'],
			'pnotmultimediafalta'=> $datos['notmultimediafalta'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s")
			);
		
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno) )
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al insertar el multimedia a la noticia. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;	
	}



	protected function Eliminar($datos)
	{
		$spnombre="del_not_noticias_mul_multimedia_xnoticiacod_multimediacod";
		$sparam=array(
			'pnoticiacod'=> $datos['noticiacod'],
			'pmultimediacod'=> $datos['multimediacod']
			);

		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno) )
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al eliminar el multimedia de la noticia. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;	
	}

	protected function EliminarCompletoxNoticiacod($datos)
	{
		$spnombre="del_not_noticias_mul_multimedia_xnoticiacod";
		$sparam=array(
			'pnoticiacod'=> $datos['noticiacod']
			);

		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno) )
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al eliminar el multimedia de la noticia. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;	
	}	
	
	protected function ModificarOrden($datos)
	{
			
		$spnombre="upd_not_noticias_mul_multimedia_orden_xnoticiacod_multimediacod";
		$sparam=array(
			'pnotmultimediaorden'=> $datos['notmultimediaorden'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			'pnoticiacod'=> $datos['noticiacod'],
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
					
		$spnombre="upd_not_noticias_mul_multimedia_preview_xnoticiacod_multimediacod";
		$sparam=array(
			'pmultimediacodpreview'=> $datos['multimediacodpreview'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			'pnoticiacod'=> $datos['noticiacod'],
			'pmultimediacod'=> $datos['multimediacod']
			);
	
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno) )
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al modificar el preview de un multimedia. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		return true;	
	}





	protected function BuscarMultimediaUltimoOrdenxNoticiaxConjunto($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_not_noticias_mul_multimedia_max_orden_xnoticiacod_multimediaconjuntocod";
		$sparam=array(
			'pnoticiacod'=> $datos['noticiacod'],
			'pmultimediaconjuntocod'=> $datos['multimediaconjuntocod']
			);	


		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar el proximo orden.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;	
	}


	protected function ModificarHomeMultimedia($datos)
	{
			
		$spnombre="upd_not_noticias_mul_multimedia_notmultimediamuestrahome_xnoticiacod_multimediacod";
		$sparam=array(
			'pnotmultimediamuestrahome'=> $datos['notmultimediamuestrahome'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			'pnoticiacod'=> $datos['noticiacod'],
			'pmultimediacod'=> $datos['multimediacod']
			);

		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno) )
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al modificar si la imagen es de home. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		return true;	
	}



	protected function ModificarTituloMultimedia($datos)
	{
			
		$spnombre="upd_not_noticias_mul_multimedia_titulo_xnoticiacod_multimediacod";
		$sparam=array(
			'pnotmultimediatitulo'=> $datos['notmultimediatitulo'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			'pnoticiacod'=> $datos['noticiacod'],
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
			
		$spnombre="upd_not_noticias_mul_multimedia_descripcion_xnoticiacod_multimediacod";
		$sparam=array(
			'pnotmultimediadesc'=> $datos['notmultimediadesc'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			'pnoticiacod'=> $datos['noticiacod'],
			'pmultimediacod'=> $datos['multimediacod']
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno) )
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al modificar la descripcion del multimedia. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		return true;	
	}






}
?>