<?php  
abstract class cNoticiasNoticiasTemasdb
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

//----------------------------------------------------------------------------------------- 
// Buscar las temas por un codigo de una noticia

// Parámetros de Entrada:
//		noticiacod= codigo del tramite

// Retorna:
//		spnombre,spparam
//		la función retorna true o false si se pudo ejecutar con éxito o no

	
	protected function BuscarxNoticia($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_not_noticias_temas_xnoticiacod";
		$sparam=array(
			'pnoticiacod'=> $datos['noticiacod'],
			'ptemaestado'=> $datos['temaestado']
			);

		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al obtener los temas por noticia. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		return true;
	
	}
	
	protected function BuscarxCodigoNoticia($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_not_noticias_temas_x_noticiacod";
		$sparam=array(
			'pnoticiacod'=> $datos['noticiacod']
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al obtener el tema relacionado a la noticia. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		return true;
	
	}


	protected function BuscarxCodigo($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_not_noticias_temas_xnoticiacod_temacod";
		$sparam=array(
			'pnoticiacod'=> $datos['noticiacod'],
			'ptemacod'=> $datos['temacod']
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al obtener el tema relacionado a la noticia. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		return true;
	
	}
	
	protected function BuscarxTema($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_not_noticias_temas_xtemacod";
		$sparam=array(
			'ptemacod'=> $datos['temacod']
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al obtener la noticia por código de tema. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		return true;
	
	}


	protected function Insertar($datos)
	{
		$spnombre="ins_not_noticias_temas";
		$sparam=array(
			'pnoticiacod'=> $datos['noticiacod'],
			'ptemacod'=> $datos['temacod'],
			'pultmodusuario'=> $_SESSION['usuariocod'],
			'pultmodfecha'=> date("Y/m/d H:i:s")
			);

		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$query,$numfilas,$errno) || $numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al insertar un tema a la noticia. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}


		return true;
	}
	

	protected function Eliminar($datos)
	{
		$spnombre="del_not_noticias_temas_xnoticiacod_temacod";
		$sparam=array(
			'pnoticiacod'=> $datos['noticiacod'],
			'ptemacod'=> $datos['temacod']
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$query,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al eliminar el tema de la noticia. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		return true;
	}


	protected function EliminarxNoticia($datos)
	{
		$spnombre="del_tra_tramites_temas_xtramitecod";
		$sparam=array(
			'ptramitecod'=> $datos['tramitecod']
			);
	
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$query,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al eliminar el tema del tramite. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		return true;
	}

	protected function EliminarCompletoxNoticiacod($datos)
	{
		$spnombre="del_not_noticias_temas_xnoticiacod";
		$sparam=array(
			'pnoticiacod'=> $datos['noticiacod']
			);

		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno) )
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al eliminar el tema de la noticia. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;	
	}



}


?>