<?php  
abstract class cAgendaMultimediadb
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
	protected function BuscarMultimediaxCodigoEvento($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_age_agenda_mul_multimedia_xagendacod";
		$sparam=array(
			'pagendacod'=> $datos['agendacod'],
			);			
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno) )
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar el archivo multimedia por codigo de evento. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;	
	}
	
	protected function BuscarMultimediaxCodigoEventoxCodigoMultimedia($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_age_agenda_mul_multimedia_xagendacod_multimediacod";
		$sparam=array(
			'pagendacod'=> $datos['agendacod'],
			'pmultimediacod'=> $datos['multimediacod']
			);			
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno) )
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar el archivo multimedia por codigo de evento y codigo de multimedia. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;	
	}



	protected function BuscarMultimediaxCodigoEventoxMultimediaConjunto($datos,&$resultado,&$numfilas)
	{
		

		$spnombre="sel_age_agenda_mul_multimedia_xagendacod_mulconjuntocod";
		$sparam=array(
			'pagendacod' => $datos['agendacod'],
			'pmultimediaconjuntocod' => $datos['multimediaconjuntocod']
			);		
			
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno) )
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar los archivos multimedia del evento. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;	
	}

	protected function EliminarCompletoxEventocod($datos)
	{
		$spnombre="del_age_agenda_mul_multimedia_xagendacod";
		$sparam=array(
			'pagendacod'=> $datos['agendacod']
			);

		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno) )
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al eliminar el multimedia del evento. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;	
	}	

	protected function ModificarOrden($datos)
	{
			
		$spnombre="upd_age_agenda_mul_multimediaorden_xagendacodmultimediacod";
		$sparam=array(
			'pagemultimediaorden'=> $datos['agemultimediaorden'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			'pagendacod'=> $datos['agendacod'],
			'pmultimediacod'=> $datos['multimediacod']
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno) )
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al modificar el orden de los multimedias relacionados. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		return true;	
	}



	protected function Insertar($datos)
	{
		$spnombre="ins_age_agenda_mul_multimedia";
		$sparam=array(
			'pagendacod'=> $datos['agendacod'],
			'pmultimediaconjuntocod'=> $datos['multimediaconjuntocod'],
			'pmultimediacod'=> $datos['multimediacod'],
			'pagemultimediatitulo'=> $datos['agemultimediatitulo'],
			'pagemultimediadesc'=> $datos['agemultimediadesc'],
			'pagemultimediaorden'=> $datos['agemultimediaorden'],
			'pmultimediacodpreview'=> $datos['multimediacodpreview'],
			'pusuariodioalta'=> $_SESSION['usuariocod'],
			'pagemultimediafalta'=> date("Y/m/d H:i:s"),
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s")
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno) )
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al insertar el multimedia al evento. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;	
	}

	protected function ModificarPreview($datos)
	{
					
		$spnombre="upd_age_agenda_mul_multimedia_preview_xagendacod_multimediacod";
		$sparam=array(
			'pmultimediacodpreview'=> $datos['multimediacodpreview'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			'pagendacod'=> $datos['agendacod'],
			'pmultimediacod'=> $datos['multimediacod']
			);
	
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno) )
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al modificar el preview de un multimedia. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		return true;	
	}

	protected function Eliminar($datos)
	{
		$spnombre="del_age_agenda_mul_multimedia_xagendacod_multimediacod";
		$sparam=array(
			'pagendacod'=> $datos['agendacod'],
			'pmultimediacod'=> $datos['multimediacod']
			);

		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno) )
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al eliminar el multimedia del evento. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;	
	}




	protected function BuscarMultimediaUltimoOrdenxEventoxConjunto($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_age_agenda_mul_multimedia_max_orden_xagendacod_multimediaconjuntocod";
		$sparam=array(
			'pagendacod'=> $datos['agendacod'],
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
			
		$spnombre="upd_age_agenda_mul_multimedia_agemultimediamuestrahome_xagendacod_multimediacod";
		$sparam=array(
			'pagemultimediamuestrahome'=> $datos['agemultimediamuestrahome'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			'pagendacod'=> $datos['agendacod'],
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
		$spnombre="upd_age_agenda_mul_multimedia_agemultimediatitulo_xagendacod_multimediacod";
		$sparam=array(
			'pagemultimediatitulo'=> $datos['agemultimediatitulo'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			'pagendacod'=> $datos['agendacod'],
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
			
		$spnombre="upd_age_agenda_mul_multimedia_agemultimediadesc_xagendacod_multimediacod";
		$sparam=array(
			'pagemultimediadesc'=> $datos['agemultimediadesc'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			'pagendacod'=> $datos['agendacod'],
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