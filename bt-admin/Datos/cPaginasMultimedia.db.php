<?php  
abstract class cPaginasMultimediadb
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
	protected function BuscarMultimediaxCodigoPagina($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_pag_paginas_mul_multimedia_xnoticiacod";
		$sparam=array(
			'ppagcod'=> $datos['pagcod'],
			);			
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno) )
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar el archivo multimedia por codigo de la pagina. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;	
	}
	
	protected function BuscarMultimediaxCodigoPaginaxCodigoMultimedia($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_pag_paginas_mul_multimedia_xpagcod_multimediacod";
		$sparam=array(
			'ppagcod'=> $datos['pagcod'],
			'pmultimediacod'=> $datos['multimediacod']
			);			
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno) )
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar el archivo multimedia por codigo de pagina y codigo de multimedia. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;	
	}



	protected function BuscarMultimediaxCodigoPaginaxMultimediaConjunto($datos,&$resultado,&$numfilas)
	{
		

		$spnombre="sel_pag_paginas_mul_multimedia_xpagcod_mulconjuntocod";
		$sparam=array(
			'ppagcod' => $datos['pagcod'],
			'pmultimediaconjuntocod' => $datos['multimediaconjuntocod']
			);		
			
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno) )
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar los archivos multimedia de la pagina. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;	
	}

	protected function EliminarCompletoxPaginacod($datos)
	{
		$spnombre="del_pag_paginas_mul_multimedia_xpagcod";
		$sparam=array(
			'ppagcod'=> $datos['pagcod']
			);

		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno) )
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al eliminar el multimedia de la pagina. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;	
	}	

	protected function ModificarOrden($datos)
	{
			
		$spnombre="upd_pag_paginas_mul_multimedia_orden_xpagcod_multimediacod";
		$sparam=array(
			'ppagmultimediaorden'=> $datos['pagmultimediaorden'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			'ppagcod'=> $datos['pagcod'],
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
					
		$spnombre="upd_pag_paginas_mul_multimedia_preview_xpagcod_multimediacod";
		$sparam=array(
			'pmultimediacodpreview'=> $datos['multimediacodpreview'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			'ppagcod'=> $datos['pagcod'],
			'pmultimediacod'=> $datos['multimediacod']
			);
	
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno) )
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al modificar el preview de un multimedia. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		return true;	
	}

	protected function Insertar($datos)
	{

		$spnombre="ins_pag_paginas_mul_multimedia";
		$sparam=array(
			'ppagcod'=> $datos['pagcod'],
			'pmultimediaconjuntocod'=> $datos['multimediaconjuntocod'],
			'pmultimediacod'=> $datos['multimediacod'],
			'ppagmultimediatitulo'=> $datos['pagmultimediatitulo'],
			'ppagmultimediadesc'=> $datos['pagmultimediadesc'],
			'ppagmultimediaorden'=> $datos['pagmultimediaorden'],
			'pmultimediacodpreview'=> $datos['multimediacodpreview'],
			'pusuariodioalta'=> $datos['usuariodioalta'],
			'ppagmultimediafalta'=> $datos['pagmultimediafalta'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s")
			);
		
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno) )
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al insertar el multimedia a la pagina. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;	
	}



	protected function Eliminar($datos)
	{
		$spnombre="del_pag_paginas_mul_multimedia_xpagcod_multimediacod";
		$sparam=array(
			'ppagcod'=> $datos['pagcod'],
			'pmultimediacod'=> $datos['multimediacod']
			);

		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno) )
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al eliminar el multimedia de la pagina. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;	
	}


	





	protected function BuscarMultimediaUltimoOrdenxPaginaxConjunto($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_pag_paginas_mul_multimedia_max_orden_xpagcod_multimediaconjuntocod";
		$sparam=array(
			'ppagcod'=> $datos['pagcod'],
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
			
		$spnombre="upd_pag_paginas_mul_multimedia_pagmultimediamuestrahome_xpagcod_multimediacod";
		$sparam=array(
			'ppagmultimediamuestrahome'=> $datos['pagmultimediamuestrahome'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			'ppagcod'=> $datos['pagcod'],
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
			
		$spnombre="upd_pag_paginas_mul_multimedia_titulo_xpagcod_multimediacod";
		$sparam=array(
			'ppagmultimediatitulo'=> $datos['pagmultimediatitulo'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			'ppagcod'=> $datos['pagcod'],
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
			
		$spnombre="upd_pag_paginas_mul_multimedia_multimediadesc_xpagcod_multimediacod";
		$sparam=array(
			'ppagmultimediadesc'=> $datos['pagmultimediadesc'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			'ppagcod'=> $datos['pagcod'],
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