<? 
abstract class cNoticiasdb
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
	protected function BuscarDatosCompletosxCodigo($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_not_noticias_datos_completos_xnoticiacod";
		$sparam=array(
			'pnoticiacod'=> $datos['noticiacod']
			);		
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar la noticia por código.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	}

	protected function BuscarDatosCompletosNoticiasPublicadasxCodigo($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_not_noticias_publicadas_datos_completos_xnoticiacod";
		$sparam=array(
			'pnoticiacod'=> $datos['noticiacod']
			);		
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar la noticia por código.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	}

	protected function BuscarxCodigo($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_not_noticias_xnoticiacod";
		$sparam=array(
			'pnoticiacod'=> $datos['noticiacod']
			);		
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar la noticia por código.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	}



	protected function TieneDuplicadoDb($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_not_noticias_xnoticiacopiacodorig";
		$sparam=array(
			'pnoticiacod'=> $datos['noticiacod']
			);	
				
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar la noticia por código original.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		return true;
	}
	
	protected function BusquedaAvanzada($datos,&$resultado,&$numfilas)
	{

		$spnombre="sel_not_noticias_busqueda";
		$sparam=array(
			'pestadonoticiacod'=> $datos['estadonoticiacod'],
			'pnoticiacod'=> $datos['noticiacod'],
			'pestadotitulo'=> $datos['estadotitulo'],
			'pnoticiatitulo'=> $datos['noticiatitulo'],
			'pestadofecha'=> $datos['estadofecha'],
			'pnoticiafecha'=> $datos['noticiafecha'],
			'pnoticiafecha2'=> $datos['noticiafecha2'],
			'pestadonoticiaestadocod'=> $datos['estadonoticiaestadocod'],
			'pestadonoticiacopiacod'=> $datos['estadonoticiacopiacod'],
			'pestadonoticiacopiacodorig'=> $datos['estadonoticiacopiacodorig'],
			'pnoticiaestadocod'=> $datos['noticiaestadocod'],
			'pxcatcod'=> $datos['xcatcod'],
			'pcatcod'=> $datos['catcod'],
			'pusuariocod'=> $datos['usuariocod'],
			'prolcod'=> $datos['rolcod'],
			'pxnoticiaestadocodbaja'=> $datos['xnoticiaestadocodbaja'],
			'pnoticiaestadocodbaja'=> $datos['noticiaestadocodbaja'],
			'porderby'=> $datos['orderby'],
			'plimit'=> $datos['limit']
			);	
			
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar las noticias.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	}

	protected function InsertarDB($datos,&$codigoinsertado)
	{


		$spnombre="ins_not_noticias";
		$sparam=array(
			'pcatcod'=> $datos['catcod'],
			'pnoticiatitulo'=> $datos['noticiatitulo'],
			'pnoticiatitulocorto'=> $datos['noticiatitulocorto'],
			'pnoticiahrefexterno'=> $datos['noticiahrefexterno'],
			'pnoticiacopete'=> $datos['noticiacopete'],
			'pnoticiacuerpo'=> $datos['noticiacuerpo'],
			'pnoticiacuerpoprocesado'=> $datos['noticiacuerpoprocesado'],
			'pnoticiavolanta'=> $datos['noticiavolanta'],
			'pnoticiaautor'=> $datos['noticiaautor'],
			'pnoticiafecha'=> $datos['noticiafecha'],
			'pnoticiaestadocod'=> $datos['noticiaestadocod'],
			'pnoticiabloqusuario'=> $datos['noticiabloqusuario'],
			'pnoticiacopiacodorig'=> $datos['noticiacopiacodorig'],
			'pnoticiacopiacod'=> $datos['noticiacopiacod'],
			'pnoticialat'=> $datos['noticialat'],
			'pnoticialng'=> $datos['noticialng'],
			'pnoticiazoom'=> $datos['noticiazoom'],
			'pnoticiatype'=> $datos['noticiatype'],
			'pnoticiadireccion'=> $datos['noticiadireccion'],
			'pnoticiamuestramapa'=> $datos['noticiamuestramapa'],	
			'pnoticiacomentarios'=> $datos['noticiacomentarios'],
			'pnoticiadestacada'=> $datos['noticiadestacada'],
			'pusuariodioalta'=> $datos['usuariodioalta'],
			'pnoticiafalta'=> $datos['noticiafalta'],
			'pnoticiafbaja'=> $datos['noticiafbaja'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s")
			);	
			
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno) && $numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al insertar la noticia.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		$codigoinsertado=$this->conexion->UltimoCodigoInsertado();
		return true;
	}
	
	protected function InsertarDuplicar($datos,&$codigoinsertado)
	{

		$spnombre="ins_not_noticias_noticiacod";
		$sparam=array(
			'pnoticiacod'=> $datos['noticiacod'],
			'pcatcod'=> $datos['catcod'],			
			'pnoticiatitulo'=> $datos['noticiatitulo'],
			'pnoticiatitulocorto'=> $datos['noticiatitulocorto'],
			'pnoticiahrefexterno'=> $datos['noticiahrefexterno'],
			'pnoticiacopete'=> $datos['noticiacopete'],
			'pnoticiacuerpo'=> $datos['noticiacuerpo'],
			'pnoticiavolanta'=> $datos['noticiavolanta'],
			'pnoticiaautor'=> $datos['noticiaautor'],
			'pnoticiafecha'=> $datos['noticiafecha'],
			'pnoticiaestadocod'=> $datos['noticiaestadocod'],
			'pnoticiabloqusuario'=> $datos['noticiabloqusuario'],
			'pnoticiacopiacodorig'=> $datos['noticiacopiacodorig'],
			'pnoticiacopiacod'=> $datos['noticiacopiacod'],
			'pnoticialat'=> $datos['noticialat'],
			'pnoticialng'=> $datos['noticialng'],
			'pnoticiazoom'=> $datos['noticiazoom'],
			'pnoticiatype'=> $datos['noticiatype'],
			'pnoticiadireccion'=> $datos['noticiadireccion'],
			'pnoticiamuestramapa'=> $datos['noticiamuestramapa'],
			'pusuariodioalta'=> $datos['usuariodioalta'],
			'pnoticiafalta'=> $datos['noticiafalta'],
			'pnoticiafbaja'=> $datos['noticiafbaja'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s")
			);	
		
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno) && $numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al insertar la noticia duplicada.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		$codigoinsertado=$this->conexion->UltimoCodigoInsertado();
		return true;
	}
	
	
	
	
	
	protected function ModificarDB($datos)
	{
		$spnombre="upd_not_noticias_xnoticiacod";
		$sparam=array(
			'pcatcod'=> $datos['catcod'],		
			'pnoticiatitulo'=> $datos['noticiatitulo'],
			'pnoticiatitulocorto'=> $datos['noticiatitulocorto'],
			'pnoticiahrefexterno'=> $datos['noticiahrefexterno'],
			'pnoticiacopete'=> $datos['noticiacopete'],
			'pnoticiacuerpo'=> $datos['noticiacuerpo'],
			'pnoticiacuerpoprocesado'=> $datos['noticiacuerpoprocesado'],
			'pnoticiavolanta'=> $datos['noticiavolanta'],
			'pnoticiaautor'=> $datos['noticiaautor'],
			'pnoticialat'=> $datos['noticialat'],
			'pnoticialng'=> $datos['noticialng'],
			'pnoticiazoom'=> $datos['noticiazoom'],
			'pnoticiatype'=> $datos['noticiatype'],
			'pnoticiadireccion'=> $datos['noticiadireccion'],
			'pnoticiamuestramapa'=> $datos['noticiamuestramapa'],
			'pnoticiacomentarios'=> $datos['noticiacomentarios'],
			'pnoticiadestacada'=> $datos['noticiadestacada'],
			'pnoticiafecha'=> $datos['noticiafecha'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			'pnoticiacod'=> $datos['noticiacod']
			);			
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al modificar la noticia.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		return true;
	}
	
	
	
	
	
	protected function ActualizarCodigo($datos)
	{

		$spnombre="upd_not_noticias_xnoticiacod_xnoticiacodorig";
		$sparam=array(
			'pnoticiacopiacodorig'=> $datos['noticiacopiacodorig'],
			'pnoticiacod'=> $datos['noticiacod']
			);		
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al modificar el codigo de noticia.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		return true;
	}	
	
	protected function BuscarNoticiasPublicadas($datos,&$resultado,&$numfilas)
	{

		$spnombre="sel_not_noticias_publicadas";
		$sparam=array(
			'pxcatcod'=> $datos['xcatcod'],
			'pcatcod'=> $datos['catcod'],		
			'porderby'=> $datos['orderby'],
			'plimit'=> $datos['limit']
			);		

		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al modificar el codigo de noticia.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		return true;
	}		
	
	
	
	protected function Eliminar($datos)
	{
		$spnombre="del_not_noticias_xnoticiacod";
		$sparam=array(
			'pnoticiacod'=> $datos['noticiacod']
			);			
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al eliminar la noticia.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		return true;
	}
	

	protected function ActualizarEstado($datos)
	{

		$spnombre="upd_not_noticias_noticiaestadocod_xnoticiacod";
		$sparam=array(
			'pnoticiaestadocod'=> $datos['noticiaestadocod'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			'pnoticiacod'=> $datos['noticiacod']
			);		
			
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al modificar el estado de la noticia.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		return true;
	}
	
	
	
	protected function ActualizarCopiaOriginal($datos,$codigonoticiacopia)
	{

		$spnombre="upd_not_noticias_xnoticiacod_noticiacopia";
		$sparam=array(
			'pnoticiacodcopia'=> $codigonoticiacopia,
			'pnoticiacod'=> $datos['noticiacod']
			);		
			
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al modificar el estado de la noticia.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		return true;
	}

}
?>