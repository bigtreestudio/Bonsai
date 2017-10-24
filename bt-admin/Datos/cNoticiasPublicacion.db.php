<? 
abstract class cNoticiasPublicaciondb
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

	protected function EsNoticiaPublicada($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_not_noticias_publicadas_xnoticiacod";
		$sparam=array(
			'pnoticiacod'=> $datos['noticiacod']
			);		
			
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno) )
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar las noticias publicadas. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;	
	}
	
	
	
	protected function BuscarNoticiasGoogleNews(&$resultado,&$numfilas)
	{
		$spnombre="sel_not_noticias_publicadas_googlenews";
		$sparam=array(
			);		
			
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno) )
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar las noticias publicadas para googlenews. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;	
	}
	
	
	
	
	protected function BusquedaAvanzada($datos,&$resultado,&$numfilas)
	{

		$spnombre="sel_not_noticias_publicadas_xbusqueda_avanzada";
		$sparam=array(
			'pestadotitulo'=> $datos['estadotitulo'],
			'pnoticiatitulo'=> $datos['noticiatitulo'],
			'pestadofecha'=> $datos['estadofecha'],
			'pnoticiafecha'=> $datos['noticiafecha'],
			'pnoticiafecha2'=> $datos['noticiafecha2'],
			'pxcatcod'=> $datos['xcatcod'],
			'pcatcod'=> $datos['catcod'],
			'pxnoticiadestacada'=> $datos['xnoticiadestacada'],
			'pnoticiadestacada'=> $datos['noticiadestacada'],
			'porderby'=> $datos['orderby'],
			'plimit'=> $datos['limit']
			);	
			
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar las noticias PUBLICADAS.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	}
	
		protected function BuscarNoticiaxCodigo($datos,&$resultado,&$numfilas)
	{

		$spnombre="sel_not_noticias_publicadas_xcodigo";
		$sparam=array(
			'pnoticiacod'=> $datos['noticiacod'],

			);	
			
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar las noticias PUBLICADAS.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	}
	
	protected function Eliminar($datos)
	{
		$spnombre="del_not_noticias_publicadas_xnoticiacod";
		$sparam=array(
			'pnoticiacod'=> $datos['noticiacod']
			);	
			
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno) )
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al eliminar la noticia publicada. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;	
	}	


	protected function Insertar($datos,&$codigoinsertado)
	{
	
		$spnombre="ins_not_noticias_publicadas";
		$sparam=array(
			'pnoticiacod'=> $datos['noticiacod'],
			'pcatnom'=> $datos['catnom'],
			'pcatdominio'=> $datos['catdominio'],
			'pcatcod'=> $datos['catcod'],			
			'pnoticiadominio'=> $datos['noticiadominio'],
			'pnoticiatitulo'=> $datos['noticiatitulo'],
			'pnoticiatitulocorto'=> $datos['noticiatitulocorto'],
			'pnoticiahrefexterno'=> $datos['noticiahrefexterno'],
			'pnoticiacopete'=> $datos['noticiacopete'],
			'pnoticiacuerpo'=> $datos['noticiacuerpo'],
			'pnoticiacuerpoprocesado'=> $datos['noticiacuerpoprocesado'],
			'pnoticiavolanta'=> $datos['noticiavolanta'],
			'pnoticiaautor'=> $datos['noticiaautor'],
			'pnoticiatags'=> $datos['noticiatags'],
			'pnoticiafecha'=> $datos['noticiafecha'],
			'pnoticialat'=> $datos['noticialat'],
			'pnoticialng'=> $datos['noticialng'],
			'pnoticiazoom'=> $datos['noticiazoom'],
			'pnoticiatype'=> $datos['noticiatype'],
			'pnoticiadireccion'=> $datos['noticiadireccion'],
			'pnoticiamuestramapa'=> $datos['noticiamuestramapa'],			
			'pnoticiacomentarios'=> $datos['noticiacomentarios'],
			'pnoticiadestacada'=> $datos['noticiadestacada'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s")
			);

		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno) && $numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al publicar la noticia.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		$codigoinsertado=$this->conexion->UltimoCodigoInsertado();
		return true;
	}



	protected function ModificarPublicacion($datos)
	{
			
		$spnombre="upd_not_noticias_publicadas_xnoticiacod";
		$sparam=array(
			'pcatnom'=> $datos['catnom'],
			'pcatdominio'=> $datos['catdominio'],
			'pcatcod'=> $datos['catcod'],
			'pnoticiatitulo'=> $datos['noticiatitulo'],
			'pnoticiatitulocorto'=> $datos['noticiatitulocorto'],
			'pnoticiahrefexterno'=> $datos['noticiahrefexterno'],
			'pnoticiacopete'=> $datos['noticiacopete'],
			'pnoticiacuerpo'=> $datos['noticiacuerpo'],
			'pnoticiacuerpoprocesado'=> $datos['noticiacuerpoprocesado'],
			'pnoticiavolanta'=> $datos['noticiavolanta'],
			'pnoticiaautor'=> $datos['noticiaautor'],
			'pnoticiatags'=> $datos['noticiatags'],
			'pnoticiafecha'=> $datos['noticiafecha'],
			'pnoticialat'=> $datos['noticialat'],
			'pnoticialng'=> $datos['noticialng'],
			'pnoticiazoom'=> $datos['noticiazoom'],
			'pnoticiatype'=> $datos['noticiatype'],
			'pnoticiadireccion'=> $datos['noticiadireccion'],
			'pnoticiamuestramapa'=> $datos['noticiamuestramapa'],			
			'pnoticiacomentarios'=> $datos['noticiacomentarios'],
			'pnoticiadestacada'=> $datos['noticiadestacada'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			'pnoticiacod'=> $datos['noticiacod']
			);			
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno) && $numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al modificar una noticia online.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	}

	protected function BusquedaAvanzadaMultimediaEstadisticas($datos,&$resultado,&$numfilas)
	{

		$spnombre="sel_not_noticias_multimedia_estadisticas";
		$sparam=array(
			'pmultimediaconjuntocod'=> $datos['multimediaconjuntocod'],
			'plimit'=> $datos['limit']
			);	
			
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar las noticias.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	}

}
?>