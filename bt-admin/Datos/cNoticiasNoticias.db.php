<?php  
abstract class cNoticiasNoticiasdb
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

	protected function BuscarNoticiasRelacionadasxNoticia($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_not_noticias_relacionadas_xcodigonoticia";
		$sparam=array(
			'pnoticiacod'=> $datos['noticiacod'],
			'pxnoticiaestadocod'=> $datos['xnoticiaestadocod'],
			'pnoticiaestadocod'=> $datos['noticiaestadocod']
			);		
			
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno) )
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar las noticias relacionadas. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;	
	}
	
	protected function BuscarNoticiasRelacionadasPublicadasxNoticia($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_not_noticias_publicadas_relacionadas_xcodigonoticia";
		$sparam=array(
			'pnoticiacod'=> $datos['noticiacod'],
			);		
			
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno) )
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar las noticias relacionadas. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;	
	}	
	protected function BuscarNoticiasRelacionadasxCodigoNoticia($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_not_noticias_not_noticias_relacionadas_xcodigonoticia";
		$sparam=array(
			'pnoticiacod'=> $datos['noticiacod']
			);		
			
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno) )
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar las noticias relacionadas. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;	
	}
	protected function BuscarNoticiasRelacionadasxCodigo($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_not_noticias_not_noticias_xnoticiacod_noticiacodrel";
		$sparam=array(
			'pnoticiacod'=> $datos['noticiacod'],
			'pnoticiacodrel'=> $datos['noticiacodrel']
			);			
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno) )
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar las noticias relacionadas. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;	
	}



	protected function BuscarNoticiaUltimoOrden($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_not_noticias_not_noticias_maxorden_xnoticiacod";
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
		$spnombre="ins_not_noticias_not_noticias";
		$sparam=array(
			'pnoticiacod'=> $datos['noticiacod'],
			'pnoticiacodrel'=> $datos['noticiacodrel'],
			'pnoticiaorden'=> $datos['noticiaorden'],
			'pnoticiaimportante'=> $datos['noticiaimportante'],
			'pusuariodioalta'=> $datos['usuariodioalta'],
			'pnoticiafalta'=> $datos['noticiafalta'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s")
			);
		
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno) )
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al insertar la noticia relacionada. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;	
	}


	protected function Eliminar($datos)
	{
		$spnombre="del_not_noticias_not_noticias_xnoticiacod_noticiacodrel";
		$sparam=array(
			'pnoticiacod'=> $datos['noticiacod'],
			'pnoticiacodrel'=> $datos['noticiacodrel']
			);		
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno) )
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al eliminar la noticia relacionada. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;	
	}
	protected function EliminarCompletoxNoticiacod($datos)
	{
		$spnombre="del_not_noticias_not_noticias_xnoticiacod";
		$sparam=array(
			'pnoticiacod'=> $datos['noticiacod']
			);		
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno) )
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al eliminar la noticia relacionada. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;	
	}

	protected function ModificarOrden($datos)
	{
			
		$spnombre="upd_not_noticias_not_noticias_orden_xnoticiacod_noticiacodrel";
		$sparam=array(
			'pnoticiaorden'=> $datos['noticiaorden'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			'pnoticiacod'=> $datos['noticiacod'],
			'pnoticiacodrel'=> $datos['noticiacodrel']
			);

		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno) )
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al modificar el orden de las noticias relacionadas. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		return true;	
	}

	protected function ModificarImportancia($datos)
	{
			
		$spnombre="upd_not_noticias_not_noticias_importancia_xnoticiacod_noticiacodrel";
		$sparam=array(
			'pnoticiaimportante'=> $datos['noticiaimportante'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s"),
			'pnoticiacod'=> $datos['noticiacod'],
			'pnoticiacodrel'=> $datos['noticiacodrel']
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